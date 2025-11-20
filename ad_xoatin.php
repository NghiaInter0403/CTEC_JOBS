<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'quantrivien') {
    exit;
}
include 'ketnoi.php';
$id_vieclam = $_GET['id'];

mysqli_query($conn, "DELETE FROM vieclam WHERE id = '$id_vieclam'");
header("Location: ad_qlyviec.php");
?>