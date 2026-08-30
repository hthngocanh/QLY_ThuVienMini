<?php
session_start();

require_once __DIR__ . '/functionsNguoiDung.php';

// Kiểm tra đăng nhập
$maNguoiDungHienTai = $_SESSION["maNguoiDung"] ?? $_SESSION["ma_nguoi_dung"] ?? "";

if ($maNguoiDungHienTai === "") {
    header("Location: ../dangnhap.php");
    exit;
}

// Lấy thông tin tài khoản hiện tại từ Database
$currentUser = layNguoiDungTheoMa($maNguoiDungHienTai);

if (!$currentUser) {
    header("Location: ../dangnhap.php");
    exit;
}

$errors = [];
$thongBao = "";
$loaiThongBao = "";

// ==================================================
// XỬ LÝ CẬP NHẬT THÔNG TIN CÁ NHÂN (POST)
// ==================================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hoTen = trim($_POST["hoTen"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $sdt = trim($_POST["sdt"] ?? "");
    $khoaLop = trim($_POST["khoaLop"] ?? "");

    // Validate Họ và tên
    if ($hoTen === "") {
        $errors["hoTen"] = "Vui lòng nhập họ và tên.";
    }

    // Validate Email
    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Email không hợp lệ.";
    } elseif (kiemTraEmailTonTai($email, $maNguoiDungHienTai)) {
        $errors["email"] = "Email này đã được sử dụng bởi tài khoản khác.";
    }

    // Nếu hợp lệ -> Cập nhật thông tin cho chính tài khoản đang đăng nhập
    if (empty($errors)) {
        capNhatThongTinDocGia($maNguoiDungHienTai, $hoTen, $email, $sdt, $khoaLop);

        // Đồng bộ lại Session
        $_SESSION["hoTen"] = $_SESSION["ho_ten"] = $hoTen;
        if (isset($_SESSION["user"])) {
            $_SESSION["user"]["ho_ten"] = $hoTen;
            $_SESSION["user"]["email"] = $email;
            $_SESSION["user"]["sdt"] = $sdt;
            $_SESSION["user"]["khoa_lop"] = $khoaLop;
        }

        // Tải lại dữ liệu mới nhất
        $currentUser = layNguoiDungTheoMa($maNguoiDungHienTai);

        $thongBao = "Cập nhật thông tin cá nhân thành công.";
        $loaiThongBao = "success";
    } else {
        $thongBao = "Vui lòng kiểm tra lại thông tin đã nhập.";
        $loaiThongBao = "error";
    }
}

$activePage = 'nguoidung';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân - Thư viện Mini</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Scoped CSS cho trang Thông tin cá nhân */
        .user-page {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            color: #1e293b;
        }

        .user-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 20px;
        }

        .user-alert {
            padding: 13px 18px;
            border-radius: 8px;
            margin-bottom: 22px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        .user-alert.success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .user-alert.error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* 1. KHUNG THÔNG TIN TỔNG QUAN */
        .user-profile-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px 28px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            gap: 22px;
            width: 100%;
            box-sizing: border-box;
        }

        .user-profile-avatar {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .user-profile-info {
            flex: 1;
        }

        .user-profile-info h2 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px 0;
            line-height: 1.2;
        }

        .user-profile-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 16px 28px;
            font-size: 14px;
            color: #475569;
        }

        .user-profile-meta span strong {
            color: #0f172a;
            font-weight: 600;
        }

        .user-status-tag {
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .user-status-tag.active {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .user-status-tag.locked {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* 2. KHUNG CẬP NHẬT THÔNG TIN CÁ NHÂN */
        .user-form-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 28px 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            width: 100%;
            box-sizing: border-box;
        }

        .user-form-card-title {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin: 0 0 22px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .user-form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px 30px;
            margin-bottom: 24px;
        }

        .user-form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .user-form-group label {
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
        }

        .user-form-group input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            color: #0f172a;
            background-color: #ffffff;
            box-sizing: border-box;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .user-form-group input:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .user-form-group input[readonly],
        .user-form-group input:disabled {
            background-color: #f8fafc;
            color: #64748b;
            border-color: #e2e8f0;
            cursor: not-allowed;
        }

        .user-error-text {
            color: #dc2626;
            font-size: 12.5px;
            margin-top: 2px;
        }

        .user-input-error {
            border-color: #dc2626 !important;
        }

        .user-form-actions {
            display: flex;
            justify-content: flex-end;
            padding-top: 8px;
        }

        .user-btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 26px;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14.5px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .user-btn-save:hover {
            background-color: #1d4ed8;
        }

        .user-btn-save:active {
            transform: translateY(1px);
        }

        @media (max-width: 768px) {
            .user-profile-card {
                flex-direction: column;
                text-align: center;
            }

            .user-profile-meta {
                justify-content: center;
            }

            .user-form-grid {
                grid-template-columns: 1fr;
            }

            .user-form-actions {
                justify-content: stretch;
            }

            .user-btn-save {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <div class="layout">
        <!-- Nhúng Sidebar dùng chung -->
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <!-- Vùng nội dung chính -->
        <main class="main-content">
            <div class="user-page">

                <!-- Thông báo trạng thái -->
                <?php if ($thongBao !== ""): ?>
                    <div class="user-alert <?= $loaiThongBao ?>">
                        <span><?= $loaiThongBao === "success" ? "✅" : "⚠️" ?></span>
                        <span><?= htmlspecialchars($thongBao) ?></span>
                    </div>
                <?php endif; ?>

                <!-- KHUNG 1: THÔNG TIN TỔNG QUAN -->
                <div class="user-profile-card">
                    <div class="user-profile-avatar">
                        <?= mb_strtoupper(mb_substr($currentUser["ho_ten"] ?? "S", 0, 1, "UTF-8"), "UTF-8") ?>
                    </div>
                    <div class="user-profile-info">
                        <h2><?= htmlspecialchars($currentUser["ho_ten"]) ?></h2>
                        <div class="user-profile-meta">
                            <span>Mã sinh viên: <strong><?= htmlspecialchars($currentUser["ma_nguoi_dung"]) ?></strong></span>
                            <span>Khoa/Lớp: <strong><?= htmlspecialchars($currentUser["khoa_lop"] ?: "Chưa cập nhật") ?></strong></span>
                            <span>Trạng thái:
                                <span class="user-status-tag <?= $currentUser["trang_thai"] === 'Hoạt động' ? 'active' : 'locked' ?>">
                                    <?= htmlspecialchars($currentUser["trang_thai"]) ?>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- KHUNG 2: CẬP NHẬT THÔNG TIN CÁ NHÂN -->
                <div class="user-form-card">
                    <div class="user-form-card-title">
                        <span>✏️ Thông tin cá nhân</span>
                    </div>

                    <form method="POST" novalidate>
                        <div class="user-form-grid">
                            <!-- Hàng 1 -->
                            <div class="user-form-group">
                                <label for="maNguoiDung">Mã sinh viên</label>
                                <input
                                    type="text"
                                    id="maNguoiDung"
                                    value="<?= htmlspecialchars($currentUser["ma_nguoi_dung"]) ?>"
                                    readonly
                                    title="Mã sinh viên không thể thay đổi">
                            </div>

                            <div class="user-form-group">
                                <label for="hoTen">Họ và tên *</label>
                                <input
                                    type="text"
                                    id="hoTen"
                                    name="hoTen"
                                    value="<?= htmlspecialchars($currentUser["ho_ten"]) ?>"
                                    placeholder="Nhập họ và tên"
                                    class="<?= isset($errors["hoTen"]) ? "user-input-error" : "" ?>"
                                    required>
                                <?php if (isset($errors["hoTen"])): ?>
                                    <span class="user-error-text"><?= htmlspecialchars($errors["hoTen"]) ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Hàng 2 -->
                            <div class="user-form-group">
                                <label for="email">Email *</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="<?= htmlspecialchars($currentUser["email"]) ?>"
                                    placeholder="VD: an@gmail.com"
                                    class="<?= isset($errors["email"]) ? "user-input-error" : "" ?>"
                                    required>
                                <?php if (isset($errors["email"])): ?>
                                    <span class="user-error-text"><?= htmlspecialchars($errors["email"]) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="user-form-group">
                                <label for="sdt">Số điện thoại</label>
                                <input
                                    type="text"
                                    id="sdt"
                                    name="sdt"
                                    value="<?= htmlspecialchars($currentUser["sdt"] ?? "") ?>"
                                    placeholder="VD: 0912345678">
                            </div>

                            <!-- Hàng 3 -->
                            <div class="user-form-group">
                                <label for="khoaLop">Khoa / Lớp</label>
                                <input
                                    type="text"
                                    id="khoaLop"
                                    name="khoaLop"
                                    value="<?= htmlspecialchars($currentUser["khoa_lop"] ?? "") ?>"
                                    placeholder="VD: Công nghệ thông tin - K68">
                            </div>

                            <div class="user-form-group">
                                <label for="vaiTro">Vai trò</label>
                                <input
                                    type="text"
                                    id="vaiTro"
                                    value="<?= htmlspecialchars($currentUser["vai_tro"]) ?>"
                                    readonly
                                    title="Vai trò không thể thay đổi">
                            </div>
                        </div>

                        <div class="user-form-actions">
                            <button type="submit" class="user-btn-save">
                                💾 Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

</body>

</html>