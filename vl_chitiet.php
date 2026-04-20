<?php
session_start();
include 'ketnoi.php';
include 'zalo.php';
$id_vieclam = $_GET['id'] ?? 0;

$sql = "SELECT vl.*, nd.hoten as tencongty_nguoidung FROM vieclam vl 
        JOIN nguoidung nd ON vl.idnhatuyendung = nd.id 
        WHERE vl.id = ? AND vl.trangthai = 'daduyet'";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_vieclam);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$vieclam = mysqli_fetch_assoc($result);

if (!$vieclam) {
    header("Location: vl_danhsach.php");
    exit;
}

mysqli_query($conn, "INSERT INTO thongke (trang) VALUES ('Việc làm_$id_vieclam') ON DUPLICATE KEY UPDATE solanxem = solanxem + 1");
?>

<?php include 'include_header.php'; ?>

<style>
    /* CONTAINER */
.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

/* CARD */
.job-detail {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

/* HEADER */
.job-header {
    background: linear-gradient(135deg, #2e7d32, #66bb6a);
    color: white;
    padding: 25px;
}

.job-header h2 {
    margin-bottom: 10px;
}

.meta {
    display: flex;
    gap: 10px;
    align-items: center;
}

.badge {
    background: white;
    color: #2e7d32;
    padding: 5px 10px;
    border-radius: 10px;
    font-weight: bold;
}

/* BODY */
.job-body {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    padding: 30px;
}

/* LEFT */
.info-box p {
    margin-bottom: 10px;
}

.salary {
    color: red;
    font-weight: bold;
}

.content-text {
    line-height: 1.7;
    margin-bottom: 20px;
}

/* RIGHT */
.job-right {
    border-left: 2px solid #eee;
    padding-left: 20px;
}

.job-img {
    width: 100%;
    border-radius: 10px;
}

.file-box {
    text-align: center;
    padding: 20px;
    background: #f1f8f4;
    border-radius: 10px;
}

.file-box a {
    display: block;
    margin-top: 10px;
    color: #2e7d32;
}

/* FOOTER */
.job-footer {
    border-top: 1px solid #eee;
    padding: 20px 30px;
}

.actions {
    margin-top: 15px;
    display: flex;
    gap: 10px;
}

/* BUTTON */
.btn-main {
    background: #2e7d32;
    color: white;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
}

.btn-main:hover {
    background: #1b5e20;
}

.btn-outline {
    border: 2px solid #2e7d32;
    color: #2e7d32;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
}

.btn-disabled {
    background: gray;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
}

/* EMPTY */
.empty {
    color: gray;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .job-body {
        grid-template-columns: 1fr;
    }

    .job-right {
        border-left: none;
        padding-left: 0;
    }

    .actions {
        flex-direction: column;
    }
}
</style>
<div class="container">

    <div class="job-detail">

        <!-- HEADER -->
        <div class="job-header">
            <h2><?php echo htmlspecialchars($vieclam['tieude']); ?></h2>
            <div class="meta">
                <span class="badge">Tin cậy</span>
                <span class="date">
                    <i class="far fa-calendar-alt"></i>
                    <?php echo date('d/m/Y', strtotime($vieclam['ngaydang'])); ?>
                </span>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="job-body">

            <!-- LEFT -->
            <div class="job-left">

                <div class="info-box">
                    <p><strong><i class="fas fa-building"></i> Công ty:</strong> <?php echo htmlspecialchars($vieclam['tencongty']); ?></p>
                    <p><strong><i class="fas fa-money-bill-wave"></i> Mức lương:</strong> 
                        <span class="salary"><?php echo htmlspecialchars($vieclam['mucluong']); ?></span>
                    </p>
                    <p><strong><i class="fas fa-map-marker-alt"></i> Khu vực:</strong> <?php echo htmlspecialchars($vieclam['diadiem']); ?></p>
                    <p><strong><i class="fas fa-briefcase"></i> Ngành nghề:</strong> <?php echo htmlspecialchars($vieclam['nganhnghe']); ?></p>
                </div>

                <h3>Mô tả công việc</h3>
                <p class="content-text"><?php echo nl2br(htmlspecialchars($vieclam['mota'])); ?></p>

                <h3>Yêu cầu ứng viên</h3>
                <p class="content-text"><?php echo nl2br(htmlspecialchars($vieclam['yeucau'])); ?></p>

            </div>

            <!-- RIGHT -->
            <div class="job-right">
                <h4><i class="fas fa-paperclip"></i> Tài liệu</h4>

                <?php if (!empty($vieclam['chitiet'])): ?>
                    <?php 
                        $file_path = "uploads/chitiet_vieclam/" . $vieclam['chitiet'];
                        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                    ?>

                    <?php if (in_array($ext, ['jpg','jpeg','png','gif'])): ?>
                        <a href="<?php echo $file_path; ?>" target="_blank">
                            <img src="<?php echo $file_path; ?>" class="job-img">
                        </a>
                    <?php else: ?>
                        <div class="file-box">
                            <i class="fas fa-file"></i>
                            <p>.<?php echo $ext; ?></p>
                            <a href="<?php echo $file_path; ?>" target="_blank">Xem file</a>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <p class="empty">Không có file đính kèm</p>
                <?php endif; ?>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="job-footer">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($vieclam['emaillienhe']); ?></p>

            <div class="actions">

                <?php if (isset($_SESSION['vaitro']) && $_SESSION['vaitro'] == 'sinhvien'): ?>
                    <?php
                    $id_sinhvien = $_SESSION['id_nguoidung'];
                    $ungtuyen_check = mysqli_query($conn, "SELECT id FROM donungvien WHERE idvieclam = '$id_vieclam' AND idsinhvien = '$id_sinhvien'");
                    $da_ungtuyen = mysqli_num_rows($ungtuyen_check);
                    ?>

                    <?php if ($da_ungtuyen == 0): ?>
                        <a href="sv_nopdon.php?id=<?php echo $id_vieclam; ?>" class="btn-main">Ứng tuyển</a>
                    <?php else: ?>
                        <button class="btn-disabled">Đã ứng tuyển</button>
                    <?php endif; ?>

                <?php elseif (!isset($_SESSION['id_nguoidung'])): ?>
                    <a href="login.php" class="btn-main">Đăng nhập ứng tuyển</a>
                <?php endif; ?>

                <a href="vl_danhsach.php" class="btn-outline">Quay lại</a>

            </div>
        </div>

    </div>
</div>

<?php include 'include_footer.php'; ?>