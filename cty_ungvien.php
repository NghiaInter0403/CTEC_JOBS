<?php
session_start();
include 'ketnoi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}

$sv_id = $_GET['id'];

$sql = mysqli_query($conn, "
    SELECT nd.hoten, nd.email, hs.sodienthoai, hs.diachi, hs.kynang, hs.duongdancv
    FROM nguoidung nd
    LEFT JOIN hosoungvien hs ON nd.id = hs.idnguoidung
    WHERE nd.id = '$sv_id'
");

$info = mysqli_fetch_assoc($sql);
?>

<?php include 'include_header.php'; ?>

<div class="container mt-4">
    <h3>Hồ sơ sinh viên</h3>

    <p><strong>Họ tên:</strong> <?= $info['hoten'] ?></p>
    <p><strong>Email:</strong> <?= $info['email'] ?></p>
    <p><strong>Số điện thoại:</strong> <?= $info['sodienthoai'] ?></p>
    <p><strong>Địa chỉ:</strong> <?= $info['diachi'] ?></p>
    <p><strong>Kỹ năng:</strong> <?= $info['kynang'] ?></p>

    <?php if ($info['duongdancv']): ?>
        <a href="<?= $info['duongdancv'] ?>" class="btn btn-primary" target="_blank">Tải CV</a>
    <?php endif; ?>
</div>

<?php include 'include_footer.php'; ?>