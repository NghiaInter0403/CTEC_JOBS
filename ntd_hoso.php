<?php
    session_start();
    include 'ketnoi.php';

    if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'nhatuyendung') {
        header("Location: login.php");
        exit;
    }
    $back_link = "index.php"; // mặc định

    if (isset($_SESSION['last_page'])) {
        $back_link = $_SESSION['last_page'];
    } else {
        // fallback theo role
        if ($_SESSION['vaitro'] == 'nhatuyendung') {
            $back_link = "cty_trangchu.php";
        }
    }
    $id = $_SESSION['id_nguoidung'];

    $msg = $err = "";

    /* =========================
    LẤY DỮ LIỆU HIỆN TẠI
    ========================= */
    $stmt = $conn->prepare("SELECT * FROM hosonhatuyendung WHERE idnguoidung=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();

    /* =========================
    XỬ LÝ SUBMIT
    ========================= */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $tencongty = $_POST['tencongty'];
        $sdt = $_POST['sodienthoai'];
        $email = $_POST['emailcongty'];
        $website = $_POST['website'];
        $diachi = $_POST['diachi'];
        $mota = $_POST['mota'];
        $quymo = $_POST['quymo'];

        $logo = $data['logo'] ?? null;

        /* ===== UPLOAD LOGO ===== */
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {

            $file = $_FILES['logo'];
            $filename = time() . "_" . basename($file['name']);
            $target = "uploads/logo/" . $filename;
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($ext, ['jpg','jpeg','png'])) {
                $err = "Chỉ cho phép JPG, PNG!";
            } elseif ($file['size'] > 2*1024*1024) {
                $err = "Logo tối đa 2MB!";
            } else {
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $logo = $filename;
                } else {
                    $err = "Upload logo thất bại!";
                }
            }
        }

        if (!$err) {

            if ($data) {
                // UPDATE
                $stmt = $conn->prepare("UPDATE hosonhatuyendung 
                    SET tencongty=?, logo=?, sodienthoai=?, emailcongty=?, website=?, diachi=?, mota=?, quymo=? 
                    WHERE idnguoidung=?");

                $stmt->bind_param("ssssssssi", $tencongty, $logo, $sdt, $email, $website, $diachi, $mota, $quymo, $id);
            } else {
                // INSERT
                $stmt = $conn->prepare("INSERT INTO hosonhatuyendung 
                    (idnguoidung, tencongty, logo, sodienthoai, emailcongty, website, diachi, mota, quymo) 
                    VALUES (?,?,?,?,?,?,?,?,?)");

                $stmt->bind_param("issssssss", $id, $tencongty, $logo, $sdt, $email, $website, $diachi, $mota, $quymo);
            }

            if ($stmt->execute()) {
                $msg = "Cập nhật thành công!";
            } else {
                $err = "Lỗi database!";
            }
        }
    }
?>

<?php include 'include_header.php'; ?>

<style>
    .container-box {
        max-width: 600px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .logo-preview {
        width: 120px;
        height: 120px;
        border-radius: 15px;
        object-fit: cover;
        border: 2px solid #4caf50;
        margin-bottom: 15px;
    }

    .btn-save {
        background: #2e7d32;
        color: #fff;
        border: none;
        padding: 12px;
        border-radius: 10px;
        width: 100%;
    }

    .btn-save:hover {
        background: #1b5e20;
    }

    .msg { background:#e8f5e9; padding:10px; border-radius:8px; margin-bottom:10px;}
    .err { background:#ffebee; padding:10px; border-radius:8px; margin-bottom:10px;}
    .btn-back {
    background: #e0e0e0;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    font-weight: 500;
    transition: 0.3s;
    }

    .btn-back:hover {
        background: #c8e6c9;
        color: #1b5e20;
    }
</style>

<div class="container-box">

    <h3 class="text-center mb-3">Hồ sơ nhà tuyển dụng</h3>

    <?php if($msg) echo "<div class='msg'>$msg</div>"; ?>
    <?php if($err) echo "<div class='err'>$err</div>"; ?>

    <div class="text-center">
        <img id="preview" 
             src="<?php echo !empty($data['logo']) ? 'uploads/logo/'.$data['logo'] : 'uploads/logo/default.jpg'; ?>" 
             class="logo-preview">
    </div>

    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="tencongty" class="form-control mb-2" placeholder="Tên công ty"
               value="<?php echo $data['tencongty'] ?? ''; ?>">

        <input type="text" name="sodienthoai" class="form-control mb-2" placeholder="Số điện thoại"
               value="<?php echo $data['sodienthoai'] ?? ''; ?>">

        <input type="email" name="emailcongty" class="form-control mb-2" placeholder="Email công ty"
               value="<?php echo $data['emailcongty'] ?? ''; ?>">

        <input type="text" name="website" class="form-control mb-2" placeholder="Website"
               value="<?php echo $data['website'] ?? ''; ?>">

        <textarea name="diachi" class="form-control mb-2" placeholder="Địa chỉ"><?php echo $data['diachi'] ?? ''; ?></textarea>

        <textarea name="mota" class="form-control mb-2" placeholder="Mô tả công ty"><?php echo $data['mota'] ?? ''; ?></textarea>

        <select name="quymo" class="form-control mb-2">
            <option value="">-- Quy mô --</option>
            <option value="1-10">1-10 nhân viên</option>
            <option value="11-50">11-50 nhân viên</option>
            <option value="51-100">51-100 nhân viên</option>
            <option value="100+">100+ nhân viên</option>
        </select>

        <input type="file" name="logo" class="form-control mb-3" onchange="previewImage(event)">

        <button class="btn-save" style="font-weight: bold;">Lưu thông tin</button>
        <div class="d-flex justify-content-between align-items-center mb-3" style="margin-top: 20px;">
            <h3>Hồ sơ nhà tuyển dụng</h3>
            <a href="<?php echo $back_link; ?>" class="btn-back">
                ← Quay lại
            </a>
        </div>
    </form>
</div>

<script>
function previewImage(event){
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('preview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<?php include 'include_footer.php'; ?>