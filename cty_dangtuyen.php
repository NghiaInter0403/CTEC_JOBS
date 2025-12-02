<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$id_nhatuyendung = $_SESSION['user_id'];

if ($_POST) {
    $tieude = $_POST['tieude'];
    $tencongty = $_POST['tencongty'];
    $mucluong = $_POST['mucluong'];
    $diadiem = $_POST['diadiem'];
    $nganhnghe = $_POST['nganhnghe'];
    $mota = $_POST['mota'];
    $yeucau = $_POST['yeucau'];
    $email_lienhe = $_POST['emaillienhe'];

    $dangtin = "INSERT INTO vieclam (idnhatuyendung, tieude, tencongty, mucluong,diadiem, nganhnghe, mota, yeucau, emaillienhe)
            VALUES ('$id_nhatuyendung', '$tieude', '$tencongty', '$mucluong', '$diadiem', '$nganhnghe', '$mota', '$yeucau', '$email_lienhe')";
    mysqli_query($conn, $dangtin);
    $thanhcong = "Đăng tin thành công! Đang chờ duyệt.";
}
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4">
    <h3>Đăng tin tuyển dụng</h3>
    <?php if (isset($thanhcong)) echo "<div class='alert alert-success'>$thanhcong</div>"; ?>

    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Vị trí</label>
                <input type="text" name="tieude" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Công ty</label>
                <input type="text" name="tencongty" class="form-control" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Mức lương</label>
                <input type="text" name="mucluong" class="form-control" placeholder="5-7 triệu" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Khu vực</label>
                <input type="text" name="diadiem" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label>Ngành nghề</label>
            <select name="nganhnghe" class="form-select" required>
                <option value="IT">Công nghệ thông tin</option>
                <option value="Marketing">Marketing</option>
                <option value="Kinh doanh">Kinh doanh</option>
                <option value="Nông Nghiệp">Nông Nghiệp</option>
                <option value="Kế Toán">Kế Toán</option>
                <option value="Gia Sư">Gia Sư</option>
                <option value="Bán Thời Gian">Bán Thời Gian</option>
                <option value="Freelancer">Freelancer</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Mô tả công việc</label>
            <textarea name="mota" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label>Yêu cầu</label>
            <textarea name="yeucau" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label>Email liên hệ</label>
            <input type="email" name="emaillienhe" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Đăng tin</button>
    </form>
</div>
<?php include 'include_footer.php'; ?>