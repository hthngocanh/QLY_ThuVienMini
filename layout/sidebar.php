<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xác định đường dẫn gốc của ứng dụng web
$appRoot = '/QLY_ThuVienMini/';

// Tự động nhận diện trang hiện tại nếu chưa đặt biến $activePage
$currentScript = $_SERVER['SCRIPT_NAME'] ?? '';
if (!isset($activePage)) {
    if (strpos($currentScript, 'nguoiDung') !== false || strpos($currentScript, 'User.php') !== false) {
        $activePage = 'nguoidung';
    } elseif (strpos($currentScript, 'dausach') !== false) {
        $activePage = 'dausach';
    } elseif (strpos($currentScript, 'banSaoSach') !== false || strpos($currentScript, 'bansao.php') !== false) {
        $activePage = 'bansao';
    } elseif (strpos($currentScript, 'phieuMuon') !== false || strpos($currentScript, 'phieumuon.php') !== false) {
        $activePage = 'phieumuon';
    } elseif (strpos($currentScript, 'danhmucsach') !== false || strpos($currentScript, 'danhmuc.php') !== false) {
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

<style>
    :root {
        --sb-primary: #2563eb;
        --sb-primary-hover: #1d4ed8;
        --sb-bg: #0f172a;
        --sb-hover: #1e293b;
        --sb-active: #2563eb;
        --sb-border: rgba(255, 255, 255, 0.08);
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        min-height: 100vh !important;
        background-color: #f8fafc !important;
    }

    .layout {
        display: flex !important;
        min-height: 100vh !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background-color: #f8fafc !important;
    }

    .sidebar {
        width: 270px !important;
        min-width: 270px !important;
        max-width: 270px !important;
        flex: 0 0 270px !important;
        background: var(--sb-bg) !important;
        color: #f8fafc !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.05) !important;
        position: sticky !important;
        top: 0 !important;
        left: 0 !important;
        height: 100vh !important;
        z-index: 999 !important;
        font-family: 'Inter', Arial, sans-serif !important;
        box-sizing: border-box !important;
    }

    .main-content, .main {
        flex: 1 1 0% !important;
        min-width: 0 !important;
        padding: 35px 40px !important;
        overflow-y: auto !important;
        box-sizing: border-box !important;
        background-color: #f8fafc !important;
    }

    .sidebar-top {
        padding: 24px 18px 12px;
        overflow-y: auto;
    }

    .logo-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 8px 24px;
        border-bottom: 1px solid var(--sb-border);
    }

    .logo-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
        flex-shrink: 0;
    }

    .logo-text h2 {
        font-size: 17px;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: #ffffff;
        margin: 0;
        line-height: 1.2;
    }

    .logo-text span {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .nav-section {
        margin-top: 20px;
    }

    .nav-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 0 12px 10px;
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
        padding: 12px 14px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .menu-link .icon {
        font-size: 18px;
        width: 22px;
        text-align: center;
        transition: transform 0.2s ease;
    }

    .menu-link:hover {
        color: #ffffff;
        background: var(--sb-hover);
    }

    .menu-link:hover .icon {
        transform: scale(1.1);
    }

    .menu-link.active {
        color: #ffffff;
        background: var(--sb-active);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        font-weight: 600;
    }

    /* ================= USER PROFILE WIDGET ================= */
    .sidebar-bottom {
        padding: 16px 18px 24px;
        border-top: 1px solid var(--sb-border);
        position: relative;
        background: var(--sb-bg);
    }

    .user-card {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--sb-border);
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .user-card:hover, .user-card.active {
        background: rgba(255, 255, 255, 0.09);
        border-color: rgba(255, 255, 255, 0.16);
    }

    .user-info-left {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow: hidden;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
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
        color: #ffffff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 130px;
    }

    /* Role Badges */
    .role-badge {
        display: inline-block;
        font-size: 10.5px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 20px;
        text-transform: capitalize;
        width: fit-content;
    }

    .badge-admin {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .badge-librarian {
        background: rgba(14, 165, 233, 0.2);
        color: #38bdf8;
        border: 1px solid rgba(14, 165, 233, 0.3);
    }

    .badge-reader {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .badge-guest {
        background: rgba(148, 163, 184, 0.2);
        color: #cbd5e1;
        border: 1px solid rgba(148, 163, 184, 0.3);
    }

    .user-dropdown-arrow {
        color: #94a3b8;
        font-size: 11px;
        transition: transform 0.2s ease;
    }

    .user-card.active .user-dropdown-arrow {
        transform: rotate(180deg);
    }

    /* Dropdown Menu */
    .user-dropdown-menu {
        position: absolute;
        bottom: 85px;
        left: 18px;
        right: 18px;
        background: #1e293b;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        display: none;
        flex-direction: column;
        gap: 4px;
        animation: dropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 1000;
    }

    .user-dropdown-menu.show {
        display: flex;
    }

    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
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
        padding: 10px 12px;
        color: #cbd5e1;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.15s ease;
    }

    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
    }

    .dropdown-item.logout {
        color: #f87171;
    }

    .dropdown-item.logout:hover {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
    }

    .dropdown-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.08);
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
        transition: background 0.2s;
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
            <div class="logo-icon">📚</div>
            <div class="logo-text">
                <h2>Thư viện Mini</h2>
                <span>Library System</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="nav-section">
            <div class="nav-label">Quản lý hệ thống</div>

            <nav class="menu">
                <a href="<?= $appRoot ?>index.php" class="menu-link <?= $activePage === 'trangchu' ? 'active' : '' ?>">
                    <span class="icon">🏠</span>
                    <span>Trang chủ</span>
                </a>

                <?php
                $userMenuLabel = "Người dùng";
                $userMenuIcon = "👥";
                if ($vaiTro === "Độc giả") {
                    $userMenuLabel = "Thông tin cá nhân";
                    $userMenuIcon = "👤";
                } elseif ($vaiTro === "Thủ thư") {
                    $userMenuLabel = "Tra cứu độc giả";
                    $userMenuIcon = "🔍";
                } elseif ($vaiTro === "Quản trị viên") {
                    $userMenuLabel = "Người dùng";
                    $userMenuIcon = "👥";
                }
                ?>
                <a href="<?= $appRoot ?>nguoiDung/User.php" class="menu-link <?= $activePage === 'nguoidung' ? 'active' : '' ?>">
                    <span class="icon"><?= $userMenuIcon ?></span>
                    <span><?= $userMenuLabel ?></span>
                </a>

                <a href="<?= $appRoot ?>dausach/dausach.php" class="menu-link <?= $activePage === 'dausach' ? 'active' : '' ?>">
                    <span class="icon">📖</span>
                    <span>Đầu sách</span>
                </a>

                <a href="<?= $appRoot ?>banSaoSach/bansao.php" class="menu-link <?= $activePage === 'bansao' ? 'active' : '' ?>">
                    <span class="icon">📑</span>
                    <span>Bản sao sách</span>
                </a>

                <a href="<?= $appRoot ?>phieuMuon/phieumuon.php" class="menu-link <?= $activePage === 'phieumuon' ? 'active' : '' ?>">
                    <span class="icon">📋</span>
                    <span>Phiếu mượn</span>
                </a>

                <a href="<?= $appRoot ?>danhmucsach/danhmuc.php" class="menu-link <?= $activePage === 'danhmuc' ? 'active' : '' ?>">
                    <span class="icon">🏷️</span>
                    <span>Danh mục</span>
                </a>
            </nav>
        </div>

    </div>

    <!-- User Profile Footer -->
    <div class="sidebar-bottom">

        <?php if ($isLoggedIn): ?>
            <!-- Dropdown Menu -->
            <div class="user-dropdown-menu" id="userDropdownMenu">
                <div style="padding: 6px 12px; font-size: 11.5px; color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.06); margin-bottom: 4px;">
                    Mã: <strong><?= htmlspecialchars($maNguoiDung) ?></strong>
                </div>
                <a href="<?= $appRoot ?>doimatkhau.php" class="dropdown-item">
                    <span>🔑</span>
                    <span>Đổi mật khẩu</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= $appRoot ?>dangxuat.php" class="dropdown-item logout">
                    <span>🚪</span>
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
                <div class="user-dropdown-arrow">▲</div>
            </div>

        <?php else: ?>
            <a href="<?= $appRoot ?>dangnhap.php" class="btn-login-now">
                <span>🔐</span>
                <span>Đăng nhập</span>
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
