<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem CV ứng viên</title>

    <!-- Bootstrap 5 + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        #pdf-viewer {
            width: 100%;
            height: calc(100vh - 120px);
            border: none;
        }
       
    </style>
</head>
<body>

<?php include 'include_header.php'; ?>

<div class="container-fluid p-0">
    <div class="pdf-container">
        <div class="CV" style="align-items: center; height: 100px; margin-top: 30px; ">
            <strong class="ms-2" style="color: black; font-size: 20px; align-items: center">Nguyễn Văn A</strong>

            <a href="cty_ungvien.php" class="btn btn-success" style="float:right;">
            </i>Quay lại danh sách
            </a>
        </div>
        <!-- Xem PDF full màn hình -->
        <embed src="uploads/CV.pdf" type="application/pdf" id="pdf-viewer">
    </div>
</div>

<?php include "include_footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>