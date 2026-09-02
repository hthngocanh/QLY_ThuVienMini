<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhân sự - Thư viện Mini</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        .user-page {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--text-body);
        }

        /* HEADER CARD */
        .user-profile-card {
            background: var(--white);
            border-radius: 16px;
            padding: 24px 30px;
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
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1e3a8a);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
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
            margin: 0 0 6px 0;
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

        /* MAIN CARD */
        .user-form-card {
            background: var(--white);
            border-radius: 16px;
            padding: 28px 32px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 30px;
        }

        .user-form-card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 14px;
            border-bottom: 1px solid #F1F5F9;
        }

        /* TOOLBAR */
        .toolbar-nhansu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .form-tim-kiem-flex {
            display: flex;
            gap: 12px;
            align-items: center;
            flex: 1;
            max-width: 600px;
        }

        .search-input-wrapper {
            position: relative;
            flex: 1;
        }

        .search-input-wrapper svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
        }

        .search-input-wrapper input[type="text"] {
            width: 100%;
            min-height: var(--input-height);
            padding: 10px 16px 10px 42px;
            border: 1px solid var(--border);
            border-radius: var(--radius-input);
            font-size: 14px;
            font-family: inherit;
            color: var(--text-body);
            outline: none;
            box-sizing: border-box;
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        }

        .search-input-wrapper input[type="text"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .btn-tim-kiem {
            min-height: var(--button-height);
            padding: 0 20px;
            border: none;
            border-radius: var(--radius-button);
            background-color: var(--primary);
            color: var(--white);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: background-color var(--transition-fast);
            white-space: nowrap;
        }

        .btn-tim-kiem:hover {
            background-color: var(--primary-dark);
        }

        .btn-them-nhansu {
            min-height: var(--button-height);
            padding: 0 20px;
            border: none;
            border-radius: var(--radius-button);
            background-color: #16A34A;
            color: #FFFFFF;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color var(--transition-fast);
            white-space: nowrap;
        }

        .btn-them-nhansu:hover {
            background-color: #15803D;
        }

        .btn-lam-moi {
            min-height: var(--button-height);
            padding: 0 16px;
            border: 1px solid var(--border);
            border-radius: var(--radius-button);
            background-color: var(--white);
            color: var(--text-body);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all var(--transition-fast);
        }

        .btn-lam-moi:hover {
            background-color: var(--bg-page);
            border-color: var(--text-secondary);
        }

        /* TABLE */
        .table-responsive-wrapper {
            width: 100%;
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .bang-quanly {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        .bang-quanly th {
            background-color: #F8FAFC;
            color: var(--text-primary);
            font-weight: 700;
            padding: 13px 18px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .bang-quanly td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            color: var(--text-body);
            vertical-align: middle;
        }

        .bang-quanly tr:last-child td {
            border-bottom: none;
        }

        .bang-quanly tbody tr:hover {
            background-color: var(--primary-light);
        }

        /* TAGS */
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

        .user-role-tag {
            display: inline-block;
            font-size: 12.5px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            background-color: #EFF6FF;
            color: #1D4ED8;
            border: 1px solid #DBEAFE;
        }

        /* BUTTONS */
        .btn-action-lock {
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid #FECACA;
            background-color: #FEF2F2;
            color: #DC2626;
            transition: all var(--transition-fast);
        }

        .btn-action-lock:hover {
            background-color: #DC2626;
            color: #FFFFFF;
        }

        .btn-action-unlock {
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid #BBF7D0;
            background-color: #ECFDF5;
            color: #059669;
            transition: all var(--transition-fast);
        }

        .btn-action-unlock:hover {
            background-color: #059669;
            color: #FFFFFF;
        }

        /* MODALS */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(3px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .modal-overlay.active {
            display: flex;
            animation: fadeInModal 0.2s ease-out;
        }

        @keyframes fadeInModal {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-box-form {
            background: var(--white);
            width: 100%;
            max-width: 540px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .modal-box-confirm {
            background: var(--white);
            width: 100%;
            max-width: 440px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 1px solid var(--border);
            padding: 24px;
            text-align: center;
        }

        .modal-header {
            padding: 18px 24px;
            background-color: var(--bg-page);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-body {
            padding: 24px;
        }

        .form-group-modal {
            margin-bottom: 16px;
        }

        .form-group-modal label {
            display: block;
            margin-bottom: 6px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-body);
        }

        .form-group-modal input {
            width: 100%;
            min-height: 40px;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-input);
            font-size: 14px;
            font-family: inherit;
            box-sizing: border-box;
            outline: none;
        }

        .form-group-modal input:focus {
            border-color: var(--primary);
        }

        .form-group-modal input.input-error {
            border-color: #DC2626;
            background-color: #FEF2F2;
        }

        .form-group-modal input.input-error:focus {
            border-color: #DC2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
        }

        .field-error {
            color: #DC2626;
            font-size: 12.5px;
            font-weight: 500;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .user-alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 22px;
            font-size: 14px;
            font-weight: 500;
        }

        .user-alert.success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .user-alert.error { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
    </style>
</head>

<body>
    <div class="layout">
        <!-- Sidebar -->
        <?php
        $activePage = 'nguoidung';
        $activeAction = 'quanLyNhanSu';
        include __DIR__ . '/../../layout/sidebar.php';
        ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="user-page">

                <!-- THÔNG BÁO -->
                <?php if (!empty($thongBao)): ?>
                    <div class="user-alert <?= htmlspecialchars($loaiThongBao ?? 'success') ?>">
                        <?= htmlspecialchars($thongBao) ?>
                    </div>
                <?php endif; ?>

                <!-- HEADER CARD -->
                <div class="user-profile-card">
                    <div class="user-profile-avatar">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <polyline points="17 11 19 13 23 9"></polyline>
                        </svg>
                    </div>
                    <div class="user-profile-info">
                        <h2>QUẢN LÝ NHÂN SỰ</h2>
                        <div class="user-profile-meta">
                            <span>Phân hệ: <strong>Quản lý tài khoản Thủ thư</strong></span>
                        </div>
                    </div>
                </div>

                <!-- MAIN FORM & TABLE CARD -->
                <div class="user-form-card">
                    <div class="user-form-card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Danh sách nhân sự (Thủ thư)</span>
                    </div>

                    <!-- TOOLBAR -->
                    <div class="toolbar-nhansu">
                        <form method="GET" action="index.php" class="form-tim-kiem-flex">
                            <input type="hidden" name="controller" value="user">
                            <input type="hidden" name="action" value="quanLyNhanSu">

                            <div class="search-input-wrapper">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input
                                    type="text"
                                    name="tuKhoa"
                                    value="<?= htmlspecialchars($tuKhoa ?? '') ?>"
                                    placeholder="Nhập mã nhân sự hoặc họ tên..."
                                    autofocus
                                >
                            </div>

                            <button type="submit" class="btn-tim-kiem">
                                <span>Tìm kiếm</span>
                            </button>

                            <?php if (!empty($tuKhoa)): ?>
                                <a href="index.php?controller=user&action=quanLyNhanSu" class="btn-lam-moi" title="Xem tất cả nhân sự">Làm mới</a>
                            <?php endif; ?>
                        </form>

                        <button type="button" class="btn-them-nhansu" onclick="moModalThem()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>+ Thêm nhân sự</span>
                        </button>
                    </div>

                    <!-- BẢNG NHÂN SỰ (6 CỘT CHUẨN) -->
                    <div class="table-responsive-wrapper">
                        <table class="bang-quanly">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Mã nhân sự</th>
                                    <th style="width: 25%;">Họ tên</th>
                                    <th style="width: 25%;">Email</th>
                                    <th style="width: 15%;">Vai trò</th>
                                    <th style="width: 12%;">Trạng thái</th>
                                    <th style="width: 8%; text-align: center;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($danhSachNhanSu)): ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 36px; color: var(--text-secondary);">
                                            Không tìm thấy nhân sự nào phù hợp.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($danhSachNhanSu as $ns): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($ns['ma_nguoi_dung']) ?></strong></td>
                                            <td><?= htmlspecialchars($ns['ho_ten']) ?></td>
                                            <td><?= htmlspecialchars($ns['email']) ?></td>
                                            <td>
                                                <span class="user-role-tag"><?= htmlspecialchars($ns['vai_tro']) ?></span>
                                            </td>
                                            <td>
                                                <span class="user-status-tag <?= ($ns['trang_thai'] ?? '') === 'Hoạt động' ? 'active' : 'locked' ?>">
                                                    <?= htmlspecialchars($ns['trang_thai'] ?? 'Hoạt động') ?>
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <?php if (($ns['trang_thai'] ?? '') === 'Hoạt động'): ?>
                                                    <button
                                                        type="button"
                                                        class="btn-action-lock"
                                                        onclick="moPopupKhoa('<?= htmlspecialchars($ns['ma_nguoi_dung']) ?>', '<?= htmlspecialchars($ns['ho_ten']) ?>')"
                                                    >
                                                        Khóa
                                                    </button>
                                                <?php else: ?>
                                                    <button
                                                        type="button"
                                                        class="btn-action-unlock"
                                                        onclick="moPopupMoKhoa('<?= htmlspecialchars($ns['ma_nguoi_dung']) ?>', '<?= htmlspecialchars($ns['ho_ten']) ?>')"
                                                    >
                                                        Mở khóa
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL THÊM NHÂN SỰ -->
    <div class="modal-overlay <?= !empty($moModalThem) ? 'active' : '' ?>" id="modalThem">
        <div class="modal-box-form">
            <div class="modal-header">
                <h3 style="margin:0; font-size:17px; color:var(--text-primary);">Thêm nhân sự mới (Thủ thư)</h3>
                <button type="button" onclick="dongModalThem()" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>
            <form method="POST" action="index.php?controller=user&action=quanLyNhanSu" class="modal-body" novalidate>
                <input type="hidden" name="hanhDong" value="them">

                <div class="form-group-modal">
                    <label for="ma_nguoi_dung">Mã nhân sự *</label>
                    <input 
                        type="text" 
                        id="ma_nguoi_dung" 
                        name="ma_nguoi_dung" 
                        placeholder="VD: TT002, TT003..." 
                        value="<?= htmlspecialchars($formDataThem['ma_nguoi_dung'] ?? '') ?>"
                        class="<?= !empty($errorsThem['ma_nguoi_dung']) ? 'input-error' : '' ?>"
                        required
                    >
                    <?php if (!empty($errorsThem['ma_nguoi_dung'])): ?>
                        <div class="field-error">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span><?= htmlspecialchars($errorsThem['ma_nguoi_dung']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group-modal">
                    <label for="ho_ten">Họ và tên *</label>
                    <input 
                        type="text" 
                        id="ho_ten" 
                        name="ho_ten" 
                        placeholder="Nhập họ và tên đầy đủ" 
                        value="<?= htmlspecialchars($formDataThem['ho_ten'] ?? '') ?>"
                        class="<?= !empty($errorsThem['ho_ten']) ? 'input-error' : '' ?>"
                        required
                    >
                    <?php if (!empty($errorsThem['ho_ten'])): ?>
                        <div class="field-error">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span><?= htmlspecialchars($errorsThem['ho_ten']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group-modal">
                    <label for="email">Email *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="VD: thuthu@gmail.com" 
                        value="<?= htmlspecialchars($formDataThem['email'] ?? '') ?>"
                        class="<?= !empty($errorsThem['email']) ? 'input-error' : '' ?>"
                        required
                    >
                    <?php if (!empty($errorsThem['email'])): ?>
                        <div class="field-error">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span><?= htmlspecialchars($errorsThem['email']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 8px; padding: 12px 14px; margin-top: 14px; font-size: 13px; color: #166534; line-height: 1.5;">
                    <div style="font-weight: 600; margin-bottom: 2px;">🔑 Mật khẩu khởi tạo mặc định:</div>
                    <div><code>Thuvien12345!</code> (Nhân sự có thể đổi mật khẩu sau khi đăng nhập)</div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                    <button type="button" class="btn-lam-moi" onclick="dongModalThem()">Hủy</button>
                    <button type="submit" class="btn-them-nhansu" style="padding:10px 22px;">Lưu nhân sự</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CONFIRM KHÓA TÀI KHOẢN -->
    <div class="modal-overlay" id="modalKhoa">
        <div class="modal-box-confirm">
            <h3 style="margin-top:0; color:#991B1B;">Xác nhận khóa tài khoản</h3>
            <p id="msgConfirmKhoa" style="font-size:14.5px; color:var(--text-body); margin:16px 0 24px 0;"></p>

            <form method="POST" action="index.php?controller=user&action=quanLyNhanSu">
                <input type="hidden" name="hanhDong" value="khoa">
                <input type="hidden" name="ma_nguoi_dung" id="inputMaKhoa">
                <div style="display:flex; justify-content:center; gap:12px;">
                    <button type="button" class="btn-lam-moi" onclick="dongPopupConfirm()">Hủy</button>
                    <button type="submit" class="btn-action-lock" style="padding:10px 22px;">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CONFIRM MỞ KHÓA TÀI KHOẢN -->
    <div class="modal-overlay" id="modalMoKhoa">
        <div class="modal-box-confirm">
            <h3 style="margin-top:0; color:#065F46;">Xác nhận mở khóa tài khoản</h3>
            <p id="msgConfirmMoKhoa" style="font-size:14.5px; color:var(--text-body); margin:16px 0 24px 0;"></p>

            <form method="POST" action="index.php?controller=user&action=quanLyNhanSu">
                <input type="hidden" name="hanhDong" value="mokhoa">
                <input type="hidden" name="ma_nguoi_dung" id="inputMaMoKhoa">
                <div style="display:flex; justify-content:center; gap:12px;">
                    <button type="button" class="btn-lam-moi" onclick="dongPopupConfirm()">Hủy</button>
                    <button type="submit" class="btn-action-unlock" style="padding:10px 22px;">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function moModalThem() {
            document.getElementById('modalThem').classList.add('active');
        }

        function dongModalThem() {
            document.getElementById('modalThem').classList.remove('active');
        }

        function moPopupKhoa(ma, hoTen) {
            document.getElementById('inputMaKhoa').value = ma;
            document.getElementById('msgConfirmKhoa').innerText = 'Bạn có chắc chắn muốn khóa tài khoản nhân sự ' + hoTen + ' (' + ma + ') không?';
            document.getElementById('modalKhoa').classList.add('active');
        }

        function moPopupMoKhoa(ma, hoTen) {
            document.getElementById('inputMaMoKhoa').value = ma;
            document.getElementById('msgConfirmMoKhoa').innerText = 'Bạn có chắc chắn muốn mở khóa tài khoản nhân sự ' + hoTen + ' (' + ma + ') không?';
            document.getElementById('modalMoKhoa').classList.add('active');
        }

        function dongPopupConfirm() {
            document.getElementById('modalKhoa').classList.remove('active');
            document.getElementById('modalMoKhoa').classList.remove('active');
        }
    </script>
</body>

</html>
