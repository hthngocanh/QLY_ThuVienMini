<?php
// src/Model/BookCopyModel.php

require_once __DIR__ . '/../../database/config/database.php';

class BookCopyModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function layDanhSachDauSach()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT id, ma_sach, ten_sach
                FROM books
                ORDER BY ten_sach ASC
            ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Danh sách bản sao đang hoạt động.
     * Bản sao đã xóa mềm (deleted_at IS NOT NULL) sẽ không xuất hiện.
     */
    public function layDanhSachBanSao()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT
                    bc.id,
                    bc.book_id,
                    bc.ma_ban_sao,
                    bc.vi_tri,
                    bc.trang_thai,
                    b.ma_sach,
                    b.ten_sach
                FROM book_copies bc
                INNER JOIN books b
                    ON bc.book_id = b.id
                WHERE bc.deleted_at IS NULL
                ORDER BY bc.id DESC
            ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Danh sách bản sao đã xóa mềm - chỉ dùng cho Quản trị viên.
     */
    public function layDanhSachBanSaoDaXoa()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT
                    bc.id,
                    bc.book_id,
                    bc.ma_ban_sao,
                    bc.vi_tri,
                    bc.trang_thai,
                    bc.deleted_at,
                    b.ma_sach,
                    b.ten_sach
                FROM book_copies bc
                INNER JOIN books b
                    ON bc.book_id = b.id
                WHERE bc.deleted_at IS NOT NULL
                ORDER BY bc.deleted_at DESC, bc.id DESC
            ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Dữ liệu tra cứu dành cho Độc giả.
     * Không lộ ID bản sao riêng lẻ, chỉ tổng hợp số bản còn theo đầu sách.
     */
    public function layTinhTrangDauSach()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT
                    b.id AS book_id,
                    b.ma_sach,
                    b.ten_sach,
                    COUNT(bc.id) AS tong_ban,
                    SUM(CASE WHEN bc.trang_thai = 'Có sẵn' THEN 1 ELSE 0 END) AS so_ban_con,
                    SUM(CASE WHEN bc.trang_thai = 'Đang mượn' THEN 1 ELSE 0 END) AS so_ban_dang_muon,
                    SUM(CASE WHEN bc.trang_thai = 'Chưa có sẵn' THEN 1 ELSE 0 END) AS so_ban_chua_co_san
                FROM books b
                LEFT JOIN book_copies bc
                    ON bc.book_id = b.id
                    AND bc.deleted_at IS NULL
                GROUP BY b.id, b.ma_sach, b.ten_sach
                ORDER BY b.ten_sach ASC
            ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function layBanSaoTheoId($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT
                id,
                book_id,
                ma_ban_sao,
                vi_tri,
                trang_thai,
                deleted_at
            FROM book_copies
            WHERE id = :id
              AND deleted_at IS NULL
        ");

        $stmt->execute([":id" => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function themBanSao($bookId, $maBanSao, $viTri, $trangThai)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO book_copies
                (book_id, ma_ban_sao, vi_tri, trang_thai)
            VALUES
                (:book_id, :ma_ban_sao, :vi_tri, :trang_thai)
        ");

        return $stmt->execute([
            ":book_id" => (int)$bookId,
            ":ma_ban_sao" => $maBanSao,
            ":vi_tri" => $viTri,
            ":trang_thai" => $trangThai
        ]);
    }

    public function suaBanSao($id, $bookId, $maBanSao, $viTri, $trangThai)
    {
        $stmt = $this->pdo->prepare("
            UPDATE book_copies
            SET
                book_id = :book_id,
                ma_ban_sao = :ma_ban_sao,
                vi_tri = :vi_tri,
                trang_thai = :trang_thai
            WHERE id = :id
              AND deleted_at IS NULL
        ");

        return $stmt->execute([
            ":book_id" => (int)$bookId,
            ":ma_ban_sao" => $maBanSao,
            ":vi_tri" => $viTri,
            ":trang_thai" => $trangThai,
            ":id" => (int)$id
        ]);
    }

    /**
     * Xóa mềm: không DELETE dữ liệu.
     * Có thêm điều kiện trạng thái để DB tự bảo vệ bản sao đang mượn.
     */
    public function xoaBanSao($id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE book_copies
            SET deleted_at = CURRENT_TIMESTAMP
            WHERE id = :id
              AND deleted_at IS NULL
              AND trang_thai <> 'Đang mượn'
        ");

        $stmt->execute([":id" => (int)$id]);
        return $stmt->rowCount() > 0;
    }

    public function khoiPhucBanSao($id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE book_copies
            SET deleted_at = NULL
            WHERE id = :id
              AND deleted_at IS NOT NULL
        ");

        $stmt->execute([":id" => (int)$id]);
        return $stmt->rowCount() > 0;
    }

    public function thongKeBanSao()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT
                    SUM(CASE WHEN deleted_at IS NULL THEN 1 ELSE 0 END) AS tong_hoat_dong,
                    SUM(CASE WHEN deleted_at IS NULL AND trang_thai = 'Có sẵn' THEN 1 ELSE 0 END) AS co_san,
                    SUM(CASE WHEN deleted_at IS NULL AND trang_thai = 'Đang mượn' THEN 1 ELSE 0 END) AS dang_muon,
                    SUM(CASE WHEN deleted_at IS NULL AND trang_thai = 'Chưa có sẵn' THEN 1 ELSE 0 END) AS chua_co_san,
                    SUM(CASE WHEN deleted_at IS NOT NULL THEN 1 ELSE 0 END) AS da_xoa
                FROM book_copies
            ");

            $data = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

            return [
                'tong_hoat_dong' => (int)($data['tong_hoat_dong'] ?? 0),
                'co_san' => (int)($data['co_san'] ?? 0),
                'dang_muon' => (int)($data['dang_muon'] ?? 0),
                'chua_co_san' => (int)($data['chua_co_san'] ?? 0),
                'da_xoa' => (int)($data['da_xoa'] ?? 0),
            ];
        } catch (PDOException $e) {
            return [
                'tong_hoat_dong' => 0,
                'co_san' => 0,
                'dang_muon' => 0,
                'hong' => 0,
                'da_xoa' => 0,
            ];
        }
    }

    /**
     * Giữ lại cho trang kiemtra.php cũ.
     * Chỉ tra cứu các bản sao chưa bị xóa mềm.
     */
    public function kiemTraTrangThaiBanSao($idBanSao, $idDauSach, $maBanSao, $trangThai)
    {
        $where = ["deleted_at IS NULL"];
        $params = [];

        if ($idBanSao !== "") {
            $where[] = "id = :id";
            $params["id"] = (int)$idBanSao;
        }

        if ($idDauSach !== "") {
            $where[] = "book_id = :book_id";
            $params["book_id"] = (int)$idDauSach;
        }

        if ($maBanSao !== "") {
            $where[] = "ma_ban_sao = :ma";
            $params["ma"] = $maBanSao;
        }

        if ($trangThai !== "") {
            $where[] = "trang_thai = :tt";
            $params["tt"] = $trangThai;
        }

        // Nếu người dùng không nhập tiêu chí nào ngoài điều kiện deleted_at,
        // không trả về toàn bộ dữ liệu.
        if (count($where) === 1) {
            return [];
        }

        $sql = "SELECT * FROM book_copies WHERE " . implode(" AND ", $where);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
