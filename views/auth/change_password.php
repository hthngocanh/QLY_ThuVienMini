<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu - Thư viện Mini</title>

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

        .card {
            width: 100%;
            max-width: 480px;
            padding: 38px 32px;
        }

        h1 {
            font-size: var(--font-size-section-title);
            letter-spacing: -0.4px;
            margin-bottom: 8px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: var(--text-secondary);
            font-size: var(--font-size-body);
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .btn-submit {
            width: 100%;
            height: var(--button-height);
            margin-top: 10px;
        }

        .btn-cancel {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: var(--font-size-label);
            font-weight: var(--font-weight-medium);
        }

        .btn-cancel:hover {
            color: var(--primary);
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="card">
        <h1>ĐỔI MẬT KHẨU</h1>
        <div class="subtitle">Cập nhật mật khẩu tài khoản của bạn</div>

        <?php if (isset($errors["chung"])): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; border: 1px solid #fecaca;">
                <?= htmlspecialchars($errors["chung"]) ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <!-- MẬT KHẨU CŨ -->
            <div class="form-group">
                <label for="matKhauCu">Mật khẩu hiện tại</label>
                <input
                    type="password"
                    id="matKhauCu"
                    name="matKhauCu"
                    placeholder="Nhập mật khẩu hiện tại"
                    value="<?= htmlspecialchars($matKhauCu ?? '') ?>"
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
                    value="<?= htmlspecialchars($xacNhanMatKhau ?? '') ?>"
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
