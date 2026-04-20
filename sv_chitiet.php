<?php
session_start();
include 'zalo.php';
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$idnguoidung = $_SESSION['id_nguoidung'];

// Lấy dữ liệu cũ để hiển thị vào form
$sql_cu = "SELECT * FROM hosoungvien WHERE idnguoidung = '$idnguoidung'";
$result_cu = mysqli_query($conn, $sql_cu);
$data_cu = mysqli_fetch_assoc($result_cu);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sodienthoai = mysqli_real_escape_string($conn, $_POST['sodienthoai']);
    $diachi = mysqli_real_escape_string($conn, $_POST['diachi']);
    $kynang = mysqli_real_escape_string($conn, $_POST['kynang']);

    // Xử lý Upload file (Hình ảnh hoặc PDF/DOCX)
    $duongdancv = $data_cu['duongdancv'] ?? ''; 
    if (isset($_FILES['cv']) && $_FILES['cv']['name']) {
        $diachiluu = "uploads/";
        if (!file_exists($diachiluu)) mkdir($diachiluu, 0777, true);
        
        $file_extension = strtolower(pathinfo($_FILES["cv"]["name"], PATHINFO_EXTENSION));
        $new_file_name = "HS_" . $idnguoidung . "_" . time() . "." . $file_extension;
        $target_file = $diachiluu . $new_file_name;

        // Cho phép các định dạng phổ biến
        $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx');
        if (in_array($file_extension, $allowed)) {
            if (move_uploaded_file($_FILES["cv"]["tmp_name"], $target_file)) {
                $duongdancv = $target_file;
            }
        } else {
            $loi_file = "Định dạng file không hỗ trợ! Vui lòng chọn ảnh hoặc PDF/Word.";
        }
    }

    if (!isset($loi_file)) {
        $sql = "INSERT INTO hosoungvien (idnguoidung, sodienthoai, diachi, duongdancv, kynang) 
                VALUES ('$idnguoidung', '$sodienthoai', '$diachi', '$duongdancv', '$kynang')
                ON DUPLICATE KEY UPDATE 
                sodienthoai='$sodienthoai', diachi='$diachi', kynang='$kynang', duongdancv='$duongdancv'";

        if (mysqli_query($conn, $sql)) {
            $thanhcong = "Cập nhật hồ sơ thành công!";
            $data_cu['sodienthoai'] = $sodienthoai;
            $data_cu['diachi'] = $diachi;
            $data_cu['kynang'] = $kynang;
            $data_cu['duongdancv'] = $duongdancv;
        }
    }
}
?>

<?php include 'include_header.php'; ?>

<div class="container mt-5 pb-5" style="margin-bottom: 80px;"> 
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <a href="sv_trangchu.php" class="btn btn-outline-dark shadow-sm border-2 fw-bold">
                    <i class="bi bi-arrow-left-short fs-4"></i> QUAY LẠI TRANG CHỦ
                </a>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white py-4 text-center">
                    <h2 class="fw-bold mb-0 text-uppercase"><i class="bi bi-person-bounding-box"></i> Hồ sơ cá nhân</h2>
                    <p class="mb-0 opacity-75 fs-5 mt-2">Cung cấp thông tin ấn tượng để thu hút nhà tuyển dụng</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if (isset($thanhcong)): ?>
                        <div class='alert alert-success alert-dismissible fade show fs-5 mb-4 border-0 shadow-sm' role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> <?php echo $thanhcong; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($loi_file)): ?>
                        <div class='alert alert-danger fs-5 mb-4 border-0 shadow-sm' role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $loi_file; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5 text-dark">Số điện thoại</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 shadow-sm"><i class="bi bi-telephone text-primary"></i></span>
                                <input type="text" name="sodienthoai" class="form-control form-control-lg shadow-sm border-0 bg-light" 
                                       value="<?php echo $data_cu['sodienthoai'] ?? ''; ?>" placeholder="09xxxxxxx" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5 text-dark">Địa chỉ thường trú</label>
                            <textarea name="diachi" class="form-control form-control-lg shadow-sm border-0 bg-light" rows="2" 
                                      placeholder="Ví dụ: TP. Cần Thơ"><?php echo $data_cu['diachi'] ?? ''; ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5 text-dark">Kỹ năng nổi bật</label>
                            <textarea name="kynang" class="form-control form-control-lg shadow-sm border-0 bg-light" rows="3" 
                                      placeholder="Ví dụ: Lập trình, Giao tiếp, Tiếng Anh..."><?php echo $data_cu['kynang'] ?? ''; ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5 text-dark">Tải lên CV (Ảnh hoặc PDF, Word)</label>
                            <input type="file" name="cv" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="form-control form-control-lg shadow-sm border-0 bg-light">
                            <div class="form-text text-muted mt-2">
                                <i class="bi bi-info-circle"></i> Hỗ trợ định dạng: JPG, PNG, PDF, DOCX (Tối đa 5MB)
                            </div>
                            
                            <?php if (!empty($data_cu['duongdancv'])): ?>
                                <div class="mt-4 p-3 border-0 rounded-3 bg-light shadow-sm">
                                    <p class="small text-uppercase fw-bold text-muted mb-2">Tài liệu/Ảnh đã tải lên:</p>
                                    <?php 
                                        $ext = pathinfo($data_cu['duongdancv'], PATHINFO_EXTENSION);
                                        if (in_array($ext, ['jpg', 'jpeg', 'png'])): 
                                    ?>
                                        <img src="<?php echo $data_cu['duongdancv']; ?>" class="img-thumbnail" style="max-height: 150px;">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-check fs-1 text-primary me-3"></i>
                                            <a href="<?php echo $data_cu['duongdancv']; ?>" target="_blank" class="text-decoration-none fw-bold">Xem tài liệu đã tải lên</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 shadow-lg border-0">
                                <i class="bi bi-save2-fill me-2"></i> LƯU THÔNG TIN HỒ SƠ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-4"></div> 

<?php include 'include_footer.php'; ?>