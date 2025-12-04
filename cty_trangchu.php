<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$id_nhatuyendung = $_SESSION['id_nguoidung'];

// Thống kê
$tongtin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM vieclam WHERE idnhatuyendung = '$id_nhatuyendung'"))['total'];
$daduyet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM vieclam WHERE idnhatuyendung = '$id_nhatuyendung' AND trangthai = 'daduyet'"))['total'];
$choduyet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM vieclam WHERE idnhatuyendung = '$id_nhatuyendung' AND trangthai = 'choxuly'"))['total'];
$tong_ungvien = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM donungvien dut JOIN vieclam vl ON dut.idvieclam = vl.id WHERE vl.idnhatuyendung = '$id_nhatuyendung'"))['total'];

// Tin tuyển dụng của tôi
$tin_cty = mysqli_query($conn, "SELECT * FROM vieclam WHERE idnhatuyendung = '$id_nhatuyendung' ORDER BY ngaydang DESC");
// Ứng viên mới nhất
$ungvien_moi = mysqli_query($conn, "SELECT duv.*, vl.tieude, nd.hoten as sinhvien, hsuv.duongdancv 
    FROM donungvien duv
    JOIN vieclam vl ON duv.idvieclam = vl.id 
    JOIN nguoidung nd ON duv.idsinhvien = nd.id 
    LEFT JOIN hosoungvien hsuv ON nd.id = hsuv.idnguoidung 
    WHERE vl.idnhatuyendung = '$id_nhatuyendung' 
    ORDER BY duv.ngaynop DESC");
?>

<?php include 'include_header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <!-- thay đổic -->
        <h2>Xin chào, <strong><?php echo $_SESSION['hoten']; ?></strong></h2> 
        <a href="cty_dangtuyen.php" class="btn btn-success">
            Đăng tin mới
        </a>
    </div>
    <!-- Thẻ thống kê -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5>Tổng tin</h5>
                    <h3 class="mb-0"><?php echo $tongtin; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5>Đã duyệt</h5>
                    <h3 class="mb-0"><?php echo $daduyet; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5>Chờ duyệt</h5>
                    <h3 class="mb-0"><?php echo $choduyet; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <h5>Ứng viên</h5>
                    <h3 class="mb-0"><?php echo $tong_ungvien; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tin của tôi -->
        <div class="col-lg-6 mb-4">
    <div class="card h-auto">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tin tuyển dụng của tôi</h5>
        </div>
        <div class="card-body p-0">
            <?php if (mysqli_num_rows($tin_cty) > 0): ?>
            <!-- List group cố định chiều cao + scroll -->
            <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                <?php while ($vieclam = mysqli_fetch_assoc($tin_cty)): ?>
                <a href="vl_chitiet.php?id=<?php echo $vieclam['id']; ?>" 
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo $vieclam['tieude']; ?></strong><br>
                        <small class="text-muted"><?php echo $vieclam['tencongty']; ?> • <?php echo date('d/m/Y', strtotime($vieclam['ngaydang'])); ?></small>
                    </div>
                    <div>
                        <span class="badge bg-<?php echo $vieclam['trangthai']=='daduyet'?'success':($vieclam['trangthai']=='tuchoi'?'danger':'warning'); ?> rounded-pill">
                            <?php echo ucfirst($vieclam['trangthai']=='daduyet'?'Đã duyệt':($vieclam['trangthai']=='tuchoi'?'Từ chối':'Chờ xử lý')); ?>
                        </span>
                        <a href="cty_suatin.php?id=<?php echo $vieclam['id']; ?>" class="btn btn-sm btn-outline-secondary ms-1">Sửa</a>
                        <a href="cty_xoatin.php?id=<?php echo $vieclam['id']; ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Xóa tin này?')">Xóa</a>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
            <!-- Kết thúc list-group -->
            <?php else: ?>
                <p class="text-center text-muted py-4">Chưa có tin nào</p>
            <?php endif; ?>
            <div class="card-footer bg-light text-center">
                <a href="cty_dangtuyen.php" class="btn btn-sm btn-outline-primary">Đăng tin mới</a>
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
            <?php if (mysqli_num_rows($ungvien_moi) > 0): ?>  
            <!-- Thêm scroll + cố định chiều cao -->
            <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                <?php while ($ungtuyen = mysqli_fetch_assoc($ungvien_moi)): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo $ungtuyen['sinhvien']; ?></strong><br>
                        <small class="text-muted">
                            <?php echo $ungtuyen['tieude']; ?> • <?php echo date('d/m H:i', strtotime($ungtuyen['ngaynop'])); ?>
                        </small>
                    </div>
                    <div>
                        <?php if ($ungtuyen['duongdancv']): ?>
                            <a href="<?php echo $ungtuyen['duongdancv']; ?>" target="_blank" class="btn btn-sm btn-info">CV</a>
                        <?php endif; ?>
                        <span class="badge bg-warning ms-1"><?php echo ucfirst($ungtuyen['trangthai']); ?></span>
                    </div>
                </div>
                <?php endwhile; ?> 
            </div>
            <!-- Kết thúc list-group cuộn -->
            <?php else: ?>
                <p class="text-center text-muted py-4">Chưa có ứng viên</p>
            <?php endif; ?>
            <div class="card-footer bg-light text-center">
                <a href="cty_ungvien.php" class="btn btn-sm btn-outline-success">Xem tất cả</a>
            </div>
        </div>
    </div>
</div>

    </div>

    <!-- Menu nhanh -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Quản lý nhanh</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="cty_dangtuyen.php" class="btn btn-success w-100 p-3">Đăng tin mới</a>
                </div>
                <div class="col-md-4">
                    <a href="cty_ungvien.php" class="btn btn-primary w-100 p-3">Xem ứng viên</a>
                </div>
                <div class="col-md-4">
                    <a href="vl_danhsach.php" class="btn btn-outline-secondary w-100 p-3">Xem danh sách việc làm</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include_footer.php'; ?>