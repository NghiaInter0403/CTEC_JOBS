
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
<style>
    .custom-navbar {
        background: linear-gradient(135deg, #2e7d32, #4caf50);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .nav-container {
        max-width: 1320px;
        margin: auto;
        padding: 0 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 80px;
    }

    /* LOGO */
    .nav-logo img {
        width: 200px;
    }

    /* MENU */
    .nav-menu {
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding-top: 20px;
    }

    .nav-left, .nav-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .nav-menu a {
        color: #fff;
        font-size: 25px;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: 0.3s;
        text-decoration: none;
    }

    .nav-menu a:hover {
        background: rgba(255,255,255,0.2);
    }

    /* LOGIN BUTTON */
    .btn-login {
        color: #fff !important;
        border-radius: 20px;
        padding: 8px 18px;
        font-weight: bold;
    }

    /* DROPDOWN */
    .dropdown {
        position: relative;
    }

    .dropdown-menu {
        position: absolute;
        top: 110%;
        left: 0;
        background: #2e7d32;
        border-radius: 8px;
        min-width: 200px;
        display: none;
        flex-direction: column;
    }

    .dropdown-menu a {
        padding: 10px;
        font-size: 16px;
    }

    .dropdown:hover .dropdown-menu {
        display: flex;
    }

    /* MOBILE */
    .nav-toggle {
        display: none;
        font-size: 28px;
        color: white;
        background: none;
        border: none;
    }

    @media (max-width: 768px) {
        .nav-toggle {
            display: block;
        }

        .nav-menu {
            position: absolute;
            top: 80px;
            left: -100%;
            flex-direction: column;
            background: #2e7d32;
            width: 100%;
            padding: 20px;
        }

        .nav-menu.active {
            left: 0;
        }

        .nav-left, .nav-right {
            flex-direction: column;
            width: 100%;
        }

        .dropdown-menu {
            position: static;
            display: none;
        }

        .dropdown.active .dropdown-menu {
            display: flex;
        }
    }
    /* USER INFO */
    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* AVATAR */
    .nav-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        transition: 0.3s;
    }

    /* HOVER */
    .nav-avatar:hover {
        transform: scale(1.1);
        border-color: #c8e6c9;
    }
    .username-toggle {
    color: white;
    font-size: 18px;
    font-weight: 500;
    cursor: pointer;
    }

    .username-toggle:hover {
        text-decoration: underline;
    }
</style>
<body>
<?php
$avatar = "uploads/avatar/default.jpg";

if (isset($_SESSION['id_nguoidung']) && isset($conn)) {
    $id = $_SESSION['id_nguoidung'];

    $stmt = $conn->prepare("SELECT avatar FROM nguoidung WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $data = $result->fetch_assoc();
            if (!empty($data['avatar'])) {
                $avatar = "uploads/avatar/" . $data['avatar'];
            }
        }
        $stmt->close();
    }
}
if (isset($_SERVER['HTTP_REFERER'])) {
    // chỉ lưu nếu là link nội bộ
    if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false) {
        $_SESSION['last_page'] = $_SERVER['HTTP_REFERER'];
    }
}
?>
<header class="custom-navbar">
    <div class="nav-container">
        
        <!-- Logo -->
        <a href="index.php" class="nav-logo">
            <img src="img/LOGO.png" alt="Logo">
        </a>

        <!-- Nút mobile -->
        <button class="nav-toggle" id="menu-btn">
            <i class="bi bi-list"></i>
        </button>

        <!-- Menu -->
        <nav class="nav-menu" id="nav-menu">
            <ul class="nav-left">
                <li><a href="index.php"><i class="bi bi-house-fill"></i> Trang chủ</a></li>
                <li><a href="vl_danhsach.php"><i class="bi bi-newspaper"></i> Tin tuyển dụng</a></li>

                <!-- Dropdown -->
                <li class="dropdown">
                    <a href="#"><i class="bi bi-globe"></i> Liên hệ</a>
                    <ul class="dropdown-menu">
                        <li><a href="https://www.ctec.edu.vn/ctec/" target="_blank">Trang chủ CTEC</a></li>
                        <li><a href="https://www.facebook.com/truongcdktktct?locale=vi_VN" target="_blank">Facebook CTEC</a></li>
                        <li><a href="https://www.facebook.com/pcthssvctec?locale=vi_VN" target="_blank">Phòng CTHSSV</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="nav-right">
                <?php if (isset($_SESSION['id_nguoidung'])): ?>
                    <li class="dropdown">
                        <div class="user-info">
                            <a href="uploads_avatar.php">
                                <img src="<?php echo $avatar; ?>" class="nav-avatar">
                            </a> 
                            <span class="username-toggle">
                                <?php echo $_SESSION['hoten']; ?>
                            </span>
                        </div>
                        <ul class="dropdown-menu">
                            <?php if ($_SESSION['vaitro'] == 'sinhvien'): ?>
                                <li><a href="sv_trangchu.php">Trang cá nhân</a></li>
                                <li><a href="sv_hoso.php">Xem hồ sơ</a></li>
                                 <li><a href="doimatkhau.php">Đổi mật khẩu</a></li>
                                <li><a href="dangxuat.php">Đăng xuất</a></li>
                            <?php elseif ($_SESSION['vaitro'] == 'nhatuyendung'): ?>
                                <li><a href="cty_trangchu.php">Trang chủ</a></li>
                                <li><a href="cty_dangtuyen.php">Đăng tin</a></li>
                                <li><a href="ntd_hoso.php">Cập nhật thông tin công ty</a></li>
                                 <li><a href="doimatkhau.php">Đổi mật khẩu</a></li>
                                <li><a href="dangxuat.php">Đăng xuất</a></li>
                            <?php elseif ($_SESSION['vaitro'] == 'quantrivien'): ?>
                                <li><a href="ad_trangchu.php">Quản trị</a></li>
                                <li><a href="doimatkhau.php">Đổi mật khẩu</a></li>
                                <li><a href="dangxuat.php">Đăng xuất</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-login">Đăng nhập</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<script>
const menuBtn = document.getElementById("menu-btn");
const navMenu = document.getElementById("nav-menu");
menuBtn.onclick = () => {
    navMenu.classList.toggle("active");
};

// dropdown mobile
document.querySelectorAll(".dropdown > a").forEach(item => {
    item.addEventListener("click", function(e){
        if(window.innerWidth < 768){
            e.preventDefault();
            this.parentElement.classList.toggle("active");
        }
    });
});
</script>