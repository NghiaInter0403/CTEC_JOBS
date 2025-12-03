<?php
$host = "localhost";
$tendata = "ctecjobs";
$user = "root";
$matkhau = "";
    $conn = mysqli_connect($host, $user, $matkhau, $tendata);
    if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>
