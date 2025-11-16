<?php
session_start();
include 'ketnoi.php';

$success = $error = '';

if ($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Kiểm tra email đã tồn tại
    $check = $conn->prepare("SELECT id FROM nguoidung WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email này đã được sử dụng!";
    } else {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Insert dùng Prepared Statement
        $stmt = $conn->prepare("INSERT INTO nguoidung (hoten, email, matkhau, vaitro) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            $success = "Đăng ký thành công! Vui lòng đăng nhập.";
            // Có thể chuyển hướng sau 2 giây
            header("refresh:2;url=login.php");
        } else {
            $error = "Có lỗi xảy ra. Vui lòng thử lại.";
        }
        $stmt->close();
    }
    $check->close();
}
?>
<?php include 'include_header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Đăng ký tài khoản</h4>
                </div>
                <div class="card-body p-4">

                    <!-- Thông báo thành công -->
                    <?php if ($success): ?>
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                            <br><small>Đang chuyển đến trang đăng nhập...</small>
                        </div>
                    <?php endif; ?>

                    <!-- Thông báo lỗi -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Form đăng ký -->
                    <?php if (!$success): ?>
                    <form method="POST" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
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
                            <input type="password" name="password" class="form-control" 
                                   placeholder="Ít nhất 6 ký tự" minlength="6" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bạn là</label>
                            <select name="role" class="form-select" required>
                                <option value="student" <?php echo (isset($_POST['role']) && $_POST['role']=='sinhvien') ? 'selected' : ''; ?>>
                                    Sinh viên
                                </option>
                                <option value="employer" <?php echo (isset($_POST['role']) && $_POST['role']=='nhatuyendung') ? 'selected' : ''; ?>>
                                    Nhà tuyển dụng
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            Đăng ký ngay
                        </button>
                    </form>
                    <?php endif; ?>

                    <p class="mt-3 text-center text-muted">
                        Đã có tài khoản? 
                        <a href="login.php" class="text-primary fw-bold">Đăng nhập</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include_footer.php'; ?>