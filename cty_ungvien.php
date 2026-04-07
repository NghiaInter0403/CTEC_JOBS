<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$id_nhatuyendung = $_SESSION['id_nguoidung'];

// ... (Giữ nguyên phần xử lý logic XÓA, ĐỒNG Ý, TỪ CHỐI của bạn) ...

// LẤY DANH SÁCH ỨNG VIÊN
$danhsach = "SELECT duv.*, duv.id as don_id, vl.tieude AS tieude_jobs, sv.hoten AS hoten_sv, 
        hs.duongdancv, hs.sodienthoai, hs.diachi, hs.kynang
        FROM donungvien duv
        JOIN vieclam vl ON duv.idvieclam = vl.id
        JOIN nguoidung sv ON duv.idsinhvien = sv.id
        LEFT JOIN hosoungvien hs ON sv.id = hs.idnguoidung
        WHERE vl.idnhatuyendung = '$id_nhatuyendung'
        ORDER BY duv.ngaynop DESC";

$thuchien_danhsach = mysqli_query($conn, $danhsach);
?>

<?php include 'include_header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="display-6 fw-bold mb-0 text-primary">Danh sách ứng viên</h2>
        <a href="cty_trangchu.php" class="btn btn-outline-secondary btn-lg shadow-sm">
            <i class="bi bi-arrow-left"></i> Quay lại trang chủ
        </a>
    </div>

    <?php if(isset($thanhcong)) echo "<div class='alert alert-success fs-5'>$thanhcong</div>"; ?>
    <?php if(isset($thatbai)) echo "<div class='alert alert-danger fs-5'>$thatbai</div>"; ?>
    
    <?php if(mysqli_num_rows($thuchien_danhsach) > 0): ?>
        <div class="list-group">
            <?php while($ungtuyen = mysqli_fetch_assoc($thuchien_danhsach)): ?>
                <div class="list-group-item mb-4 p-4 shadow-sm border-0 rounded-3">
                    <div class="row align-items-center">
                        <div class="col-md-7 fs-5">
                            <h3 class="fw-bold text-dark mb-3"><?php echo htmlspecialchars($ungtuyen['hoten_sv']); ?></h3>
                            
                            <p class="mb-2"><strong><i class="bi bi-briefcase"></i> Ứng tuyển:</strong> 
                                <span class="text-primary fw-bold"><?php echo htmlspecialchars($ungtuyen['tieude_jobs']); ?></span>
                            </p>
                            
                            <p class="mb-2 text-muted"><strong><i class="bi bi-calendar-event"></i> Ngày nộp:</strong> 
                                <?php echo date('d/m/Y H:i', strtotime($ungtuyen['ngaynop'])); ?>
                            </p>

                            <hr>

                            <div class="bg-light p-3 rounded">
                                <p class="mb-1"><strong>SĐT:</strong> <?php echo htmlspecialchars($ungtuyen['sodienthoai'] ?? '-'); ?></p>
                                <p class="mb-1"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($ungtuyen['diachi'] ?? '-'); ?></p>
                                <p class="mb-0"><strong>Kỹ năng:</strong> <?php echo htmlspecialchars($ungtuyen['kynang'] ?? '-'); ?></p>
                            </div>

                            <div class="mt-3">
                                <span class="badge p-2 fs-6 bg-<?php 
                                    echo $ungtuyen['trangthai']=='choxuly'?'warning':
                                        ($ungtuyen['trangthai']=='chapnhan'?'success':'danger'); ?>">
                                    <i class="bi bi-info-circle"></i> 
                                    <?php echo $ungtuyen['trangthai'] == 'choxuly'?'Chờ xử lý':
                                    ($ungtuyen['trangthai']=='chapnhan'?'Đã chấp nhận':'Đã từ chối'); ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-5 text-center mt-3 mt-md-0">
                            <?php if (!empty($ungtuyen['duongdancv'])): ?>
                                <p class="text-muted fw-bold">CV/Hình ảnh đính kèm:</p>
                                <a href="<?php echo $ungtuyen['duongdancv']; ?>" target="_blank">
                                    <img src="<?= $ungtuyen['duongdancv'] ?>" 
                                        alt="CV"
                                        class="img-fluid border rounded shadow-sm"
                                        style="max-height: 350px; width: 100%; object-fit: contain;">
                                </a>
                                <br>
                                <small class="text-muted">(Nhấn vào hình để xem rõ hơn)</small>
                            <?php else: ?>
                                <div class="py-5 border rounded bg-light">
                                    <i class="bi bi-file-earmark-x fs-1 text-muted"></i>
                                    <p class="text-muted mb-0">Không có CV đính kèm</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">

                        <?php if ($ungtuyen['trangthai'] == 'choxuly'): ?>
                            <a href="cty_ungvien.php?dongy=<?= $ungtuyen['don_id']; ?>" 
                               class="btn btn-success btn-lg px-4 shadow-sm fw-bold">
                               <i class="bi bi-check-lg"></i> Chấp nhận hồ sơ
                            </a>

                            <a href="cty_ungvien.php?tuchoi=<?= $ungtuyen['don_id']; ?>" 
                               class="btn btn-warning btn-lg px-4 shadow-sm fw-bold">
                               <i class="bi bi-x-lg"></i> Từ chối
                            </a>
                        <?php endif; ?>

                        <a href="cty_ungvien.php?xoa=<?= $ungtuyen['don_id']; ?>" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa ứng viên này khỏi danh sách?')"
                           class="btn btn-outline-danger btn-lg px-4 shadow-sm">
                           <i class="bi bi-trash"></i> Xóa
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 shadow-sm bg-white rounded">
            <i class="bi bi-person-x display-1 text-muted"></i>
            <p class="fs-4 text-muted mt-3">Chưa có ứng viên nào ứng tuyển vào các vị trí của bạn.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'include_footer.php'; ?>