<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'quantrivien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';

$total_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM vieclam WHERE trangthai='daduyet'"))['total'];
$total_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM donungvien"))['total'];
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM nguoidung WHERE vaitro='sinhvien'"))['total'];
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4">
    <h3>Thống kê hệ thống</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h4><?php echo $total_jobs; ?></h4>
                    <p>Tin tuyển dụng</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h4><?php echo $total_applications; ?></h4>
                    <p>Ứng tuyển</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h4><?php echo $total_students; ?></h4>
                    <p>Sinh viên</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'include_footer.php'; ?>