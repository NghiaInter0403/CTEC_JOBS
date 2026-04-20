<?php
session_start();
include 'ketnoi.php';
include 'zalo.php';
$id_vieclam = $_GET['id'] ?? 0;

$sql = "SELECT vl.*, nd.hoten as tencongty_nguoidung FROM vieclam vl 
        JOIN nguoidung nd ON vl.idnhatuyendung = nd.id 
        WHERE vl.id = ? AND vl.trangthai = 'daduyet'";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_vieclam);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$vieclam = mysqli_fetch_assoc($result);

if (!$vieclam) {
    header("Location: vl_danhsach.php");
    exit;
}

mysqli_query($conn, "INSERT INTO thongke (trang) VALUES ('Việc làm_$id_vieclam') ON DUPLICATE KEY UPDATE solanxem = solanxem + 1");
?>

<?php include 'include_header.php'; ?>

<style>
    .job-title {
        color: #212529 !important; /* Màu đen xám đậm */
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .card-header-custom {
        background-color: #f8f9fa; /* Nền xám cực nhẹ */
        border-bottom: 2px solid #e9ecef;
    }
</style>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header card-header-custom py-4">
            <h3 class="job-title mb-1"><?php echo htmlspecialchars($vieclam['tieude']); ?></h3>
            <div class="d-flex align-items-center">
                <span class="badge bg-primary me-2">Tin cậy</span>
                <small class="text-muted"><i class="far fa-calendar-alt"></i> Đăng ngày: <?php echo date('d/m/Y', strtotime($vieclam['ngaydang'])); ?></small>
            </div>
        </div>
        
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <p class="mb-2"><strong><i class="fas fa-building text-secondary me-2"></i>Công ty:</strong> <?php echo htmlspecialchars($vieclam['tencongty']); ?></p>
                        <p class="mb-2"><strong><i class="fas fa-money-bill-wave text-success me-2"></i>Mức lương:</strong> <span class="text-danger fw-bold fs-5"><?php echo htmlspecialchars($vieclam['mucluong']); ?></span></p>
                        <p class="mb-2"><strong><i class="fas fa-map-marker-alt text-danger me-2"></i>Khu vực:</strong> <?php echo htmlspecialchars($vieclam['diadiem']); ?></p>
                        <p class="mb-2"><strong><i class="fas fa-briefcase text-info me-2"></i>Ngành nghề:</strong> <?php echo htmlspecialchars($vieclam['nganhnghe']); ?></p>
                    </div>
                    
                    <hr class="my-4">
                    <h5 class="fw-bold border-start border-4 border-primary ps-2 mb-3">Mô tả công việc</h5>
                    <p class="text-justify line-height-lg"><?php echo nl2br(htmlspecialchars($vieclam['mota'])); ?></p>
                    
                    <h5 class="fw-bold border-start border-4 border-primary ps-2 mt-4 mb-3">Yêu cầu ứng viên</h5>
                    <p class="text-justify line-height-lg"><?php echo nl2br(htmlspecialchars($vieclam['yeucau'])); ?></p>
                </div>
                
                <div class="col-md-4 border-start d-none d-md-block">
                    <h5 class="fw-bold mb-3"><i class="fas fa-paperclip me-2"></i>Tài liệu đính kèm</h5>
                    <?php if (!empty($vieclam['chitiet'])): ?>
                        <?php 
                        $file_path = "uploads/chitiet_vieclam/" . $vieclam['chitiet'];
                        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                        
                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                            <div class="mb-3">
                                <a href="<?php echo $file_path; ?>" target="_blank">
                                    <img src="<?php echo $file_path; ?>" class="img-fluid img-thumbnail hover-shadow" alt="Chi tiết công việc">
                                </a>
                                <p class="small text-muted mt-2 text-center small italic"><i class="fas fa-search-plus"></i> Click để xem ảnh lớn</p>
                            </div>
                        <?php else: ?>
                            <div class="card bg-light border-0 text-center p-3">
                                <i class="fas fa-file-pdf text-danger fa-3x mb-2"></i>
                                <p class="small fw-bold mb-2">Tài liệu chi tiết (.<?php echo $ext; ?>)</p>
                                <a href="<?php echo $file_path; ?>" target="_blank" class="btn btn-sm btn-primary w-100">Xem ngay</a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4 bg-light rounded">
                            <p class="text-muted small mb-0">Không có file đính kèm</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 fs-6"><strong><i class="fas fa-envelope me-2"></i>Email liên hệ:</strong> <span class="text-primary"><?php echo htmlspecialchars($vieclam['emaillienhe']); ?></span></p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <?php if (isset($_SESSION['vaitro']) && $_SESSION['vaitro'] == 'sinhvien'): ?>
                        <?php
                        $id_sinhvien = $_SESSION['id_nguoidung'];
                        $ungtuyen_check = mysqli_query($conn, "SELECT id FROM donungvien WHERE idvieclam = '$id_vieclam' AND idsinhvien = '$id_sinhvien'");
                        $da_ungtuyen = mysqli_num_rows($ungtuyen_check);
                        ?>
                        <?php if ($da_ungtuyen == 0): ?>
                            <a href="sv_nopdon.php?id=<?php echo $id_vieclam; ?>" class="btn btn-success btn-lg px-4 shadow-sm fw-bold">Ứng tuyển ngay</a>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-lg px-4 shadow-sm" disabled><i class="fas fa-check-circle me-1"></i> Đã ứng tuyển</button>
                        <?php endif; ?>
                    <?php elseif (!isset($_SESSION['id_nguoidung'])): ?>
                        <a href="login.php" class="btn btn-primary btn-lg shadow-sm">Đăng nhập ứng tuyển</a>
                    <?php endif; ?>

                    <a href="vl_danhsach.php" class="btn btn-outline-secondary btn-lg shadow-sm ms-2">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include_footer.php'; ?>