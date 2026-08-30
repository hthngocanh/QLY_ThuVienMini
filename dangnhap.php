<?php
session_start();

require_once __DIR__ . '/nguoiDung/functionsNguoiDung.php';

// Nếu đã đăng nhập thì chuyển hướng về trang chủ
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

$errors = [];
$taiKhoan = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $taiKhoan = trim($_POST["taiKhoan"] ?? "");
    $matKhau = $_POST["matKhau"] ?? "";

    // =========================
    // KIỂM TRA DỮ LIỆU ĐẦU VÀO
    // =========================
    if ($taiKhoan === "") {
        $errors["taiKhoan"] = "Vui lòng nhập mã sinh viên hoặc email.";
    }

    if ($matKhau === "") {
        $errors["matKhau"] = "Vui lòng nhập mật khẩu.";
    }

    // =========================
    // XÁC THỰC ĐĂNG NHẬP
    // =========================
    if (empty($errors)) {
        $nguoiDung = layNguoiDungDangNhap($taiKhoan);

        $matKhauChinhXac = false;

        if ($nguoiDung) {
            $dbPass = $nguoiDung["mat_khau"] ?? "";

            // 1. Kiểm tra bằng password_verify (chuẩn mã hóa bcrypt)
            if (password_verify($matKhau, $dbPass)) {
                $matKhauChinhXac = true;
            }
            // 2. Hỗ trợ trường hợp mật khẩu trong DB là văn bản thuần (plain-text)
            elseif ($matKhau === $dbPass) {
                $matKhauChinhXac = true;
            }
            // 3. Hỗ trợ trường hợp mật khẩu trong DB mã hóa MD5
            elseif (md5($matKhau) === $dbPass) {
                $matKhauChinhXac = true;
            }
        }

        if (!$nguoiDung || !$matKhauChinhXac) {
            $errors["chung"] = "Tài khoản hoặc mật khẩu không chính xác.";
        } elseif ($nguoiDung["trang_thai"] === "Bị khóa") {
            $errors["chung"] = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ thủ thư/quản trị viên.";
        } else {
            // Đăng nhập thành công -> lưu Session
            $_SESSION["user"] = $nguoiDung;
            $_SESSION["ma_nguoi_dung"] = $nguoiDung["ma_nguoi_dung"];
            $_SESSION["ho_ten"] = $nguoiDung["ho_ten"];
            $_SESSION["vai_tro"] = $nguoiDung["vai_tro"];

            header("Location: index.php");
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
    <title>Đăng nhập - Thư viện Mini</title>

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
            max-width: 460px;
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
            margin-bottom: 20px;
        }

        .label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        label {
            font-weight: bold;
            color: #334155;
            font-size: 14px;
        }

        .forgot-link {
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
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

            <h1>ĐĂNG NHẬP</h1>
            <div class="sub-title">Hệ thống Quản lý Thư viện Mini</div>

            <?php if (isset($errors["chung"])): ?>
                <div class="alert-error">
                    <?= htmlspecialchars($errors["chung"]) ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>

                <!-- TÀI KHOẢN (MSV / EMAIL) -->
                <div class="form-group">
                    <label for="taiKhoan">
                        Mã sinh viên hoặc Email
                    </label>

                    <input
                        type="text"
                        id="taiKhoan"
                        name="taiKhoan"
                        placeholder="Nhập mã SV (VD: SV001) hoặc email"
                        value="<?= htmlspecialchars($taiKhoan) ?>"
                        class="<?= isset($errors["taiKhoan"]) ? "input-error" : "" ?>"
                        autofocus>

                    <?php if (isset($errors["taiKhoan"])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors["taiKhoan"]) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- MẬT KHẨU -->
                <div class="form-group">
                    <div class="label-row">
                        <label for="matKhau">
                            Mật khẩu
                        </label>
                        <a href="quenmatkhau.php" class="forgot-link">Quên mật khẩu?</a>
                    </div>

                    <input
                        type="password"
                        id="matKhau"
                        name="matKhau"
                        placeholder="Nhập mật khẩu"
                        class="<?= isset($errors["matKhau"]) ? "input-error" : "" ?>">

                    <?php if (isset($errors["matKhau"])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors["matKhau"]) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit">
                    Đăng nhập
                </button>

                <div class="auth-footer">
                    Chưa có tài khoản? <a href="dangky.php">Đăng ký ngay</a>
                </div>

            </form>

        </div>

    </div>

</body>

</html>
