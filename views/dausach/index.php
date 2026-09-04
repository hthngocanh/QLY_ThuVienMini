<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý đầu sách</title>

    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family:
                "Inter",
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                sans-serif;

            background: #F8FAFC;
            color: #334155;
            font-size: 15px;
            line-height: 1.5;
        }

        body.modal-open {
            overflow: hidden;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
        }

        .layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .main-content {
    flex: 1 1 auto;
    min-width: 0;
    width: auto;
    padding: 32px 40px 40px;
    overflow-x: hidden;
    overflow-y: auto;
    background: #F8FAFC;
    box-sizing: border-box;
}
        .page-container {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    min-width: 0;
    box-sizing: border-box;
}
.page-container,
.list-card,
.table-wrapper {
    min-width: 0;
    max-width: 100%;
}
        .page-header {
            display: flex;
            align-items: center;
            width: 100%;
            min-height: 124px;
            padding: 26px 32px;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
        }

        .page-header-content {
            min-width: 0;
        }

        .page-title {
            margin: 0 0 6px;
            color: #0F172A;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.25;
        }

        .page-subtitle {
            margin: 0;
            color: #64748B;
            font-size: 15px;
        }

        .btn {
            height: 42px;
            padding: 0 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            background: #FFFFFF;
            color: #334155;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            transition:
                background-color 0.15s ease,
                border-color 0.15s ease,
                color 0.15s ease,
                transform 0.15s ease;
        }

        .btn:hover {
            background: #EFF6FF;
            border-color: #BFDBFE;
            color: #2563EB;
        }

        .btn-primary {
            background: #2563EB;
            border-color: #2563EB;
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background: #1E3A8A;
            border-color: #1E3A8A;
            color: #FFFFFF;
        }

        .btn svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .alert {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            padding: 12px 16px;
            border: 1px solid #DCFCE7;
            border-radius: 8px;
            background: #F0FDF4;
            color: #16A34A;
            font-size: 13px;
            font-weight: 500;
        }

        .alert svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .form-card,
.search-card,
.list-card {
    width: 100%;
    max-width: 100%;
    min-width: 0;
    box-sizing: border-box;

    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);

    overflow: hidden;
}

        .form-card,
        .search-card {
            margin-bottom: 24px;
        }

        .card-header {
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            border-bottom: 1px solid #E2E8F0;
        }

        .card-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header-icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 8px;
            color: #2563EB;
        }

        .card-header-icon svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .card-title {
            margin: 0;
            color: #0F172A;
            font-size: 18px;
            font-weight: 700;
        }

        .card-body {
            padding: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            column-gap: 20px;
            row-gap: 0;
        }

        .form-group {
            min-width: 0;
            margin-bottom: 18px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
        }

        .form-control {
            display: block;
            width: 100%;
            height: 40px;
            padding: 8px 13px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            background: #FFFFFF;
            color: #334155;
            font-size: 15px;
            transition:
                border-color 0.15s ease,
                box-shadow 0.15s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #BFDBFE;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        textarea.form-control {
            height: auto;
            min-height: 90px;
            resize: vertical;
        }

        .form-control.input-error {
            border-color: #DC2626 !important;
            background: #FFFDFD;
        }

        .form-control.input-error:focus {
            border-color: #DC2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.10);
        }

        .form-error {
            display: block;
            margin-top: 4px;
            color: #DC2626;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.4;
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
            padding-top: 18px;
            border-top: 1px solid #E2E8F0;
        }

        .search-body {
            padding: 24px;
        }

        .search-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .search-top-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-grid {
            display: grid;
            grid-template-columns:
                minmax(260px, 2fr)
                minmax(150px, 1fr)
                minmax(180px, 1.15fr)
                minmax(140px, 0.9fr)
                auto
                auto;
            gap: 10px;
            align-items: end;
        }

        .search-field {
            min-width: 0;
        }

        .search-label {
            display: block;
            margin-bottom: 6px;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
        }

        .search-input-wrap {
            position: relative;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            width: 18px;
            height: 18px;
            transform: translateY(-50%);
            color: #64748B;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            height: 40px;
            padding: 8px 13px 8px 42px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            background: #FFFFFF;
            color: #334155;
            font-size: 15px;
        }

        .search-input:focus {
            outline: none;
            border-color: #BFDBFE;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .search-control {
            width: 100%;
            height: 40px;
            padding: 8px 13px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            background: #FFFFFF;
            color: #334155;
            font-size: 15px;
        }

        .search-control:focus {
            outline: none;
            border-color: #BFDBFE;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .search-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(15, 23, 42, 0.48);
            backdrop-filter: blur(2px);
        }

        .modal-overlay.show {
            display: flex;
        }

        .book-modal {
            width: min(900px, 100%);
            max-height: calc(100vh - 48px);
            display: flex;
            flex-direction: column;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
            overflow: hidden;
            animation: modalShow 0.18s ease-out;
        }

        @keyframes modalShow {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.985);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border-bottom: 1px solid #E2E8F0;
        }

        .modal-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-close {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            background: #FFFFFF;
            color: #64748B;
            cursor: pointer;
        }

        .modal-close:hover {
            background: #FEF2F2;
            border-color: #FCA5A5;
            color: #DC2626;
        }

        .modal-close svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
        }

        .list-header {
            min-height: 66px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 24px;
            border-bottom: 1px solid #E2E8F0;
        }

        .list-title-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .list-title-icon {
            width: 22px;
            height: 22px;
            color: #334155;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .list-title {
            margin: 0;
            color: #0F172A;
            font-size: 18px;
            font-weight: 700;
        }

        .book-count {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748B;
            font-size: 13px;
            font-weight: 500;
        }

        .book-count svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

.table-wrapper {
    width: 100%;
    max-width: 100%;
    min-width: 0;
    overflow-x: auto;
    overflow-y: hidden;
    box-sizing: border-box;
}

.book-table {
    width: max-content;
    min-width: 1200px;
    max-width: none;

    border-collapse: collapse;
    table-layout: fixed;

    color: #334155;
    font-size: 15px;
}

       .book-table th,
.book-table td {
    padding: 12px;
    border-bottom: 1px solid #E2E8F0;
    vertical-align: middle;
    box-sizing: border-box;
}

.book-table th {
    background: #F8FAFC;
    color: #0F172A;
    font-size: 14px;
    font-weight: 600;
    text-align: left;
    white-space: nowrap;
}

.book-table td {
    line-height: 1.5;
    overflow-wrap: anywhere;
    word-break: break-word;
}

        .book-table tbody tr:hover {
            background: #F8FAFC;
        }

        .book-table tbody tr:last-child td {
            border-bottom: 0;
        }

.col-stt {
    width: 48px;
}

.col-ma {
    width: 82px;
}

.col-ten {
    width: 125px;
}

.col-ma-tg {
    width: 90px;
}

.col-tg {
    width: 115px;
}

.col-danh-muc {
    width: 100px;
}

.col-nxb {
    width: 125px;
}

.col-nam {
    width: 75px;
}

.col-isbn {
    width: 115px;
}

.col-gia {
    width: 95px;
}

.col-mo-ta {
    width: 180px;
}

.col-trang-thai {
    width: 115px;
}

.col-thao-tac {
    width: 100px;
}

        .text-center {
            text-align: center !important;
        }

        .book-code,
        .book-name {
            color: #0F172A !important;
            font-weight: 600;
        }

        .description-cell {
    color: #64748B !important;
    white-space: normal;
    overflow-wrap: anywhere;
    word-break: break-word;
}

        .price-cell {
            text-align: right !important;
            font-weight: 500;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 20px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-active {
            background: #F0FDF4;
            color: #16A34A;
            border: 1px solid #DCFCE7;
        }

        .status-active .status-dot {
            background: #16A34A;
        }

        .status-inactive {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FEE2E2;
        }

        .status-inactive .status-dot {
            background: #DC2626;
        }

        .action-form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            background: #FFFFFF;
            cursor: pointer;
        }

        .action-btn svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .action-edit {
            color: #B45309;
            border-color: #FCD34D;
        }

        .action-edit:hover {
            background: #FFFBEB;
            border-color: #F59E0B;
        }

        .action-delete {
            color: #DC2626;
            border-color: #FCA5A5;
        }

        .action-delete:hover {
            background: #FEF2F2;
            border-color: #DC2626;
        }

        .empty-state {
            padding: 42px 20px !important;
            color: #64748B !important;
            text-align: center !important;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 20px 24px;
        }

        .pagination-link {
            min-width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            background: #FFFFFF;
            color: #334155;
            font-size: 13px;
            text-decoration: none;
        }

        .pagination-link:hover {
            background: #EFF6FF;
            border-color: #BFDBFE;
            color: #2563EB;
        }

        .pagination-link.active {
            background: #2563EB;
            border-color: #2563EB;
            color: #FFFFFF;
        }

        @media (max-width: 1100px) {
            .search-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .search-actions {
                grid-column: 1 / -1;
            }

            .search-actions .btn {
                flex: 1;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }

            .page-header {
                min-height: auto;
                padding: 20px;
            }

            .page-title {
                font-size: 24px;
            }

            .search-top {
                flex-direction: column;
                align-items: stretch;
            }

            .search-top .btn {
                width: 100%;
            }

            .search-grid {
                grid-template-columns: 1fr;
            }

            .search-actions {
                grid-column: auto;
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: auto;
            }

            .form-actions {
                grid-column: auto;
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .form-actions .btn {
                width: 100%;
            }

            .modal-overlay {
                padding: 12px;
            }

            .book-modal {
                max-height: calc(100vh - 24px);
            }

            .modal-body {
                padding: 18px;
            }
        }

        @media (max-width: 480px) {
            .search-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    <?php
    $activePage = 'dausach';
    require_once __DIR__ . '/../../layout/sidebar.php';
    ?>

    <main class="main-content">

        <div class="page-container">

            <!-- HEADER -->

            <section class="page-header">

                <div class="page-header-content">

                    <h1 class="page-title">
                        Quản lý đầu sách
                    </h1>

                    <p class="page-subtitle">
                        Quản lý thông tin các đầu sách trong thư viện
                    </p>

                </div>

            </section>

<?php if (!empty($thong_bao)) { ?>

    <div class="alert" id="success-alert">

        <svg viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="9"></circle>
            <path d="M12 8v4"></path>
            <path d="M12 16h.01"></path>
        </svg>

        <span>
            <?php echo htmlspecialchars($thong_bao); ?>
        </span>

    </div>

<?php } ?>


            <!-- FORM SỬA -->

            <?php if (!empty($vi_tri_sua)) { ?>

                <section class="form-card" id="form-dau-sach">

                    <div class="card-header">

                        <div class="card-header-left">

                            <div class="card-header-icon">

                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z"></path>
                                    <path d="M4 5.5V20"></path>
                                </svg>

                            </div>

                            <h2 class="card-title">
                                Cập nhật đầu sách
                            </h2>

                        </div>

                    </div>

                    <div class="card-body">

                        <form method="POST" action="index.php?controller=dausach">

                            <div class="form-grid">

                                <div class="form-group">

                                    <label class="form-label">
                                        Mã sách
                                    </label>

                                    <input
                                        type="text"
                                        name="ma_sach"
                                        class="form-control <?php echo isset($loi["ma_sach"]) ? 'input-error' : ''; ?>"
                                        value="<?php echo htmlspecialchars($ma_sach ?? ''); ?>"
                                    >

                                    <?php if (isset($loi["ma_sach"])) { ?>
                                        <span class="form-error">
                                            <?php echo htmlspecialchars($loi["ma_sach"]); ?>
                                        </span>
                                    <?php } ?>

                                </div>


                                <div class="form-group">

                                    <label class="form-label">
                                        Tên sách
                                    </label>

                                    <input
                                        type="text"
                                        name="ten_sach"
                                        class="form-control <?php echo isset($loi["ten_sach"]) ? 'input-error' : ''; ?>"
                                        value="<?php echo htmlspecialchars($ten_sach ?? ''); ?>"
                                    >

                                    <?php if (isset($loi["ten_sach"])) { ?>
                                        <span class="form-error">
                                            <?php echo htmlspecialchars($loi["ten_sach"]); ?>
                                        </span>
                                    <?php } ?>

                                </div>


                                <div class="form-group">

                                    <label class="form-label">
                                        Mã tác giả
                                    </label>

                                    <input
                                        type="text"
                                        name="ma_tac_gia"
                                        class="form-control <?php echo isset($loi["ma_tac_gia"]) ? 'input-error' : ''; ?>"
                                        value="<?php echo htmlspecialchars($ma_tac_gia ?? ''); ?>"
                                    >

                                    <?php if (isset($loi["ma_tac_gia"])) { ?>
                                        <span class="form-error">
                                            <?php echo htmlspecialchars($loi["ma_tac_gia"]); ?>
                                        </span>
                                    <?php } ?>

                                </div>


                                <div class="form-group">

                                    <label class="form-label">
                                        Tác giả
                                    </label>

                                    <input
                                        type="text"
                                        name="tac_gia"
                                        class="form-control <?php echo isset($loi["tac_gia"]) ? 'input-error' : ''; ?>"
                                        value="<?php echo htmlspecialchars($tac_gia ?? ''); ?>"
                                    >

                                    <?php if (isset($loi["tac_gia"])) { ?>
                                        <span class="form-error">
                                            <?php echo htmlspecialchars($loi["tac_gia"]); ?>
                                        </span>
                                    <?php } ?>

                                </div>


                                <div class="form-group">

                                    <label class="form-label">
                                        Danh mục
                                    </label>

                                    <select
                                        name="danh_muc"
                                        class="form-control <?php echo isset($loi["danh_muc"]) ? 'input-error' : ''; ?>"
                                    >

                                        <option value="">
                                            -- Chọn danh mục --
                                        </option>

                                        <?php foreach ($danh_sach_danh_muc as $danh_muc_item) { ?>

                                            <option
                                                value="<?php echo htmlspecialchars($danh_muc_item["ten_danh_muc"]); ?>"
                                                <?php
                                                if (($danh_muc ?? '') == $danh_muc_item["ten_danh_muc"]) {
                                                    echo "selected";
                                                }
                                                ?>
                                            >
                                                <?php echo htmlspecialchars($danh_muc_item["ten_danh_muc"]); ?>
                                            </option>

                                        <?php } ?>

                                    </select>

                                    <?php if (isset($loi["danh_muc"])) { ?>
                                        <span class="form-error">
                                            <?php echo htmlspecialchars($loi["danh_muc"]); ?>
                                        </span>
                                    <?php } ?>

                                </div>


                                <div class="form-group">

                                    <label class="form-label">
                                        Nhà xuất bản
                                    </label>

                                    <input
                                        type="text"
                                        name="nha_xuat_ban"
                                        class="form-control <?php echo isset($loi["nha_xuat_ban"]) ? 'input-error' : ''; ?>"
                                        value="<?php echo htmlspecialchars($nha_xuat_ban ?? ''); ?>"
                                    >

                                    <?php if (isset($loi["nha_xuat_ban"])) { ?>
                                        <span class="form-error">
                                            <?php echo htmlspecialchars($loi["nha_xuat_ban"]); ?>
                                        </span>
                                    <?php } ?>

                                </div>


                                <div class="form-group">

                                    <label class="form-label">
                                        Năm xuất bản
                                    </label>

                                    <input
                                        type="number"
                                        name="nam_xuat_ban"
                                        class="form-control <?php echo isset($loi["nam_xuat_ban"]) ? 'input-error' : ''; ?>"
                                        value="<?php echo htmlspecialchars($nam_xuat_ban ?? ''); ?>"
                                    >

                                    <?php if (isset($loi["nam_xuat_ban"])) { ?>
                                        <span class="form-error">
                                            <?php echo htmlspecialchars($loi["nam_xuat_ban"]); ?>
                                        </span>
                                    <?php } ?>

                                </div>


                                <div class="form-group">

                                    <label class="form-label">
                                        ISBN
                                    </label>

                                    <input
                                        type="text"
                                        name="isbn"
                                        class="form-control <?php echo isset($loi["isbn"]) ? 'input-error' : ''; ?>"
                                        value="<?php echo htmlspecialchars($isbn ?? ''); ?>"
                                    >

                                    <?php if (isset($loi["isbn"])) { ?>
                                        <span class="form-error">
                                            <?php echo htmlspecialchars($loi["isbn"]); ?>
                                        </span>
                                    <?php } ?>

                                </div>


                                <div class="form-group">

                                    <label class="form-label">
                                        Giá sách (VNĐ)
                                    </label>

                                    <input
                                        type="number"
                                        name="gia_sach"
                                        min="1"
                                        class="form-control <?php echo isset($loi["gia_sach"]) ? 'input-error' : ''; ?>"
                                        value="<?php echo htmlspecialchars($gia_sach ?? ''); ?>"
                                    >

                                    <?php if (isset($loi["gia_sach"])) { ?>
                                        <span class="form-error">
                                            <?php echo htmlspecialchars($loi["gia_sach"]); ?>
                                        </span>
                                    <?php } ?>

                                </div>


                                <div class="form-group full-width">

                                    <label class="form-label">
                                        Mô tả
                                    </label>

                                    <textarea
                                        name="mo_ta"
                                        class="form-control <?php echo isset($loi["mo_ta"]) ? 'input-error' : ''; ?>"
                                    ><?php echo htmlspecialchars($mo_ta ?? ''); ?></textarea>

                                    <?php if (isset($loi["mo_ta"])) { ?>
                                        <span class="form-error">
                                            <?php echo htmlspecialchars($loi["mo_ta"]); ?>
                                        </span>
                                    <?php } ?>

                                </div>


                                <div class="form-actions">

                                    <a
                                        href="index.php?controller=dausach"
                                        class="btn"
                                    >
                                        Hủy
                                    </a>

                                    <input
                                        type="hidden"
                                        name="id_sua"
                                        value="<?php echo $vi_tri_sua; ?>"
                                    >

                                    <button
                                        type="submit"
                                        name="cap_nhat_sach"
                                        class="btn btn-primary"
                                    >

                                        <svg viewBox="0 0 24 24" fill="none">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>

                                        Cập nhật sách

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </section>

            <?php } ?>


            <!-- TÌM KIẾM -->

            <section class="search-card">

                <div class="search-body">

                    <div class="search-top">

                        <div class="search-top-left">

                            <div class="card-header-icon">

                                <svg viewBox="0 0 24 24" fill="none">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path d="m20 20-4-4"></path>
                                </svg>

                            </div>

                            <h2 class="card-title">
                                Tìm kiếm đầu sách
                            </h2>

                        </div>


                        <button
                            type="button"
                            class="btn btn-primary"
                            id="btn-open-add"
                        >

                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>

                            Thêm đầu sách

                        </button>

                    </div>


                    <form
                        method="GET"
                        action="index.php"
                        class="search-grid"
                    >

                        <input
                            type="hidden"
                            name="controller"
                            value="dausach"
                        >


                        <div class="search-field">

                            <label class="search-label">
                                Tìm kiếm
                            </label>

                            <div class="search-input-wrap">

                                <svg
                                    class="search-icon"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path d="m20 20-4-4"></path>
                                </svg>

                                <input
                                    type="text"
                                    name="tu_khoa"
                                    class="search-input"
                                    placeholder="Mã sách, tên sách, tác giả, NXB..."
                                    value="<?php echo htmlspecialchars($tu_khoa ?? ''); ?>"
                                >

                            </div>

                        </div>


                        <div class="search-field">

                            <label class="search-label">
                                Tác giả
                            </label>

                            <input
                                type="text"
                                name="loc_tac_gia"
                                class="search-control"
                                placeholder="Tên tác giả"
                                value="<?php echo htmlspecialchars($loc_tac_gia ?? ''); ?>"
                            >

                        </div>


                        <div class="search-field">

                            <label class="search-label">
                                Danh mục
                            </label>

                            <select
                                name="loc_danh_muc"
                                class="search-control"
                            >

                                <option value="">
                                    Tất cả danh mục
                                </option>

                                <?php foreach ($danh_sach_danh_muc as $item) { ?>

                                    <option
                                        value="<?php echo $item["category_id"]; ?>"
                                        <?php
                                        if (($loc_danh_muc ?? '') == $item["category_id"]) {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        <?php echo htmlspecialchars($item["ten_danh_muc"]); ?>
                                    </option>

                                <?php } ?>

                            </select>

                        </div>


                        <div class="search-field">

                            <label class="search-label">
                                Năm xuất bản
                            </label>

                            <input
                                type="number"
                                name="loc_nam"
                                class="search-control"
                                placeholder="Năm"
                                value="<?php echo htmlspecialchars($loc_nam ?? ''); ?>"
                            >

                        </div>


                        <div class="search-actions">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <svg viewBox="0 0 24 24" fill="none">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path d="m20 20-4-4"></path>
                                </svg>

                                Tìm kiếm

                            </button>


                            <a
                                href="index.php?controller=dausach"
                                class="btn"
                            >
                                Làm mới
                            </a>

                        </div>

                    </form>

                </div>

            </section>


            <!-- DANH SÁCH -->

            <section class="list-card">

                <div class="list-header">

                    <div class="list-title-group">

                        <svg
                            class="list-title-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <rect
                                x="5"
                                y="3"
                                width="14"
                                height="18"
                                rx="1"
                            ></rect>

                            <path d="M8 7h8"></path>
                            <path d="M8 11h8"></path>
                            <path d="M8 15h5"></path>
                        </svg>

                        <h2 class="list-title">
                            Danh sách đầu sách
                        </h2>

                    </div>


                    <div class="book-count">

                        <svg viewBox="0 0 24 24" fill="none">
                            <rect
                                x="5"
                                y="3"
                                width="14"
                                height="18"
                                rx="1"
                            ></rect>

                            <path d="M8 7h8"></path>
                        </svg>

                        <?php
                        $so_luong_hien_thi = !empty($danh_sach_sach)
                            ? count($danh_sach_sach)
                            : 0;
                        ?>

                        <?php echo $so_luong_hien_thi; ?> đầu sách

                    </div>

                </div>


                <div class="table-wrapper">

                    <table class="book-table">

                        <colgroup>

                            <col class="col-stt">
                            <col class="col-ma">
                            <col class="col-ten">
                            <col class="col-ma-tg">
                            <col class="col-tg">
                            <col class="col-danh-muc">
                            <col class="col-nxb">
                            <col class="col-nam">
                            <col class="col-isbn">
                            <col class="col-gia">
                            <col class="col-mo-ta">
                            <col class="col-trang-thai">
                            <col class="col-thao-tac">

                        </colgroup>


                        <thead>

                        <tr>

                            <th class="text-center">STT</th>
                            <th>Mã sách</th>
                            <th>Tên sách</th>
                            <th>Mã tác giả</th>
                            <th>Tác giả</th>
                            <th>Danh mục</th>
                            <th>Nhà xuất bản</th>
                            <th class="text-center">Năm XB</th>
                            <th class="text-center">ISBN</th>
                            <th class="text-center">Giá sách</th>
                            <th>Mô tả</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>

                        </tr>

                        </thead>


                        <tbody>

                        <?php

                        if (empty($danh_sach_sach)) {

                            ?>

                            <tr>

                                <td
                                    colspan="13"
                                    class="empty-state"
                                >
                                    Không tìm thấy sách.
                                </td>

                            </tr>

                            <?php

                        } else {

                            foreach ($danh_sach_sach as $vi_tri => $sach) {

                                $stt =
                                    $offset +
                                    $vi_tri +
                                    1;

                                $trang_thai =
                                    $sach["trang_thai"] ??
                                    "Hoạt động";

                                $is_active =
                                    ($trang_thai === "Hoạt động");

                                $status_class =
                                    $is_active
                                        ? "status-active"
                                        : "status-inactive";

                                ?>

                                <tr>

                                    <td class="text-center">
                                        <?php echo $stt; ?>
                                    </td>


                                    <td class="book-code">
                                        <?php
                                        echo htmlspecialchars(
                                            $sach["ma_sach"]
                                        );
                                        ?>
                                    </td>


                                    <td class="book-name">
                                        <?php
                                        echo htmlspecialchars(
                                            $sach["ten_sach"]
                                        );
                                        ?>
                                    </td>


                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $sach["ma_tac_gia"]
                                        );
                                        ?>
                                    </td>


                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $sach["tac_gia"]
                                        );
                                        ?>
                                    </td>


                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $sach["danh_muc"]
                                        );
                                        ?>
                                    </td>


                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $sach["nha_xuat_ban"]
                                        );
                                        ?>
                                    </td>


                                    <td class="text-center">
                                        <?php
                                        echo htmlspecialchars(
                                            $sach["nam_xuat_ban"]
                                        );
                                        ?>
                                    </td>


                                    <td class="text-center">
                                        <?php
                                        echo htmlspecialchars(
                                            $sach["isbn"]
                                        );
                                        ?>
                                    </td>


                                    <td class="price-cell">

                                        <?php
                                        echo number_format(
                                            (float)$sach["gia_sach"],
                                            0,
                                            ",",
                                            "."
                                        );
                                        ?>

                                        VNĐ

                                    </td>


                                    <td class="description-cell">
                                        <?php
                                        echo htmlspecialchars(
                                            $sach["mo_ta"] ?? ""
                                        );
                                        ?>
                                    </td>


                                    <td class="text-center">

                                        <span
                                            class="status-badge <?php echo $status_class; ?>"
                                        >

                                            <span class="status-dot"></span>

                                            <?php
                                            echo htmlspecialchars(
                                                $trang_thai
                                            );
                                            ?>

                                        </span>

                                    </td>


                                    <td>

                                        <form
                                            method="POST"
                                            action="index.php?controller=dausach"
                                            class="action-form"
                                        >

                                            <button
                                                type="submit"
                                                name="sua_sach"
                                                value="<?php echo $sach["id"]; ?>"
                                                class="action-btn action-edit"
                                                title="Sửa"
                                            >

                                                <svg
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                >
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"></path>
                                                </svg>

                                            </button>


                                            <button
                                                type="submit"
                                                name="xoa_sach"
                                                value="<?php echo $sach["id"]; ?>"
                                                class="action-btn action-delete"
                                                title="Xóa"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa sách này không?');"
                                            >

                                                <svg
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                >
                                                    <path d="M3 6h18"></path>
                                                    <path d="M8 6V4h8v2"></path>
                                                    <path d="M19 6l-1 15H6L5 6"></path>
                                                    <path d="M10 11v6"></path>
                                                    <path d="M14 11v6"></path>
                                                </svg>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                                <?php
                            }
                        }

                        ?>

                        </tbody>

                    </table>

                </div>


                <!-- PHÂN TRANG -->

                <?php if ($tong_so_trang > 1) { ?>

                    <div class="pagination-wrapper">

                        <?php

                        for ($i = 1; $i <= $tong_so_trang; $i++) {

                            $tham_so = [

                                "controller" => "dausach",

                                "tu_khoa" => $tu_khoa,

                                "loc_tac_gia" => $loc_tac_gia,

                                "loc_danh_muc" => $loc_danh_muc,

                                "loc_nam" => $loc_nam,

                                "trang" => $i

                            ];

                            $url =
                                "index.php?" .
                                http_build_query($tham_so);

                            ?>

                            <a
                                href="<?php echo htmlspecialchars($url); ?>"
                                class="pagination-link <?php echo ($i == $trang) ? "active" : ""; ?>"
                            >
                                <?php echo $i; ?>
                            </a>

                            <?php
                        }

                        ?>

                    </div>

                <?php } ?>

            </section>

        </div>

    </main>

</div>


<!-- =============================================================
     POPUP THÊM ĐẦU SÁCH
     ============================================================= -->

<?php
/*
 * CHỈ coi là đang xử lý form THÊM khi:
 * - Controller cho phép popup mở
 *
 * Vì vậy:
 * - Vào trang lần đầu => popup đóng
 * - Bấm "Thêm đầu sách" => JS mở popup
 * - Submit thiếu dữ liệu => controller trả lại view,
 *   popup tự mở lại và giữ dữ liệu + lỗi.
 */
$dang_loi_them = !empty($hien_popup_them);
?>

<div
    class="modal-overlay <?php echo $dang_loi_them ? 'show' : ''; ?>"
    id="add-book-modal"
    aria-hidden="<?php echo $dang_loi_them ? 'false' : 'true'; ?>"
>

    <div
        class="book-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="add-book-title"
    >

        <div class="modal-header">

            <div class="modal-header-left">

                <div class="card-header-icon">

                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z"></path>
                        <path d="M4 5.5V20"></path>
                    </svg>

                </div>

                <h2
                    class="card-title"
                    id="add-book-title"
                >
                    Thêm đầu sách
                </h2>

            </div>


            <button
                type="button"
                class="modal-close"
                id="btn-close-add"
                aria-label="Đóng"
            >

                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M6 6l12 12"></path>
                    <path d="M18 6 6 18"></path>
                </svg>

            </button>

        </div>


        <div class="modal-body">

            <form
                method="POST"
                action="index.php?controller=dausach"
            >

                <div class="form-grid">


                    <!-- MÃ SÁCH -->

                    <div class="form-group">

                        <label class="form-label">
                            Mã sách
                        </label>

                        <input
                            type="text"
                            name="ma_sach"
                            class="form-control <?php echo ($dang_loi_them && isset($loi["ma_sach"])) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($dang_loi_them ? ($ma_sach ?? '') : ''); ?>"
                        >

                        <?php if ($dang_loi_them && isset($loi["ma_sach"])) { ?>

                            <span class="form-error">
                                <?php echo htmlspecialchars($loi["ma_sach"]); ?>
                            </span>

                        <?php } ?>

                    </div>


                    <!-- TÊN SÁCH -->

                    <div class="form-group">

                        <label class="form-label">
                            Tên sách
                        </label>

                        <input
                            type="text"
                            name="ten_sach"
                            class="form-control <?php echo ($dang_loi_them && isset($loi["ten_sach"])) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($dang_loi_them ? ($ten_sach ?? '') : ''); ?>"
                        >

                        <?php if ($dang_loi_them && isset($loi["ten_sach"])) { ?>

                            <span class="form-error">
                                <?php echo htmlspecialchars($loi["ten_sach"]); ?>
                            </span>

                        <?php } ?>

                    </div>


                    <!-- MÃ TÁC GIẢ -->

                    <div class="form-group">

                        <label class="form-label">
                            Mã tác giả
                        </label>

                        <input
                            type="text"
                            name="ma_tac_gia"
                            class="form-control <?php echo ($dang_loi_them && isset($loi["ma_tac_gia"])) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($dang_loi_them ? ($ma_tac_gia ?? '') : ''); ?>"
                        >

                        <?php if ($dang_loi_them && isset($loi["ma_tac_gia"])) { ?>

                            <span class="form-error">
                                <?php echo htmlspecialchars($loi["ma_tac_gia"]); ?>
                            </span>

                        <?php } ?>

                    </div>


                    <!-- TÁC GIẢ -->

                    <div class="form-group">

                        <label class="form-label">
                            Tác giả
                        </label>

                        <input
                            type="text"
                            name="tac_gia"
                            class="form-control <?php echo ($dang_loi_them && isset($loi["tac_gia"])) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($dang_loi_them ? ($tac_gia ?? '') : ''); ?>"
                        >

                        <?php if ($dang_loi_them && isset($loi["tac_gia"])) { ?>

                            <span class="form-error">
                                <?php echo htmlspecialchars($loi["tac_gia"]); ?>
                            </span>

                        <?php } ?>

                    </div>


                    <!-- DANH MỤC -->

                    <div class="form-group">

                        <label class="form-label">
                            Danh mục
                        </label>

                        <select
                            name="danh_muc"
                            class="form-control <?php echo ($dang_loi_them && isset($loi["danh_muc"])) ? 'input-error' : ''; ?>"
                        >

                            <option value="">
                                -- Chọn danh mục --
                            </option>

                            <?php foreach ($danh_sach_danh_muc as $danh_muc_item) { ?>

                                <option
                                    value="<?php echo htmlspecialchars($danh_muc_item["ten_danh_muc"]); ?>"
                                    <?php
                                    if (
                                        $dang_loi_them &&
                                        ($danh_muc ?? '') == $danh_muc_item["ten_danh_muc"]
                                    ) {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    <?php echo htmlspecialchars($danh_muc_item["ten_danh_muc"]); ?>
                                </option>

                            <?php } ?>

                        </select>

                        <?php if ($dang_loi_them && isset($loi["danh_muc"])) { ?>

                            <span class="form-error">
                                <?php echo htmlspecialchars($loi["danh_muc"]); ?>
                            </span>

                        <?php } ?>

                    </div>


                    <!-- NXB -->

                    <div class="form-group">

                        <label class="form-label">
                            Nhà xuất bản
                        </label>

                        <input
                            type="text"
                            name="nha_xuat_ban"
                            class="form-control <?php echo ($dang_loi_them && isset($loi["nha_xuat_ban"])) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($dang_loi_them ? ($nha_xuat_ban ?? '') : ''); ?>"
                        >

                        <?php if ($dang_loi_them && isset($loi["nha_xuat_ban"])) { ?>

                            <span class="form-error">
                                <?php echo htmlspecialchars($loi["nha_xuat_ban"]); ?>
                            </span>

                        <?php } ?>

                    </div>


                    <!-- NĂM -->

                    <div class="form-group">

                        <label class="form-label">
                            Năm xuất bản
                        </label>

                        <input
                            type="number"
                            name="nam_xuat_ban"
                            class="form-control <?php echo ($dang_loi_them && isset($loi["nam_xuat_ban"])) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($dang_loi_them ? ($nam_xuat_ban ?? '') : ''); ?>"
                        >

                        <?php if ($dang_loi_them && isset($loi["nam_xuat_ban"])) { ?>

                            <span class="form-error">
                                <?php echo htmlspecialchars($loi["nam_xuat_ban"]); ?>
                            </span>

                        <?php } ?>

                    </div>


                    <!-- ISBN -->

                    <div class="form-group">

                        <label class="form-label">
                            ISBN
                        </label>

                        <input
                            type="text"
                            name="isbn"
                            class="form-control <?php echo ($dang_loi_them && isset($loi["isbn"])) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($dang_loi_them ? ($isbn ?? '') : ''); ?>"
                        >

                        <?php if ($dang_loi_them && isset($loi["isbn"])) { ?>

                            <span class="form-error">
                                <?php echo htmlspecialchars($loi["isbn"]); ?>
                            </span>

                        <?php } ?>

                    </div>


                    <!-- GIÁ -->

                    <div class="form-group">

                        <label class="form-label">
                            Giá sách (VNĐ)
                        </label>

                        <input
                            type="number"
                            name="gia_sach"
                            min="1"
                            class="form-control <?php echo ($dang_loi_them && isset($loi["gia_sach"])) ? 'input-error' : ''; ?>"
                            value="<?php echo htmlspecialchars($dang_loi_them ? ($gia_sach ?? '') : ''); ?>"
                        >

                        <?php if ($dang_loi_them && isset($loi["gia_sach"])) { ?>

                            <span class="form-error">
                                <?php echo htmlspecialchars($loi["gia_sach"]); ?>
                            </span>

                        <?php } ?>

                    </div>


                    <!-- MÔ TẢ -->

                    <div class="form-group full-width">

                        <label class="form-label">
                            Mô tả
                        </label>

                        <textarea
                            name="mo_ta"
                            class="form-control <?php echo ($dang_loi_them && isset($loi["mo_ta"])) ? 'input-error' : ''; ?>"
                        ><?php echo htmlspecialchars($dang_loi_them ? ($mo_ta ?? '') : ''); ?></textarea>

                        <?php if ($dang_loi_them && isset($loi["mo_ta"])) { ?>

                            <span class="form-error">
                                <?php echo htmlspecialchars($loi["mo_ta"]); ?>
                            </span>

                        <?php } ?>

                    </div>


                    <!-- BUTTON -->

                    <div class="form-actions">

                        <button
                            type="button"
                            class="btn"
                            id="btn-cancel-add"
                        >
                            Hủy
                        </button>


                        <button
                            type="submit"
                            name="them_sach"
                            class="btn btn-primary"
                        >

                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>

                            Thêm sách

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener("DOMContentLoaded", function () {

    const modal =
        document.getElementById("add-book-modal");

    const openButton =
        document.getElementById("btn-open-add");

    const closeButton =
        document.getElementById("btn-close-add");

    const cancelButton =
        document.getElementById("btn-cancel-add");


    function openModal() {

        if (!modal) {
            return;
        }

        modal.classList.add("show");

        modal.setAttribute(
            "aria-hidden",
            "false"
        );

        document.body.classList.add(
            "modal-open"
        );

    }


    function closeModal() {

        if (!modal) {
            return;
        }

        modal.classList.remove("show");

        modal.setAttribute(
            "aria-hidden",
            "true"
        );

        document.body.classList.remove(
            "modal-open"
        );

    }


    /*
     * BẤM "THÊM ĐẦU SÁCH"
     *
     * Chỉ mở popup.
     * Không validate.
     * Không thêm class đỏ.
     * Không gửi form.
     */

    if (openButton) {

        openButton.addEventListener(
            "click",
            function () {

                openModal();

            }
        );

    }


    /*
     * NÚT X
     */

    if (closeButton) {

        closeButton.addEventListener(
            "click",
            function () {

                closeModal();

            }
        );

    }


    /*
     * NÚT HỦY
     */

    if (cancelButton) {

        cancelButton.addEventListener(
            "click",
            function () {

                closeModal();

            }
        );

    }


    /*
     * CLICK RA NGOÀI POPUP
     */

    if (modal) {

        modal.addEventListener(
            "click",
            function (event) {

                if (event.target === modal) {

                    closeModal();

                }

            }
        );

    }


    /*
     * ESC
     */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key === "Escape" &&
                modal &&
                modal.classList.contains("show")
            ) {

                closeModal();

            }

        }
    );


    /*
     * QUAN TRỌNG:
     *
     * Chỉ tự mở popup khi:
     * request hiện tại là POST them_sach
     * và controller trả lại form do có lỗi.
     *
     * Không dùng riêng $loi để mở popup.
     */

    <?php if ($dang_loi_them) { ?>

        openModal();

    <?php } ?>
 const successAlert =
        document.getElementById("success-alert");

    if (successAlert) {

        setTimeout(function () {

            successAlert.style.transition =
                "opacity 0.3s ease, transform 0.3s ease";

            successAlert.style.opacity = "0";

            successAlert.style.transform =
                "translateY(-5px)";

            setTimeout(function () {

                successAlert.remove();

            }, 300);

        }, 3000);

    }
});

</script>

</body>

</html>