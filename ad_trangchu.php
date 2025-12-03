<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'quantrivien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';

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

<div class="container mt-4">
    <!-- Chào mừng -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Chào mừng, <strong><?php echo $_SESSION['hoten']; ?></strong> (Admin)</h2>
    </div>
    <!-- Thẻ thống kê -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">Tổng tin tuyển dụng</h5>
                        <h3 class="mb-0"><?php echo $soluong_viec; ?></h3>
                    </div>
                    <small><i class="fas fa-briefcase"></i> Tất cả</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">Tin chờ duyệt</h5>
                        <h3 class="mb-0"><?php echo $trangthai_vieclam; ?></h3>
                    </div>
                    <small><i class="fas fa-clock"></i> Đang chờ</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">Ứng tuyển</h5>
                        <h3 class="mb-0"><?php echo $soluong_ungvien; ?></h3>
                    </div>
                    <small><i class="fas fa-users"></i> Đơn ứng tuyển</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">Người dùng</h5>
                        <h3 class="mb-0"><?php echo $soluong_sinhvien + $soluong_nhatuyendung; ?></h3>
                    </div>
                    <small>
                        <span class="badge bg-light text-dark"><?php echo $soluong_sinhvien; ?> SV</span>
                        <span class="badge bg-light text-dark"><?php echo $soluong_nhatuyendung; ?> NTD</span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tin chờ duyệt -->
        <div class="col-lg-6 mb-4">
            <div class="card h-auto">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        Tin tuyển dụng chờ duyệt
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if (mysqli_num_rows($vieclam_choduyet) > 0): ?>
                        <div class="list-group list-group-flush" style="height: 350px; overflow-y: auto;">
                            <?php while ($vieclam = mysqli_fetch_assoc($vieclam_choduyet)): ?>
                                <a href="ad_qlyviec.php"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo $vieclam['tieude']; ?></strong><br>
                                        <small class="text-muted">
                                            <?php echo $vieclam['nhatuyendung']; ?> • 
                                            <?php echo date('d/m/Y', strtotime($job['ngaydang'])); ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-warning rounded-pill">Chờ duyệt</span>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted py-4">Không có tin nào chờ duyệt</p>
                    <?php endif; ?>
                    <div class="card-footer bg-light text-center">
                        <a href="ad_qlyviec.php" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                    </div>
                </div>
            </div>
        </div>


        <!-- Ứng viên mới -->
        <div class="col-lg-6 mb-4">
            <div class="card h-auto">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Ứng viên mới nhất</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (mysqli_num_rows($ungvienmoi) > 0): ?>
                    
                        <div class="list-group list-group-flush ungvien-scroll" 
                            style="max-height: 350px; overflow-y: auto;">
                            
                            <?php while ($app = mysqli_fetch_assoc($ungvienmoi)): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo $app['sinhvien']; ?></strong><br>
                                        <small class="text-muted">
                                            <?php echo $app['tieude']; ?> • 
                                            <?php echo date('d/m/Y H:i', strtotime($app['ngaynop'])); ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-<?=
                                        $app['trangthai']=='chapnhan' ? 'success' :
                                        ($app['trangthai']=='tuchoi' ? 'danger' : 'warning')
                                    ?> rounded-pill">
                                        <?= 
                                            $app['trangthai']=='chapnhan' ? 'Chấp nhận' :
                                            ($app['trangthai']=='tuchoi' ? 'Từ chối' : 'Chờ xử lý')
                                        ?>
                                    </span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted py-4">Chưa có ứng viên</p>
                    <?php endif; ?>

                    <div class="card-footer bg-light text-center">
                        <a href="thongke.php" class="btn btn-sm btn-outline-success">Xem thống kê</a>
                    </div>
                </div>
            </div>
        </div>
</div>
    <!-- Menu nhanh -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Quản lý hệ thống</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="ad_qlyviec.php" class="btn btn-outline-primary w-100 p-3">
                                Duyệt tin tuyển dụng
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="ad_qlyuser.php" class="btn btn-outline-danger w-100 p-3">
                                Quản lý người dùng
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="thongke.php" class="btn btn-outline-info w-100 p-3">
                                Thống kê & Báo cáo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include_footer.php'; ?>