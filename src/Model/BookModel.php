<?php
// src/Model/BookModel.php

require_once __DIR__ . '/../../database/config/database.php';

class BookModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function layDanhSachDanhMuc()
    {
        $stmt = $this->pdo->query("
            SELECT category_id, ten_danh_muc
            FROM Categories
            ORDER BY category_id ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layDauSachTheoId($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT
                b.id,
                b.ma_sach,
                b.ten_sach,
                b.ma_tac_gia,
                b.tac_gia,
                b.category_id,
                c.ten_danh_muc AS danh_muc,
                b.nha_xuat_ban,
                b.nam_xuat_ban,
                b.isbn,
                b.gia_sach,
                b.mo_ta,
                b.trang_thai
            FROM books b
            INNER JOIN Categories c
                ON b.category_id = c.category_id
            WHERE b.id = :id
        ");
        $stmt->execute(["id" => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function layCategoryIdTheoTen($tenDanhMuc)
    {
        $stmt = $this->pdo->prepare("
            SELECT category_id
            FROM Categories
            WHERE ten_danh_muc = :ten_danh_muc
        ");
        $stmt->execute(["ten_danh_muc" => $tenDanhMuc]);
        return $stmt->fetchColumn();
    }

    public function demDauSach($tuKhoa = "", $locTacGia = "", $locDanhMuc = "", $locNam = "")
    {
        $where = ["1=1"];
        $params = [];

        if ($tuKhoa !== "") {
            $where[] = "(
                b.ma_sach LIKE :tu_khoa
                OR b.ten_sach LIKE :tu_khoa
                OR b.ma_tac_gia LIKE :tu_khoa
                OR b.tac_gia LIKE :tu_khoa
                OR b.nha_xuat_ban LIKE :tu_khoa
            )";
            $params["tu_khoa"] = "%" . $tuKhoa . "%";
        }

        if ($locTacGia !== "") {
            $where[] = "b.tac_gia LIKE :loc_tac_gia";
            $params["loc_tac_gia"] = "%" . $locTacGia . "%";
        }

        if ($locDanhMuc !== "") {
            $where[] = "b.category_id = :loc_danh_muc";
            $params["loc_danh_muc"] = $locDanhMuc;
        }

        if ($locNam !== "") {
            $where[] = "b.nam_xuat_ban = :loc_nam";
            $params["loc_nam"] = $locNam;
        }

        $dieuKien = "WHERE " . implode(" AND ", $where);

        $sql = "
            SELECT COUNT(*)
            FROM books b
            INNER JOIN Categories c
                ON b.category_id = c.category_id
            $dieuKien
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function layDanhSachDauSach($tuKhoa = "", $locTacGia = "", $locDanhMuc = "", $locNam = "", $limit = 5, $offset = 0)
    {
        $where = ["1=1"];
        $params = [];

        if ($tuKhoa !== "") {
            $where[] = "(
                b.ma_sach LIKE :tu_khoa
                OR b.ten_sach LIKE :tu_khoa
                OR b.ma_tac_gia LIKE :tu_khoa
                OR b.tac_gia LIKE :tu_khoa
                OR b.nha_xuat_ban LIKE :tu_khoa
            )";
            $params["tu_khoa"] = "%" . $tuKhoa . "%";
        }

        if ($locTacGia !== "") {
            $where[] = "b.tac_gia LIKE :loc_tac_gia";
            $params["loc_tac_gia"] = "%" . $locTacGia . "%";
        }

        if ($locDanhMuc !== "") {
            $where[] = "b.category_id = :loc_danh_muc";
            $params["loc_danh_muc"] = $locDanhMuc;
        }

        if ($locNam !== "") {
            $where[] = "b.nam_xuat_ban = :loc_nam";
            $params["loc_nam"] = $locNam;
        }

        $dieuKien = "WHERE " . implode(" AND ", $where);

        $sql = "
            SELECT
                b.id,
                b.ma_sach,
                b.ten_sach,
                b.ma_tac_gia,
                b.tac_gia,
                c.ten_danh_muc AS danh_muc,
                b.nha_xuat_ban,
                b.nam_xuat_ban,
                b.isbn,
                b.gia_sach,
                b.mo_ta
            FROM books b
            INNER JOIN Categories c
                ON b.category_id = c.category_id
            $dieuKien
            ORDER BY b.id ASC
            LIMIT $limit OFFSET $offset
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function kiemTraMaSachTonTai($maSach, $excludeId = null)
    {
        if ($excludeId !== null) {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM books
                WHERE ma_sach = :ma_sach
                AND id != :id
            ");
            $stmt->execute([
                "ma_sach" => $maSach,
                "id" => (int)$excludeId
            ]);
        } else {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM books
                WHERE ma_sach = :ma_sach
            ");
            $stmt->execute([
                "ma_sach" => $maSach
            ]);
        }
        return (int)$stmt->fetchColumn() > 0;
    }

    public function kiemTraIsbnTonTai($isbn, $excludeId = null)
    {
        if ($excludeId !== null) {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM books
                WHERE isbn = :isbn
                AND id != :id
            ");
            $stmt->execute([
                "isbn" => $isbn,
                "id" => (int)$excludeId
            ]);
        } else {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM books
                WHERE isbn = :isbn
            ");
            $stmt->execute([
                "isbn" => $isbn
            ]);
        }
        return (int)$stmt->fetchColumn() > 0;
    }

    public function themDauSach($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO books
            (
                ma_sach,
                ten_sach,
                ma_tac_gia,
                tac_gia,
                category_id,
                nha_xuat_ban,
                nam_xuat_ban,
                isbn,
                gia_sach,
                mo_ta
            )
            VALUES
            (
                :ma_sach,
                :ten_sach,
                :ma_tac_gia,
                :tac_gia,
                :category_id,
                :nha_xuat_ban,
                :nam_xuat_ban,
                :isbn,
                :gia_sach,
                :mo_ta
            )
        ");

        return $stmt->execute([
            "ma_sach" => $data['ma_sach'],
            "ten_sach" => $data['ten_sach'],
            "ma_tac_gia" => $data['ma_tac_gia'],
            "tac_gia" => $data['tac_gia'],
            "category_id" => $data['category_id'],
            "nha_xuat_ban" => $data['nha_xuat_ban'],
            "nam_xuat_ban" => $data['nam_xuat_ban'],
            "isbn" => $data['isbn'],
            "gia_sach" => $data['gia_sach'],
            "mo_ta" => $data['mo_ta']
        ]);
    }

    public function suaDauSach($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE books SET
                ma_sach = :ma_sach,
                ten_sach = :ten_sach,
                ma_tac_gia = :ma_tac_gia,
                tac_gia = :tac_gia,
                category_id = :category_id,
                nha_xuat_ban = :nha_xuat_ban,
                nam_xuat_ban = :nam_xuat_ban,
                isbn = :isbn,
                gia_sach = :gia_sach,
                mo_ta = :mo_ta
            WHERE id = :id
        ");

        return $stmt->execute([
            "ma_sach" => $data['ma_sach'],
            "ten_sach" => $data['ten_sach'],
            "ma_tac_gia" => $data['ma_tac_gia'],
            "tac_gia" => $data['tac_gia'],
            "category_id" => $data['category_id'],
            "nha_xuat_ban" => $data['nha_xuat_ban'],
            "nam_xuat_ban" => $data['nam_xuat_ban'],
            "isbn" => $data['isbn'],
            "gia_sach" => $data['gia_sach'],
            "mo_ta" => $data['mo_ta'],
            "id" => (int)$id
        ]);
    }

    public function xoaDauSach($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = :id");
        return $stmt->execute(["id" => (int)$id]);
    }
}
