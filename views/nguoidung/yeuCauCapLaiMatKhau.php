<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu cấp lại mật khẩu - Thư viện Mini</title>

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

        /* STATUS BADGES */
        .status-badge {
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .status-badge.cho-duyet {
            background-color: #FEF3C7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }

        .status-badge.da-duyet {
            background-color: #DCFCE7;
            color: #15803D;
            border: 1px solid #BBF7D0;
        }

        .status-badge.da-tu-choi {
            background-color: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        /* BUTTONS */
        .btn-duyet {
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

        .btn-duyet:hover {
            background-color: #059669;
            color: #FFFFFF;
        }

        .btn-tuchoi {
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

        .btn-tuchoi:hover {
            background-color: #DC2626;
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

        .modal-box-confirm {
            background: var(--white);
            width: 100%;
            max-width: 460px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 1px solid var(--border);
            padding: 26px;
            text-align: center;
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
        $activeAction = 'yeuCauCapLaiMatKhau';
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
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <div class="user-profile-info">
                        <h2>YÊU CẦU CẤP LẠI MẬT KHẨU</h2>
                        <div class="user-profile-meta">
                            <span>Phân hệ: <strong>Phê duyệt mật khẩu cho Sinh viên & Thủ thư</strong></span>
                        </div>
                    </div>
                </div>

                <!-- MAIN CARD & TABLE -->
                <div class="user-form-card">
                    <div class="user-form-card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <span>Danh sách yêu cầu cấp lại mật khẩu</span>
                    </div>

                    <!-- BẢNG YÊU CẦU -->
                    <div class="table-responsive-wrapper">
                        <table class="bang-quanly">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Mã người dùng</th>
                                    <th style="width: 25%;">Họ tên</th>
                                    <th style="width: 25%;">Email</th>
                                    <th style="width: 15%;">Thời gian gửi</th>
                                    <th style="width: 10%;">Trạng thái</th>
                                    <th style="width: 10%; text-align: center;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($danhSachYeuCau)): ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 36px; color: var(--text-secondary);">
                                            Hiện tại không có yêu cầu cấp lại mật khẩu nào.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($danhSachYeuCau as $yc): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($yc['ma_nguoi_dung']) ?></strong></td>
                                            <td><?= htmlspecialchars($yc['ho_ten']) ?></td>
                                            <td><?= htmlspecialchars($yc['email']) ?></td>
                                            <td><?= htmlspecialchars($yc['created_at']) ?></td>
                                            <td>
                                                <?php
                                                $sc = 'cho-duyet';
                                                if ($yc['trang_thai'] === 'Đã duyệt') $sc = 'da-duyet';
                                                elseif ($yc['trang_thai'] === 'Đã từ chối') $sc = 'da-tu-choi';
                                                ?>
                                                <span class="status-badge <?= $sc ?>">
                                                    <?= htmlspecialchars($yc['trang_thai']) ?>
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <?php if ($yc['trang_thai'] === 'Chờ duyệt'): ?>
                                                    <div style="display:inline-flex; gap:6px;">
                                                        <button
                                                            type="button"
                                                            class="btn-duyet"
                                                            onclick="moPopupDuyet(<?= (int)$yc['id'] ?>, '<?= htmlspecialchars($yc['ho_ten']) ?>', '<?= htmlspecialchars($yc['ma_nguoi_dung']) ?>')"
                                                        >
                                                            Duyệt
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn-tuchoi"
                                                            onclick="moPopupTuChoi(<?= (int)$yc['id'] ?>, '<?= htmlspecialchars($yc['ho_ten']) ?>', '<?= htmlspecialchars($yc['ma_nguoi_dung']) ?>')"
                                                        >
                                                            Từ chối
                                                        </button>
                                                    </div>
                                                <?php else: ?>
                                                    <span style="color:var(--text-secondary); font-size:13px; font-style:italic;">Đã xử lý</span>
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

    <!-- MODAL CONFIRM DUYỆT -->
    <div class="modal-overlay" id="modalDuyet">
        <div class="modal-box-confirm">
            <h3 style="margin-top:0; color:#065F46;">Xác nhận duyệt yêu cầu</h3>
            <p id="msgConfirmDuyet" style="font-size:14.5px; color:var(--text-body); margin:16px 0 24px 0;"></p>

            <form method="POST" action="index.php?controller=user&action=yeuCauCapLaiMatKhau">
                <input type="hidden" name="hanhDong" value="duyet">
                <input type="hidden" name="id" id="inputIdDuyet">
                <div style="display:flex; justify-content:center; gap:12px;">
                    <button type="button" class="btn-tuchoi" onclick="dongPopupConfirm()" style="background:#FFFFFF; color:var(--text-body); border-color:var(--border);">Hủy</button>
                    <button type="submit" class="btn-duyet" style="padding:10px 22px;">Duyệt</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CONFIRM TỪ CHỐI -->
    <div class="modal-overlay" id="modalTuChoi">
        <div class="modal-box-confirm">
            <h3 style="margin-top:0; color:#991B1B;">Xác nhận từ chối yêu cầu</h3>
            <p id="msgConfirmTuChoi" style="font-size:14.5px; color:var(--text-body); margin:16px 0 24px 0;"></p>

            <form method="POST" action="index.php?controller=user&action=yeuCauCapLaiMatKhau">
                <input type="hidden" name="hanhDong" value="tuchoi">
                <input type="hidden" name="id" id="inputIdTuChoi">
                <div style="display:flex; justify-content:center; gap:12px;">
                    <button type="button" class="btn-duyet" onclick="dongPopupConfirm()" style="background:#FFFFFF; color:var(--text-body); border-color:var(--border);">Hủy</button>
                    <button type="submit" class="btn-tuchoi" style="padding:10px 22px;">Từ chối</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function moPopupDuyet(id, hoTen, ma) {
            document.getElementById('inputIdDuyet').value = id;
            document.getElementById('msgConfirmDuyet').innerText = 'Bạn có chắc chắn muốn duyệt yêu cầu cấp lại mật khẩu cho ' + hoTen + ' (' + ma + ') không?';
            document.getElementById('modalDuyet').classList.add('active');
        }

        function moPopupTuChoi(id, hoTen, ma) {
            document.getElementById('inputIdTuChoi').value = id;
            document.getElementById('msgConfirmTuChoi').innerText = 'Bạn có chắc chắn muốn từ chối yêu cầu này không?';
            document.getElementById('modalTuChoi').classList.add('active');
        }

        function dongPopupConfirm() {
            document.getElementById('modalDuyet').classList.remove('active');
            document.getElementById('modalTuChoi').classList.remove('active');
        }
    </script>
</body>

</html>
