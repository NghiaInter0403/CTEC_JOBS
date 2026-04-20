<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
include 'zalo.php';
$idnguoidung = $_SESSION['id_nguoidung'];
?>

<?php include 'include_header.php'; ?>

<style>
    /* Hiệu ứng giúp các ô card nổi bật trên nền xám */
    .sv-card {
        transition: transform 0.3s, shadow 0.3s;
        border: none;
        border-radius: 15px;
    }
    .sv-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
    }
    .sv-card a {
        text-decoration: none;
        display: block;
        padding: 20px;
    }
</style>

<div class="container mt-5 mb-5" style="min-height: 600px;">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Xin chào, <span class="text-primary"><?php echo $_SESSION['hoten']; ?></span>!</h1>
        <p class="fs-4 text-muted">Chào mừng <span><?php echo $_SESSION['hoten']; ?></span> quay trở lại. Hôm nay <span><?php echo $_SESSION['hoten']; ?></span> muốn làm gì?</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card sv-card text-center bg-primary shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <a href="sv_chitiet.php" class="text-white">
                        <i class="bi bi-person-lines-fill" style="font-size: 3rem;"></i>
                        <h4 class="mt-3 fw-bold">Cập nhật hồ sơ</h4>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card sv-card text-center bg-success shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <a href="sv_lichsu.php" class="text-white">
                        <i class="bi bi-clock-history" style="font-size: 3rem;"></i>
                        <h4 class="mt-3 fw-bold">Lịch sử ứng tuyển</h4>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card sv-card text-center bg-warning shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <a href="vl_danhsach.php" class="text-dark">
                        <i class="bi bi-search-heart" style="font-size: 3rem;"></i>
                        <h4 class="mt-3 fw-bold">Tìm việc làm</h4>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card sv-card text-center bg-info shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-center">
                    <a href="sv_hoso.php" class="text-white">
                        <i class="bi bi-file-earmark-person" style="font-size: 3rem;"></i>
                        <h4 class="mt-3 fw-bold">Hồ sơ của tôi</h4>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-lightbulb text-warning fs-1 me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-1">Mẹo nhỏ cho bạn:</h5>
                        <p class="mb-0 fs-5 text-muted">Hãy cập nhật hồ sơ đầy đủ để tăng 90% cơ hội được nhà tuyển dụng chú ý nhé!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include_footer.php'; ?>