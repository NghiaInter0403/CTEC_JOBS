<?php
session_start();
include 'ketnoi.php';

// Tìm kiếm
$tukhoa = $_GET['keyword'] ?? '';
$diadiem = $_GET['location'] ?? '';

$sql = "SELECT vl.* FROM vieclam vl WHERE vl.trangthai = 'daduyet' ";

if ($keyword) {
    $sql .= " AND (vl.tieude LIKE '%$keyword%' OR vl.tencongty LIKE '%$keyword%')";
}
if ($location) {
    $sql .= " AND vl.diadiem LIKE '%$location%'";
}

$sql .= " ORDER BY vl.ngaydang DESC";

$result = mysqli_query($conn, $sql);
?>

<?php include 'include_header.php'; ?>
<link rel="stylesheet" href="style.css">

<div class="container mt-4">
    <h2>Việc làm cho sinh viên</h2>

    <!-- Form tìm kiếm -->
    <form method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" name="keyword" class="form-control"
                       placeholder="Tìm theo từ khóa..."
                       value="<?= $keyword ?>">
            </div>
            <div class="col-md-5">
                <input type="text" name="location" class="form-control"
                       placeholder="Khu vực..."
                       value="<?= $location ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Tìm</button>
            </div>
        </div>
    </form>

    <div class="row">
        <?php while ($job = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-6 mb-3">
            <div class="card p-3">
                <h5 style="color:blue"><?= $job['tieude'] ?></h5>
                <p><strong><?= $job['tencongty'] ?></strong></p>
                <p>Lương: <?= $job['mucluong'] ?> | <?= $job['diadiem'] ?></p>

                <a href="vl_chitiet.php?id=<?= $job['id'] ?>"
                   class="btn btn-sm btn-primary">
                    Xem chi tiết
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php include 'include_footer.php'; ?>
