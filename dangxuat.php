<?php
session_start();

// Xóa toàn bộ session
session_unset();
session_destroy();

// Xóa cookie nếu có
setcookie('user_id', '', time() - 3600, "/");

// Chuyển về trang chủ
header("Location: index.php");
exit;
?>