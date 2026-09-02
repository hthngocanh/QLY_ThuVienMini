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

    /**
     * Lấy thông tin người dùng theo Mã người dùng
     */
    public function layNguoiDungTheoMa($maNguoiDung)
    {
        $sql = "SELECT * FROM users WHERE ma_nguoi_dung = :ma LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['ma' => $maNguoiDung]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin người dùng theo ID (Primary Key)
     */
    public function layNguoiDungTheoId($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin người dùng khi đăng nhập (theo Mã hoặc Email)
     */
    public function layNguoiDungDangNhap($taiKhoan)
    {
        $sql = "SELECT * FROM users WHERE ma_nguoi_dung = :tk OR email = :tk LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tk' => $taiKhoan]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy người dùng theo Email
     */
    public function layNguoiDungTheoEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật thông tin cá nhân của người dùng (dùng chung cho Sinh viên, Thủ thư, Admin)
     */
    public function capNhatThongTinDocGia($maNguoiDung, $hoTen, $email, $sdt, $khoaLop)
    {
        $sql = "UPDATE users
                SET ho_ten = :hoten, email = :email, sdt = :sdt, khoa_lop = :khoalop
                WHERE ma_nguoi_dung = :ma";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'hoten'    => $hoTen,
            'email'    => $email,
            'sdt'      => $sdt,
            'khoalop'  => $khoaLop,
            'ma'       => $maNguoiDung
        ]);
    }

    // =========================================================================
    // DÀNH CHO ROLE THỦ THƯ: TRA CỨU ĐỘC GIẢ (READ-ONLY)
    // =========================================================================

    /**
     * Tra cứu danh sách độc giả (dành riêng cho Thủ thư - READ ONLY)
     *
     * // TODO [PHIEU_MUON]:
     * // Dữ liệu số sách đang mượn (so_sach_dang_muon) và trạng thái vi phạm (co_vi_pham)
     * // sau này sẽ được kết nối chính thức từ module Phiếu mượn (PhieuMuonModel).
     * // Hiện tại UserModel truy vấn trực tiếp từ bảng borrow_slips để hỗ trợ hiển thị.
     */
    public function layDanhSachDocGiaTraCuu($tuKhoa = "")
    {
        return $this->layDanhSachDocGia($tuKhoa);
    }

    // =========================================================================
    // DÀNH CHO ROLE QUẢN TRỊ VIÊN: 1. QUẢN LÝ ĐỘC GIẢ
    // =========================================================================

    /**
     * Lấy danh sách độc giả kèm số sách đang mượn và trạng thái vi phạm
     */
    public function layDanhSachDocGia($tuKhoa = "")
    {
        $tuKhoa = trim((string)$tuKhoa);
        $params = [];

        $sql = "
            SELECT
                u.id,
                u.ma_nguoi_dung,
                u.ho_ten,
                u.email,
                u.sdt,
                u.khoa_lop,
                u.vai_tro,
                u.trang_thai,
                u.ngay_tao,
                5 AS han_muc,
                (
                    SELECT COUNT(*)
                    FROM borrow_slips bs
                    WHERE bs.ID_NguoiDung = u.id AND bs.TrangThai = 'Đang mượn'
                ) AS so_sach_dang_muon,
                CASE
                    WHEN u.trang_thai = 'Bị khóa' THEN 1
                    WHEN EXISTS (
                        SELECT 1
                        FROM borrow_slips bs
                        WHERE bs.ID_NguoiDung = u.id AND bs.TrangThai = 'Quá hạn'
                    ) THEN 1
                    ELSE 0
                END AS co_vi_pham
            FROM users u
            WHERE u.vai_tro = 'Độc giả'
        ";

        if ($tuKhoa !== '') {
            $sql .= " AND (u.ma_nguoi_dung LIKE :tk OR u.ho_ten LIKE :tk OR u.email LIKE :tk)";
            $params['tk'] = '%' . $tuKhoa . '%';
        }

        $sql .= " ORDER BY u.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $danhSach = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nạp lịch sử mượn cho mỗi độc giả để phục vụ modal popup
        foreach ($danhSach as &$docGia) {
            $docGia['lich_su_muon'] = $this->layLichSuMuonDocGia((int)$docGia['id']);
        }

        return $danhSach;
    }

    /**
     * Lấy chi tiết lịch sử mượn của một độc giả (dùng cho Modal Popup)
     * // TODO [PHIEU_MUON]: Sẽ kết nối trực tiếp với PhieuMuonModel khi hoàn thành.
     */
    public function layLichSuMuonDocGia($idNguoiDung)
    {
        try {
            $sql = "
                SELECT
                    bs.ID_PhieuMuon,
                    bs.ID_NguoiDung,
                    bs.ID_BanSao,
                    bs.NgayMuon,
                    bs.NgayTra,
                    DATE_ADD(bs.NgayMuon, INTERVAL 14 DAY) AS HanTra,
                    bs.TrangThai,
                    b.ten_sach,
                    bc.ma_ban_sao
                FROM borrow_slips bs
                LEFT JOIN book_copies bc ON bs.ID_BanSao = bc.id
                LEFT JOIN books b ON bc.book_id = b.id
                WHERE bs.ID_NguoiDung = :id
                ORDER BY bs.ID_PhieuMuon DESC
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => (int)$idNguoiDung]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // =========================================================================
    // DÀNH CHO ROLE QUẢN TRỊ VIÊN: 2. QUẢN LÝ NHÂN SỰ
    // =========================================================================

    /**
     * Lấy danh sách nhân sự (Thủ thư)
     */
    public function layDanhSachNhanSu($tuKhoa = "")
    {
        $tuKhoa = trim((string)$tuKhoa);
        $params = [];

        $sql = "
            SELECT
                u.id,
                u.ma_nguoi_dung,
                u.ho_ten,
                u.email,
                u.sdt,
                u.khoa_lop,
                u.vai_tro,
                u.trang_thai,
                u.ngay_tao
            FROM users u
            WHERE u.vai_tro = 'Thủ thư'
        ";

        if ($tuKhoa !== '') {
            $sql .= " AND (u.ma_nguoi_dung LIKE :tk OR u.ho_ten LIKE :tk OR u.email LIKE :tk)";
            $params['tk'] = '%' . $tuKhoa . '%';
        }

        $sql .= " ORDER BY u.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm nhân sự mới (Role cố định là 'Thủ thư', trạng thái mặc định 'Hoạt động')
     */
    public function themNhanSu($maNguoiDung, $hoTen, $email, $matKhauHash, $sdt = null, $khoaLop = null)
    {
        $sql = "INSERT INTO users (ma_nguoi_dung, ho_ten, email, mat_khau, sdt, khoa_lop, vai_tro, trang_thai, ngay_tao)
                VALUES (:ma, :hoten, :email, :matkhau, :sdt, :khoalop, 'Thủ thư', 'Hoạt động', NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'ma'       => $maNguoiDung,
            'hoten'    => $hoTen,
            'email'    => $email,
            'matkhau'  => $matKhauHash,
            'sdt'      => $sdt,
            'khoalop'  => $khoaLop
        ]);
    }

    /**
     * Khóa tài khoản người dùng theo Mã người dùng
     */
    public function khoaNguoiDung($maNguoiDung)
    {
        $sql = "UPDATE users SET trang_thai = 'Bị khóa' WHERE ma_nguoi_dung = :ma";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['ma' => $maNguoiDung]);
    }

    /**
     * Mở khóa tài khoản người dùng theo Mã người dùng
     */
    public function moKhoaNguoiDung($maNguoiDung)
    {
        $sql = "UPDATE users SET trang_thai = 'Hoạt động' WHERE ma_nguoi_dung = :ma";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['ma' => $maNguoiDung]);
    }

    /**
     * Đổi trạng thái tài khoản theo ID
     */
    public function doiTrangThaiNguoiDung($id, $trangThaiMoi)
    {
        $sql = "UPDATE users SET trang_thai = :tt WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'tt' => $trangThaiMoi,
            'id' => (int)$id
        ]);
    }

    // =========================================================================
    // DÀNH CHO ROLE QUẢN TRỊ VIÊN: 3. YÊU CẦU CẤP LẠI MẬT KHẨU
    // =========================================================================

    /**
     * Tạo yêu cầu cấp lại mật khẩu (dành cho Sinh viên và Thủ thư)
     * Trạng thái mặc định: 'Chờ duyệt', mật khẩu mới được hash an toàn
     */
    public function taoYeuCauCapLaiMatKhau($maNguoiDung, $hoTen, $email, $matKhauMoiHash)
    {
        $sql = "INSERT INTO password_reset_requests (ma_nguoi_dung, ho_ten, email, mat_khau_moi, trang_thai, created_at)
                VALUES (:ma, :hoten, :email, :matkhaumoi, 'Chờ duyệt', NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'ma'          => $maNguoiDung,
            'hoten'       => $hoTen,
            'email'       => $email,
            'matkhaumoi'  => $matKhauMoiHash
        ]);
    }

    /**
     * Lấy danh sách yêu cầu cấp lại mật khẩu (ưu tiên 'Chờ duyệt' lên đầu)
     */
    public function layDanhSachYeuCauCapLaiMatKhau($locTrangThai = "")
    {
        $sql = "SELECT * FROM password_reset_requests";
        $params = [];

        if ($locTrangThai !== "") {
            $sql .= " WHERE trang_thai = :tt";
            $params['tt'] = $locTrangThai;
        }

        // Sắp xếp: Chờ duyệt lên đầu tiên, sau đó theo thời gian mới nhất
        $sql .= " ORDER BY CASE WHEN trang_thai = 'Chờ duyệt' THEN 0 ELSE 1 END, id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết một yêu cầu cấp lại mật khẩu theo ID
     */
    public function layYeuCauCapLaiMatKhauTheoId($id)
    {
        $sql = "SELECT * FROM password_reset_requests WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Duyệt yêu cầu cấp lại mật khẩu:
     * 1. Cập nhật mật khẩu mới trong bảng users
     * 2. Chuyển trạng thái yêu cầu thành 'Đã duyệt'
     */
    public function duyetYeuCauCapLaiMatKhau($id)
    {
        $req = $this->layYeuCauCapLaiMatKhauTheoId($id);
        if (!$req || $req['trang_thai'] !== 'Chờ duyệt') {
            return false;
        }

        $user = $this->layNguoiDungTheoMa($req['ma_nguoi_dung']);
        if (!$user) {
            return false;
        }

        $this->pdo->beginTransaction();
        try {
            // 1. Cập nhật mật khẩu trong users
            $stmtUser = $this->pdo->prepare("UPDATE users SET mat_khau = :hash WHERE ma_nguoi_dung = :ma");
            $stmtUser->execute([
                'hash' => $req['mat_khau_moi'],
                'ma'   => $req['ma_nguoi_dung']
            ]);

            // 2. Cập nhật trạng thái request thành 'Đã duyệt'
            $stmtReq = $this->pdo->prepare("UPDATE password_reset_requests SET trang_thai = 'Đã duyệt' WHERE id = :id");
            $stmtReq->execute(['id' => (int)$id]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    /**
     * Từ chối yêu cầu cấp lại mật khẩu:
     * Giữ nguyên mật khẩu hiện tại của user, chuyển trạng thái request thành 'Đã từ chối'
     */
    public function tuChoiYeuCauCapLaiMatKhau($id)
    {
        $req = $this->layYeuCauCapLaiMatKhauTheoId($id);
        if (!$req || $req['trang_thai'] !== 'Chờ duyệt') {
            return false;
        }

        $sql = "UPDATE password_reset_requests SET trang_thai = 'Đã từ chối' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => (int)$id]);
    }

    // =========================================================================
    // HÀM BỔ TRỢ / KIỂM TRA CHUNG
    // =========================================================================

    public function themNguoiDung($maNguoiDung, $hoTen, $email, $matKhauHash, $sdt = "", $khoaLop = "", $vaiTro = "Độc giả", $trangThai = "Hoạt động")
    {
        $sql = "INSERT INTO users (ma_nguoi_dung, ho_ten, email, mat_khau, sdt, khoa_lop, vai_tro, trang_thai, ngay_tao)
                VALUES (:ma, :hoten, :email, :matkhau, :sdt, :khoalop, :vaitro, :trangthai, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'ma'        => $maNguoiDung,
            'hoten'     => $hoTen,
            'email'     => $email,
            'matkhau'   => $matKhauHash,
            'sdt'       => $sdt,
            'khoalop'   => $khoaLop,
            'vaitro'    => $vaiTro,
            'trangthai' => $trangThai
        ]);
    }

    public function suaNguoiDung($id, $maNguoiDung, $hoTen, $email, $sdt, $khoaLop, $vaiTro, $trangThai)
    {
        $sql = "UPDATE users
                SET ma_nguoi_dung = :ma, ho_ten = :hoten, email = :email, sdt = :sdt,
                    khoa_lop = :khoalop, vai_tro = :vaitro, trang_thai = :trangthai
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'ma'        => $maNguoiDung,
            'hoten'     => $hoTen,
            'email'     => $email,
            'sdt'       => $sdt,
            'khoalop'   => $khoaLop,
            'vaitro'    => $vaiTro,
            'trangthai' => $trangThai,
            'id'        => (int)$id
        ]);
    }

    public function kiemTraMaNguoiDungTonTai($maNguoiDung, $excludeId = 0)
    {
        $sql = "SELECT id FROM users WHERE ma_nguoi_dung = :ma";
        $params = ['ma' => $maNguoiDung];

        if ($excludeId > 0) {
            $sql .= " AND id != :exId";
            $params['exId'] = (int)$excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    public function kiemTraEmailTonTai($email, $excludeId = 0)
    {
        $sql = "SELECT id FROM users WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeId > 0) {
            $sql .= " AND id != :exId";
            $params['exId'] = (int)$excludeId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    public function doiMatKhauTheoMa($maNguoiDung, $matKhauHash)
    {
        $sql = "UPDATE users SET mat_khau = :matkhau WHERE ma_nguoi_dung = :ma";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'matkhau' => $matKhauHash,
            'ma'      => $maNguoiDung
        ]);
    }

    public function xacThucMatKhau($matKhauNhap, $matKhauHashDB)
    {
        if (password_verify($matKhauNhap, $matKhauHashDB)) {
            return true;
        }
        if ($matKhauNhap === $matKhauHashDB) {
            return true;
        }
        return false;
    }
}
