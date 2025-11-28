<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'sinhvien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$id_nguoidung = $_SESSION['user_id'];

$sql = "SELECT a.*, j.tieude, j.tencongty, j.mucluong, a.trangthai 
        FROM donungvien a 
        JOIN vieclam j ON a.idvieclam = j.id 
        WHERE a.idsinhvien = '$id_nguoidung'
        ORDER BY a.ngaynop DESC";
$ketqua = mysqli_query($conn, $sql);
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4">
    <h3>Lịch sử ứng tuyển</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Vị trí</th>
                <th>Công ty</th>
                <th>Lương</th>
                <th>Ngày ứng tuyển</th>
                <th>Thông báo từ công ty</th>
            </tr>
        </thead>
        <body>
            <?php while ($row = mysqli_fetch_assoc($ketqua)): ?>
            <tr>
                <td><?php echo $row['tieude']; ?></td>
                <td><?php echo $row['tencongty']; ?></td>
                <td><?php echo $row['mucluong']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($row['ngaynop'])); ?></td>
                <td>
                    <span class="badge bg-<?php
                        echo $row['trangthai'] == 'chapnhan' ? 'success' :
                             ($row['trangthai'] == 'tuchoi' ? 'danger' :
                             ($row['trangthai'] == 'daxem' ? 'info' : 'warning'));
                    ?>">
                        <?php echo ucfirst($row['trangthai']); ?>
                    </span>
                </td>
            </tr>
            <?php endwhile; ?>
        </body>
    </table>
</div>
<?php include 'include_footer.php'; ?>