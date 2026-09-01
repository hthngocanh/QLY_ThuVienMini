<?php

// ======================================================
// VIEW: danhmucsach/danhmuc.php
// Controller sẽ truyền dữ liệu sang View
// ======================================================

// Tránh lỗi khi biến chưa được Controller truyền sang
$tenDanhMuc = $tenDanhMuc ?? '';
$moTa = $moTa ?? '';
$trangThai = $trangThai ?? 'Hoạt động';
$errors = $errors ?? [];
$thongBaoThanhCong = $thongBaoThanhCong ?? '';
$danhSachDanhMuc = $danhSachDanhMuc ?? [];
$danhMucDangSua = $danhMucDangSua ?? null;


// ======================================================
// HÀM CHỐNG XSS
// ======================================================

if (!function_exists('escape')) {

    function escape($data): string
    {
        return htmlspecialchars(
            (string)($data ?? ''),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

?>

<!DOCTYPE html>

<html lang="vi">

<head>

```
<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Quản lý danh mục sách</title>

<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-color: #f8fafc;
    }

    .layout {
        display: flex;
        min-height: 100vh;
        width: 100%;
    }

    .main-content {
        flex: 1;
        min-width: 0;
        padding: 30px;
        background-color: #f8fafc;
    }

    .page-title {
        margin-top: 0;
        margin-bottom: 25px;
        color: #1e3a8a;
    }

    .form-card {
        background: #ffffff;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
    }

    textarea.form-control {
        min-height: 90px;
        resize: vertical;
    }

    .error-message {
        color: red;
        font-size: 14px;
        display: block;
        margin-top: 5px;
    }

    .success-message {
        background: #dcfce7;
        color: #166534;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .btn {
        display: inline-block;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        color: white;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-primary {
        background: #2563eb;
    }

    .btn-warning {
        background: #f59e0b;
    }

    .btn-danger {
        background: #ef4444;
    }

    .btn-secondary {
        background: #64748b;
    }

    .btn:hover {
        opacity: 0.9;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    thead {
        background: #f1f5f9;
    }

    th,
    td {
        padding: 12px;
        border-bottom: 1px solid #e2e8f0;
    }

    th {
        font-weight: 600;
    }

    .actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .actions form {
        margin: 0;
    }

    @media (max-width: 768px) {

        .main-content {
            padding: 20px;
        }

        .actions {
            flex-direction: column;
            align-items: stretch;
        }

    }

</style>
```

</head>

<body>

<div class="layout">

```
<!-- ==================================================
     SIDEBAR
================================================== -->

<?php

$activePage = 'danhmuc';

require_once __DIR__ . '/../layout/sidebar.php';

?>


<!-- ==================================================
     NỘI DUNG CHÍNH
================================================== -->

<main class="main-content">

    <h1 class="page-title">
        📚 Quản lý danh mục sách
    </h1>


    <!-- ==================================================
         THÔNG BÁO
    ================================================== -->

    <?php if (!empty($thongBaoThanhCong)): ?>

        <div class="success-message">

            <?= escape($thongBaoThanhCong) ?>

        </div>

    <?php endif; ?>


    <!-- ==================================================
         FORM SỬA
    ================================================== -->

    <?php if (!empty($danhMucDangSua)): ?>

        <h2>✏️ Sửa danh mục sách</h2>

        <div class="form-card">

            <form
                method="POST"
                action="index.php?controller=danhmuc"
            >

                <input
                    type="hidden"
                    name="action"
                    value="sua"
                >

                <input
                    type="hidden"
                    name="category_id"
                    value="<?= escape($danhMucDangSua['category_id']) ?>"
                >


                <div class="form-group">

                    <label>

                        Tên danh mục

                        <span style="color: red;">
                            *
                        </span>

                    </label>

                    <input
                        type="text"
                        name="ten_danh_muc"
                        value="<?= escape($danhMucDangSua['ten_danh_muc']) ?>"
                        class="form-control"
                    >

                    <?php if (isset($errors['ten_danh_muc'])): ?>

                        <span class="error-message">

                            <?= escape($errors['ten_danh_muc']) ?>

                        </span>

                    <?php endif; ?>

                </div>


                <div class="form-group">

                    <label>Mô tả:</label>

                    <textarea
                        name="mo_ta"
                        class="form-control"
                    ><?= escape($danhMucDangSua['mo_ta'] ?? '') ?></textarea>

                </div>


                <div class="form-group">

                    <label>Trạng thái:</label>

                    <select
                        name="trang_thai"
                        class="form-control"
                    >

                        <option
                            value="Hoạt động"
                            <?= ($danhMucDangSua['trang_thai'] === 'Hoạt động') ? 'selected' : '' ?>
                        >
                            Hoạt động
                        </option>

                        <option
                            value="Ngừng hoạt động"
                            <?= ($danhMucDangSua['trang_thai'] === 'Ngừng hoạt động') ? 'selected' : '' ?>
                        >
                            Ngừng hoạt động
                        </option>

                    </select>

                </div>


                <button
                    type="submit"
                    class="btn btn-warning"
                >
                    💾 Cập nhật
                </button>


                <a
                    href="index.php?controller=danhmuc"
                    class="btn btn-secondary"
                >
                    Hủy
                </a>

            </form>

        </div>

    <?php endif; ?>


    <!-- ==================================================
         FORM THÊM
    ================================================== -->

    <h2>➕ Thêm danh mục sách</h2>

    <form
        method="POST"
        action="index.php?controller=danhmuc"
        class="form-card"
    >

        <input
            type="hidden"
            name="action"
            value="them"
        >


        <div class="form-group">

            <label>

                Tên danh mục

                <span style="color: red;">
                    *
                </span>

            </label>


            <input
                type="text"
                name="ten_danh_muc"
                placeholder="Nhập tên danh mục..."
                value="<?= escape($tenDanhMuc) ?>"
                class="form-control"
            >


            <?php if (isset($errors['ten_danh_muc'])): ?>

                <span class="error-message">

                    <?= escape($errors['ten_danh_muc']) ?>

                </span>

            <?php endif; ?>

        </div>


        <div class="form-group">

            <label>Mô tả:</label>

            <textarea
                name="mo_ta"
                placeholder="Nhập mô tả danh mục..."
                class="form-control"
            ><?= escape($moTa) ?></textarea>

        </div>


        <div class="form-group">

            <label>Trạng thái:</label>

            <select
                name="trang_thai"
                class="form-control"
            >

                <option
                    value="Hoạt động"
                    <?= ($trangThai === 'Hoạt động') ? 'selected' : '' ?>
                >
                    Hoạt động
                </option>

                <option
                    value="Ngừng hoạt động"
                    <?= ($trangThai === 'Ngừng hoạt động') ? 'selected' : '' ?>
                >
                    Ngừng hoạt động
                </option>

            </select>

        </div>


        <button
            type="submit"
            class="btn btn-primary"
        >
            ➕ Thêm danh mục
        </button>

    </form>


    <!-- ==================================================
         DANH SÁCH DANH MỤC
    ================================================== -->

    <h2>📋 Danh sách danh mục sách</h2>


    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    <th>STT</th>

                    <th>Tên danh mục</th>

                    <th>Mô tả</th>

                    <th>Trạng thái</th>

                    <th>Thao tác</th>

                </tr>

            </thead>


            <tbody>

                <?php if (empty($danhSachDanhMuc)): ?>

                    <tr>

                        <td
                            colspan="5"
                            style="
                                text-align: center;
                                padding: 15px;
                                color: #7f8c8d;
                            "
                        >

                            Chưa có danh mục nào.

                        </td>

                    </tr>


                <?php else: ?>


                    <?php foreach ($danhSachDanhMuc as $index => $danhMuc): ?>


                        <tr>

                            <td>

                                <?= $index + 1 ?>

                            </td>


                            <td>

                                <?= escape($danhMuc['ten_danh_muc']) ?>

                            </td>


                            <td>

                                <?= escape($danhMuc['mo_ta'] ?? '') ?>

                            </td>


                            <td>

                                <?= escape($danhMuc['trang_thai']) ?>

                            </td>


                            <td>

                                <div class="actions">


                                    <!-- SỬA -->

                                    <a
                                        href="index.php?controller=danhmuc&edit_id=<?= escape($danhMuc['category_id']) ?>"
                                        class="btn btn-warning"
                                    >
                                        ✏️ Sửa
                                    </a>


                                    <!-- XÓA -->

                                    <form
                                        method="POST"
                                        action="index.php?controller=danhmuc"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này không?');"
                                    >

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="xoa"
                                        >

                                        <input
                                            type="hidden"
                                            name="category_id"
                                            value="<?= escape($danhMuc['category_id']) ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-danger"
                                        >
                                            🗑️ Xóa
                                        </button>

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
```

</div>

</body>

</html>
