<?php
// src/View/phieumuon/lich_su.php
// Nhận từ PhieuMuonController::index() (nhánh Độc giả):
//   $danhSachPhieuMuon (đã có sẵn key 'HanTra'), $thongBao,
//   $tuKhoa, $trangThaiLoc, $activePage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$v = function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};

$danhSachPhieuMuon = $danhSachPhieuMuon ?? [];
$thongBao = $thongBao ?? '';
$trangThaiLoc = $trangThaiLoc ?? '';
$activePage = $activePage ?? 'phieumuon';

// Đếm nhanh cho 3 ô số liệu (trên chính danh sách của người này)
$soDangMuon = 0;
$soQuaHan = 0;
$soDaTra = 0;

foreach ($danhSachPhieuMuon as $p) {
    if (($p['TrangThai'] ?? '') === 'Đang mượn') $soDangMuon++;
    elseif (($p['TrangThai'] ?? '') === 'Quá hạn') $soQuaHan++;
    elseif (($p['TrangThai'] ?? '') === 'Đã trả') $soDaTra++;
}
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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
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

    .management-panel {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.05);
    }

    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 22px;
        border-bottom: 1px solid #e2e8f0;
        flex-wrap: wrap;
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

    .status-filter {
        flex-shrink: 0;
        width: 180px;
        padding: 11px 30px 11px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: white;
        color: #334155;
        outline: none;
        cursor: pointer;
        box-sizing: border-box;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .loan-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 820px;
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

    .loan-table tbody tr:hover {
        background: #f8fafc;
    }

    .book-name {
        font-weight: 700;
        color: #0f172a;
    }

    .muted {
        color: #64748b;
    }

    .han-tra-warning {
        color: #dc2626;
        font-weight: 700;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-cho-duyet { background: #fef3c7; color: #92400e; }
    .status-dang-muon { background: #dbeafe; color: #1e40af; }
    .status-qua-han   { background: #fee2e2; color: #991b1b; }
    .status-da-tra    { background: #dcfce7; color: #166534; }

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

    @media (max-width: 900px) {
        .phieu-main { padding: 20px 16px 40px; }
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

<main class="phieu-main">

    <section class="module-hero">
        <h1>Lịch sử mượn</h1>
        <p>Danh sách các cuốn sách bạn đã mượn và trạng thái hiện tại.</p>
    </section>

    <?php if (!empty($thongBao)): ?>
        <div class="alert-success">✓ <span><?= $v($thongBao) ?></span></div>
    <?php endif; ?>

    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Đang mượn</div>
            <div class="stat-number"><?= $soDangMuon ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Quá hạn</div>
            <div class="stat-number"><?= $soQuaHan ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Đã trả</div>
            <div class="stat-number"><?= $soDaTra ?></div>
        </div>
    </section>

    <section class="management-panel">

        <div class="panel-header">
            <div>
                <h2 class="panel-title">Danh sách sách đã mượn</h2>
                <p class="panel-subtitle">Theo dõi tình trạng và hạn trả từng cuốn sách.</p>
            </div>

            <form method="GET" action="index.php">
                <input type="hidden" name="controller" value="phieumuon">
                <select name="trang_thai" class="status-filter" onchange="this.form.submit()">
                    <option value="" <?= $trangThaiLoc === '' ? 'selected' : '' ?>>Tất cả trạng thái</option>
                    <option value="Chờ duyệt" <?= $trangThaiLoc === 'Chờ duyệt' ? 'selected' : '' ?>>Chờ duyệt</option>
                    <option value="Đang mượn" <?= $trangThaiLoc === 'Đang mượn' ? 'selected' : '' ?>>Đang mượn</option>
                    <option value="Quá hạn" <?= $trangThaiLoc === 'Quá hạn' ? 'selected' : '' ?>>Quá hạn</option>
                    <option value="Đã trả" <?= $trangThaiLoc === 'Đã trả' ? 'selected' : '' ?>>Đã trả</option>
                </select>
            </form>
        </div>

        <div class="table-wrapper">
            <table class="loan-table">
                <thead>
                    <tr>
                        <th>Tên sách</th>
                        <th>Mã bản sao</th>
                        <th>Ngày mượn</th>
                        <th>Ngày trả</th>
                        <th>Hạn trả</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (empty($danhSachPhieuMuon)): ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-state-icon">📚</div>
                            <div class="empty-state-title">Bạn chưa mượn cuốn sách nào</div>
                            <div>Hãy liên hệ thủ thư để mượn sách mới.</div>
                        </td>
                    </tr>
                <?php else: ?>

                    <?php foreach ($danhSachPhieuMuon as $phieu): ?>
                        <?php
                        $status = $phieu['TrangThai'] ?? '';
                        $statusClass = 'status-cho-duyet';

                        if ($status === 'Đang mượn') $statusClass = 'status-dang-muon';
                        elseif ($status === 'Quá hạn') $statusClass = 'status-qua-han';
                        elseif ($status === 'Đã trả') $statusClass = 'status-da-tra';
                        ?>
                        <tr>
                            <td><span class="book-name"><?= $v($phieu['ten_sach'] ?? '') ?></span></td>
                            <td><?= $v($phieu['ma_ban_sao'] ?? '') ?></td>
                            <td><?= $v($phieu['NgayMuon'] ?? '') ?></td>
                            <td>
                                <?php if (!empty($phieu['NgayTra'])): ?>
                                    <?= $v($phieu['NgayTra']) ?>
                                <?php else: ?>
                                    <span class="muted">Chưa trả</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($phieu['HanTra'])): ?>
                                    <span class="<?= $status === 'Quá hạn' ? 'han-tra-warning' : '' ?>">
                                        <?= $v($phieu['HanTra']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="status-badge <?= $statusClass ?>"><?= $v($status) ?></span></td>
                        </tr>
                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>
            </table>
        </div>

    </section>

</main>

    </div><!-- /.main-content -->
</div><!-- /.layout -->