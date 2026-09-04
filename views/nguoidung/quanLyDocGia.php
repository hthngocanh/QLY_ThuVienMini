<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý độc giả - Thư viện Mini</title>

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

        /* SEARCH SECTION */
        .search-container-inner {
            margin-bottom: 22px;
        }

        .form-tim-kiem-flex {
            display: flex;
            gap: 12px;
            align-items: center;
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
            padding: 0 22px;
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
            transition: background-color var(--transition-fast), transform 0.1s ease;
            white-space: nowrap;
        }

        .btn-tim-kiem:hover {
            background-color: var(--primary-dark);
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

        /* STATUS TAGS */
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

        .btn-vi-pham-co {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 6px;
            background-color: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
            font-weight: 700;
            font-size: 12.5px;
            cursor: pointer;
            text-decoration: underline;
        }

        .btn-vi-pham-co:hover {
            background-color: #DC2626;
            color: #FFFFFF;
            text-decoration: none;
        }

        .tag-vi-pham-khong {
            color: var(--text-secondary);
            font-weight: 500;
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
            max-width: 440px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 1px solid var(--border);
            padding: 24px;
            text-align: center;
        }

        .modal-box-history {
            background: var(--white);
            width: 100%;
            max-width: 780px;
            max-height: 90vh;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border);
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
            padding: 22px;
            overflow-y: auto;
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
        $activeAction = 'quanLyDocGia';
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
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="user-profile-info">
                        <h2>QUẢN LÝ ĐỘC GIẢ</h2>
                        <div class="user-profile-meta">
                            <span>Phân hệ: <strong>Quản lý tài khoản Sinh viên / Độc giả</strong></span>
                        </div>
                    </div>
                </div>

                <!-- MAIN FORM & TABLE CARD -->
                <div class="user-form-card">
                    <div class="user-form-card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <span>Danh sách độc giả</span>
                    </div>

                    <!-- Ô TÌM KIẾM -->
                    <div class="search-container-inner">
                        <form method="GET" action="index.php" class="form-tim-kiem-flex">
                            <input type="hidden" name="controller" value="user">
                            <input type="hidden" name="action" value="quanLyDocGia">

                            <div class="search-input-wrapper">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input
                                    type="text"
                                    name="tuKhoa"
                                    value="<?= htmlspecialchars($tuKhoa ?? '') ?>"
                                    placeholder="Nhập mã SV hoặc họ tên..."
                                    autofocus
                                >
                            </div>

                            <button type="submit" class="btn-tim-kiem">
                                <span>Tìm kiếm</span>
                            </button>

                            <?php if (!empty($tuKhoa)): ?>
                                <a href="index.php?controller=user&action=quanLyDocGia" class="btn-lam-moi" title="Xem tất cả độc giả">Làm mới</a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <!-- BẢNG ĐỘC GIẢ (7 CỘT CHUẨN) -->
                    <div class="table-responsive-wrapper">
                        <table class="bang-quanly">
                            <thead>
                                <tr>
                                    <th style="width: 12%;">Mã SV</th>
                                    <th style="width: 22%;">Họ tên</th>
                                    <th style="width: 22%;">Email</th>
                                    <th style="width: 14%;">Trạng thái</th>
                                    <th style="width: 12%;">Đang mượn</th>
                                    <th style="width: 10%;">Vi phạm</th>
                                    <th style="width: 8%; text-align: center;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($danhSachDocGia)): ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 36px; color: var(--text-secondary);">
                                            Không tìm thấy độc giả nào phù hợp.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($danhSachDocGia as $dg): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($dg['ma_nguoi_dung']) ?></strong></td>
                                            <td><?= htmlspecialchars($dg['ho_ten']) ?></td>
                                            <td><?= htmlspecialchars($dg['email']) ?></td>
                                            <td>
                                                <span class="user-status-tag <?= ($dg['trang_thai'] ?? '') === 'Hoạt động' ? 'active' : 'locked' ?>">
                                                    <?= htmlspecialchars($dg['trang_thai'] ?? 'Hoạt động') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong><?= (int)($dg['so_sach_dang_muon'] ?? 0) ?></strong> / <?= (int)($dg['han_muc'] ?? 5) ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($dg['co_vi_pham'])): ?>
                                                    <button
                                                        type="button"
                                                        class="btn-vi-pham-co"
                                                        onclick="moPopupLichSu(<?= htmlspecialchars(json_encode($dg, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>)"
                                                        title="Xem lịch sử mượn / vi phạm"
                                                    >
                                                        Có
                                                    </button>
                                                <?php else: ?>
                                                    <span class="tag-vi-pham-khong">Không</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <?php if (($dg['trang_thai'] ?? '') === 'Hoạt động'): ?>
                                                    <button
                                                        type="button"
                                                        class="btn-action-lock"
                                                        onclick="moPopupKhoa('<?= htmlspecialchars($dg['ma_nguoi_dung']) ?>', '<?= htmlspecialchars($dg['ho_ten']) ?>')"
                                                    >
                                                        Khóa
                                                    </button>
                                                <?php else: ?>
                                                    <button
                                                        type="button"
                                                        class="btn-action-unlock"
                                                        onclick="moPopupMoKhoa('<?= htmlspecialchars($dg['ma_nguoi_dung']) ?>', '<?= htmlspecialchars($dg['ho_ten']) ?>')"
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

    <!-- MODAL CONFIRM KHÓA TÀI KHOẢN -->
    <div class="modal-overlay" id="modalKhoa">
        <div class="modal-box-confirm">
            <h3 style="margin-top:0; color:#991B1B;">Xác nhận khóa tài khoản</h3>
            <p id="msgConfirmKhoa" style="font-size:14.5px; color:var(--text-body); margin:16px 0 24px 0;"></p>

            <form method="POST" action="index.php?controller=user&action=quanLyDocGia">
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

            <form method="POST" action="index.php?controller=user&action=quanLyDocGia">
                <input type="hidden" name="hanhDong" value="mokhoa">
                <input type="hidden" name="ma_nguoi_dung" id="inputMaMoKhoa">
                <div style="display:flex; justify-content:center; gap:12px;">
                    <button type="button" class="btn-lam-moi" onclick="dongPopupConfirm()">Hủy</button>
                    <button type="submit" class="btn-action-unlock" style="padding:10px 22px;">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL LỊCH SỬ MƯỢN -->
    <div class="modal-overlay" id="modalLichSu" onclick="dongModalKhiClickNgoai(event)">
        <div class="modal-box-history">
            <div class="modal-header">
                <div>
                    <h3 style="margin:0; font-size:17px;">LỊCH SỬ MƯỢN</h3>
                    <p id="modalDocGiaInfo" style="margin:4px 0 0 0; font-size:13.5px; color:var(--primary); font-weight:600;"></p>
                </div>
                <button type="button" onclick="dongPopupLichSu()" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
            </div>
            <div class="modal-body" id="modalLichSuContent"></div>
        </div>
    </div>

    <script>
        function moPopupKhoa(ma, hoTen) {
            document.getElementById('inputMaKhoa').value = ma;
            document.getElementById('msgConfirmKhoa').innerText = 'Bạn có chắc chắn muốn khóa tài khoản ' + hoTen + ' (' + ma + ') không?';
            document.getElementById('modalKhoa').classList.add('active');
        }

        function moPopupMoKhoa(ma, hoTen) {
            document.getElementById('inputMaMoKhoa').value = ma;
            document.getElementById('msgConfirmMoKhoa').innerText = 'Bạn có chắc chắn muốn mở khóa tài khoản ' + hoTen + ' (' + ma + ') không?';
            document.getElementById('modalMoKhoa').classList.add('active');
        }

        function dongPopupConfirm() {
            document.getElementById('modalKhoa').classList.remove('active');
            document.getElementById('modalMoKhoa').classList.remove('active');
        }

        function moPopupLichSu(docGia) {
            document.getElementById('modalDocGiaInfo').innerText = docGia.ho_ten + ' - ' + docGia.ma_nguoi_dung;
            var container = document.getElementById('modalLichSuContent');
            var lichSu = docGia.lich_su_muon || [];

            if (lichSu.length === 0) {
                container.innerHTML = '<div style="text-align:center; padding:30px; color:var(--text-secondary);">Chưa có lịch sử mượn sách.</div>';
            } else {
                var html = '<table class="bang-quanly">';
                html += '<thead><tr><th>Mã phiếu</th><th>Tên sách</th><th>Mã bản sao</th><th>Ngày mượn</th><th>Hạn trả</th><th>Trạng thái</th></tr></thead><tbody>';
                lichSu.forEach(function(item) {
                    html += '<tr>';
                    html += '<td><strong>#' + item.ID_PhieuMuon + '</strong></td>';
                    html += '<td>' + (item.ten_sach || '-') + '</td>';
                    html += '<td><code>' + (item.ma_ban_sao || '-') + '</code></td>';
                    html += '<td>' + (item.NgayMuon || '-') + '</td>';
                    html += '<td>' + (item.HanTra || '-') + '</td>';
                    html += '<td><span class="user-status-tag ' + (item.TrangThai === 'Đã trả' ? 'active' : 'locked') + '">' + item.TrangThai + '</span></td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
                container.innerHTML = html;
            }
            document.getElementById('modalLichSu').classList.add('active');
        }

        function dongPopupLichSu() {
            document.getElementById('modalLichSu').classList.remove('active');
        }

        function dongModalKhiClickNgoai(e) {
            if (e.target.id === 'modalLichSu') dongPopupLichSu();
        }
    </script>
</body>

</html>
