<?php

$host = 'localhost';
$dbname = 'QLThuVien';
$username = 'root';

/*
 * Mỗi thành viên tự nhập mật khẩu
 * MySQL trên máy của mình.
 */
$password = '';

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    die(
        "Kết nối MySQL thất bại: "
        . $e->getMessage()
    );
}