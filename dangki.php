<?php
session_start();
include 'ketnoi.php';
include 'zalo.php';
$thanhcong = $thatbai = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hoten = trim($_POST['hoten']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);       
    $matkhau = $_POST['matkhau'];
    $vaitro = $_POST['vaitro'];

    // Kiểm tra trống các ô nhập liệu
  if (empty($hoten) || empty($username) || empty($email) || empty($matkhau) || empty($vaitro)) {
    $thatbai = "Vui lòng điền đầy đủ thông tin!";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $thatbai = "Email không hợp lệ!";
    }
    elseif (strlen($matkhau) < 6) {
        $thatbai = "Mật khẩu phải có ít nhất 6 ký tự!";
    }
    else {
        // Kiểm tra email đã tồn tại
        $check = $conn->prepare("SELECT id FROM nguoidung WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            // Đã sửa từ $error thành $thatbai để hiển thị được lỗi
            $thatbai = "Tài khoản (Email) này đã được sử dụng!"; 
        } else {
            // Mã hóa mật khẩu
            $mahoamk = password_hash($matkhau, PASSWORD_DEFAULT);
            // Insert dữ liệu
            $stmt = $conn->prepare("INSERT INTO nguoidung (hoten, username, email, matkhau, vaitro) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $hoten, $username, $email, $mahoamk, $vaitro);   

            if ($stmt->execute()) {
                $thanhcong = "Tạo tài khoản thành công! Vui lòng đợi.";
                header("refresh:2;url=ad_trangchu.php");
            } else {
                $thatbai = "Có lỗi xảy ra. Vui lòng thử lại.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<?php include 'include_header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-center">
                    <h4 class="mb-0" style="color: black;">Tạo tài khoản mới</h4>
                </div>
                <div class="card-body p-4">

                    <?php if ($thanhcong): ?>
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle"></i> <?php echo $thanhcong; ?>
                            <br><small>Đang chuyển đến trang chủ...</small>
                        </div>
                    <?php endif; ?>

                    <?php if ($thatbai): ?>
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $thatbai; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!$thanhcong): ?>
                    <form method="POST" novalidate>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="hoten" class="form-control" 
                                   value="<?php echo isset($_POST['hoten']) ? htmlspecialchars($_POST['hoten']) : ''; ?>" 
                                   required>
                        </div>

                        <div class="mb-3">
                        <label class="form-label fw-bold">Tên đăng nhập</label>
                        <input type="text" name="username" class="form-control" 
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                            required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" 
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu</label>
                            <input type="password" name="matkhau" class="form-control" 
                                   placeholder="Ít nhất 6 ký tự" minlength="6" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Vai trò</label>
                            <select name="vaitro" class="form-select" required>
                                <option value="sinhvien"
                                <?php echo (isset($_POST['vaitro']) && $_POST['vaitro']=='sinhvien') ? 'selected' : ''; ?>>
                                Sinh viên
                                </option>
                                <option value="nhatuyendung"
                                <?php echo (isset($_POST['vaitro']) && $_POST['vaitro']=='nhatuyendung') ? 'selected' : ''; ?>>
                                Nhà tuyển dụng
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold" style="color: black;">
                            Tạo tài khoản
                        </button>
                        <a href="admin_import_user.php" class="btn btn-success w-100 fw-bold mt-3" style="color: black; text-decoration: none;">
                            <i class="fas fa-file-excel"></i> Tạo tài khoản theo danh sách
                        </a>
                        <a href="ad_trangchu.php" class="btn btn-outline-secondary w-100 fw-bold mt-3" style="color: black; text-decoration: none;">
                             Quay lại
                        </a>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include_footer.php'; ?>