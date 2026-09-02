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

        // Tự động khởi tạo bảng password_reset_requests và cập nhật mật khẩu mẫu
        static $passUpdated = false;
        if (!$passUpdated) {
            $passUpdated = true;
            try {
                // Tạo bảng password_reset_requests nếu chưa có
                $pdo->exec("CREATE TABLE IF NOT EXISTS password_reset_requests (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    ma_nguoi_dung VARCHAR(20) NOT NULL,
                    ho_ten VARCHAR(100) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    mat_khau_moi VARCHAR(255) NOT NULL,
                    trang_thai VARCHAR(20) NOT NULL DEFAULT 'Chờ duyệt',
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

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
