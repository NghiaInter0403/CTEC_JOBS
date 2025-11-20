<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'sinhvien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$user_id = $_SESSION['user_id'];

if ($_POST) {
    $sodienthoai = $_POST['sodienthoai'];
    $diachi = $_POST['diachi'];
    $kynang = $_POST['kynang'];

    // Upload CV
    $duongdancv = '';
    if ($_FILES['cv']['name']) {
        $target_dir = "uploads/";
        $duongdancv = $target_dir . basename($_FILES["cv"]["name"]);
        move_uploaded_file($_FILES["cv"]["tmp_name"], $duongdancv);
    }

    $sql = "INSERT INTO hosoungvien (idnguoidung, sodienthoai, diachi, duongdancv, kynang) 
            VALUES ('$user_id', '$sodienthoai', '$diachi', '$duongdancv', '$kynang')
            ON DUPLICATE KEY UPDATE 
            sodienthoai='$sodienthoai', diachi='$diachi', kynang='$kynang'" . ($duongdancv ? ", duongdancv='$duongdancv'" : "");

    mysqli_query($conn, $sql);
    $success = "Cập nhật hồ sơ thành công!";
}
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4">
    <h3>Cập nhật hồ sơ cá nhân</h3>
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="sodienthoai" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Địa chỉ</label>
            <textarea name="diachi" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label>Kỹ năng</label>
            <textarea name="kynang" class="form-control" rows="3" placeholder="HTML, CSS, PHP, ..."></textarea>
        </div>
        <div class="mb-3">
            <label>Upload CV (PDF)</label>
            <input type="file" name="cv" accept=".pdf" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Lưu hồ sơ</button>
    </form>
</div>
<?php include 'include_footer.php'; ?>