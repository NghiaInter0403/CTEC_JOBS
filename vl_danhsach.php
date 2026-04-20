<?php
session_start();
include 'ketnoi.php';
include 'zalo.php';
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
<style>
    /* CONTAINER */
.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

/* TITLE */
.page-title {
    text-align: center;
    font-size: 32px;
    color: #2e7d32;
    margin-bottom: 25px;
}

/* FORM */
.search-form {
    display: grid;
    grid-template-columns: repeat(4, 1fr) 120px;
    gap: 10px;
    margin-bottom: 30px;
}

.search-form input,
.search-form select {
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ccc;
}

.search-form button {
    background: #2e7d32;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
}

.search-form button:hover {
    background: #1b5e20;
}

/* GRID */
.job-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

/* CARD */
.job-card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.job-card:hover {
    transform: translateY(-5px);
}

.job-card h3 {
    color: #2e7d32;
    margin-bottom: 10px;
}

.company {
    font-weight: bold;
    margin-bottom: 10px;
}

.info {
    margin-bottom: 15px;
}

.salary {
    color: #d32f2f;
    font-weight: bold;
}

.btn-detail {
    display: block;
    text-align: center;
    font-weight: bold;
    background: #2e7d32;
    color: white;
    padding: 10px;
    border-radius: 10px;
    text-decoration: none;
}

.btn-detail:hover {
    background: #1b5e20;
}

/* PAGINATION */
.pagination {
    margin-top: 30px;
    text-align: center;
}

.page-number, .page-btn {
    display: inline-block;
    margin: 5px;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    background: #e8f5e9;
    color: #2e7d32;
}

.page-number.active {
    background: #2e7d32;
    color: white;
}

.page-btn.disabled {
    pointer-events: none;
    opacity: 0.5;
}

/* EMPTY */
.empty {
    text-align: center;
    padding: 40px;
    color: gray;
}

/* RESPONSIVE */
@media (max-width: 992px) {
    .search-form {
        grid-template-columns: repeat(2, 1fr);
    }

    .job-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<?php include 'include_header.php'; ?>

<div class="container">

    <h2 class="page-title">Tin tuyển dụng</h2>

    <!-- FORM -->
    <form method="GET" class="search-form">
        <input type="text" name="tukhoa" placeholder="Tìm theo từ khóa..." 
            value="<?php echo htmlspecialchars($tukhoa); ?>">

        <select name="nganh">
            <option value="">Tất cả ngành nghề</option>
            <option value="Công nghệ thông tin" <?= $nganh=='Công nghệ thông tin'?'selected':'' ?>>Công nghệ thông tin</option>
            <option value="Marketing" <?= $nganh=='Marketing'?'selected':'' ?>>Marketing</option>
            <option value="Kinh doanh" <?= $nganh=='Kinh doanh'?'selected':'' ?>>Kinh doanh</option>
            <option value="Nông Nghiệp" <?= $nganh=='Nông Nghiệp'?'selected':'' ?>>Nông Nghiệp</option>
            <option value="Kế Toán" <?= $nganh=='Kế Toán'?'selected':'' ?>>Kế Toán</option>
            <option value="Gia Sư" <?= $nganh=='Gia Sư'?'selected':'' ?>>Gia Sư</option>
            <option value="Bán Thời Gian" <?= $nganh=='Bán Thời Gian'?'selected':'' ?>>Bán Thời Gian</option>
            <option value="Freelancer" <?= $nganh=='Freelancer'?'selected':'' ?>>Freelancer</option>
        </select>

        <input type="text" name="diadiem" placeholder="Tìm khu vực..." 
            value="<?php echo htmlspecialchars($diadiem); ?>">

        <button type="submit">Tìm</button>
    </form>

    <!-- LIST -->
    <?php if (mysqli_num_rows($ketqua) > 0): ?>
        <div class="job-grid">
            <?php while ($vieclam = mysqli_fetch_assoc($ketqua)): ?>
                <div class="job-card">
                    <h3><?php echo htmlspecialchars($vieclam['tieude']); ?></h3>
                    <p class="company"><?php echo htmlspecialchars($vieclam['tencongty']); ?></p>

                    <p class="info">
                        <span class="salary"><?php echo htmlspecialchars($vieclam['mucluong']); ?></span>  
                        • <?php echo htmlspecialchars($vieclam['diadiem']); ?>
                    </p>

                    <a href="vl_chitiet.php?id=<?php echo $vieclam['id']; ?>" class="btn-detail">
                        Xem chi tiết
                    </a>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- PAGINATION -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            
            <!-- prev -->
            <a class="page-btn <?= $page<=1?'disabled':'' ?>" 
               href="?<?= http_build_query(array_merge($_GET, ['page'=>$page-1])) ?>">
               « Trước
            </a>

            <?php
            $range = 2;
            $start = max(1, $page - $range);
            $end = min($total_pages, $page + $range);

            for ($i=$start; $i <= $end; $i++): ?>
                <a class="page-number <?= $i==$page?'active':'' ?>" 
                   href="?<?= http_build_query(array_merge($_GET, ['page'=>$i])) ?>">
                   <?= $i ?>
                </a>
            <?php endfor; ?>

            <!-- next -->
            <a class="page-btn <?= $page>=$total_pages?'disabled':'' ?>" 
               href="?<?= http_build_query(array_merge($_GET, ['page'=>$page+1])) ?>">
               Sau »
            </a>

        </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="empty">Không tìm thấy tin tuyển dụng nào.</div>
    <?php endif; ?>

</div>

<?php include 'include_footer.php'; ?>