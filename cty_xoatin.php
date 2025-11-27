<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'nhatuyendung') {
    exit;
}
include 'ketnoi.php';
$id_vieclam = $_GET['id'];
$id_nhatuyendung = $_SESSION['user_id'];

mysqli_query($conn, "DELETE FROM vieclam WHERE id = '$id_vieclam' AND idnhatuyendung = '$id_nhatuyendung'");
header("Location: cty_trangchu.php");
?>