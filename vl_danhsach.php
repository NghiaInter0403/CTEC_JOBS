<?php
session_start();
include 'ketnoi.php';

// Tìm kiếm & lọc
$tukhoa = $_GET['tukhoa'] ?? '';
$nganh = $_GET['nganh'] ?? '';
$diadiem = $_GET['diadiem'] ?? '';

$sql = "SELECT vl.*, nd.hoten as company FROM vieclam vl JOIN nguoidung nd ON vl.idnhatuyendung = nd.id WHERE vl.trangthai = 'daduyet' ";
if ($tukhoa) $sql .= " AND (vl.tieude LIKE '%$tukhoa%' OR vl.tencongty LIKE '%$tukhoa%')";
if ($nganh) $sql .= " AND vl.nganhnghe = '$nganh'";
if ($diadiem) $sql .= " AND vl.diadiem = '$diadiem'";
$sql .= " ORDER BY vl.ngaydang DESC";

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
                <input type="text" name="tukhoa" class="form-control" placeholder="Tìm theo từ khóa..." value="<?php echo $tukhoa; ?>">
            </div>
            <div class="col-md-3">
                <select name="nganh" class="form-select">
                    <option value="">Tất cả ngành nghề</option>
                    <option value="IT" <?php if($nganh=='Công nghệ thông tin') echo 'selected'; ?>>Công nghệ thông tin</option>
                    <option value="Marketing" <?php if($nganh=='Marketing') echo 'selected'; ?>>Marketing</option>
                    <option value="Kinh doanh" <?php if($nganh=='Kinh doanh') echo 'selected'; ?>>Kinh doanh</option>
                    <option value="Nông Nghiệp" <?php if($nganh=='Nông Nghiệp') echo 'selected'; ?>>Nông Nghiệp</option>
                    <option value="Kế Toán" <?php if($nganh=='Kế Toán') echo 'selected'; ?>>Kế Toán</option>
                    <option value="Gia Sư" <?php if($nganh=='Gia Sư') echo 'selected'; ?>>Gia SƯ</option>
                    <option value="Bán Thời Gian" <?php if($nganh=='Bán Thời Gian') echo 'selected'; ?>>Bán Thời Gian</option>
                    <option value="Freelancer" <?php if($nganh=='Freelancer') echo 'selected'; ?>>Freelancer</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="diadiem" class="form-control" placeholder="Tìm khu vực..." value="<?php echo $diadiem; ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tìm</button>
            </div>
        </div>
    </form>

    <div class="row">
        <?php while ($vieclam = mysqli_fetch_assoc($ketqua)): ?>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 style="color:blue"><?php echo $vieclam['tieude']; ?></h5>
                    <p><strong><?php echo $vieclam['tencongty']; ?></strong></p>
                    <p>Lương: <?php echo $vieclam['mucluong']; ?> | <?php echo $vieclam['diadiem']; ?></p>
                    <a href="vl_chitiet.php?id=<?php echo $vieclam['id']; ?>" class="btn btn-sm btn-primary">Xem chi tiết</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'include_footer.php'; ?>