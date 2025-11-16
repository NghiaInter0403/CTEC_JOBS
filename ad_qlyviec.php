<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'quantrivien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';

// Duyệt / Từ chối
if (isset($_GET['daduyet'])) {
    $id = $_GET['daduyet'];
    mysqli_query($conn, "UPDATE vieclam SET trangthai = 'daduyet' WHERE id = '$id'");
}
if (isset($_GET['tuchoi'])) {
    $id = $_GET['tuchoi'];
    mysqli_query($conn, "UPDATE vieclam SET trangthai = 'tuchoi' WHERE id = '$id'");
}

$sql = "SELECT j.*, u.hoten as nhatuyedung FROM vieclam j JOIN nguoidung u ON j.idnhatuyendung = u.id ORDER BY j.ngaydang DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4">
    <h3>Quản lý tin tuyển dụng</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Công ty</th>
                <th>Nhà tuyển dụng</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <body>
            <?php while ($job = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $job['tieude']; ?></td>
                <td><?php echo $job['tencongty']; ?></td>
                <td><?php echo $job['nhatuyendung']; ?></td>
                <td>
                    <span class="badge bg-<?php echo $job['trangthai'] == 'daduyet' ? 'success' : ($job['trangthai'] == 'tuchoi' ? 'danger' : 'warning'); ?>">
                        <?php echo ucfirst($job['trangthai']); ?>
                    </span>
                </td>
                <td>
                    <?php if ($job['trangthai'] == 'choxuly'): ?>
                        <a href="?daduyet=<?php echo $job['id']; ?>" class="btn btn-sm btn-success">Duyệt</a>
                        <a href="?tuchoi=<?php echo $job['id']; ?>" class="btn btn-sm btn-danger">Từ chối</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </body>
    </table>
</div>
<?php include 'include_footer.php'; ?>