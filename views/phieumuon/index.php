```php
<?php

/*
|--------------------------------------------------------------------------
| QUẢN LÝ PHIẾU MƯỢN
|--------------------------------------------------------------------------
*/

$v = function ($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
};

$danhSachPhieuMuon = $danhSachPhieuMuon ?? [];

$thongKePhieuMuon = $thongKePhieuMuon ?? [
    'tong' => 0,
    'cho_duyet' => 0,
    'dang_muon' => 0,
    'qua_han' => 0,
    'da_tra' => 0
];

$pageTitle = $pageTitle ?? 'QUẢN LÝ PHIẾU MƯỢN';

$pageSubtitle = $pageSubtitle ??
    'Quản lý thông tin & trạng thái phiếu mượn';

$errors = $errors ?? [];

$thongBao = $thongBao ?? '';

$laThuThu = $laThuThu ?? false;

$laQuanTriVien = $laQuanTriVien ?? false;

?>

<style>

/* =========================================================
   MAIN
   Không can thiệp vào .layout/sidebar dùng chung
========================================================= */

.phieu-main {
    flex: 1;
    min-width: 0;
    min-height: 100vh;
    padding: 28px 32px 50px;
    background: var(--background, #f8fafc);
}


/* =========================================================
   HERO
========================================================= */

.module-hero {
    background: linear-gradient(
        135deg,
        #1e3a8a 0%,
        #2563eb 100%
    );

    border-radius: 20px;

    padding: 28px 32px;

    margin-bottom: 24px;

    color: white;

    box-shadow:
        0 12px 30px rgba(37, 99, 235, 0.16);
}

.module-hero h1 {
    margin: 0 0 8px;

    font-size: 28px;

    font-weight: 800;

    letter-spacing: -0.4px;
}

.module-hero p {
    margin: 0;

    font-size: 14px;

    opacity: 0.9;
}


/* =========================================================
   ALERT
========================================================= */

.alert-success {
    display: flex;

    align-items: center;

    gap: 10px;

    padding: 14px 16px;

    margin-bottom: 22px;

    border: 1px solid #bbf7d0;

    border-radius: 12px;

    background: #f0fdf4;

    color: #166534;

    font-size: 14px;

    font-weight: 600;
}


/* =========================================================
   STAT CARDS
========================================================= */

.stats-grid {
    display: grid;

    grid-template-columns:
        repeat(5, minmax(0, 1fr));

    gap: 16px;

    margin-bottom: 24px;
}

.stat-card {
    background: white;

    border: 1px solid #e2e8f0;

    border-radius: 16px;

    padding: 20px;

    box-shadow:
        0 4px 14px rgba(15, 23, 42, 0.04);

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);

    box-shadow:
        0 10px 24px rgba(15, 23, 42, 0.08);
}

.stat-label {
    color: #64748b;

    font-size: 13px;

    font-weight: 600;

    margin-bottom: 8px;
}

.stat-number {
    color: #0f172a;

    font-size: 28px;

    line-height: 1;

    font-weight: 800;
}


/* =========================================================
   MANAGEMENT PANEL
========================================================= */

.management-panel {
    background: white;

    border: 1px solid #e2e8f0;

    border-radius: 18px;

    overflow: hidden;

    box-shadow:
        0 6px 20px rgba(15, 23, 42, 0.05);
}

.panel-header {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 16px;

    padding: 20px 22px;

    border-bottom: 1px solid #e2e8f0;
}

.panel-title {
    margin: 0;

    color: #0f172a;

    font-size: 18px;

    font-weight: 800;
}

.panel-subtitle {
    margin: 5px 0 0;

    color: #64748b;

    font-size: 13px;
}

.panel-tools {
    display: flex;

    align-items: center;

    gap: 10px;
}


/* =========================================================
   SEARCH
========================================================= */

.search-box {
    position: relative;
}

.search-box input {
    width: 250px;

    padding: 11px 14px 11px 38px;

    border: 1px solid #cbd5e1;

    border-radius: 10px;

    outline: none;

    color: #0f172a;

    background: white;

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

.search-box input:focus {
    border-color: #2563eb;

    box-shadow:
        0 0 0 3px rgba(37, 99, 235, 0.12);
}

.search-icon {
    position: absolute;

    left: 13px;

    top: 50%;

    transform: translateY(-50%);

    color: #64748b;

    pointer-events: none;
}


/* =========================================================
   FILTER
========================================================= */

.status-filter {
    padding: 11px 34px 11px 12px;

    border: 1px solid #cbd5e1;

    border-radius: 10px;

    background: white;

    color: #334155;

    outline: none;

    cursor: pointer;
}

.status-filter:focus {
    border-color: #2563eb;

    box-shadow:
        0 0 0 3px rgba(37, 99, 235, 0.12);
}


/* =========================================================
   BUTTON PRIMARY
========================================================= */

.btn-primary {
    border: none;

    padding: 11px 16px;

    border-radius: 10px;

    background: #2563eb;

    color: white;

    font-weight: 700;

    cursor: pointer;

    transition:
        background 0.2s ease,
        transform 0.2s ease;
}

.btn-primary:hover {
    background: #1d4ed8;

    transform: translateY(-1px);
}


/* =========================================================
   TABLE
========================================================= */

.table-wrapper {
    overflow-x: auto;
}

.loan-table {
    width: 100%;

    border-collapse: collapse;

    min-width: 1050px;
}

.loan-table th {
    padding: 14px 16px;

    background: #f8fafc;

    border-bottom: 1px solid #e2e8f0;

    color: #475569;

    font-size: 12px;

    font-weight: 800;

    text-align: left;

    white-space: nowrap;
}

.loan-table td {
    padding: 15px 16px;

    border-bottom: 1px solid #f1f5f9;

    color: #334155;

    font-size: 13px;

    vertical-align: middle;
}

.loan-table tbody tr {
    transition: background 0.15s ease;
}

.loan-table tbody tr:hover {
    background: #f8fafc;
}

.loan-id {
    font-weight: 800;

    color: #2563eb;
}

.book-name {
    font-weight: 700;

    color: #0f172a;
}

.muted {
    color: #64748b;
}


/* =========================================================
   STATUS BADGE
========================================================= */

.status-badge {
    display: inline-flex;

    align-items: center;

    padding: 5px 9px;

    border-radius: 999px;

    font-size: 11px;

    font-weight: 800;

    white-space: nowrap;
}

.status-cho-duyet {
    background: #fef3c7;

    color: #92400e;
}

.status-dang-muon {
    background: #dbeafe;

    color: #1e40af;
}

.status-qua-han {
    background: #fee2e2;

    color: #991b1b;
}

.status-da-tra {
    background: #dcfce7;

    color: #166534;
}


/* =========================================================
   ACTION BUTTON
========================================================= */

.action-group {
    display: flex;

    gap: 7px;
}

.btn-action {
    border: 1px solid #cbd5e1;

    background: white;

    color: #334155;

    padding: 7px 10px;

    border-radius: 8px;

    font-size: 12px;

    font-weight: 700;

    cursor: pointer;

    transition: all 0.15s ease;
}

.btn-action:hover {
    border-color: #2563eb;

    color: #2563eb;

    background: #eff6ff;
}

.btn-delete:hover {
    border-color: #ef4444;

    color: #dc2626;

    background: #fef2f2;
}


/* =========================================================
   EMPTY
========================================================= */

.empty-state {
    text-align: center;

    padding: 55px 20px;

    color: #64748b;
}

.empty-state-icon {
    font-size: 38px;

    margin-bottom: 12px;
}

.empty-state-title {
    color: #334155;

    font-weight: 800;

    margin-bottom: 5px;
}


/* =========================================================
   MODAL
========================================================= */

.modal-overlay {
    position: fixed;

    inset: 0;

    z-index: 9999;

    display: none;

    align-items: center;

    justify-content: center;

    padding: 20px;

    background:
        rgba(15, 23, 42, 0.55);

    backdrop-filter: blur(3px);
}

.modal-overlay.show {
    display: flex;
}

.modal {
    width: 100%;

    max-width: 620px;

    max-height: 90vh;

    overflow-y: auto;

    background: white;

    border-radius: 18px;

    box-shadow:
        0 25px 70px rgba(15, 23, 42, 0.22);
}

.modal-header {
    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 20px 22px;

    border-bottom: 1px solid #e2e8f0;
}

.modal-header h2 {
    margin: 0;

    color: #0f172a;

    font-size: 19px;

    font-weight: 800;
}

.modal-close {
    width: 34px;

    height: 34px;

    border: none;

    border-radius: 8px;

    background: #f1f5f9;

    color: #475569;

    font-size: 20px;

    cursor: pointer;
}

.modal-close:hover {
    background: #e2e8f0;
}

.modal-body {
    padding: 22px;
}

.form-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 16px;
}

.form-group {
    display: flex;

    flex-direction: column;

    gap: 7px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-label {
    color: #334155;

    font-size: 13px;

    font-weight: 700;
}

.required {
    color: #dc2626;
}

.form-control {
    width: 100%;

    box-sizing: border-box;

    padding: 11px 12px;

    border: 1px solid #cbd5e1;

    border-radius: 9px;

    background: white;

    color: #0f172a;

    outline: none;

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

.form-control:focus {
    border-color: #2563eb;

    box-shadow:
        0 0 0 3px rgba(37, 99, 235, 0.12);
}

.form-error {
    color: #dc2626;

    font-size: 12px;

    font-weight: 600;
}

.general-error {
    margin-bottom: 16px;

    padding: 11px 13px;

    border-radius: 9px;

    background: #fef2f2;

    border: 1px solid #fecaca;

    color: #991b1b;

    font-size: 13px;
}

.modal-footer {
    display: flex;

    justify-content: flex-end;

    gap: 10px;

    padding: 16px 22px;

    border-top: 1px solid #e2e8f0;
}

.btn-secondary {
    padding: 10px 15px;

    border: 1px solid #cbd5e1;

    border-radius: 9px;

    background: white;

    color: #475569;

    font-weight: 700;

    cursor: pointer;
}

.btn-secondary:hover {
    background: #f8fafc;
}

.btn-danger {
    padding: 10px 15px;

    border: none;

    border-radius: 9px;

    background: #dc2626;

    color: white;

    font-weight: 700;

    cursor: pointer;
}

.btn-danger:hover {
    background: #b91c1c;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .stats-grid {
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
    }

    .panel-header {
        align-items: flex-start;

        flex-direction: column;
    }

    .panel-tools {
        width: 100%;
    }

    .search-box {
        flex: 1;
    }

    .search-box input {
        width: 100%;
    }
}

@media (max-width: 900px) {

    .phieu-main {
        padding: 20px 16px 40px;
    }

    .stats-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .panel-tools {
        flex-wrap: wrap;
    }
}

@media (max-width: 600px) {

    .module-hero {
        padding: 22px;
    }

    .module-hero h1 {
        font-size: 22px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .panel-tools {
        flex-direction: column;

        align-items: stretch;
    }

    .search-box input,
    .status-filter,
    .btn-primary {
        width: 100%;

        box-sizing: border-box;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full {
        grid-column: auto;
    }
}

</style>


<!-- =========================================================
     NỘI DUNG CHÍNH
========================================================= -->

<main class="phieu-main">

    <!-- HERO -->

    <section class="module-hero">

        <h1>
            <?= $v($pageTitle) ?>
        </h1>

        <p>
            <?= $v($pageSubtitle) ?>
        </p>

    </section>


    <!-- THÔNG BÁO -->

    <?php if (!empty($thongBao)): ?>

        <div class="alert-success">

            ✓

            <span>
                <?= $v($thongBao) ?>
            </span>

        </div>

    <?php endif; ?>


    <!-- THỐNG KÊ -->

    <section class="stats-grid">

        <div class="stat-card">

            <div class="stat-label">
                Tổng phiếu mượn
            </div>

            <div
                class="stat-number"
                id="statTong"
            >
                <?= (int)$thongKePhieuMuon['tong'] ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-label">
                Chờ duyệt
            </div>

            <div
                class="stat-number"
                id="statChoDuyet"
            >
                <?= (int)$thongKePhieuMuon['cho_duyet'] ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-label">
                Đang mượn
            </div>

            <div
                class="stat-number"
                id="statDangMuon"
            >
                <?= (int)$thongKePhieuMuon['dang_muon'] ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-label">
                Quá hạn
            </div>

            <div
                class="stat-number"
                id="statQuaHan"
            >
                <?= (int)$thongKePhieuMuon['qua_han'] ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-label">
                Đã trả
            </div>

            <div
                class="stat-number"
                id="statDaTra"
            >
                <?= (int)$thongKePhieuMuon['da_tra'] ?>
            </div>

        </div>

    </section>


    <!-- QUẢN LÝ -->

    <section class="management-panel">

        <div class="panel-header">

            <div>

                <h2 class="panel-title">
                    Danh sách phiếu mượn
                </h2>

                <p class="panel-subtitle">
                    Tra cứu, thêm, chỉnh sửa và quản lý trạng thái phiếu mượn.
                </p>

            </div>


            <div class="panel-tools">

                <!-- SEARCH -->

                <div class="search-box">

                    <span class="search-icon">
                        🔍
                    </span>

                    <input
                        type="text"
                        id="loanSearch"
                        placeholder="Tìm mã người dùng, mã bản sao, tên sách..."
                        autocomplete="off"
                    >

                </div>


                <!-- FILTER -->

                <select
                    id="loanStatus"
                    class="status-filter"
                >

                    <option value="">
                        Tất cả trạng thái
                    </option>

                    <option value="Chờ duyệt">
                        Chờ duyệt
                    </option>

                    <option value="Đang mượn">
                        Đang mượn
                    </option>

                    <option value="Quá hạn">
                        Quá hạn
                    </option>

                    <option value="Đã trả">
                        Đã trả
                    </option>

                </select>


                <!-- ADD -->

                <button
                    type="button"
                    class="btn-primary"
                    id="openLoanModal"
                >
                    + Thêm phiếu mượn
                </button>

            </div>

        </div>


        <!-- TABLE -->

        <div class="table-wrapper">

            <table
                class="loan-table"
                id="loanTable"
            >

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Người mượn</th>

                        <th>Họ tên</th>

                        <th>Bản sao</th>

                        <th>Tên sách</th>

                        <th>Ngày mượn</th>

                        <th>Ngày trả</th>

                        <th>Trạng thái</th>

                        <th>Thao tác</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (empty($danhSachPhieuMuon)): ?>

                    <tr>

                        <td
                            colspan="9"
                            class="empty-state"
                        >

                            <div class="empty-state-icon">
                                📚
                            </div>

                            <div class="empty-state-title">
                                Chưa có phiếu mượn
                            </div>

                            <div>
                                Hiện tại chưa có dữ liệu phiếu mượn.
                            </div>

                        </td>

                    </tr>

                <?php else: ?>

                    <?php foreach ($danhSachPhieuMuon as $phieu): ?>

                        <?php

                        $idPhieu = (int)(
                            $phieu['ID_PhieuMuon'] ?? 0
                        );

                        $maND =
                            $phieu['ma_nguoi_dung'] ?? '';

                        $hoTen =
                            $phieu['ho_ten'] ?? '';

                        $maBS =
                            $phieu['ma_ban_sao'] ?? '';

                        $tenSach =
                            $phieu['ten_sach'] ?? '';

                        $ngayM =
                            $phieu['NgayMuon'] ?? '';

                        $ngayT =
                            $phieu['NgayTra'] ?? '';

                        $status =
                            $phieu['TrangThai'] ?? '';

                        $statusClass =
                            'status-cho-duyet';

                        if ($status === 'Đang mượn') {

                            $statusClass =
                                'status-dang-muon';

                        } elseif ($status === 'Quá hạn') {

                            $statusClass =
                                'status-qua-han';

                        } elseif ($status === 'Đã trả') {

                            $statusClass =
                                'status-da-tra';

                        }

                        $searchData = strtolower(
                            implode(' ', [
                                $idPhieu,
                                $maND,
                                $hoTen,
                                $maBS,
                                $tenSach,
                                $status
                            ])
                        );

                        ?>

                        <tr
                            class="loan-row"
                            data-search="<?= $v($searchData) ?>"
                            data-status="<?= $v($status) ?>"
                        >

                            <td>

                                <span class="loan-id">
                                    #<?= $idPhieu ?>
                                </span>

                            </td>


                            <td>
                                <?= $v($maND) ?>
                            </td>


                            <td>

                                <strong>
                                    <?= $v($hoTen) ?>
                                </strong>

                            </td>


                            <td>
                                <?= $v($maBS) ?>
                            </td>


                            <td>

                                <span class="book-name">
                                    <?= $v($tenSach) ?>
                                </span>

                            </td>


                            <td>
                                <?= $v($ngayM) ?>
                            </td>


                            <td>

                                <?php if (!empty($ngayT)): ?>

                                    <?= $v($ngayT) ?>

                                <?php else: ?>

                                    <span class="muted">
                                        Chưa trả
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>

                                <span
                                    class="status-badge <?= $v($statusClass) ?>"
                                >
                                    <?= $v($status) ?>
                                </span>

                            </td>


                            <td>

                                <div class="action-group">

                                    <!-- SỬA -->

                                    <button
                                        type="button"
                                        class="btn-action btn-edit-loan"
                                        data-id="<?= $idPhieu ?>"
                                        data-ma-nguoi-dung="<?= $v($maND) ?>"
                                        data-ma-ban-sao="<?= $v($maBS) ?>"
                                        data-ngay-muon="<?= $v($ngayM) ?>"
                                        data-ngay-tra="<?= $v($ngayT) ?>"
                                        data-trang-thai="<?= $v($status) ?>"
                                    >
                                        Sửa
                                    </button>


                                    <!-- XÓA
                                         CHỈ QUẢN TRỊ VIÊN -->

                                    <?php if ($laQuanTriVien): ?>

                                        <button
                                            type="button"
                                            class="btn-action btn-delete btn-delete-loan"
                                            data-id="<?= $idPhieu ?>"
                                        >
                                            Xóa
                                        </button>

                                    <?php endif; ?>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>


<!-- =========================================================
     MODAL THÊM / SỬA
========================================================= -->

<div
    class="modal-overlay"
    id="loanModal"
>

    <div class="modal">

        <div class="modal-header">

            <h2 id="loanModalTitle">
                Thêm phiếu mượn
            </h2>

            <button
                type="button"
                class="modal-close"
                id="closeLoanModal"
            >
                ×
            </button>

        </div>


        <form
            method="POST"
            action="index.php?controller=phieumuon"
            id="loanForm"
        >

            <input
                type="hidden"
                name="action"
                id="loanAction"
                value="add"
            >

            <input
                type="hidden"
                name="id"
                id="loanId"
                value="0"
            >


            <div class="modal-body">

                <?php if (!empty($errors['general'])): ?>

                    <div class="general-error">

                        <?= $v($errors['general']) ?>

                    </div>

                <?php endif; ?>


                <div class="form-grid">

                    <!-- MÃ NGƯỜI DÙNG -->

                    <div class="form-group">

                        <label class="form-label">

                            Mã người dùng

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="ma_nguoi_dung"
                            id="maNguoiDung"
                            value="<?= $v($maNguoiDung ?? '') ?>"
                            placeholder="VD: DG001"
                        >

                        <?php if (!empty($errors['ma_nguoi_dung'])): ?>

                            <div class="form-error">
                                <?= $v($errors['ma_nguoi_dung']) ?>
                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- MÃ BẢN SAO -->

                    <div class="form-group">

                        <label class="form-label">

                            Mã bản sao

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="ma_ban_sao"
                            id="maBanSao"
                            value="<?= $v($maBanSao ?? '') ?>"
                            placeholder="VD: BS001"
                        >

                        <?php if (!empty($errors['ma_ban_sao'])): ?>

                            <div class="form-error">
                                <?= $v($errors['ma_ban_sao']) ?>
                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- NGÀY MƯỢN -->

                    <div class="form-group">

                        <label class="form-label">

                            Ngày mượn

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="date"
                            class="form-control"
                            name="ngay_muon"
                            id="ngayMuon"
                            value="<?= $v($ngayMuon ?? '') ?>"
                        >

                        <?php if (!empty($errors['ngay_muon'])): ?>

                            <div class="form-error">
                                <?= $v($errors['ngay_muon']) ?>
                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- NGÀY TRẢ -->

                    <div class="form-group">

                        <label class="form-label">
                            Ngày trả
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            name="ngay_tra"
                            id="ngayTra"
                            value="<?= $v($ngayTra ?? '') ?>"
                        >

                        <?php if (!empty($errors['ngay_tra'])): ?>

                            <div class="form-error">
                                <?= $v($errors['ngay_tra']) ?>
                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- TRẠNG THÁI -->

                    <div class="form-group full">

                        <label class="form-label">

                            Trạng thái

                            <span class="required">
                                *
                            </span>

                        </label>

                        <select
                            class="form-control"
                            name="trang_thai"
                            id="trangThai"
                        >

                            <option value="Chờ duyệt">
                                Chờ duyệt
                            </option>

                            <option value="Đang mượn">
                                Đang mượn
                            </option>

                            <option value="Quá hạn">
                                Quá hạn
                            </option>

                            <option value="Đã trả">
                                Đã trả
                            </option>

                        </select>

                        <?php if (!empty($errors['trang_thai'])): ?>

                            <div class="form-error">
                                <?= $v($errors['trang_thai']) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn-secondary"
                    id="cancelLoanModal"
                >
                    Hủy
                </button>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Lưu phiếu mượn
                </button>

            </div>

        </form>

    </div>

</div>


<?php if ($laQuanTriVien): ?>

<!-- =========================================================
     MODAL XÓA
     CHỈ HIỂN THỊ VỚI QUẢN TRỊ VIÊN
========================================================= -->

<div
    class="modal-overlay"
    id="deleteLoanModal"
>

    <div
        class="modal"
        style="max-width:440px;"
    >

        <div class="modal-header">

            <h2>
                Xác nhận xóa
            </h2>

            <button
                type="button"
                class="modal-close"
                id="closeDeleteModal"
            >
                ×
            </button>

        </div>


        <div class="modal-body">

            <p
                style="
                    margin:0;
                    color:#475569;
                    line-height:1.6;
                "
            >

                Bạn có chắc chắn muốn xóa phiếu mượn này không?

                <br>

                Phiếu sẽ được chuyển sang trạng thái đã xóa.

            </p>

        </div>


        <div class="modal-footer">

            <button
                type="button"
                class="btn-secondary"
                id="cancelDeleteModal"
            >
                Hủy
            </button>


            <form
                method="POST"
                action="index.php?controller=phieumuon"
                id="deleteLoanForm"
                style="display:inline;"
            >

                <input
                    type="hidden"
                    name="action"
                    value="delete"
                >

                <input
                    type="hidden"
                    name="id"
                    id="deleteLoanId"
                    value="0"
                >

                <button
                    type="submit"
                    class="btn-danger"
                >
                    Xác nhận xóa
                </button>

            </form>

        </div>

    </div>

</div>

<?php endif; ?>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       ELEMENT
    ===================================================== */

    const loanModal =
        document.getElementById('loanModal');

    const deleteModal =
        document.getElementById('deleteLoanModal');

    const loanForm =
        document.getElementById('loanForm');

    const loanAction =
        document.getElementById('loanAction');

    const loanId =
        document.getElementById('loanId');

    const loanModalTitle =
        document.getElementById('loanModalTitle');

    const maNguoiDung =
        document.getElementById('maNguoiDung');

    const maBanSao =
        document.getElementById('maBanSao');

    const ngayMuon =
        document.getElementById('ngayMuon');

    const ngayTra =
        document.getElementById('ngayTra');

    const trangThai =
        document.getElementById('trangThai');

    const loanSearch =
        document.getElementById('loanSearch');

    const loanStatus =
        document.getElementById('loanStatus');


    /* =====================================================
       MỞ MODAL THÊM
    ===================================================== */

    const openLoanModal =
        document.getElementById('openLoanModal');

    if (openLoanModal) {

        openLoanModal.addEventListener(
            'click',
            function () {

                loanForm.reset();

                loanAction.value = 'add';

                loanId.value = '0';

                loanModalTitle.textContent =
                    'Thêm phiếu mượn';

                trangThai.value =
                    'Chờ duyệt';

                loanModal.classList.add('show');

                setTimeout(function () {

                    maNguoiDung.focus();

                }, 100);

            }
        );

    }


    /* =====================================================
       ĐÓNG MODAL
    ===================================================== */

    function closeLoanModal() {

        loanModal.classList.remove('show');

    }

    document
        .getElementById('closeLoanModal')
        .addEventListener(
            'click',
            closeLoanModal
        );

    document
        .getElementById('cancelLoanModal')
        .addEventListener(
            'click',
            closeLoanModal
        );


    /* =====================================================
       SỬA
    ===================================================== */

    document
        .querySelectorAll('.btn-edit-loan')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    loanAction.value =
                        'edit';

                    loanId.value =
                        this.dataset.id || '0';

                    loanModalTitle.textContent =
                        'Cập nhật phiếu mượn';

                    maNguoiDung.value =
                        this.dataset.maNguoiDung || '';

                    maBanSao.value =
                        this.dataset.maBanSao || '';

                    ngayMuon.value =
                        this.dataset.ngayMuon || '';

                    ngayTra.value =
                        this.dataset.ngayTra || '';

                    trangThai.value =
                        this.dataset.trangThai ||
                        'Chờ duyệt';

                    loanModal.classList.add('show');

                    if (ngayMuon.value) {
                        ngayTra.min =
                            ngayMuon.value;
                    }

                    setTimeout(function () {

                        maNguoiDung.focus();

                    }, 100);

                }
            );

        });


    /* =====================================================
       XÓA
       CHỈ CHẠY VỚI QUẢN TRỊ VIÊN
    ===================================================== */

    if (deleteModal) {

        const deleteLoanId =
            document.getElementById('deleteLoanId');

        document
            .querySelectorAll('.btn-delete-loan')
            .forEach(function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        deleteLoanId.value =
                            this.dataset.id || '0';

                        deleteModal.classList.add(
                            'show'
                        );

                    }
                );

            });


        function closeDeleteModal() {

            deleteModal.classList.remove('show');

        }


        document
            .getElementById('closeDeleteModal')
            .addEventListener(
                'click',
                closeDeleteModal
            );


        document
            .getElementById('cancelDeleteModal')
            .addEventListener(
                'click',
                closeDeleteModal
            );


        deleteModal.addEventListener(
            'click',
            function (event) {

                if (event.target === deleteModal) {

                    closeDeleteModal();

                }

            }
        );

    }


    /* =====================================================
       CLICK NGOÀI MODAL
    ===================================================== */

    loanModal.addEventListener(
        'click',
        function (event) {

            if (event.target === loanModal) {

                closeLoanModal();

            }

        }
    );


    /* =====================================================
       ESC
    ===================================================== */

    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Escape') {

                closeLoanModal();

                if (deleteModal) {

                    deleteModal.classList.remove(
                        'show'
                    );

                }

            }

        }
    );


    /* =====================================================
       TÌM KIẾM + LỌC
    ===================================================== */

    function filterLoans() {

        const keyword =
            loanSearch.value
                .trim()
                .toLowerCase();

        const status =
            loanStatus.value;

        const rows =
            document.querySelectorAll(
                '.loan-row'
            );

        rows.forEach(function (row) {

            const searchData =
                row.dataset.search || '';

            const rowStatus =
                row.dataset.status || '';

            const matchKeyword =
                keyword === '' ||
                searchData.includes(keyword);

            const matchStatus =
                status === '' ||
                rowStatus === status;

            row.style.display =
                (
                    matchKeyword &&
                    matchStatus
                )
                    ? ''
                    : 'none';

        });

    }


    loanSearch.addEventListener(
        'input',
        filterLoans
    );

    loanStatus.addEventListener(
        'change',
        filterLoans
    );


    /* =====================================================
       NGÀY TRẢ >= NGÀY MƯỢN
    ===================================================== */

    ngayMuon.addEventListener(
        'change',
        function () {

            if (this.value) {

                ngayTra.min =
                    this.value;

            } else {

                ngayTra.removeAttribute('min');

            }

        }
    );


    /* =====================================================
       SUBMIT
    ===================================================== */

    loanForm.addEventListener(
        'submit',
        function (event) {

            if (
                ngayMuon.value &&
                ngayTra.value &&
                ngayTra.value < ngayMuon.value
            ) {

                event.preventDefault();

                alert(
                    'Ngày trả không được trước ngày mượn.'
                );

                ngayTra.focus();

            }

        }
    );

});

</script>
```
