<?php
session_start();
include 'ketnoi.php';

// Tìm kiếm & lọc
$keyword = $_GET['keyword'] ?? '';
$category = $_GET['category'] ?? '';
$location = $_GET['location'] ?? '';

$sql = "SELECT vl.*, nd.hoten as company FROM vieclam vl JOIN nguoidung nd ON vl.idnhatuyendung = nd.id WHERE vl.trangthai = 'daduyet' ";
if ($keyword) $sql .= " AND (vl.tieude LIKE '%$keyword%' OR vl.tencongty LIKE '%$keyword%')";
if ($category) $sql .= " AND vl.nganhnghe = '$category'";
if ($location) $sql .= " AND vl.diadiem = '$location'";
$sql .= " ORDER BY vl.ngaydang DESC";

$result = mysqli_query($conn, $sql);
?>

<?php include 'include_header.php'; ?>
<link rel="stylesheet" href="style.css">
<div class="container mt-4">
    <h2>Tin tuyển dụng</h2>

    <!-- Form tìm kiếm -->
    <form method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo từ khóa..." value="<?php echo $keyword; ?>">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Tất cả ngành nghề</option>
                    <option value="IT" <?php if($category=='IT') echo 'selected'; ?>>Công nghệ thông tin</option>
                    <option value="Marketing" <?php if($category=='Marketing') echo 'selected'; ?>>Marketing</option>
                    <option value="Kinh doanh" <?php if($category=='Kinh doanh') echo 'selected'; ?>>Kinh doanh</option>
                    <option value="Kinh doanh" <?php if($category=='Nông Nghiệp') echo 'selected'; ?>>Nông Nghiệp</option>
                    <option value="Kinh doanh" <?php if($category=='Kế Toán') echo 'selected'; ?>>Kế Toán</option>
                    <option value="Kinh doanh" <?php if($category=='Gia Sư') echo 'selected'; ?>>Gia SƯ</option>
                    <option value="Kinh doanh" <?php if($category=='Bán Thời Gian') echo 'selected'; ?>>Bán Thời Gian</option>
                    <option value="Kinh doanh" <?php if($category=='Freelancer') echo 'selected'; ?>>Freelancer</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="location" class="form-control" placeholder="Tìm khu vực..." value="<?php echo $location; ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tìm</button>
            </div>
        </div>
    </form>

    <div class="row">
        <?php while ($job = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5><?php echo $job['tieude']; ?></h5>
                    <p><strong><?php echo $job['tencongty']; ?></strong></p>
                    <p>Lương: <?php echo $job['luong']; ?> | <?php echo $job['diadiem']; ?></p>
                    <a href="vl_chitiet.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-primary">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'include_footer.php'; ?>