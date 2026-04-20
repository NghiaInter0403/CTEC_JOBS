<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'quantrivien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
include 'zalo.php';
// Thống kê nhanh
$soluong_viec = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as tongso FROM vieclam"))['tongso'];
$trangthai_vieclam = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as tongso FROM vieclam WHERE trangthai='choxuly'"))['tongso'];
$soluong_ungvien = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as tongso FROM donungvien"))['tongso'];
$soluong_sinhvien = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as tongso FROM nguoidung WHERE vaitro='sinhvien'"))['tongso'];
$soluong_nhatuyendung = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as tongso FROM nguoidung WHERE vaitro='nhatuyendung'"))['tongso'];

// Tin tuyển dụng mới nhất (chờ duyệt)
$vieclam_choduyet = mysqli_query($conn, "SELECT j.*, u.hoten as nhatuyendung FROM vieclam j JOIN nguoidung u ON j.idnhatuyendung = u.id WHERE j.trangthai='choxuly' ORDER BY j.ngaydang DESC");

// Ứng viên mới nhất
$ungvienmoi = mysqli_query($conn, "SELECT a.*, j.tieude, u.hoten as sinhvien FROM donungvien a JOIN vieclam j ON a.idvieclam = j.id JOIN nguoidung u ON a.idsinhvien = u.id ORDER BY a.ngaynop DESC");
?>

<?php include 'include_header.php'; ?>

<style>
    /* CSS bổ sung để ép kích thước chữ to hơn cho các thành phần đặc thù */
    .card-title { font-size: 1.4rem !important; font-weight: bold; }
    .card-body h3 { font-size: 2.5rem !important; font-weight: 800; }
    .list-group-item strong { font-size: 1.2rem; }
    .btn-outline-primary, .btn-outline-danger, .btn-outline-info { font-size: 1.2rem; font-weight: bold; }
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 fw-bold mb-0">Chào mừng, <strong><?php echo $_SESSION['hoten']; ?></strong></h1>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100 shadow">
                <div class="card-body p-4" style="color: black;">
                    <h5 class="card-title text-uppercase opacity-75">Tổng tin tuyển dụng</h5>
                    <h3 class="mb-2"><?php echo $soluong_viec; ?></h3>
                    <div class="fs-5"><i class="fas fa-briefcase"></i> Hệ thống</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning h-100 shadow">
                <div class="card-body p-4" style="color: black;">
                    <h5 class="card-title text-uppercase opacity-75">Tin chờ duyệt</h5>
                    <h3 class="mb-2"><?php echo $trangthai_vieclam; ?></h3>
                    <div class="fs-5"><i class="fas fa-clock"></i> Đang chờ</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success h-100 shadow">
                <div class="card-body p-4" style="color: black;"> 
                    <h5 class="card-title text-uppercase opacity-75">Ứng tuyển</h5>
                    <h3 class="mb-2"><?php echo $soluong_ungvien; ?></h3>
                    <div class="fs-5"><i class="fas fa-users"></i> Tổng số đơn</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info h-100 shadow">
                <div class="card-body p-4" style="color: black;">
                    <h5 class="card-title text-uppercase opacity-75">Người dùng</h5>
                    <h3 class="mb-2"><?php echo $soluong_sinhvien + $soluong_nhatuyendung; ?></h3>
                    <div class="fs-5">
                        <span class="badge bg-light text-dark"><?php echo $soluong_sinhvien; ?> SV</span>
                        <span class="badge bg-light text-dark"><?php echo $soluong_nhatuyendung; ?> NTD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark py-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-clipboard-check me-2"></i>Tin chờ duyệt</h4>
                </div>
                <div class="card-body p-0">
                    <?php if (mysqli_num_rows($vieclam_choduyet) > 0): ?>
                        <div class="list-group list-group-flush" style="height: 400px; overflow-y: auto;">
                            <?php while ($vieclam = mysqli_fetch_assoc($vieclam_choduyet)): ?>
                                <a href="ad_qlyviec.php" class="list-group-item list-group-item-action py-3">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-primary"><?php echo $vieclam['tieude']; ?></strong><br>
                                            <span class="fs-6 text-muted">
                                                <i class="fas fa-building me-1"></i> <?php echo $vieclam['nhatuyendung']; ?> | 
                                                <i class="fas fa-calendar-alt me-1"></i> <?php echo date('d/m/Y', strtotime($vieclam['ngaydang'])); ?>
                                            </span>
                                        </div>
                                        <span class="badge bg-warning text-dark p-2 fs-6">Duyệt ngay</span>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center fs-4 text-muted py-5">Sạch sẽ! Không có tin nào chờ duyệt</p>
                    <?php endif; ?>
                    <div class="card-footer text-center">
                        <a href="ad_qlyviec.php" class="btn btn-primary fw-bold" style="color: black;">Quản lý tin tuyển dụng</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-black py-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-user-graduate me-2"></i>Ứng viên mới nhất</h4>
                </div>
                <div class="card-body p-0">
                    <?php if (mysqli_num_rows($ungvienmoi) > 0): ?>
                        <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                            <?php while ($ungvien = mysqli_fetch_assoc($ungvienmoi)): ?>
                                <div class="list-group-item py-3">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <strong class="fs-5"><?php echo $ungvien['sinhvien']; ?></strong><br>
                                            <span class="text-muted fs-6">
                                                Nộp vào: <strong><?php echo $ungvien['tieude']; ?></strong>
                                            </span>
                                        </div>
                                        <span class="badge bg-<?= 
                                            $ungvien['trangthai']=='chapnhan' ? 'success' : 
                                            ($ungvien['trangthai']=='tuchoi' ? 'danger' : 'warning') 
                                        ?> p-2 fs-6">
                                            <?= $ungvien['trangthai']=='chapnhan' ? 'Chấp nhận' : ($ungvien['trangthai']=='tuchoi' ? 'Từ chối' : 'Chờ xử lý') ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center fs-4 text-muted py-5">Chưa có hoạt động ứng tuyển</p>
                    <?php endif; ?>
                    <div class="card-footer text-center">
                        <a href="thongke.php" class="btn btn-success fw-bold" style="color: black;">Xem toàn bộ báo cáo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3 mb-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-black py-3">
                    <h4 class="mb-0 fw-bold">Phím tắt quản trị nhanh</h4>
                </div>
                <div class="card-body py-4">
                    <div class="row g-4 text-center">
                        <div class="col-md-4">
                            <a href="dangki.php" class="btn btn-outline-primary w-100 py-4 shadow-sm">
                                <i class="fas fa-user-plus d-block mb-2 fs-2"></i> TẠO TÀI KHOẢN MỚI
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="ad_qlyuser.php" class="btn btn-outline-danger w-100 py-4 shadow-sm">
                                <i class="fas fa-users-cog d-block mb-2 fs-2"></i> QUẢN LÝ NGƯỜI DÙNG
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="thongke.php" class="btn btn-outline-info w-100 py-4 shadow-sm">
                                <i class="fas fa-chart-line d-block mb-2 fs-2"></i> BÁO CÁO THỐNG KÊ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include_footer.php'; ?>