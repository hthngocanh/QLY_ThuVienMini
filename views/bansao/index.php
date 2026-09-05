<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bản sao sách</title>
    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        :root {
            --copy-primary: var(--primary, #2563EB);
            --copy-primary-dark: var(--primary-dark, #1E3A8A);
            --copy-primary-light: var(--primary-light, #EFF6FF);
            --copy-border-blue: var(--border-blue, #BFDBFE);
            --copy-white: var(--white, #FFFFFF);
            --copy-bg: var(--bg-page, #F8FAFC);
            --copy-text: var(--text-primary, #0F172A);
            --copy-body: var(--text-body, #334155);
            --copy-muted: var(--text-secondary, #64748B);
            --copy-border: var(--border, #E2E8F0);
            --copy-success: var(--success, #16A34A);
            --copy-warning: var(--warning, #F59E0B);
            --copy-danger: var(--danger, #DC2626);
            --copy-radius-card: var(--radius-card, 16px);
            --copy-radius-button: var(--radius-button, 8px);
            --copy-shadow: var(--shadow-card, 0 4px 20px rgba(0,0,0,.04));
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            max-width: 100%;
            overflow-x: hidden;
            background: var(--copy-bg);
        }

        .copy-main {
            flex: 1;
            min-width: 0;
            padding: 32px 36px;
            background: var(--copy-bg);
        }
.page-head {
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            gap: 20px;
            margin-bottom: 22px;
        }

        .page-head h1 {
            margin: 0;
            color: var(--copy-text);
            font-size: 28px;
            line-height: 1.25;
            font-weight: 800;
        }



        .message {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: opacity .35s ease, transform .35s ease;
        }

        .message.success { color: #166534; background: #F0FDF4; border: 1px solid #DCFCE7; border-left: 4px solid var(--copy-success); }
        .message.error { color: #991B1B; background: #FEF2F2; border: 1px solid #FEE2E2; border-left: 4px solid var(--copy-danger); }
        .message.hide { opacity: 0; transform: translateY(-7px); }

        .admin-overview {
            margin-bottom: 22px;
        }

        .admin-overview-head {
            display: none;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 0;
        }

        .stat-card {
            background: var(--copy-white);
            border: 1px solid var(--copy-border);
            border-radius: var(--copy-radius-card);
            padding: 18px;
            min-height: 92px;
            box-shadow: var(--copy-shadow);
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
            border-top: 3px solid #BFDBFE;
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }

        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(15,23,42,.07); }
        .stat-card.success { border-top-color: #86EFAC; }
        .stat-card.warning { border-top-color: #FCD34D; }
        .stat-card.danger { border-top-color: #FCA5A5; }
        .stat-card.deleted { border-top-color: #CBD5E1; }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: var(--copy-primary-light);
            color: var(--copy-primary);
        }

        .stat-card.success .stat-icon { background: #F0FDF4; color: var(--copy-success); }
        .stat-card.warning .stat-icon { background: #FFFBEB; color: #B45309; }
        .stat-card.danger .stat-icon { background: #FEF2F2; color: var(--copy-danger); }
        .stat-card.deleted .stat-icon { background: #F8FAFC; color: #64748B; }

        .stat-value { font-size: 24px; line-height: 1; font-weight: 800; color: var(--copy-text); }
        .stat-label { margin-top: 5px; font-size: 12px; font-weight: 600; color: var(--copy-muted); }

        .panel {
            width: 100%;
            background: var(--copy-white);
            border: 1px solid var(--copy-border);
            border-radius: var(--copy-radius-card);
            box-shadow: var(--copy-shadow);
            padding: 22px;
            margin-bottom: 22px;
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .panel-head h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: var(--copy-text);
        }

        .panel-head p {
            margin: 5px 0 0;
            font-size: 13px;
            color: var(--copy-muted);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px 18px;
        }

        .field.full { grid-column: 1 / -1; }
        .field label { display: block; margin-bottom: 7px; font-size: 14px; font-weight: 600; color: var(--copy-body); }

        .field input,
        .field select,
        .filter-input,
        .filter-select {
            width: 100%;
            min-height: 40px;
            border: 1px solid var(--copy-border);
            border-radius: 8px;
            background: var(--copy-white);
            color: var(--copy-body);
            padding: 8px 13px;
            font: inherit;
            font-size: 14px;
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .field input:focus,
        .field select:focus,
        .filter-input:focus,
        .filter-select:focus {
            border-color: var(--copy-border-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
        }

        .input-error { border-color: var(--copy-danger) !important; box-shadow: 0 0 0 3px rgba(220, 38, 38, .10) !important; }
        .field-error { margin: 5px 0 0; color: var(--copy-danger); font-size: 13px; font-weight: 500; }


        .field-locked {
            width: 100%;
            min-height: 40px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            background: #F1F5F9;
            color: #64748B;
            padding: 9px 13px;
            font: inherit;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: not-allowed;
        }

        .field-note {
            margin: 6px 0 0;
            color: #64748B;
            font-size: 12.5px;
            line-height: 1.45;
        }

        .field-note.warning {
            color: #92400E;
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-top: 4px;
        }

        .btn {
            min-height: 42px;
            border-radius: 8px;
            border: 1px solid transparent;
            padding: 0 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all .15s ease;
            white-space: nowrap;
        }

        .btn-primary { background: var(--copy-primary); color: #fff; box-shadow: 0 2px 6px rgba(37, 99, 235, .20); }
        .btn-primary:hover { background: var(--copy-primary-dark); transform: translateY(-1px); }
        .btn-secondary { background: #fff; color: var(--copy-body); border-color: var(--copy-border); }
        .btn-secondary:hover { background: #F8FAFC; border-color: #CBD5E1; }

        .toolbar {
            display: grid;
            grid-template-columns: minmax(220px, 1fr) 190px;
            gap: 12px;
            margin-bottom: 16px;
        }

        /* Thanh công cụ quản lý: tìm kiếm + trạng thái + thêm bản sao cùng hàng */
        .management-toolbar {
            display: grid;
            grid-template-columns: minmax(280px, 520px) 180px auto;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .management-search {
            min-width: 0;
        }

        .management-status {
            width: 180px;
        }

        .management-add-btn {
            justify-self: end;
            min-width: 150px;
        }

        /* Popup Thêm/Cập nhật bản sao */
        .copy-modal-box {
            width: min(650px, 100%);
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .26);
            transform: translateY(10px) scale(.97);
            transition: transform .18s ease;
        }

        .modal-overlay.show .copy-modal-box {
            transform: translateY(0) scale(1);
        }

        .copy-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--copy-border);
        }

        .copy-modal-head h3 {
            margin: 0;
            font-size: 19px;
            color: var(--copy-text);
            font-weight: 800;
        }

        .copy-modal-close {
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: var(--copy-muted);
            font-size: 24px;
            line-height: 1;
            cursor: pointer;
        }

        .copy-modal-close:hover {
            background: #F1F5F9;
            color: var(--copy-text);
        }

        .copy-modal-body {
            padding: 22px 24px 24px;
        }

        .copy-modal-body .form-actions {
            justify-content: flex-end;
            border-top: 1px solid var(--copy-border);
            margin-top: 6px;
            padding-top: 18px;
        }

        .search-wrap { position: relative; }
        .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--copy-muted); pointer-events: none; }
        .search-wrap .filter-input { padding-left: 39px; }

        .tabs {
            display: flex;
            gap: 6px;
            padding: 5px;
            border: 1px solid var(--copy-border);
            background: #F8FAFC;
            border-radius: 10px;
            width: fit-content;
            margin-bottom: 18px;
        }

        .tab-btn {
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: var(--copy-muted);
            padding: 9px 13px;
            font: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .tab-btn.active { background: #fff; color: var(--copy-primary); box-shadow: 0 1px 4px rgba(15,23,42,.08); }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        .table-wrap { width: 100%; overflow-x: auto; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .data-table th {
            padding: 12px 13px;
            background: #F8FAFC;
            border-bottom: 1px solid var(--copy-border);
            color: var(--copy-text);
            font-size: 13px;
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
        }

        .data-table td {
            padding: 13px;
            border-bottom: 1px solid var(--copy-border);
            color: var(--copy-body);
            vertical-align: middle;
        }

        .data-table tbody tr:hover { background: #FAFCFF; }
        .data-table tbody tr:last-child td { border-bottom: 0; }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            min-height: 27px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge.available { background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7; }
        .badge.borrowed { background: #FFFBEB; color: #B45309; border: 1px solid #FEF3C7; }
        .badge.unavailable { background: #FEF2F2; color: #DC2626; border: 1px solid #FEE2E2; }
        .badge.deleted { background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; }
        .badge.none { background: #FEF2F2; color: #DC2626; border: 1px solid #FEE2E2; }

        .actions { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
        .action-form { margin: 0; }

        .btn-action {
            min-height: 32px;
            border-radius: 6px;
            padding: 5px 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font: inherit;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all .15s ease;
        }

        .btn-edit { color: #92400E; background: #FFFBEB; border: 1px solid #FEF3C7; }
        .btn-edit:hover { color: #fff; background: #F59E0B; }
        .btn-delete { color: #DC2626; background: #FEF2F2; border: 1px solid #FEE2E2; }
        .btn-delete:hover { color: #fff; background: #DC2626; }
        .btn-restore { color: #166534; background: #F0FDF4; border: 1px solid #DCFCE7; }
        .btn-restore:hover { color: #fff; background: #16A34A; }
        .btn-disabled { color: #94A3B8; background: #F8FAFC; border: 1px solid #E2E8F0; cursor: not-allowed; opacity: .78; }
        .status-note { display: block; margin-top: 5px; color: #B45309; font-size: 12px; font-weight: 600; }


        .pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid var(--copy-border);
            flex-wrap: wrap;
        }

        .pagination-info {
            color: var(--copy-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .pagination-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-btn {
            min-width: 38px;
            height: 36px;
            padding: 0 12px;
            border: 1px solid var(--copy-border);
            border-radius: 8px;
            background: #fff;
            color: var(--copy-body);
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .page-btn:hover:not(:disabled) {
            border-color: var(--copy-border-blue);
            color: var(--copy-primary);
            background: var(--copy-primary-light);
        }

        .page-btn:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        .page-current {
            min-width: 84px;
            text-align: center;
            color: var(--copy-text);
            font-size: 13px;
            font-weight: 700;
        }

        .empty-state {
            padding: 34px 20px;
            text-align: center;
            color: var(--copy-muted);
            font-size: 14px;
        }

        .empty-state svg { margin-bottom: 8px; color: #94A3B8; }

        .reader-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .reader-summary .summary-card {
            background: #fff;
            border: 1px solid var(--copy-border);
            border-radius: 14px;
            padding: 18px;
            box-shadow: var(--copy-shadow);
        }

        .summary-card .summary-number { font-size: 24px; font-weight: 800; color: var(--copy-text); }
        .summary-card .summary-label { margin-top: 5px; color: var(--copy-muted); font-size: 13px; font-weight: 600; }


        .quick-check {
            display: grid;
            grid-template-columns: minmax(220px, 1fr) auto;
            gap: 10px;
            margin-bottom: 16px;
            padding: 14px;
            border: 1px solid var(--copy-border);
            border-radius: 12px;
            background: #F8FAFC;
        }

        .quick-check .filter-select { min-width: 0; }
        .quick-result {
            grid-column: 1 / -1;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid var(--copy-border);
            background: #FFFFFF;
            color: var(--copy-body);
            font-size: 13.5px;
            line-height: 1.6;
            white-space: pre-line;
        }
        .quick-result.success { border-color: #BBF7D0; background: #F0FDF4; color: #166534; }
        .quick-result.warning { border-color: #FDE68A; background: #FFFBEB; color: #92400E; }
        .quick-result.error { border-color: #FECACA; background: #FEF2F2; color: #991B1B; }

        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15,23,42,.55);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .18s ease, visibility .18s ease;
        }

        .modal-overlay.show { opacity: 1; visibility: visible; pointer-events: auto; }

        .modal-box {
            width: min(430px, 100%);
            background: #fff;
            border-radius: 14px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 18px 50px rgba(0,0,0,.22);
            transform: scale(.94);
            transition: transform .18s ease;
        }

        .modal-overlay.show .modal-box { transform: scale(1); }
        .modal-icon { width: 52px; height: 52px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px; background: var(--copy-primary-light); color: var(--copy-primary); }
        .modal-box h3 { margin: 0 0 8px; color: var(--copy-text); font-size: 19px; }
        .modal-box p { margin: 0; color: var(--copy-muted); font-size: 14px; line-height: 1.6; }
        .modal-actions { display: flex; justify-content: center; gap: 10px; margin-top: 21px; }
        .modal-confirm.delete { background: var(--copy-danger); }
        .modal-confirm.restore { background: var(--copy-success); }


        /* ===== KHUNG TIÊU ĐỀ KIỂU TRA CỨU ĐỘC GIẢ ===== */
        .module-hero {
            display: flex;
            align-items: center;
            gap: 22px;
            background: var(--copy-white);
            border: 1px solid var(--copy-border);
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 24px;
            box-shadow: var(--copy-shadow);
        }

        .module-hero-icon {
            width: 64px;
            height: 64px;
            min-width: 64px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #3157C8;
            color: #FFFFFF;
            box-shadow: 0 8px 18px rgba(49, 87, 200, .20);
        }

        .module-hero-icon svg {
            width: 31px;
            height: 31px;
        }

        .module-hero-content {
            min-width: 0;
        }

        .module-hero-title {
            margin: 0 0 7px;
            color: var(--copy-text);
            font-size: 24px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .module-hero-subtitle {
            margin: 0;
            color: var(--copy-muted);
            font-size: 14px;
            line-height: 1.5;
        }

        .module-hero-subtitle strong {
            color: var(--copy-text);
            font-weight: 700;
        }

        @media (max-width: 1199px) {
            .copy-main { padding: 26px 24px; }
            .stats-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        @media (max-width: 960px) {
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .toolbar { grid-template-columns: 1fr 170px; }
            .management-toolbar {
                grid-template-columns: minmax(220px, 1fr) 170px;
            }
            .management-add-btn {
                grid-column: 1 / -1;
                justify-self: end;
            }
        }

        @media (max-width: 768px) {
            .copy-main { padding: 18px 12px; }
            .module-hero {
                padding: 18px;
                gap: 15px;
            }
            .module-hero-icon {
                width: 52px;
                height: 52px;
                min-width: 52px;
            }
            .module-hero-icon svg {
                width: 26px;
                height: 26px;
            }
            .module-hero-title {
                font-size: 20px;
            }
            .page-head { flex-direction: column; }
            .page-head h1 { font-size: 24px; }
            .form-grid { grid-template-columns: 1fr; }
            .field.full, .form-actions { grid-column: auto; }
            .toolbar { grid-template-columns: 1fr; }
            .quick-check { grid-template-columns: 1fr; }
            .management-toolbar {
                grid-template-columns: 1fr;
            }
            .management-status {
                width: 100%;
            }
            .management-add-btn {
                width: 100%;
                justify-self: stretch;
            }
            .copy-modal-head,
            .copy-modal-body {
                padding-left: 18px;
                padding-right: 18px;
            }
            .reader-summary, .stats-grid { grid-template-columns: 1fr; }
            .panel { padding: 16px; }
            .tabs { width: 100%; }
            .tab-btn { flex: 1; }

            .data-table,
            .data-table tbody,
            .data-table tr,
            .data-table td {
                display: block;
                width: 100%;
            }

            .data-table thead { display: none; }
            .data-table tbody tr {
                border: 1px solid var(--copy-border);
                border-radius: 11px;
                margin-bottom: 12px;
                padding: 7px 12px;
                background: #fff;
            }

            .data-table td {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 14px;
                padding: 9px 0;
                border-bottom: 1px solid #EDF2F7;
                text-align: right;
            }

            .data-table td:last-child { border-bottom: 0; }
            .data-table td::before { content: attr(data-label); flex: 0 0 40%; color: var(--copy-muted); font-size: 12px; font-weight: 700; text-align: left; }
            .actions { justify-content: flex-end; }
        }

        @media (max-width: 520px) {
            .form-actions, .modal-actions { flex-direction: column; }
            .form-actions .btn, .modal-actions .btn { width: 100%; }
        }
    </style>
</head>
<body>
<?php
$vaiTroHienTai = $vaiTroHienTai ?? "";
$duocQuanLyBanSao = $duocQuanLyBanSao ?? false;
$laQuanTriVien = $laQuanTriVien ?? false;
$laDocGia = $laDocGia ?? false;
$danhSachBanSao = $danhSachBanSao ?? [];
$danhSachBanSaoDaXoa = $danhSachBanSaoDaXoa ?? [];
$danhSachTraCuu = $danhSachTraCuu ?? [];
$thongKeBanSao = $thongKeBanSao ?? [];
$danhSachDauSach = $danhSachDauSach ?? [];
$trangThaiPhieuDangSua = $trangThaiPhieuDangSua ?? '';
$csrfToken = $csrfToken ?? '';

$isReaderView = !$duocQuanLyBanSao;

if ($laQuanTriVien) {
    $pageTitle = "KIỂM SOÁT BẢN SAO SÁCH";
    $pageSubtitle = "Kiểm soát thông tin & Trạng thái bản sao sách";
} elseif ($vaiTroHienTai === "Thủ thư") {
    $pageTitle = "QUẢN LÝ BẢN SAO SÁCH";
    $pageSubtitle = "Quản lý thông tin & Trạng thái bản sao sách";
} else {
    $pageTitle = "TRA CỨU BẢN SAO SÁCH";
    $pageSubtitle = "Tra cứu tình trạng & Số lượng bản sao sách";
}
?>

<div class="layout">
    <?php
    $activePage = 'bansao';
    require_once __DIR__ . '/../../layout/sidebar.php';
    ?>

    <!-- Sidebar cố định giống kiểu Phiếu mượn: trang cuộn nhưng sidebar không chạy theo -->
    <style>
        .layout {
            display: block !important;
            min-height: 100vh !important;
        }

        .layout > .sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: auto !important;
            width: 260px !important;
            min-width: 260px !important;
            max-width: 260px !important;
            height: 100vh !important;
            min-height: 100vh !important;
            z-index: 999 !important;
        }

        .copy-main {
            margin-left: 260px !important;
            width: calc(100% - 260px) !important;
            min-height: 100vh !important;
            box-sizing: border-box !important;
            overflow: visible !important;
        }

        @media (max-width: 768px) {
            .layout > .sidebar {
                position: relative !important;
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                min-height: auto !important;
            }

            .copy-main {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
    </style>

<main class="copy-main">
        <section class="module-hero">
            <div class="module-hero-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
            </div>

            <div class="module-hero-content">
                <h1 class="module-hero-title"><?= htmlspecialchars($pageTitle); ?></h1>
                <p class="module-hero-subtitle">
                    Nghiệp vụ: <strong><?= htmlspecialchars($pageSubtitle); ?></strong>
                </p>
            </div>
        </section>

        <?php if (!empty($thongBao)): ?>
            <div class="message success js-message"><?= htmlspecialchars($thongBao); ?></div>
        <?php endif; ?>

        <?php if (!empty($thongBaoLoi)): ?>
            <div class="message error js-message"><?= htmlspecialchars($thongBaoLoi); ?></div>
        <?php endif; ?>

        <?php if ($isReaderView): ?>
            <?php
            $tongDauSach = count($danhSachTraCuu);
            $dauSachCon = 0;
            $tongBanCon = 0;
            foreach ($danhSachTraCuu as $itemTraCuu) {
                $soCon = (int)($itemTraCuu['so_ban_con'] ?? 0);
                if ($soCon > 0) {
                    $dauSachCon++;
                }
                $tongBanCon += $soCon;
            }
            ?>

            <div class="reader-summary">
                <div class="summary-card">
                    <div class="summary-number"><?= $tongDauSach; ?></div>
                    <div class="summary-label">Đầu sách đang tra cứu</div>
                </div>
                <div class="summary-card">
                    <div class="summary-number"><?= $dauSachCon; ?></div>
                    <div class="summary-label">Đầu sách còn bản</div>
                </div>
                <div class="summary-card">
                    <div class="summary-number"><?= $tongBanCon; ?></div>
                    <div class="summary-label">Tổng bản có sẵn</div>
                </div>
            </div>

            <section class="panel">
                <div class="panel-head">
                    <div>
                        <h2>Tra cứu tình trạng sách</h2>
                    </div>
                </div>

                <div class="quick-check" aria-label="Kiểm tra nhanh trạng thái bản sao">
                    <select id="quickBookSelect" class="filter-select">
                        <option value="">-- Chọn đầu sách để kiểm tra nhanh --</option>
                        <?php foreach ($danhSachDauSach as $dauSach): ?>
                            <option value="<?= (int)($dauSach['id'] ?? 0); ?>">
                                <?= htmlspecialchars(($dauSach['ma_sach'] ?? '') . ' - ' . ($dauSach['ten_sach'] ?? '')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-primary" id="quickBookCheck">Kiểm tra trạng thái</button>
                    <div id="quickBookResult" class="quick-result" hidden aria-live="polite"></div>
                </div>

                <div class="toolbar">
                    <div class="search-wrap">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input id="readerSearch" class="filter-input" type="text" placeholder="Tìm theo mã sách hoặc tên sách...">
                    </div>
                    <select id="readerStatus" class="filter-select">
                        <option value="all">Tất cả tình trạng</option>
                        <option value="available">Còn bản</option>
                        <option value="none">Hết bản</option>
                    </select>
                </div>

                <div class="table-wrap">
                    <table class="data-table" id="readerTable">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã sách</th>
                                <th>Tên sách</th>
                                <th>Bản sao</th>
                                <th>Tình trạng</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($danhSachTraCuu)): ?>
                            <tr class="no-filter-row">
                                <td colspan="5"><div class="empty-state">Chưa có dữ liệu đầu sách.</div></td>
                            </tr>
                        <?php else: ?>
                            <?php $stt = 1; foreach ($danhSachTraCuu as $sach): ?>
                                <?php
                                $soBanCon = (int)($sach['so_ban_con'] ?? 0);
                                $tongBan = (int)($sach['tong_ban'] ?? 0);
                                $readerState = $soBanCon > 0 ? 'available' : 'none';
                                $searchText = strtolower(($sach['ma_sach'] ?? '') . ' ' . ($sach['ten_sach'] ?? ''));
                                ?>
                                <tr class="filter-row" data-search="<?= htmlspecialchars($searchText, ENT_QUOTES, 'UTF-8'); ?>" data-status="<?= $readerState; ?>">
                                    <td data-label="STT"><?= $stt++; ?></td>
                                    <td data-label="Mã sách"><?= htmlspecialchars($sach['ma_sach'] ?? ''); ?></td>
                                    <td data-label="Tên sách"><?= htmlspecialchars($sach['ten_sach'] ?? ''); ?></td>
                                    <td data-label="Bản sao"><strong><?= $soBanCon; ?></strong> / <?= $tongBan; ?></td>
                                    <td data-label="Tình trạng">
                                        <?php if ($soBanCon > 0): ?>
                                            <span class="badge available">Còn bản</span>
                                        <?php else: ?>
                                            <span class="badge none">Hết bản</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination-bar" id="readerPagination"></div>
            </section>

        <?php else: ?>

            <?php if ($laQuanTriVien): ?>
                <section class="admin-overview">
                    <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                        </div>
                        <div><div class="stat-value"><?= (int)($thongKeBanSao['tong_hoat_dong'] ?? 0); ?></div><div class="stat-label">Đang hoạt động</div></div>
                    </div>
                    <div class="stat-card success">
                        <div class="stat-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
                        <div><div class="stat-value"><?= (int)($thongKeBanSao['co_san'] ?? 0); ?></div><div class="stat-label">Có sẵn</div></div>
                    </div>
                    <div class="stat-card warning">
                        <div class="stat-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle><polyline points="12 7 12 12 15 14"></polyline></svg></div>
                        <div><div class="stat-value"><?= (int)($thongKeBanSao['dang_muon'] ?? 0); ?></div><div class="stat-label">Đang mượn</div></div>
                    </div>
                    <div class="stat-card danger">
                        <div class="stat-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.3 2.7 1.8 17a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 2.7a2 2 0 0 0-3.4 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg></div>
                        <div><div class="stat-value"><?= (int)($thongKeBanSao['chua_co_san'] ?? 0); ?></div><div class="stat-label">Chưa có sẵn</div></div>
                    </div>
                    <div class="stat-card deleted">
                        <div class="stat-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path></svg></div>
                        <div><div class="stat-value"><?= (int)($thongKeBanSao['da_xoa'] ?? 0); ?></div><div class="stat-label">Đã xóa mềm</div></div>
                    </div>
                    </div>
                </section>
            <?php endif; ?>

            
            <?php if ($laQuanTriVien): ?>
                <div class="tabs" role="tablist">
                    <button class="tab-btn active" type="button" data-tab="activeCopies">Tất cả bản sao</button>
                    <button class="tab-btn" type="button" data-tab="deletedCopies">Bản sao đã xóa (<?= count($danhSachBanSaoDaXoa); ?>)</button>
                </div>
            <?php endif; ?>

            <section id="activeCopies" class="panel tab-content active">
                <div class="panel-head">
                    <div>
                        <h2>Danh sách bản sao</h2>
                    </div>
                </div>

                <div class="management-toolbar">
                    <div class="search-wrap management-search">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input id="activeSearch" class="filter-input" type="text" placeholder="Tìm mã bản sao, mã sách, tên sách, vị trí...">
                    </div>

                    <select id="activeStatus" class="filter-select management-status">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="Có sẵn">Có sẵn</option>
                        <option value="Đang mượn">Đang mượn</option>
                        <option value="Chưa có sẵn">Chưa có sẵn</option>
                    </select>

                    <button type="button" class="btn btn-primary management-add-btn" id="openCopyModal">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Thêm bản sao
                    </button>
                </div>

                <div class="table-wrap">
                    <table class="data-table" id="activeTable">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>ID</th>
                                <th>Mã bản sao</th>
                                <th>Mã sách</th>
                                <th>Tên sách</th>
                                <th>Vị trí</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($danhSachBanSao)): ?>
                            <tr class="no-filter-row"><td colspan="8"><div class="empty-state">Chưa có bản sao đang hoạt động.</div></td></tr>
                        <?php else: ?>
                            <?php $stt = 1; foreach ($danhSachBanSao as $banSao): ?>
                                <?php
                                $tt = $banSao['trang_thai'] ?? '';
                                $trangThaiPhieu = (string)($banSao['trang_thai_phieu'] ?? '');
                                $coPhieuDangHieuLuc = in_array($trangThaiPhieu, ['Chờ duyệt', 'Đang mượn', 'Quá hạn'], true);
                                $badgeClass = $tt === 'Có sẵn' ? 'available' : ($tt === 'Đang mượn' ? 'borrowed' : 'unavailable');
                                $searchText = strtolower(($banSao['ma_ban_sao'] ?? '') . ' ' . ($banSao['ma_sach'] ?? '') . ' ' . ($banSao['ten_sach'] ?? '') . ' ' . ($banSao['vi_tri'] ?? '') . ' ' . $trangThaiPhieu);
                                ?>
                                <tr class="filter-row" data-search="<?= htmlspecialchars($searchText, ENT_QUOTES, 'UTF-8'); ?>" data-status="<?= htmlspecialchars($tt, ENT_QUOTES, 'UTF-8'); ?>">
                                    <td data-label="STT"><?= $stt++; ?></td>
                                    <td data-label="ID"><?= (int)$banSao['id']; ?></td>
                                    <td data-label="Mã bản sao"><?= htmlspecialchars($banSao['ma_ban_sao'] ?? ''); ?></td>
                                    <td data-label="Mã sách"><?= htmlspecialchars($banSao['ma_sach'] ?? ''); ?></td>
                                    <td data-label="Tên sách"><?= htmlspecialchars($banSao['ten_sach'] ?? ''); ?></td>
                                    <td data-label="Vị trí"><?= htmlspecialchars($banSao['vi_tri'] ?? ''); ?></td>
                                    <td data-label="Trạng thái">
                                        <span class="badge <?= $badgeClass; ?>"><?= htmlspecialchars($tt); ?></span>
                                        <?php if ($trangThaiPhieu === 'Chờ duyệt'): ?>
                                            <span class="status-note">Phiếu: Chờ duyệt</span>
                                        <?php elseif ($trangThaiPhieu === 'Quá hạn'): ?>
                                            <span class="status-note" style="color:#DC2626;">Phiếu: Quá hạn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Thao tác">
                                        <div class="actions">
                                            <a class="btn-action btn-edit js-confirm-edit" href="index.php?controller=bansao&edit=<?= (int)$banSao['id']; ?>" data-code="<?= htmlspecialchars($banSao['ma_ban_sao'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.8 2.8 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                                Sửa
                                            </a>

                                            <?php if ($laQuanTriVien): ?>
                                                <?php if ($coPhieuDangHieuLuc || $tt === 'Đang mượn'): ?>
                                                    <button class="btn-action btn-disabled" type="button" disabled title="Bản sao đang gắn với phiếu mượn hiệu lực nên không thể xóa.">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                        Xóa
                                                    </button>
                                                <?php else: ?>
                                                    <form class="action-form js-delete-form" method="post" action="index.php?controller=bansao" data-code="<?= htmlspecialchars($banSao['ma_ban_sao'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                        <input type="hidden" name="delete_id" value="<?= (int)$banSao['id']; ?>">
                                                        <button class="btn-action btn-delete js-confirm-delete" type="button">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                            Xóa
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination-bar" id="activePagination"></div>
            </section>

            <?php if ($laQuanTriVien): ?>
                <section id="deletedCopies" class="panel tab-content">
                    <div class="panel-head">
                        <div>
                            <h2>Bản sao đã xóa mềm</h2>
                        </div>
                    </div>

                    <div class="toolbar" style="grid-template-columns:1fr;">
                        <div class="search-wrap">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <input id="deletedSearch" class="filter-input" type="text" placeholder="Tìm trong danh sách đã xóa...">
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="data-table" id="deletedTable">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>ID</th>
                                    <th>Mã bản sao</th>
                                    <th>Mã sách</th>
                                    <th>Tên sách</th>
                                    <th>Vị trí</th>
                                    <th>Ngày xóa</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($danhSachBanSaoDaXoa)): ?>
                                <tr class="no-filter-row"><td colspan="8"><div class="empty-state">Chưa có bản sao nào bị xóa mềm.</div></td></tr>
                            <?php else: ?>
                                <?php $sttXoa = 1; foreach ($danhSachBanSaoDaXoa as $banSaoDaXoa): ?>
                                    <?php $searchDeleted = strtolower(($banSaoDaXoa['ma_ban_sao'] ?? '') . ' ' . ($banSaoDaXoa['ma_sach'] ?? '') . ' ' . ($banSaoDaXoa['ten_sach'] ?? '') . ' ' . ($banSaoDaXoa['vi_tri'] ?? '')); ?>
                                    <tr class="filter-row" data-search="<?= htmlspecialchars($searchDeleted, ENT_QUOTES, 'UTF-8'); ?>">
                                        <td data-label="STT"><?= $sttXoa++; ?></td>
                                        <td data-label="ID"><?= (int)$banSaoDaXoa['id']; ?></td>
                                        <td data-label="Mã bản sao"><?= htmlspecialchars($banSaoDaXoa['ma_ban_sao'] ?? ''); ?></td>
                                        <td data-label="Mã sách"><?= htmlspecialchars($banSaoDaXoa['ma_sach'] ?? ''); ?></td>
                                        <td data-label="Tên sách"><?= htmlspecialchars($banSaoDaXoa['ten_sach'] ?? ''); ?></td>
                                        <td data-label="Vị trí"><?= htmlspecialchars($banSaoDaXoa['vi_tri'] ?? ''); ?></td>
                                        <td data-label="Ngày xóa"><span class="badge deleted"><?= htmlspecialchars($banSaoDaXoa['deleted_at'] ?? ''); ?></span></td>
                                        <td data-label="Thao tác">
                                            <form class="action-form js-restore-form" method="post" action="index.php?controller=bansao" data-code="<?= htmlspecialchars($banSaoDaXoa['ma_ban_sao'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="action" value="restore">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="restore_id" value="<?= (int)$banSaoDaXoa['id']; ?>">
                                                <button class="btn-action btn-restore js-confirm-restore" type="button">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.5 15a9 9 0 1 0 2.1-9.4L1 10"></path></svg>
                                                    Khôi phục
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-bar" id="deletedPagination"></div>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</div>


<div class="modal-overlay" id="copyModal" aria-hidden="true">
    <div class="copy-modal-box" role="dialog" aria-modal="true" aria-labelledby="copyModalTitle">
        <div class="copy-modal-head">
            <h3 id="copyModalTitle"><?= !empty($editId) ? 'Cập nhật bản sao' : 'Thêm bản sao mới'; ?></h3>
            <button type="button" class="copy-modal-close" id="closeCopyModal" aria-label="Đóng">&times;</button>
        </div>

        <div class="copy-modal-body">
<form method="post" action="index.php?controller=bansao" class="form-grid" id="copyForm">
                    <input type="hidden" name="action" value="<?= !empty($editId) ? 'update' : 'add'; ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="edit_id" value="<?= htmlspecialchars($editId ?? ''); ?>">

                    <div class="field">
                        <label for="book_id">Đầu sách</label>
                        <?php
                        $coPhieuHieuLucKhiSua = !empty($editId) && in_array(
                            (string)$trangThaiPhieuDangSua,
                            ['Chờ duyệt', 'Đang mượn', 'Quá hạn'],
                            true
                        );
                        $khoaNghiepVuKhiSua = !empty($editId) && ($coPhieuHieuLucKhiSua || (($trangThai ?? '') === 'Đang mượn'));
                        ?>
                        <?php if ($khoaNghiepVuKhiSua): ?>
                            <input type="hidden" name="book_id" value="<?= htmlspecialchars($bookId ?? ''); ?>">
                            <div class="field-locked" aria-disabled="true">
                                <?php
                                $tenDauSachDangSua = '';
                                foreach ($danhSachDauSach as $dauSach) {
                                    if ((string)($bookId ?? '') === (string)($dauSach['id'] ?? '')) {
                                        $tenDauSachDangSua = ($dauSach['ma_sach'] ?? '') . ' - ' . ($dauSach['ten_sach'] ?? '');
                                        break;
                                    }
                                }
                                ?>
                                🔒 <?= htmlspecialchars($tenDauSachDangSua !== '' ? $tenDauSachDangSua : ('ID đầu sách: ' . ($bookId ?? ''))); ?>
                            </div>
                            <p class="field-note warning">
                                Bản sao đang gắn với phiếu <?= htmlspecialchars($trangThaiPhieuDangSua !== '' ? $trangThaiPhieuDangSua : 'Đang mượn'); ?> nên không thể đổi sang đầu sách khác.
                            </p>
                        <?php else: ?>
                            <select id="book_id" name="book_id" class="<?= !empty($loiBookId) ? 'input-error' : ''; ?>">
                                <option value="">-- Chọn đầu sách --</option>
                                <?php foreach ($danhSachDauSach as $dauSach): ?>
                                    <option value="<?= (int)$dauSach['id']; ?>" <?= ((string)($bookId ?? '') === (string)$dauSach['id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars(($dauSach['ma_sach'] ?? '') . ' - ' . ($dauSach['ten_sach'] ?? '')); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                        <?php if (!empty($loiBookId)): ?><p class="field-error"><?= htmlspecialchars($loiBookId); ?></p><?php endif; ?>
                    </div>

                    <div class="field">
                        <label for="ma_ban_sao">Mã bản sao</label>
                        <input id="ma_ban_sao" name="ma_ban_sao" type="text" placeholder="Ví dụ: BS005" value="<?= htmlspecialchars($maBanSao ?? ''); ?>" class="<?= !empty($loiMaBanSao) ? 'input-error' : ''; ?>">
                        <?php if (!empty($loiMaBanSao)): ?><p class="field-error"><?= htmlspecialchars($loiMaBanSao); ?></p><?php endif; ?>
                    </div>

                    <div class="field">
                        <label for="vi_tri">Vị trí</label>
                        <input id="vi_tri" name="vi_tri" type="text" placeholder="Ví dụ: Kệ A1" value="<?= htmlspecialchars($viTri ?? ''); ?>" class="<?= !empty($loiViTri) ? 'input-error' : ''; ?>">
                        <?php if (!empty($loiViTri)): ?><p class="field-error"><?= htmlspecialchars($loiViTri); ?></p><?php endif; ?>
                    </div>

                    <div class="field">
                        <label for="trang_thai">Trạng thái</label>
                        <?php if ($khoaNghiepVuKhiSua): ?>
                            <input type="hidden" name="trang_thai" value="<?= htmlspecialchars($trangThai ?? ''); ?>">
                            <div class="field-locked" aria-disabled="true">
                                🔒 <?= htmlspecialchars($trangThai ?? ''); ?>
                                <?php if ($trangThaiPhieuDangSua !== ''): ?>
                                    — Phiếu: <?= htmlspecialchars($trangThaiPhieuDangSua); ?>
                                <?php endif; ?>
                            </div>
                            <p class="field-note warning">Đầu sách và trạng thái đang do Phiếu mượn kiểm soát. Chỉ mã bản sao và vị trí còn được sửa.</p>
                        <?php else: ?>
                            <select id="trang_thai" name="trang_thai" class="<?= !empty($loiTrangThai) ? 'input-error' : ''; ?>">
                                <option value="Có sẵn" <?= (($trangThai ?? '') === 'Có sẵn') ? 'selected' : ''; ?>>Có sẵn</option>
                                <option value="Chưa có sẵn" <?= (($trangThai ?? '') === 'Chưa có sẵn') ? 'selected' : ''; ?>>Chưa có sẵn</option>
                            </select>
                            <p class="field-note">“Đang mượn” không nhập thủ công; trạng thái này được cập nhật tự động khi Thủ thư duyệt Phiếu mượn.</p>
                        <?php endif; ?>
                        <?php if (!empty($loiTrangThai)): ?><p class="field-error"><?= htmlspecialchars($loiTrangThai); ?></p><?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <?php if (!empty($editId)): ?>
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline>
                                <?php else: ?>
                                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                                <?php endif; ?>
                            </svg>
                            <?= !empty($editId) ? 'Lưu thay đổi' : 'Lưu bản sao'; ?>
                        </button>
                        <?php if (!empty($editId)): ?>
                            <a class="btn btn-secondary" href="index.php?controller=bansao">Hủy sửa</a>
                        <?php endif; ?>
                    </div>
                </form>
        </div>
    </div>
</div>

<div class="modal-overlay" id="confirmModal" aria-hidden="true">
    <div class="modal-box">
        <div class="modal-icon" id="modalIcon">
            <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        </div>
        <h3 id="modalTitle">Xác nhận thao tác</h3>
        <p id="modalText"></p>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" id="modalCancel">Hủy</button>
            <button type="button" class="btn btn-primary modal-confirm" id="modalConfirm">Xác nhận</button>
        </div>
    </div>
</div>

<script>
(function () {
    const normalize = (value) => (value || '').toString().trim().toLowerCase();

    function setupFilterAndPagination(tableId, searchId, statusId, paginationId, pageSize = 10) {
        const table = document.getElementById(tableId);
        const search = document.getElementById(searchId);
        const status = statusId ? document.getElementById(statusId) : null;
        const pagination = document.getElementById(paginationId);
        if (!table || !search || !pagination) return;

        const rows = Array.from(table.querySelectorAll('tbody tr.filter-row'));
        let currentPage = 1;

        const render = () => {
            const q = normalize(search.value);
            const statusValue = status ? status.value : 'all';

            const matchedRows = rows.filter((row) => {
                const haystack = normalize(row.dataset.search);
                const rowStatus = row.dataset.status || '';
                const matchSearch = q === '' || haystack.includes(q);
                const matchStatus = statusValue === 'all' || rowStatus === statusValue;
                return matchSearch && matchStatus;
            });

            const totalRows = matchedRows.length;
            const totalPages = Math.max(1, Math.ceil(totalRows / pageSize));
            if (currentPage > totalPages) currentPage = totalPages;

            rows.forEach((row) => {
                row.style.display = 'none';
            });

            const start = (currentPage - 1) * pageSize;
            const end = start + pageSize;
            matchedRows.slice(start, end).forEach((row) => {
                row.style.display = '';
            });

            pagination.innerHTML = '';

            if (rows.length === 0) {
                pagination.style.display = 'none';
                return;
            }

            pagination.style.display = 'flex';

            const info = document.createElement('div');
            info.className = 'pagination-info';
            if (totalRows === 0) {
                info.textContent = 'Không có kết quả phù hợp';
            } else {
                const first = start + 1;
                const last = Math.min(end, totalRows);
                info.textContent = `Hiển thị ${first}-${last} / ${totalRows} kết quả`;
            }

            const actions = document.createElement('div');
            actions.className = 'pagination-actions';

            const prev = document.createElement('button');
            prev.type = 'button';
            prev.className = 'page-btn';
            prev.textContent = 'Trước';
            prev.disabled = currentPage <= 1 || totalRows === 0;
            prev.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    render();
                }
            });

            const page = document.createElement('span');
            page.className = 'page-current';
            page.textContent = totalRows === 0 ? 'Trang 0/0' : `Trang ${currentPage}/${totalPages}`;

            const next = document.createElement('button');
            next.type = 'button';
            next.className = 'page-btn';
            next.textContent = 'Sau';
            next.disabled = currentPage >= totalPages || totalRows === 0;
            next.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    render();
                }
            });

            actions.append(prev, page, next);
            pagination.append(info, actions);
        };

        search.addEventListener('input', () => {
            currentPage = 1;
            render();
        });

        if (status) {
            status.addEventListener('change', () => {
                currentPage = 1;
                render();
            });
        }

        render();
    }

    setupFilterAndPagination('readerTable', 'readerSearch', 'readerStatus', 'readerPagination', 10);
    setupFilterAndPagination('activeTable', 'activeSearch', 'activeStatus', 'activePagination', 10);
    setupFilterAndPagination('deletedTable', 'deletedSearch', null, 'deletedPagination', 10);


    // ===== JSON ENDPOINT + FETCH API: kiểm tra nhanh trạng thái bản sao =====
    const quickBookSelect = document.getElementById('quickBookSelect');
    const quickBookCheck = document.getElementById('quickBookCheck');
    const quickBookResult = document.getElementById('quickBookResult');

    async function fetchQuickBookStatus() {
        if (!quickBookSelect || !quickBookCheck || !quickBookResult) return;

        const bookId = quickBookSelect.value;
        quickBookResult.hidden = false;
        quickBookResult.className = 'quick-result';

        if (!bookId) {
            quickBookResult.classList.add('warning');
            quickBookResult.textContent = 'Vui lòng chọn một đầu sách để kiểm tra.';
            return;
        }

        const oldText = quickBookCheck.textContent;
        quickBookCheck.disabled = true;
        quickBookCheck.textContent = 'Đang kiểm tra...';
        quickBookResult.textContent = 'Đang lấy trạng thái mới nhất...';

        try {
            const response = await fetch(
                `index.php?controller=bansao&action=apiTrangThai&book_id=${encodeURIComponent(bookId)}`,
                { headers: { 'Accept': 'application/json' } }
            );

            let data = null;
            try {
                data = await response.json();
            } catch (parseError) {
                throw new Error('Phản hồi từ máy chủ không phải JSON hợp lệ.');
            }

            if (!response.ok || !data?.ok) {
                throw new Error(data?.message || 'Không thể kiểm tra trạng thái bản sao.');
            }

            quickBookResult.className = 'quick-result ' + (data.co_the_muon ? 'success' : 'warning');
            quickBookResult.textContent =
                `${data.ma_sach} - ${data.ten_sach}\n` +
                `Trạng thái: ${data.trang_thai}\n` +
                `Có thể mượn: ${data.so_ban_con}/${data.tong_ban} bản | ` +
                `Đang mượn: ${data.so_ban_dang_muon} | ` +
                `Chưa có sẵn/đang giữ: ${data.so_ban_chua_co_san}\n` +
                `${data.message}`;
        } catch (error) {
            quickBookResult.className = 'quick-result error';
            quickBookResult.textContent = error?.message || 'Có lỗi khi kiểm tra trạng thái.';
        } finally {
            quickBookCheck.disabled = false;
            quickBookCheck.textContent = oldText;
        }
    }

    quickBookCheck?.addEventListener('click', fetchQuickBookStatus);
    quickBookSelect?.addEventListener('change', () => {
        if (quickBookSelect.value) fetchQuickBookStatus();
    });

    const copyModal = document.getElementById('copyModal');
    const openCopyModalButton = document.getElementById('openCopyModal');
    const closeCopyModalButton = document.getElementById('closeCopyModal');

    function openCopyFormModal() {
        copyModal?.classList.add('show');
        copyModal?.setAttribute('aria-hidden', 'false');
    }

    function closeCopyFormModal() {
        copyModal?.classList.remove('show');
        copyModal?.setAttribute('aria-hidden', 'true');

        // Nếu đang ở chế độ sửa thì đóng popup sẽ trở về danh sách bình thường.
        const editing = <?= !empty($editId) ? 'true' : 'false'; ?>;
        if (editing) {
            window.location.href = 'index.php?controller=bansao';
        }
    }

    openCopyModalButton?.addEventListener('click', openCopyFormModal);
    closeCopyModalButton?.addEventListener('click', closeCopyFormModal);

    copyModal?.addEventListener('click', (event) => {
        if (event.target === copyModal) closeCopyFormModal();
    });

    const shouldOpenCopyModal =
        <?= (!empty($editId) || !empty($loiBookId) || !empty($loiMaBanSao) || !empty($loiViTri) || !empty($loiTrangThai)) ? 'true' : 'false'; ?>;

    if (shouldOpenCopyModal) {
        requestAnimationFrame(openCopyFormModal);
    }

    document.querySelectorAll('.tab-btn').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach((item) => item.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach((item) => item.classList.remove('active'));
            button.classList.add('active');
            const target = document.getElementById(button.dataset.tab);
            if (target) target.classList.add('active');
        });
    });

    const modal = document.getElementById('confirmModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalText = document.getElementById('modalText');
    const modalConfirm = document.getElementById('modalConfirm');
    const modalCancel = document.getElementById('modalCancel');
    let pendingAction = null;

    function openConfirm(type, code, action) {
        pendingAction = action;
        modalConfirm.classList.remove('delete', 'restore');

        if (type === 'delete') {
            modalTitle.textContent = 'Xác nhận xóa mềm';
            modalText.textContent = `Bản sao ${code} sẽ được ẩn khỏi danh sách hoạt động nhưng dữ liệu vẫn được giữ để có thể khôi phục.`;
            modalConfirm.textContent = 'Có, xóa';
            modalConfirm.classList.add('delete');
        } else if (type === 'restore') {
            modalTitle.textContent = 'Xác nhận khôi phục';
            modalText.textContent = `Khôi phục bản sao ${code} trở lại danh sách đang hoạt động?`;
            modalConfirm.textContent = 'Khôi phục';
            modalConfirm.classList.add('restore');
        } else if (type === 'add') {
            modalTitle.textContent = 'Xác nhận thêm bản sao';
            modalText.textContent = `Bạn có chắc chắn muốn thêm bản sao ${code} vào hệ thống?`;
            modalConfirm.textContent = 'Có, thêm';
        } else {
            modalTitle.textContent = 'Xác nhận sửa';
            modalText.textContent = `Mở thông tin bản sao ${code} để chỉnh sửa?`;
            modalConfirm.textContent = 'Có, sửa';
        }

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeConfirm() {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        pendingAction = null;
    }

    const copyForm = document.getElementById('copyForm');
    copyForm?.addEventListener('submit', (event) => {
        const actionValue = copyForm.querySelector('input[name="action"]')?.value || '';

        // Chỉ hỏi xác nhận khi THÊM mới; cập nhật giữ nguyên luồng hiện tại.
        if (actionValue !== 'add') return;

        event.preventDefault();

        const code = copyForm.querySelector('input[name="ma_ban_sao"]')?.value.trim() || '';
        openConfirm('add', code, () => copyForm.submit());
    });

    document.querySelectorAll('.js-confirm-edit').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const href = link.getAttribute('href');
            openConfirm('edit', link.dataset.code || '', () => { window.location.href = href; });
        });
    });

    document.querySelectorAll('.js-confirm-delete').forEach((button) => {
        button.addEventListener('click', () => {
            const form = button.closest('.js-delete-form');
            openConfirm('delete', form?.dataset.code || '', () => form?.submit());
        });
    });

    document.querySelectorAll('.js-confirm-restore').forEach((button) => {
        button.addEventListener('click', () => {
            const form = button.closest('.js-restore-form');
            openConfirm('restore', form?.dataset.code || '', () => form?.submit());
        });
    });

    modalCancel?.addEventListener('click', closeConfirm);
    modal?.addEventListener('click', (event) => {
        if (event.target === modal) closeConfirm();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;

        if (modal?.classList.contains('show')) {
            closeConfirm();
            return;
        }

        if (copyModal?.classList.contains('show')) {
            closeCopyFormModal();
        }
    });
    modalConfirm?.addEventListener('click', () => {
        if (typeof pendingAction === 'function') {
            const action = pendingAction;
            closeConfirm();
            action();
        }
    });

    setTimeout(() => {
        document.querySelectorAll('.js-message').forEach((item) => {
            item.classList.add('hide');
            setTimeout(() => item.remove(), 400);
        });
    }, 3200);
})();
</script>
</body>
</html>
