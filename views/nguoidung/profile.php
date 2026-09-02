<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân - Thư viện Mini</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        /* Scoped CSS cho trang Thông tin cá nhân */
        .user-page {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--text-body);
        }

        .user-subtitle {
            font-size: 14.5px;
            color: var(--text-secondary);
            margin-bottom: 22px;
        }

        .user-alert {
            padding: 13px 18px;
            border-radius: 8px;
            margin-bottom: 22px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        .user-alert.success {
            background-color: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .user-alert.error {
            background-color: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        /* 1. KHUNG THÔNG TIN TỔNG QUAN */
        .user-profile-card {
            background: var(--white);
            border-radius: 16px;
            padding: 26px 30px;
            margin-bottom: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            gap: 22px;
            width: 100%;
            box-sizing: border-box;
        }

        .user-profile-avatar {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1e3a8a);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .user-profile-info {
            flex: 1;
        }

        .user-profile-info h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0 0 8px 0;
            line-height: 1.2;
        }

        .user-profile-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 16px 28px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .user-profile-meta span strong {
            color: var(--text-primary);
            font-weight: 600;
        }

        .user-status-tag {
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .user-status-tag.active {
            background-color: #DCFCE7;
            color: #15803D;
            border: 1px solid #BBF7D0;
        }

        .user-status-tag.locked {
            background-color: #FEE2E2;
            color: #B91C1C;
            border: 1px solid #FECACA;
        }

        /* 2. KHUNG CẬP NHẬT THÔNG TIN CÁ NHÂN */
        .user-form-card {
            background: var(--white);
            border-radius: 16px;
            padding: 30px 32px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            width: 100%;
            box-sizing: border-box;
        }

        .user-form-card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 22px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 14px;
            border-bottom: 1px solid #F1F5F9;
        }

        .user-form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px 30px;
            margin-bottom: 24px;
        }

        .user-form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .user-form-group label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-body);
        }

        .user-form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14.5px;
            color: var(--text-body);
            background-color: var(--white);
            box-sizing: border-box;
            font-family: inherit;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .user-form-group input:focus {
            border-color: var(--border-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .user-form-group input[readonly],
        .user-form-group input:disabled {
            background-color: var(--bg-page);
            color: var(--text-secondary);
            border-color: var(--border);
            cursor: not-allowed;
        }

        .user-error-text {
            color: var(--danger);
            font-size: 13px;
            margin-top: 3px;
        }

        .user-input-error {
            border-color: var(--danger) !important;
        }

        .user-form-actions {
            display: flex;
            justify-content: flex-end;
            padding-top: 8px;
        }

        .user-btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background-color: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background-color 0.15s ease;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
        }

        .user-btn-save:hover {
            background-color: var(--primary-dark);
        }

        .user-btn-save:active {
            transform: translateY(1px);
        }

        @media (max-width: 768px) {
            .user-profile-card {
                flex-direction: column;
                text-align: center;
            }

            .user-profile-meta {
                justify-content: center;
            }

            .user-form-grid {
                grid-template-columns: 1fr;
            }

            .user-form-actions {
                justify-content: stretch;
            }

            .user-btn-save {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <div class="layout">
        <!-- Nhúng Sidebar dùng chung -->
        <?php
        $activePage = 'nguoidung';
        $activeAction = 'profile';
        require_once __DIR__ . '/../../layout/sidebar.php';
        ?>

        <!-- Vùng nội dung chính -->
        <main class="main-content">
            <div class="user-page">

                <!-- Thông báo trạng thái -->
                <?php if (!empty($thongBao)): ?>
                    <div class="user-alert <?= htmlspecialchars($loaiThongBao ?? '') ?>">
                        <?php if (($loaiThongBao ?? '') === "success"): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        <?php else: ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                        <?php endif; ?>
                        <span><?= htmlspecialchars($thongBao) ?></span>
                    </div>
                <?php endif; ?>

                <?php 
                $isDocGia = (($currentUser["vai_tro"] ?? '') === 'Độc giả'); 
                ?>

                <!-- KHUNG 1: THÔNG TIN TỔNG QUAN -->
                <div class="user-profile-card">
                    <div class="user-profile-avatar">
                        <?= mb_strtoupper(mb_substr($currentUser["ho_ten"] ?? "S", 0, 1, "UTF-8"), "UTF-8") ?>
                    </div>
                    <div class="user-profile-info">
                        <h2><?= htmlspecialchars($currentUser["ho_ten"] ?? '') ?></h2>
                        <div class="user-profile-meta">
                            <span><?= $isDocGia ? 'Mã sinh viên' : 'Mã người dùng' ?>: <strong><?= htmlspecialchars($currentUser["ma_nguoi_dung"] ?? '') ?></strong></span>
                            <span>Vai trò: <strong><?= htmlspecialchars($currentUser["vai_tro"] ?? '') ?></strong></span>
                            <?php if ($isDocGia): ?>
                                <span>Khoa/Lớp: <strong><?= htmlspecialchars($currentUser["khoa_lop"] ?: "Chưa cập nhật") ?></strong></span>
                            <?php endif; ?>
                            <span>Trạng thái:
                                <span class="user-status-tag <?= ($currentUser["trang_thai"] ?? '') === 'Hoạt động' ? 'active' : 'locked' ?>">
                                    <?= htmlspecialchars($currentUser["trang_thai"] ?? '') ?>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- KHUNG 2: CẬP NHẬT THÔNG TIN CÁ NHÂN -->
                <div class="user-form-card">
                    <div class="user-form-card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Thông tin cá nhân</span>
                    </div>

                    <form method="POST" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <div class="user-form-grid">
                            <!-- Hàng 1 -->
                            <div class="user-form-group">
                                <label for="maNguoiDung"><?= $isDocGia ? 'Mã sinh viên' : 'Mã người dùng' ?></label>
                                <input
                                    type="text"
                                    id="maNguoiDung"
                                    value="<?= htmlspecialchars($currentUser["ma_nguoi_dung"] ?? '') ?>"
                                    readonly
                                    title="Mã tài khoản không thể thay đổi">
                            </div>

                            <div class="user-form-group">
                                <label for="hoTen">Họ và tên *</label>
                                <input
                                    type="text"
                                    id="hoTen"
                                    name="hoTen"
                                    value="<?= htmlspecialchars($currentUser["ho_ten"] ?? '') ?>"
                                    placeholder="Nhập họ và tên"
                                    class="<?= isset($errors["hoTen"]) ? "user-input-error" : "" ?>"
                                    required>
                                <?php if (isset($errors["hoTen"])): ?>
                                    <span class="user-error-text"><?= htmlspecialchars($errors["hoTen"]) ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Hàng 2 -->
                            <div class="user-form-group">
                                <label for="email">Email *</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="<?= htmlspecialchars($currentUser["email"] ?? '') ?>"
                                    placeholder="VD: an@gmail.com"
                                    class="<?= isset($errors["email"]) ? "user-input-error" : "" ?>"
                                    required>
                                <?php if (isset($errors["email"])): ?>
                                    <span class="user-error-text"><?= htmlspecialchars($errors["email"]) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="user-form-group">
                                <label for="sdt">Số điện thoại</label>
                                <input
                                    type="text"
                                    id="sdt"
                                    name="sdt"
                                    value="<?= htmlspecialchars($currentUser["sdt"] ?? "") ?>"
                                    placeholder="VD: 0912345678">
                            </div>

                            <!-- Hàng 3 -->
                            <?php if ($isDocGia): ?>
                                <div class="user-form-group">
                                    <label for="khoaLop">Khoa / Lớp</label>
                                    <input
                                        type="text"
                                        id="khoaLop"
                                        name="khoaLop"
                                        value="<?= htmlspecialchars($currentUser["khoa_lop"] ?? "") ?>"
                                        placeholder="VD: Công nghệ thông tin - K68">
                                </div>
                            <?php endif; ?>

                            <div class="user-form-group">
                                <label for="vaiTro">Vai trò</label>
                                <input
                                    type="text"
                                    id="vaiTro"
                                    value="<?= htmlspecialchars($currentUser["vai_tro"] ?? '') ?>"
                                    readonly
                                    title="Vai trò không thể thay đổi">
                            </div>
                        </div>

                        <div class="user-form-actions">
                            <button type="submit" class="user-btn-save">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                <span>Lưu thay đổi</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

</body>

</html>
