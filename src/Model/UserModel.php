<?php
// src/Model/UserModel.php

require_once __DIR__ . '/../../database/config/database.php';

class UserModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function layDanhSachNguoiDung($tuKhoa = "")
    {
        if ($tuKhoa !== "") {
            $sql = "SELECT * FROM users WHERE ma_nguoi_dung LIKE :kw OR ho_ten LIKE :kw OR email LIKE :kw ORDER BY id DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['kw' => "%$tuKhoa%"]);
        } else {
            $sql = "SELECT * FROM users ORDER BY id DESC";
            $stmt = $this->pdo->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layNguoiDungTheoMa($maNguoiDung)
    {
        $sql = "SELECT * FROM users WHERE ma_nguoi_dung = :ma";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['ma' => $maNguoiDung]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function layNguoiDungDangNhap($taiKhoan)
    {
        $sql = "SELECT * FROM users WHERE ma_nguoi_dung = :tk OR email = :tk LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tk' => $taiKhoan]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function layNguoiDungTheoEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function themNguoiDung($ma, $hoTen, $email, $matKhau, $sdt, $khoaLop, $vaiTro, $trangThai)
    {
        $sql = "INSERT INTO users (ma_nguoi_dung, ho_ten, email, mat_khau, sdt, khoa_lop, vai_tro, trang_thai)
                VALUES (:ma, :hoten, :email, :matkhau, :sdt, :khoalop, :vaitro, :trangthai)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'ma' => $ma,
            'hoten' => $hoTen,
            'email' => $email,
            'matkhau' => $matKhau,
            'sdt' => $sdt,
            'khoalop' => $khoaLop,
            'vaitro' => $vaiTro,
            'trangthai' => $trangThai
        ]);
    }

    public function suaNguoiDung($maCu, $maMoi, $hoTen, $email, $sdt, $khoaLop, $vaiTro, $trangThai)
    {
        $sql = "UPDATE users
                SET ma_nguoi_dung = :maMoi, ho_ten = :hoten, email = :email, sdt = :sdt,
                    khoa_lop = :khoalop, vai_tro = :vaitro, trang_thai = :trangthai
                WHERE ma_nguoi_dung = :maCu";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'maMoi' => $maMoi,
            'hoten' => $hoTen,
            'email' => $email,
            'sdt' => $sdt,
            'khoalop' => $khoaLop,
            'vaitro' => $vaiTro,
            'trangthai' => $trangThai,
            'maCu' => $maCu
        ]);
    }

    public function capNhatThongTinDocGia($maNguoiDung, $hoTen, $email, $sdt, $khoaLop)
    {
        $sql = "UPDATE users
                SET ho_ten = :hoten, email = :email, sdt = :sdt, khoa_lop = :khoalop
                WHERE ma_nguoi_dung = :ma";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'hoten' => $hoTen,
            'email' => $email,
            'sdt' => $sdt,
            'khoalop' => $khoaLop,
            'ma' => $maNguoiDung
        ]);
    }

    public function kiemTraMaNguoiDungTonTai($maNguoiDung)
    {
        $sql = "SELECT id FROM users WHERE ma_nguoi_dung = :ma LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['ma' => $maNguoiDung]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function kiemTraEmailTonTai($email, $maNguoiDungHienTai = null)
    {
        if ($maNguoiDungHienTai !== null) {
            $sql = "SELECT id FROM users WHERE email = :email AND ma_nguoi_dung != :ma LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['email' => $email, 'ma' => $maNguoiDungHienTai]);
        } else {
            $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function doiMatKhauTheoMa($maNguoiDung, $matKhauMoiHash)
    {
        $sql = "UPDATE users SET mat_khau = :matkhau WHERE ma_nguoi_dung = :ma";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'matkhau' => $matKhauMoiHash,
            'ma' => $maNguoiDung
        ]);
    }

    public function doiMatKhauTheoEmail($email, $matKhauMoiHash)
    {
        $sql = "UPDATE users SET mat_khau = :matkhau WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'matkhau' => $matKhauMoiHash,
            'email' => $email
        ]);
    }

    public function xoaNguoiDung($maNguoiDung)
    {
        // Khóa tài khoản thay vì xóa cứng để bảo toàn dữ liệu liên kết
        $sql = "UPDATE users SET trang_thai = 'Bị khóa' WHERE ma_nguoi_dung = :ma";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['ma' => $maNguoiDung]);
    }

    public function laySoSachDangMuon($maNguoiDung)
    {
        try {
            $sql = "SELECT COUNT(*) FROM borrow_slips bs 
                    JOIN users u ON bs.ID_NguoiDung = u.id 
                    WHERE u.ma_nguoi_dung = :ma AND bs.TrangThai IN ('Đang mượn', 'Chờ duyệt', 'Quá hạn')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['ma' => $maNguoiDung]);
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    public function kiemTraDuocMuon($trangThai, $soSachDangMuon, $hanMucMuon)
    {
        if ($trangThai !== 'Hoạt động') return false;
        if ($soSachDangMuon >= $hanMucMuon) return false;
        return true;
    }

    public function layLyDoKhongDuocMuon($trangThai, $soSachDangMuon, $hanMucMuon)
    {
        if ($trangThai !== 'Hoạt động') return 'Tài khoản đang bị khóa';
        if ($soSachDangMuon >= $hanMucMuon) return 'Đã đạt giới hạn mượn';
        return '';
    }

    /**
     * Xác thực mật khẩu hỗ trợ Bcrypt, Plain-text và MD5
     */
    public function xacThucMatKhau($matKhauNhap, $matKhauDb)
    {
        if (password_verify($matKhauNhap, $matKhauDb)) {
            return true;
        }
        if ($matKhauNhap === $matKhauDb) {
            return true;
        }
        if (md5($matKhauNhap) === $matKhauDb) {
            return true;
        }
        return false;
    }
}
