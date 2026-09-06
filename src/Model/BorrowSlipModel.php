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

    // =========================================================
    // SELECT DÙNG CHUNG
    // =========================================================
    private function baseSelect()
    {
        return "
            SELECT
                pm.ID_PhieuMuon,

                pm.ID_NguoiDung,
                u.ma_nguoi_dung,
                u.ho_ten,

                pm.ID_BanSao,
                bc.ma_ban_sao,

                b.ma_sach,
                b.ten_sach,

                pm.NgayMuon,
                pm.NgayTra,
                pm.TrangThai

            FROM borrow_slips pm

            LEFT JOIN users u
                ON u.id = pm.ID_NguoiDung

            LEFT JOIN book_copies bc
                ON bc.id = pm.ID_BanSao

            LEFT JOIN books b
                ON b.id = bc.book_id
        ";
    }


    // =========================================================
    // DANH SÁCH PHIẾU MƯỢN
    // Dùng cho Thủ thư + Admin
    // =========================================================
    public function layDanhSachPhieuMuon(
        string $tuKhoa = '',
        string $trangThai = ''
    ) {
        $sql = $this->baseSelect() . "
            WHERE 1 = 1
        ";

        $params = [];

        // Tìm kiếm
        if ($tuKhoa !== '') {
            $sql .= "
                AND (
                    u.ma_nguoi_dung LIKE :tu_khoa
                    OR u.ho_ten LIKE :tu_khoa
                    OR bc.ma_ban_sao LIKE :tu_khoa
                    OR b.ma_sach LIKE :tu_khoa
                    OR b.ten_sach LIKE :tu_khoa
                )
            ";

            $params['tu_khoa'] = '%' . $tuKhoa . '%';
        }

        // Lọc trạng thái
        if ($trangThai !== '') {
            $sql .= "
                AND pm.TrangThai = :trang_thai
            ";

            $params['trang_thai'] = $trangThai;
        }

        $sql .= "
            ORDER BY pm.ID_PhieuMuon DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // =========================================================
    // LẤY PHIẾU THEO ID
    // =========================================================
    public function layPhieuTheoId(int $id)
    {
        $sql = $this->baseSelect() . "
            WHERE pm.ID_PhieuMuon = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // =========================================================
    // THÊM PHIẾU MƯỢN
    // =========================================================
    public function themPhieuMuon(
        $idNguoiDung,
        $idBanSao,
        string $ngayMuon,
        ?string $ngayTra,
        string $trangThai
    ) {
        // Tra cứu ID nếu truyền vào là Mã chuỗi
        if (!is_numeric($idNguoiDung)) {
            $stmtUser = $this->pdo->prepare("SELECT id FROM users WHERE ma_nguoi_dung = :ma LIMIT 1");
            $stmtUser->execute(['ma' => $idNguoiDung]);
            $userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);
            $idNguoiDung = $userRow ? $userRow['id'] : null;
        }

        if (!is_numeric($idBanSao)) {
            $stmtCopy = $this->pdo->prepare("SELECT id FROM book_copies WHERE ma_ban_sao = :ma LIMIT 1");
            $stmtCopy->execute(['ma' => $idBanSao]);
            $copyRow = $stmtCopy->fetch(PDO::FETCH_ASSOC);
            $idBanSao = $copyRow ? $copyRow['id'] : null;
        }

        $sql = "
            INSERT INTO borrow_slips
            (
                ID_NguoiDung,
                ID_BanSao,
                NgayMuon,
                NgayTra,
                TrangThai
            )
            VALUES
            (
                :id_nguoi_dung,
                :id_ban_sao,
                :ngay_muon,
                :ngay_tra,
                :trang_thai
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id_nguoi_dung' => $idNguoiDung,
            'id_ban_sao' => $idBanSao,
            'ngay_muon' => $ngayMuon,
            'ngay_tra' => !empty($ngayTra) ? $ngayTra : null,
            'trang_thai' => $trangThai
        ]);
    }


    // =========================================================
    // SỬA PHIẾU MƯỢN
    // =========================================================
    public function suaPhieuMuon(
        int $id,
        $idNguoiDung,
        $idBanSao,
        string $ngayMuon,
        ?string $ngayTra,
        string $trangThai
    ) {
        // Tra cứu ID nếu truyền vào là Mã chuỗi (VD: 'SV001', 'BS004')
        if (!is_numeric($idNguoiDung)) {
            $stmtUser = $this->pdo->prepare("SELECT id FROM users WHERE ma_nguoi_dung = :ma LIMIT 1");
            $stmtUser->execute(['ma' => $idNguoiDung]);
            $userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);
            $idNguoiDung = $userRow ? $userRow['id'] : null;
        }

        if (!is_numeric($idBanSao)) {
            $stmtCopy = $this->pdo->prepare("SELECT id FROM book_copies WHERE ma_ban_sao = :ma LIMIT 1");
            $stmtCopy->execute(['ma' => $idBanSao]);
            $copyRow = $stmtCopy->fetch(PDO::FETCH_ASSOC);
            $idBanSao = $copyRow ? $copyRow['id'] : null;
        }

        $sql = "
            UPDATE borrow_slips
            SET
                ID_NguoiDung = :id_nguoi_dung,
                ID_BanSao = :id_ban_sao,
                NgayMuon = :ngay_muon,
                NgayTra = :ngay_tra,
                TrangThai = :trang_thai

            WHERE ID_PhieuMuon = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id_nguoi_dung' => $idNguoiDung,
            'id_ban_sao' => $idBanSao,
            'ngay_muon' => $ngayMuon,
            'ngay_tra' => !empty($ngayTra) ? $ngayTra : null,
            'trang_thai' => $trangThai,
            'id' => $id
        ]);
    }


    // =========================================================
    // XÓA PHIẾU MƯỢN
    // =========================================================
    public function xoaPhieuMuon(int $id)
    {
        $sql = "
            DELETE FROM borrow_slips
            WHERE ID_PhieuMuon = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id
        ]);
    }


    // =========================================================
    // THỐNG KÊ TỔNG QUAN
    // =========================================================
    public function demThongKeTongQuan()
    {
        $sql = "
            SELECT
                COUNT(*) AS tong,

                SUM(
                    CASE
                        WHEN TrangThai = 'Chờ duyệt'
                        THEN 1 ELSE 0
                    END
                ) AS cho_duyet,

                SUM(
                    CASE
                        WHEN TrangThai = 'Đang mượn'
                        THEN 1 ELSE 0
                    END
                ) AS dang_muon,

                SUM(
                    CASE
                        WHEN TrangThai = 'Quá hạn'
                        THEN 1 ELSE 0
                    END
                ) AS qua_han,

                SUM(
                    CASE
                        WHEN TrangThai = 'Đã trả'
                        THEN 1 ELSE 0
                    END
                ) AS da_tra

            FROM borrow_slips
        ";

        $stmt = $this->pdo->query($sql);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'tong' => (int)($row['tong'] ?? 0),
            'cho_duyet' => (int)($row['cho_duyet'] ?? 0),
            'dang_muon' => (int)($row['dang_muon'] ?? 0),
            'qua_han' => (int)($row['qua_han'] ?? 0),
            'da_tra' => (int)($row['da_tra'] ?? 0)
        ];
    }


    // =========================================================
    // LỊCH SỬ MƯỢN CỦA ĐỘC GIẢ
    // =========================================================
    public function layLichSuMuonTheoNguoiDung(
        string $maNguoiDung,
        string $trangThai = ''
    ) {
        $sql = $this->baseSelect() . "
            WHERE u.ma_nguoi_dung = :ma_nguoi_dung
        ";

        $params = [
            'ma_nguoi_dung' => $maNguoiDung
        ];

        if ($trangThai !== '') {
            $sql .= "
                AND pm.TrangThai = :trang_thai
            ";

            $params['trang_thai'] = $trangThai;
        }

        $sql .= "
            ORDER BY pm.ID_PhieuMuon DESC
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // =========================================================
    // CẤU HÌNH HẠN MỨC
    // =========================================================
    public function layCauHinhHanMuc()
    {
        $stmt = $this->pdo->query("
            SELECT *
            FROM cau_hinh_han_muc
            WHERE id = 1
        ");

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return [
                'id' => 1,
                'so_ngay_muon' => 14,
                'so_sach_toi_da' => 5,
                'cap_nhat_luc' => null
            ];
        }

        return $row;
    }


    // =========================================================
    // CẬP NHẬT CẤU HÌNH HẠN MỨC
    // =========================================================
    public function capNhatCauHinhHanMuc(
        int $soNgayMuon,
        int $soSachToiDa
    ) {
        $sql = "
            INSERT INTO cau_hinh_han_muc
            (
                id,
                so_ngay_muon,
                so_sach_toi_da,
                cap_nhat_luc
            )
            VALUES
            (
                1,
                :so_ngay_muon,
                :so_sach_toi_da,
                NOW()
            )

            ON DUPLICATE KEY UPDATE
                so_ngay_muon = :so_ngay_muon_2,
                so_sach_toi_da = :so_sach_toi_da_2,
                cap_nhat_luc = NOW()
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'so_ngay_muon' => $soNgayMuon,
            'so_sach_toi_da' => $soSachToiDa,

            'so_ngay_muon_2' => $soNgayMuon,
            'so_sach_toi_da_2' => $soSachToiDa
        ]);
    }


    // =========================================================
    // TÍNH HẠN TRẢ
    // =========================================================
    public function tinhHanTra(
        string $ngayMuon,
        int $soNgayMuon
    ): string {
        $timestamp = strtotime($ngayMuon);

        return date(
            'Y-m-d',
            strtotime(
                '+' . $soNgayMuon . ' days',
                $timestamp
            )
        );
    }


    // =========================================================
    // THỐNG KÊ THEO THÁNG
    // =========================================================
    public function layThongKeTheoThang(int $nam)
    {
        $sql = "
            SELECT
                MONTH(NgayMuon) AS thang,
                COUNT(*) AS so_luong

            FROM borrow_slips

            WHERE YEAR(NgayMuon) = :nam

            GROUP BY MONTH(NgayMuon)

            ORDER BY thang ASC
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'nam' => $nam
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Đủ 12 tháng
        $result = array_fill(1, 12, 0);

        foreach ($rows as $row) {
            $thang = (int)$row['thang'];

            if ($thang >= 1 && $thang <= 12) {
                $result[$thang] = (int)$row['so_luong'];
            }
        }

        return $result;
    }


    // =========================================================
    // TOP SÁCH ĐƯỢC MƯỢN NHIỀU NHẤT
    // =========================================================
    public function layTopSachDuocMuonNhieu(
        int $gioiHan = 5
    ) {
        $sql = "
            SELECT
                b.ma_sach,
                b.ten_sach,
                COUNT(*) AS so_lan_muon

            FROM borrow_slips pm

            LEFT JOIN book_copies bc
                ON bc.id = pm.ID_BanSao

            LEFT JOIN books b
                ON b.id = bc.book_id

            GROUP BY
                b.id,
                b.ma_sach,
                b.ten_sach

            ORDER BY so_lan_muon DESC

            LIMIT :gioi_han
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(
            ':gioi_han',
            $gioiHan,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}