<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}

include 'ketnoi.php';
$employer_id = $_SESSION['user_id'];

// Lấy danh sách ứng viên ứng tuyển vào tin tuyển dụng của công ty
$sql = "SELECT duv.*, vl.tieude AS job_title, sv.hoten AS student_name, hs.duongdancv, hs.sodienthoai, hs.diachi, hs.kynang
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
    <?php if(mysqli_num_rows($result) > 0): ?>
        <div class="list-group">
            <?php while($app = mysqli_fetch_assoc($result)): ?>
                <div class="list-group-item mb-2">
                    <h5><?php echo htmlspecialchars($app['student_name']); ?></h5>
                    <p><strong>Ứng tuyển vào:</strong> <?php echo htmlspecialchars($app['job_title']); ?></p>
                    <p><strong>Ngày nộp:</strong> <?php echo date('d/m/Y H:i', strtotime($app['ngaynop'])); ?></p>
                    <p>
                        <strong>SĐT:</strong> <?php echo htmlspecialchars($app['sodienthoai'] ?? '-'); ?><br>
                        <strong>Địa chỉ:</strong> <?php echo htmlspecialchars($app['diachi'] ?? '-'); ?><br>
                        <strong>Kỹ năng:</strong> <?php echo htmlspecialchars($app['kynang'] ?? '-'); ?>
                    </p>
                    <?php if(!empty($app['duongdancv'])): ?>
                        <a href="<?php echo $app['duongdancv']; ?>" target="_blank" class="btn btn-sm btn-info">Xem CV</a>
                    <?php endif; ?>
                    <span class="badge bg-<?php echo $app['trangthai']=='choxuly'?'warning':($app['trangthai']=='chapnhan'?'success':'danger'); ?> ms-2">
                        <?php echo ucfirst($app['trangthai']); ?>
                    </span>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">Chưa có ứng viên nào ứng tuyển vào tin tuyển dụng của bạn.</p>
    <?php endif; ?>
</div>

<?php include 'include_footer.php'; ?>
