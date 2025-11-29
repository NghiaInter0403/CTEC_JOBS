<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}

include 'ketnoi.php';
$employer_id = $_SESSION['user_id'];

/* ===================== XỬ LÝ XÓA ===================== */
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $sql_check = "SELECT duv.id FROM donungvien duv 
                  JOIN vieclam vl ON duv.idvieclam = vl.id 
                  WHERE duv.id='$delete_id' AND vl.idnhatuyendung='$employer_id'";

    $res_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($res_check) > 0) {
        mysqli_query($conn, "DELETE FROM donungvien WHERE id='$delete_id'");
        $success = "Xóa ứng viên thành công!";
    } else {
        $error = "Không thể xóa ứng viên này.";
    }
}

/* ===================== XỬ LÝ ĐỒNG Ý ===================== */
if (isset($_GET['accept']) && is_numeric($_GET['accept'])) {
    $don_id = $_GET['accept'];

    $sql_check = "SELECT duv.id FROM donungvien duv 
                  JOIN vieclam vl ON duv.idvieclam = vl.id 
                  WHERE duv.id='$don_id' AND vl.idnhatuyendung='$employer_id'";

    $res_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($res_check) > 0) {
        mysqli_query($conn, "UPDATE donungvien SET trangthai='chapnhan' WHERE id='$don_id'");
        $success = "Đã đồng ý hồ sơ!";
    } else {
        $error = "Không có quyền duyệt hồ sơ này.";
    }
}

/* ===================== XỬ LÝ TỪ CHỐI ===================== */
if (isset($_GET['deny']) && is_numeric($_GET['deny'])) {
    $don_id = $_GET['deny'];

    $sql_check = "SELECT duv.id FROM donungvien duv 
                  JOIN vieclam vl ON duv.idvieclam = vl.id 
                  WHERE duv.id='$don_id' AND vl.idnhatuyendung='$employer_id'";

    $res_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($res_check) > 0) {
        mysqli_query($conn, "UPDATE donungvien SET trangthai='tuchoi' WHERE id='$don_id'");
        $success = "Đã từ chối hồ sơ!";
    } else {
        $error = "Không có quyền từ chối hồ sơ này.";
    }
}

/* ===================== LẤY DANH SÁCH ỨNG VIÊN ===================== */
$sql = "SELECT duv.*, duv.id as don_id, vl.tieude AS job_title, sv.hoten AS student_name, 
        hs.duongdancv, hs.sodienthoai, hs.diachi, hs.kynang
        FROM donungvien duv
        JOIN vieclam vl ON duv.idvieclam = vl.id
        JOIN nguoidung sv ON duv.idsinhvien = sv.id
        LEFT JOIN hosoungvien hs ON sv.id = hs.idnguoidung
        WHERE vl.idnhatuyendung = '$employer_id'
        ORDER BY duv.ngaynop DESC";

$result = mysqli_query($conn, $sql);
?>

<?php include 'include_header.php'; ?>

<div class="container mt-4">
    <h3>Danh sách ứng viên</h3>

    <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <?php if(mysqli_num_rows($result) > 0): ?>
        <div class="list-group">
            <?php while($app = mysqli_fetch_assoc($result)): ?>
                <div class="list-group-item mb-3 p-3">

                <div class="row">
                    <!-- Hiển thị thông tin -->
                    <div class="col-md-6">
                        <h5><?php echo htmlspecialchars($app['student_name']); ?></h5>

                        <p><strong>Ứng tuyển vào:</strong> <?php echo htmlspecialchars($app['job_title']); ?></p>
                        <p><strong>Ngày nộp:</strong> 
                            <?php echo date('d/m/Y H:i', strtotime($app['ngaynop'])); ?>
                        </p>

                        <p>
                            <strong>SĐT:</strong> <?php echo htmlspecialchars($app['sodienthoai'] ?? '-'); ?><br>
                            <strong>Địa chỉ:</strong> <?php echo htmlspecialchars($app['diachi'] ?? '-'); ?><br>
                            <strong>Kỹ năng:</strong> <?php echo htmlspecialchars($app['kynang'] ?? '-'); ?>
                        </p>

                        <!--Hiển thị trạng thái -->
                        <span class="badge bg-<?php 
                            echo $app['trangthai']=='choxuly'?'warning':
                                ($app['trangthai']=='chapnhan'?'success':'danger'); ?>">
                            <?php echo ucfirst($app['trangthai'] == 'choxuly'?'Chờ xử lý':
                            ($app['trangthai']=='chapnhan'?'Chấp nhận':'Từ chối')); ?>
                        </span>
                    </div>

                    <!-- Hiển thị CV -->
                    <div class="col-md-6 text-center">
                        <?php if (!empty($app['duongdancv'])): ?>
                            <a href="<?php echo $app['duongdancv']; ?>" target="_blank">
                                <img src="<?= $app['duongdancv'] ?>" 
                                    alt="Hình ảnh đính kèm"
                                    class="img-fluid border rounded shadow-sm"
                                    style="max-height: 260px;">
                            </a>
                        <?php else: ?>
                            <p class="text-muted">Không có CV đính kèm</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- CÁC NÚT XỬ LÝ BÊN DƯỚI -->
                <div class="mt-3 text-center">

                    <?php if ($app['trangthai'] == 'choxuly'): ?>

                        <a href="cty_ungvien.php?accept=<?= $app['don_id']; ?>" 
                        class="btn btn-success btn-sm px-3 me-2">
                        Đồng ý
                        </a>

                        <a href="cty_ungvien.php?deny=<?= $app['don_id']; ?>" 
                        class="btn btn-warning btn-sm px-3 me-2">
                        Từ chối
                        </a>

                    <?php endif; ?>

                    <a href="cty_ungvien.php?delete=<?= $app['don_id']; ?>" 
                    onclick="return confirm('Bạn có chắc chắn muốn xóa ứng viên này?')"
                    class="btn btn-danger btn-sm px-3">
                    Xóa
                    </a>

                </div>

            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">Chưa có ứng viên nào ứng tuyển vào tin tuyển dụng của bạn.</p>
    <?php endif; ?>
</div>

<?php include 'include_footer.php'; ?>
