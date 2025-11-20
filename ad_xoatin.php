<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'quantrivien') {
    header("Location: login.php");
    exit;
}
include 'ketnoi.php';
if (isset($_POST['id'])) {
    $id_vieclam = $_POST['id'];
    $sql = "DELETE FROM vieclam WHERE id = $id_vieclam";
    mysqli_query($conn, $sql);
}
header("Location: ad_qlyviec.php");
exit;
?>
