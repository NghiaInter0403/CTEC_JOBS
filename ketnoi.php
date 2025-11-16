<?php
$host = "localhost";
$dbname = "ctecjobs";
$user = "root";
$pass = "";
    $conn = mysqli_connect($host, $user, $pass, $dbname);
    if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>
