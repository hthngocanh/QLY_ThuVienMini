<?php
session_start();

$activePage = 'trangchu';
$currentUser = $_SESSION["user"] ?? null;
$hoTen = $currentUser["ho_ten"] ?? "Khách";
$vaiTro = $currentUser["vai_tro"] ?? "Khách vãng lai";
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản lý Thư viện Mini</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg-main: #f8fafc;
            --border-color: #e2e8f0;
        }

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
        <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

        <!-- Nội dung chính -->
        <main class="main">

            <div class="page-header">
                <h1>Tổng quan hệ thống</h1>
                <p>Chào mừng bạn trở lại, <strong><?= htmlspecialchars($hoTen) ?></strong> (<?= htmlspecialchars($vaiTro) ?>)</p>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid">
                <a href="dausach/dausach.php" class="stat-card">
                    <div class="stat-icon icon-blue">📖</div>
                    <div class="stat-info">
                        <h3>Đầu sách</h3>
                        <span>Quản lý kho sách</span>
                    </div>
                </a>

                <a href="banSaoSach/bansao.php" class="stat-card">
                    <div class="stat-icon icon-green">📑</div>
                    <div class="stat-info">
                        <h3>Bản sao</h3>
                        <span>Tình trạng cuốn sách</span>
                    </div>
                </a>

                <a href="phieuMuon/phieumuon.php" class="stat-card">
                    <div class="stat-icon icon-purple">📋</div>
                    <div class="stat-info">
                        <h3>Phiếu mượn</h3>
                        <span>Lịch sử mượn/trả</span>
                    </div>
                </a>

                <a href="nguoiDung/User.php" class="stat-card">
                    <div class="stat-icon icon-amber">👥</div>
                    <div class="stat-info">
                        <h3>Người dùng</h3>
                        <span>Quản lý tài khoản</span>
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