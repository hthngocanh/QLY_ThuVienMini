<?php
// src/Model/CategoryModel.php

require_once __DIR__ . '/../../database/config/database.php';

class CategoryModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function layDanhSachDanhMuc()
    {
        $sql = "SELECT category_id, ten_danh_muc, mo_ta, trang_thai
                FROM Categories
                ORDER BY category_id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layDanhMucTheoId(int $categoryId)
    {
        $sql = "SELECT category_id, ten_danh_muc, mo_ta, trang_thai
                FROM Categories
                WHERE category_id = :category_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function themDanhMuc(string $tenDanhMuc, string $moTa, string $trangThai)
    {
        $sql = "INSERT INTO Categories (ten_danh_muc, mo_ta, trang_thai)
                VALUES (:ten_danh_muc, :mo_ta, :trang_thai)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'ten_danh_muc' => $tenDanhMuc,
            'mo_ta'        => $moTa,
            'trang_thai'   => $trangThai
        ]);
    }

    public function suaDanhMuc(int $categoryId, string $tenDanhMuc, string $moTa, string $trangThai)
    {
        $sql = "UPDATE Categories
                SET ten_danh_muc = :ten_danh_muc, mo_ta = :mo_ta, trang_thai = :trang_thai
                WHERE category_id = :category_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'category_id'  => $categoryId,
            'ten_danh_muc' => $tenDanhMuc,
            'mo_ta'        => $moTa,
            'trang_thai'   => $trangThai
        ]);
    }

    public function xoaDanhMuc(int $categoryId)
    {
        $sql = "DELETE FROM Categories WHERE category_id = :category_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['category_id' => $categoryId]);
    }
}
