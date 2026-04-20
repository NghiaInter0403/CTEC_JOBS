<?php
session_start();
include 'ketnoi.php';

$msg = $err = '';

if (!isset($_SESSION['reset_user'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['reset_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $otp = trim($_POST['otp'] ?? '');
    $newpass = trim($_POST['matkhau'] ?? '');

    // ================= VALIDATE =================
    if (empty($otp) && empty($newpass)) {
        $err = "Vui lòng nhập OTP và mật khẩu mới!";
    } 
    elseif (empty($otp)) {
        $err = "Vui lòng nhập mã OTP!";
    } 
    elseif (!preg_match('/^[0-9]{6}$/', $otp)) {
        $err = "OTP phải là 6 chữ số!";
    }
    elseif (empty($newpass)) {
        $err = "Vui lòng nhập mật khẩu mới!";
    }
    elseif (strlen($newpass) < 6) {
        $err = "Mật khẩu phải >= 6 ký tự!";
    } 
    else {

        // ================= CHECK OTP =================
        $stmt = $conn->prepare("SELECT * FROM otp_reset WHERE username=? AND otp=? AND expire > NOW()");
        $stmt->bind_param("ss", $username, $otp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $err = "OTP sai hoặc đã hết hạn!";
        } else {

            // ================= UPDATE PASSWORD =================
            $hash = password_hash($newpass, PASSWORD_DEFAULT);

            $stmt2 = $conn->prepare("UPDATE nguoidung SET matkhau=? WHERE username=?");
            $stmt2->bind_param("ss", $hash, $username);
            $stmt2->execute();

            // ================= XOÁ OTP =================
            $stmt3 = $conn->prepare("DELETE FROM otp_reset WHERE username=?");
            $stmt3->bind_param("s", $username);
            $stmt3->execute();

            // ================= XOÁ SESSION =================
            unset($_SESSION['reset_user']);

            $msg = "Đổi mật khẩu thành công!";
        }
    }
}
?>
<style>
    /* CONTAINER */
    .reset-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
    }

    /* CARD */
    .reset-card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        overflow: hidden;
        animation: fadeIn 0.4s ease;
    }

    /* HEADER */
    .reset-header {
        background: linear-gradient(135deg, #2e7d32, #66bb6a);
        color: white;
        text-align: center;
        padding: 25px;
    }

    .reset-header h2 {
        margin-bottom: 5px;
    }

    .reset-header p {
        font-size: 14px;
        opacity: 0.9;
    }

    /* FORM */
    .reset-form {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
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

    /* PASSWORD ICON */
    .password-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 18px;
    }

    /* BUTTON */
    .btn-reset {
        width: 100%;
        padding: 13px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #2e7d32, #43a047);
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-reset:hover {
        background: linear-gradient(135deg, #1b5e20, #2e7d32);
        transform: translateY(-2px);
    }

    /* FOOTER */
    .reset-footer {
        text-align: center;
        padding-bottom: 20px;
    }

    .reset-footer a {
        color: #2e7d32;
        text-decoration: none;
        font-size: 14px;
    }

    .reset-footer a:hover {
        text-decoration: underline;
    }

    /* ANIMATION */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<?php include 'include_header.php' ?>
<div class="reset-container">

    <div class="reset-card">

        <div class="reset-header">
            <h2>Đặt lại mật khẩu</h2>
            <p>Nhập mã OTP và mật khẩu mới</p>
        </div>
        <?php if ($err): ?>
            <div style="background:#ffebee;color:#c62828;padding:10px;border-radius:8px;margin-bottom:15px;text-align:center;">
                <?php echo htmlspecialchars($err); ?>
            </div>
        <?php endif; ?>

        <?php if ($msg): ?>
            <div style="background:#e8f5e9;color:#2e7d32;padding:10px;border-radius:8px;margin-bottom:15px;text-align:center;">
                <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="reset-form">

            <div class="form-group">
                <label>Mã OTP</label>
                <input 
                    type="text" 
                    name="otp" 
                    placeholder="Nhập mã 6 số..."
                    maxlength="6"
                    required
                >
            </div>

            <div class="form-group password-box">
                <label>Mật khẩu mới</label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        name="matkhau" 
                        id="password"
                        placeholder="Ít nhất 6 ký tự"
                        required
                    >
                    <span class="toggle-password" onclick="togglePass()">👁</span>
                </div>
            </div>

            <button class="btn-reset">
                Đổi mật khẩu
            </button>
        </form>
        <div class="reset-footer">
            <a href="login.php">← Quay lại đăng nhập</a>
        </div>
    </div>
</div>
<?php include 'include_footer.php' ?>