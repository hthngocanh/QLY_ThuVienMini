<?php
// src/Model/BorrowSlipModel.php

require_once __DIR__ . '/../../database/config/database.php';

class BorrowSlipModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function getAllPhieuMuon()
    {
        $sql = "
            SELECT
                bs.ID_PhieuMuon,
                bs.ID_NguoiDung,
                bs.ID_BanSao,
                nd.ma_nguoi_dung,
                nd.ho_ten,
                bc.ma_ban_sao,
                b.ten_sach,
                bs.NgayMuon,
                bs.NgayTra,
                bs.TrangThai
            FROM borrow_slips bs
            INNER JOIN users nd
                ON bs.ID_NguoiDung = nd.id
            INNER JOIN book_copies bc
                ON bs.ID_BanSao = bc.id
            INNER JOIN books b
                ON bc.book_id = b.id
            WHERE bs.DaXoa = 0    
            ORDER BY bs.ID_PhieuMuon DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPhieuMuonById($id)
    {
        $sql = "
            SELECT
                bs.ID_PhieuMuon,
                bs.ID_NguoiDung,
                bs.ID_BanSao,
                nd.ma_nguoi_dung,
                bc.ma_ban_sao,
                bs.NgayMuon,
                bs.NgayTra,
                bs.TrangThai
            FROM borrow_slips bs
            INNER JOIN users nd
                ON bs.ID_NguoiDung = nd.id
            INNER JOIN book_copies bc
                ON bs.ID_BanSao = bc.id
            WHERE bs.ID_PhieuMuon = ?
            AND bs.DaXoa = 0
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getIdNguoiDungTheoMa($maNguoiDung)
    {
        $sql = "
            SELECT id
            FROM users
            WHERE ma_nguoi_dung = ?
              AND trang_thai = 'Hoạt động'
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$maNguoiDung]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['id'] : 0;
    }

    public function getIdBanSaoTheoMa($maBanSao)
    {
        $sql = "
            SELECT id
            FROM book_copies
            WHERE ma_ban_sao = ?
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$maBanSao]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['id'] : 0;
    }

    public function addPhieuMuon($idNguoiDung, $idBanSao, $ngayMuon, $ngayTra, $trangThai)
    {
        $sql = "
            INSERT INTO borrow_slips
            (
                ID_NguoiDung,
                ID_BanSao,
                NgayMuon,
                NgayTra,
                TrangThai
            )
            VALUES (?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            (int)$idNguoiDung,
            (int)$idBanSao,
            $ngayMuon,
            $ngayTra,
            $trangThai
        ]);
    }

    public function updatePhieuMuon($id, $idNguoiDung, $idBanSao, $ngayMuon, $ngayTra, $trangThai)
    {
        $sql = "
            UPDATE borrow_slips
            SET
                ID_NguoiDung = ?,
                ID_BanSao = ?,
                NgayMuon = ?,
                NgayTra = ?,
                TrangThai = ?
            WHERE ID_PhieuMuon = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            (int)$idNguoiDung,
            (int)$idBanSao,
            $ngayMuon,
            $ngayTra,
            $trangThai,
            (int)$id
        ]);
    }

public function deletePhieuMuon($id)
{
    $sql = "UPDATE borrow_slips
            SET DaXoa = 1
            WHERE ID_PhieuMuon = ?";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        (int)$id
    ]);
    }
}