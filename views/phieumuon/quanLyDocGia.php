<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu mượn độc giả - Thư viện Mini</title>
    <!-- Bootstrap 5 CSS & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }
        
        .main-content {
            background-color: #f8fafc;
            min-height: 100vh;
        }

        /* Card Style */
        .custom-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        /* Nav Tabs Style */
        .custom-tabs {
            border-bottom: 2px solid #e2e8f0;
        }
        .custom-tabs .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 12px 24px;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            font-size: 15px;
        }
        .custom-tabs .nav-link.active {
            color: #2563eb;
            background: transparent;
            border-bottom-color: #2563eb;
        }
        .custom-tabs .nav-link:hover:not(.active) {
            color: #1e293b;
            border-bottom-color: #cbd5e1;
        }

        /* Form Inputs & Controls */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 10px 14px;
            font-size: 14px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Table Styling */
        .table {
            vertical-align: middle;
            font-size: 14px;
        }
        .table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 16px;
        }
        .table tbody td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Badges */
        .badge-cat {
            background-color: #e0e7ff;
            color: #3730a3;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 6px;
        }
        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }
        .status-available { background-color: #10b981; color: #047857; }
        .status-borrowed { background-color: #f59e0b; color: #b45309; }
        .status-unavailable { background-color: #ef4444; color: #b91c1c; }

        /* Buttons */
        .btn-primary-custom {
            background-color: #2563eb;
            color: #fff;
            border-radius: 10px;
            padding: 9px 20px;
            font-weight: 500;
            border: none;
        }
        .btn-primary-custom:hover {
            background-color: #1d4ed8;
            color: #fff;
        }
        .btn-success-custom {
            background-color: #10b981;
            color: #fff;
            border-radius: 10px;
            padding: 7px 16px;
            font-weight: 500;
            border: none;
        }
        .btn-success-custom:hover {
            background-color: #059669;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="d-flex" style="min-height: 100vh;">

    <!-- 1. NHÚNG SIDEBAR CHUẨN CỦA DỰ ÁN -->
    <?php include_once __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- 2. NỘI DUNG CHÍNH (TRÀN MÀN HÌNH NỀN XÁM NHẠT) -->
    <div class="flex-grow-1 main-content p-4 p-md-5">

        <!-- HEADER TÊN ĐỘC GIẢ -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: #0f172a;">
                    Xin chào, <?= htmlspecialchars($_SESSION['user']['ho_ten'] ?? $_SESSION['user']['ten_nguoi_dung'] ?? 'Độc giả') ?> 👋
                </h3>
                <p class="text-muted mb-0 fs-6">Chào mừng bạn đến với hệ thống mượn sách Thư viện Mini</p>
            </div>
        </div>

        <!-- THÔNG BÁO HỆ THỐNG -->
        <?php if (!empty($thongBao)): ?>
            <div class="alert alert-success alert-dismissible fade show custom-card border-0 mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($thongBao) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger alert-dismissible fade show custom-card border-0 mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($errors['general']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- NAV TABS SANG TRỌNG -->
        <ul class="nav custom-tabs mb-4" id="docGiaTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="dang-ky-tab" data-bs-toggle="tab" data-bs-target="#dang-ky-pane" type="button" role="tab">
                    <i class="fa-solid fa-book-open me-2"></i>Đăng Ký Mượn Sách
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="lich-su-tab" data-bs-toggle="tab" data-bs-target="#lich-su-pane" type="button" role="tab">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i>Lịch Sử Mượn Trả
                </button>
            </li>
        </ul>

        <div class="tab-content" id="docGiaTabContent">

            <!-- =================================================== -->
            <!-- TAB 1: ĐĂNG KÝ MƯỢN SÁCH (CÓ BỘ LỌC + BẢNG + POPUP) -->
            <!-- =================================================== -->
            <div class="tab-pane fade show active" id="dang-ky-pane" role="tabpanel">

                <!-- THANH TÌM KIẾM VÀ LỌC SÁCH -->
                <div class="custom-card p-3 mb-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" id="searchBox" class="form-control border-start-0 ps-0" placeholder="Tìm kiếm theo tên sách, tác giả, mã bản sao...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="filterDanhMuc" class="form-select">
                                <option value="">Tất cả danh mục</option>
                                <option value="CNTT">CNTT</option>
                                <option value="Văn học">Văn học</option>
                                <option value="Ngoại ngữ">Ngoại ngữ</option>
                                <option value="Kỹ năng">Kỹ năng</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filterTrangThai" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="Sẵn sàng">Sẵn sàng</option>
                                <option value="Đang mượn">Đang mượn</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary-custom w-100" onclick="locDanhSachSach()">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>

                <!-- BẢNG DANH SÁCH SÁCH TRÀN MÀN HÌNH -->
                <div class="custom-card overflow-hidden">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="tableSach">
                            <thead>
                                <tr>
                                    <th>Mã bản sao</th>
                                    <th>Tên sách</th>
                                    <th>Tác giả</th>
                                    <th>Danh mục</th>
                                    <th>Trạng thái bản sao</th>
                                    <th class="text-end pe-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($danhSachSach)): ?>
                                    <?php foreach ($danhSachSach as $sach): ?>
                                        <?php 
                                            $trangThaiTxt = $sach['trang_thai'] ?? 'Sẵn sàng';
                                            $isAvailable = ($trangThaiTxt === 'Sẵn sàng' || $trangThaiTxt === 'Có sẵn');
                                        ?>
                                        <tr>
                                            <td class="fw-semibold text-secondary"><?= htmlspecialchars($sach['ma_ban_sao']) ?></td>
                                            <td><span class="fw-bold text-dark"><?= htmlspecialchars($sach['ten_sach']) ?></span></td>
                                            <td class="text-muted"><?= htmlspecialchars($sach['tac_gia'] ?? 'Đang cập nhật') ?></td>
                                            <td><span class="badge-cat"><?= htmlspecialchars($sach['ten_the_loai'] ?? 'Khác') ?></span></td>
                                            <td>
                                                <?php if ($isAvailable): ?>
                                                    <span class="fw-semibold text-success"><span class="status-dot status-available"></span>Có sẵn</span>
                                                <?php else: ?>
                                                    <span class="fw-semibold text-warning"><span class="status-dot status-borrowed"></span>Đang mượn</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <?php if ($isAvailable): ?>
                                                    <button class="btn btn-success-custom" onclick="moModalMuon('<?= htmlspecialchars($sach['ma_ban_sao']) ?>', '<?= htmlspecialchars(addslashes($sach['ten_sach'])) ?>')">
                                                        <i class="fa-solid fa-bookmark me-1"></i> Mượn
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary rounded-3 px-3 py-1" disabled style="opacity: 0.6;">
                                                        <i class="fa-solid fa-lock me-1"></i> Mượn
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Không có dữ liệu sách khả dụng.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- =================================================== -->
            <!-- TAB 2: LỊCH SỬ MƯỢN TRẢ SÁCH (BẢNG TRÀN MÀN HÌNH)  -->
            <!-- =================================================== -->
            <div class="tab-pane fade" id="lich-su-pane" role="tabpanel">
                <div class="custom-card overflow-hidden">
                    <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-receipt text-primary me-2"></i>Danh Sách Phiếu Mượn Của Bạn</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Mã phiếu</th>
                                    <th>Sách / Bản sao</th>
                                    <th><i class="fa-regular fa-calendar me-1"></i>Ngày mượn</th>
                                    <th><i class="fa-regular fa-calendar-xmark text-danger me-1"></i>Hạn trả (Max 15 ngày)</th>
                                    <th><i class="fa-regular fa-calendar-check text-success me-1"></i>Ngày trả</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($danhSachPhieuMuon)): ?>
                                    <?php foreach ($danhSachPhieuMuon as $pm): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary">#<?= sprintf('%04d', $pm['ID_PhieuMuon']) ?></td>
                                            <td>
                                                <div class="fw-bold text-dark mb-1"><?= htmlspecialchars($pm['ten_sach'] ?? 'N/A') ?></div>
                                                <span class="badge bg-light text-secondary border"><?= htmlspecialchars($pm['ma_ban_sao'] ?? '') ?></span>
                                            </td>
                                            <!-- Ngày mượn -->
                                            <td><?= !empty($pm['NgayMuon']) ? date('d/m/Y', strtotime($pm['NgayMuon'])) : '-' ?></td>
                                            
                                            <!-- Hạn trả (Tự động cộng 15 ngày từ ngày mượn) -->
                                            <td class="text-danger fw-semibold">
                                                <?= !empty($pm['NgayMuon']) ? date('d/m/Y', strtotime($pm['NgayMuon'] . ' +15 days')) : '-' ?>
                                            </td>
                                            
                                            <!-- Ngày trả -->
                                            <td>
                                                <?= !empty($pm['NgayTra']) ? date('d/m/Y', strtotime($pm['NgayTra'])) : '<span class="text-muted fst-italic">Chưa trả</span>' ?>
                                            </td>
                                            
                                            <!-- Trạng thái phiếu -->
                                            <td>
                                                <?php
                                                    $stt = $pm['TrangThai'] ?? '';
                                                    $badgeBg = match ($stt) {
                                                        'Chờ duyệt' => 'bg-warning text-dark',
                                                        'Đang mượn' => 'bg-info text-dark',
                                                        'Quá hạn'   => 'bg-danger text-white',
                                                        'Đã trả'    => 'bg-success text-white',
                                                        default     => 'bg-secondary'
                                                    };
                                                ?>
                                                <span class="badge rounded-pill <?= $badgeBg ?> px-3 py-2"><?= htmlspecialchars($stt) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Bạn chưa có lịch sử mượn sách nào.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- =================================================== -->
<!-- MODAL POPUP ĐĂNG KÝ MƯỢN (CÓ LỊCH MINI TỰ TÍNH 15 NGÀY) -->
<!-- =================================================== -->
<div class="modal fade" id="modalMuonSach" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="fa-solid fa-paper-plane me-2"></i>Đăng Ký Mượn Sách</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?controller=phieumuon" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="dang_ky_muon">

                    <!-- Tên sách chọn -->
                    <div class="mb-3">
                        <label class="form-label text-muted fs-7 mb-1">Sách chọn mượn</label>
                        <input type="text" class="form-control bg-light fw-bold text-primary" id="modalTenSach" readonly>
                    </div>

                    <!-- Mã bản sao -->
                    <div class="mb-3">
                        <label for="ma_ban_sao" class="form-label fw-semibold">Mã bản sao <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light fw-bold" id="modalMaBanSao" name="ma_ban_sao" readonly required>
                    </div>

                    <!-- Ngày mượn (Lịch Mini Picker) -->
                    <div class="mb-3">
                        <label for="ngay_muon" class="form-label fw-semibold">Ngày mượn <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-regular fa-calendar-days text-primary"></i></span>
                            <input type="date" class="form-control" id="modalNgayMuon" name="ngay_muon" 
                                   value="<?= date('Y-m-d') ?>" onchange="capNhatHanTraModal()" required>
                        </div>
                    </div>

                    <!-- Hạn trả (Tự động +15 ngày) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hạn trả (Cố định 15 ngày)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-regular fa-calendar-check text-success"></i></span>
                            <input type="date" class="form-control bg-light fw-bold text-success" id="modalHanTra" readonly>
                        </div>
                        <small class="text-muted fs-7 mt-1 d-block"><i class="fa-solid fa-circle-info me-1"></i>Hạn trả tự động tính tối đa 15 ngày kể từ ngày mượn.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary-custom px-4"><i class="fa-solid fa-check me-1"></i> Xử lý đăng ký</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Mở Modal đăng ký mượn khi bấm nút "Mượn"
    function moModalMuon(maBanSao, tenSach) {
        document.getElementById('modalMaBanSao').value = maBanSao;
        document.getElementById('modalTenSach').value = tenSach;
        capNhatHanTraModal();
        
        var modal = new bootstrap.Modal(document.getElementById('modalMuonSach'));
        modal.show();
    }

    // Tự động cộng 15 ngày cho Hạn trả trong Modal
    function capNhatHanTraModal() {
        const ngayMuonVal = document.getElementById('modalNgayMuon').value;
        if (ngayMuonVal) {
            const d = new Date(ngayMuonVal);
            d.setDate(d.getDate() + 15);
            
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            
            document.getElementById('modalHanTra').value = `${year}-${month}-${day}`;
        }
    }

    // Hàm lọc dữ liệu trên bảng sách theo từ khóa tìm kiếm
    function locDanhSachSach() {
        const input = document.getElementById('searchBox').value.toLowerCase();
        const table = document.getElementById('tableSach');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const rowText = rows[i].textContent.toLowerCase();
            if (rowText.includes(input)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
</script>
</body>
</html>