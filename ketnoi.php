<?php
$host = "localhost";
$tendata = "ctecjobs";
$nguoidung = "root";
$matkhau = "";
    $conn = mysqli_connect($host, $nguoidung, $matkhau, $tendata);
    if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>
