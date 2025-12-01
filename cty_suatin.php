<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$id_nhatuyendung = $_SESSION['user_id'];
$id_vieclam = $_GET['id'];

$sql = "SELECT * FROM vieclam WHERE id = '$id_vieclam' AND idnhatuyendung = '$id_nhatuyendung'";
$vieclam = mysqli_fetch_assoc(mysqli_query($conn, $sql));
if (!$vieclam) exit("Không có quyền!");

if ($_POST) {
    $tieude = $_POST['title'];
    $tencongty = $_POST['company'];
    $luong = $_POST['salary'];
    $diadiem = $_POST['location'];
    $nganhnghe = $_POST['category'];
    $mota = $_POST['description'];
    $yeucau = $_POST['requirements'];
    $email_lienhe = $_POST['contact_email'];

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
        <div class="mb-3"><label>Vị trí</label><input type="text" name="title" class="form-control" value="<?php echo $job['tieude']; ?>" required></div>
        <div class="mb-3"><label>Công ty</label><input type="text" name="company" class="form-control" value="<?php echo $job['tencongty']; ?>" required></div>
        <div class="mb-3"><label>Lương</label><input type="text" name="salary" class="form-control" value="<?php echo $job['mucluong']; ?>" required></div>
        <div class="mb-3"><label>Khu vực</label><input type="text" name="location" class="form-control" value="<?php echo $job['diadiem']; ?>" required></div>
        <div class="mb-3"><label>Ngành nghề</label>
            <select name="category" class="form-select" required>
                <option value="Công Nghệ Thông Tin" <?php if($job['nganhnghe']=='IT') echo 'selected'; ?>>Công Nghệ Thông Tin</option>
                <option value="Marketing" <?php if($job['nganhnghe']=='Marketing') echo 'selected'; ?>>Marketing</option>
                <option value="Kinh doanh" <?php if($job['nganhnghe']=='Kinh doanh') echo 'selected'; ?>>Kinh doanh</option>
                <option value="Nông Nghiệp" <?php if($job['nganhnghe']=='Nông Nghiệp') echo 'selected'; ?>>Nông Nghiệp</option>
                <option value="Kế Toán" <?php if($job['nganhnghe']=='Kế Toán') echo 'selected'; ?>>Kế Toán</option>
                <option value="Gia Sư" <?php if($job['nganhnghe']=='Gia Sư') echo 'selected'; ?>>Gia Sư</option>
                <option value="Bán Thời Gian" <?php if($job['nganhnghe']=='Bán Thời Gian') echo 'selected'; ?>>Bán Thời Gian</option>
                 <option value="Freelancer" <?php if($job['nganhnghe']=='Freelancer') echo 'selected'; ?>>Freelancer</option>
            </select>
        </div>
        <div class="mb-3"><label>Mô tả</label><textarea name="description" class="form-control" rows="4" required><?php echo $job['mota']; ?></textarea></div>
        <div class="mb-3"><label>Yêu cầu</label><textarea name="requirements" class="form-control" rows="4" required><?php echo $job['yeucau']; ?></textarea></div>
        <div class="mb-3"><label>Email liên hệ</label><input type="email" name="contact_email" class="form-control" value="<?php echo $job['emaillienhe']; ?>" required></div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="cty_trangchu.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>
<?php include 'include_footer.php'; ?>