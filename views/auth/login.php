<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Thư viện Mini</title>

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
            max-width: 460px;
            margin: auto;
        }

        .card {
            padding: 38px 32px;
        }

        h1 {
            text-align: center;
            margin-bottom: 8px;
            font-size: var(--font-size-section-title);
            letter-spacing: -0.4px;
        }

        .sub-title {
            text-align: center;
            color: var(--text-secondary);
            font-size: var(--font-size-label);
            margin-bottom: 26px;
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

        .forgot-link {
            font-size: var(--font-size-caption);
            color: var(--primary);
            text-decoration: none;
            font-weight: var(--font-weight-medium);
            margin-bottom: 6px;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        button {
            width: 100%;
            height: var(--button-height);
            margin-top: 6px;
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
                        value="<?= htmlspecialchars($taiKhoan ?? '') ?>"
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
                        <a href="index.php?controller=auth&action=forgot_password" class="forgot-link">Quên mật khẩu?</a>
                    </div>

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

                <button type="submit">
                    Đăng nhập
                </button>

                <div class="auth-footer">
                    Chưa có tài khoản? <a href="index.php?controller=auth&action=register">Đăng ký ngay</a>
                </div>

            </form>

        </div>

    </div>

</body>

</html>
