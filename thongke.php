<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'quantrivien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
include 'zalo.php';
$tong_vieclam = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as thongke FROM vieclam WHERE trangthai='daduyet'"))['thongke'];
$tong_ungtuyen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as thongke FROM donungvien"))['thongke'];
$tong_sinhvien = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as thongke FROM nguoidung WHERE vaitro='sinhvien'"))['thongke'];
$tong_cty = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as thongke FROM nguoidung WHERE vaitro='nhatuyendung'"))['thongke'];
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4">
    <h3>Thống kê hệ thống</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h4><?php echo $tong_vieclam; ?></h4>
                    <p>Tin tuyển dụng</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h4><?php echo $tong_ungtuyen; ?></h4>
                    <p>Ứng tuyển</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h4><?php echo $tong_sinhvien; ?></h4>
                    <p>Sinh viên</p>
                </div>
            </div>
        </div>
         <div class="col-md-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h4><?php echo $tong_cty; ?></h4>
                    <p>Công Ty</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'include_footer.php'; ?>