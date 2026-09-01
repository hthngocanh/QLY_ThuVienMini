<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng ký - Thư viện Mini</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        body {
            padding: 24px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 510px;
            margin: auto;
        }

        .card {
            padding: 26px 30px;
        }

        h1 {
            text-align: center;
            margin-bottom: 4px;
            font-size: var(--font-size-section-title);
            letter-spacing: -0.4px;
        }

        .sub-title {
            text-align: center;
            color: var(--text-secondary);
            font-size: var(--font-size-label);
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 11px;
        }

        button {
            width: 100%;
            height: var(--button-height);
            margin-top: 6px;
        }

        .auth-footer {
            margin-top: 14px;
        }

        @media (max-width: 480px) {
            body {
                padding: 16px 12px;
            }

            .card {
                padding: 22px 18px;
            }

            h1 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="card">

            <h1>ĐĂNG KÝ TÀI KHOẢN</h1>
            <div class="sub-title">Hệ thống Quản lý Thư viện Mini</div>

            <?php if (isset($errors["chung"])): ?>
                <div class="alert-error">
                    <?= htmlspecialchars($errors["chung"]) ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

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
                        value="<?= htmlspecialchars($maNguoiDung ?? '') ?>"
                        class="<?= isset($errors["maNguoiDung"]) ? "input-error" : "" ?>"
                        autofocus>

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
                        value="<?= htmlspecialchars($hoTen ?? '') ?>"
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
                        value="<?= htmlspecialchars($email ?? '') ?>"
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
                        value="<?= htmlspecialchars($sdt ?? '') ?>"
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
                        value="<?= htmlspecialchars($khoaLop ?? '') ?>"
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
                        value="<?= htmlspecialchars($matKhau ?? '') ?>"
                        class="<?= isset($errors["matKhau"]) ? "input-error" : "" ?>">

                    <div class="password-hint">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <span>Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.</span>
                    </div>

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
                        value="<?= htmlspecialchars($xacNhanMatKhau ?? '') ?>"
                        class="<?= isset($errors["xacNhanMatKhau"]) ? "input-error" : "" ?>">

                    <?php if (isset($errors["xacNhanMatKhau"])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors["xacNhanMatKhau"]) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit">
                    Đăng ký tài khoản
                </button>

                <div class="auth-footer">
                    Đã có tài khoản? <a href="index.php?controller=auth&action=login">Đăng nhập ngay</a>
                </div>

            </form>

        </div>

    </div>

</body>

</html>
