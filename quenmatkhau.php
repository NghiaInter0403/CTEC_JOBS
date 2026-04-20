<?php
session_start();
include 'ketnoi.php';

$msg = $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // ================= VALIDATE =================
    if (empty($username) && empty($email)) {
        $err = "Vui lòng nhập tên đăng nhập và email!";
    } 
    elseif (empty($username)) {
        $err = "Vui lòng nhập tên đăng nhập!";
    } 
    elseif (empty($email)) {
        $err = "Vui lòng nhập email!";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Email không hợp lệ!";
    } 
    else {

        // ================= CHECK USER =================
        $stmt = $conn->prepare("SELECT id FROM nguoidung WHERE username=? AND email=?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $err = "Tên đăng nhập hoặc email không tồn tại!";
        } else {
            // kiểm tra gửi trong 30s
            $checkOtp = $conn->prepare("SELECT * FROM otp_reset WHERE username=? AND expire > NOW()");
            $checkOtp->bind_param("s", $username);
            $checkOtp->execute();

            if ($checkOtp->get_result()->num_rows > 0) {
                $err = "Bạn vừa yêu cầu OTP, vui lòng đợi!";
            }
            // ================= TẠO OTP =================
            $otp = rand(100000, 999999);
            $expire = date("Y-m-d H:i:s", strtotime("+5 minutes"));

            // ================= LƯU OTP =================
            $stmtDel = $conn->prepare("DELETE FROM otp_reset WHERE username=?");
            $stmtDel->bind_param("s", $username);
            $stmtDel->execute();

            $stmt2 = $conn->prepare("INSERT INTO otp_reset(username,email,otp,expire) VALUES(?,?,?,?)");
            $stmt2->bind_param("ssss", $username, $email, $otp, $expire);
            $stmt2->execute();

            // ================= GỬI MAIL =================
            $subject = "OTP Reset Password";
            $message = "Ma OTP cua ban la: $otp (co hieu luc 5 phut)";
            $headers = "From: no-reply@ctec.edu.vn\r\n";
            $headers .= "Reply-To: no-reply@ctec.edu.vn\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";  
            if (mail($email, $subject, $message, $headers)) {
                $_SESSION['reset_user'] = $username;
                header("Location: reset_password.php");
                exit;
            } else {
                // fallback khi mail fail (rất hay xảy ra localhost)
                $msg = "OTP của bạn là: $otp (do hệ thống không gửi được email)";
            }
        }
    }
}
?>
<style>
        /* BACKGROUND */
    body {
        background: linear-gradient(135deg, #e8f5e9, #f1f8f4);
        font-family: 'Segoe UI', sans-serif;
    }

    /* CONTAINER */
    .otp-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
    }

    /* CARD */
    .otp-card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        overflow: hidden;
        animation: fadeIn 0.4s ease;
    }

    /* HEADER */
    .otp-header {
        background: linear-gradient(135deg, #2e7d32, #66bb6a);
        color: #fff;
        text-align: center;
        padding: 25px;
    }

    .otp-header h2 {
        margin-bottom: 5px;
    }

    .otp-header p {
        font-size: 14px;
        opacity: 0.9;
    }

    /* FORM */
    .otp-form {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        display: block;
        margin-bottom: 6px;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #ccc;
        transition: 0.3s;
        outline: none;
    }

    .form-group input:focus {
        border-color: #2e7d32;
        box-shadow: 0 0 5px rgba(46,125,50,0.3);
    }

    /* BUTTON */
    .btn-otp {
        width: 100%;
        padding: 13px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #2e7d32, #43a047);
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-otp:hover {
        background: linear-gradient(135deg, #1b5e20, #2e7d32);
        transform: translateY(-2px);
    }

    /* FOOTER */
    .otp-footer {
        text-align: center;
        padding-bottom: 20px;
    }

    .otp-footer a {
        color: #2e7d32;
        text-decoration: none;
        font-size: 14px;
    }

    .otp-footer a:hover {
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

    /* MOBILE */
    @media (max-width: 480px) {
        .otp-card {
            border-radius: 15px;
        }

        .otp-form {
            padding: 20px;
        }
    }
</style>
<?php include 'include_header.php'?>
<div class="otp-container">
    <div class="otp-card">

        <div class="otp-header">
            <h2>Quên mật khẩu</h2>
            <p>Nhập thông tin để nhận mã OTP</p>
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
        <form method="POST" class="otp-form">

            <div class="form-group">
                <label>Tên đăng nhập</label>
                <input 
                    type="text" 
                    name="username" 
                    placeholder="Nhập tên đăng nhập..."
                    required
                >
            </div>

            <div class="form-group">
                <label>Email</label>
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Nhập email của bạn..."
                    required
                >
            </div>

            <button class="btn-otp">
                Gửi mã OTP
            </button>

        </form>

        <div class="otp-footer">
            <a href="login.php">← Quay lại đăng nhập</a>
        </div>

    </div>
</div>
<?php  include 'include_footer.php'?>
