<?php
session_start();
include 'ketnoi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);        // <-- Lấy đúng tên field
    $matkhau = $_POST['matkhau'];           // <-- ĐÃ SỬA: dùng 'password'

    // === DÙNG PREPARED STATEMENT ĐỂ AN TOÀN ===
    $stmt = $conn->prepare("SELECT id, hoten, matkhau, vaitro FROM nguoidung WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();

    if ($user && password_verify($matkhau, $user['matkhau'])) {
        // === LƯU SESSION ===
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['hoten'];
        $_SESSION['role']    = $user['vaitro'];  // Lưu vaitro gốc

        // === CHUYỂN HƯỚNG THEO VAI TRÒ (dùng giá trị trong CSDL) ===
        switch ($user['vaitro']) {
            case 'sinhvien':
                header("Location: sv_trangchu.php");
                break;
            case 'nhatuyendung':
                header("Location: cty_trangchu.php");
                break;
            case 'quantrivien':
                header("Location: ad_trangchu.php");
                break;
            default:
                $error = "Vai trò không hợp lệ!";
                break;
        }
        exit;

    } else {
        $error = "Email hoặc mật khẩu không đúng!";
    }
    $stmt->close();
}
?>

<?php include 'include_header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Đăng nhập hệ thống</h4>
                </div>
                <div class="card-body p-4">

                    <!-- Thông báo lỗi -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <!-- Form đăng nhập -->
                    <form method="POST" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                   required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu</label>
                            <input type="password" name="matkhau" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            Đăng nhập
                        </button>
                    </form>

                    <p class="mt-3 text-center text-muted">
                        Chưa có tài khoản? 
                        <a href="dangki.php" class="text-primary fw-bold">Đăng ký ngay</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include_footer.php'; ?>