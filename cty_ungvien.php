<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$id_nhatuyendung = $_SESSION['id_nguoidung'];

//XỬ LÝ XÓA
if (isset($_GET['xoa']) && is_numeric($_GET['xoa'])) {
    $xoa_id = $_GET['xoa'];

   $kiemtra_xoa = "SELECT duv.id FROM donungvien duv 
                  JOIN vieclam vl ON duv.idvieclam = vl.id 
                  WHERE duv.id='$xoa_id' AND vl.idnhatuyendung='$id_nhatuyendung'";

    $thuchien_xoa = mysqli_query($conn, $kiemtra_xoa);

    if (mysqli_num_rows($thuchien_xoa) > 0) {
        mysqli_query($conn, "DELETE FROM donungvien WHERE id='$xoa_id'");
        $thanhcong = "Xóa ứng viên thành công!";
    } else {
        $thatbai = "Không thể xóa ứng viên này.";
    }
}

// XỬ LÝ ĐỒNG Ý
if (isset($_GET['dongy']) && is_numeric($_GET['dongy'])) {
    $don_id = $_GET['dongy'];

    $kiemtra_dongy = "SELECT duv.id FROM donungvien duv 
                  JOIN vieclam vl ON duv.idvieclam = vl.id 
                  WHERE duv.id='$don_id' AND vl.idnhatuyendung='$id_nhatuyendung'";

    $thuchien_dongy = mysqli_query($conn, $kiemtra_dongy);

    if (mysqli_num_rows($thuchien_dongy) > 0) {
        mysqli_query($conn, "UPDATE donungvien SET trangthai='chapnhan' WHERE id='$don_id'");
        $thanhcong = "Đã đồng ý hồ sơ!";
    } else {
        $thatbai = "Không có quyền duyệt hồ sơ này.";
    }
}

// XỬ LÝ TỪ CHỐI
if (isset($_GET['tuchoi']) && is_numeric($_GET['tuchoi'])) {
    $don_id = $_GET['tuchoi'];
    $kiemtra_tuchoi = "SELECT duv.id FROM donungvien duv 
                  JOIN vieclam vl ON duv.idvieclam = vl.id 
                  WHERE duv.id='$don_id' AND vl.idnhatuyendung='$id_nhatuyendung'";

    $thuchien_tuchoi = mysqli_query($conn, $kiemtra_tuchoi);

    if (mysqli_num_rows($thuchien_tuchoi) > 0) {
        mysqli_query($conn, "UPDATE donungvien SET trangthai='tuchoi' WHERE id='$don_id'");
        $thanhcong = "Đã từ chối hồ sơ!";
    } else {
        $thatbai = "Không có quyền từ chối hồ sơ này.";
    }
}

// LẤY DANH SÁCH ỨNG VIÊN
$danhsach = "SELECT duv.*, duv.id as don_id, vl.tieude AS tieude_jobs, sv.hoten AS hoten_sv, 
        hs.duongdancv, hs.sodienthoai, hs.diachi, hs.kynang
        FROM donungvien duv
        JOIN vieclam vl ON duv.idvieclam = vl.id
        JOIN nguoidung sv ON duv.idsinhvien = sv.id
        LEFT JOIN hosoungvien hs ON sv.id = hs.idnguoidung
        WHERE vl.idnhatuyendung = '$id_nhatuyendung'
        ORDER BY duv.ngaynop DESC";

$thuchien_danhsach = mysqli_query($conn, $danhsach);
?>

<?php include 'include_header.php'; ?>

<div class="container mt-4">
    <h3>Danh sách ứng viên</h3>
    <?php if(isset($thanhcong)) echo "<div class='alert alert-success'>$thanhcong</div>"; ?>
    <?php if(isset($thatbai)) echo "<div class='alert alert-danger'>$thatbai</div>"; ?>
    
    <?php if(mysqli_num_rows($thuchien_danhsach) > 0): ?>
        <div class="list-group">
            <?php while($ungtuyen = mysqli_fetch_assoc($thuchien_danhsach)): ?>
                <div class="list-group-item mb-3 p-3">
                <div class="row">
                    <!-- Hiển thị thông tin -->
                    <div class="col-md-6">
                        <h5><?php echo htmlspecialchars($ungtuyen['hoten_sv']); ?></h5>
                        <p><strong>Ứng tuyển vào:</strong> <?php echo htmlspecialchars($ungtuyen['tieude_jobs']); ?></p>
                        <p><strong>Ngày nộp:</strong> 
                            <?php echo date('d/m/Y H:i', strtotime($ungtuyen['ngaynop'])); ?>
                        </p>

                        <p>
                            <strong>SĐT:</strong> <?php echo htmlspecialchars($ungtuyen['sodienthoai'] ?? '-'); ?><br>
                            <strong>Địa chỉ:</strong> <?php echo htmlspecialchars($ungtuyen['diachi'] ?? '-'); ?><br>
                            <strong>Kỹ năng:</strong> <?php echo htmlspecialchars($ungtuyen['kynang'] ?? '-'); ?>
                        </p>

                        <!--Hiển thị trạng thái -->
                        <span class="badge bg-<?php 
                            echo $ungtuyen['trangthai']=='choxuly'?'warning':
                                ($ungtuyen['trangthai']=='chapnhan'?'success':'danger'); ?>">
                            <?php echo ucfirst($ungtuyen['trangthai'] == 'choxuly'?'Chờ xử lý':
                            ($ungtuyen['trangthai']=='chapnhan'?'Chấp nhận':'Từ chối')); ?>
                        </span>
                    </div>
                    <!-- Hiển thị CV -->
                    <div class="col-md-6 text-center">
                        <?php if (!empty($ungtuyen['duongdancv'])): ?>
                            <a href="<?php echo $ungtuyen['duongdancv']; ?>" target="_blank">
                                <img src="<?= $ungtuyen['duongdancv'] ?>" 
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

                    <?php if ($ungtuyen['trangthai'] == 'choxuly'): ?>

                        <a href="cty_ungvien.php?dongy=<?= $ungtuyen['don_id']; ?>" 
                        class="btn btn-success btn-sm px-3 me-2">
                        Đồng ý
                        </a>

                        <a href="cty_ungvien.php?tuchoi=<?= $ungtuyen['don_id']; ?>" 
                        class="btn btn-warning btn-sm px-3 me-2">
                        Từ chối
                        </a>

                    <?php endif; ?>

                    <a href="cty_ungvien.php?xoa=<?= $ungtuyen['don_id']; ?>" 
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
