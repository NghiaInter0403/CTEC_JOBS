<?php
session_start();
if (!isset($_SESSION['id_nguoidung']) || $_SESSION['vaitro'] != 'nhatuyendung') {
    exit;
}
include 'ketnoi.php';
$id_vieclam = $_GET['id'];
$id_nhatuyendung = $_SESSION['id_nguoidung'];

mysqli_query($conn, "DELETE FROM vieclam WHERE id = '$id_vieclam' AND idnhatuyendung = '$id_nhatuyendung'");
header("Location: cty_trangchu.php");
?>