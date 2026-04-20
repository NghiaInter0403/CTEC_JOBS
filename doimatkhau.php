<?php
session_start();
include 'zalo.php';
if (!isset($_SESSION['id_nguoidung'])) {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';

$id_user = $_SESSION['id_nguoidung'];
$vaitro = $_SESSION['vaitro'];
$loi = "";
$thanhcong = "";

// Xác định link quay lại trang chủ dựa trên vai trò
$link_back = "login.php";
if ($vaitro == 'quantrivien') $link_back = "ad_trangchu.php";
elseif ($vaitro == 'sinhvien') $link_back = "sv_trangchu.php";
elseif ($vaitro == 'nhatuyendung') $link_back = "cty_trangchu.php";

if (isset($_POST['btn_doimk'])) {
    $mk_cu = $_POST['mk_cu'];
    $mk_moi = $_POST['mk_moi'];
    $re_mk_moi = $_POST['re_mk_moi'];

    // 1. Kiểm tra nhập liệu
    if (empty($mk_cu) || empty($mk_moi) || empty($re_mk_moi)) {
        $loi = "Vui lòng nhập đầy đủ thông tin!";
    } elseif ($mk_moi !== $re_mk_moi) {
        $loi = "Mật khẩu mới không khớp nhau!";
    } elseif (strlen($mk_moi) < 6) {
        $loi = "Mật khẩu mới phải từ 6 ký tự trở lên!";
    } else {
        // 2. Lấy mật khẩu hiện tại trong DB để so sánh
        $stmt = $conn->prepare("SELECT matkhau FROM nguoidung WHERE id = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (password_verify($mk_cu, $user['matkhau'])) {
            // 3. Mật khẩu cũ đúng -> Cập nhật mật khẩu mới
            $hash_password = password_hash($mk_moi, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE nguoidung SET matkhau = ? WHERE id = ?");
            $update->bind_param("si", $hash_password, $id_user);
            
            if ($update->execute()) {
                $thanhcong = "Đổi mật khẩu thành công!";
            } else {
                $loi = "Có lỗi xảy ra, vui lòng thử lại!";
            }
            $update->close();
        } else {
            $loi = "Mật khẩu cũ không chính xác!";
        }
        $stmt->close();
    }
}
?>
<style>
    .btn-green {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #2e7d32, #66bb6a);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: bold;
    font-size: 16px;
    letter-spacing: 1px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

/* hover */
.btn-green:hover {
    background: linear-gradient(135deg, #1b5e20, #4caf50);
    transform: translateY(-2px);
}

/* click */
.btn-green:active {
    transform: scale(0.98);
}

/* disabled */
.btn-green:disabled {
    background: #ccc;
    cursor: not-allowed;
    box-shadow: none;
}
</style>
<?php include 'include_header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h4 class="text-center mb-4 text-uppercase fw-bold">Đổi mật khẩu</h4>

                    <?php if ($loi): ?>
                        <div class="alert alert-danger small py-2"><?php echo $loi; ?></div>
                    <?php endif; ?>

                    <?php if ($thanhcong): ?>
                        <div class="alert alert-success small py-2"><?php echo $thanhcong; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Mật khẩu hiện tại</label>
                            <input type="password" name="mk_cu" class="form-control" required>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Mật khẩu mới</label>
                            <input type="password" name="mk_moi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Xác nhận mật khẩu mới</label>
                            <input type="password" name="re_mk_moi" class="form-control" required>
                        </div>

                        <button type="submit" name="btn_doimk" class="btn-green">
                             CẬP NHẬT MẬT KHẨU
                        </button>
                        
                        <a href="<?php echo $link_back; ?>" class="btn btn-link w-100 mt-2 text-decoration-none text-muted small">
                            <i class="fas fa-arrow-left"></i> Quay lại trang chủ
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include_footer.php'; ?>