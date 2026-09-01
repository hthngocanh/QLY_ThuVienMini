<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - Thư viện Mini</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        body {
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 480px;
            margin: auto;
        }

        .card {
            padding: 38px 32px;
        }

        h1 {
            text-align: center;
            margin-bottom: 8px;
            font-size: var(--font-size-page-title);
            letter-spacing: -0.4px;
        }

        .sub-title {
            text-align: center;
            color: var(--text-secondary);
            font-size: var(--font-size-body);
            margin-bottom: 26px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        button {
            width: 100%;
            height: var(--button-height);
            margin-top: 8px;
        }

        @media (max-width: 480px) {
            body {
                padding: 24px 16px;
            }

            .card {
                padding: 28px 20px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="card">

            <h1>QUÊN MẬT KHẨU</h1>
            <div class="sub-title">Đặt lại mật khẩu cho tài khoản của bạn</div>

            <form method="POST" novalidate>

                <!-- TÀI KHOẢN -->
                <div class="form-group">
                    <label for="taiKhoan">
                        Mã sinh viên hoặc Email
                    </label>

                    <input
                        type="text"
                        id="taiKhoan"
                        name="taiKhoan"
                        placeholder="Nhập mã SV hoặc email đã đăng ký"
                        value="<?= htmlspecialchars($taiKhoan ?? '') ?>"
                        class="<?= isset($errors["taiKhoan"]) ? "input-error" : "" ?>"
                        autofocus>

                    <?php if (isset($errors["taiKhoan"])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors["taiKhoan"]) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- MẬT KHẨU MỚI -->
                <div class="form-group">
                    <label for="matKhauMoi">
                        Mật khẩu mới
                    </label>

                    <input
                        type="password"
                        id="matKhauMoi"
                        name="matKhauMoi"
                        placeholder="Nhập mật khẩu mới"
                        value="<?= htmlspecialchars($matKhauMoi ?? '') ?>"
                        class="<?= isset($errors["matKhauMoi"]) ? "input-error" : "" ?>">

                    <div class="password-hint">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <span>Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.</span>
                    </div>

                    <?php if (isset($errors["matKhauMoi"])): ?>
                        <div class="field-error">
                            <?= htmlspecialchars($errors["matKhauMoi"]) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- XÁC NHẬN MẬT KHẨU MỚI -->
                <div class="form-group">
                    <label for="xacNhanMatKhau">
                        Xác nhận mật khẩu mới
                    </label>

                    <input
                        type="password"
                        id="xacNhanMatKhau"
                        name="xacNhanMatKhau"
                        placeholder="Nhập lại mật khẩu mới"
                        value="<?= htmlspecialchars($xacNhanMatKhau ?? '') ?>"
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
                    Nhớ mật khẩu? <a href="index.php?controller=auth&action=login">Đăng nhập ngay</a>
                </div>

            </form>

        </div>

    </div>

</body>

</html>
