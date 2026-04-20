<?php
session_start();
include 'zalo.php';
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$id_nhatuyendung = $_SESSION['id_nguoidung'];
$id_vieclam = $_GET['id'];

// Lấy thông tin việc làm cũ
$sql = "SELECT * FROM vieclam WHERE id = '$id_vieclam' AND idnhatuyendung = '$id_nhatuyendung'";
$vieclam = mysqli_fetch_assoc(mysqli_query($conn, $sql));
if (!$vieclam) exit("Không có quyền!");

if ($_POST) {
    $tieude = $_POST['tieude'];
    $tencongty = $_POST['tencongty'];
    $luong = $_POST['luong'];
    $diadiem = $_POST['diadiem'];
    $nganhnghe = $_POST['nganhnghe'];
    $mota = $_POST['mota'];
    $yeucau = $_POST['yeucau'];
    $email_lienhe = $_POST['email_lienhe'];

    // Mặc định giữ lại tên file cũ trong DB
    $file_name_insert = $vieclam['chitiet']; 

    // === XỬ LÝ UPLOAD FILE MỚI (NẾU CÓ) ===
    if (isset($_FILES['file_chitiet']) && $_FILES['file_chitiet']['error'] == 0) {
        $target_dir = "uploads/chitiet_vieclam/";
        
        // Tạo thư mục nếu chưa có
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

        $file_extension = strtolower(pathinfo($_FILES["file_chitiet"]["name"], PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png", "pdf");

        if (in_array($file_extension, $allowed_extensions)) {
            // Xóa file cũ khỏi thư mục để tiết kiệm bộ nhớ (nếu cần)
            if (!empty($vieclam['chitiet']) && file_exists($target_dir . $vieclam['chitiet'])) {
                unlink($target_dir . $vieclam['chitiet']);
            }

            // Đặt tên file mới
            $file_name_insert = "VL_" . time() . "_" . uniqid() . "." . $file_extension;
            move_uploaded_file($_FILES["file_chitiet"]["tmp_name"], $target_dir . $file_name_insert);
        }
    }

    // Cập nhật Database (Dùng Prepared Statement để an toàn với dấu nháy đơn)
    $truyvan_viec = "UPDATE vieclam SET 
                    tieude=?, tencongty=?, mucluong=?, diadiem=?, nganhnghe=?, 
                    mota=?, yeucau=?, emaillienhe=?, chitiet=?, trangthai='choxuly' 
                    WHERE id=? AND idnhatuyendung=?";
    
    $stmt = mysqli_prepare($conn, $truyvan_viec);
    mysqli_stmt_bind_param($stmt, "sssssssssii", 
        $tieude, $tencongty, $luong, $diadiem, $nganhnghe, 
        $mota, $yeucau, $email_lienhe, $file_name_insert, $id_vieclam, $id_nhatuyendung);
    
    if (mysqli_stmt_execute($stmt)) {
        $thanhcong = "Cập nhật thành công! Tin đang chờ duyệt lại.";
        // Cập nhật lại biến $vieclam để hiển thị dữ liệu mới trên form
        $vieclam['chitiet'] = $file_name_insert; 
    }
    mysqli_stmt_close($stmt);
}
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4 mb-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Chỉnh sửa tin tuyển dụng</h4>
        </div>
        <div class="card-body">
            <?php if (isset($thanhcong)) echo "<div class='alert alert-success'>$thanhcong</div>"; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Vị trí</label>
                        <input type="text" name="tieude" class="form-control" value="<?php echo htmlspecialchars($vieclam['tieude']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Công ty</label>
                        <input type="text" name="tencongty" class="form-control" value="<?php echo htmlspecialchars($vieclam['tencongty']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Lương</label>
                        <input type="text" name="luong" class="form-control" value="<?php echo htmlspecialchars($vieclam['mucluong']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Khu vực</label>
                        <input type="text" name="diadiem" class="form-control" value="<?php echo htmlspecialchars($vieclam['diadiem']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Ngành nghề</label>
                        <select name="nganhnghe" class="form-select" required>
                            <?php 
                            $nganhs = ["Công nghệ thông tin", "Marketing", "Kinh doanh", "Nông Nghiệp", "Kế Toán", "Gia Sư", "Bán Thời Gian", "Freelancer"];
                            foreach($nganhs as $n) {
                                $sel = ($vieclam['nganhnghe'] == $n) ? "selected" : "";
                                echo "<option value='$n' $sel>$n</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Mô tả</label>
                    <textarea name="mota" class="form-control" rows="4" required><?php echo htmlspecialchars($vieclam['mota']); ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="fw-bold">Yêu cầu</label>
                    <textarea name="yeucau" class="form-control" rows="4" required><?php echo htmlspecialchars($vieclam['yeucau']); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Email liên hệ</label>
                        <input type="email" name="email_lienhe" class="form-control" value="<?php echo htmlspecialchars($vieclam['emaillienhe']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-primary">Tài liệu/Hình ảnh chi tiết mới (Để trống nếu giữ cũ)</label>
                        <input type="file" name="file_chitiet" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        
                        <?php if(!empty($vieclam['chitiet'])): ?>
                            <div class="mt-2 small text-muted">
                                File hiện tại: <a href="uploads/chitiet_vieclam/<?php echo $vieclam['chitiet']; ?>" target="_blank"><?php echo $vieclam['chitiet']; ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary px-4">Cập nhật tin</button>
                    <a href="cty_trangchu.php" class="btn btn-secondary">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'include_footer.php'; ?>