<?php
session_start();

// Hủy toàn bộ session
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

// Chuyển hướng về màn hình đăng nhập
header("Location: dangnhap.php");
exit;
