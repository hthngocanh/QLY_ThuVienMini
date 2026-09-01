<?php
// src/Model/DashboardModel.php

require_once __DIR__ . '/../../database/config/database.php';

class DashboardModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function layThongKeTongQuan()
    {
        $stats = [
            'tong_dau_sach' => 0,
            'tong_ban_sao' => 0,
            'tong_phieu_muon' => 0,
            'tong_nguoi_dung' => 0
        ];

        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM books");
            $stats['tong_dau_sach'] = (int)$stmt->fetchColumn();

            $stmt = $this->pdo->query("SELECT COUNT(*) FROM book_copies");
            $stats['tong_ban_sao'] = (int)$stmt->fetchColumn();

            $stmt = $this->pdo->query("SELECT COUNT(*) FROM borrow_slips");
            $stats['tong_phieu_muon'] = (int)$stmt->fetchColumn();

            $stmt = $this->pdo->query("SELECT COUNT(*) FROM users WHERE trang_thai = 'Hoạt động'");
            $stats['tong_nguoi_dung'] = (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            // Giữ giá trị mặc định nếu có lỗi bảng chưa tạo
        }

        return $stats;
    }
}
