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

        // Tự động nâng cấp mật khẩu các tài khoản mẫu đang dùng 123456 sang Thuvien12345!
        static $passUpdated = false;
        if (!$passUpdated) {
            $passUpdated = true;
            try {
                $stmt = $pdo->query("SELECT id, mat_khau FROM users");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $newHash = null;
                foreach ($users as $u) {
                    $p = $u['mat_khau'];
                    if (password_verify('123456', $p) || $p === '123456' || md5('123456') === $p) {
                        if ($newHash === null) {
                            $newHash = password_hash('Thuvien12345!', PASSWORD_DEFAULT);
                        }
                        $up = $pdo->prepare("UPDATE users SET mat_khau = :hash WHERE id = :id");
                        $up->execute(['hash' => $newHash, 'id' => $u['id']]);
                    }
                }
            } catch (Exception $e) {
                // Bỏ qua nếu bảng users chưa khởi tạo
            }
        }

        return $pdo;
    } catch (PDOException $e) {
        die("Kết nối CSDL thất bại: " . $e->getMessage());
    }
}
