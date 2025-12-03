<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$idnguoidung = $_SESSION['id_nguoidung'];

if ($_POST) {
    $sodienthoai = $_POST['sodienthoai'];
    $diachi = $_POST['diachi'];
    $kynang = $_POST['kynang'];

    // Upload CV
    $duongdancv = '';
    if ($_FILES['cv']['name']) {
        $diachiluu = "uploads/";
        $duongdancv = $diachiluu . basename($_FILES["cv"]["name"]);
        move_uploaded_file($_FILES["cv"]["tmp_name"], $duongdancv);
    }

    $sql = "INSERT INTO hosoungvien (idnguoidung, sodienthoai, diachi, duongdancv, kynang) 
            VALUES ('$idnguoidung', '$sodienthoai', '$diachi', '$duongdancv', '$kynang')
            ON DUPLICATE KEY UPDATE 
            sodienthoai='$sodienthoai', diachi='$diachi', kynang='$kynang'" . ($duongdancv ? ", duongdancv='$duongdancv'" : "");

    mysqli_query($conn, $sql);
    $thanhcong = "Cập nhật hồ sơ thành công!";
}
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4">
    <h3>Cập nhật hồ sơ cá nhân</h3>
    <?php if (isset($thanhcong)) echo "<div class='alert alert-success'>$thanhcong</div>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="sodienthoai" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Địa chỉ</label>
            <textarea name="diachi" class="form-control" rows="3" ></textarea>
        </div>
        <div class="mb-3">
            <label>Kỹ năng</label>
            <textarea name="kynang" class="form-control" rows="3" placeholder="HTML, CSS, PHP, ..."></textarea>
        </div>
       <div class="mb-3">
        <label>Upload CV (IMG)</label>
        <input type="file" name="cv" accept="image/jpeg, image/png, .jpg, .jpeg, .png" class="form-control">
       </div>
        <button type="submit" class="btn btn-primary">Lưu hồ sơ</button>
    </form>
    <a href="sv_trangchu.php" class="btn btn-outline-secondary float-end">Quay lại</a>
</div>
<?php include 'include_footer.php'; ?>