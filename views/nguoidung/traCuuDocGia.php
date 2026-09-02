<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu độc giả - Thư viện Mini</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        /* Đồng bộ tuyệt đối theo cấu trúc scoped CSS của trang Thông tin cá nhân (profile.php) */
        .user-page {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--text-body);
        }

        /* 1. KHUNG THÔNG TIN TỔNG QUAN HEADER */
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

        /* 2. KHUNG NỘI DUNG CHÍNH (SEARCH + TABLE CHUNG 1 CARD) */
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

        /* KHU VỰC TÌM KIẾM BÊN TRONG CARD */
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

        .btn-tim-kiem:active {
            transform: scale(0.98);
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

        /* BẢNG KẾT QUẢ 5 CỘT */
        .table-responsive-wrapper {
            width: 100%;
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .bang-tracuu {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        .bang-tracuu th {
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

        .bang-tracuu td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            color: var(--text-body);
            vertical-align: middle;
        }

        .bang-tracuu tr:last-child td {
            border-bottom: none;
        }

        .bang-tracuu tbody tr:hover {
            background-color: var(--primary-light);
        }

        /* STATUS TAGS ĐỒNG BỘ PROFILE.PHP */
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

        /* VI PHẠM */
        .tag-vi-pham-khong {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .btn-vi-pham-co {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 6px;
            background-color: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all var(--transition-fast);
            text-decoration: underline;
        }

        .btn-vi-pham-co:hover {
            background-color: #DC2626;
            color: var(--white);
            border-color: #DC2626;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.25);
        }

        /* MODAL POPUP LỊCH SỬ MƯỢN */
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

        .modal-box {
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

        .modal-header-info h3 {
            margin: 0 0 4px 0;
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .modal-header-info p {
            margin: 0;
            font-size: 13.5px;
            color: var(--primary);
            font-weight: 600;
        }

        .modal-close-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-secondary);
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-fast);
        }

        .modal-close-btn:hover {
            background-color: #E2E8F0;
            color: var(--text-primary);
        }

        .modal-body {
            padding: 22px;
            overflow-y: auto;
        }

        .bang-lich-su {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        .bang-lich-su th {
            background-color: #F8FAFC;
            color: var(--text-secondary);
            font-weight: 600;
            padding: 10px 12px;
            border-bottom: 2px solid var(--border);
            font-size: 12.5px;
        }

        .bang-lich-su td {
            padding: 12px;
            border-bottom: 1px solid var(--border);
            color: var(--text-body);
        }

        .badge-slip {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-slip.dang-muon { background-color: #EFF6FF; color: #1D4ED8; }
        .badge-slip.da-tra { background-color: #ECFDF5; color: #047857; }
        .badge-slip.qua-han { background-color: #FEF2F2; color: #B91C1C; }
        .badge-slip.cho-duyet { background-color: #FFFBEB; color: #B45309; }
    </style>
</head>

<body>
    <div class="layout">
        <!-- Nhúng Sidebar dùng chung -->
        <?php
        $activePage = 'nguoidung';
        $activeAction = 'traCuuDocGia';
        require_once __DIR__ . '/../../layout/sidebar.php';
        ?>

        <!-- Vùng nội dung chính -->
        <main class="main-content">
            <div class="user-page">

                <!-- KHUNG 1: THÔNG TIN TỔNG QUAN HEADER -->
                <div class="user-profile-card">
                    <div class="user-profile-avatar">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <div class="user-profile-info">
                        <h2>TRA CỨU ĐỘC GIẢ</h2>
                        <div class="user-profile-meta">
                            <span>Nghiệp vụ: <strong>Tra cứu thông tin & Tình trạng mượn sách</strong></span>

                        </div>
                    </div>
                </div>

                <!-- KHUNG 2: TÌM KIẾM & BẢNG KẾT QUẢ TRONG 1 CARD CHÍNH DUY NHẤT -->
                <div class="user-form-card">
                    <div class="user-form-card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Danh sách độc giả</span>
                    </div>

                    <!-- Ô TÌM KIẾM BÊN TRONG CARD -->
                    <div class="search-container-inner">
                        <form method="GET" action="index.php" class="form-tim-kiem-flex">
                            <input type="hidden" name="controller" value="user">
                            <input type="hidden" name="action" value="traCuuDocGia">

                            <div class="search-input-wrapper">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input
                                    type="text"
                                    name="tuKhoa"
                                    value="<?= htmlspecialchars($tuKhoa ?? '') ?>"
                                    placeholder="Nhập mã sinh viên hoặc họ tên..."
                                    autofocus
                                >
                            </div>

                            <button type="submit" class="btn-tim-kiem">
                                <span>Tìm kiếm</span>
                            </button>

                            <?php if (!empty($tuKhoa)): ?>
                                <a href="index.php?controller=user&action=traCuuDocGia" class="btn-lam-moi" title="Xem tất cả độc giả">Làm mới</a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <!-- BẢNG KẾT QUẢ ĐÚNG 5 CỘT -->
                    <div class="table-responsive-wrapper">
                        <table class="bang-tracuu">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Mã SV</th>
                                    <th style="width: 32%;">Họ tên</th>
                                    <th style="width: 20%;">Trạng thái</th>
                                    <th style="width: 18%;">Đang mượn</th>
                                    <th style="width: 15%;">Vi phạm</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($danhSachDocGia)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 36px; color: var(--text-secondary); font-size: 14.5px;">
                                            Không tìm thấy độc giả phù hợp.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($danhSachDocGia as $dg): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($dg['ma_nguoi_dung']) ?></strong></td>
                                            <td><?= htmlspecialchars($dg['ho_ten']) ?></td>
                                            <td>
                                                <span class="user-status-tag <?= ($dg['trang_thai'] ?? '') === 'Hoạt động' ? 'active' : 'locked' ?>">
                                                    <?= htmlspecialchars($dg['trang_thai'] ?? 'Hoạt động') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <!-- TODO [PHIEU_MUON]: Số sách đang mượn kết nối từ PhieuMuonModel -->
                                                <strong><?= (int)($dg['so_sach_dang_muon'] ?? 0) ?></strong> / <?= (int)($dg['han_muc'] ?? 5) ?>
                                            </td>
                                            <td>
                                                <!-- TODO [PHIEU_MUON]: Kết nối dữ liệu vi phạm từ PhieuMuonModel -->
                                                <?php if (!empty($dg['co_vi_pham'])): ?>
                                                    <button
                                                        type="button"
                                                        class="btn-vi-pham-co"
                                                        onclick="moPopupLichSu(<?= htmlspecialchars(json_encode($dg, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>)"
                                                        title="Bấm để xem chi tiết lịch sử vi phạm"
                                                    >
                                                        Có
                                                    </button>
                                                <?php else: ?>
                                                    <span class="tag-vi-pham-khong">Không</span>
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

    <!-- MODAL POPUP LỊCH SỬ MƯỢN (TODO [PHIEU_MUON]: Kết nối modal với dữ liệu thật sau khi PhieuMuonModel hoàn thành) -->
    <div class="modal-overlay" id="modalLichSu" onclick="dongModalKhiClickNgoai(event)">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-header-info">
                    <h3>LỊCH SỬ MƯỢN</h3>
                    <p id="modalDocGiaInfo">Đang tải...</p>
                </div>
                <button type="button" class="modal-close-btn" onclick="dongPopupLichSu()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="modalLichSuContent">
                    <!-- Bảng lịch sử sẽ được render bằng Javascript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function moPopupLichSu(docGia) {
            document.getElementById('modalDocGiaInfo').innerText = docGia.ho_ten + ' - ' + docGia.ma_nguoi_dung;

            var container = document.getElementById('modalLichSuContent');
            var lichSu = docGia.lich_su_muon || [];

            if (lichSu.length === 0) {
                container.innerHTML = '<div style="text-align:center; padding:30px; color:var(--text-secondary);">Chưa có lịch sử mượn sách.</div>';
            } else {
                var html = '<table class="bang-lich-su">';
                html += '<thead><tr>';
                html += '<th>Mã phiếu</th>';
                html += '<th>Tên sách</th>';
                html += '<th>Mã bản sao</th>';
                html += '<th>Ngày mượn</th>';
                html += '<th>Hạn trả</th>';
                html += '<th>Ngày trả</th>';
                html += '<th>Trạng thái</th>';
                html += '</tr></thead><tbody>';

                lichSu.forEach(function(item) {
                    var badgeClass = 'dang-muon';
                    if (item.TrangThai === 'Đã trả') badgeClass = 'da-tra';
                    else if (item.TrangThai === 'Quá hạn') badgeClass = 'qua-han';
                    else if (item.TrangThai === 'Chờ duyệt') badgeClass = 'cho-duyet';

                    var ngayTraHienThi = item.NgayTra ? item.NgayTra : '<span style="color:var(--text-secondary); font-style:italic;">Chưa trả</span>';
                    var hanTraHienThi = item.HanTra ? item.HanTra : '-';

                    html += '<tr>';
                    html += '<td><strong>#' + item.ID_PhieuMuon + '</strong></td>';
                    html += '<td>' + (item.ten_sach || '-') + '</td>';
                    html += '<td><code>' + (item.ma_ban_sao || '-') + '</code></td>';
                    html += '<td>' + (item.NgayMuon || '-') + '</td>';
                    html += '<td>' + hanTraHienThi + '</td>';
                    html += '<td>' + ngayTraHienThi + '</td>';
                    html += '<td><span class="badge-slip ' + badgeClass + '">' + item.TrangThai + '</span></td>';
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

        function dongModalKhiClickNgoai(event) {
            if (event.target.id === 'modalLichSu') {
                dongPopupLichSu();
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                dongPopupLichSu();
            }
        });
    </script>
</body>

</html>
