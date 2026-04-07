<?php
session_start();
include 'ketnoi.php';

// === PHÂN TRANG ===
$limit = 10;                    // Số tin tuyển dụng trên 1 trang 
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

// === TÌM KIẾM & LỌC ===
$tukhoa  = $_GET['tukhoa']  ?? '';
$nganh   = $_GET['nganh']   ?? '';
$diadiem = $_GET['diadiem'] ?? '';

// Xây dựng điều kiện WHERE
$where = "WHERE vl.trangthai = 'daduyet'";
if ($tukhoa) {
    $where .= " AND (vl.tieude LIKE '%$tukhoa%' OR vl.tencongty LIKE '%$tukhoa%')";
}
if ($nganh) {
    $where .= " AND vl.nganhnghe = '$nganh'";
}
if ($diadiem) {
    $where .= " AND vl.diadiem = '$diadiem'";
}

// 1. Đếm tổng số bản ghi (để tính tổng trang)
$count_sql = "SELECT COUNT(*) as total FROM vieclam vl JOIN nguoidung nd ON vl.idnhatuyendung = nd.id $where";
$count_result = mysqli_query($conn, $count_sql);
$total_row = mysqli_fetch_assoc($count_result)['total'];

$total_pages = ceil($total_row / $limit);

// 2. Truy vấn dữ liệu trang hiện tại
$sql = "SELECT vl.*, nd.hoten as company 
        FROM vieclam vl 
        JOIN nguoidung nd ON vl.idnhatuyendung = nd.id 
        $where 
        ORDER BY vl.ngaydang DESC 
        LIMIT $limit OFFSET $offset";

$ketqua = mysqli_query($conn, $sql);
?>

<?php include 'include_header.php'; ?>
<link rel="stylesheet" href="style.css">

<div class="container mt-4">
    <h2>Tin tuyển dụng</h2>

    <!-- Form tìm kiếm -->
    <form method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="tukhoa" class="form-control" placeholder="Tìm theo từ khóa..." value="<?php echo htmlspecialchars($tukhoa); ?>">
            </div>
            <div class="col-md-3">
                <select name="nganh" class="form-select">
                    <option value="">Tất cả ngành nghề</option>
                    <option value="Công nghệ thông tin" <?php if($nganh=='Công nghệ thông tin') echo 'selected'; ?>>Công nghệ thông tin</option>
                    <option value="Marketing" <?php if($nganh=='Marketing') echo 'selected'; ?>>Marketing</option>
                    <option value="Kinh doanh" <?php if($nganh=='Kinh doanh') echo 'selected'; ?>>Kinh doanh</option>
                    <option value="Nông Nghiệp" <?php if($nganh=='Nông Nghiệp') echo 'selected'; ?>>Nông Nghiệp</option>
                    <option value="Kế Toán" <?php if($nganh=='Kế Toán') echo 'selected'; ?>>Kế Toán</option>
                    <option value="Gia Sư" <?php if($nganh=='Gia Sư') echo 'selected'; ?>>Gia Sư</option>
                    <option value="Bán Thời Gian" <?php if($nganh=='Bán Thời Gian') echo 'selected'; ?>>Bán Thời Gian</option>
                    <option value="Freelancer" <?php if($nganh=='Freelancer') echo 'selected'; ?>>Freelancer</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="diadiem" class="form-control" placeholder="Tìm khu vực..." value="<?php echo htmlspecialchars($diadiem); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tìm</button>
            </div>
        </div>
    </form>

    <!-- Kết quả -->
    <?php if (mysqli_num_rows($ketqua) > 0): ?>
        <div class="row">
            <?php while ($vieclam = mysqli_fetch_assoc($ketqua)): ?>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h2 style="color:blue"><?php echo htmlspecialchars($vieclam['tieude']); ?></h2>
                        <h4><strong><?php echo htmlspecialchars($vieclam['tencongty']); ?></strong></h4>
                        <h4>Lương: <?php echo htmlspecialchars($vieclam['mucluong']); ?> | <?php echo htmlspecialchars($vieclam['diadiem']); ?></h4>
                        <a href="vl_chitiet.php?id=<?php echo $vieclam['id']; ?>" class="btn btn-sm btn-primary">Xem chi tiết</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- PHÂN TRANG -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <!-- Trang trước -->
                <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page-1])); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo; Trước</span>
                    </a>
                </li>

                <!-- Các số trang -->
                <?php
                $range = 2; // hiển thị ... 2 trang trước và 2 trang sau trang hiện tại
                $start = max(1, $page - $range);
                $end   = min($total_pages, $page + $range);

                if ($start > 1): ?>
                    <li class="page-item"><a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>">1</a></li>
                    <?php if ($start > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item"><a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $total_pages])); ?>"><?php echo $total_pages; ?></a></li>
                <?php endif; ?>

                <!-- Trang sau -->
                <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page+1])); ?>" aria-label="Next">
                        <span aria-hidden="true">Sau &raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-info text-center">Không tìm thấy tin tuyển dụng nào phù hợp.</div>
    <?php endif; ?>
</div>

<?php include 'include_footer.php'; ?>