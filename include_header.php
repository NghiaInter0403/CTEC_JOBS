
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Việc Làm Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
<<<<<<< HEAD
        <img src="img/LOGO.png" style="width: 250px; height: auto;">
        <a class="navbar-brand" href="index.php"></a>
=======
        <a class="navbar-brand" href="index.php">JobSV</a>
>>>>>>> ebde7a9259c463f1d6351903e82e413fcc790660
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="vl_danhsach.php">Tin tuyển dụng</a></li>
                <div class="dropdown">
        </div>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Xin chào, <?php echo $_SESSION['name']; ?>
                        </a>
                        <ul class="dropdown-menu">
<<<<<<< HEAD
                            <?php if ($_SESSION['role'] == 'student'): ?>
                                <li><a class="dropdown-item" href="sv_trangchu.php">Trang cá nhân</a></li>
                            <?php elseif ($_SESSION['role'] == 'employer'): ?>
                                <li><a class="dropdown-item" href="cty_trangchu.php">Dashboard</a></li>
=======
                            <?php if ($_SESSION['role'] == 'sinhvien'): ?>
                                <li><a class="dropdown-item" href="sv_trangchu.php">Trang cá nhân</a></li>
                            <?php elseif ($_SESSION['role'] == 'nhatuyendung'): ?>
                                <li><a class="dropdown-item" href="cty_trangchu.php">Trang chủ</a></li>
>>>>>>> ebde7a9259c463f1d6351903e82e413fcc790660
                                <li><a class="dropdown-item" href="cty_dangtuyen.php">Đăng tin</a></li>
                                <li><a class="dropdown-item" href="cty_ungvien.php">Xem ứng viên</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="dangxuat.php">Đăng xuất</a></li>
<<<<<<< HEAD
                            <?php elseif ($_SESSION['role'] == 'admin'): ?>
=======
                            <?php elseif ($_SESSION['role'] == 'quatrivien'): ?>
>>>>>>> ebde7a9259c463f1d6351903e82e413fcc790660
                                <li><a class="dropdown-item" href="ad_trangchu.php">Quản trị</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="dangxuat.php">Đăng xuất</a></li>
                           </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Đăng nhập</a></li>
                    <li class="nav-item"><a class="nav-link" href="dangki.php">Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>