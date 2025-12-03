<?php
$host = "localhost";
$namedb = "ctecjobs";
$user = "root";
$password = "";
    $conn = mysqli_connect($host, $user, $password, $namedb);
    if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>
