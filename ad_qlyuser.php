<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'quantrivien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';

// Xóa user
if (isset($_GET['delete'])) {
    $idnguoidung = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM nguoidung WHERE id = '$idnguoidung' AND vaitro != 'quantrivien'");
}

$sql = "SELECT * FROM nguoidung WHERE vaitro != 'quantrivien' ORDER BY ngaytao DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4">
    <h3>Quản lý người dùng</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Ngày đăng ký</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($nguoidung = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $nguoidung['id']; ?></td>
                <td><?php echo $nguoidung['hoten']; ?></td>
                <td><?php echo $nguoidung['email']; ?></td>
                <td><?php echo $nguoidung['vaitro'] == 'sinhvien' ? 'Sinh viên' : 'Nhà tuyển dụng'; ?></td>
                <td><?php echo date('d/m/Y', strtotime($user['ngaytao'])); ?></td>
                <td>
                    <a href="?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include 'include_footer.php'; ?>