<?php
session_start();

require_once __DIR__ . '/nguoiDung/functionsNguoiDung.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION["user"])) {
    header("Location: dangnhap.php");
    exit;
}

$currentUser = $_SESSION["user"];
$maNguoiDung = $currentUser["ma_nguoi_dung"];
$errors = [];
$thongBaoThanhCong = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matKhauCu = $_POST["matKhauCu"] ?? "";
    $matKhauMoi = $_POST["matKhauMoi"] ?? "";
    $xacNhanMatKhau = $_POST["xacNhanMatKhau"] ?? "";

    if ($matKhauCu === "") {
        $errors["matKhauCu"] = "Vui lòng nhập mật khẩu hiện tại.";
    }

    if ($matKhauMoi === "") {
        $errors["matKhauMoi"] = "Vui lòng nhập mật khẩu mới.";
    } elseif (strlen($matKhauMoi) < 6) {
        $errors["matKhauMoi"] = "Mật khẩu mới phải có ít nhất 6 ký tự.";
    }

    if ($xacNhanMatKhau === "") {
        $errors["xacNhanMatKhau"] = "Vui lòng xác nhận mật khẩu mới.";
    } elseif ($matKhauMoi !== "" && $matKhauMoi !== $xacNhanMatKhau) {
        $errors["xacNhanMatKhau"] = "Mật khẩu xác nhận không khớp.";
    }

    if (empty($errors)) {
        // Lấy thông tin user mới nhất từ DB
        $userDB = layNguoiDungTheoMa($maNguoiDung);

        $dungMatKhauCu = false;
        if ($userDB) {
            $dbPass = $userDB["mat_khau"];
            if (password_verify($matKhauCu, $dbPass) || $matKhauCu === $dbPass || md5($matKhauCu) === $dbPass) {
                $dungMatKhauCu = true;
            }
        }

        if (!$dungMatKhauCu) {
            $errors["matKhauCu"] = "Mật khẩu hiện tại không chính xác.";
        } else {
            $hashMoi = password_hash($matKhauMoi, PASSWORD_DEFAULT);
            doiMatKhauTheoMa($maNguoiDung, $hashMoi);

            // Cập nhật lại session
            $_SESSION["user"]["mat_khau"] = $hashMoi;

            echo "<script>
                    alert('Đổi mật khẩu thành công!');
                    window.location.href = 'index.php';
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
    <title>Đổi mật khẩu - Thư viện Mini</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 30px 20px;
        }

        .card {
            background: white;
            width: 100%;
            max-width: 480px;
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        h1 {
            color: #1e3a8a;
            font-size: 24px;
            margin-bottom: 8px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .user-info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #1e40af;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 14px;
            color: #334155;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: 0.2s;
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

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }

        .btn-cancel {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-cancel:hover {
            color: #1e293b;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="card">
        <h1>ĐỔI MẬT KHẨU</h1>
        <div class="subtitle">Cập nhật mật khẩu tài khoản của bạn</div>

        <div class="user-info-box">
            👤 Đang đăng nhập: <strong><?= htmlspecialchars($currentUser["ho_ten"]) ?></strong> (<?= htmlspecialchars($currentUser["ma_nguoi_dung"]) ?>)
        </div>

        <form method="POST" novalidate>
            <!-- MẬT KHẨU CŨ -->
            <div class="form-group">
                <label for="matKhauCu">Mật khẩu hiện tại</label>
                <input
                    type="password"
                    id="matKhauCu"
                    name="matKhauCu"
                    placeholder="Nhập mật khẩu hiện tại"
                    class="<?= isset($errors["matKhauCu"]) ? "input-error" : "" ?>"
                    autofocus>
                <?php if (isset($errors["matKhauCu"])): ?>
                    <div class="field-error"><?= htmlspecialchars($errors["matKhauCu"]) ?></div>
                <?php endif; ?>
            </div>

            <!-- MẬT KHẨU MỚI -->
            <div class="form-group">
                <label for="matKhauMoi">Mật khẩu mới</label>
                <input
                    type="password"
                    id="matKhauMoi"
                    name="matKhauMoi"
                    placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)"
                    class="<?= isset($errors["matKhauMoi"]) ? "input-error" : "" ?>">
                <?php if (isset($errors["matKhauMoi"])): ?>
                    <div class="field-error"><?= htmlspecialchars($errors["matKhauMoi"]) ?></div>
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
                    <div class="field-error"><?= htmlspecialchars($errors["xacNhanMatKhau"]) ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-submit">Lưu thay đổi</button>
            <a href="index.php" class="btn-cancel">← Quay lại trang chủ</a>
        </form>
    </div>

</body>

</html>
