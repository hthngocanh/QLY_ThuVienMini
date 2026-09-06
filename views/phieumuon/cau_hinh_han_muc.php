<?php
// src/View/phieumuon/cau_hinh_han_muc.php
// Nhận từ PhieuMuonController::cauHinhHanMuc():
//   $cauHinh, $errors, $thongBao, $activePage

$v = function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};

$cauHinh = $cauHinh ?? ['so_ngay_muon' => 14, 'so_sach_toi_da' => 5, 'cap_nhat_luc' => null];
$errors = $errors ?? [];
$thongBao = $thongBao ?? '';
$activePage = $activePage ?? 'phieumuon';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../../layout/sidebar.php'; ?>

    <div class="main-content">

<style>
    .phieu-main {
        flex: 1;
        min-width: 0;
        min-height: 100vh;
        padding: 28px 32px 50px;
        background: #f8fafc;
    }

    .module-hero {
        background: #FFFFFF;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 24px;
        color: #0f172a;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    }

    .module-hero h1 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.4px;
    }

    .module-hero p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

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

    .general-error {
        margin-bottom: 16px;
        padding: 11px 13px;
        border-radius: 9px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        font-size: 13px;
    }

    .config-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.05);
        max-width: 560px;
    }

    .config-card-header {
        padding: 20px 22px;
        border-bottom: 1px solid #e2e8f0;
    }

    .config-card-title {
        margin: 0;
        color: #0f172a;
        font-size: 18px;
        font-weight: 800;
    }

    .config-card-subtitle {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .config-card-body {
        padding: 22px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
        margin-bottom: 18px;
    }

    .form-group:last-of-type {
        margin-bottom: 0;
    }

    .form-label {
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    .form-hint {
        color: #94a3b8;
        font-size: 12px;
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
        font-size: 14px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .form-error {
        color: #dc2626;
        font-size: 12px;
        font-weight: 600;
    }

    .form-footer {
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .last-updated {
        color: #94a3b8;
        font-size: 12px;
    }

    .btn-primary {
        border: none;
        padding: 11px 20px;
        border-radius: 10px;
        background: #2563eb;
        color: white;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }
</style>

<main class="phieu-main">

    <section class="module-hero">
        <h1>Cấu hình hạn mức</h1>
        <p>Thiết lập số ngày mượn tối đa và số sách một độc giả được mượn cùng lúc.</p>
    </section>

    <?php if (!empty($thongBao)): ?>
        <div class="alert-success">✓ <span><?= $v($thongBao) ?></span></div>
    <?php endif; ?>

    <section class="config-card">

        <div class="config-card-header">
            <h2 class="config-card-title">Thông số hạn mức mượn sách</h2>
            <p class="config-card-subtitle">Áp dụng cho tất cả phiếu mượn mới được tạo sau khi lưu.</p>
        </div>

        <div class="config-card-body">

            <?php if (!empty($errors['general'])): ?>
                <div class="general-error"><?= $v($errors['general']) ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=phieumuon&action=cauHinhHanMuc">

                <div class="form-group">
                    <label class="form-label">Số ngày mượn tối đa (ngày)</label>
                    <input
                        type="number"
                        min="1"
                        class="form-control"
                        name="so_ngay_muon"
                        value="<?= $v($cauHinh['so_ngay_muon'] ?? 14) ?>"
                    >
                    <span class="form-hint">Dùng để tự tính "Hạn trả" hiển thị cho độc giả (Ngày mượn + số ngày này).</span>
                    <?php if (!empty($errors['so_ngay_muon'])): ?>
                        <div class="form-error"><?= $v($errors['so_ngay_muon']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Số sách tối đa được mượn cùng lúc</label>
                    <input
                        type="number"
                        min="1"
                        class="form-control"
                        name="so_sach_toi_da"
                        value="<?= $v($cauHinh['so_sach_toi_da'] ?? 5) ?>"
                    >
                    <span class="form-hint">Áp dụng khi kiểm tra điều kiện cho mượn sách mới.</span>
                    <?php if (!empty($errors['so_sach_toi_da'])): ?>
                        <div class="form-error"><?= $v($errors['so_sach_toi_da']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-footer">
                    <span class="last-updated">
                        <?php if (!empty($cauHinh['cap_nhat_luc'])): ?>
                            Cập nhật lần cuối: <?= $v($cauHinh['cap_nhat_luc']) ?>
                        <?php else: ?>
                            Chưa từng cập nhật
                        <?php endif; ?>
                    </span>
                    <button type="submit" class="btn-primary">Lưu cấu hình</button>
                </div>

            </form>

        </div>
    </section>

</main>

    </div><!-- /.main-content -->
</div><!-- /.layout -->