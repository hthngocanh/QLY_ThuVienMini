<?php
function getDB()
{
    $host = '127.0.0.1';
    $dbname = 'qly_thuvienmini'; // Đã khớp với DB trên DBeaver
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Lỗi kết nối CSDL: " . $e->getMessage());
    }
}


function layDanhSachNguoiDung($tuKhoa = "")
{
    $pdo = getDB();
    if ($tuKhoa !== "") {
        $sql = "SELECT * FROM users WHERE ma_nguoi_dung LIKE :kw OR ho_ten LIKE :kw OR email LIKE :kw";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['kw' => "%$tuKhoa%"]);
    } else {
        $sql = "SELECT * FROM users";
        $stmt = $pdo->query($sql);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function layNguoiDungTheoMa($maNguoiDung)
{
    $pdo = getDB();
    $sql = "SELECT * FROM users WHERE ma_nguoi_dung = :ma";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ma' => $maNguoiDung]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function themNguoiDung($ma, $hoTen, $email, $matKhau, $sdt, $khoaLop, $vaiTro, $trangThai)
{
    $pdo = getDB();
    $sql = "INSERT INTO users (ma_nguoi_dung, ho_ten, email, mat_khau, sdt, khoa_lop, vai_tro, trang_thai)
            VALUES (:ma, :hoten, :email, :matkhau, :sdt, :khoalop, :vaitro, :trangthai)";
    $stmt = $pdo->prepare($sql);
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

function suaNguoiDung($maCu, $maMoi, $hoTen, $email, $sdt, $khoaLop, $vaiTro, $trangThai)
{
    $pdo = getDB();
    $sql = "UPDATE users
            SET ma_nguoi_dung = :maMoi, ho_ten = :hoten, email = :email, sdt = :sdt,
                khoa_lop = :khoalop, vai_tro = :vaitro, trang_thai = :trangthai
            WHERE ma_nguoi_dung = :maCu";
    $stmt = $pdo->prepare($sql);
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

function xoaNguoiDung($maNguoiDung)
{
    $pdo = getDB();
    // Thay vì dùng DELETE, ta UPDATE trạng thái để bảo toàn lịch sử mượn sách
    $sql = "UPDATE users SET trang_thai = 'Bị khóa' WHERE ma_nguoi_dung = :ma";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['ma' => $maNguoiDung]);
}

function laySoSachDangMuon($maNguoiDung)
{
    return 0; // Tạm thời trả về 0 để không bị lỗi giao diện
}


function kiemTraDuocMuon($trangThai, $soSachDangMuon, $hanMucMuon)
{
    if ($trangThai !== 'Hoạt động') return false;
    if ($soSachDangMuon >= $hanMucMuon) return false;
    return true;
}

function layLyDoKhongDuocMuon($trangThai, $soSachDangMuon, $hanMucMuon)
{
    if ($trangThai !== 'Hoạt động') return 'Tài khoản đang bị khóa';
    if ($soSachDangMuon >= $hanMucMuon) return 'Đã đạt giới hạn mượn';
    return '';
}
