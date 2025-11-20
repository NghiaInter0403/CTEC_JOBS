<?php
session_start();
include 'ketnoi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'sinhvien') {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['user_id'];
$job_id = $_GET['id'];

// Kiểm tra đã ứng tuyển chưa
$check = mysqli_query($conn, 
    "SELECT * FROM donungvien WHERE idsinhvien='$student_id' AND idvieclam='$job_id'"
);

if (mysqli_num_rows($check) > 0) {
    header("Location: vl_chitiet.php?id=$job_id&msg=already");
    exit;
}

// Lưu đơn ứng tuyển
$sql = "INSERT INTO donungvien (idsinhvien, idvieclam, trangthai, ngaynop)
        VALUES ('$student_id', '$job_id', 'choduyet', NOW())";

mysqli_query($conn, $sql);

// Tăng bộ đếm cho admin? → KHÔNG CẦN
// Vì admin đọc trực tiếp COUNT(*) FROM donungvien nên tự tăng.

header("Location: vl_chitiet.php?id=$job_id&msg=success");
exit;
?>