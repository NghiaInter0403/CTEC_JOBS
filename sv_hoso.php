<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'sinhvien') {
    header("Location: login.php");
    exit;
}

include 'ketnoi.php';

$idnguoidung = $_SESSION['user_id'];

// Lấy thông tin tài khoản
$sql_sv = "SELECT * FROM nguoidung WHERE id = '$idnguoidung'";
$sv = mysqli_fetch_assoc(mysqli_query($conn, $sql_sv));

// Lấy thông tin hồ sơ sinh viên
$sql_hoso = "SELECT * FROM hosoungvien WHERE idnguoidung = '$idnguoidung'";
$hoso = mysqli_fetch_assoc(mysqli_query($conn, $sql_hoso));
?>

<?php include 'include_header.php'; ?>

<div class="container mt-4">
    <h3 class="mb-4">Hồ sơ sinh viên</h3>

    <div class="card shadow-sm p-4">
        <h4 class="text-primary"><?= htmlspecialchars($sv['hoten']) ?></h4>
        <p class="text-muted mb-1">Email: <?= htmlspecialchars($sv['email']) ?></p>

        <hr>

        <h5 class="fw-bold">Thông tin liên hệ</h5>
        <p><strong>Số điện thoại:</strong> <?= $hoso['sodienthoai'] ?? "Chưa cập nhật" ?></p>
        <p><strong>Địa chỉ:</strong> <?= $hoso['diachi'] ?? "Chưa cập nhật" ?></p>

        <h5 class="fw-bold mt-4">Kỹ năng</h5>
        <p><?= $hoso['kynang'] ?? "Chưa cập nhật" ?></p>

        <h5 class="fw-bold mt-4">CV đính kèm</h5>
        <?php if (!empty($hoso['duongdancv'])): ?>
            <a href="<?= $hoso['duongdancv'] ?>" target="_blank">
                <img src="<?= $hoso['duongdancv'] ?>" 
                alt="Hình ảnh đính kèm"
                style="max-width: 400px; height: auto; border: 1px solid #ccc; border-radius: 6px;">
            </a>
     
        <?php else: ?>
            <p class="text-muted">Chưa upload CV</p>
        <?php endif; ?>

        <hr>

        <a href="sv_chitiet.php" class="btn btn-primary mt-3">Cập nhật hồ sơ</a>
    </div>
    <a href="sv_trangchu.php" class="btn btn-outline-secondary float-end">Quay lại</a>
</div>

<?php include 'include_footer.php'; ?>