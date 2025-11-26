<?php
session_start();
include 'ketnoi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'sinhvien') {
    header("Location: login.php");
    exit;
}

$idnguoidung = $_SESSION['user_id'];
$id_vieclam = $_GET['id'];

// Kiểm tra đã ứng tuyển chưa
$check = mysqli_query($conn, 
    "SELECT * FROM donungvien WHERE idsinhvien='$student_id' AND idvieclam='$job_id'"
);

if (mysqli_num_rows($check) > 0) {
    header("Location: vl_chitiet.php?id=$job_id&msg=already");
    exit;
}

// Lưu đơn ứng tuyển
$sql = "INSERT INTO donungvien (idvieclam, idsinhvien, ngaynop, trangthai)
        VALUES ('$job_id', '$student_id', NOW(), 'choxuly')";

mysqli_query($conn, $sql);

header("Location: vl_chitiet.php?id=$job_id&msg=success");
exit;
?>