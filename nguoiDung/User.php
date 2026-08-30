<?php
require_once __DIR__ . '/functionsNguoiDung.php';
session_start();

$errors = [];
$thongBao = "";
$loaiThongBao = "";

// Các biến lưu trữ dữ liệu form
$maNguoiDung = "";
$hoTen = "";
$email = "";
$matKhau = "";
$sdt = "";
$khoaLop = "";
$vaiTro = "";
$trangThai = "";
$dangSua = false;
$maNguoiDungCu = "";

// -------------------------------------------------------------------
// XỬ LÝ CÁC HÀNH ĐỘNG TỪ GIAO DIỆN (POST)
// -------------------------------------------------------------------

// 1. Luồng Lấy dữ liệu cũ để Đắp lên form Sửa
if (isset($_POST["sua"])) {
    $maCanSua = $_POST["sua"];
    $nguoiDung = layNguoiDungTheoMa($maCanSua);

    if ($nguoiDung) {
        $maNguoiDung = $nguoiDung["ma_nguoi_dung"];
        $hoTen = $nguoiDung["ho_ten"];
        $email = $nguoiDung["email"];
        $sdt = $nguoiDung["sdt"];
        $khoaLop = $nguoiDung["khoa_lop"];
        $vaiTro = $nguoiDung["vai_tro"];
        $trangThai = $nguoiDung["trang_thai"];

        $dangSua = true;
        $maNguoiDungCu = $maCanSua;
    }
}
// 2. Luồng Xóa mềm người dùng
elseif (isset($_POST["xoa"])) {
    $maCanXoa = $_POST["xoa"];
    xoaNguoiDung($maCanXoa); // Hàm này sẽ UPDATE trạng thái thành 'Bị khóa'
    $thongBao = "Đã khóa người dùng thành công.";
    $loaiThongBao = "success";
}
// 3. Luồng Thêm mới / Cập nhật
elseif (isset($_POST["btnLuu"])) {
    $maNguoiDung = trim($_POST["maNguoiDung"] ?? "");
    $hoTen = trim($_POST["hoTen"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $matKhau = trim($_POST["matKhau"] ?? ""); // Chỉ dùng khi thêm mới
    $sdt = trim($_POST["sdt"] ?? "");
    $khoaLop = trim($_POST["khoaLop"] ?? "");
    $vaiTro = trim($_POST["vaiTro"] ?? "");
    $trangThai = trim($_POST["trangThai"] ?? "");

    if (isset($_POST["maNguoiDungCu"])) {
        $maNguoiDungCu = $_POST["maNguoiDungCu"];
        $dangSua = true;
    }

    // --- VALIDATE DỮ LIỆU ---
    if ($maNguoiDung === "") {
        $errors["maNguoiDung"] = "Vui lòng nhập mã người dùng.";
    }
    if ($hoTen === "") {
        $errors["hoTen"] = "Vui lòng nhập họ tên.";
    }
    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Email không hợp lệ.";
    }
    if ($vaiTro === "") {
        $errors["vaiTro"] = "Vui lòng chọn vai trò.";
    }
    if ($trangThai === "") {
        $errors["trangThai"] = "Vui lòng chọn trạng thái.";
    }
    if (!$dangSua && $matKhau === "") {
        $errors["matKhau"] = "Vui lòng nhập mật khẩu cho người dùng mới.";
    }

    // --- XỬ LÝ LƯU DATABASE ---
    if (empty($errors)) {
        if ($dangSua) {
            // Đang sửa
            suaNguoiDung($maNguoiDungCu, $maNguoiDung, $hoTen, $email, $sdt, $khoaLop, $vaiTro, $trangThai);
            $thongBao = "Cập nhật người dùng thành công.";
            $loaiThongBao = "success";
            $dangSua = false;
            $maNguoiDungCu = "";
        } else {
            // Đang thêm mới
            $kiemTraTonTai = layNguoiDungTheoMa($maNguoiDung);
            if ($kiemTraTonTai) {
                $errors["maNguoiDung"] = "Mã người dùng đã tồn tại trong hệ thống.";
            } else {
                $matKhauHash = password_hash($matKhau, PASSWORD_DEFAULT);
                themNguoiDung($maNguoiDung, $hoTen, $email, $matKhauHash, $sdt, $khoaLop, $vaiTro, $trangThai);
                $thongBao = "Thêm người dùng thành công.";
                $loaiThongBao = "success";

                // Reset form sau khi thêm
                $maNguoiDung = "";
                $hoTen = "";
                $email = "";
                $sdt = "";
                $khoaLop = "";
                $vaiTro = "";
                $trangThai = "";
            }
        }
    }
}

// -------------------------------------------------------------------
// LẤY DỮ LIỆU HIỂN THỊ LÊN BẢNG (KÈM TÌM KIẾM)
// -------------------------------------------------------------------
$tuKhoa = trim($_GET["tuKhoa"] ?? "");
$danhSachNguoiDung = layDanhSachNguoiDung($tuKhoa);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - Thư viện Mini</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 35px 40px;
            overflow-y: auto;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            margin-bottom: 25px;
            color: #1e3a8a;
            font-size: 26px;
            font-weight: 800;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
        }

        .card h2 {
            font-size: 18px;
            color: #1e293b;
            margin-bottom: 18px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 14px;
            color: #334155;
        }

        input,
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .input-error {
            border-color: #dc2626;
        }

        .field-error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        button {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background: #2563eb;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        button:hover {
            opacity: 0.9;
        }

        .btn-danger {
            background: #dc2626;
        }

        .message {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th,
        td {
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            text-align: center;
            font-size: 14px;
        }

        th {
            background: #2563eb;
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        .duoc-muon {
            color: #15803d;
            font-weight: bold;
        }

        .khong-duoc-muon {
            color: #dc2626;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .main-content {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="layout">
        <?php 
        $activePage = 'nguoidung';
        require_once __DIR__ . '/../layout/sidebar.php'; 
        ?>

        <main class="main-content">
            <div class="container">
                <h1>QUẢN LÝ NGƯỜI DÙNG</h1>

                <?php if ($thongBao !== ""): ?>
                    <div class="message <?= htmlspecialchars($loaiThongBao) ?>">
                        <?= htmlspecialchars($thongBao) ?>
                    </div>
                <?php endif; ?>

        <!-- FORM THÊM / SỬA -->
        <div class="card">
            <h2><?= $dangSua ? "Sửa thông tin người dùng" : "Nhập thông tin người dùng" ?></h2>
            <form method="POST" novalidate>
                <?php if ($dangSua): ?>
                    <input type="hidden" name="maNguoiDungCu" value="<?= htmlspecialchars($maNguoiDungCu) ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="maNguoiDung">Mã người dùng</label>
                        <input type="text" id="maNguoiDung" name="maNguoiDung" placeholder="VD: SV001"
                            value="<?= htmlspecialchars($maNguoiDung) ?>" class="<?= isset($errors["maNguoiDung"]) ? "input-error" : "" ?>">
                        <?php if (isset($errors["maNguoiDung"])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors["maNguoiDung"]) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="hoTen">Họ tên</label>
                        <input type="text" id="hoTen" name="hoTen" placeholder="VD: Nguyễn Văn An"
                            value="<?= htmlspecialchars($hoTen) ?>" class="<?= isset($errors["hoTen"]) ? "input-error" : "" ?>">
                        <?php if (isset($errors["hoTen"])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors["hoTen"]) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="VD: an@gmail.com"
                            value="<?= htmlspecialchars($email) ?>" class="<?= isset($errors["email"]) ? "input-error" : "" ?>">
                        <?php if (isset($errors["email"])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors["email"]) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="matKhau">Mật khẩu <?= $dangSua ? "(Bỏ trống nếu không đổi)" : "" ?></label>
                        <input type="password" id="matKhau" name="matKhau" placeholder="Nhập mật khẩu"
                            <?= $dangSua ? "disabled" : "" ?> title="Mật khẩu chỉ thiết lập khi tạo mới"
                            class="<?= isset($errors["matKhau"]) ? "input-error" : "" ?>">
                        <?php if (isset($errors["matKhau"])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors["matKhau"]) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="sdt">Số điện thoại</label>
                        <input type="text" id="sdt" name="sdt" placeholder="VD: 0912345678"
                            value="<?= htmlspecialchars($sdt) ?>">
                    </div>
                    <div class="form-group">
                        <label for="khoaLop">Khóa/Lớp</label>
                        <input type="text" id="khoaLop" name="khoaLop" placeholder="VD: Công nghệ thông tin - K68"
                            value="<?= htmlspecialchars($khoaLop) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="vaiTro">Vai trò</label>
                        <select id="vaiTro" name="vaiTro" class="<?= isset($errors["vaiTro"]) ? "input-error" : "" ?>">
                            <option value="">-- Chọn vai trò --</option>
                            <option value="Độc giả" <?= $vaiTro === "Độc giả" ? "selected" : "" ?>>Độc giả</option>
                            <option value="Thủ thư" <?= $vaiTro === "Thủ thư" ? "selected" : "" ?>>Thủ thư</option>
                            <option value="Quản trị viên" <?= $vaiTro === "Quản trị viên" ? "selected" : "" ?>>Quản trị viên</option>
                        </select>
                        <?php if (isset($errors["vaiTro"])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors["vaiTro"]) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="trangThai">Trạng thái</label>
                        <select id="trangThai" name="trangThai" class="<?= isset($errors["trangThai"]) ? "input-error" : "" ?>">
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="Hoạt động" <?= $trangThai === "Hoạt động" ? "selected" : "" ?>>Hoạt động</option>
                            <option value="Bị khóa" <?= $trangThai === "Bị khóa" ? "selected" : "" ?>>Bị khóa</option>
                        </select>
                        <?php if (isset($errors["trangThai"])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors["trangThai"]) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" name="btnLuu">
                    <?= $dangSua ? "Cập nhật người dùng" : "Thêm người dùng" ?>
                </button>
            </form>
        </div>

        <!-- BẢNG DANH SÁCH -->
        <div class="card">
            <h2>Danh sách người dùng</h2>
            <form method="GET" style="margin-bottom: 20px;">
                <input type="text" name="tuKhoa" placeholder="Tìm theo mã, họ tên hoặc email..."
                    value="<?= htmlspecialchars($_GET["tuKhoa"] ?? "") ?>" style="width: auto; min-width: 300px;">
                <button type="submit">Tìm kiếm</button>
            </form>

            <?php if (count($danhSachNguoiDung) === 0): ?>
                <div class="empty">Chưa có người dùng nào hoặc không tìm thấy kết quả.</div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã ND</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Đang mượn</th>
                                <th>Quyền mượn</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stt = 1;
                            $hanMucMacDinh = 5;
                            foreach ($danhSachNguoiDung as $nd):
                                // Lấy số sách đang mượn từ DB (Hàm giả lập ở functions)
                                $soSachDangMuon = laySoSachDangMuon($nd["ma_nguoi_dung"]);
                                $duocMuon = kiemTraDuocMuon($nd["trang_thai"], $soSachDangMuon, $hanMucMacDinh);
                                $lyDo = layLyDoKhongDuocMuon($nd["trang_thai"], $soSachDangMuon, $hanMucMacDinh);
                            ?>
                                <tr>
                                    <td><?= $stt ?></td>
                                    <td><?= htmlspecialchars($nd["ma_nguoi_dung"]) ?></td>
                                    <td><?= htmlspecialchars($nd["ho_ten"]) ?></td>
                                    <td><?= htmlspecialchars($nd["email"]) ?></td>
                                    <td><?= htmlspecialchars($nd["vai_tro"]) ?></td>
                                    <td><?= htmlspecialchars($nd["trang_thai"]) ?></td>
                                    <td><?= $soSachDangMuon ?>/<?= $hanMucMacDinh ?></td>
                                    <td>
                                        <?php if ($duocMuon): ?>
                                            <span class="duoc-muon">Được phép</span>
                                        <?php else: ?>
                                            <span class="khong-duoc-muon">Không được<br><small><?= htmlspecialchars($lyDo) ?></small></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="POST" onsubmit="return confirm('Bạn có chắc chắn thực hiện thao tác này?');">
                                            <button type="submit" name="sua" value="<?= htmlspecialchars($nd["ma_nguoi_dung"]) ?>" style="background: #eab308; margin-bottom: 5px;">Sửa</button>
                                            <button type="submit" name="xoa" value="<?= htmlspecialchars($nd["ma_nguoi_dung"]) ?>" class="btn-danger">Khóa</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php $stt++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
    </main>
</div>
</body>

</html>