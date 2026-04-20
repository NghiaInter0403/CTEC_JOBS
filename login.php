<?php
session_start();
include 'ketnoi.php';
include 'zalo.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $matkhau = $_POST['matkhau'];           

    // DÙNG PREPARED STATEMENT ĐỂ AN TOÀN
    $stmt = $conn->prepare("SELECT id, hoten, matkhau, vaitro FROM nguoidung WHERE username = ?");
    $stmt->bind_param("s", $username);
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
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
    $stmt->close();
}
?>
<style>
        /* BACKGROUND */
    body {
        background: linear-gradient(135deg, #e8f5e9, #f1f8f4);
        font-family: 'Segoe UI', sans-serif;
    }

    /* CENTER */
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
    }

    /* CARD */
    .login-card {
        width: 100%;
        max-width: 420px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 6px 25px rgba(0,0,0,0.15);
        overflow: hidden;
    }

    /* HEADER */
    .login-header {
        background: linear-gradient(135deg, #2e7d32, #66bb6a);
        color: white;
        text-align: center;
        padding: 20px;
    }

    /* BODY */
    .login-body {
        padding: 30px;
    }

    /* FORM */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #ccc;
        outline: none;
        transition: 0.3s;
    }

    .form-group input:focus {
        border-color: #2e7d32;
        box-shadow: 0 0 5px rgba(46,125,50,0.3);
    }

    /* BUTTON */
    .btn-login {
        width: 100%;
        padding: 12px;
        background: #2e7d32;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-login:hover {
        background: #1b5e20;
    }

    /* ERROR */
    .error-box {
        background: #ffebee;
        color: #c62828;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
        text-align: center;
    }

    /* MOBILE */
    @media (max-width: 500px) {
        .login-card {
            border-radius: 15px;
        }

        .login-body {
            padding: 20px;
        }
    }
    .forgot-link {
    font-size: 14px;
    color: #2e7d32;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
    }

    .forgot-link:hover {
        text-decoration: underline;
        color: #1b5e20;
    }
</style>
<?php include 'include_header.php'; ?>
<div class="login-container">

    <div class="login-card">

        <div class="login-header">
            <h3>Đăng nhập hệ thống</h3>
        </div>

        <div class="login-body">

            <!-- Lỗi -->
            <?php if ($error): ?>
                <div class="error-box">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input 
                        type="text"
                        name="username"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="matkhau" required>
                </div>

                <button type="submit" class="btn-login">
                    Đăng nhập
                </button>
                <div style="text-align: right; margin-bottom: 15px; margin-top: 20px">
                    <a href="quenmatkhau.php" class="forgot-link">
                        Quên mật khẩu?
                    </a>
                </div>
            </form>

        </div>

    </div>

</div>

<?php include 'include_footer.php'; ?>