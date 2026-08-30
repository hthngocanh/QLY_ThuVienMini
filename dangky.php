<?php

require_once __DIR__ . '/nguoiDung/functionsNguoiDung.php';

$errors = [];

$maNguoiDung = "";
$hoTen = "";
$email = "";
$sdt = "";
$khoaLop = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $maNguoiDung = trim($_POST["maNguoiDung"] ?? "");
    $hoTen = trim($_POST["hoTen"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $sdt = trim($_POST["sdt"] ?? "");
    $khoaLop = trim($_POST["khoaLop"] ?? "");

    $matKhau = $_POST["matKhau"] ?? "";
    $xacNhanMatKhau = $_POST["xacNhanMatKhau"] ?? "";

    // =========================
    // KIỂM TRA DỮ LIỆU
    // =========================

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
    }

    if ($xacNhanMatKhau === "") {
        $errors["xacNhanMatKhau"] = "Vui lòng xác nhận mật khẩu.";
    }

    if ($matKhau !== "" && $xacNhanMatKhau !== "" && $matKhau !== $xacNhanMatKhau) {
        $errors["xacNhanMatKhau"] = "Mật khẩu xác nhận không khớp.";
    }

    // =========================
    // KIỂM TRA MSV ĐÃ TỒN TẠI
    // =========================

    if (!isset($errors["maNguoiDung"])) {

        $nguoiDungTonTai = layNguoiDungTheoMa($maNguoiDung);

        if ($nguoiDungTonTai) {
            $errors["maNguoiDung"] = "Mã sinh viên đã được đăng ký.";
        }
    }

    // =========================
    // THÊM VÀO DATABASE
    // =========================

    if (empty($errors)) {

        $matKhauHash = password_hash(
            $matKhau,
            PASSWORD_DEFAULT
        );

        // Sinh viên đăng ký nên mặc định:
        $vaiTro = "Độc giả";
        $trangThai = "Hoạt động";

        themNguoiDung(
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
                window.location.href = 'dangnhap.php';
              </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng ký tài khoản</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        h1 {
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input:focus {
            border-color: #2563eb;
            outline: none;
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
        }

        button:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="card">

            <h1>ĐĂNG KÝ TÀI KHOẢN</h1>

            <form method="POST" novalidate>

                <!-- MSV -->
                <div class="form-group">

                    <label for="maNguoiDung">
                        Mã sinh viên
                    </label>

                    <input
                        type="text"
                        id="maNguoiDung"
                        name="maNguoiDung"
                        placeholder="VD: SV001"
                        value="<?= htmlspecialchars($maNguoiDung) ?>"
                        class="<?= isset($errors["maNguoiDung"]) ? "input-error" : "" ?>">

                    <?php if (isset($errors["maNguoiDung"])): ?>

                        <div class="field-error">
                            <?= htmlspecialchars($errors["maNguoiDung"]) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <!-- HỌ TÊN -->
                <div class="form-group">

                    <label for="hoTen">
                        Họ tên
                    </label>

                    <input
                        type="text"
                        id="hoTen"
                        name="hoTen"
                        placeholder="VD: Nguyễn Văn An"
                        value="<?= htmlspecialchars($hoTen) ?>"
                        class="<?= isset($errors["hoTen"]) ? "input-error" : "" ?>">

                    <?php if (isset($errors["hoTen"])): ?>

                        <div class="field-error">
                            <?= htmlspecialchars($errors["hoTen"]) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <!-- EMAIL -->
                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

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


                <!-- SĐT -->
                <div class="form-group">

                    <label for="sdt">
                        Số điện thoại
                    </label>

                    <input
                        type="text"
                        id="sdt"
                        name="sdt"
                        placeholder="VD: 0912345678"
                        value="<?= htmlspecialchars($sdt) ?>"
                        class="<?= isset($errors["sdt"]) ? "input-error" : "" ?>">

                    <?php if (isset($errors["sdt"])): ?>

                        <div class="field-error">
                            <?= htmlspecialchars($errors["sdt"]) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <!-- KHOA LỚP -->
                <div class="form-group">

                    <label for="khoaLop">
                        Khoa/Lớp
                    </label>

                    <input
                        type="text"
                        id="khoaLop"
                        name="khoaLop"
                        placeholder="VD: Công nghệ thông tin - K68"
                        value="<?= htmlspecialchars($khoaLop) ?>"
                        class="<?= isset($errors["khoaLop"]) ? "input-error" : "" ?>">

                    <?php if (isset($errors["khoaLop"])): ?>

                        <div class="field-error">
                            <?= htmlspecialchars($errors["khoaLop"]) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <!-- MẬT KHẨU -->
                <div class="form-group">

                    <label for="matKhau">
                        Mật khẩu
                    </label>

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


                <!-- XÁC NHẬN MẬT KHẨU -->
                <div class="form-group">

                    <label for="xacNhanMatKhau">
                        Xác nhận mật khẩu
                    </label>

                    <input
                        type="password"
                        id="xacNhanMatKhau"
                        name="xacNhanMatKhau"
                        placeholder="Nhập lại mật khẩu"
                        class="<?= isset($errors["xacNhanMatKhau"]) ? "input-error" : "" ?>">

                    <?php if (isset($errors["xacNhanMatKhau"])): ?>

                        <div class="field-error">
                            <?= htmlspecialchars($errors["xacNhanMatKhau"]) ?>
                        </div>

                    <?php endif; ?>

                </div>


                <button type="submit">
                    Đăng ký
                </button>

                <div class="auth-footer" style="margin-top: 20px; text-align: center; font-size: 14px; color: #64748b;">
                    Đã có tài khoản? <a href="dangnhap.php" style="color: #2563eb; text-decoration: none; font-weight: bold;">Đăng nhập ngay</a>
                </div>

            </form>

        </div>

    </div>

</body>

</html>