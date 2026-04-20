<?php
session_start();
include 'zalo.php';
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'nhatuyendung') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
$id_nhatuyendung = $_SESSION['id_nguoidung'];

// Thống kê
$tongtin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM vieclam WHERE idnhatuyendung = '$id_nhatuyendung'"))['total'];
$daduyet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM vieclam WHERE idnhatuyendung = '$id_nhatuyendung' AND trangthai = 'daduyet'"))['total'];
$choduyet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM vieclam WHERE idnhatuyendung = '$id_nhatuyendung' AND trangthai = 'choxuly'"))['total'];
$tong_ungvien = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM donungvien dut JOIN vieclam vl ON dut.idvieclam = vl.id WHERE vl.idnhatuyendung = '$id_nhatuyendung'"))['total'];

// Tin tuyển dụng của tôi
$tin_cty = mysqli_query($conn, "SELECT * FROM vieclam WHERE idnhatuyendung = '$id_nhatuyendung' ORDER BY ngaydang DESC");
// Ứng viên mới nhất
$ungvien_moi = mysqli_query($conn, "SELECT duv.*, vl.tieude, nd.hoten as sinhvien, hsuv.duongdancv 
    FROM donungvien duv
    JOIN vieclam vl ON duv.idvieclam = vl.id 
    JOIN nguoidung nd ON duv.idsinhvien = nd.id 
    LEFT JOIN hosoungvien hsuv ON nd.id = hsuv.idnguoidung 
    WHERE vl.idnhatuyendung = '$id_nhatuyendung' 
    ORDER BY duv.ngaynop DESC");
?>
<style>
            /* CARD */
    .quick-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin-bottom: 40px;
        overflow: hidden;
    }

    /* HEADER */
    .quick-header {
        background: linear-gradient(135deg, #2e7d32, #66bb6a);
        padding: 20px;
        text-align: center;
    }

    .quick-header h4 {
        margin: 0;
        color: white;
        font-size: 22px;
        font-weight: bold;
    }

    /* BODY */
    .quick-body {
        padding: 25px;
    }

    /* GRID */
    .quick-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    /* BUTTON */
    .quick-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 90px;
        border-radius: 15px;
        font-size: 18px;
        font-weight: bold;
        text-decoration: none;
        transition: 0.3s;
    }

    /* GREEN */
    .quick-btn.green {
        background: linear-gradient(135deg, #43a047, #66bb6a);
        color: white;
    }

    .quick-btn.green:hover {
        background: linear-gradient(135deg, #2e7d32, #43a047);
        transform: translateY(-4px);
    }

    /* BLUE */
    .quick-btn.blue {
        background: linear-gradient(135deg, #1976d2, #42a5f5);
        color: white;
    }

    .quick-btn.blue:hover {
        background: linear-gradient(135deg, #0d47a1, #1976d2);
        transform: translateY(-4px);
    }

    /* OUTLINE */
    .quick-btn.outline {
        border: 2px solid #2e7d32;
        color: #2e7d32;
        background: #f9fdf9;
    }

    .quick-btn.outline:hover {
        background: #e8f5e9;
        transform: translateY(-4px);
    }

    /* MOBILE */
    @media (max-width: 768px) {
        .quick-grid {
            grid-template-columns: 1fr;
        }

        .quick-btn {
            height: 70px;
            font-size: 16px;
        }
}
</style>
<?php include 'include_header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold mb-0">Xin chào, <span class="text-primary"><?php echo $_SESSION['hoten']; ?></span></h1> 
        <a href="cty_dangtuyen.php" class="btn btn-success btn-lg fw-bold text-black">
            <i class="bi bi-plus-circle me-1"></i> Đăng tin mới
        </a>
    </div>

    <div class="row g-3 mb-4 text-center">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100 border-0 shadow-sm">
                <div class="card-body py-4">
                    <h5 class="text-uppercase fw-bold opacity-75">Tổng tin</h5>
                    <h2 class="display-4 fw-bold mb-0"><?php echo $tongtin; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100 border-0 shadow-sm">
                <div class="card-body py-4">
                    <h5 class="text-uppercase fw-bold opacity-75">Đã duyệt</h5>
                    <h2 class="display-4 fw-bold mb-0"><?php echo $daduyet; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100 border-0 shadow-sm text-dark">
                <div class="card-body py-4">
                    <h5 class="text-uppercase fw-bold opacity-75">Chờ duyệt</h5>
                    <h2 class="display-4 fw-bold mb-0"><?php echo $choduyet; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info h-100 border-0 shadow-sm">
                <div class="card-body py-4">
                    <h5 class="text-uppercase fw-bold opacity-75">Ứng viên</h5>
                    <h2 class="display-4 fw-bold mb-0"><?php echo $tong_ungvien; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-list-task me-2"></i>Tin tuyển dụng của tôi</h4>
                </div>
                <div class="card-body p-0">
                    <?php if (mysqli_num_rows($tin_cty) > 0): ?>
                    <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                        <?php while ($vieclam = mysqli_fetch_assoc($tin_cty)): ?>
                        <div class="list-group-item list-group-item-action p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="fs-5 text-dark d-block mb-1"><?php echo $vieclam['tieude']; ?></strong>
                                    <span class="fs-6 text-muted"><i class="bi bi-calendar3"></i> <?php echo date('d/m/Y', strtotime($vieclam['ngaydang'])); ?></span>
                                </div>
                                <div class="text-end">
                                    <span class="badge fs-6 mb-2 d-block bg-<?php echo $vieclam['trangthai']=='daduyet'?'success':($vieclam['trangthai']=='tuchoi'?'danger':'warning'); ?>">
                                        <?php echo $vieclam['trangthai']=='daduyet'?'Đã duyệt':($vieclam['trangthai']=='tuchoi'?'Từ chối':'Chờ xử lý'); ?>
                                    </span>
                                    <div class="btn-group btn-group-sm">
                                        <a href="cty_suatin.php?id=<?php echo $vieclam['id']; ?>" class="btn btn-outline-secondary">Sửa</a>
                                        <a href="cty_xoatin.php?id=<?php echo $vieclam['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Xóa tin này?')">Xóa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                        <p class="text-center fs-5 text-muted py-5">Chưa có tin tuyển dụng nào</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Ứng viên mới nhất</h4>
                </div>
                <div class="card-body p-0">
                    <?php if (mysqli_num_rows($ungvien_moi) > 0): ?> 
                    <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                        <?php while ($ungtuyen = mysqli_fetch_assoc($ungvien_moi)): ?>
                        <div class="list-group-item p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="fs-5 text-dark"><?php echo $ungtuyen['sinhvien']; ?></strong><br>
                                    <span class="fs-6 text-muted">Ứng tuyển: <strong><?php echo $ungtuyen['tieude']; ?></strong></span>
                                </div>
                                <div class="text-end">
                                    <div class="mb-1">
                                        <?php if ($ungtuyen['duongdancv']): ?>
                                            <a href="<?php echo $ungtuyen['duongdancv']; ?>" target="_blank" class="btn btn-sm btn-info fw-bold">Xem CV</a>
                                        <?php endif; ?>
                                    </div>
                                    <span class="badge fs-6 bg-<?php echo $ungtuyen['trangthai']=='daduyet'?'success':($ungtuyen['trangthai']=='tuchoi'?'danger':'warning'); ?>">
                                        <?php echo $ungtuyen['trangthai']=='daduyet'?'Đã duyệt':($ungtuyen['trangthai']=='tuchoi'?'Từ chối':'Chờ xử lý'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?> 
                    </div>
                    <?php else: ?>
                        <p class="text-center fs-5 text-muted py-5">Chưa có ứng viên mới nộp đơn</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="quick-card">
    <div class="quick-header">
        <h4>Quản lý nhanh</h4>
    </div>

    <div class="quick-body">
        <div class="quick-grid">

            <a href="cty_dangtuyen.php" class="quick-btn green">
                <span>ĐĂNG TIN MỚI</span>
            </a>

            <a href="cty_ungvien.php" class="quick-btn blue">
                <span>XEM ỨNG VIÊN</span>
            </a>

            <a href="vl_danhsach.php" class="quick-btn outline">
                <span>DS VIỆC LÀM</span>
            </a>

        </div>
    </div>
</div>
</div>

<?php include 'include_footer.php'; ?>