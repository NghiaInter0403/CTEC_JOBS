<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tìm Việc Làm Sinh Viên - Part-time, Thực Tập, Việc Làm Thêm Mới Nhất</title>
    <meta name="description" content="Tuyển dụng việc làm thêm cho sinh viên: part-time, thực tập có lương, việc làm bán thời gian. Cập nhật mới mỗi ngày!">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Tìm Việc Làm Sinh Viên - Part-time & Thực Tập">
    <meta property="og:description" content="Hàng nghìn việc làm thêm dành cho sinh viên, cập nhật liên tục">
    <meta property="og:type" content="website">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
</head>

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: #f1f8f4;
    }

    .container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    /* HERO */
    .hero {
        background: linear-gradient(135deg, #2e7d32, #66bb6a);
        color: white;
        text-align: center;
        padding: 60px 20px;
        border-radius: 20px;
        margin-top: 20px;
    }

    .hero h1 {
        font-size: 42px;
        margin-bottom: 15px;
    }

    .hero p {
        font-size: 20px;
        margin-bottom: 25px;
    }

    .hero a {
        background: white;
        color: #1b5e20;
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
    }

    .hero a:hover {
        transform: translateY(-3px);
    }

    /* TITLE */
    .section-title {
        text-align: center;
        font-size: 36px;
        margin: 50px 0 30px;
        color: #2e7d32;
    }

    /* GRID */
    .job-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }

    /* CARD */
    .job-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        transition: 0.3s;
    }

    .job-card:hover {
        transform: translateY(-5px);
    }

    .job-card h4 {
        color: #2e7d32;
        margin-bottom: 15px;
    }

    .job-info {
        font-size: 15px;
        margin-bottom: 15px;
        line-height: 1.6;
    }

    .job-info i {
        width: 20px;
    }

    .salary {
        color: #d32f2f;
        font-weight: bold;
    }

    .job-btn {
        margin-top: auto;
        text-align: center;
        background: #2e7d32;
        color: white;
        padding: 10px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
    }

    .job-btn:hover {
        background: #1b5e20;
    }

    /* EMPTY */
    .empty {
        text-align: center;
        padding: 50px;
        color: gray;
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
        .job-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .job-grid {
            grid-template-columns: 1fr;
        }

        .hero h1 {
            font-size: 28px;
        }

        .hero p {
            font-size: 16px;
        }
    }
    </style>
<?php 
session_start(); 
include 'zalo.php'; 
include 'ketnoi.php'; 
include 'include_header.php';
?>

<div class="container">

    <!-- HERO -->
    <div class="hero">
        <h1>Tìm Việc Làm Dành Cho Sinh Viên</h1>
        <p>Thực tập • Việc làm thêm • Part-time • Full-time</p>
        <a href="vl_danhsach.php">Xem việc làm ngay</a>
    </div>

    <!-- TITLE -->
    <h2 class="section-title">Tin tuyển dụng mới nhất</h2>

    <!-- JOB LIST -->
    <div class="job-grid">
    <?php
    $sql = "SELECT vl.*, nd.hoten as company FROM vieclam vl 
            JOIN nguoidung nd ON vl.idnhatuyendung = nd.id 
            WHERE vl.trangthai = 'daduyet' 
            ORDER BY vl.ngaydang DESC LIMIT 6";

    $ketqua = mysqli_query($conn, $sql);

    if (mysqli_num_rows($ketqua) > 0):
        while ($vieclam = mysqli_fetch_assoc($ketqua)):
    ?>

        <div class="job-card">
            <h4><?php echo htmlspecialchars($vieclam['tieude']); ?></h4>

            <div class="job-info">
                <div><i class="fas fa-building"></i> <?php echo htmlspecialchars($vieclam['tencongty']); ?></div>
                <div class="salary"><i class="fas fa-money-bill-wave"></i> <?php echo htmlspecialchars($vieclam['mucluong']); ?></div>
                <div><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($vieclam['diadiem']); ?></div>
            </div>

            <a href="vl_chitiet.php?id=<?php echo $vieclam['id']; ?>" class="job-btn">
                Xem chi tiết
            </a>
        </div>

    <?php 
        endwhile;
    else:
    ?>
        <div class="empty">
            <h3>Chưa có tin tuyển dụng nào.</h3>
        </div>
    <?php endif; ?>
    </div>

</div>

<?php include 'include_footer.php'; ?>

</body>
</html>