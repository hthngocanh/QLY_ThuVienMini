<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xác định đường dẫn gốc của ứng dụng web
$appRoot = '/QLY_ThuVienMini/';

// Tự động nhận diện trang hiện tại nếu chưa đặt biến $activePage
$currentScript = $_SERVER['SCRIPT_NAME'] ?? '';
$getController = strtolower($_GET['controller'] ?? '');

if (!isset($activePage)) {
    if ($getController === 'user' || $getController === 'nguoidung' || strpos($currentScript, 'nguoiDung') !== false || strpos($currentScript, 'User.php') !== false) {
        $activePage = 'nguoidung';
    } elseif ($getController === 'dausach' || $getController === 'book' || strpos($currentScript, 'dausach') !== false) {
        $activePage = 'dausach';
    } elseif ($getController === 'bansao' || $getController === 'book_copy' || strpos($currentScript, 'banSaoSach') !== false || strpos($currentScript, 'bansao.php') !== false) {
        $activePage = 'bansao';
    } elseif ($getController === 'phieumuon' || $getController === 'borrow_slip' || strpos($currentScript, 'phieuMuon') !== false || strpos($currentScript, 'phieumuon.php') !== false) {
        $activePage = 'phieumuon';
    } elseif ($getController === 'danhmuc' || $getController === 'category' || strpos($currentScript, 'danhmucsach') !== false || strpos($currentScript, 'danhmuc.php') !== false) {
        $activePage = 'danhmuc';
    } else {
        $activePage = 'trangchu';
    }
}

// Thông tin người dùng đăng nhập
$isLoggedIn = isset($_SESSION["user"]);
$currentUser = $_SESSION["user"] ?? null;

$hoTen = $currentUser["ho_ten"] ?? "Khách";
$vaiTro = $currentUser["vai_tro"] ?? "Khách vãng lai";
$maNguoiDung = $currentUser["ma_nguoi_dung"] ?? "";

// Phân loại màu sắc huy hiệu theo vai trò (Role Badge)
$roleBadgeClass = "badge-guest";
if ($vaiTro === "Quản trị viên") {
    $roleBadgeClass = "badge-admin";
} elseif ($vaiTro === "Thủ thư") {
    $roleBadgeClass = "badge-librarian";
} elseif ($vaiTro === "Độc giả") {
    $roleBadgeClass = "badge-reader";
}
?>

<!-- ================= CSS DÙNG CHUNG CHO SIDEBAR ================= -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $appRoot ?>assets/css/design-system.css">

<style>
    :root {
        --sb-primary: var(--primary);
        --sb-primary-hover: var(--primary-dark);
        --sb-primary-light: var(--primary-light);
        --sb-bg: var(--white);
        --sb-text: var(--text-body);
        --sb-text-muted: var(--text-secondary);
        --sb-text-heading: var(--text-primary);
        --sb-border: var(--border);
        --sb-active-bg: var(--primary-light);
        --sb-active-text: var(--primary);
        --sb-hover-bg: var(--primary-light);
        --sb-hover-text: var(--primary);
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        min-height: 100vh !important;
        background-color: #F8FAFC !important;
    }

    .layout {
        display: flex !important;
        min-height: 100vh !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background-color: #F8FAFC !important;
    }

    .sidebar {
        width: 260px !important;
        min-width: 260px !important;
        max-width: 260px !important;
        flex: 0 0 260px !important;
        background: var(--sb-bg) !important;
        color: var(--sb-text) !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        border-right: 1px solid var(--sb-border) !important;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.02) !important;
        position: sticky !important;
        top: 0 !important;
        left: 0 !important;
        height: 100vh !important;
        z-index: 999 !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        box-sizing: border-box !important;
    }

    .main-content, .main {
        flex: 1 1 0% !important;
        min-width: 0 !important;
        padding: 35px 40px !important;
        overflow-y: auto !important;
        box-sizing: border-box !important;
        background-color: #F8FAFC !important;
    }

    .sidebar-top {
        padding: 24px 16px 12px;
        overflow-y: auto;
    }

    .logo-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 6px 20px;
        border-bottom: 1px solid var(--sb-border);
    }

    .logo-icon {
        width: 40px;
        height: 40px;
        background: var(--sb-primary-light);
        color: var(--sb-primary);
        border: 1px solid #BFDBFE;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .logo-text h2 {
        font-size: 17px;
        font-weight: 800;
        letter-spacing: -0.3px;
        color: #1E3A8A;
        margin: 0;
        line-height: 1.2;
    }

    .logo-text span {
        font-size: 11.5px;
        color: var(--sb-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    .nav-section {
        margin-top: 20px;
    }

    .nav-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--sb-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 0 10px 10px;
    }

    .menu {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .menu-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        color: var(--sb-text);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.15s ease;
    }

    .menu-link .icon {
        font-size: 18px;
        width: 22px;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.15s ease;
    }

    .menu-link:hover {
        color: var(--sb-hover-text);
        background: var(--sb-hover-bg);
        font-weight: 600;
    }

    .menu-link:hover .icon {
        transform: scale(1.08);
    }

    .menu-link.active {
        color: var(--sb-active-text);
        background: var(--sb-active-bg);
        font-weight: 600;
        border-left: 3px solid var(--sb-primary);
        border-radius: 0 8px 8px 0;
        padding-left: 11px;
    }

    /* ================= USER PROFILE WIDGET ================= */
    .sidebar-bottom {
        padding: 14px 16px 20px;
        border-top: 1px solid var(--sb-border);
        position: relative;
        background: var(--sb-bg);
    }

    .user-card {
        background: #F8FAFC;
        border: 1px solid var(--sb-border);
        border-radius: 10px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
    }

    .user-card:hover, .user-card.active {
        background: #EFF6FF;
        border-color: #BFDBFE;
    }

    .user-info-left {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow: hidden;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--sb-primary);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }

    .user-details {
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .user-name {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--sb-text-heading);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 125px;
    }

    /* Role Badges */
    .role-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 20px;
        width: fit-content;
    }

    .badge-admin {
        background: #FEE2E2;
        color: #B91C1C;
        border: 1px solid #FECACA;
    }

    .badge-librarian {
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
    }

    .badge-reader {
        background: #DCFCE7;
        color: #15803D;
        border: 1px solid #BBF7D0;
    }

    .badge-guest {
        background: #F1F5F9;
        color: #64748B;
        border: 1px solid #E2E8F0;
    }

    .user-dropdown-arrow {
        color: var(--sb-text-muted);
        font-size: 10px;
        transition: transform 0.2s ease;
    }

    .user-card.active .user-dropdown-arrow {
        transform: rotate(180deg);
    }

    /* Dropdown Menu */
    .user-dropdown-menu {
        position: absolute;
        bottom: 78px;
        left: 16px;
        right: 16px;
        background: #FFFFFF;
        border: 1px solid var(--sb-border);
        border-radius: 12px;
        padding: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        display: none;
        flex-direction: column;
        gap: 4px;
        animation: dropdownFadeIn 0.15s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 1000;
    }

    .user-dropdown-menu.show {
        display: flex;
    }

    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        color: var(--sb-text);
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.15s ease;
    }

    .dropdown-item:hover {
        background: #EFF6FF;
        color: var(--sb-primary);
    }

    .dropdown-item.logout {
        color: #DC2626;
    }

    .dropdown-item.logout:hover {
        background: #FEF2F2;
        color: #991B1B;
    }

    .dropdown-divider {
        height: 1px;
        background: var(--sb-border);
        margin: 4px 0;
    }

    .btn-login-now {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 11px;
        background: var(--sb-primary);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
        transition: background 0.15s ease;
    }

    .btn-login-now:hover {
        background: var(--sb-primary-hover);
    }
</style>

<!-- ================= SIDEBAR COMPONENT HTML ================= -->
<aside class="sidebar">

    <div class="sidebar-top">

        <!-- Logo -->
        <div class="logo-box">
            <div class="logo-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
            </div>
            <div class="logo-text">
                <h2>Thư viện Mini</h2>
                <span>Library System</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="nav-section">
            <div class="nav-label">Menu độc giả</div>

            <nav class="menu">
                <a href="<?= $appRoot ?>index.php" class="menu-link <?= $activePage === 'trangchu' ? 'active' : '' ?>">
                    <span class="icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </span>
                    <span>Trang chủ</span>
                </a>

                <?php
                $userMenuLabel = "Người dùng";
                if ($vaiTro === "Độc giả") {
                    $userMenuLabel = "Thông tin cá nhân";
                } elseif ($vaiTro === "Thủ thư") {
                    $userMenuLabel = "Tra cứu độc giả";
                } elseif ($vaiTro === "Quản trị viên") {
                    $userMenuLabel = "Người dùng";
                }
                ?>
                <a href="<?= $appRoot ?>index.php?controller=user&action=profile" class="menu-link <?= $activePage === 'nguoidung' ? 'active' : '' ?>">
                    <span class="icon">
                        <?php if ($vaiTro === "Độc giả"): ?>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        <?php elseif ($vaiTro === "Thủ thư"): ?>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        <?php else: ?>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        <?php endif; ?>
                    </span>
                    <span><?= $userMenuLabel ?></span>
                </a>

                <a href="<?= $appRoot ?>index.php?controller=phieumuon" class="menu-link <?= $activePage === 'phieumuon' ? 'active' : '' ?>">
                    <span class="icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            <line x1="9" y1="12" x2="15" y2="12"></line>
                            <line x1="9" y1="16" x2="13" y2="16"></line>
                        </svg>
                    </span>
                    <span>Phiếu mượn</span>
                </a>
            </nav>
        </div>

    </div>

    <!-- User Profile Footer -->
    <div class="sidebar-bottom">

        <?php if ($isLoggedIn): ?>
            <!-- Dropdown Menu -->
            <div class="user-dropdown-menu" id="userDropdownMenu">
                <div style="padding: 6px 12px; font-size: 11.5px; color: var(--sb-text-muted); border-bottom: 1px solid var(--sb-border); margin-bottom: 4px;">
                    Mã: <strong><?= htmlspecialchars($maNguoiDung) ?></strong>
                </div>
                <?php if ($vaiTro === "Độc giả"): ?>
                    <a href="<?= $appRoot ?>index.php?controller=auth&action=change_password" class="dropdown-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <span>Đổi mật khẩu</span>
                    </a>
                    <div class="dropdown-divider"></div>
                <?php endif; ?>
                <a href="<?= $appRoot ?>index.php?controller=auth&action=logout" class="dropdown-item logout">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Đăng xuất</span>
                </a>
            </div>

            <!-- User Trigger Button -->
            <div class="user-card" id="userCardTrigger" onclick="toggleSidebarUserDropdown(event)">
                <div class="user-info-left">
                    <div class="user-avatar">
                        <?= mb_strtoupper(mb_substr($hoTen, 0, 1, "UTF-8"), "UTF-8") ?>
                    </div>
                    <div class="user-details">
                        <div class="user-name" title="<?= htmlspecialchars($hoTen) ?>">
                            <?= htmlspecialchars($hoTen) ?>
                        </div>
                        <span class="role-badge <?= $roleBadgeClass ?>">
                            <?= htmlspecialchars($vaiTro) ?>
                        </span>
                    </div>
                </div>
                <div class="user-dropdown-arrow">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="18 15 12 9 6 15"></polyline>
                    </svg>
                </div>
            </div>

        <?php else: ?>
            <a href="<?= $appRoot ?>dangnhap.php" class="btn-login-now">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <polyline points="10 17 15 12 10 7"></polyline>
                    <line x1="15" y1="12" x2="3" y2="12"></line>
            </a>
        <?php endif; ?>

    </div>

</aside>

<script>
    function toggleSidebarUserDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('userDropdownMenu');
        const card = document.getElementById('userCardTrigger');
        if (dropdown) {
            dropdown.classList.toggle('show');
            card.classList.toggle('active');
        }
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdownMenu');
        const card = document.getElementById('userCardTrigger');
        if (dropdown && dropdown.classList.contains('show')) {
            if (!dropdown.contains(event.target) && !card.contains(event.target)) {
                dropdown.classList.remove('show');
                card.classList.remove('active');
            }
        }
    });
</script>
