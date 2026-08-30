<?php
session_start();

require_once __DIR__ . '/nguoiDung/functionsNguoiDung.php';

$errors = [];
$thongBaoThanhCong = "";
$taiKhoan = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $taiKhoan = trim($_POST["taiKhoan"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $matKhauMoi = $_POST["matKhauMoi"] ?? "";
    $xacNhanMatKhau = $_POST["xacNhanMatKhau"] ?? "";

    // =========================
    // KIỂM TRA DỮ LIỆU
    // =========================
    if ($taiKhoan === "") {
        $errors["taiKhoan"] = "Vui lòng nhập mã sinh viên.";
    }

    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Email không hợp lệ.";
    }

    if ($matKhauMoi === "") {
        $errors["matKhauMoi"] = "Vui lòng nhập mật khẩu mới.";
    }

    if ($xacNhanMatKhau === "") {
        $errors["xacNhanMatKhau"] = "Vui lòng xác nhận mật khẩu mới.";
    }

    if ($matKhauMoi !== "" && $xacNhanMatKhau !== "" && $matKhauMoi !== $xacNhanMatKhau) {
        $errors["xacNhanMatKhau"] = "Mật khẩu xác nhận không khớp.";
    }

    // =========================
    // XÁC MINH VÀ ĐỔI MẬT KHẨU
    // =========================
    if (empty($errors)) {
        $nguoiDung = layNguoiDungTheoMa($taiKhoan);

        if (!$nguoiDung || strtolower(trim($nguoiDung["email"])) !== strtolower($email)) {
            $errors["chung"] = "Mã sinh viên hoặc Email xác minh không chính xác.";
        } elseif ($nguoiDung["trang_thai"] === "Bị khóa") {
            $errors["chung"] = "Tài khoản đang bị khóa. Vui lòng liên hệ thủ thư/quản trị viên.";
        } else {
            $matKhauHash = password_hash($matKhauMoi, PASSWORD_DEFAULT);
            doiMatKhauTheoMa($nguoiDung["ma_nguoi_dung"], $matKhauHash);

            echo "<script>
                    alert('Đổi mật khẩu thành công! Vui lòng đăng nhập bằng mật khẩu mới.');
                    window.location.href = 'dangnhap.php';
                  </script>";
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - Thư viện Mini</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 480px;
        }

        .card {
            background: white;
            padding: 35px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        h1 {
            text-align: center;
            color: #1e3a8a;
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 24px;
        }

        .sub-title {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .alert-error {
            background-color: #fee2e2;
            border-left: 4px solid #dc2626;
            color: #b91c1c;
            padding: 12px 14px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            color: #334155;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .input-error {
            border-color: #dc2626;
        }

        .field-error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            font-weight: bold;
            cursor: pointer;
            font-size: 15px;
            transition: background 0.2s, opacity 0.2s;
            margin-top: 10px;
        }

        button:hover {
            background: #1d4ed8;
        }

        .auth-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
        }

        .auth-footer a {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="card">

            <h1>QUÊN MẬT KHẨU</h1>
            <div class="sub-title">Nhập mã SV và Email đã đăng ký để tạo mật khẩu mới</div>

            <?php if (isset($errors["chung"])): ?>
                <div class="alert-error">
                    <?= htmlspecialchars($errors["chung"]) ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>

                <!-- MÃ SINH VIÊN -->
                <div class="form-group">
                    <label for="taiKhoan">Mã sinh viên</label>
                    <input
                        type="text"
                        id="taiKhoan"
                        name="taiKhoan"
                        placeholder="VD: SV001"
                        value="<?= htmlspecialchars($taiKhoan) ?>"
                        class="<?= isset($errors["taiKhoan"]) ? "input-error" : "" ?>"
                        autofocus>

                    <?php if (isset($errors["taiKhoan"])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors["taiKhoan"]) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label for="email">Email đã đăng ký</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="VD: an@gmail.com"
                        value="<?= htmlspecialchars($email) ?>"
                        class="<?= isset($errors["email"]) ? "input-error" : "" ?>">

                    <?php if (isset($errors["email"])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors["email"]) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- MẬT KHẨU MỚI -->
                <div class="form-group">
                    <label for="matKhauMoi">Mật khẩu mới</label>
                    <input
                        type="password"
                        id="matKhauMoi"
                        name="matKhauMoi"
                        placeholder="Nhập mật khẩu mới"
                        class="<?= isset($errors["matKhauMoi"]) ? "input-error" : "" ?>">

                    <?php if (isset($errors["matKhauMoi"])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors["matKhauMoi"]) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- XÁC NHẬN MẬT KHẨU MỚI -->
                <div class="form-group">
                    <label for="xacNhanMatKhau">Xác nhận mật khẩu mới</label>
                    <input
                        type="password"
                        id="xacNhanMatKhau"
                        name="xacNhanMatKhau"
                        placeholder="Nhập lại mật khẩu mới"
                        class="<?= isset($errors["xacNhanMatKhau"]) ? "input-error" : "" ?>">

                    <?php if (isset($errors["xacNhanMatKhau"])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors["xacNhanMatKhau"]) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit">
                    Đặt lại mật khẩu
                </button>

                <div class="auth-footer">
                    Quay lại <a href="dangnhap.php">Đăng nhập</a>
                </div>

            </form>

        </div>

    </div>

</body>

</html>
