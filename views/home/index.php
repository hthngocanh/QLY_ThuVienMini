<?php
// views/home/index.php

$isLoggedIn = isset($_SESSION["user"]);

if ($isLoggedIn):
    $activePage = 'trangchu';
    $currentUser = $_SESSION["user"];
    $hoTen = $currentUser["ho_ten"] ?? "Người dùng";
    $vaiTro = $currentUser["vai_tro"] ?? "Độc giả";
?>
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

<?php
else:
    // Giao diện Landing Page cho khách
?>
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
