<?php
// src/View/danhmuc/index.php
// Được CategoryController::index() gọi qua:
//   $this->renderView('danhmuc/index.php', [...]);
//
// Các biến có sẵn (do renderView extract ra):
//   $tenDanhMuc, $moTa, $errors, $danhMucDangSua,
//   $thongBaoThanhCong, $danhSachDanhMuc, $vaiTro, $tieuDe,
//   $tuKhoa, $locTrangThai, $tongDanhMuc, $soDangHoatDong,
//   $soNgungHoatDong, $activePage

$tenDanhMuc        = $tenDanhMuc ?? '';
$moTa              = $moTa ?? '';
$errors            = $errors ?? [];
$danhMucDangSua    = $danhMucDangSua ?? null;
$thongBaoThanhCong = $thongBaoThanhCong ?? '';
$danhSachDanhMuc   = $danhSachDanhMuc ?? [];
$vaiTro            = $vaiTro ?? ($_SESSION['user']['vai_tro'] ?? '');
$tieuDe            = $tieuDe ?? 'Quản lý danh mục';
$tuKhoa            = $tuKhoa ?? '';
$locTrangThai      = $locTrangThai ?? '';
$tongDanhMuc       = $tongDanhMuc ?? 0;
$soDangHoatDong    = $soDangHoatDong ?? 0;
$soNgungHoatDong   = $soNgungHoatDong ?? 0;
$activePage        = $activePage ?? 'danhmuc';

if (!function_exists('escape')) {
    function escape($value)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

$laThuThu = ($vaiTro === 'Thủ thư');
$laAdmin  = ($vaiTro === 'Quản trị viên');
?>

<div class="layout">
    <?php require_once __DIR__ . '/../../layout/sidebar.php'; ?>

    <div class="main-content">

<style>
    /* ==================================================
       PAGE
    ================================================== */

    .category-page {
        width: 100%;
        min-height: 100vh;
        padding: 28px 32px 40px;
        background: #F8FAFC;
        box-sizing: border-box;
    }

    .category-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ==================================================
       HEADER CARD
    ================================================== */

    .page-header-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 24px 28px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        box-sizing: border-box;
    }

    .page-header-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #2563EB;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .page-header-icon svg {
        width: 30px;
        height: 30px;
    }

    .page-header-title {
        margin: 0;
        color: #0F172A;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.3px;
        text-transform: uppercase;
        line-height: 1.25;
    }

    .page-header-subtitle {
        margin: 6px 0 0;
        color: #64748B;
        font-size: 14px;
        line-height: 1.5;
    }

    .page-header-subtitle strong {
        color: #334155;
        font-weight: 600;
    }

    /* ==================================================
       ALERT
    ================================================== */

    .category-alert {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        box-sizing: border-box;
    }

    .category-alert.success {
        color: #166534;
        background: #F0FDF4;
        border: 1px solid #DCFCE7;
    }

    .category-alert.error {
        color: #DC2626;
        background: #FEF2F2;
        border: 1px solid #FEE2E2;
    }

    /* ==================================================
       STAT CARDS (ADMIN)
    ================================================== */

    .stat-cards-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-top: 3px solid var(--stat-color, #2563EB);
        border-radius: 12px;
        padding: 18px 20px;
        box-sizing: border-box;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--stat-bg, #EFF6FF);
        color: var(--stat-color, #2563EB);
        flex-shrink: 0;
    }

    .stat-icon svg {
        width: 22px;
        height: 22px;
    }

    .stat-number {
        color: #0F172A;
        font-size: 24px;
        font-weight: 800;
        line-height: 1.2;
    }

    .stat-label {
        margin-top: 2px;
        color: #64748B;
        font-size: 13px;
        font-weight: 500;
    }

    .stat-card.total {
        --stat-color: #2563EB;
        --stat-bg: #EFF6FF;
    }

    .stat-card.active {
        --stat-color: #16A34A;
        --stat-bg: #F0FDF4;
    }

    .stat-card.inactive {
        --stat-color: #DC2626;
        --stat-bg: #FEF2F2;
    }

    /* ==================================================
       CARD (DANH SÁCH)
    ================================================== */

    .category-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .category-card-header {
        padding: 20px 22px 16px;
        border-bottom: 1px solid #E2E8F0;
    }

    .category-card-title {
        margin: 0;
        color: #0F172A;
        font-size: 18px;
        line-height: 1.35;
        font-weight: 700;
    }

    .category-card-subtitle {
        margin: 5px 0 0;
        color: #64748B;
        font-size: 13px;
        line-height: 1.4;
    }

    .category-card-body {
        padding: 22px;
    }

    /* ==================================================
       FORM (modal)
    ================================================== */

    .form-group {
        margin-bottom: 17px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 14px;
        line-height: 1.4;
        font-weight: 600;
    }

    .required {
        color: #DC2626;
    }

    .form-input,
    .form-textarea {
        width: 100%;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        background: #FFFFFF;
        color: #0F172A;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-size: 15px;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-input {
        height: 40px;
        padding: 8px 13px;
    }

    .form-textarea {
        min-height: 90px;
        padding: 10px 13px;
        line-height: 1.5;
        resize: vertical;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: #94A3B8;
    }

    .form-input:focus,
    .form-textarea:focus {
        border-color: #BFDBFE;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .form-input.error,
    .form-textarea.error {
        border-color: #DC2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
    }

    .error-message {
        margin-top: 5px;
        color: #DC2626;
        font-size: 13px;
        line-height: 1.35;
        font-weight: 500;
    }

    .form-actions {
        display: flex;
        gap: 8px;
        margin-top: 20px;
    }

    /* ==================================================
       BUTTON
    ================================================== */

    .btn {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 18px;
        border: 0;
        border-radius: 8px;
        font-family: inherit;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
        box-sizing: border-box;
    }

    .btn-primary {
        color: #FFFFFF;
        background: #2563EB;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
    }

    .btn-primary:hover {
        background: #1E3A8A;
        transform: translateY(-1px);
    }

    .btn-secondary {
        color: #334155;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
    }

    .btn-secondary:hover {
        background: #F8FAFC;
        transform: translateY(-1px);
    }

    .btn svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    /* ==================================================
       TOOLBAR: SEARCH + FILTER + THÊM
    ================================================== */

    .toolbar-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .search-form {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 260px;
    }

    .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .search-input-wrap svg {
        position: absolute;
        left: 13px;
        top: 50%;
        width: 18px;
        height: 18px;
        color: #64748B;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        height: 40px;
        padding: 8px 13px 8px 40px;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        background: #FFFFFF;
        color: #0F172A;
        font-family: inherit;
        font-size: 15px;
        outline: none;
        box-sizing: border-box;
    }

    .search-input:focus {
        border-color: #BFDBFE;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .filter-select {
        height: 40px;
        padding: 0 30px 0 14px;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        background: #FFFFFF;
        color: #334155;
        font-family: inherit;
        font-size: 14px;
        font-weight: 500;
        outline: none;
        cursor: pointer;
        flex-shrink: 0;
        min-width: 170px;
        box-sizing: border-box;
    }

    .filter-select:focus {
        border-color: #BFDBFE;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .search-btn {
        height: 40px;
        padding: 0 18px;
        color: #FFFFFF;
        background: #2563EB;
        border: 0;
        border-radius: 8px;
        font-family: inherit;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        flex-shrink: 0;
    }

    .search-btn:hover {
        background: #1E3A8A;
    }

    .btn-add-category {
        flex-shrink: 0;
        white-space: nowrap;
    }

    /* ==================================================
       TABLE
    ================================================== */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .category-table {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
    }

    .category-table th {
        padding: 14px 16px;
        color: #64748B;
        background: #F8FAFC;
        border-bottom: 1px solid #E2E8F0;
        font-size: 13px;
        line-height: 1.4;
        font-weight: 600;
        text-align: left;
        white-space: nowrap;
    }

    .category-table td {
        padding: 15px 16px;
        color: #334155;
        border-bottom: 1px solid #E2E8F0;
        font-size: 15px;
        line-height: 1.5;
        vertical-align: middle;
    }

    .category-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .category-table tbody tr:hover {
        background: #F8FAFC;
    }

    .category-name {
        color: #0F172A;
        font-weight: 600;
    }

    .category-description-text {
        max-width: 320px;
        color: #64748B;
    }

    .book-count {
        color: #334155;
        font-weight: 600;
    }

    /* ==================================================
       BADGES
    ================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 26px;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 13px;
        line-height: 1.2;
        font-weight: 600;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .status-active {
        color: #16A34A;
        background: #F0FDF4;
        border: 1px solid #DCFCE7;
    }

    .status-inactive {
        color: #DC2626;
        background: #FEF2F2;
        border: 1px solid #FEE2E2;
    }

    /* ==================================================
       ACTIONS
    ================================================== */

    .table-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        flex-wrap: wrap;
    }

    .action-btn {
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 0 11px;
        border-radius: 6px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        transition: background 0.15s ease, transform 0.15s ease;
    }

    .action-edit {
        color: #B45309;
        background: #FFFBEB;
        border: 1px solid #FEF3C7;
    }

    .action-edit:hover {
        background: #FEF3C7;
        transform: translateY(-1px);
    }

    .action-delete {
        color: #DC2626;
        background: #FEF2F2;
        border: 1px solid #FEE2E2;
    }

    .action-delete:hover {
        background: #FEE2E2;
        transform: translateY(-1px);
    }

    .action-btn svg {
        width: 14px;
        height: 14px;
    }

    .delete-form {
        margin: 0;
        display: inline-flex;
    }

    /* ==================================================
       EMPTY
    ================================================== */

    .empty-state {
        padding: 45px 20px;
        text-align: center;
        color: #64748B;
        font-size: 15px;
    }

    /* ==================================================
       ADMIN STATUS SELECT
    ================================================== */

    .status-form {
        margin: 0;
        display: inline-flex;
    }

    .status-select {
        height: 34px;
        padding: 0 28px 0 10px;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        background: #FFFFFF;
        color: #334155;
        font-family: inherit;
        font-size: 13px;
        font-weight: 500;
        outline: none;
        cursor: pointer;
    }

    .status-select:focus {
        border-color: #BFDBFE;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    /* ==================================================
       MODAL (THÊM / SỬA - THỦ THƯ)
    ================================================== */

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        align-items: center;
        justify-content: center;
        padding: 20px;
        z-index: 2000;
        box-sizing: border-box;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-box {
        width: 100%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
        background: #FFFFFF;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        animation: modalFadeIn 0.15s ease;
        box-sizing: border-box;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-8px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 20px 22px;
        border-bottom: 1px solid #E2E8F0;
    }

    .modal-title {
        margin: 0;
        color: #0F172A;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.35;
    }

    .modal-close {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: #64748B;
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .modal-close:hover {
        background: #F1F5F9;
        color: #0F172A;
    }

    .modal-body {
        padding: 22px;
    }

    /* ==================================================
       RESPONSIVE
    ================================================== */

    @media (max-width: 1199px) {
        .category-page { padding: 24px; }
    }

    @media (max-width: 960px) {
        .category-page { padding: 20px; }
        .page-header-card { flex-direction: column; align-items: flex-start; }
    }

    @media (max-width: 768px) {
        .category-page { padding: 16px; }
        .page-header-title { font-size: 20px; }

        .toolbar-row { flex-direction: column; align-items: stretch; }
        .search-form { flex-direction: column; align-items: stretch; min-width: 0; }
        .filter-select, .search-btn, .btn-add-category { width: 100%; }

        .form-actions { flex-direction: column; }
        .form-actions .btn { width: 100%; }

        .table-wrapper { overflow: visible; }
        .category-table { min-width: 0; }
        .category-table thead { display: none; }

        .category-table,
        .category-table tbody,
        .category-table tr,
        .category-table td {
            display: block;
            width: 100%;
        }

        .category-table tr {
            margin-bottom: 12px;
            padding: 12px;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            background: #FFFFFF;
            box-sizing: border-box;
        }

        .category-table td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 9px 4px;
            border-bottom: 0;
        }

        .category-table td::before {
            content: attr(data-label);
            color: #64748B;
            font-size: 13px;
            font-weight: 600;
        }

        .category-description-text { max-width: 55%; text-align: right; }
        .table-actions { justify-content: flex-end; }
    }
</style>

<div class="category-page">
    <div class="category-container">

        <!-- ===== HEADER CARD ===== -->
        <div class="page-header-card">
            <div class="page-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
            </div>
            <div>
                <h1 class="page-header-title"><?= escape($tieuDe) ?></h1>
                <p class="page-header-subtitle">
                    Nghiệp vụ:
                    <strong>
                        <?= $laThuThu
                            ? 'Quản lý tên và mô tả các danh mục sách'
                            : 'Kiểm soát trạng thái hoạt động của danh mục sách' ?>
                    </strong>
                </p>
            </div>
        </div>

        <!-- SUCCESS -->
        <?php if ($thongBaoThanhCong !== ''): ?>
            <div class="category-alert success"><?= escape($thongBaoThanhCong) ?></div>
        <?php endif; ?>

        <!-- ERROR (chung, vd: xóa thất bại) -->
        <?php if (!empty($errors['general'])): ?>
            <div class="category-alert error"><?= escape($errors['general']) ?></div>
        <?php endif; ?>

        <!-- ===== STAT CARDS (CHỈ ADMIN) ===== -->
        <?php if ($laAdmin): ?>
            <div class="stat-cards-row">

                <div class="stat-card total">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-number"><?= (int)$tongDanhMuc ?></div>
                        <div class="stat-label">Tổng số danh mục</div>
                    </div>
                </div>

                <div class="stat-card active">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-number"><?= (int)$soDangHoatDong ?></div>
                        <div class="stat-label">Đang hoạt động</div>
                    </div>
                </div>

                <div class="stat-card inactive">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <path d="M12 9v4"></path>
                            <path d="M12 17h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-number"><?= (int)$soNgungHoatDong ?></div>
                        <div class="stat-label">Ngừng hoạt động</div>
                    </div>
                </div>

            </div>
        <?php endif; ?>

        <!-- ===== MODAL THÊM/SỬA (CHỈ THỦ THƯ) ===== -->
        <?php if ($laThuThu): ?>
            <?php require __DIR__ . '/form.php'; ?>
        <?php endif; ?>

        <!-- ===== DANH SÁCH DANH MỤC ===== -->
        <div class="category-card">

            <div class="category-card-header">
                <h2 class="category-card-title">Danh sách danh mục</h2>
                <p class="category-card-subtitle">Danh sách các danh mục hiện có trong hệ thống.</p>
            </div>

            <div class="category-card-body">

                <div class="toolbar-row">

                    <form method="GET" action="index.php" class="search-form">
                        <input type="hidden" name="controller" value="danhmuc">

                        <div class="search-input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input
                                type="text"
                                name="search"
                                class="search-input"
                                value="<?= escape($tuKhoa) ?>"
                                placeholder="Tìm tên danh mục, mô tả..."
                            >
                        </div>

                        <select name="trang_thai" class="filter-select" onchange="this.form.submit()">
                            <option value="" <?= $locTrangThai === '' ? 'selected' : '' ?>>Tất cả trạng thái</option>
                            <option value="Hoạt động" <?= $locTrangThai === 'Hoạt động' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="Ngừng hoạt động" <?= $locTrangThai === 'Ngừng hoạt động' ? 'selected' : '' ?>>Ngừng hoạt động</option>
                        </select>

                        <button type="submit" class="search-btn">Tìm kiếm</button>
                    </form>

                    <?php if ($laThuThu): ?>
                        <button type="button" class="btn btn-primary btn-add-category" onclick="openAddCategoryModal()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Thêm danh mục
                        </button>
                    <?php endif; ?>

                </div>

                <div class="table-wrapper">
                    <table class="category-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">STT</th>
                                <th>Tên danh mục</th>
                                <th>Mô tả</th>
                                <th style="width: 130px;">Số lượng sách</th>
                                <th style="width: 140px;">Trạng thái</th>
                                <th style="width: <?= $laAdmin ? '220px' : '110px' ?>;">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if (empty($danhSachDanhMuc)): ?>

                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">Chưa có danh mục nào.</div>
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach ($danhSachDanhMuc as $index => $danhMuc): ?>

                                <tr>
                                    <td data-label="STT"><?= $index + 1 ?></td>

                                    <td data-label="Tên danh mục">
                                        <span class="category-name"><?= escape($danhMuc['ten_danh_muc']) ?></span>
                                    </td>

                                    <td data-label="Mô tả">
                                        <span class="category-description-text">
                                            <?= $danhMuc['mo_ta'] ? escape($danhMuc['mo_ta']) : 'Chưa có mô tả' ?>
                                        </span>
                                    </td>

                                    <td data-label="Số lượng sách">
                                        <span class="book-count"><?= (int)($danhMuc['so_luong_sach'] ?? 0) ?></span>
                                    </td>

                                    <td data-label="Trạng thái">
                                        <?php if ($danhMuc['trang_thai'] === 'Hoạt động'): ?>
                                            <span class="status-badge status-active">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="status-badge status-inactive">Ngừng hoạt động</span>
                                        <?php endif; ?>
                                    </td>

                                    <td data-label="Thao tác">
                                        <div class="table-actions">

                                            <?php if ($laThuThu): ?>

                                                <button
                                                    type="button"
                                                    class="action-btn action-edit"
                                                    data-id="<?= (int)$danhMuc['category_id'] ?>"
                                                    data-ten="<?= escape($danhMuc['ten_danh_muc']) ?>"
                                                    data-mota="<?= escape($danhMuc['mo_ta']) ?>"
                                                    onclick="openEditCategoryModal(this)"
                                                >
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 20h9"></path>
                                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                                    </svg>
                                                    Sửa
                                                </button>

                                            <?php elseif ($laAdmin): ?>

                                                <form method="POST" action="index.php?controller=danhmuc" class="status-form">
                                                    <input type="hidden" name="action" value="doi_trang_thai">
                                                    <input type="hidden" name="category_id" value="<?= (int)$danhMuc['category_id'] ?>">
                                                    <select name="trang_thai" class="status-select" onchange="this.form.submit()">
                                                        <option value="Hoạt động" <?= $danhMuc['trang_thai'] === 'Hoạt động' ? 'selected' : '' ?>>Hoạt động</option>
                                                        <option value="Ngừng hoạt động" <?= $danhMuc['trang_thai'] === 'Ngừng hoạt động' ? 'selected' : '' ?>>Ngừng hoạt động</option>
                                                    </select>
                                                </form>

                                                <form
                                                    method="POST"
                                                    action="index.php?controller=danhmuc"
                                                    class="delete-form"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục &quot;<?= escape($danhMuc['ten_danh_muc']) ?>&quot; không?');"
                                                >
                                                    <input type="hidden" name="action" value="xoa">
                                                    <input type="hidden" name="category_id" value="<?= (int)$danhMuc['category_id'] ?>">
                                                    <button type="submit" class="action-btn action-delete">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                            <path d="M10 11v6"></path>
                                                            <path d="M14 11v6"></path>
                                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                                        </svg>
                                                        Xóa
                                                    </button>
                                                </form>

                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

<?php if ($laThuThu): ?>
<script>
    function openCategoryModal() {
        document.getElementById('categoryModalOverlay').classList.add('show');
    }

    function closeCategoryModal() {
        document.getElementById('categoryModalOverlay').classList.remove('show');
    }

    function openAddCategoryModal() {
        document.getElementById('categoryModalTitle').textContent = 'Thêm danh mục';
        document.getElementById('categoryFormAction').value = 'them';
        document.getElementById('categoryFormId').value = '';
        document.getElementById('categoryFormTen').value = '';
        document.getElementById('categoryFormMoTa').value = '';
        document.getElementById('categoryFormSubmitBtn').lastChild.textContent = ' Thêm danh mục';
        openCategoryModal();
    }

    function openEditCategoryModal(btn) {
        document.getElementById('categoryModalTitle').textContent = 'Chỉnh sửa danh mục';
        document.getElementById('categoryFormAction').value = 'sua';
        document.getElementById('categoryFormId').value = btn.dataset.id;
        document.getElementById('categoryFormTen').value = btn.dataset.ten;
        document.getElementById('categoryFormMoTa').value = btn.dataset.mota;
        document.getElementById('categoryFormSubmitBtn').lastChild.textContent = ' Lưu thay đổi';
        openCategoryModal();
    }

    document.getElementById('categoryModalOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeCategoryModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeCategoryModal();
    });

    <?php if ($danhMucDangSua || !empty($errors['ten_danh_muc']) || !empty($errors['mo_ta'])): ?>
    document.addEventListener('DOMContentLoaded', function () {
        openCategoryModal();
    });
    <?php endif; ?>
</script>
<?php endif; ?>

    </div><!-- /.main-content -->
</div><!-- /.layout -->