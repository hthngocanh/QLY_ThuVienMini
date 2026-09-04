<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cấu hình hạn mức</title>
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .main-wrapper { display: flex; min-height: 100vh; }
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
    </style>
</head>
<body>

<div class="main-wrapper">
   <!-- Nhúng Sidebar chung của nhóm -->
    <?php 
    $activePage = 'phieumuon';
    $activeAction = 'cauhinhhanMuc';

    // Đường dẫn chính xác cho cấu trúc views/ của bạn
    $paths = [
        __DIR__ . '/../layout/sidebar.php',
        __DIR__ . '/../layouts/sidebar.php',
        __DIR__ . '/../includes/sidebar.php',
        __DIR__ . '/../inc/sidebar.php',
        __DIR__ . '/../components/sidebar.php',
        __DIR__ . '/../sidebar.php',
        $_SERVER['DOCUMENT_ROOT'] . '/QLY_ThuVienMini/views/layout/sidebar.php',
        $_SERVER['DOCUMENT_ROOT'] . '/QLY_ThuVienMini/views/layouts/sidebar.php',
        $_SERVER['DOCUMENT_ROOT'] . '/QLY_ThuVienMini/views/includes/sidebar.php'
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            include $path;
            break;
        }
    }
    ?>

    <!-- Nội dung trang Cấu hình hạn mức -->
    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Cấu hình hạn mức mượn sách</h4>
                <p class="text-muted small mb-0">Thiết lập quy định mượn trả áp dụng toàn hệ thống</p>
            </div>
        </div>

        <?php if (!empty($thongBao)): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($thongBao) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="index.php?controller=phieumuon&action=cauHinhHanMuc" method="POST">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">SỐ LƯỢNG SÁCH TỐI ĐA / PHIẾU</label>
                            <input type="number" name="so_sach_toi_da" class="form-control form-control-lg bg-light fs-6" value="5" min="1" required>
                            <div class="form-text">Số lượng sách tối đa một độc giả có thể mượn cùng lúc.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">SỐ NGÀY MƯỢN TỐI ĐA</label>
                            <input type="number" name="so_ngay_toi_da" class="form-control form-control-lg bg-light fs-6" value="14" min="1" required>
                            <div class="form-text">Thời hạn mặc định tính từ ngày tạo phiếu.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">PHÍ PHẠT QUÁ HẠN (VNĐ/NGÀY)</label>
                            <input type="number" name="phi_phat" class="form-control form-control-lg bg-light fs-6" value="5000" step="1000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">TRẠNG THÁI ÁP DỤNG</label>
                            <select name="trang_thai" class="form-select form-select-lg bg-light fs-6">
                                <option value="1">Kích hoạt áp dụng</option>
                                <option value="0">Tạm dừng</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 text-end border-top">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-3">
                            <i class="bi bi-save me-1"></i> Lưu cấu hình
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>