<?php
// Bắt đầu session nếu chưa bắt đầu
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hủy tất cả các biến session
$_SESSION = array();

// Xóa cookie session nếu có
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hủy session
session_destroy();

// Chuyển hướng về trang chủ hoặc trang đăng nhập
header("Location: ../pages/index.php"); // Chuyển hướng về trang chủ
// Hoặc header("Location: ../views/auth/login.php"); // Chuyển hướng về trang đăng nhập
exit();
?>