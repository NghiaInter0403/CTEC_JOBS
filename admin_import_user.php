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
            if (empty($data[0]) || empty($data[1]) || empty($data[2])) continue;

            $hoten = trim($data[0]);
            $username = trim($data[1]); 
            $email = trim($data[2]);    
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;
            $vaitro = 'sinhvien';
            $password = password_hash($username, PASSWORD_DEFAULT);

            // Kiểm tra trùng tên đăng nhập (cột email)
            $check = $conn->prepare("SELECT id FROM nguoidung WHERE username = ? OR email = ?");
            $check->bind_param("ss", $username, $email);
            $check->execute();
            if ($check->get_result()->num_rows == 0) {
                $stmt = $conn->prepare("INSERT INTO nguoidung (hoten, username, email, matkhau, vaitro) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $hoten, $username, $email, $password, $vaitro);
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
                        <i class="fas fa-info-circle"></i> Định dạng file <b>.csv</b><br>
                        Cột 1: Họ tên<br>
                        Cột 2: Tên đăng nhập (MSSV)<br>
                        Cột 3: Email<br>
                        Mật khẩu mặc định: MSSV
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