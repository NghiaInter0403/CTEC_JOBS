<?php
session_start();
require_once __DIR__ . '/ketnoi.php'; // sửa path nếu cần

// check login
if (!isset($_SESSION['id_nguoidung'])) {
    header("Location: login.php");
    exit;
}
$backUrl = "index.php";

// 1. ưu tiên trang trước
if (!empty($_SESSION['last_page'])) {
    $backUrl = $_SESSION['last_page'];
} 
// 2. fallback theo role
elseif (isset($_SESSION['vaitro'])) {
    switch ($_SESSION['vaitro']) {
        case 'sinhvien':
            $backUrl = "sv_trangchu.php";
            break;
        case 'nhatuyendung':
            $backUrl = "cty_trangchu.php";
            break;
        case 'quantrivien':
            $backUrl = "ad_trangchu.php";
            break;
    }
}
$id = $_SESSION['id_nguoidung'];

$msg = $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {

        $file = $_FILES['avatar'];
        $filename = time() . "_" . basename($file['name']);
        $target = "uploads/avatar/" . $filename;

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg','jpeg','png'])) {
            $err = "Chỉ cho phép JPG, PNG!";
        } else if ($file['size'] > 2*1024*1024) {
            $err = "Ảnh tối đa 2MB!";
        } else {

            if (move_uploaded_file($file['tmp_name'], $target)) {

                $stmt = $conn->prepare("UPDATE nguoidung SET avatar=? WHERE id=?");
            if ($stmt) {
                $stmt->bind_param("si", $filename, $id);
                $stmt->execute();
                $stmt->close();
                $msg = "Cập nhật ảnh thành công!";
            } else {
                $err = "Lỗi database!";
            }

                $msg = "Cập nhật ảnh thành công!";
            } else {
                $err = "Upload thất bại!";
            }
        }
    } else {
        $err = "Vui lòng chọn ảnh!";
    }
}
?>

<style>
    .avatar-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
    }

    .avatar-card {
        width: 100%;
        max-width: 400px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        overflow: hidden;
    }

    .avatar-header {
        background: linear-gradient(135deg, #2e7d32, #66bb6a);
        color: white;
        text-align: center;
        padding: 25px;
    }

    .avatar-body {
        padding: 30px;
        text-align: center;
    }

    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #4caf50;
        margin-bottom: 20px;
    }

    .file-input {
        margin-bottom: 20px;
    }

    .file-input input {
        width: 100%;
    }

    .btn-upload {
        width: 100%;
        padding: 12px;
        background: #2e7d32;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-upload:hover {
        background: #1b5e20;
    }

    .msg {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .err {
        background: #ffebee;
        color: #c62828;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .btn-back {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 16px;
    background: linear-gradient(135deg, #757575, #9e9e9e);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #424242, #616161);
        transform: translateY(-2px);
    }
</style>
<?php include 'include_header.php' ?>
<div class="avatar-container">
    <div class="avatar-card">

        <div class="avatar-header">
            <h2>Đổi ảnh đại diện</h2>
        </div>

        <div class="avatar-body">

            <!-- thông báo -->
            <?php if ($msg): ?>
                <div class="msg"><?php echo $msg; ?></div>
            <?php endif; ?>

            <?php if ($err): ?>
                <div class="err"><?php echo $err; ?></div>
            <?php endif; ?>

            <!-- preview -->
            <?php
                $currentAvatar = "uploads/avatar/default.png";

                $rs = $conn->query("SELECT avatar FROM nguoidung WHERE id = $id");
                $data = $rs->fetch_assoc();

                if (!empty($data['avatar'])) {
                    $currentAvatar = "uploads/avatar/" . $data['avatar'];
                }
            ?>

            <img id="preview" src="<?php echo $currentAvatar; ?>" class="avatar-preview">

            <form method="POST" enctype="multipart/form-data">

                <div class="file-input">
                    <input type="file" name="avatar" accept="image/*" onchange="previewImage(event)">
                </div>

                <button type="submit" class="btn-upload">
                    Cập nhật ảnh
                </button>
                <a href="<?php echo $backUrl; ?>" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </form>

        </div>
    </div>
</div>
<?php include 'include_footer.php'?>
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('preview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>