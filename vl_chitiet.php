<?php
session_start();
include 'ketnoi.php';
$job_id = $_GET['id'] ?? 0;

$sql = "SELECT vl.*, nd.hoten as company FROM vieclam vl JOIN nguoidung nd ON vl.idnhatuyendung = nd.id WHERE vl.id = '$job_id' AND vl.trangthai = 'daduyet'";
$job = mysqli_fetch_assoc(mysqli_query($conn, $sql));

if (!$job) {
    header("Location: vl_danhsach.php");
    exit;
}

// Theo dõi lượt xem (tùy chọn)
mysqli_query($conn, "INSERT INTO thongke (trang) VALUES ('Việc làm_$job_id') ON DUPLICATE KEY UPDATE solanxem = solanxem + 1");
?>

<?php include 'include_header.php'; ?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3><?php echo $job['tieude']; ?></h3>
            <small class="text-muted">Đăng ngày: <?php echo date('d/m/Y', strtotime($job['ngaydang'])); ?></small>
        </div>
        <div class="card-body">
            <p><strong>Công ty:</strong> <?php echo $job['tencongty']; ?></p>
            <p><strong>Mức lương:</strong> <?php echo $job['mucluong']; ?></p>
            <p><strong>Khu vực:</strong> <?php echo $job['diadiem']; ?></p>
            <p><strong>Ngành nghề:</strong> <?php echo $job['nganhnghe']; ?></p>
            <hr>
            <h5>Mô tả công việc</h5>
            <p><?php echo nl2br($job['mota']); ?></p>
            <hr>
            <h5>Yêu cầu ứng viên</h5>
            <p><?php echo nl2br($job['yeucau']); ?></p>
            <hr>
            <p><strong>Liên hệ:</strong> <?php echo $job['emaillienhe']; ?></p>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'sinhvien'): ?>
                <?php
                $student_id = $_SESSION['user_id'];
                $applied = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM donungvien WHERE idvieclam = '$job_id' AND idsinhvien = '$student_id'"));
                ?>
                <?php if ($applied == 0): ?>
                    <a href="sv_nopdon.php?id=<?php echo $job_id; ?>" class="btn btn-success">Ứng tuyển ngay</a>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>Đã ứng tuyển</button>
                <?php endif; ?>
            <?php elseif (!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="btn btn-primary">Đăng nhập để ứng tuyển</a>
            <?php endif; ?>

            <a href="vl_danhsach.php" class="btn btn-outline-secondary float-end">Quay lại</a>
        </div>
    </div>
</div>
<?php include 'include_footer.php'; ?>