<?php
// views/phieumuon/index.php

if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('hienThiNgay')) {
    function hienThiNgay($date)
    {
        if (empty($date)) {
            return '';
        }
        $dateObject = DateTime::createFromFormat('Y-m-d', $date);
        if (!$dateObject) {
            return e($date);
        }
        return $dateObject->format('d/m/Y');
    }
}

$vaiTroHienTai = $vaiTroHienTai ?? ($_SESSION['user']['vai_tro'] ?? '');
$laDocGia = $laDocGia ?? ($vaiTroHienTai === 'Độc giả');
$duocQuanLy = $duocQuanLy ?? in_array($vaiTroHienTai, ['Thủ thư', 'Quản trị viên'], true);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $laDocGia ? 'Phiếu mượn của tôi' : 'Quản lý phiếu mượn' ?></title>

    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; background: #f8fafc; }
        .container { width: 100%; max-width: 1200px; margin: 0 auto; }
        h1 { text-align: center; margin-bottom: 25px; }

        .form-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #ccc;
        }

        .form-group { margin-bottom: 18px; }
        label { display: block; font-weight: bold; margin-bottom: 6px; }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input:focus, select:focus { outline: none; border-color: #2563eb; }
        .loi-truong { color: #c62828; font-size: 14px; margin-top: 6px; font-weight: bold; }
        .input-loi { border: 1px solid #c62828; }
        .mo-ta { color: #777; font-size: 13px; margin-top: 5px; }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-sua { background: #2563eb; }
        .btn-xoa { background: #dc2626; }
        .btn-huy { background: #64748b; }
        .btn-duyet { background: #16a34a; }
        .btn-tra { background: #0f766e; }

        .thanh-cong {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .thanh-loi {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        h2 { margin-top: 30px; }
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: center; vertical-align: middle; }
        th { background: #f1f5f9; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-borrowing { background: #dbeafe; color: #1d4ed8; }
        .status-overdue { background: #fee2e2; color: #b91c1c; }
        .status-returned { background: #dcfce7; color: #166534; }

        .actions {
            display: flex;
            gap: 6px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }
        .actions form { margin: 0; }
        .actions a,
        .actions button {
            padding: 7px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            font-size: 13px;
        }

        .reader-note {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        @media (max-width: 900px) {
            .main-content { padding: 18px !important; }
            .form-box { padding: 18px; }
            th, td { padding: 8px; font-size: 13px; }
        }
    </style>
</head>
<body>

<div class="layout">
    <?php
    $activePage = 'phieumuon';
    require_once __DIR__ . '/../../layout/sidebar_reader.php';
    ?>

    <main class="main-content" style="flex: 1; min-width: 0; padding: 30px; overflow-y: auto; background: #f8fafc;">
        <div class="container">
            <h1><?= $laDocGia ? 'PHIẾU MƯỢN CỦA TÔI' : 'QUẢN LÝ PHIẾU MƯỢN' ?></h1>

            <?php if (!empty($thongBao)): ?>
                <div class="thanh-cong"><?= e($thongBao) ?></div>
            <?php endif; ?>

            <?php if (!empty($thongBaoLoi)): ?>
                <div class="thanh-loi"><?= e($thongBaoLoi) ?></div>
            <?php endif; ?>

            <?php if ($laDocGia): ?>
                <div class="reader-note">
                    Đây là danh sách phiếu mượn của bạn. Yêu cầu mới sẽ ở trạng thái <strong>Chờ duyệt</strong> cho đến khi Thủ thư duyệt.
                </div>
            <?php endif; ?>

            <?php if ($duocQuanLy): ?>
                <div class="form-box">
                    <h2><?= ($id > 0) ? 'Sửa phiếu mượn' : 'Thêm phiếu mượn'; ?></h2>

                    <form method="POST" action="index.php?controller=phieumuon">
                        <input type="hidden" name="action" value="<?= ($id > 0) ? 'edit' : 'add'; ?>">

                        <?php if ($id > 0): ?>
                            <input type="hidden" name="id" value="<?= e($id) ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="ma_nguoi_dung">Người mượn:</label>
                            <input
                                type="text"
                                id="ma_nguoi_dung"
                                name="ma_nguoi_dung"
                                value="<?= e($maNguoiDung ?? '') ?>"
                                placeholder="Nhập mã người dùng, ví dụ DG001"
                                maxlength="20"
                                autocomplete="off"
                                class="<?= isset($errors['ma_nguoi_dung']) ? 'input-loi' : '' ?>"
                                required
                            >
                            <?php if (isset($errors['ma_nguoi_dung'])): ?>
                                <div class="loi-truong"><?= e($errors['ma_nguoi_dung']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="ma_ban_sao">Bản sao sách:</label>
                            <input
                                type="text"
                                id="ma_ban_sao"
                                name="ma_ban_sao"
                                value="<?= e($maBanSao ?? '') ?>"
                                placeholder="Nhập mã bản sao, ví dụ BS001"
                                maxlength="50"
                                autocomplete="off"
                                class="<?= isset($errors['ma_ban_sao']) ? 'input-loi' : '' ?>"
                                required
                            >
                            <?php if (isset($errors['ma_ban_sao'])): ?>
                                <div class="loi-truong"><?= e($errors['ma_ban_sao']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="ngay_muon">Ngày mượn:</label>
                            <input
                                type="date"
                                id="ngay_muon"
                                name="ngay_muon"
                                max="<?= date('Y-m-d') ?>"
                                value="<?= e($ngayMuon ?? '') ?>"
                                class="<?= isset($errors['ngay_muon']) ? 'input-loi' : '' ?>"
                                required
                            >
                            <?php if (isset($errors['ngay_muon'])): ?>
                                <div class="loi-truong"><?= e($errors['ngay_muon']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="ngay_tra">Ngày trả:</label>
                            <input
                                type="date"
                                id="ngay_tra"
                                name="ngay_tra"
                                max="<?= date('Y-m-d') ?>"
                                value="<?= e($ngayTra ?? '') ?>"
                                class="<?= isset($errors['ngay_tra']) ? 'input-loi' : '' ?>"
                            >
                            <div class="mo-ta">Để trống nếu sách chưa được trả.</div>
                            <?php if (isset($errors['ngay_tra'])): ?>
                                <div class="loi-truong"><?= e($errors['ngay_tra']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="trang_thai">Trạng thái:</label>
                            <select
                                id="trang_thai"
                                name="trang_thai"
                                class="<?= isset($errors['trang_thai']) ? 'input-loi' : '' ?>"
                                required
                            >
                                <?php
                                $trangThaiOptions = [
                                    'Chờ duyệt',
                                    'Đang mượn',
                                    'Quá hạn',
                                    'Đã trả'
                                ];
                                ?>
                                <?php foreach ($trangThaiOptions as $option): ?>
                                    <option value="<?= e($option) ?>" <?= (($trangThai ?? '') === $option) ? 'selected' : '' ?>>
                                        <?= e($option) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="mo-ta">Với phiếu do Độc giả gửi, nên dùng nút Duyệt mượn/Xác nhận trả ở bảng bên dưới để đồng bộ trạng thái bản sao.</div>
                            <?php if (isset($errors['trang_thai'])): ?>
                                <div class="loi-truong"><?= e($errors['trang_thai']) ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" style="background:#334155;">
                            <?= ($id > 0) ? 'Cập nhật phiếu mượn' : 'Thêm phiếu mượn'; ?>
                        </button>

                        <?php if ($id > 0): ?>
                            <a href="index.php?controller=phieumuon" class="btn-huy" style="display:inline-block; padding:10px 20px; color:white; text-decoration:none; border-radius:5px;">
                                Hủy sửa
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            <?php endif; ?>

            <h2><?= $laDocGia ? 'Lịch sử mượn sách' : 'Danh sách phiếu mượn' ?></h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người mượn</th>
                        <th>Bản sao</th>
                        <th>Tên sách</th>
                        <th>Ngày mượn</th>
                        <th>Ngày trả</th>
                        <th>Trạng thái</th>
                        <?php if ($duocQuanLy): ?>
                            <th>Thao tác</th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($danhSachPhieuMuon)): ?>
                        <tr>
                            <td colspan="<?= $duocQuanLy ? 8 : 7 ?>">Chưa có dữ liệu.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($danhSachPhieuMuon as $phieu): ?>
                            <?php
                            $tt = $phieu['TrangThai'] ?? '';
                            $statusClass = 'status-pending';
                            if ($tt === 'Đang mượn') $statusClass = 'status-borrowing';
                            if ($tt === 'Quá hạn') $statusClass = 'status-overdue';
                            if ($tt === 'Đã trả') $statusClass = 'status-returned';
                            ?>
                            <tr>
                                <td><?= e($phieu['ID_PhieuMuon']) ?></td>
                                <td><?= e($phieu['ma_nguoi_dung']) ?> - <?= e($phieu['ho_ten']) ?></td>
                                <td><?= e($phieu['ma_ban_sao']) ?></td>
                                <td><?= e($phieu['ten_sach']) ?></td>
                                <td><?= hienThiNgay($phieu['NgayMuon']) ?></td>
                                <td>
                                    <?php if (!empty($phieu['NgayTra'])): ?>
                                        <?= hienThiNgay($phieu['NgayTra']) ?>
                                    <?php else: ?>
                                        Chưa trả
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge <?= e($statusClass) ?>"><?= e($tt) ?></span>
                                </td>

                                <?php if ($duocQuanLy): ?>
                                    <td>
                                        <div class="actions">
                                            <?php if ($tt === 'Chờ duyệt'): ?>
                                                <form method="POST" action="index.php?controller=phieumuon" onsubmit="return confirm('Xác nhận duyệt yêu cầu mượn sách này?');">
                                                    <input type="hidden" name="action" value="approve">
                                                    <input type="hidden" name="id" value="<?= e($phieu['ID_PhieuMuon']) ?>">
                                                    <button type="submit" class="btn-duyet">Duyệt mượn</button>
                                                </form>
                                            <?php elseif (in_array($tt, ['Đang mượn', 'Quá hạn'], true)): ?>
                                                <form method="POST" action="index.php?controller=phieumuon" onsubmit="return confirm('Xác nhận độc giả đã trả sách?');">
                                                    <input type="hidden" name="action" value="return">
                                                    <input type="hidden" name="id" value="<?= e($phieu['ID_PhieuMuon']) ?>">
                                                    <button type="submit" class="btn-tra">Xác nhận trả</button>
                                                </form>
                                            <?php endif; ?>

                                            <a href="index.php?controller=phieumuon&edit=<?= e($phieu['ID_PhieuMuon']) ?>" class="btn-sua">Sửa</a>

                                            <?php if (!in_array($tt, ['Đang mượn', 'Quá hạn'], true)): ?>
                                                <form method="POST" action="index.php?controller=phieumuon" onsubmit="return confirm('Bạn có chắc muốn xóa phiếu mượn này?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= e($phieu['ID_PhieuMuon']) ?>">
                                                    <button type="submit" class="btn-xoa">Xóa</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>
