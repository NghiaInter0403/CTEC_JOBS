<?php
session_start();
include 'ketnoi.php';
include 'zalo.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $matkhau = $_POST['matkhau'];           

    // DÙNG PREPARED STATEMENT ĐỂ AN TOÀN
    $stmt = $conn->prepare("SELECT id, hoten, matkhau, vaitro FROM nguoidung WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $nguoidung = $result->fetch_assoc();

    if ($nguoidung && password_verify($matkhau, $nguoidung['matkhau'])) {
        // LƯU SESSION
        $_SESSION['id_nguoidung'] = $nguoidung['id'];
        $_SESSION['hoten']    = $nguoidung['hoten'];
        $_SESSION['vaitro']    = $nguoidung['vaitro'];

        //CHUYỂN HƯỚNG THEO VAI TRÒ
        switch ($nguoidung['vaitro']) {
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
                            <label class="form-label fw-bold">Tên đăng nhập</label>
                            <input name="email" class="form-control" 
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
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include_footer.php'; ?>