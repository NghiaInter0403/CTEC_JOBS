<?php
session_start();
include 'ketnoi.php';

if ($_POST) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['matkhau'];

    $sql = "SELECT * FROM nguoidung WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['hoten'];
        $_SESSION['role'] = $user['vaitro'];

        // Chuyển hướng theo vai trò
        if ($user['role'] == 'sinhvien') {
            header("Location: sv_trangchu.php");
        } elseif ($user['role'] == 'nhatuyendung') {
            header("Location: cty_trangchu.php");
        } elseif ($user['role'] == 'admin') {
            header("Location: ad_trangchu.php");
        }
        exit;
    } else {
        $error = "Email hoặc mật khẩu sai!";
    }
}
?>

<?php include 'include_header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><h4>Đăng nhập</h4></div>
                <div class="card-body">
                    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Mật khẩu</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                    </form>
                    <p class="mt-3 text-center">Chưa có tài khoản? <a href="dangki.php">Đăng ký</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'include_footer.php'; ?>