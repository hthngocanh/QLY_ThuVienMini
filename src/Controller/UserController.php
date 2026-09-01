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
     * Hiển thị & Cập nhật thông tin cá nhân
     */
    public function profile()
    {
        $maNguoiDungHienTai = $_SESSION["maNguoiDung"] ?? $_SESSION["ma_nguoi_dung"] ?? "";

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
            $khoaLop = trim($_POST["khoaLop"] ?? "");

            if ($hoTen === "") {
                $errors["hoTen"] = "Vui lòng nhập họ và tên.";
            }

            if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = "Email không hợp lệ.";
            } elseif ($this->userModel->kiemTraEmailTonTai($email, $maNguoiDungHienTai)) {
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
            'currentUser' => $currentUser,
            'errors' => $errors,
            'thongBao' => $thongBao,
            'loaiThongBao' => $loaiThongBao,
            'activePage' => 'nguoidung'
        ]);
    }

    public function index()
    {
        $this->profile();
    }
}
