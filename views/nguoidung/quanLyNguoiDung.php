<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - Thư viện Mini</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        .tieu-de-quanly {
            margin-top: 25px;
            margin-bottom: 20px;
            font-size: var(--font-size-page-title);
            font-weight: 800;
            color: var(--text-primary);
        }

        .thanh-cong-cu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 24px;
            background: var(--white);
            padding: 16px 20px;
            border-radius: var(--radius-card);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-card);
        }

        .bo-loc-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .bo-loc-form input,
        .bo-loc-form select {
            min-height: var(--input-height);
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-input);
            font-size: 13.5px;
            font-family: inherit;
            outline: none;
        }

        .bo-loc-form input:focus,
        .bo-loc-form select:focus {
            border-color: var(--primary);
        }

        .nut-chinh {
            min-height: var(--button-height);
            padding: 0 18px;
            border: none;
            border-radius: var(--radius-button);
            background-color: var(--primary);
            color: var(--white);
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all var(--transition-fast);
        }

        .nut-chinh:hover {
            background-color: var(--primary-dark);
        }

        .nut-them-user {
            background-color: var(--success);
        }

        .nut-them-user:hover {
            background-color: #15803D;
        }

        .bang-khung-chua {
            width: 100%;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            overflow-x: auto;
            margin-bottom: 40px;
        }

        .bang-quan-ly {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13.5px;
        }

        .bang-quan-ly th {
            background-color: #F8FAFC;
            color: var(--text-primary);
            font-weight: 700;
            padding: 12px 16px;
            border-bottom: 2px solid var(--border);
            white-space: nowrap;
        }

        .bang-quan-ly td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text-body);
            vertical-align: middle;
        }

        .bang-quan-ly tr:hover {
            background-color: var(--primary-light);
        }

        .role-badge-tag {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .role-badge-tag.doc-gia { background-color: #E0F2FE; color: #0369A1; }
        .role-badge-tag.thu-thu { background-color: #EFF6FF; color: #1D4ED8; }
        .role-badge-tag.admin { background-color: #FEF3C7; color: #92400E; }

        .status-badge-tag {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge-tag.hoat-dong { background-color: #DCFCE7; color: #166534; }
        .status-badge-tag.bi-khoa { background-color: #FEE2E2; color: #991B1B; }

        .nhom-nut-thao-tac {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .btn-action-sm {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12.5px;
            font-weight: 600;
            border: 1px solid var(--border);
            background: var(--white);
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .btn-action-sm.edit:hover {
            background-color: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }

        .btn-action-sm.lock:hover {
            background-color: var(--danger);
            color: var(--white);
            border-color: var(--danger);
        }

        .btn-action-sm.unlock:hover {
            background-color: var(--success);
            color: var(--white);
            border-color: var(--success);
        }

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(3px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box-crud {
            background: var(--white);
            width: 100%;
            max-width: 600px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border);
        }

        .modal-header-crud {
            padding: 16px 20px;
            background: var(--bg-page);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body-crud {
            padding: 20px;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 14px;
        }

        .form-group-crud {
            margin-bottom: 14px;
        }

        .form-group-crud label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-body);
        }

        .form-group-crud input,
        .form-group-crud select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-input);
            font-size: 13.5px;
            box-sizing: border-box;
            outline: none;
        }

        .user-alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .user-alert.success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .user-alert.error { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
    </style>
</head>

<body>
    <div class="layout">
        <!-- Sidebar -->
        <?php
        $activePage = 'nguoidung';
        $activeAction = 'quanLyNguoiDung';
        include __DIR__ . '/../../layout/sidebar.php';
        ?>

        <!-- Main Content -->
        <main class="main-content">
            <h1 class="tieu-de-quanly">QUẢN LÝ NGƯỜI DÙNG</h1>

            <?php if (!empty($thongBao)): ?>
                <div class="user-alert <?= $loaiThongBao ?>">
                    <?= htmlspecialchars($thongBao) ?>
                </div>
            <?php endif; ?>

            <!-- THANH CÔNG CỤ TÌM KIẾM & BỘ LỌC -->
            <div class="thanh-cong-cu">
                <form method="GET" action="index.php" class="bo-loc-form">
                    <input type="hidden" name="controller" value="user">
                    <input type="hidden" name="action" value="quanLyNguoiDung">

                    <input type="text" name="tuKhoa" value="<?= htmlspecialchars($tuKhoa ?? '') ?>" placeholder="Tìm mã, tên, email, sđt...">

                    <select name="locVaiTro">
                        <option value="">-- Tất cả vai trò --</option>
                        <option value="Độc giả" <?= ($locVaiTro ?? '') === 'Độc giả' ? 'selected' : '' ?>>Độc giả</option>
                        <option value="Thủ thư" <?= ($locVaiTro ?? '') === 'Thủ thư' ? 'selected' : '' ?>>Thủ thư</option>
                        <option value="Quản trị viên" <?= ($locVaiTro ?? '') === 'Quản trị viên' ? 'selected' : '' ?>>Quản trị viên</option>
                    </select>

                    <select name="locTrangThai">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="Hoạt động" <?= ($locTrangThai ?? '') === 'Hoạt động' ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="Bị khóa" <?= ($locTrangThai ?? '') === 'Bị khóa' ? 'selected' : '' ?>>Bị khóa</option>
                    </select>

                    <button type="submit" class="nut-chinh">Lọc</button>
                    <?php if (!empty($tuKhoa) || !empty($locVaiTro) || !empty($locTrangThai)): ?>
                        <a href="index.php?controller=user&action=quanLyNguoiDung" class="btn-action-sm">Xóa lọc</a>
                    <?php endif; ?>
                </form>

                <button type="button" class="nut-chinh nut-them-user" onclick="moModalThem()">
                    + Thêm người dùng
                </button>
            </div>

            <!-- BẢNG DANH SÁCH NGƯỜI DÙNG -->
            <div class="bang-khung-chua">
                <table class="bang-quan-ly">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Họ và tên</th>
                            <th>Email</th>
                            <th>Số ĐT</th>
                            <th>Khoa/Lớp</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th style="text-align:center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($danhSachNguoiDung)): ?>
                            <tr>
                                <td colspan="8" style="text-align:center; padding:30px; color:var(--text-secondary);">
                                    Không tìm thấy người dùng nào.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($danhSachNguoiDung as $u): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($u['ma_nguoi_dung']) ?></strong></td>
                                    <td><?= htmlspecialchars($u['ho_ten']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><?= htmlspecialchars($u['sdt'] ?: '-') ?></td>
                                    <td><?= htmlspecialchars($u['khoa_lop'] ?: '-') ?></td>
                                    <td>
                                        <?php
                                        $rc = 'doc-gia';
                                        if ($u['vai_tro'] === 'Thủ thư') $rc = 'thu-thu';
                                        elseif ($u['vai_tro'] === 'Quản trị viên') $rc = 'admin';
                                        ?>
                                        <span class="role-badge-tag <?= $rc ?>"><?= htmlspecialchars($u['vai_tro']) ?></span>
                                    </td>
                                    <td>
                                        <span class="status-badge-tag <?= $u['trang_thai'] === 'Hoạt động' ? 'hoat-dong' : 'bi-khoa' ?>">
                                            <?= htmlspecialchars($u['trang_thai']) ?>
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <div class="nhom-nut-thao-tac" style="justify-content:center;">
                                            <button type="button" class="btn-action-sm edit" onclick="moModalSua(<?= htmlspecialchars(json_encode($u, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>)">Sửa</button>
                                            
                                            <form method="POST" action="index.php?controller=user&action=quanLyNguoiDung" style="margin:0;">
                                                <input type="hidden" name="hanhDong" value="doi_trang_thai">
                                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                                <?php if ($u['trang_thai'] === 'Hoạt động'): ?>
                                                    <input type="hidden" name="trang_thai_moi" value="Bị khóa">
                                                    <button type="submit" class="btn-action-sm lock" onclick="return confirm('Bạn có chắc chắn muốn KHÓA tài khoản này?')">Khóa</button>
                                                <?php else: ?>
                                                    <input type="hidden" name="trang_thai_moi" value="Hoạt động">
                                                    <button type="submit" class="btn-action-sm unlock" onclick="return confirm('MỞ KHÓA tài khoản này?')">Mở khóa</button>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- MODAL THÊM NGƯỜI DÙNG -->
    <div class="modal-overlay" id="modalThem">
        <div class="modal-box-crud">
            <div class="modal-header-crud">
                <h3 style="margin:0; font-size:16px;">Thêm người dùng mới</h3>
                <button type="button" onclick="dongModalThem()" style="background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
            </div>
            <form method="POST" action="index.php?controller=user&action=quanLyNguoiDung" class="modal-body-crud">
                <input type="hidden" name="hanhDong" value="them">

                <div class="form-row-2">
                    <div class="form-group-crud">
                        <label>Mã người dùng *</label>
                        <input type="text" name="ma_nguoi_dung" required placeholder="VD: SV004, TT002...">
                    </div>
                    <div class="form-group-crud">
                        <label>Họ và tên *</label>
                        <input type="text" name="ho_ten" required placeholder="Họ và tên đầy đủ">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-crud">
                        <label>Email *</label>
                        <input type="email" name="email" required placeholder="email@gmail.com">
                    </div>
                    <div class="form-group-crud">
                        <label>Mật khẩu khởi tạo *</label>
                        <input type="password" name="mat_khau" required placeholder="Mật khẩu">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-crud">
                        <label>Số điện thoại</label>
                        <input type="text" name="sdt" placeholder="09xxxxxxx">
                    </div>
                    <div class="form-group-crud">
                        <label>Khoa / Lớp</label>
                        <input type="text" name="khoa_lop" placeholder="VD: CNTT K68">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-crud">
                        <label>Vai trò</label>
                        <select name="vai_tro">
                            <option value="Độc giả">Độc giả (Sinh viên)</option>
                            <option value="Thủ thư">Thủ thư</option>
                            <option value="Quản trị viên">Quản trị viên</option>
                        </select>
                    </div>
                    <div class="form-group-crud">
                        <label>Trạng thái</label>
                        <select name="trang_thai">
                            <option value="Hoạt động">Hoạt động</option>
                            <option value="Bị khóa">Bị khóa</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:10px;">
                    <button type="button" class="btn-action-sm" onclick="dongModalThem()">Hủy</button>
                    <button type="submit" class="nut-chinh nut-them-user">Lưu người dùng</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SỬA NGƯỜI DÙNG -->
    <div class="modal-overlay" id="modalSua">
        <div class="modal-box-crud">
            <div class="modal-header-crud">
                <h3 style="margin:0; font-size:16px;">Chỉnh sửa thông tin người dùng</h3>
                <button type="button" onclick="dongModalSua()" style="background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
            </div>
            <form method="POST" action="index.php?controller=user&action=quanLyNguoiDung" class="modal-body-crud">
                <input type="hidden" name="hanhDong" value="sua">
                <input type="hidden" name="id" id="edit_id">

                <div class="form-row-2">
                    <div class="form-group-crud">
                        <label>Mã người dùng *</label>
                        <input type="text" name="ma_nguoi_dung" id="edit_ma" required>
                    </div>
                    <div class="form-group-crud">
                        <label>Họ và tên *</label>
                        <input type="text" name="ho_ten" id="edit_hoten" required>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-crud">
                        <label>Email *</label>
                        <input type="email" name="email" id="edit_email" required>
                    </div>
                    <div class="form-group-crud">
                        <label>Số điện thoại</label>
                        <input type="text" name="sdt" id="edit_sdt">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group-crud">
                        <label>Khoa / Lớp</label>
                        <input type="text" name="khoa_lop" id="edit_khoalop">
                    </div>
                    <div class="form-group-crud">
                        <label>Vai trò</label>
                        <select name="vai_tro" id="edit_vaitro">
                            <option value="Độc giả">Độc giả</option>
                            <option value="Thủ thư">Thủ thư</option>
                            <option value="Quản trị viên">Quản trị viên</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-crud">
                    <label>Trạng thái</label>
                    <select name="trang_thai" id="edit_trangthai">
                        <option value="Hoạt động">Hoạt động</option>
                        <option value="Bị khóa">Bị khóa</option>
                    </select>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:10px;">
                    <button type="button" class="btn-action-sm" onclick="dongModalSua()">Hủy</button>
                    <button type="submit" class="nut-chinh">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function moModalThem() {
            document.getElementById('modalThem').classList.add('active');
        }
        function dongModalThem() {
            document.getElementById('modalThem').classList.remove('active');
        }
        function moModalSua(user) {
            document.getElementById('edit_id').value = user.id;
            document.getElementById('edit_ma').value = user.ma_nguoi_dung;
            document.getElementById('edit_hoten').value = user.ho_ten;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_sdt').value = user.sdt || '';
            document.getElementById('edit_khoalop').value = user.khoa_lop || '';
            document.getElementById('edit_vaitro').value = user.vai_tro;
            document.getElementById('edit_trangthai').value = user.trang_thai;
            document.getElementById('modalSua').classList.add('active');
        }
        function dongModalSua() {
            document.getElementById('modalSua').classList.remove('active');
        }
    </script>
</body>

</html>
