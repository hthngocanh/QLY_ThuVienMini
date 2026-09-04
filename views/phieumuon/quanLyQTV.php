<?php
// Nhận trạng thái tab đang active từ Controller
$activeTab = $_GET['tab'] ?? ($activeTab ?? 'cauhinh');

$thongBao = $thongBao ?? ($_SESSION['thong_bao'] ?? '');
if (isset($_SESSION['thong_bao'])) {
    unset($_SESSION['thong_bao']);
}

$cauhinh = $cauhinh ?? [
    'so_luot_muon' => 5,
    'so_sach_toi_da' => 3
];

$danhSachThongKe = $danhSachThongKe ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị Viên (QTV) - Cấu Hình & Thống Kê</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #334155; }
        .main-content { background-color: #f8fafc; min-height: 100vh; }
        .custom-card { background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .custom-tabs { border-bottom: 2px solid #e2e8f0; display: flex; gap: 8px; }
        .tab-btn {
            background: none; border: none; color: #64748b; font-weight: 600;
            padding: 12px 24px; border-bottom: 3px solid transparent; font-size: 15px; cursor: pointer;
        }
        .tab-btn:hover { color: #2563eb; }
        .tab-btn.active { color: #2563eb; border-bottom-color: #2563eb; }
        .tab-content-item { display: none; }
        .tab-content-item.active { display: block; }
        .form-control, .form-select { border-radius: 10px; border: 1px solid #cbd5e1; padding: 10px 14px; font-size: 14px; }
        .table { vertical-align: middle; font-size: 14px; }
        .table thead th { background-color: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 12px; border-bottom: 1px solid #e2e8f0; padding: 14px 16px; }
        .table tbody td { padding: 16px; border-bottom: 1px solid #f1f5f9; }
        .btn-primary-custom { background-color: #2563eb; color: #fff; border-radius: 10px; padding: 10px 24px; font-weight: 600; border: none; }
        .btn-primary-custom:hover { background-color: #1d4ed8; color: #fff; }
    </style>
</head>
<body>
<!-- Sidebar -->
<?php
$activePage = 'nguoidung';
$activeAction = 'quanLyDocGia';
include __DIR__ . '/../../layout/sidebar.php';
?>
<div class="d-flex" style="min-height: 100vh;">

    <!-- NHÚNG SIDEBAR -->
    <?php 
    if (file_exists(__DIR__ . '/../../layout/sidebar.php')) {
        include_once __DIR__ . '/../../layout/sidebar.php';
    } elseif (file_exists(__DIR__ . '/../layout/sidebar.php')) {
        include_once __DIR__ . '/../layout/sidebar.php';
    }
    ?>

    <div class="flex-grow-1 main-content p-4 p-md-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: #0f172a;">Menu Quản Trị Viên (QTV)</h3>
                <p class="text-muted mb-0 fs-6">Quản lý quy định hạn mức & xem thống kê mượn trả</p>
            </div>
        </div>

        <?php if (!empty($thongBao)): ?>
            <div class="alert alert-success alert-dismissible fade show custom-card border-0 mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($thongBao) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- CHUYỂN MENU GỒM 1 & 2 DỰA THEO SƠ ĐỒ -->
        <div class="custom-tabs mb-4">
            <button class="tab-btn <?= $activeTab === 'cauhinh' ? 'active' : '' ?>" id="btn-cauhinh" onclick="switchTab('cauhinh')">
                <i class="fa-solid fa-sliders me-2"></i>1. Cấu Hình Hạn Mức
            </button>
            <button class="tab-btn <?= $activeTab === 'thongke' ? 'active' : '' ?>" id="btn-thongke" onclick="switchTab('thongke')">
                <i class="fa-solid fa-chart-column me-2"></i>2. Bảng Thống Kê
            </button>
        </div>

        <!-- =================================================== -->
        <!-- MENU 1: CẤU HÌNH HẠN MỨC (Gồm Quy Định: Lượt mượn & Số sách) -->
        <!-- =================================================== -->
        <div id="tab-cauhinh" class="tab-content-item <?= $activeTab === 'cauhinh' ? 'active' : '' ?>">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="custom-card p-4 p-md-5">
                        <h5 class="fw-bold mb-3 text-primary">
                            <i class="fa-solid fa-file-signature me-2"></i>Quy Định Hạn Mức Mượn
                        </h5>
                        <form action="index.php?controller=quantri&action=cap_nhat_han_muc" method="POST">
                            <div class="row g-4">
                                <!-- Quy định: Số lượt mượn -->
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-4 border">
                                        <label for="so_luot_muon" class="form-label fw-bold text-dark">
                                            <i class="fa-solid fa-arrows-rotate me-1 text-primary"></i> Quy Định Lượt Mượn
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control fw-bold fs-5 text-center" 
                                                   id="so_luot_muon" name="so_luot_muon" 
                                                   value="<?= htmlspecialchars($cauhinh['so_luot_muon'] ?? 5) ?>" min="1" required>
                                            <span class="input-group-text bg-white fw-semibold">Lượt/Tháng</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quy định: Số sách -->
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-4 border">
                                        <label for="so_sach_toi_da" class="form-label fw-bold text-dark">
                                            <i class="fa-solid fa-book-bookmark me-1 text-success"></i> Quy Định Số Sách
                                        </label>
                                        <div class="input-group">
                                            <input type="number" class="form-control fw-bold fs-5 text-center" 
                                                   id="so_sach_toi_da" name="so_sach_toi_da" 
                                                   value="<?= htmlspecialchars($cauhinh['so_sach_toi_da'] ?? 3) ?>" min="1" required>
                                            <span class="input-group-text bg-white fw-semibold">Cuốn/Lần</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Quy Định
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- =================================================== -->
        <!-- MENU 2: BẢNG THỐNG KÊ (Hiện Full All, Có Tìm Kiếm, Lọc & Quá Hạn) -->
        <!-- =================================================== -->
        <div id="tab-thongke" class="tab-content-item <?= $activeTab === 'thongke' ? 'active' : '' ?>">

            <!-- THANH TÌM KIẾM VÀ BỘ LỌC -->
            <div class="custom-card p-3 mb-4">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" id="qtvSearchInput" class="form-control border-start-0 ps-0" 
                                   placeholder="Tìm kiếm phiếu mượn, độc giả, sách..." onkeyup="locBangThongKe()">
                        </div>
                    </div>

                    <!-- Lọc -->
                    <div class="col-md-4">
                        <select id="qtvFilterStatus" class="form-select" onchange="locBangThongKe()">
                            <option value="">-- Hiện Full All --</option>
                            <option value="Quá hạn">⚠️ Chỉ lọc danh sách QUÁ HẠN</option>
                            <option value="Đang mượn">📖 Đang mượn</option>
                            <option value="Đã trả">✅ Đã trả</option>
                            <option value="Chờ duyệt">⏳ Chờ duyệt</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="button" class="btn btn-outline-danger w-100 fw-semibold" onclick="locQuaHanQuick()">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Xem Nhanh Quá Hạn
                        </button>
                    </div>
                </div>
            </div>

            <!-- BẢNG HIỆN FULL ALL -->
            <div class="custom-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="tableThongKe">
                        <thead>
                            <tr>
                                <th class="ps-4">Mã Phiếu</th>
                                <th>Độc Giả</th>
                                <th>Tên Sách</th>
                                <th>Ngày Mượn</th>
                                <th>Hạn Trả</th>
                                <th>Ngày Trả</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($danhSachThongKe)): ?>
                                <?php foreach ($danhSachThongKe as $row): ?>
                                    <?php 
                                        $stt = $row['TrangThai'] ?? 'Chưa rõ';
                                        $isQuaHan = ($stt === 'Quá hạn');
                                    ?>
                                    <tr class="dong-thong-ke <?= $isQuaHan ? 'table-danger' : '' ?>" data-status="<?= htmlspecialchars($stt) ?>">
                                        <td class="ps-4 fw-bold text-primary">#<?= sprintf('%04d', $row['ID_PhieuMuon'] ?? $row['id'] ?? 0) ?></td>
                                        <td class="fw-bold text-dark"><?= htmlspecialchars($row['ten_doc_gia'] ?? $row['ho_ten'] ?? 'N/A') ?></td>
                                        <td class="fw-semibold text-dark"><?= htmlspecialchars($row['ten_sach'] ?? 'N/A') ?></td>
                                        <td><?= !empty($row['NgayMuon']) ? date('d/m/Y', strtotime($row['NgayMuon'])) : '-' ?></td>
                                        <td class="fw-semibold <?= $isQuaHan ? 'text-danger' : '' ?>">
                                            <?= !empty($row['HanTra']) ? date('d/m/Y', strtotime($row['HanTra'])) : '-' ?>
                                        </td>
                                        <td><?= !empty($row['NgayTra']) ? date('d/m/Y', strtotime($row['NgayTra'])) : '<span class="text-muted fst-italic">Chưa trả</span>' ?></td>
                                        <td>
                                            <span class="badge rounded-pill <?= $isQuaHan ? 'bg-danger' : ($stt === 'Đã trả' ? 'bg-success' : 'bg-info') ?> px-3 py-2">
                                                <?= htmlspecialchars($stt) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">Chưa có dữ liệu thống kê.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    // Hàm chuyển Tab hiển thị
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content-item').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

        document.getElementById('tab-' + tabName).classList.add('active');
        document.getElementById('btn-' + tabName).classList.add('active');
    }

    // Hàm Lọc và Tìm kiếm trên bảng
    function locBangThongKe() {
        const searchText = document.getElementById('qtvSearchInput').value.toLowerCase().trim();
        const filterStatus = document.getElementById('qtvFilterStatus').value.toLowerCase().trim();
        const rows = document.querySelectorAll('#tableThongKe tbody tr.dong-thong-ke');

        rows.forEach(row => {
            const rowContent = row.textContent.toLowerCase();
            const rowStatus = (row.getAttribute('data-status') || '').toLowerCase().trim();

            const matchesSearch = rowContent.includes(searchText);
            const matchesStatus = (filterStatus === '') || (rowStatus === filterStatus);

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    // Lọc nhanh trạng thái Quá hạn
    function locQuaHanQuick() {
        document.getElementById('qtvFilterStatus').value = 'Quá hạn';
        locBangThongKe();
    }
</script>
</body>
</html>