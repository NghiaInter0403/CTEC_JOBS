<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'nhatuyendung') {
    exit;
}
include 'ketnoi.php';
$id_vieclam = $_GET['id'];
$employer_id = $_SESSION['user_id'];

mysqli_query($conn, "DELETE FROM vieclam WHERE id = '$id_vieclam' AND idnhatuyendung = '$employer_id'");
header("Location: cty_trangchu.php");
?>