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

    // Lấy danh sách danh mục + số lượng sách
    // $trangThai: '' = tất cả, hoặc 'Hoạt động' / 'Ngừng hoạt động'
    public function layDanhSachDanhMuc($tuKhoa = '', $trangThai = '')
    {
        $sql = "
            SELECT 
                c.category_id,
                c.ten_danh_muc,
                c.mo_ta,
                c.trang_thai,
                COUNT(b.id) AS so_luong_sach
            FROM Categories c
            LEFT JOIN books b 
                ON c.category_id = b.category_id
            WHERE 1 = 1
        ";

        $params = [];

        if ($tuKhoa !== '') {
            $sql .= "
                AND (
                    c.ten_danh_muc LIKE :tu_khoa
                    OR c.mo_ta LIKE :tu_khoa
                )
            ";

            $params['tu_khoa'] = '%' . $tuKhoa . '%';
        }

        if ($trangThai !== '') {
            $sql .= "
                AND c.trang_thai = :trang_thai
            ";

            $params['trang_thai'] = $trangThai;
        }

        $sql .= "
            GROUP BY
                c.category_id,
                c.ten_danh_muc,
                c.mo_ta,
                c.trang_thai
            ORDER BY c.category_id DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh mục theo ID
    public function layDanhMucTheoId(int $categoryId)
    {
        $sql = "
            SELECT
                c.category_id,
                c.ten_danh_muc,
                c.mo_ta,
                c.trang_thai,
                COUNT(b.id) AS so_luong_sach
            FROM Categories c
            LEFT JOIN books b
                ON c.category_id = b.category_id
            WHERE c.category_id = :category_id
            GROUP BY
                c.category_id,
                c.ten_danh_muc,
                c.mo_ta,
                c.trang_thai
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'category_id' => $categoryId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra tên danh mục đã tồn tại
    public function kiemTraTenDanhMucTonTai(
        string $tenDanhMuc,
        int $boQuaId = 0
    ) {
        $sql = "
            SELECT category_id
            FROM Categories
            WHERE LOWER(TRIM(ten_danh_muc)) = LOWER(TRIM(:ten_danh_muc))
        ";

        $params = [
            'ten_danh_muc' => $tenDanhMuc
        ];

        if ($boQuaId > 0) {
            $sql .= " AND category_id <> :bo_qua_id";
            $params['bo_qua_id'] = $boQuaId;
        }

        $sql .= " LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Thêm danh mục
    public function themDanhMuc(
        string $tenDanhMuc,
        string $moTa
    ) {
        $sql = "
            INSERT INTO Categories
            (
                ten_danh_muc,
                mo_ta,
                trang_thai
            )
            VALUES
            (
                :ten_danh_muc,
                :mo_ta,
                'Hoạt động'
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'ten_danh_muc' => $tenDanhMuc,
            'mo_ta' => $moTa
        ]);
    }

    // Thủ thư chỉ được sửa tên và mô tả
    public function suaDanhMuc(
        int $categoryId,
        string $tenDanhMuc,
        string $moTa
    ) {
        $sql = "
            UPDATE Categories
            SET
                ten_danh_muc = :ten_danh_muc,
                mo_ta = :mo_ta
            WHERE category_id = :category_id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'category_id' => $categoryId,
            'ten_danh_muc' => $tenDanhMuc,
            'mo_ta' => $moTa
        ]);
    }

    // Admin đổi trạng thái
    public function doiTrangThai(
        int $categoryId,
        string $trangThai
    ) {
        $sql = "
            UPDATE Categories
            SET trang_thai = :trang_thai
            WHERE category_id = :category_id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'category_id' => $categoryId,
            'trang_thai' => $trangThai
        ]);
    }

    // ==========================================
    // BỔ SUNG MỚI
    // ==========================================

    // Đếm tổng số danh mục (dùng cho ô số liệu Admin)
    public function demTongSoDanhMuc()
    {
        $sql = "SELECT COUNT(*) FROM Categories";

        $stmt = $this->pdo->query($sql);

        return (int)$stmt->fetchColumn();
    }

    // Đếm số danh mục theo trạng thái (dùng cho ô số liệu Admin)
    public function demTheoTrangThai(string $trangThai)
    {
        $sql = "
            SELECT COUNT(*)
            FROM Categories
            WHERE trang_thai = :trang_thai
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['trang_thai' => $trangThai]);

        return (int)$stmt->fetchColumn();
    }

    // Kiểm tra danh mục còn sách hay không -> dùng để chặn xóa
    public function kiemTraCoSachTrongDanhMuc(int $categoryId)
    {
        $sql = "
            SELECT COUNT(*)
            FROM books
            WHERE category_id = :category_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category_id' => $categoryId]);

        return (int)$stmt->fetchColumn() > 0;
    }

    // Admin xóa danh mục
    public function xoaDanhMuc(int $categoryId)
    {
        $sql = "
            DELETE FROM Categories
            WHERE category_id = :category_id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'category_id' => $categoryId
        ]);
    }

    // Danh sách danh mục đang "Hoạt động" - dùng cho dropdown
    // ở màn hình Thêm sách, để không thể thêm sách vào danh mục
    // đã bị Ngừng hoạt động.
    public function layDanhMucDangHoatDong()
    {
        $sql = "
            SELECT category_id, ten_danh_muc
            FROM Categories
            WHERE trang_thai = 'Hoạt động'
            ORDER BY ten_danh_muc ASC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}