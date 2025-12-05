<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$id_nhatuyendung = $_SESSION['id_nguoidung'];
$id_vieclam = $_GET['id'];

$sql = "SELECT * FROM vieclam WHERE id = '$id_vieclam' AND idnhatuyendung = '$id_nhatuyendung'";
$vieclam = mysqli_fetch_assoc(mysqli_query($conn, $sql));
if (!$vieclam) exit("Không có quyền!");

if ($_POST) {
    $tieude = $_POST['tieude'];
    $tencongty = $_POST['tencongty'];
    $luong = $_POST['luong'];
    $diadiem = $_POST['diadiem'];
    $nganhnghe = $_POST['nganhnghe'];
    $mota = $_POST['mota'];
    $yeucau = $_POST['yeucau'];
    $email_lienhe = $_POST['email_lienhe'];

    $truyvan_viec = "UPDATE vieclam SET tieude='$tieude', tencongty='$tencongty', mucluong='$luong', diadiem='$diadiem', 
            nganhnghe='$nganhnghe', mota='$mota', yeucau='$yeucau', 
            emaillienhe='$email_lienhe', trangthai='choxuly' WHERE id='$id_vieclam'";
    mysqli_query($conn, $truyvan_viec);
    $thanhcong = "Cập nhật thành công! Tin đang chờ duyệt lại.";
}
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4">
    <h3>Chỉnh sửa tin tuyển dụng</h3>
    <!-- nếu thành công thì hiện cái khung màu xanh -->
    <?php if (isset($thanhcong)) echo "<div class='alert alert-success'>$thanhcong</div>"; ?>

    <form method="POST">
        <!-- Giống form post-job.php -->
        <div class="mb-3"><label>Vị trí</label><input type="text" name="tieude" class="form-control" value="<?php echo $vieclam['tieude']; ?>" required></div>
        <div class="mb-3"><label>Công ty</label><input type="text" name="tencongty" class="form-control" value="<?php echo $vieclam['tencongty']; ?>" required></div>
        <div class="mb-3"><label>Lương</label><input type="text" name="luong" class="form-control" value="<?php echo $vieclam['mucluong']; ?>" required></div>
        <div class="mb-3"><label>Khu vực</label><input type="text" name="diadiem" class="form-control" value="<?php echo $vieclam['diadiem']; ?>" required></div>
        <div class="mb-3"><label>Ngành nghề</label>
            <select name="nganhnghe" class="form-select" required>
                <option value="Công Nghệ Thông Tin" <?php if($vieclam['nganhnghe']=='Công nghệ thông tin') echo 'selected'; ?>>Công Nghệ Thông Tin</option>
                <option value="Marketing" <?php if($vieclam['nganhnghe']=='Marketing') echo 'selected'; ?>>Marketing</option>
                <option value="Kinh doanh" <?php if($vieclam['nganhnghe']=='Kinh doanh') echo 'selected'; ?>>Kinh doanh</option>
                <option value="Nông Nghiệp" <?php if($vieclam['nganhnghe']=='Nông Nghiệp') echo 'selected'; ?>>Nông Nghiệp</option>
                <option value="Kế Toán" <?php if($vieclam['nganhnghe']=='Kế Toán') echo 'selected'; ?>>Kế Toán</option>
                <option value="Gia Sư" <?php if($vieclam['nganhnghe']=='Gia Sư') echo 'selected'; ?>>Gia Sư</option>
                <option value="Bán Thời Gian" <?php if($vieclam['nganhnghe']=='Bán Thời Gian') echo 'selected'; ?>>Bán Thời Gian</option>
                 <option value="Freelancer" <?php if($vieclam['nganhnghe']=='Freelancer') echo 'selected'; ?>>Freelancer</option>
            </select>
        </div>
        <div class="mb-3"><label>Mô tả</label><textarea name="mota" class="form-control" rows="4" required><?php echo $vieclam['mota']; ?></textarea></div>
        <div class="mb-3"><label>Yêu cầu</label><textarea name="yeucau" class="form-control" rows="4" required><?php echo $vieclam['yeucau']; ?></textarea></div>
        <div class="mb-3"><label>Email liên hệ</label><input type="email" name="email_lienhe" class="form-control" value="<?php echo $vieclam['emaillienhe']; ?>" required></div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="cty_trangchu.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>
<?php include 'include_footer.php'; ?>