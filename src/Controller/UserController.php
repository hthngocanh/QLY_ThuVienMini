<?php
// src/Controller/UserController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/UserModel.php';

class UserController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UserModel();
    }

    /**
     * Điều hướng thông minh theo vai trò
     */
    public function index()
    {
        $this->requireLogin();
        $vaiTro = $_SESSION["user"]["vai_tro"] ?? "Độc giả";

        if ($vaiTro === "Quản trị viên") {
            $this->quanLyDocGia();
        } elseif ($vaiTro === "Thủ thư") {
            $this->traCuuDocGia();
        } else {
            $this->profile();
        }
    }

    /**
     * =========================================================================
     * 1. THÔNG TIN CÁ NHÂN (DÙNG CHUNG: SINH VIÊN, THỦ THƯ, QUẢN TRỊ VIÊN)
     * =========================================================================
     */
    public function profile()
    {
        $this->requireLogin();

        $maNguoiDungHienTai = $_SESSION["maNguoiDung"] ?? $_SESSION["ma_nguoi_dung"] ?? $_SESSION["user"]["ma_nguoi_dung"] ?? "";

        if ($maNguoiDungHienTai === "") {
            $this->redirect("index.php?controller=auth&action=login");
        }

        $currentUser = $this->userModel->layNguoiDungTheoMa($maNguoiDungHienTai);

        if (!$currentUser) {
            $this->redirect("index.php?controller=auth&action=login");
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $errors = [];
        $thongBao = "";
        $loaiThongBao = "";

        if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST") {
            $csrfToken = $_POST["csrf_token"] ?? "";
            if (!hash_equals($_SESSION["csrf_token"] ?? "", $csrfToken)) {
                $errors["chung"] = "Yêu cầu không hợp lệ. Vui lòng thử lại.";
            }

            $hoTen = trim($_POST["hoTen"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $sdt = trim($_POST["sdt"] ?? "");
            $khoaLop = isset($_POST["khoaLop"]) ? trim($_POST["khoaLop"]) : ($currentUser["khoa_lop"] ?? "");

            if ($hoTen === "") {
                $errors["hoTen"] = "Vui lòng nhập họ và tên.";
            }

            if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = "Email không hợp lệ.";
            } elseif ($this->userModel->kiemTraEmailTonTai($email, (int)$currentUser['id'])) {
                $errors["email"] = "Email này đã được sử dụng bởi tài khoản khác.";
            }

            if (empty($errors)) {
                $this->userModel->capNhatThongTinDocGia($maNguoiDungHienTai, $hoTen, $email, $sdt, $khoaLop);

                $_SESSION["hoTen"] = $_SESSION["ho_ten"] = $hoTen;
                if (isset($_SESSION["user"])) {
                    $_SESSION["user"]["ho_ten"] = $hoTen;
                    $_SESSION["user"]["email"] = $email;
                    $_SESSION["user"]["sdt"] = $sdt;
                    $_SESSION["user"]["khoa_lop"] = $khoaLop;
                }

                $currentUser = $this->userModel->layNguoiDungTheoMa($maNguoiDungHienTai);

                $thongBao = "Cập nhật thông tin cá nhân thành công.";
                $loaiThongBao = "success";
            } else {
                $thongBao = "Vui lòng kiểm tra lại thông tin đã nhập.";
                $loaiThongBao = "error";
            }
        }

        $this->renderView("nguoidung/profile.php", [
            'currentUser'  => $currentUser,
            'errors'       => $errors,
            'thongBao'     => $thongBao,
            'loaiThongBao' => $loaiThongBao,
            'activePage'   => 'nguoidung',
            'activeAction' => 'profile'
        ]);
    }

    /**
     * =========================================================================
     * 2. TRA CỨU ĐỘC GIẢ (DÀNH CHO ROLE THỦ THƯ - READ ONLY)
     * =========================================================================
     */
    public function traCuuDocGia()
    {
        $this->requireLogin();
        $this->requireRole(['Thủ thư', 'Quản trị viên']);

        $tuKhoa = trim($_GET["tuKhoa"] ?? $_POST["tuKhoa"] ?? "");

        // TODO [PHIEU_MUON]:
        // Số sách đang mượn và trạng thái vi phạm được chuẩn bị từ UserModel,
        // sau này khi PhieuMuonModel hoàn thành sẽ kết nối dữ liệu chính thức tại đây.
        $danhSachDocGia = $this->userModel->layDanhSachDocGiaTraCuu($tuKhoa);

        $this->renderView("nguoidung/traCuuDocGia.php", [
            'danhSachDocGia' => $danhSachDocGia,
            'tuKhoa'         => $tuKhoa,
            'activePage'     => 'nguoidung',
            'activeAction'   => 'traCuuDocGia'
        ]);
    }

    /**
     * =========================================================================
     * 3. QUẢN LÝ ĐỘC GIẢ (DÀNH CHO ROLE QUẢN TRỊ VIÊN)
     * =========================================================================
     */
    public function quanLyDocGia()
    {
        $this->requireLogin();
        $this->requireRole(['Quản trị viên']);

        $thongBao = "";
        $loaiThongBao = "";

        if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST") {
            $hanhDong = $_POST["hanhDong"] ?? "";
            $maNguoiDung = trim($_POST["ma_nguoi_dung"] ?? "");

            if ($maNguoiDung !== "") {
                if ($hanhDong === "khoa") {
                    $this->userModel->khoaNguoiDung($maNguoiDung);
                    $thongBao = "Đã khóa tài khoản độc giả {$maNguoiDung} thành công.";
                    $loaiThongBao = "success";
                } elseif ($hanhDong === "mokhoa") {
                    $this->userModel->moKhoaNguoiDung($maNguoiDung);
                    $thongBao = "Đã mở khóa tài khoản độc giả {$maNguoiDung} thành công.";
                    $loaiThongBao = "success";
                }
            }
        }

        $tuKhoa = trim($_GET["tuKhoa"] ?? "");
        $danhSachDocGia = $this->userModel->layDanhSachDocGia($tuKhoa);

        $this->renderView("nguoidung/quanLyDocGia.php", [
            'danhSachDocGia' => $danhSachDocGia,
            'tuKhoa'         => $tuKhoa,
            'thongBao'       => $thongBao,
            'loaiThongBao'   => $loaiThongBao,
            'activePage'     => 'nguoidung',
            'activeAction'   => 'quanLyDocGia'
        ]);
    }

    /**
     * =========================================================================
     * 4. QUẢN LÝ NHÂN SỰ (DÀNH CHO ROLE QUẢN TRỊ VIÊN)
     * =========================================================================
     */
    public function quanLyNhanSu()
    {
        $this->requireLogin();
        $this->requireRole(['Quản trị viên']);

        $thongBao = "";
        $loaiThongBao = "";
        $errorsThem = [];
        $formDataThem = [
            'ma_nguoi_dung' => '',
            'ho_ten' => '',
            'email' => ''
        ];
        $moModalThem = false;

        if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST") {
            $hanhDong = $_POST["hanhDong"] ?? "";

            if ($hanhDong === "them") {
                $ma = trim($_POST["ma_nguoi_dung"] ?? "");
                $hoTen = trim($_POST["ho_ten"] ?? "");
                $email = trim($_POST["email"] ?? "");

                $formDataThem['ma_nguoi_dung'] = $ma;
                $formDataThem['ho_ten'] = $hoTen;
                $formDataThem['email'] = $email;

                // 1. Kiểm tra Mã nhân sự
                if ($ma === "") {
                    $errorsThem["ma_nguoi_dung"] = "Vui lòng nhập mã nhân sự.";
                } elseif ($this->userModel->kiemTraMaNguoiDungTonTai($ma)) {
                    $errorsThem["ma_nguoi_dung"] = "Mã nhân sự đã tồn tại.";
                }

                // 2. Kiểm tra Họ tên
                if ($hoTen === "") {
                    $errorsThem["ho_ten"] = "Vui lòng nhập họ tên.";
                }

                // 3. Kiểm tra Email
                if ($email === "") {
                    $errorsThem["email"] = "Vui lòng nhập email.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errorsThem["email"] = "Email không hợp lệ.";
                } elseif ($this->userModel->kiemTraEmailTonTai($email)) {
                    $errorsThem["email"] = "Email này đã được sử dụng.";
                }

                // Nếu có lỗi -> Giữ popup mở, không reset form, không insert database
                if (!empty($errorsThem)) {
                    $moModalThem = true;
                } else {
                    $matKhauMacDinh = 'Thuvien12345!';
                    $hash = password_hash($matKhauMacDinh, PASSWORD_DEFAULT);
                    $this->userModel->themNhanSu($ma, $hoTen, $email, $hash);
                    $thongBao = "Thêm nhân sự thành công.";
                    $loaiThongBao = "success";
                    $formDataThem = [
                        'ma_nguoi_dung' => '',
                        'ho_ten' => '',
                        'email' => ''
                    ];
                    $moModalThem = false;
                }
            } elseif ($hanhDong === "khoa") {
                $ma = trim($_POST["ma_nguoi_dung"] ?? "");
                if ($ma !== "") {
                    $this->userModel->khoaNguoiDung($ma);
                    $thongBao = "Đã khóa tài khoản nhân sự {$ma} thành công.";
                    $loaiThongBao = "success";
                }
            } elseif ($hanhDong === "mokhoa") {
                $ma = trim($_POST["ma_nguoi_dung"] ?? "");
                if ($ma !== "") {
                    $this->userModel->moKhoaNguoiDung($ma);
                    $thongBao = "Đã mở khóa tài khoản nhân sự {$ma} thành công.";
                    $loaiThongBao = "success";
                }
            }
        }

        $tuKhoa = trim($_GET["tuKhoa"] ?? "");
        $danhSachNhanSu = $this->userModel->layDanhSachNhanSu($tuKhoa);

        $this->renderView("nguoidung/quanLyNhanSu.php", [
            'danhSachNhanSu' => $danhSachNhanSu,
            'tuKhoa'         => $tuKhoa,
            'thongBao'       => $thongBao,
            'loaiThongBao'   => $loaiThongBao,
            'errorsThem'     => $errorsThem,
            'formDataThem'   => $formDataThem,
            'moModalThem'    => $moModalThem,
            'activePage'     => 'nguoidung',
            'activeAction'   => 'quanLyNhanSu'
        ]);
    }

    /**
     * =========================================================================
     * 5. YÊU CẦU CẤP LẠI MẬT KHẨU (DÀNH CHO ROLE QUẢN TRỊ VIÊN)
     * =========================================================================
     */
    public function yeuCauCapLaiMatKhau()
    {
        $this->requireLogin();
        $this->requireRole(['Quản trị viên']);

        $thongBao = "";
        $loaiThongBao = "";

        if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST") {
            $hanhDong = $_POST["hanhDong"] ?? "";
            $id = (int)($_POST["id"] ?? 0);

            if ($id > 0) {
                if ($hanhDong === "duyet") {
                    $thanhCong = $this->userModel->duyetYeuCauCapLaiMatKhau($id);
                    if ($thanhCong) {
                        $thongBao = "Đã duyệt yêu cầu và cập nhật mật khẩu mới cho người dùng thành công.";
                        $loaiThongBao = "success";
                    } else {
                        $thongBao = "Không thể duyệt yêu cầu này (có thể yêu cầu đã được xử lý hoặc tài khoản không tồn tại).";
                        $loaiThongBao = "error";
                    }
                } elseif ($hanhDong === "tuchoi") {
                    $thanhCong = $this->userModel->tuChoiYeuCauCapLaiMatKhau($id);
                    if ($thanhCong) {
                        $thongBao = "Đã từ chối yêu cầu cấp lại mật khẩu.";
                        $loaiThongBao = "success";
                    } else {
                        $thongBao = "Không thể từ chối yêu cầu này (có thể yêu cầu đã được xử lý).";
                        $loaiThongBao = "error";
                    }
                }
            }
        }

        $danhSachYeuCau = $this->userModel->layDanhSachYeuCauCapLaiMatKhau();

        $this->renderView("nguoidung/yeuCauCapLaiMatKhau.php", [
            'danhSachYeuCau' => $danhSachYeuCau,
            'thongBao'       => $thongBao,
            'loaiThongBao'   => $loaiThongBao,
            'activePage'     => 'nguoidung',
            'activeAction'   => 'yeuCauCapLaiMatKhau'
        ]);
    }
}
