<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
include 'zalo.php';
$id_nhatuyendung = $_SESSION['id_nguoidung'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tieude = $_POST['tieude'];
    $tencongty = $_POST['tencongty'];
    $mucluong = $_POST['mucluong'];
    $diadiem = $_POST['diadiem'];
    $nganhnghe = $_POST['nganhnghe'];
    $mota = $_POST['mota'];
    $yeucau = $_POST['yeucau'];
    $email_lienhe = $_POST['emaillienhe'];

    $file_name_insert = null; // Mặc định nếu không có file

    // === XỬ LÝ UPLOAD FILE ===
    if (isset($_FILES['file_chitiet']) && $_FILES['file_chitiet']['error'] == 0) {
        $target_dir = "uploads/chitiet_vieclam/";
        
        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_extension = strtolower(pathinfo($_FILES["file_chitiet"]["name"], PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png", "pdf");

        if (in_array($file_extension, $allowed_extensions)) {
            // Đổi tên file: idNTD_thoigian_random.ext để không bị trùng
            $file_name_insert = "VL_" . time() . "_" . uniqid() . "." . $file_extension;
            $target_file = $target_dir . $file_name_insert;

            if (!move_uploaded_file($_FILES["file_chitiet"]["tmp_name"], $target_file)) {
                $loi_upload = "Không thể lưu file vào thư mục.";
                $file_name_insert = null;
            }
        } else {
            $loi_upload = "Chỉ chấp nhận file JPG, PNG, PDF.";
        }
    }

    // === LƯU VÀO DATABASE (Dùng Prepared Statement để an toàn) ===
    $sql = "INSERT INTO vieclam (idnhatuyendung, tieude, tencongty, mucluong, diadiem, nganhnghe, mota, yeucau, emaillienhe, chitiet) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isssssssss", $id_nhatuyendung, $tieude, $tencongty, $mucluong, $diadiem, $nganhnghe, $mota, $yeucau, $email_lienhe, $file_name_insert);
    
    if (mysqli_stmt_execute($stmt)) {
        $thanhcong = "Đăng tin thành công! Đang chờ duyệt.";
    } else {
        $loi_hethong = "Lỗi: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between">
        <h3>Đăng tin tuyển dụng</h3>
        <a href="cty_trangchu.php" class="btn btn-outline-secondary btn-sm">Quay lại</a>
    </div>
    <hr>

    <?php if (isset($thanhcong)) echo "<div class='alert alert-success'>$thanhcong</div>"; ?>
    <?php if (isset($loi_upload)) echo "<div class='alert alert-warning'>$loi_upload</div>"; ?>
    <?php if (isset($loi_hethong)) echo "<div class='alert alert-danger'>$loi_hethong</div>"; ?>

    <form method="POST" enctype="multipart/form-data" class="shadow p-4 bg-white rounded">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Vị trí tuyển dụng</label>
                <input type="text" name="tieude" class="form-control" placeholder="Ví dụ: Lập trình viên PHP" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tên công ty hiển thị</label>
                <input type="text" name="tencongty" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Mức lương</label>
                <input type="text" name="mucluong" class="form-control" placeholder="5-7 triệu hoặc Thỏa thuận" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Khu vực làm việc</label>
                <input type="text" name="diadiem" class="form-control" placeholder="Ví dụ: Cần Thơ" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Ngành nghề</label>
                <select name="nganhnghe" class="form-select" required>
                    <option value="">-- Chọn ngành nghề --</option>
                    <option value="Công nghệ thông tin">Công nghệ thông tin</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Kinh doanh">Kinh doanh</option>
                    <option value="Nông Nghiệp">Nông Nghiệp</option>
                    <option value="Kế Toán">Kế Toán</option>
                    <option value="Gia Sư">Gia Sư</option>
                    <option value="Bán Thời Gian">Bán Thời Gian</option>
                    <option value="Freelancer">Freelancer</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Mô tả công việc</label>
            <textarea name="mota" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Yêu cầu ứng viên</label>
            <textarea name="yeucau" class="form-control" rows="3" required></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email nhận hồ sơ</label>
                <input type="email" name="emaillienhe" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-primary">Tài liệu/Hình ảnh chi tiết (Nếu có)</label>
                <input type="file" name="file_chitiet" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <div class="form-text text-muted">Hỗ trợ JPG, PNG hoặc PDF (Yêu cầu công việc chi tiết)</div>
            </div>
        </div>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-success px-5 fw-bold text-uppercase">Đăng tin ngay</button>
        </div>
    </form>
</div>
<?php include 'include_footer.php'; ?>