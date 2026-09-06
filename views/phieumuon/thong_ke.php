<?php
// src/View/phieumuon/thong_ke.php
// Nhận từ PhieuMuonController::thongKe():
//   $thongKeTheoThang (mảng 12 phần tử, key = tháng 1-12),
//   $topSach, $thongKeTongQuan, $nam, $activePage

$v = function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};

$thongKeTheoThang = $thongKeTheoThang ?? array_fill(1, 12, 0);
$topSach = $topSach ?? [];
$thongKeTongQuan = $thongKeTongQuan ?? ['tong' => 0, 'cho_duyet' => 0, 'dang_muon' => 0, 'qua_han' => 0, 'da_tra' => 0];
$nam = $nam ?? (int)date('Y');
$activePage = $activePage ?? 'phieumuon';

$maxThang = max(1, max($thongKeTheoThang));
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
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
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

    .year-select {
        height: 42px;
        padding: 0 32px 0 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: white;
        color: #334155;
        font-weight: 600;
        outline: none;
        cursor: pointer;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
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

    .charts-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 20px;
    }

    .chart-card, .top-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.05);
    }

    .card-title {
        margin: 0 0 18px;
        color: #0f172a;
        font-size: 16px;
        font-weight: 800;
    }

    /* Biểu đồ cột đơn giản bằng CSS, không cần thư viện JS */
    .bar-chart {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        height: 220px;
        padding-top: 10px;
    }

    .bar-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        height: 100%;
        justify-content: flex-end;
    }

    .bar {
        width: 100%;
        max-width: 28px;
        background: linear-gradient(180deg, #3b82f6, #2563eb);
        border-radius: 6px 6px 0 0;
        transition: height 0.3s ease;
    }

    .bar-value {
        font-size: 11px;
        font-weight: 700;
        color: #334155;
    }

    .bar-label {
        font-size: 11px;
        color: #94a3b8;
    }

    .top-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .top-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .top-rank {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #eff6ff;
        color: #2563eb;
        font-size: 12px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .top-info {
        flex: 1;
        min-width: 0;
    }

    .top-name {
        color: #0f172a;
        font-size: 13.5px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .top-count {
        color: #64748b;
        font-size: 12px;
    }

    .empty-state {
        text-align: center;
        padding: 30px 10px;
        color: #94a3b8;
        font-size: 13px;
    }

    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .charts-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 600px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

<main class="phieu-main">

    <section class="module-hero">
        <div>
            <h1>Bảng thống kê phiếu mượn</h1>
            <p>Tổng quan số lượng phiếu mượn và sách được mượn nhiều nhất.</p>
        </div>

        <form method="GET" action="index.php">
            <input type="hidden" name="controller" value="phieumuon">
            <input type="hidden" name="action" value="thongKe">
            <select name="nam" class="year-select" onchange="this.form.submit()">
                <?php for ($y = (int)date('Y'); $y >= (int)date('Y') - 4; $y--): ?>
                    <option value="<?= $y ?>" <?= $y === $nam ? 'selected' : '' ?>>Năm <?= $y ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </section>

    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Tổng phiếu mượn</div>
            <div class="stat-number"><?= (int)$thongKeTongQuan['tong'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Chờ duyệt</div>
            <div class="stat-number"><?= (int)$thongKeTongQuan['cho_duyet'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Đang mượn</div>
            <div class="stat-number"><?= (int)$thongKeTongQuan['dang_muon'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Quá hạn</div>
            <div class="stat-number"><?= (int)$thongKeTongQuan['qua_han'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Đã trả</div>
            <div class="stat-number"><?= (int)$thongKeTongQuan['da_tra'] ?></div>
        </div>
    </section>

    <section class="charts-grid">

        <div class="chart-card">
            <h3 class="card-title">Số phiếu mượn theo tháng - Năm <?= $nam ?></h3>

            <div class="bar-chart">
                <?php foreach ($thongKeTheoThang as $thang => $soLuong): ?>
                    <?php $heightPercent = $maxThang > 0 ? round(($soLuong / $maxThang) * 100) : 0; ?>
                    <div class="bar-col">
                        <span class="bar-value"><?= $soLuong ?></span>
                        <div class="bar" style="height: <?= max(4, $heightPercent) ?>%;"></div>
                        <span class="bar-label">T<?= $thang ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="top-card">
            <h3 class="card-title">Top sách được mượn nhiều nhất</h3>

            <?php if (empty($topSach)): ?>
                <div class="empty-state">Chưa có dữ liệu.</div>
            <?php else: ?>
                <div class="top-list">
                    <?php foreach ($topSach as $index => $sach): ?>
                        <div class="top-item">
                            <div class="top-rank"><?= $index + 1 ?></div>
                            <div class="top-info">
                                <div class="top-name"><?= $v($sach['ten_sach'] ?? '') ?></div>
                                <div class="top-count"><?= (int)($sach['so_lan_muon'] ?? 0) ?> lượt mượn</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </section>

</main>

    </div><!-- /.main-content -->
</div><!-- /.layout -->