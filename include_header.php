
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Việc Làm Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <img src="img/LOGO.png" style="width: 250px; height: auto;">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav" style="font-size: 20px;">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-fill"></i> Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="vl_danhsach.php"><i class="bi bi-newspaper"></i> Tin tuyển dụng</a></li>
                <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-globe"></i> Liên Hệ
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="https://www.ctec.edu.vn/ctec/" target="_blank"> Trang chủ CTEC</a></li>
                            <li><a class="dropdown-item" href="https://www.facebook.com/truongcdktktct?locale=vi_VN"  target="_blank">Facebook CTEC</a></li>
                            <li><a class="dropdown-item" href="https://www.facebook.com/pcthssvctec?locale=vi_VN"  target="_blank">Phòng CTHSSV</a></li>
                        </ul>
                <div class="dropdown">
        </div>
            </ul>
            <ul class="navbar-nav">
                <!-- hiển thị người dùng -->
                <?php if (isset($_SESSION['id_nguoidung'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['hoten']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- câu điều kiện để echo menu -->
                             <!-- phân biệt bằng vaitro -->
                            <?php if ($_SESSION['vaitro'] == 'sinhvien'): ?>
                                <li><a class="dropdown-item" href="sv_trangchu.php">Trang cá nhân</a></li>
                                <li><a class="dropdown-item" href="sv_hoso.php">Xem hồ sơ</a></li>
                                <li><a class="dropdown-item" href="dangxuat.php">Đăng xuất</a></li>
                            <?php elseif ($_SESSION['vaitro'] == 'nhatuyendung'): ?>
                                <li><a class="dropdown-item" href="cty_trangchu.php">Trang chủ</a></li>
                                <li><a class="dropdown-item" href="cty_dangtuyen.php">Đăng tin</a></li>
                                <li><a class="dropdown-item" href="cty_ungvien.php">Xem ứng viên</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="dangxuat.php">Đăng xuất</a></li>
                            <?php elseif ($_SESSION['vaitro'] == 'quantrivien'): ?>
                                <li><a class="dropdown-item" href="ad_trangchu.php">Quản trị</a></li>
                                <li><a class="dropdown-item" href="dangxuat.php">Đăng xuất</a></li>
                            <?php endif; ?>
                           </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Đăng nhập</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
</nav>