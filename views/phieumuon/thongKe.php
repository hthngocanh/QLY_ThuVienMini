<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng thống kê</title>
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
    <!-- Nội dung Bảng Thống Kê -->
    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Thống kê mượn trả sách</h4>
                <p class="text-muted small mb-0">Tổng quan tình hình mượn trả và các thông số vận hành</p>
            </div>
        </div>

        <!-- 4 thẻ Card Thống Kê -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="text-muted small fw-semibold">Tổng lượt mượn</div>
                    <div class="fs-2 fw-bold text-primary mt-1">128</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="text-muted small fw-semibold">Đang chờ duyệt</div>
                    <div class="fs-2 fw-bold text-warning mt-1">12</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="text-muted small fw-semibold">Đã trả thành công</div>
                    <div class="fs-2 fw-bold text-success mt-1">95</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="text-muted small fw-semibold">Đang quá hạn</div>
                    <div class="fs-2 fw-bold text-danger mt-1">21</div>
                </div>
            </div>
        </div>

        <!-- Bảng danh sách -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold m-0 text-dark">Danh sách phiếu mượn gần đây</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small">
                        <tr>
                            <th class="ps-4">MÃ PHIẾU</th>
                            <th>ĐỘC GIẢ</th>
                            <th>MÃ BẢN SAO</th>
                            <th>NGÀY MƯỢN</th>
                            <th>NGÀY TRẢ</th>
                            <th>TRẠNG THÁI</th>
                        </tr>
                    </thead>
                    <tbody class="fs-6">
                        <tr>
                            <td class="ps-4 fw-bold text-primary">#PM001</td>
                            <td>Nguyễn Văn A</td>
                            <td>BS-S01-01</td>
                            <td>01/03/2026</td>
                            <td>15/03/2026</td>
                            <td><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Đã trả</span></td>
                        </tr>
                        <tr>
                            <td class="ps-4 fw-bold text-primary">#PM002</td>
                            <td>Trần Thị B</td>
                            <td>BS-S03-02</td>
                            <td>02/03/2026</td>
                            <td>-</td>
                            <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Quá hạn</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>