<?php
// views/home/librarian.php
// Dashboard riêng cho vai trò Thủ thư.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION["user"] ?? [];
$hoTen = $currentUser["ho_ten"] ?? "Thủ thư";
$vaiTro = $currentUser["vai_tro"] ?? "Thủ thư";
$stats = $stats ?? [];

$tongDauSach = (int)($stats["tong_dau_sach"] ?? 0);
$tongBanSao = (int)($stats["tong_ban_sao"] ?? 0);
$tongPhieuMuon = (int)($stats["tong_phieu_muon"] ?? 0);
$choDuyet = (int)($stats["cho_duyet"] ?? 0);
$quaHan = (int)($stats["qua_han"] ?? 0);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ Thủ thư - Thư viện Mini</title>
    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #F8FAFC;
            color: #0F172A;
            min-height: 100vh;
        }

        .librarian-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .librarian-main {
            flex: 1;
            min-width: 0;
            padding: 35px 40px;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 28px;
        }

        .page-header p {
            color: #334155;
            font-size: 15px;
            margin: 0;
        }

        .page-header strong {
            color: #0F172A;
        }

        /* ================= THỐNG KÊ - CHỈ HIỂN THỊ ================= */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            flex: 0 0 52px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-blue {
            background: #EFF6FF;
            color: #2563EB;
        }

        .icon-green {
            background: #ECFDF5;
            color: #059669;
        }

        .icon-purple {
            background: #FAF5FF;
            color: #9333EA;
        }

        .stat-info h2 {
            font-size: 24px;
            line-height: 1.1;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 5px;
        }

        .stat-info span {
            font-size: 13px;
            color: #64748B;
            font-weight: 600;
        }

        /* ================= CÔNG VIỆC CẦN XỬ LÝ ================= */
        .tasks-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        }

        .tasks-header {
            margin-bottom: 20px;
        }

        .tasks-header h2 {
            font-size: 22px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 5px;
        }

        .tasks-header p {
            color: #64748B;
            font-size: 14px;
        }

        .task-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .task-item {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
            padding: 17px 18px;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            background: #FFFFFF;
        }

        .task-main {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .task-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #EFF6FF;
            color: #2563EB;
        }

        .task-item.overdue .task-icon {
            background: #FEF2F2;
            color: #DC2626;
        }

        .task-content h3 {
            font-size: 15px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 4px;
        }

        .task-content p {
            color: #64748B;
            font-size: 13.5px;
            line-height: 1.5;
        }

        .task-content strong {
            color: #1D4ED8;
        }

        .task-item.overdue .task-content strong {
            color: #DC2626;
        }

        .task-button {
            align-self: flex-start;
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 14px;
            border: 0;
            border-radius: 9px;
            background: #2563EB;
            color: #FFFFFF;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: background 0.15s ease, transform 0.15s ease;
        }

        .task-button:hover {
            background: #1D4ED8;
            transform: translateY(-1px);
        }


        .tasks-action {
            display: flex;
            justify-content: flex-end;
            margin-top: 16px;
        }

        .empty-task {
            padding: 25px 18px;
            border: 1px dashed #CBD5E1;
            border-radius: 12px;
            text-align: center;
            color: #64748B;
            font-size: 14px;
            background: #F8FAFC;
        }

        @media (max-width: 950px) {
            .librarian-main {
                padding: 24px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 650px) {
            .librarian-main {
                padding: 18px 14px;
            }

            .tasks-card {
                padding: 20px;
            }

            .task-item {
                align-items: flex-start;
                flex-direction: column;
            }

            .task-button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
<div class="librarian-layout">

    <?php require_once __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="librarian-main">

        <header class="page-header">
            <p>
                Chào mừng bạn trở lại,
                <strong><?= htmlspecialchars($hoTen) ?></strong>
                (<?= htmlspecialchars($vaiTro) ?>)
            </p>
        </header>

        <!-- 3 ô thống kê: chỉ hiển thị, không có liên kết/click -->
        <section class="stats-grid" aria-label="Thống kê thư viện">

            <div class="stat-card">
                <div class="stat-icon icon-blue">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                </div>
                <div class="stat-info">
                    <h2><?= number_format($tongDauSach) ?></h2>
                    <span>Tổng số đầu sách</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-green">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                </div>
                <div class="stat-info">
                    <h2><?= number_format($tongBanSao) ?></h2>
                    <span>Tổng số bản sao</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-purple">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        <line x1="9" y1="12" x2="15" y2="12"></line>
                        <line x1="9" y1="16" x2="13" y2="16"></line>
                    </svg>
                </div>
                <div class="stat-info">
                    <h2><?= number_format($tongPhieuMuon) ?></h2>
                    <span>Tổng số phiếu mượn</span>
                </div>
            </div>

        </section>

        <!-- Công việc cần xử lý -->
        <section class="tasks-card">
            <div class="tasks-header">
                <h2>Công việc cần xử lý</h2>
                <p>Các yêu cầu cần thủ thư kiểm tra và xử lý.</p>
            </div>

            <div class="task-list">

                <?php if ($choDuyet > 0): ?>
                    <div class="task-item">
                        <div class="task-main">
                            <div class="task-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                    <line x1="9" y1="12" x2="15" y2="12"></line>
                                    <line x1="9" y1="16" x2="13" y2="16"></line>
                                </svg>
                            </div>
                            <div class="task-content">
                                <h3>Phiếu mượn chờ duyệt</h3>
                                <p>
                                    Hiện có <strong><?= number_format($choDuyet) ?> phiếu mượn</strong>
                                    đang chờ bạn xử lý.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($quaHan > 0): ?>
                    <div class="task-item overdue">
                        <div class="task-main">
                            <div class="task-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <polyline points="12 7 12 12 15 14"></polyline>
                                </svg>
                            </div>
                            <div class="task-content">
                                <h3>Phiếu mượn quá hạn</h3>
                                <p>
                                    Có <strong><?= number_format($quaHan) ?> phiếu mượn</strong>
                                    đang quá hạn cần kiểm tra.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($choDuyet > 0 || $quaHan > 0): ?>
                    <div class="tasks-action">
                        <a class="task-button" href="index.php?controller=phieumuon">
                            Xem phiếu mượn
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ($choDuyet === 0 && $quaHan === 0): ?>
                    <div class="empty-task">
                        ✓ Hiện tại không có công việc nào cần xử lý.
                    </div>
                <?php endif; ?>

            </div>
        </section>

    </main>
</div>
</body>
</html>
