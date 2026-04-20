<?php
session_start();
include 'ketnoi.php';
include 'zalo.php';
if (isset($_POST['btn_import'])) {
    $file = $_FILES['file_sv']['tmp_name'];
    if ($file) {
        $open = fopen($file, "r");
        $thanhcong = 0;
        
        // Nếu file có tiêu đề (Họ tên, Mã SV) ở dòng 1 thì bỏ qua dòng này
        // fgetcsv($open, 1000, ","); 

        // Đọc từng dòng của file CSV
        while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
            if (empty($data[0]) || empty($data[1])) continue; // Bỏ qua dòng trống

            $hoten = trim($data[0]);
            $masv = trim($data[1]); // Mã SV
            $vaitro = 'sinhvien';
            $password = password_hash($masv, PASSWORD_DEFAULT); // MK mặc định là Mã SV

            // Kiểm tra trùng tên đăng nhập (cột email)
            $check = $conn->prepare("SELECT id FROM nguoidung WHERE email = ?");
            $check->bind_param("s", $masv);
            $check->execute();
            if ($check->get_result()->num_rows == 0) {
                $stmt = $conn->prepare("INSERT INTO nguoidung (hoten, email, matkhau, vaitro) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $hoten, $masv, $password, $vaitro);
                if ($stmt->execute()) $thanhcong++;
                $stmt->close();
            }
            $check->close();
        }
        fclose($open);

        // SỬA Ở ĐÂY: window.location chuyển về ad_trangchu.php
        echo "<script>
                alert('Đã tạo thành công $thanhcong tài khoản sinh viên!'); 
                window.location='ad_trangchu.php';
              </script>";
    }
}
?>

<?php include 'include_header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h4 class="text-center mb-3" style="color: black;">Tải lên danh sách sinh viên</h4>
                    <div class="alert alert-info py-2 small">
                        <i class="fas fa-info-circle"></i> Định dạng file <b>.csv</b> (Cột 1: Họ tên, Cột 2: Mã SV).<br>
                        Mật khẩu mặc định sẽ là Mã sinh viên.
                    </div>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="file" name="file_sv" class="form-control" accept=".csv" required>
                        </div>
                        
                        <button type="submit" name="btn_import" class="btn btn-success w-100 fw-bold" style="color: black;">
                            <i class="fas fa-upload"></i> Bắt đầu tải lên
                        </button>
                        
                        <a href="ad_trangchu.php" class="btn btn-outline-secondary w-100 mt-3 fw-bold" style="color: black; text-decoration: none;">
                            Hủy bỏ và quay lại
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'include_footer.php'; ?>