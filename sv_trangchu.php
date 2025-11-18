<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'sinhvien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$user_id = $_SESSION['user_id'];
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4" style="height: 500px;">
    <h2>Xin chào, <?php echo $_SESSION['name']; ?>!</h2>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h5><a href="sv_chitiet.php" class="text-white">Cập nhật hồ sơ</a></h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5><a href="sv_lichsu.php" class="text-white">Lịch sử ứng tuyển</a></h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h5><a href="sv_danhsach.php" class="text-white">Tìm việc làm</a></h5>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'include_footer.php'; ?>