<?php

$host = '127.0.0.1';
$dbname = 'qly_thuvienmini';
$username = 'root';
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

    die("Kết nối MySQL thất bại: "
        . $e->getMessage());
}
