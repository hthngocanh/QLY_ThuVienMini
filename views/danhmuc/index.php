<?php
// ======================================================
// VIEWS - QUẢN LÝ DANH MỤC SÁCH
// ======================================================

$tenDanhMuc = $tenDanhMuc ?? '';
$moTa = $moTa ?? '';
$errors = $errors ?? [];
$thongBaoThanhCong = $thongBaoThanhCong ?? '';
$danhSachDanhMuc = $danhSachDanhMuc ?? [];
$danhMucDangSua = $danhMucDangSua ?? null;
$vaiTro = $vaiTro ?? ($_SESSION['user']['vai_tro'] ?? '');
$tuKhoa = $tuKhoa ?? '';
$tieuDe = $tieuDe ?? (
    $vaiTro === 'Thủ thư'
        ? 'Danh mục sách'
        : 'Quản lý danh mục'
);

function escape($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$laThuThu = ($vaiTro === 'Thủ thư');
$laAdmin = ($vaiTro === 'Quản trị viên');
?>

<?php
$activePage = 'danhmuc';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../../layout/sidebar.php'; ?>

    <div class="main-content">

<style>
    /* ==================================================
       PAGE
    ================================================== */

    .category-page {
        width: 100%;
        min-height: 100vh;
        padding: 28px 32px 40px;
        background: #F8FAFC;
        box-sizing: border-box;
    }

    .category-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ==================================================
       HEADER
    ================================================== */

    .category-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 24px;
    }

    .category-title {
        margin: 0;
        color: #0F172A;
        font-size: 28px;
        line-height: 1.25;
        font-weight: 800;
        letter-spacing: -0.4px;
    }

    .category-description {
        margin: 7px 0 0;
        color: #64748B;
        font-size: 15px;
        line-height: 1.5;
    }

    /* ==================================================
       ALERT
    ================================================== */

    .category-alert {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        box-sizing: border-box;
    }

    .category-alert.success {
        color: #166534;
        background: #F0FDF4;
        border: 1px solid #DCFCE7;
    }

    .category-alert.error {
        color: #DC2626;
        background: #FEF2F2;
        border: 1px solid #FEE2E2;
    }

    /* ==================================================
       MAIN GRID
    ================================================== */

    .category-layout {
        display: grid;
        grid-template-columns: 330px minmax(0, 1fr);
        gap: 20px;
        align-items: start;
    }

    .category-layout.admin-layout {
        grid-template-columns: minmax(0, 1fr);
    }

    /* ==================================================
       CARD
    ================================================== */

    .category-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .category-card-header {
        padding: 20px 22px 16px;
        border-bottom: 1px solid #E2E8F0;
    }

    .category-card-title {
        margin: 0;
        color: #0F172A;
        font-size: 18px;
        line-height: 1.35;
        font-weight: 700;
    }

    .category-card-subtitle {
        margin: 5px 0 0;
        color: #64748B;
        font-size: 13px;
        line-height: 1.4;
    }

    .category-card-body {
        padding: 22px;
    }

    /* ==================================================
       FORM
    ================================================== */

    .form-group {
        margin-bottom: 17px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 14px;
        line-height: 1.4;
        font-weight: 600;
    }

    .required {
        color: #DC2626;
    }

    .form-input,
    .form-textarea {
        width: 100%;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        background: #FFFFFF;
        color: #0F172A;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-size: 15px;
        outline: none;
        box-sizing: border-box;
        transition:
            border-color 0.15s ease,
            box-shadow 0.15s ease;
    }

    .form-input {
        height: 40px;
        padding: 8px 13px;
    }

    .form-textarea {
        min-height: 90px;
        padding: 10px 13px;
        line-height: 1.5;
        resize: vertical;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: #94A3B8;
    }

    .form-input:focus,
    .form-textarea:focus {
        border-color: #BFDBFE;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .form-input.error,
    .form-textarea.error {
        border-color: #DC2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
    }

    .error-message {
        margin-top: 5px;
        color: #DC2626;
        font-size: 13px;
        line-height: 1.35;
        font-weight: 500;
    }

    /* ==================================================
       BUTTON
    ================================================== */

    .form-actions {
        display: flex;
        gap: 8px;
        margin-top: 20px;
    }

    .btn {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 18px;
        border: 0;
        border-radius: 8px;
        font-family: inherit;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition:
            background 0.15s ease,
            border-color 0.15s ease,
            box-shadow 0.15s ease,
            transform 0.15s ease;
        box-sizing: border-box;
    }

    .btn-primary {
        color: #FFFFFF;
        background: #2563EB;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
    }

    .btn-primary:hover {
        background: #1E3A8A;
        transform: translateY(-1px);
    }

    .btn-secondary {
        color: #334155;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
    }

    .btn-secondary:hover {
        background: #F8FAFC;
        transform: translateY(-1px);
    }

    .btn svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    /* ==================================================
       SEARCH
    ================================================== */

    .search-form {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
    }

    .search-input-wrap {
        position: relative;
        flex: 1;
    }

    .search-input-wrap svg {
        position: absolute;
        left: 13px;
        top: 50%;
        width: 18px;
        height: 18px;
        color: #64748B;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        height: 40px;
        padding: 8px 13px 8px 40px;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        background: #FFFFFF;
        color: #0F172A;
        font-family: inherit;
        font-size: 15px;
        outline: none;
        box-sizing: border-box;
    }

    .search-input:focus {
        border-color: #BFDBFE;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .search-btn {
        height: 40px;
        padding: 0 18px;
        color: #FFFFFF;
        background: #2563EB;
        border: 0;
        border-radius: 8px;
        font-family: inherit;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
    }

    .search-btn:hover {
        background: #1E3A8A;
    }

    /* ==================================================
       TABLE
    ================================================== */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .category-table {
        width: 100%;
        min-width: 720px;
        border-collapse: collapse;
    }

    .category-table th {
        padding: 14px 16px;
        color: #64748B;
        background: #F8FAFC;
        border-bottom: 1px solid #E2E8F0;
        font-size: 13px;
        line-height: 1.4;
        font-weight: 600;
        text-align: left;
        white-space: nowrap;
    }

    .category-table td {
        padding: 15px 16px;
        color: #334155;
        border-bottom: 1px solid #E2E8F0;
        font-size: 15px;
        line-height: 1.5;
        vertical-align: middle;
    }

    .category-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .category-table tbody tr:hover {
        background: #F8FAFC;
    }

    .category-name {
        color: #0F172A;
        font-weight: 600;
    }

    .category-description-text {
        max-width: 320px;
        color: #64748B;
    }

    .book-count {
        color: #334155;
        font-weight: 600;
    }

    /* ==================================================
       BADGES
    ================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 26px;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 13px;
        line-height: 1.2;
        font-weight: 600;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .status-active {
        color: #16A34A;
        background: #F0FDF4;
        border: 1px solid #DCFCE7;
    }

    .status-inactive {
        color: #DC2626;
        background: #FEF2F2;
        border: 1px solid #FEE2E2;
    }

    /* ==================================================
       ACTION
    ================================================== */

    .table-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .action-btn {
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 0 11px;
        border-radius: 6px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        transition:
            background 0.15s ease,
            transform 0.15s ease;
    }

    .action-edit {
        color: #B45309;
        background: #FFFBEB;
        border: 1px solid #FEF3C7;
    }

    .action-edit:hover {
        background: #FEF3C7;
        transform: translateY(-1px);
    }

    .action-status {
        color: #2563EB;
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
    }

    .action-status:hover {
        background: #DBEAFE;
        transform: translateY(-1px);
    }

    .action-btn svg {
        width: 14px;
        height: 14px;
    }

    /* ==================================================
       EMPTY
    ================================================== */

    .empty-state {
        padding: 45px 20px;
        text-align: center;
        color: #64748B;
        font-size: 15px;
    }

    /* ==================================================
       ADMIN STATUS FORM
    ================================================== */

    .status-form {
        margin: 0;
    }

    .status-select {
        height: 34px;
        padding: 0 28px 0 10px;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        background: #FFFFFF;
        color: #334155;
        font-family: inherit;
        font-size: 13px;
        font-weight: 500;
        outline: none;
        cursor: pointer;
    }

    .status-select:focus {
        border-color: #BFDBFE;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    /* ==================================================
       RESPONSIVE
    ================================================== */

    @media (max-width: 1199px) {
        .category-page {
            padding: 24px;
        }

        .category-layout {
            grid-template-columns: 290px minmax(0, 1fr);
        }
    }

    @media (max-width: 960px) {
        .category-page {
            padding: 20px;
        }

        .category-layout {
            grid-template-columns: 1fr;
        }

        .category-header {
            flex-direction: column;
            align-items: stretch;
        }
    }

    @media (max-width: 768px) {
        .category-page {
            padding: 16px;
        }

        .category-title {
            font-size: 24px;
        }

        .category-layout {
            display: block;
        }

        .category-card {
            margin-bottom: 16px;
        }

        .search-form {
            flex-direction: column;
            align-items: stretch;
        }

        .search-btn {
            width: 100%;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
        }

        .table-wrapper {
            overflow: visible;
        }

        .category-table {
            min-width: 0;
        }

        .category-table thead {
            display: none;
        }

        .category-table,
        .category-table tbody,
        .category-table tr,
        .category-table td {
            display: block;
            width: 100%;
        }

        .category-table tr {
            margin-bottom: 12px;
            padding: 12px;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            background: #FFFFFF;
            box-sizing: border-box;
        }

        .category-table td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 9px 4px;
            border-bottom: 0;
        }

        .category-table td::before {
            content: attr(data-label);
            color: #64748B;
            font-size: 13px;
            font-weight: 600;
        }

        .category-description-text {
            max-width: 55%;
            text-align: right;
        }

        .table-actions {
            justify-content: flex-end;
        }
    }
</style>

<div class="category-page">
    <div class="category-container">

        <!-- HEADER -->
        <div class="category-header">
            <div>
                <h1 class="category-title">
                    <?= escape($tieuDe) ?>
                </h1>

                <p class="category-description">
                    <?php if ($laThuThu): ?>
                        Quản lý tên và thông tin mô tả các danh mục sách.
                    <?php else: ?>
                        Theo dõi và quản lý trạng thái các danh mục sách.
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- SUCCESS -->
        <?php if ($thongBaoThanhCong !== ''): ?>
            <div class="category-alert success">
                <?= escape($thongBaoThanhCong) ?>
            </div>
        <?php endif; ?>

        <!-- ERROR -->
        <?php if (!empty($errors['general'])): ?>
            <div class="category-alert error">
                <?= escape($errors['general']) ?>
            </div>
        <?php endif; ?>

        <?php if ($laThuThu): ?>

            <div class="category-layout">

                <!-- FORM -->
                <div class="category-card">

                    <div class="category-card-header">
                        <h2 class="category-card-title">
                            <?= $danhMucDangSua ? 'Chỉnh sửa danh mục' : 'Thêm danh mục' ?>
                        </h2>

                        <p class="category-card-subtitle">
                            <?= $danhMucDangSua
                                ? 'Cập nhật thông tin danh mục sách.'
                                : 'Tạo một danh mục sách mới.' ?>
                        </p>
                    </div>

                    <div class="category-card-body">

                        <form method="POST"
                              action="index.php?controller=danhmuc">

                            <?php if ($danhMucDangSua): ?>

                                <input type="hidden"
                                       name="action"
                                       value="sua">

                                <input type="hidden"
                                       name="category_id"
                                       value="<?= (int)$danhMucDangSua['category_id'] ?>">

                            <?php else: ?>

                                <input type="hidden"
                                       name="action"
                                       value="them">

                            <?php endif; ?>

                            <div class="form-group">

                                <label class="form-label">
                                    Tên danh mục
                                    <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="ten_danh_muc"
                                    class="form-input <?= isset($errors['ten_danh_muc']) ? 'error' : '' ?>"
                                    value="<?= escape($tenDanhMuc) ?>"
                                    maxlength="100"
                                    placeholder="Nhập tên danh mục"
                                    required
                                >

                                <?php if (!empty($errors['ten_danh_muc'])): ?>
                                    <div class="error-message">
                                        <?= escape($errors['ten_danh_muc']) ?>
                                    </div>
                                <?php endif; ?>

                            </div>

                            <div class="form-group">

                                <label class="form-label">
                                    Mô tả
                                </label>

                                <textarea
                                    name="mo_ta"
                                    class="form-textarea <?= isset($errors['mo_ta']) ? 'error' : '' ?>"
                                    maxlength="255"
                                    placeholder="Nhập mô tả cho danh mục"
                                ><?= escape($moTa) ?></textarea>

                                <?php if (!empty($errors['mo_ta'])): ?>
                                    <div class="error-message">
                                        <?= escape($errors['mo_ta']) ?>
                                    </div>
                                <?php endif; ?>

                            </div>

                            <div class="form-actions">

                                <button type="submit"
                                        class="btn btn-primary">

                                    <?php if ($danhMucDangSua): ?>

                                        <svg width="18"
                                             height="18"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                            <polyline points="7 3 7 8 15 8"></polyline>
                                        </svg>

                                        Lưu thay đổi

                                    <?php else: ?>

                                        <svg width="18"
                                             height="18"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>

                                        Thêm danh mục

                                    <?php endif; ?>

                                </button>

                                <?php if ($danhMucDangSua): ?>

                                    <a href="index.php?controller=danhmuc"
                                       class="btn btn-secondary">
                                        Hủy
                                    </a>

                                <?php endif; ?>

                            </div>

                        </form>

                    </div>
                </div>

                <!-- LIST -->
                <div class="category-card">

                    <div class="category-card-header">
                        <h2 class="category-card-title">
                            Danh sách danh mục
                        </h2>

                        <p class="category-card-subtitle">
                            Danh sách các danh mục hiện có trong hệ thống.
                        </p>
                    </div>

                    <div class="category-card-body">

                        <form method="GET"
                              action="index.php"
                              class="search-form">

                            <input type="hidden"
                                   name="controller"
                                   value="danhmuc">

                            <div class="search-input-wrap">

                                <svg viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>

                                <input
                                    type="text"
                                    name="search"
                                    class="search-input"
                                    value="<?= escape($tuKhoa) ?>"
                                    placeholder="Tìm kiếm danh mục..."
                                >

                            </div>

                            <button type="submit"
                                    class="search-btn">
                                Tìm kiếm
                            </button>

                        </form>

                        <div class="table-wrapper">

                            <table class="category-table">

                                <thead>
                                    <tr>
                                        <th style="width: 60px;">STT</th>
                                        <th>Tên danh mục</th>
                                        <th>Mô tả</th>
                                        <th style="width: 130px;">Số lượng sách</th>
                                        <th style="width: 130px;">Trạng thái</th>
                                        <th style="width: 100px;">Thao tác</th>
                                    </tr>
                                </thead>

                                <tbody>

                                <?php if (empty($danhSachDanhMuc)): ?>

                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                Chưa có danh mục nào.
                                            </div>
                                        </td>
                                    </tr>

                                <?php else: ?>

                                    <?php foreach ($danhSachDanhMuc as $index => $danhMuc): ?>

                                        <tr>

                                            <td data-label="STT">
                                                <?= $index + 1 ?>
                                            </td>

                                            <td data-label="Tên danh mục">

                                                <span class="category-name">
                                                    <?= escape($danhMuc['ten_danh_muc']) ?>
                                                </span>

                                            </td>

                                            <td data-label="Mô tả">

                                                <span class="category-description-text">
                                                    <?= $danhMuc['mo_ta']
                                                        ? escape($danhMuc['mo_ta'])
                                                        : 'Chưa có mô tả' ?>
                                                </span>

                                            </td>

                                            <td data-label="Số lượng sách">

                                                <span class="book-count">
                                                    <?= (int)($danhMuc['so_luong_sach'] ?? 0) ?>
                                                </span>

                                            </td>

                                            <td data-label="Trạng thái">

                                                <?php if ($danhMuc['trang_thai'] === 'Hoạt động'): ?>

                                                    <span class="status-badge status-active">
                                                        Hoạt động
                                                    </span>

                                                <?php else: ?>

                                                    <span class="status-badge status-inactive">
                                                        Ngừng hoạt động
                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                            <td data-label="Thao tác">

                                                <div class="table-actions">

                                                    <a
                                                        href="index.php?controller=danhmuc&edit_id=<?= (int)$danhMuc['category_id'] ?>"
                                                        class="action-btn action-edit"
                                                    >

                                                        <svg viewBox="0 0 24 24"
                                                             fill="none"
                                                             stroke="currentColor"
                                                             stroke-width="2"
                                                             stroke-linecap="round"
                                                             stroke-linejoin="round">
                                                            <path d="M12 20h9"></path>
                                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                                        </svg>

                                                        Sửa

                                                    </a>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>
                </div>

            </div>

        <?php elseif ($laAdmin): ?>

            <!-- ADMIN -->
            <div class="category-layout admin-layout">

                <div class="category-card">

                    <div class="category-card-header">

                        <h2 class="category-card-title">
                            Danh sách danh mục
                        </h2>

                        <p class="category-card-subtitle">
                            Quản lý trạng thái hoạt động của danh mục sách.
                        </p>

                    </div>

                    <div class="category-card-body">

                        <form method="GET"
                              action="index.php"
                              class="search-form">

                            <input type="hidden"
                                   name="controller"
                                   value="danhmuc">

                            <div class="search-input-wrap">

                                <svg viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>

                                <input
                                    type="text"
                                    name="search"
                                    class="search-input"
                                    value="<?= escape($tuKhoa) ?>"
                                    placeholder="Tìm kiếm danh mục..."
                                >

                            </div>

                            <button type="submit"
                                    class="search-btn">
                                Tìm kiếm
                            </button>

                        </form>

                        <div class="table-wrapper">

                            <table class="category-table">

                                <thead>
                                    <tr>
                                        <th style="width: 60px;">STT</th>
                                        <th>Tên danh mục</th>
                                        <th>Mô tả</th>
                                        <th style="width: 140px;">Số lượng sách</th>
                                        <th style="width: 150px;">Trạng thái</th>
                                        <th style="width: 170px;">Thao tác</th>
                                    </tr>
                                </thead>

                                <tbody>

                                <?php if (empty($danhSachDanhMuc)): ?>

                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                Chưa có danh mục nào.
                                            </div>
                                        </td>
                                    </tr>

                                <?php else: ?>

                                    <?php foreach ($danhSachDanhMuc as $index => $danhMuc): ?>

                                        <tr>

                                            <td data-label="STT">
                                                <?= $index + 1 ?>
                                            </td>

                                            <td data-label="Tên danh mục">

                                                <span class="category-name">
                                                    <?= escape($danhMuc['ten_danh_muc']) ?>
                                                </span>

                                            </td>

                                            <td data-label="Mô tả">

                                                <span class="category-description-text">
                                                    <?= $danhMuc['mo_ta']
                                                        ? escape($danhMuc['mo_ta'])
                                                        : 'Chưa có mô tả' ?>
                                                </span>

                                            </td>

                                            <td data-label="Số lượng sách">

                                                <span class="book-count">
                                                    <?= (int)($danhMuc['so_luong_sach'] ?? 0) ?>
                                                </span>

                                            </td>

                                            <td data-label="Trạng thái">

                                                <?php if ($danhMuc['trang_thai'] === 'Hoạt động'): ?>

                                                    <span class="status-badge status-active">
                                                        Hoạt động
                                                    </span>

                                                <?php else: ?>

                                                    <span class="status-badge status-inactive">
                                                        Ngừng hoạt động
                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                            <td data-label="Thao tác">

                                                <form
                                                    method="POST"
                                                    action="index.php?controller=danhmuc"
                                                    class="status-form"
                                                >

                                                    <input type="hidden"
                                                           name="action"
                                                           value="doi_trang_thai">

                                                    <input type="hidden"
                                                           name="category_id"
                                                           value="<?= (int)$danhMuc['category_id'] ?>">

                                                    <select
                                                        name="trang_thai"
                                                        class="status-select"
                                                        onchange="this.form.submit()"
                                                    >

                                                        <option
                                                            value="Hoạt động"
                                                            <?= $danhMuc['trang_thai'] === 'Hoạt động' ? 'selected' : '' ?>
                                                        >
                                                            Hoạt động
                                                        </option>

                                                        <option
                                                            value="Ngừng hoạt động"
                                                            <?= $danhMuc['trang_thai'] === 'Ngừng hoạt động' ? 'selected' : '' ?>
                                                        >
                                                            Ngừng hoạt động
                                                        </option>

                                                    </select>

                                                </form>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>
                </div>

            </div>

        <?php endif; ?>

    </div>
</div>

    </div><!-- /.main-content -->
</div><!-- /.layout -->