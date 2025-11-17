<?php 
session_start(); 
include 'ketnoi.php'; 
include 'include_header.php'; // ĐÃ CÓ BOOTSTRAP + CSS + root_url()
?>

<div class="container mt-5">
    <div class="jumbotron text-center bg-light p-5 rounded shadow-sm">
        <h1 class="display-4 fw-bold text-white">Tìm Việc Làm Dành Cho Sinh Viên</h1>
        <p class="lead text-white fw-medium">Thực tập • Việc làm thêm • Part-time • Full-time</p>
        <a href="vl_danhsach.php" class="btn btn-primary btn-lg px-5 py-3">
            Xem việc làm ngay
        </a>
    </div>

    <h3 class="mt-5 mb-4 text-center fw-bold">Tin tuyển dụng mới nhất</h3>
    <div class="row g-4">
        <?php
        $sql = "SELECT vl.*, nd.hoten as company FROM vieclam vl JOIN nguoidung nd ON vl.idnhatuyendung = nd.id WHERE vl.trangthai = 'daduyet' ORDER BY vl.ngaydang DESC LIMIT 6";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0):
            while ($job = mysqli_fetch_assoc($result)):
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($job['tieude']); ?></h5>
                    <p class="card-text text-muted small">
                        <i class="fas fa-building me-1"></i> <strong><?php echo htmlspecialchars($job['tencongty']); ?></strong><br>
                        <i class="fas fa-money-bill-wave me-1"></i> Lương: <?php echo htmlspecialchars($job['mucluong']); ?><br>
                        <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($job['diadiem']); ?>
                    </p>
                    <div class="mt-auto">
                        <a href="vl_chitiet.php?id=<?php echo $job['id']; ?>" 
                           class="btn btn-outline-primary btn-sm w-100">
                           Xem chi tiết
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
            <p class="text-muted">Chưa có tin tuyển dụng nào.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'include_footer.php'; ?>