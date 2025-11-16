<?php
$host = "localhost";
$dbname = "ctecjobs";
$user = "root";
$pass = "";
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    // Thiết lập PDO lỗi dưới dạng exception
    if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>
