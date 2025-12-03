<?php
$host = "localhost";
$tendata = "ctecjobs";
$user = "root";
$pass = "";
    $conn = mysqli_connect($host, $user, $pass, $tendata);
    if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>
