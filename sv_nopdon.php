<?php
session_start();
include 'ketnoi.php';

if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'sinhvien') {
    header("Location: login.php");
    exit;
}

$idnguoidung = $_SESSION['id_nguoidung'];
$id_vieclam = $_GET['id'];

// Kiểm tra đã ứng tuyển chưa
$kiemtra = mysqli_query($conn, 
    "SELECT * FROM donungvien WHERE idsinhvien='$idnguoidung' AND idvieclam='$id_vieclam'"
);

if (mysqli_num_rows($kiemtra) > 0) {
    header("Location: vl_chitiet.php?id=$id_vieclam&msg=already");
    exit;
}

// Lưu đơn ứng tuyển
$sql = "INSERT INTO donungvien (idvieclam, idsinhvien, ngaynop, trangthai)
        VALUES ('$id_vieclam', '$idnguoidung', NOW(), 'choxuly')";

mysqli_query($conn, $sql);

header("Location: vl_chitiet.php?id=$id_vieclam&msg=success");
exit;
?>