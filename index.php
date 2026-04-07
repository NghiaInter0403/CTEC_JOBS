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
<body>
<!-- kết nối csdl và bắt đầu session -->
<?php 
session_start(); 
include 'ketnoi.php'; 
include 'include_header.php';
?>

<div class="container mt-5">
    <div class="jumbotron text-center bg-light p-5 rounded shadow-sm" >
        <h1 class="display-4 fw-bold text-black">Tìm Việc Làm Dành Cho Sinh Viên</h1>
        <p class="lead text-black fw-medium">Thực tập • Việc làm thêm • Part-time • Full-time</p>
        <a href="vl_danhsach.php" class="btn btn-primary btn-lg px-5 py-3" style="color: black;">
            Xem việc làm ngay
        </a>
    </div>

    <h3 class="mt-5 mb-4 text-center fw-bold " style="font-size: 50px;">Tin tuyển dụng mới nhất</h3>
    <div class="row g-4">
    <?php
    $sql = "SELECT vl.*, nd.hoten as company FROM vieclam vl JOIN nguoidung nd ON vl.idnhatuyendung = nd.id WHERE vl.trangthai = 'daduyet' ORDER BY vl.ngaydang DESC LIMIT 6";
    $ketqua = mysqli_query($conn, $sql);
    if (mysqli_num_rows($ketqua) > 0):
        while ($vieclam = mysqli_fetch_assoc($ketqua)):
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow hover-shadow transition" style="border-radius: 15px;">
            <div class="card-body d-flex flex-column p-4"> <h4 class="card-title text-primary fw-bold mb-3">
                    <?php echo htmlspecialchars($vieclam['tieude']); ?>
                </h4>
                
                <p class="card-text text-dark mb-4" style="line-height: 1.8;">
                    <span class="d-block mb-2">
                        <i class="fas fa-building text-secondary me-2"></i> 
                        <strong><?php echo htmlspecialchars($vieclam['tencongty']); ?></strong>
                    </span>
                    <span class="d-block mb-2 text-danger fw-bold">
                        <i class="fas fa-money-bill-wave me-2"></i> 
                        Lương: <?php echo htmlspecialchars($vieclam['mucluong']); ?>
                    </span>
                    <span class="d-block">
                        <i class="fas fa-map-marker-alt text-secondary me-2"></i> 
                        Địa điểm: <?php echo htmlspecialchars($vieclam['diadiem']); ?>
                    </span>
                </p>
                
                <div class="mt-auto">
                    <a href="vl_chitiet.php?id=<?php echo $vieclam['id']; ?>" 
                       class="btn btn-primary fw-bold w-100 py-2" style="border-radius: 10px;">
                       Xem chi tiết việc làm
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php 
        endwhile;
    else: 
    ?>
    <div class="col-12 text-center py-5">
        <h3 class="text-muted">Chưa có tin tuyển dụng nào hiện dụng.</h3>
    </div>
    <?php endif; ?>
</div>
</div>

<?php include 'include_footer.php'; ?>
</body>
</html>