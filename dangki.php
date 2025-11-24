<?php
session_start();
include 'ketnoi.php';

$thanhcong = $thatbai = '';

if ($_POST) {
    $hoten = trim($_POST['hoten']);
    $email = trim($_POST['email']);
    $matkhau = $_POST['matkhau'];
    $vaitro = $_POST['vaitro'];

    // Kiểm tra email đã tồn tại
    $check = $conn->prepare("SELECT id FROM nguoidung WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email này đã được sử dụng!";
    } else {
        // Mã hóa mật khẩu
        $mahoamk = password_hash($matkhau, PASSWORD_DEFAULT);
        // Insert dùng Prepared Statement
        $stmt = $conn->prepare("INSERT INTO nguoidung (hoten, email, matkhau, vaitro) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $hoten, $email, $mahoamk, $vaitro);

        if ($stmt->execute()) {
            $thanhcong = "Đăng ký thành công! Vui lòng đăng nhập.";
            // Có thể chuyển hướng sau 2 giây
            header("refresh:2;url=login.php");
        } else {
            $thatbai = "Có lỗi xảy ra. Vui lòng thử lại.";
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
                    <?php if ($thanhcong): ?>
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle"></i> <?php echo $thanhcong; ?>
                            <br><small>Đang chuyển đến trang đăng nhập...</small>
                        </div>
                    <?php endif; ?>

                    <!-- Thông báo lỗi -->
                    <?php if ($thatbai): ?>
                        <div class="alert alert-danger">
                            <?php echo $thatbai; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Form đăng ký -->
                    <?php if (!$thanhcong): ?>
                    <form method="POST" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="hoten" class="form-control" 
                                   value="<?php echo isset($_POST['hoten']) ? htmlspecialchars($_POST['hoten']) : ''; ?>" 
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
                            <label class="form-label fw-bold">Bạn là</label>
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