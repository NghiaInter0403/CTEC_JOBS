<?php
session_start();
include 'ketnoi.php';

// Tìm kiếm
$tukhoa = $_GET['tukhoa'] ?? '';
$diadiem = $_GET['diadiem'] ?? '';
$nganhnghe =$_GET['nganhnghe']?? '';
$sql = "SELECT vl.* FROM vieclam vl WHERE vl.trangthai = 'daduyet' ";

if ($tukhoa) {
    $sql .= " AND (vl.tieude LIKE '%$tukhoa%' OR vl.tencongty LIKE '%$tukhoa%')";
}
if ($diadiem) {
    $sql .= " AND vl.diadiem LIKE '%$diadiem%'";
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
            <div class="col-md-4">
                <input type="text" name="tukhoa" class="form-control"
                       placeholder="Tìm theo từ khóa..."
                       value="<?php $keyword ?>">
            </div>
             <div class="col-md-3">
                <select name="nganhnghe" class="form-select">
                    <option value="">Tất cả ngành nghề</option>
                    <option value="IT" <?php if($nganhnghe=='IT') echo 'selected'; ?>>Công nghệ thông tin</option>
                    <option value="Marketing" <?php if($nganhnghe=='Marketing') echo 'selected'; ?>>Marketing</option>
                    <option value="Kinh doanh" <?php if($nganhnghe=='Kinh doanh') echo 'selected'; ?>>Kinh doanh</option>
                    <option value="Kinh doanh" <?php if($nganhnghe=='Nông Nghiệp') echo 'selected'; ?>>Nông Nghiệp</option>
                    <option value="Kinh doanh" <?php if($nganhnghe=='Kế Toán') echo 'selected'; ?>>Kế Toán</option>
                    <option value="Kinh doanh" <?php if($nganhnghe=='Gia Sư') echo 'selected'; ?>>Gia SƯ</option>
                    <option value="Kinh doanh" <?php if($nganhnghe=='Bán Thời Gian') echo 'selected'; ?>>Bán Thời Gian</option>
                    <option value="Kinh doanh" <?php if($nganhnghe=='Freelancer') echo 'selected'; ?>>Freelancer</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="diadiem" class="form-control"
                       placeholder="Khu vực..."
                       value="<?php $diadiem ?>">
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
