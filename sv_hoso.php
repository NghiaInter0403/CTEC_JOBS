<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: login.php");
    exit;
}

include 'ketnoi.php';

$idnguoidung = $_SESSION['id_nguoidung'];

// Lấy thông tin tài khoản
$sql_sv = "SELECT * FROM nguoidung WHERE id = '$idnguoidung'";
$sv = mysqli_fetch_assoc(mysqli_query($conn, $sql_sv));

// Lấy thông tin hồ sơ sinh viên
$sql_hoso = "SELECT * FROM hosoungvien WHERE idnguoidung = '$idnguoidung'";
$hoso = mysqli_fetch_assoc(mysqli_query($conn, $sql_hoso));
?>

<?php include 'include_header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="mb-4">
        <a href="sv_trangchu.php" class="btn btn-outline-dark border-2 fw-bold shadow-sm px-4">
            <i class="bi bi-arrow-left-circle-fill me-2"></i> QUAY LẠI TRANG CHỦ
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-4 p-md-5 text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                    </div>
                    <h1 class="display-6 fw-bold mb-1"><?= htmlspecialchars($sv['hoten']) ?></h1>
                    <p class="fs-5 opacity-75 mb-0"><i class="bi bi-envelope"></i> <?= htmlspecialchars($sv['email']) ?></p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <div class="row g-4">
                        <div class="col-md-6 border-end">
                            <h4 class="fw-bold text-primary mb-4">
                                <i class="bi bi-info-square-fill me-2"></i>Thông tin liên hệ
                            </h4>
                            <div class="mb-3 fs-5">
                                <span class="text-muted d-block small text-uppercase fw-bold">Số điện thoại:</span>
                                <span class="fw-medium text-dark"><?= $hoso['sodienthoai'] ?? "<i>Chưa cập nhật</i>" ?></span>
                            </div>
                            <div class="mb-3 fs-5">
                                <span class="text-muted d-block small text-uppercase fw-bold">Địa chỉ:</span>
                                <span class="fw-medium text-dark"><?= $hoso['diachi'] ?? "<i>Chưa cập nhật</i>" ?></span>
                            </div>
                        </div>

                        <div class="col-md-6 ps-md-4">
                            <h4 class="fw-bold text-primary mb-4">
                                <i class="bi bi-award-fill me-2"></i>Kỹ năng
                            </h4>
                            <div class="fs-5 text-dark lh-base">
                                <?php if (!empty($hoso['kynang'])): ?>
                                    <div class="p-3 bg-light rounded-3 border-start border-4 border-warning shadow-sm">
                                        <?= nl2br(htmlspecialchars($hoso['kynang'])) ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted italic">Chưa cập nhật kỹ năng chuyên môn.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="text-center">
                        <h4 class="fw-bold text-primary mb-4">
                            <i class="bi bi-file-earmark-check-fill me-2"></i>CV / Tài liệu đính kèm
                        </h4>
                        
                        <?php if (!empty($hoso['duongdancv'])): ?>
                            <?php 
                                $file_ext = strtolower(pathinfo($hoso['duongdancv'], PATHINFO_EXTENSION));
                                $image_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                            ?>

                            <?php if (in_array($file_ext, $image_exts)): ?>
                                <div class="position-relative d-inline-block shadow-sm rounded-3 border p-2">
                                    <a href="<?= $hoso['duongdancv'] ?>" target="_blank" class="d-block">
                                        <img src="<?= $hoso['duongdancv'] ?>" 
                                             alt="Hình ảnh CV"
                                             class="img-fluid rounded-2 hover-zoom" 
                                             style="max-width: 500px; transition: transform .3s ease;">
                                    </a>
                                    <div class="mt-3">
                                        <a href="<?= $hoso['duongdancv'] ?>" target="_blank" class="btn btn-outline-primary btn-sm px-3">
                                            <i class="bi bi-zoom-in"></i> Phóng to ảnh
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="p-5 bg-light rounded-4 border border-dashed d-inline-block shadow-sm" style="min-width: 300px;">
                                    <i class="bi bi-file-earmark-text text-primary display-2"></i>
                                    <h5 class="mt-3 fw-bold text-dark text-uppercase"><?= $file_ext ?> File</h5>
                                    <p class="text-muted small">Hồ sơ ứng viên đã được tải lên</p>
                                    <a href="<?= $hoso['duongdancv'] ?>" target="_blank" class="btn btn-primary px-4 shadow">
                                        <i class="bi bi-box-arrow-up-right me-2"></i> Xem tài liệu / Tải xuống
                                    </a>
                                </div>
                            <?php endif; ?>

                        <?php else: ?>
                            <div class="py-5 bg-light rounded-3 border">
                                <i class="bi bi-cloud-slash display-4 text-muted"></i>
                                <p class="text-muted fs-5 mt-2">Bạn chưa tải lên CV hoặc tài liệu nào.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-5 pt-4 border-top text-center">
                        <a href="sv_chitiet.php" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-lg border-0 rounded-pill">
                            <i class="bi bi-pencil-square me-2"></i> CHỈNH SỬA HỒ SƠ CỦA TÔI
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Hiệu ứng rê chuột */
    .hover-zoom:hover {
        transform: scale(1.02);
    }
    .bi-person-circle {
        color: rgba(255,255,255,0.8);
    }
    .border-dashed {
        border: 2px dashed #dee2e6 !important;
    }
</style>

<?php include 'include_footer.php'; ?>