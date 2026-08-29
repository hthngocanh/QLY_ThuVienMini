<?php

function getDB()
{
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

        return $pdo;
    } catch (PDOException $e) {
        die("Kết nối CSDL thất bại: " . $e->getMessage());
    }
}
