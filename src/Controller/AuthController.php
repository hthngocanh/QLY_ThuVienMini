<?php
// src/Controller/AuthController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/UserModel.php';

class AuthController extends BaseController
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
     * Xử lý Đăng nhập
     */
    public function login()
    {
        // Nếu đã đăng nhập thì chuyển hướng về trang chủ
        if (isset($_SESSION["user"])) {
            $this->redirect("index.php");
        }

        $errors = [];
        $taiKhoan = "";
        $matKhau = "";

        if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST") {
            $taiKhoan = trim($_POST["taiKhoan"] ?? "");
            $matKhau = $_POST["matKhau"] ?? "";

            if ($taiKhoan === "") {
                $errors["taiKhoan"] = "Vui lòng nhập mã sinh viên hoặc email.";
            }

            if ($matKhau === "") {
                $errors["matKhau"] = "Vui lòng nhập mật khẩu.";
            }

            if (empty($errors)) {
                $nguoiDung = $this->userModel->layNguoiDungDangNhap($taiKhoan);
                $matKhauChinhXac = false;

                if ($nguoiDung) {
                    $dbPass = $nguoiDung["mat_khau"] ?? "";
                    $matKhauChinhXac = $this->userModel->xacThucMatKhau($matKhau, $dbPass);
                }

                if (!$nguoiDung || !$matKhauChinhXac) {
                    $errors["chung"] = "Tài khoản hoặc mật khẩu không chính xác.";
                } elseif ($nguoiDung["trang_thai"] === "Bị khóa") {
                    $errors["chung"] = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ thủ thư/quản trị viên.";
                } else {
                    // Đăng nhập thành công -> làm mới session ID và lưu thông tin
                    session_regenerate_id(true);
                    $_SESSION["user"] = $nguoiDung;
                    $_SESSION["ma_nguoi_dung"] = $_SESSION["maNguoiDung"] = $nguoiDung["ma_nguoi_dung"];
                    $_SESSION["ho_ten"] = $_SESSION["hoTen"] = $nguoiDung["ho_ten"];
                    $_SESSION["vai_tro"] = $_SESSION["vaiTro"] = $nguoiDung["vai_tro"];

                    $this->redirect("index.php");
                }
            }
        }

        $this->renderView("auth/login.php", [
            'errors' => $errors,
            'taiKhoan' => $taiKhoan,
            'matKhau' => $matKhau
        ]);
    }

    /**
     * Xử lý Đăng ký
     */
    public function register()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $errors = [];
        $maNguoiDung = "";
        $hoTen = "";
        $email = "";
        $sdt = "";
        $khoaLop = "";
        $matKhau = "";
        $xacNhanMatKhau = "";

        if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST") {
            $csrfToken = $_POST["csrf_token"] ?? "";
            if (!hash_equals($_SESSION["csrf_token"] ?? "", $csrfToken)) {
                $errors["chung"] = "Yêu cầu không hợp lệ. Vui lòng thử lại.";
            }

            $maNguoiDung = trim($_POST["maNguoiDung"] ?? "");
            $hoTen = trim($_POST["hoTen"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $sdt = trim($_POST["sdt"] ?? "");
            $khoaLop = trim($_POST["khoaLop"] ?? "");
            $matKhau = $_POST["matKhau"] ?? "";
            $xacNhanMatKhau = $_POST["xacNhanMatKhau"] ?? "";

            if ($maNguoiDung === "") {
                $errors["maNguoiDung"] = "Vui lòng nhập mã sinh viên.";
            }

            if ($hoTen === "") {
                $errors["hoTen"] = "Vui lòng nhập họ tên.";
            }

            if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = "Email không hợp lệ.";
            }

            if ($sdt === "") {
                $errors["sdt"] = "Vui lòng nhập số điện thoại.";
            }

            if ($khoaLop === "") {
                $errors["khoaLop"] = "Vui lòng nhập khoa/lớp.";
            }

            if ($matKhau === "") {
                $errors["matKhau"] = "Vui lòng nhập mật khẩu.";
            } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $matKhau)) {
                $errors["matKhau"] = "Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.";
            }

            if ($xacNhanMatKhau === "") {
                $errors["xacNhanMatKhau"] = "Vui lòng xác nhận mật khẩu.";
            }

            if ($matKhau !== "" && $xacNhanMatKhau !== "" && $matKhau !== $xacNhanMatKhau) {
                $errors["xacNhanMatKhau"] = "Mật khẩu xác nhận không khớp.";
            }

            if (!isset($errors["maNguoiDung"])) {
                if ($this->userModel->kiemTraMaNguoiDungTonTai($maNguoiDung)) {
                    $errors["maNguoiDung"] = "Mã sinh viên đã được đăng ký.";
                }
            }

            if (!isset($errors["email"])) {
                if ($this->userModel->kiemTraEmailTonTai($email)) {
                    $errors["email"] = "Email này đã được đăng ký trong hệ thống.";
                }
            }

            if (empty($errors)) {
                $matKhauHash = password_hash($matKhau, PASSWORD_DEFAULT);
                $vaiTro = "Độc giả";
                $trangThai = "Hoạt động";

                $this->userModel->themNguoiDung(
                    $maNguoiDung,
                    $hoTen,
                    $email,
                    $matKhauHash,
                    $sdt,
                    $khoaLop,
                    $vaiTro,
                    $trangThai
                );

                echo "<script>
                        alert('Đăng ký tài khoản thành công! Vui lòng đăng nhập.');
                        window.location.href = 'index.php?controller=auth&action=login';
                      </script>";
                exit;
            }
        }

        $this->renderView("auth/register.php", [
            'errors' => $errors,
            'maNguoiDung' => $maNguoiDung,
            'hoTen' => $hoTen,
            'email' => $email,
            'sdt' => $sdt,
            'khoaLop' => $khoaLop,
            'matKhau' => $matKhau,
            'xacNhanMatKhau' => $xacNhanMatKhau
        ]);
    }

    /**
     * Xử lý Đăng xuất
     */
    public function logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        $this->redirect("index.php?controller=auth&action=login");
    }

    /**
     * Xử lý Đổi mật khẩu
     */
    public function changePassword()
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("index.php?controller=auth&action=login");
        }

        $currentUser = $_SESSION["user"];
        $vaiTro = $currentUser["vai_tro"] ?? "";

        // Chỉ Độc giả mới tự đổi mật khẩu theo logic ban đầu
        if ($vaiTro !== "Độc giả") {
            echo "<script>
                    alert('Chức năng đổi mật khẩu chỉ dành cho Độc giả. Tài khoản nội bộ do Quản trị viên cấp/quản lý.');
                    window.location.href = 'index.php';
                  </script>";
            exit;
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $maNguoiDung = $currentUser["ma_nguoi_dung"];
        $errors = [];
        $matKhauCu = "";
        $matKhauMoi = "";
        $xacNhanMatKhau = "";

        if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST") {
            $csrfToken = $_POST["csrf_token"] ?? "";
            if (!hash_equals($_SESSION["csrf_token"] ?? "", $csrfToken)) {
                $errors["chung"] = "Yêu cầu không hợp lệ. Vui lòng thử lại.";
            }

            $matKhauCu = $_POST["matKhauCu"] ?? "";
            $matKhauMoi = $_POST["matKhauMoi"] ?? "";
            $xacNhanMatKhau = $_POST["xacNhanMatKhau"] ?? "";

            if ($matKhauCu === "") {
                $errors["matKhauCu"] = "Vui lòng nhập mật khẩu hiện tại.";
            }

            if ($matKhauMoi === "") {
                $errors["matKhauMoi"] = "Vui lòng nhập mật khẩu mới.";
            } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $matKhauMoi)) {
                $errors["matKhauMoi"] = "Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.";
            }

            if ($xacNhanMatKhau === "") {
                $errors["xacNhanMatKhau"] = "Vui lòng xác nhận mật khẩu mới.";
            } elseif ($matKhauMoi !== "" && $matKhauMoi !== $xacNhanMatKhau) {
                $errors["xacNhanMatKhau"] = "Mật khẩu xác nhận không khớp.";
            }

            if (empty($errors)) {
                $userDB = $this->userModel->layNguoiDungTheoMa($maNguoiDung);
                $dungMatKhauCu = false;

                if ($userDB) {
                    $dungMatKhauCu = $this->userModel->xacThucMatKhau($matKhauCu, $userDB["mat_khau"] ?? "");
                }

                if (!$dungMatKhauCu) {
                    $errors["matKhauCu"] = "Mật khẩu hiện tại không chính xác.";
                } else {
                    $hashMoi = password_hash($matKhauMoi, PASSWORD_DEFAULT);
                    $this->userModel->doiMatKhauTheoMa($maNguoiDung, $hashMoi);

                    $_SESSION["user"]["mat_khau"] = $hashMoi;

                    echo "<script>
                            alert('Đổi mật khẩu thành công!');
                            window.location.href = 'index.php';
                          </script>";
                    exit;
                }
            }
        }

        $this->renderView("auth/change_password.php", [
            'errors' => $errors,
            'matKhauCu' => $matKhauCu,
            'matKhauMoi' => $matKhauMoi,
            'xacNhanMatKhau' => $xacNhanMatKhau
        ]);
    }

    /**
     * Xử lý Quên mật khẩu
     */
    public function forgotPassword()
    {
        if (isset($_SESSION["user"])) {
            $this->redirect("index.php");
        }

        $errors = [];
        $taiKhoan = "";
        $matKhauMoi = "";
        $xacNhanMatKhau = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $taiKhoan = trim($_POST["taiKhoan"] ?? "");
            $matKhauMoi = $_POST["matKhauMoi"] ?? "";
            $xacNhanMatKhau = $_POST["xacNhanMatKhau"] ?? "";

            if ($taiKhoan === "") {
                $errors["taiKhoan"] = "Vui lòng nhập mã sinh viên hoặc email.";
            } else {
                $nguoiDung = $this->userModel->layNguoiDungDangNhap($taiKhoan);
                if (!$nguoiDung) {
                    $errors["taiKhoan"] = "Không tìm thấy tài khoản hoặc email trong hệ thống.";
                } elseif ($nguoiDung["trang_thai"] === "Bị khóa") {
                    $errors["taiKhoan"] = "Tài khoản này đã bị khóa. Vui lòng liên hệ thủ thư/quản trị viên.";
                }
            }

            if ($matKhauMoi === "") {
                $errors["matKhauMoi"] = "Vui lòng nhập mật khẩu mới.";
            } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $matKhauMoi)) {
                $errors["matKhauMoi"] = "Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.";
            }

            if ($xacNhanMatKhau === "") {
                $errors["xacNhanMatKhau"] = "Vui lòng xác nhận mật khẩu mới.";
            } elseif ($matKhauMoi !== "" && $matKhauMoi !== $xacNhanMatKhau) {
                $errors["xacNhanMatKhau"] = "Mật khẩu xác nhận không khớp.";
            }

            if (empty($errors) && isset($nguoiDung)) {
                $matKhauHash = password_hash($matKhauMoi, PASSWORD_DEFAULT);
                $this->userModel->doiMatKhauTheoMa($nguoiDung["ma_nguoi_dung"], $matKhauHash);

                echo "<script>
                        alert('Đặt lại mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.');
                        window.location.href = 'index.php?controller=auth&action=login';
                      </script>";
                exit;
            }
        }

        $this->renderView("auth/forgot_password.php", [
            'errors' => $errors,
            'taiKhoan' => $taiKhoan,
            'matKhauMoi' => $matKhauMoi,
            'xacNhanMatKhau' => $xacNhanMatKhau
        ]);
    }
}
