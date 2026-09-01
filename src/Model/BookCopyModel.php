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
            $stmtBooks = $this->pdo->query("
                SELECT id, ma_sach, ten_sach
                FROM books
                ORDER BY ten_sach ASC
            ");
            return $stmtBooks->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

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
                ORDER BY bc.id DESC
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
                trang_thai
            FROM book_copies
            WHERE id = :id
        ");
        $stmt->execute([":id" => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function demSoPhieuMuonCuaBanSao($id)
    {
        $stmtCheck = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM borrow_slips
            WHERE ID_BanSao = :id
        ");
        $stmtCheck->execute([":id" => (int)$id]);
        return (int)$stmtCheck->fetchColumn();
    }

    public function themBanSao($bookId, $maBanSao, $viTri, $trangThai)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO book_copies
                (
                    book_id,
                    ma_ban_sao,
                    vi_tri,
                    trang_thai
                )
            VALUES
                (
                    :book_id,
                    :ma_ban_sao,
                    :vi_tri,
                    :trang_thai
                )
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
        ");
        return $stmt->execute([
            ":book_id" => (int)$bookId,
            ":ma_ban_sao" => $maBanSao,
            ":vi_tri" => $viTri,
            ":trang_thai" => $trangThai,
            ":id" => (int)$id
        ]);
    }

    public function xoaBanSao($id)
    {
        $stmtDelete = $this->pdo->prepare("
            DELETE FROM book_copies
            WHERE id = :id
        ");
        return $stmtDelete->execute([":id" => (int)$id]);
    }

    public function kiemTraTrangThaiBanSao($idBanSao, $idDauSach, $maBanSao, $trangThai)
    {
        $where = [];
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

        if (empty($where)) {
            return [];
        }

        $sql = "SELECT * FROM book_copies WHERE " . implode(" AND ", $where);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
