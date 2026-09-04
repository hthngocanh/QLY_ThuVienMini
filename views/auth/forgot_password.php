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
            max-width: 520px;
            margin: auto;
        }

        .card {
            padding: 36px 32px;
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
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 16px;
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

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            padding: 12px 14px;
            border-radius: 6px;
            margin-bottom: 18px;
            font-size: 13.5px;
            border: 1px solid #FECACA;
            line-height: 1.4;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">
            <h1>YÊU CẦU CẤP LẠI MẬT KHẨU</h1>
            <div class="sub-title">Nhập thông tin xác thực để gửi yêu cầu tới Quản trị viên duyệt</div>

            <?php if (isset($errors["chung"])): ?>
                <div class="alert-error">
                    <?= htmlspecialchars($errors["chung"]) ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <!-- MÃ NGƯỜI DÙNG / MÃ SINH VIÊN -->
                <div class="form-group">
                    <label for="maNguoiDung">Mã sinh viên / Mã người dùng *</label>
                    <input
                        type="text"
                        id="maNguoiDung"
                        name="maNguoiDung"
                        placeholder="VD: SV001, TT001..."
                        value="<?= htmlspecialchars($maNguoiDung ?? '') ?>"
                        class="<?= isset($errors["maNguoiDung"]) ? "input-error" : "" ?>"
                        autofocus
                        required>
                    <?php if (isset($errors["maNguoiDung"])): ?>
                        <div class="field-error"><?= htmlspecialchars($errors["maNguoiDung"]) ?></div>
                    <?php endif; ?>
                </div>

                <!-- HỌ VÀ TÊN -->
                <div class="form-group">
                    <label for="hoTen">Họ và tên *</label>
                    <input
                        type="text"
                        id="hoTen"
                        name="hoTen"
                        placeholder="Nhập họ và tên đầy đủ đã đăng ký"
                        value="<?= htmlspecialchars($hoTen ?? '') ?>"
                        class="<?= isset($errors["hoTen"]) ? "input-error" : "" ?>"
                        required>
                    <?php if (isset($errors["hoTen"])): ?>
                        <div class="field-error"><?= htmlspecialchars($errors["hoTen"]) ?></div>
                    <?php endif; ?>
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="VD: sinhvien@gmail.com"
                        value="<?= htmlspecialchars($email ?? '') ?>"
                        class="<?= isset($errors["email"]) ? "input-error" : "" ?>"
                        required>
                    <?php if (isset($errors["email"])): ?>
                        <div class="field-error"><?= htmlspecialchars($errors["email"]) ?></div>
                    <?php endif; ?>
                </div>

                <!-- MẬT KHẨU MỚI -->
                <div class="form-group">
                    <label for="matKhauMoi">Mật khẩu mới mong muốn *</label>
                    <input
                        type="password"
                        id="matKhauMoi"
                        name="matKhauMoi"
                        placeholder="Nhập mật khẩu mới"
                        value="<?= htmlspecialchars($matKhauMoi ?? '') ?>"
                        class="<?= isset($errors["matKhauMoi"]) ? "input-error" : "" ?>"
                        required>
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
                    <label for="xacNhanMatKhau">Xác nhận mật khẩu mới *</label>
                    <input
                        type="password"
                        id="xacNhanMatKhau"
                        name="xacNhanMatKhau"
                        placeholder="Nhập lại mật khẩu mới"
                        value="<?= htmlspecialchars($xacNhanMatKhau ?? '') ?>"
                        class="<?= isset($errors["xacNhanMatKhau"]) ? "input-error" : "" ?>"
                        required>
                    <?php if (isset($errors["xacNhanMatKhau"])): ?>
                        <div class="field-error"><?= htmlspecialchars($errors["xacNhanMatKhau"]) ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn-submit">Gửi yêu cầu</button>
                <a href="index.php?controller=auth&action=login" class="btn-cancel">← Quay lại Đăng nhập</a>
            </form>
        </div>
    </div>

</body>

</html>
