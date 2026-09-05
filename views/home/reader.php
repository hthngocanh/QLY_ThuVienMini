<?php
// views/home/index.php

$isLoggedIn = isset($_SESSION["user"]);

if ($isLoggedIn):
    $activePage = 'trangchu';
    $currentUser = $_SESSION["user"];
    $hoTen = $currentUser["ho_ten"] ?? "Người dùng";
    $vaiTro = $currentUser["vai_tro"] ?? "Độc giả";
    $maNguoiDung = $currentUser["ma_nguoi_dung"] ?? "";
?>

<?php if ($vaiTro === 'Độc giả'): ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ độc giả - Thư viện Mini</title>
    <link rel="stylesheet" href="assets/css/design-system.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
        }
        .reader-layout { display: flex; min-height: 100vh; width: 100%; }
        .reader-main {
            flex: 1 1 auto;
            width: 100%;
            min-width: 0;
            padding: clamp(16px, 3vw, 36px);
            overflow-x: hidden;
        }
        .reader-header { margin-bottom: 24px; }
        .reader-header h1 { font-size: clamp(24px, 3vw, 32px); line-height: 1.25; font-weight: 800; letter-spacing: -0.6px; }
        .reader-header h1 span { color: #2563eb; }
        .reader-header p { margin-top: 6px; color: #64748b; font-size: 14px; }
        .toolbar {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px;
            display: grid;
            grid-template-columns: minmax(220px, 1fr) minmax(160px, 210px) minmax(160px, 210px);
            gap: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 16px rgba(15,23,42,.04);
        }
        .toolbar input, .toolbar select {
            width: 100%; height: 44px; border: 1px solid #cbd5e1; border-radius: 9px;
            padding: 0 13px; background: #fff; color: #334155; font-size: 14px; outline: none;
        }
        .toolbar input:focus, .toolbar select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.08); }
        .table-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden;
            box-shadow: 0 4px 18px rgba(15,23,42,.04);
        }
        .table-wrap { width: 100%; overflow-x: auto; }
        .book-table { width: 100%; border-collapse: collapse; min-width: 980px; }
        .book-table th {
            background: #f8fafc; color: #334155; font-size: 13px; font-weight: 700;
            text-align: left; padding: 15px 14px; border-bottom: 1px solid #e2e8f0; white-space: nowrap;
        }
        .book-table td { padding: 16px 14px; border-bottom: 1px solid #eef2f7; vertical-align: middle; font-size: 14px; color: #334155; }
        .book-table tbody tr:last-child td { border-bottom: 0; }
        .book-table tbody tr:hover { background: #fbfdff; }
        .book-name { font-weight: 700; color: #1d4ed8; }
        .description { max-width: 270px; line-height: 1.5; color: #64748b; }
        .category-badge { display: inline-flex; padding: 5px 9px; border-radius: 999px; background: #eff6ff; color: #1d4ed8; font-size: 12px; font-weight: 700; }
        .status-badge { display: inline-flex; align-items: center; gap: 7px; border-radius: 999px; padding: 6px 10px; font-size: 12px; font-weight: 700; white-space: nowrap; }
        .status-badge::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
        .status-available { color: #15803d; background: #dcfce7; }
        .status-borrowed { color: #b45309; background: #fef3c7; }
        .status-unavailable { color: #dc2626; background: #fee2e2; }
        .btn-borrow {
            border: 0; border-radius: 8px; padding: 9px 15px; font-size: 13px; font-weight: 700;
            cursor: pointer; min-width: 78px; transition: .15s ease;
        }
        .btn-borrow.available { background: #16a34a; color: #fff; }
        .btn-borrow.available:hover { background: #15803d; transform: translateY(-1px); }
        .btn-borrow.unavailable { background: #e2e8f0; color: #94a3b8; cursor: pointer; }
        .btn-borrow.pending { background: #fef3c7; color: #b45309; cursor: pointer; }
        .btn-borrow.borrowing { background: #e2e8f0; color: #64748b; cursor: pointer; }
        .btn-borrow.overdue { background: #fee2e2; color: #dc2626; cursor: pointer; }
        .empty-row { text-align: center; padding: 34px !important; color: #94a3b8 !important; }
        .table-footer { padding: 13px 16px; background: #fff; border-top: 1px solid #eef2f7; color: #64748b; font-size: 13px; }
        .mini-notice {
            position: fixed; right: 24px; bottom: 24px; background: #0f172a; color: #fff; padding: 12px 16px;
            border-radius: 10px; box-shadow: 0 10px 30px rgba(15,23,42,.22); font-size: 13px; opacity: 0;
            transform: translateY(10px); pointer-events: none; transition: .2s ease; z-index: 9999; max-width: 360px;
        }
        .mini-notice.show { opacity: 1; transform: translateY(0); }
        /* ================= POPUP PHIẾU MƯỢN ================= */
        .borrow-modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.48); display: none; align-items: center; justify-content: center; padding: 18px; z-index: 3000; }
        .borrow-modal-overlay.show { display: flex; }
        .borrow-modal { width: min(560px, 100%); max-height: calc(100vh - 36px); overflow-y: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; box-shadow: 0 24px 70px rgba(15, 23, 42, 0.22); padding: clamp(20px, 4vw, 30px); }
        .borrow-modal-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 22px; }
        .borrow-modal-header h2 { font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 5px; }
        .borrow-modal-header p { color: #64748b; font-size: 13.5px; line-height: 1.5; }
        .borrow-modal-close { width: 36px; height: 36px; flex: 0 0 36px; border: 0; border-radius: 9px; background: #f1f5f9; color: #475569; cursor: pointer; font-size: 22px; line-height: 1; }
        .borrow-modal-close:hover { background: #e2e8f0; }
        .borrow-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .borrow-form-group { display: flex; flex-direction: column; gap: 7px; min-width: 0; }
        .borrow-form-group.full { grid-column: 1 / -1; }
        .borrow-form-group label { font-size: 13.5px; font-weight: 700; color: #334155; }
        .borrow-form-group input { width: 100%; min-width: 0; padding: 11px 12px; border: 1px solid #cbd5e1; border-radius: 9px; background: #fff; color: #0f172a; font: inherit; outline: none; }
        .borrow-form-group input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10); }
        .borrow-form-group input[readonly] { background: #f8fafc; color: #475569; }
        .borrow-modal-note { margin-top: 16px; padding: 11px 12px; border-radius: 9px; background: #eff6ff; color: #1e40af; font-size: 13px; line-height: 1.5; }
        .borrow-modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; }
        .borrow-modal-actions button { border: 0; border-radius: 9px; padding: 11px 17px; font: inherit; font-weight: 700; cursor: pointer; }
        .borrow-cancel { background: #f1f5f9; color: #475569; }
        .borrow-submit { background: #16a34a; color: #fff; }
        .borrow-submit:hover { background: #15803d; }

        /* ================= POPUP KHÔNG THỂ MƯỢN ================= */
        .unavailable-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.48);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            z-index: 3100;
        }
        .unavailable-modal-overlay.show { display: flex; }
        .unavailable-modal {
            width: min(420px, 100%);
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.22);
            padding: 26px;
            text-align: center;
        }
        .unavailable-modal-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fee2e2;
            color: #dc2626;
        }
        .unavailable-modal h3 {
            margin: 0 0 9px;
            font-size: 20px;
            color: #0f172a;
        }
        .unavailable-modal p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }
        .unavailable-modal-actions {
            display: flex;
            justify-content: center;
            margin-top: 22px;
        }
        .unavailable-ok {
            min-width: 96px;
            border: 0;
            border-radius: 9px;
            padding: 11px 20px;
            background: #2563eb;
            color: #fff;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }
        .unavailable-ok:hover { background: #1d4ed8; }

        /* ================= POPUP KẾT QUẢ GỬI YÊU CẦU ================= */
        .result-modal-overlay {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.48);
            display: none; align-items: center; justify-content: center; padding: 18px; z-index: 3200;
        }
        .result-modal-overlay.show { display: flex; }
        .result-modal {
            width: min(430px, 100%); background: #fff; border: 1px solid #e2e8f0;
            border-radius: 18px; box-shadow: 0 24px 70px rgba(15,23,42,.22);
            padding: 28px; text-align: center;
        }
        .result-modal-icon {
            width: 58px; height: 58px; margin: 0 auto 16px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: #dcfce7; color: #16a34a;
        }
        .result-modal.warning .result-modal-icon { background: #fef3c7; color: #b45309; }
        .result-modal h3 { margin: 0 0 9px; font-size: 20px; color: #0f172a; }
        .result-modal p { margin: 0; color: #64748b; font-size: 14px; line-height: 1.6; }
        .result-modal-actions { display: flex; justify-content: center; margin-top: 22px; }
        .result-ok {
            min-width: 96px; border: 0; border-radius: 9px; padding: 11px 20px;
            background: #2563eb; color: #fff; font: inherit; font-weight: 700; cursor: pointer;
        }
        .result-ok:hover { background: #1d4ed8; }

        @media (max-width: 1100px) {
            .toolbar { grid-template-columns: 1fr 1fr; }
            .toolbar .search-box { grid-column: 1 / -1; }
            .book-table { min-width: 900px; }
        }

        @media (max-width: 760px) {
            .reader-main { padding: 16px 12px 26px; }
            .reader-header { margin-bottom: 18px; }
            .reader-header p { font-size: 13px; line-height: 1.5; }
            .toolbar { grid-template-columns: 1fr; padding: 12px; gap: 10px; }
            .toolbar .search-box { grid-column: auto; }
            .table-card { border-radius: 12px; }
            .table-wrap { overflow-x: visible; }
            .book-table, .book-table tbody, .book-table tr, .book-table td { display: block; width: 100%; }
            .book-table { min-width: 0; }
            .book-table thead { display: none; }
            .book-table tbody { padding: 8px 0; }
            .book-table tr.book-row {
                margin: 0 10px 12px;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                overflow: hidden;
                background: #fff;
            }
            .book-table td {
                display: grid;
                grid-template-columns: minmax(105px, 34%) 1fr;
                gap: 12px;
                align-items: start;
                padding: 10px 12px;
                border-bottom: 1px solid #eef2f7;
                font-size: 13px;
                word-break: break-word;
            }
            .book-table td:last-child { border-bottom: 0; }
            .book-table td::before {
                content: attr(data-label);
                font-weight: 700;
                color: #64748b;
            }
            .description { max-width: none; }
            .btn-borrow { width: 100%; max-width: 150px; }
            .empty-row { display: block !important; }
            .empty-row::before { display: none; }
            .table-footer { font-size: 12px; }
            .mini-notice { left: 12px; right: 12px; bottom: 12px; max-width: none; }
        }

        @media (max-width: 430px) {
            .reader-main { padding: 14px 9px 22px; }
            .reader-header h1 { font-size: 22px; }
            .toolbar input, .toolbar select { height: 42px; font-size: 13px; }
            .book-table td { grid-template-columns: 96px 1fr; padding: 9px 10px; }
        }
        @media (max-width: 560px) {
            .borrow-form-grid { grid-template-columns: 1fr; }
            .borrow-form-group.full { grid-column: auto; }
            .borrow-modal-actions { flex-direction: column-reverse; }
            .borrow-modal-actions button { width: 100%; }
        }
    </style>
</head>
<body>
<div class="reader-layout">
    <?php require_once __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="reader-main">
        <div class="reader-header">
            <h1>Xin chào, <span><?= htmlspecialchars($hoTen) ?></span> 👋</h1>
            <p>Tra cứu sách và kiểm tra tình trạng bản sao hiện có trong thư viện.</p>
        </div>

        <?php
        $danhSachSach = $danhSachSach ?? [];
        $trangThaiMuonCuaToi = $trangThaiMuonCuaToi ?? [];
        $danhMucOptions = [];
        foreach ($danhSachSach as $sach) {
            $dm = trim((string)($sach['danh_muc'] ?? ''));
            if ($dm !== '') $danhMucOptions[$dm] = $dm;
        }
        ksort($danhMucOptions, SORT_NATURAL | SORT_FLAG_CASE);
        ?>

        <div class="toolbar">
            <div class="search-box">
                <input id="bookSearch" type="text" placeholder="Tìm theo mã sách, tên sách, tác giả...">
            </div>
            <select id="categoryFilter">
                <option value="">Tất cả danh mục</option>
                <?php foreach ($danhMucOptions as $dm): ?>
                    <option value="<?= htmlspecialchars(mb_strtolower($dm, 'UTF-8'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($dm) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="statusFilter">
                <option value="">Tất cả trạng thái</option>
                <option value="có sẵn">Có sẵn</option>
                <option value="đang mượn">Đang mượn</option>
                <option value="chưa có sẵn">Chưa có sẵn</option>
            </select>
        </div>

        <section class="table-card">
            <div class="table-wrap">
                <table class="book-table" id="readerBookTable">
                    <thead>
                    <tr>
                        <th>Mã sách</th>
                        <th>Tên sách</th>
                        <th>Tác giả</th>
                        <th>Mô tả</th>
                        <th>Danh mục</th>
                        <th>Trạng thái bản sao</th>
                        <th>Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($danhSachSach)): ?>
                        <tr><td colspan="7" class="empty-row">Chưa có dữ liệu đầu sách.</td></tr>
                    <?php else: ?>
                        <?php foreach ($danhSachSach as $sach): ?>
                            <?php
                            $status = $sach['trang_thai_ban_sao'] ?? 'Chưa có sẵn';
                            $statusLower = mb_strtolower($status, 'UTF-8');
                            $category = $sach['danh_muc'] ?? 'Chưa phân loại';
                            $searchText = mb_strtolower(
                                ($sach['ma_sach'] ?? '') . ' ' .
                                ($sach['ten_sach'] ?? '') . ' ' .
                                ($sach['tac_gia'] ?? '') . ' ' .
                                ($sach['mo_ta'] ?? '') . ' ' .
                                $category,
                                'UTF-8'
                            );
                            $statusClass = $status === 'Có sẵn'
                                ? 'status-available'
                                : ($status === 'Đang mượn' ? 'status-borrowed' : 'status-unavailable');

                            $bookId = (int)($sach['book_id'] ?? 0);
                            $trangThaiCuaToi = $trangThaiMuonCuaToi[$bookId] ?? '';
                            $coTheMuon = ($status === 'Có sẵn' && $trangThaiCuaToi !== 'Chờ duyệt');
                            ?>
                            <tr class="book-row"
                                data-search="<?= htmlspecialchars($searchText, ENT_QUOTES, 'UTF-8') ?>"
                                data-category="<?= htmlspecialchars(mb_strtolower($category, 'UTF-8'), ENT_QUOTES, 'UTF-8') ?>"
                                data-status="<?= htmlspecialchars($statusLower, ENT_QUOTES, 'UTF-8') ?>">
                                <td data-label="Mã sách"><?= htmlspecialchars($sach['ma_sach'] ?? '') ?></td>
                                <td data-label="Tên sách"><span class="book-name"><?= htmlspecialchars($sach['ten_sach'] ?? '') ?></span></td>
                                <td data-label="Tác giả"><?= htmlspecialchars($sach['tac_gia'] ?? '') ?></td>
                                <td data-label="Mô tả" class="description"><?= htmlspecialchars($sach['mo_ta'] ?? 'Chưa có mô tả') ?></td>
                                <td data-label="Danh mục"><span class="category-badge"><?= htmlspecialchars($category) ?></span></td>
                                <td data-label="Trạng thái"><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span></td>
                                <td data-label="Thao tác">
                                    <?php if ($trangThaiCuaToi === 'Chờ duyệt'): ?>
                                        <button type="button"
                                                class="btn-borrow pending js-pending-btn"
                                                data-book-name="<?= htmlspecialchars($sach['ten_sach'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                            Chờ duyệt
                                        </button>
                                    <?php else: ?>
                                        <button type="button"
                                                class="btn-borrow <?= $coTheMuon ? 'available' : 'unavailable' ?> js-borrow-btn"
                                                data-can-borrow="<?= $coTheMuon ? '1' : '0' ?>"
                                                data-book-id="<?= $bookId ?>"
                                                data-book-code="<?= htmlspecialchars($sach['ma_sach'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-book-name="<?= htmlspecialchars($sach['ten_sach'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-author="<?= htmlspecialchars($sach['tac_gia'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                            Mượn
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-footer">Hiển thị <span id="visibleCount"><?= count($danhSachSach) ?></span> / <?= count($danhSachSach) ?> đầu sách</div>
        </section>
    </main>
</div>
<!-- Popup Phiếu mượn -->
<div class="unavailable-modal-overlay" id="unavailableModal" aria-hidden="true">
    <div class="unavailable-modal" role="dialog" aria-modal="true" aria-labelledby="unavailableModalTitle">
        <div class="unavailable-modal-icon" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <h3 id="unavailableModalTitle">Không thể mượn sách</h3>
        <p id="unavailableMessage">Bạn không thể mượn sách vì sách không có sẵn.</p>
        <div class="unavailable-modal-actions">
            <button type="button" class="unavailable-ok" id="unavailableOk">OK</button>
        </div>
    </div>
</div>

<div class="borrow-modal-overlay" id="borrowModal" aria-hidden="true">
    <div class="borrow-modal" role="dialog" aria-modal="true" aria-labelledby="borrowModalTitle">
        <div class="borrow-modal-header">
            <div>
                <h2 id="borrowModalTitle">Phiếu mượn sách</h2>
                <p>Kiểm tra thông tin và chọn thời gian mượn trước khi gửi yêu cầu.</p>
            </div>
            <button type="button" class="borrow-modal-close" id="borrowModalClose" aria-label="Đóng">&times;</button>
        </div>
        <form id="borrowRequestForm" method="POST" action="index.php?controller=phieumuon&action=yeuCauMuon">
            <input type="hidden" id="borrowBookId" name="book_id" value="">
            <div class="borrow-form-grid">
                <div class="borrow-form-group"><label>Mã độc giả</label><input type="text" value="<?= htmlspecialchars($maNguoiDung) ?>" readonly></div>
                <div class="borrow-form-group"><label>Người mượn</label><input type="text" value="<?= htmlspecialchars($hoTen) ?>" readonly></div>
                <div class="borrow-form-group"><label>Mã sách</label><input type="text" id="borrowBookCode" readonly></div>
                <div class="borrow-form-group"><label>Tác giả</label><input type="text" id="borrowAuthor" readonly></div>
                <div class="borrow-form-group full"><label>Tên sách</label><input type="text" id="borrowBookName" readonly></div>
                <div class="borrow-form-group"><label>Ngày gửi yêu cầu</label><input type="date" id="borrowDate" readonly></div>
                <div class="borrow-form-group"><label>Trạng thái</label><input type="text" value="Chờ duyệt" readonly></div>
            </div>
            <div class="borrow-modal-note">Khi bấm <strong>Gửi yêu cầu</strong>, hệ thống sẽ tự chọn một bản sao còn có sẵn. Phiếu mượn được tạo với trạng thái <strong>Chờ duyệt</strong>; bản sao chưa chuyển sang <strong>Đang mượn</strong> cho tới khi Thủ thư duyệt.</div>
            <div class="borrow-modal-actions">
                <button type="button" class="borrow-cancel" id="borrowCancel">Hủy</button>
                <button type="submit" class="borrow-submit" id="borrowSubmit">Gửi yêu cầu</button>
            </div>
        </form>
    </div>
</div>

<div class="result-modal-overlay" id="resultModal" aria-hidden="true">
    <div class="result-modal" id="resultModalBox" role="dialog" aria-modal="true" aria-labelledby="resultModalTitle">
        <div class="result-modal-icon" aria-hidden="true">
            <svg width="29" height="29" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <h3 id="resultModalTitle">Gửi yêu cầu thành công</h3>
        <p id="resultModalMessage">Sách của bạn đang trong trạng thái chờ duyệt.</p>
        <div class="result-modal-actions">
            <button type="button" class="result-ok" id="resultOk">OK</button>
        </div>
    </div>
</div>

<div class="mini-notice" id="miniNotice"></div>
<script>
(function () {
    const search = document.getElementById('bookSearch');
    const category = document.getElementById('categoryFilter');
    const status = document.getElementById('statusFilter');
    const rows = Array.from(document.querySelectorAll('.book-row'));
    const visibleCount = document.getElementById('visibleCount');
    const notice = document.getElementById('miniNotice');
    const borrowModal = document.getElementById('borrowModal');
    const borrowModalClose = document.getElementById('borrowModalClose');
    const borrowCancel = document.getElementById('borrowCancel');
    const borrowForm = document.getElementById('borrowRequestForm');
    const borrowBookId = document.getElementById('borrowBookId');
    const borrowBookCode = document.getElementById('borrowBookCode');
    const borrowBookName = document.getElementById('borrowBookName');
    const borrowAuthor = document.getElementById('borrowAuthor');
    const borrowDate = document.getElementById('borrowDate');
    const borrowSubmit = document.getElementById('borrowSubmit');
    const unavailableModal = document.getElementById('unavailableModal');
    const unavailableOk = document.getElementById('unavailableOk');
    const unavailableMessage = document.getElementById('unavailableMessage');
    const resultModal = document.getElementById('resultModal');
    const resultModalBox = document.getElementById('resultModalBox');
    const resultModalTitle = document.getElementById('resultModalTitle');
    const resultModalMessage = document.getElementById('resultModalMessage');
    const resultOk = document.getElementById('resultOk');
    let noticeTimer = null;

    function normalize(value) {
        return (value || '').toString().trim().toLocaleLowerCase('vi-VN');
    }

    function filterRows() {
        const q = normalize(search ? search.value : '');
        const cat = normalize(category ? category.value : '');
        const st = normalize(status ? status.value : '');
        let count = 0;

        rows.forEach(row => {
            const okSearch = !q || normalize(row.dataset.search).includes(q);
            const okCategory = !cat || normalize(row.dataset.category) === cat;
            const okStatus = !st || normalize(row.dataset.status) === st;
            const show = okSearch && okCategory && okStatus;
            row.style.display = show ? '' : 'none';
            if (show) count++;
        });

        if (visibleCount) visibleCount.textContent = count;
    }

    function showNotice(message) {
        if (!notice) return;
        notice.textContent = message;
        notice.classList.add('show');
        clearTimeout(noticeTimer);
        noticeTimer = setTimeout(() => notice.classList.remove('show'), 2600);
    }

    if (search) search.addEventListener('input', filterRows);
    if (category) category.addEventListener('change', filterRows);
    if (status) status.addEventListener('change', filterRows);

    function formatDate(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function openBorrowModal(button) {
        if (!borrowModal) return;
        if (borrowBookId) borrowBookId.value = button.dataset.bookId || '';
        if (borrowBookCode) borrowBookCode.value = button.dataset.bookCode || '';
        if (borrowBookName) borrowBookName.value = button.dataset.bookName || '';
        if (borrowAuthor) borrowAuthor.value = button.dataset.author || '';
        const today = new Date();
        if (borrowDate) borrowDate.value = formatDate(today);
        borrowModal.classList.add('show');
        borrowModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeBorrowModal() {
        if (!borrowModal) return;
        borrowModal.classList.remove('show');
        borrowModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function openUnavailableModal(message) {
        if (!unavailableModal) return;
        if (unavailableMessage && message) unavailableMessage.textContent = message;
        unavailableModal.classList.add('show');
        unavailableModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        if (unavailableOk) setTimeout(() => unavailableOk.focus(), 0);
    }

    function closeUnavailableModal() {
        if (!unavailableModal) return;
        unavailableModal.classList.remove('show');
        unavailableModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function openResultModal(title, message, warning) {
        if (!resultModal) return;
        if (resultModalTitle) resultModalTitle.textContent = title || 'Thông báo';
        if (resultModalMessage) resultModalMessage.textContent = message || '';
        if (resultModalBox) resultModalBox.classList.toggle('warning', !!warning);
        resultModal.classList.add('show');
        resultModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        if (resultOk) setTimeout(() => resultOk.focus(), 0);
    }

    function closeResultModal() {
        if (!resultModal) return;
        resultModal.classList.remove('show');
        resultModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.js-borrow-btn').forEach(button => {
        button.addEventListener('click', function () {
            if (this.dataset.canBorrow !== '1') {
                openUnavailableModal('Bạn không thể mượn sách vì sách không có sẵn.');
                return;
            }
            openBorrowModal(this);
        });
    });

    document.querySelectorAll('.js-pending-btn').forEach(button => {
        button.addEventListener('click', function () {
            openResultModal(
                'Yêu cầu đang chờ duyệt',
                'Sách của bạn đang trong trạng thái chờ duyệt.',
                true
            );
        });
    });

    if (borrowModalClose) borrowModalClose.addEventListener('click', closeBorrowModal);
    if (borrowCancel) borrowCancel.addEventListener('click', closeBorrowModal);
    if (borrowModal) borrowModal.addEventListener('click', function (event) { if (event.target === borrowModal) closeBorrowModal(); });
    if (unavailableOk) unavailableOk.addEventListener('click', closeUnavailableModal);
    if (unavailableModal) unavailableModal.addEventListener('click', function (event) { if (event.target === unavailableModal) closeUnavailableModal(); });
    if (resultOk) resultOk.addEventListener('click', closeResultModal);
    if (resultModal) resultModal.addEventListener('click', function (event) { if (event.target === resultModal) closeResultModal(); });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            if (borrowModal && borrowModal.classList.contains('show')) closeBorrowModal();
            if (unavailableModal && unavailableModal.classList.contains('show')) closeUnavailableModal();
            if (resultModal && resultModal.classList.contains('show')) closeResultModal();
        }
    });

    if (borrowForm) {
        borrowForm.addEventListener('submit', function (event) {
            if (!borrowBookId || !borrowBookId.value) {
                event.preventDefault();
                showNotice('Không xác định được đầu sách cần mượn.');
                return;
            }

            if (borrowSubmit) {
                borrowSubmit.disabled = true;
                borrowSubmit.textContent = 'Đang gửi...';
            }
        });
    }

    // Hiện popup kết quả sau khi server tạo yêu cầu và redirect về Trang chủ.
    const borrowResult = <?= json_encode($_GET['borrow'] ?? '', JSON_UNESCAPED_UNICODE) ?>;
    if (borrowResult === 'success') {
        openResultModal(
            'Gửi yêu cầu thành công',
            'Sách của bạn đang trong trạng thái chờ duyệt.',
            false
        );
    } else if (borrowResult === 'already_pending') {
        openResultModal(
            'Yêu cầu đang chờ duyệt',
            'Sách của bạn đang trong trạng thái chờ duyệt.',
            true
        );
    } else if (borrowResult === 'limit_reached') {
        const borrowLimit = <?= (int)($_GET['limit'] ?? 5) ?>;
        openResultModal(
            'Kh\u00f4ng th\u1ec3 m\u01b0\u1ee3n th\u00eam',
            'B\u1ea1n \u0111\u00e3 \u0111\u1ea1t h\u1ea1n m\u1ee9c m\u01b0\u1ee3n t\u1ed1i \u0111a ' + borrowLimit + ' cu\u1ed1n.',
            true
        );
    } else if (borrowResult === 'unavailable') {
        openUnavailableModal('Bạn không thể mượn sách vì sách không có sẵn.');
    } else if (borrowResult === 'forbidden') {
        openUnavailableModal('Bạn không có quyền gửi yêu cầu mượn sách.');
    } else if (borrowResult === 'user_invalid') {
        openUnavailableModal('Tài khoản của bạn không hoạt động hoặc không hợp lệ.');
    } else if (borrowResult === 'invalid' || borrowResult === 'error') {
        openUnavailableModal('Không thể gửi yêu cầu mượn lúc này. Vui lòng thử lại.');
    }
})();
</script>
</body>
</html>
<?php else: ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản lý Thư viện Mini - Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .main {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .page-header p {
            color: var(--text-muted);
            font-size: 15px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            color: inherit;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .icon-blue { background: #eff6ff; color: #2563eb; }
        .icon-green { background: #ecfdf5; color: #059669; }
        .icon-purple { background: #faf5ff; color: #9333ea; }
        .icon-amber { background: #fffbeb; color: #d97706; }

        .stat-info h3 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-info span {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .welcome-card {
            background: white;
            border-radius: 16px;
            padding: 35px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .welcome-content h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .welcome-content p {
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
            max-width: 700px;
        }

        @media (max-width: 850px) {
            .main {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="layout">
        <!-- Nhúng Sidebar dùng chung -->
        <?php require_once __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Nội dung chính Dashboard -->
        <main class="main">
            <div class="page-header">
                <h1>Tổng quan hệ thống</h1>
                <p>Chào mừng bạn trở lại, <strong><?= htmlspecialchars($hoTen) ?></strong> (<?= htmlspecialchars($vaiTro) ?>)</p>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid">
                <a href="index.php?controller=dausach" class="stat-card">
                    <div class="stat-icon icon-blue">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Đầu sách</h3>
                        <span>Quản lý kho sách</span>
                    </div>
                </a>

                <a href="index.php?controller=bansao" class="stat-card">
                    <div class="stat-icon icon-green">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Bản sao</h3>
                        <span>Tình trạng cuốn sách</span>
                    </div>
                </a>

                <a href="index.php?controller=phieumuon" class="stat-card">
                    <div class="stat-icon icon-purple">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            <line x1="9" y1="12" x2="15" y2="12"></line>
                            <line x1="9" y1="16" x2="13" y2="16"></line>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Phiếu mượn</h3>
                        <span>Lịch sử mượn/trả</span>
                    </div>
                </a>

                <a href="index.php?controller=user&action=profile" class="stat-card">
                    <div class="stat-icon icon-amber">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3><?= $vaiTro === 'Độc giả' ? 'Cá nhân' : 'Người dùng' ?></h3>
                        <span><?= $vaiTro === 'Độc giả' ? 'Thông tin cá nhân' : 'Quản lý tài khoản' ?></span>
                    </div>
                </a>
            </div>

            <div class="welcome-card">
                <div class="welcome-content">
                    <h2>Hệ thống Quản lý Thư viện Mini</h2>
                    <p>
                        Chào mừng bạn đến với hệ thống quản lý thư viện. Bạn có thể sử dụng thanh điều hướng bên trái để quản lý danh mục, độc giả, sách và theo dõi các phiếu mượn trả một cách nhanh chóng và chính xác.
                    </p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php endif; ?>

<?php else: ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thư viện Mini - Quản lý thư viện đơn giản & hiệu quả</title>

    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        :root {
            --navy-dark: var(--text-primary);
            --border-color: var(--border);
            --card-shadow: var(--shadow-card);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--white);
            color: var(--text-body);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .landing-wrapper {
            width: 100%;
            max-width: 1440px;
            margin: 0 auto;
            padding-left: 32px;
            padding-right: 32px;
        }

        .hero-section {
            position: relative;
            background: linear-gradient(135deg, #FFFFFF 0%, #EFF6FF 100%);
            flex: 0 0 55vh;
            min-height: 0;
            padding: clamp(28px, 4vh, 50px) 0;
            display: flex;
            align-items: center;
        }

        .hero-section .landing-wrapper {
            width: 100%;
        }

        .hero-top-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: clamp(16px, 2.5vh, 28px);
            position: relative;
            z-index: 2;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-text {
            font-size: 19px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.2px;
        }

        .hero-content-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: clamp(40px, 5vw, 90px);
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-left-col {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-main-title {
            font-size: clamp(36px, 3.2vw, 46px);
            font-weight: 800;
            line-height: 1.2;
            color: var(--navy-dark);
            letter-spacing: -0.8px;
            margin-bottom: clamp(14px, 2vh, 22px);
        }

        .hero-main-title span.blue-highlight {
            color: var(--primary);
            display: block;
        }

        .hero-sub-text {
            font-size: 15px;
            line-height: 1.6;
            color: var(--text-muted);
            margin-bottom: clamp(20px, 3vh, 32px);
            max-width: 560px;
        }

        .hero-cta-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: clamp(20px, 3vh, 32px);
        }

        .btn-cta-login {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 13px 28px;
            background-color: var(--primary);
            color: #FFFFFF;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
        }

        .btn-cta-login:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
        }

        .btn-cta-register {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 13px 28px;
            background-color: var(--white);
            color: var(--primary);
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            border: 1px solid var(--border-blue);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }

        .btn-cta-register:hover {
            background-color: var(--primary-light);
            transform: translateY(-1px);
        }

        .hero-check-list {
            display: flex;
            flex-wrap: wrap;
            gap: clamp(16px, 2vw, 26px);
            font-size: 14px;
            color: var(--text-body);
            font-weight: 600;
        }

        .hero-check-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .check-badge {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: var(--primary);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
        }

        .hero-right-col {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .library-illustration-card {
            width: clamp(320px, 32vw, 440px);
            max-width: 100%;
            height: auto;
            background: #FFFFFF;
            border-radius: 24px;
            padding: clamp(18px, 2vw, 26px);
            box-shadow: 0 16px 36px -10px rgba(37, 99, 235, 0.12), 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-blue);
            position: relative;
            overflow: hidden;
        }

        .ill-arch-window {
            background: linear-gradient(180deg, var(--primary-light) 0%, var(--border-blue) 100%);
            border-radius: 18px;
            padding: 22px 18px 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .ill-library-sign {
            background: var(--primary);
            color: #FFFFFF;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 6px;
            letter-spacing: 1.5px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
        }

        .ill-book-pile {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            gap: 6px;
            margin-bottom: 14px;
        }

        .spine-book {
            width: 82%;
            height: 26px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #FFFFFF;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .spine-b1 { background: var(--primary-dark); }
        .spine-b2 { background: var(--primary); }
        .spine-b3 { background: var(--primary); opacity: 0.85; }
        .spine-b4 { background: var(--border-blue); color: var(--primary-dark); }

        .open-book-vector {
            width: 90px;
            height: 40px;
            margin-top: 4px;
            display: flex;
            justify-content: center;
        }

        .features-section {
            background-color: var(--white);
            padding: clamp(45px, 6vh, 80px) 0;
            border-top: 1px solid var(--border-color);
        }

        .features-section .landing-wrapper {
            width: 100%;
        }

        .features-heading-wrap {
            text-align: center;
            margin-bottom: clamp(24px, 4vh, 40px);
        }

        .features-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--navy-dark);
            margin-bottom: 8px;
            letter-spacing: -0.4px;
        }

        .features-accent-line {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .features-accent-line .line {
            width: 44px;
            height: 3.5px;
            background-color: var(--primary);
            border-radius: 3px;
        }

        .features-accent-line .dot {
            width: 6px;
            height: 6px;
            background-color: var(--primary);
            border-radius: 50%;
        }

        .features-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: clamp(20px, 2.5vw, 36px);
        }

        .feature-item-card {
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: clamp(22px, 2vw, 32px);
            box-shadow: var(--card-shadow);
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .feature-item-card:hover {
            border-color: var(--border-blue);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.08);
            transform: translateY(-2px);
        }

        .feature-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
        }

        .feature-icon-square {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: var(--primary);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        .feature-card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy-dark);
        }

        .feature-card-desc {
            font-size: 14.5px;
            line-height: 1.6;
            color: var(--text-muted);
        }

        .landing-footer {
            background: var(--primary-dark);
            color: #FFFFFF;
            flex: 0 0 auto;
            padding: 14px 0;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            margin-top: auto;
            position: relative;
        }

        @media (max-width: 768px) {
            .hero-content-grid {
                grid-template-columns: 1fr;
                gap: 36px;
                text-align: center;
            }

            .hero-left-col {
                align-items: center;
            }

            .features-cards-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- 1. HERO SECTION -->
    <main class="hero-section">
        <div class="landing-wrapper">
            <div class="hero-top-brand">
                <div class="brand-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3C7.5 3 3.75 4.5 2 5.5V19C3.75 18 7.5 16.5 12 16.5C16.5 16.5 20.25 18 22 19V5.5C20.25 4.5 16.5 3 12 3ZM11 15C7.5 15 4.8 16 3.5 16.8V6.8C4.8 6 7.5 5 11 5V15ZM20.5 16.8C19.2 16 16.5 15 13 15V5C16.5 5 19.2 6 20.5 6.8V16.8Z"/>
                    </svg>
                </div>
                <span class="brand-text">THƯ VIỆN MINI</span>
            </div>

            <div class="hero-content-grid">
                <div class="hero-left-col">
                    <h1 class="hero-main-title">
                        Quản lý thư viện
                        <span class="blue-highlight">đơn giản & hiệu quả</span>
                    </h1>

                    <p class="hero-sub-text">
                        Nền tảng hỗ trợ tối ưu hóa quy trình quản lý kho sách, tra cứu độc giả và theo dõi mượn – trả sách một cách nhanh chóng, chính xác và tiện lợi.
                    </p>

                    <div class="hero-cta-buttons">
                        <a href="index.php?controller=auth&action=login" class="btn-cta-login">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            <span>Đăng nhập hệ thống</span>
                        </a>

                        <a href="index.php?controller=auth&action=register" class="btn-cta-register">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            <span>Đăng ký tài khoản</span>
                        </a>
                    </div>

                    <div class="hero-check-list">
                        <div class="hero-check-item">
                            <span class="check-badge">✓</span>
                            <span>Tra cứu tức thì</span>
                        </div>
                        <div class="hero-check-item">
                            <span class="check-badge">✓</span>
                            <span>Phân quyền bảo mật</span>
                        </div>
                        <div class="hero-check-item">
                            <span class="check-badge">✓</span>
                            <span>Giao diện trực quan</span>
                        </div>
                    </div>
                </div>

                <div class="hero-right-col">
                    <div class="library-illustration-card">
                        <div class="ill-arch-window">
                            <div class="ill-library-sign">LIBRARY</div>
                            <div class="ill-book-pile">
                                <div class="spine-book spine-b1">QUẢN LÝ THƯ VIỆN</div>
                                <div class="spine-book spine-b2">CÔNG NGHỆ THÔNG TIN</div>
                                <div class="spine-book spine-b3">DATABASE SYSTEMS</div>
                                <div class="spine-book spine-b4">KHOA HỌC DỮ LIỆU</div>
                            </div>
                            <div class="open-book-vector">
                                <svg width="90" height="42" viewBox="0 0 100 45" fill="none">
                                    <path d="M50 8C35 2 15 5 5 12V38C15 31 35 28 50 34C65 28 85 31 95 38V12C85 5 65 2 50 8Z" fill="#ffffff" stroke="#93c5fd" stroke-width="2"/>
                                    <line x1="50" y1="8" x2="50" y2="34" stroke="#60a5fa" stroke-width="2"/>
                                    <line x1="15" y1="18" x2="42" y2="15" stroke="#cbd5e1" stroke-width="1.5"/>
                                    <line x1="15" y1="24" x2="42" y2="21" stroke="#cbd5e1" stroke-width="1.5"/>
                                    <line x1="58" y1="15" x2="85" y2="18" stroke="#cbd5e1" stroke-width="1.5"/>
                                    <line x1="58" y1="21" x2="85" y2="24" stroke="#cbd5e1" stroke-width="1.5"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- 2. FEATURES SECTION -->
    <section class="features-section">
        <div class="landing-wrapper">
            <div class="features-heading-wrap">
                <h2 class="features-title">Hệ thống quản lý thư viện toàn diện</h2>
                <div class="features-accent-line">
                    <span class="line"></span>
                    <span class="dot"></span>
                </div>
            </div>

            <div class="features-cards-grid">
                <div class="feature-item-card">
                    <div class="feature-card-header">
                        <div class="feature-icon-square">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 3C7.5 3 3.75 4.5 2 5.5V19C3.75 18 7.5 16.5 12 16.5C16.5 16.5 20.25 18 22 19V5.5C20.25 4.5 16.5 3 12 3ZM11 15C7.5 15 4.8 16 3.5 16.8V6.8C4.8 6 7.5 5 11 5V15ZM20.5 16.8C19.2 16 16.5 15 13 15V5C16.5 5 19.2 6 20.5 6.8V16.8Z"/>
                            </svg>
                        </div>
                        <h3 class="feature-card-title">Quản lý sách</h3>
                    </div>
                    <p class="feature-card-desc">
                        Theo dõi danh mục, đầu sách và tình trạng từng bản sao trong kho sách một cách khoa học, rõ ràng.
                    </p>
                </div>

                <div class="feature-item-card">
                    <div class="feature-card-header">
                        <div class="feature-icon-square">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
                        </div>
                        <h3 class="feature-card-title">Quản lý độc giả</h3>
                    </div>
                    <p class="feature-card-desc">
                        Hỗ trợ sinh viên đăng ký tài khoản, cập nhật thông tin cá nhân và quản lý phân quyền theo đúng vai trò.
                    </p>
                </div>

                <div class="feature-item-card">
                    <div class="feature-card-header">
                        <div class="feature-icon-square">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                            </svg>
                        </div>
                        <h3 class="feature-card-title">Mượn & trả sách</h3>
                    </div>
                    <p class="feature-card-desc">
                        Lập phiếu mượn nhanh gọn, kiểm soát thời hạn trả và quản lý lịch sử mượn sách chính xác.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. FOOTER -->
    <footer class="landing-footer">
        <div class="landing-wrapper">
            <p>&copy; 2026 Hệ thống Quản lý Thư viện Mini.</p>
        </div>
    </footer>
</body>
</html>
<?php endif; ?>
