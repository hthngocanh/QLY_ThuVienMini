```php
<?php
// ======================================================
// app/views/danhmuc.php
// Quản lý danh mục sách
// Sử dụng các hàm trong funsi.php
// ======================================================

require_once __DIR__ . '/../danhmucsach/functionsDanhMuc.php';


// ======================================================
// HÀM CHỐNG XSS
// ======================================================

function escape($data): string
{
    return htmlspecialchars(
        (string) $data,
        ENT_QUOTES,
        'UTF-8'
    );
}


// ======================================================
// XỬ LÝ THÔNG BÁO
// ======================================================

$success = $_GET['success'] ?? '';

$thongBaoThanhCong = '';

if ($success === 'created') {
    $thongBaoThanhCong = 'Thêm danh mục sách thành công!';
}

if ($success === 'updated') {
    $thongBaoThanhCong = 'Cập nhật danh mục sách thành công!';
}

if ($success === 'deleted') {
    $thongBaoThanhCong = 'Xóa danh mục sách thành công!';
}


// ======================================================
// LẤY DANH SÁCH DANH MỤC
// ======================================================

$danhSachDanhMuc = layDanhSachDanhMuc();


// ======================================================
// LẤY DANH MỤC ĐANG SỬA
// ======================================================

$danhMucDangSua = null;

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {

    $danhMucDangSua = layDanhMucTheoId(
        (int) $_GET['edit_id']
    );
}


// ======================================================
// BIẾN FORM
// ======================================================

$tenDanhMuc = '';
$moTa = '';
$trangThai = 'Hoạt động';

$errors = [];


// ======================================================
// XỬ LÝ FORM THÊM
// ======================================================

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'them'
) {

    $tenDanhMuc = trim($_POST['ten_danh_muc'] ?? '');
    $moTa = trim($_POST['mo_ta'] ?? '');
    $trangThai = $_POST['trang_thai'] ?? 'Hoạt động';


    // ------------------------------
    // VALIDATION
    // ------------------------------

    if ($tenDanhMuc === '') {

        $errors['ten_danh_muc'] =
            'Vui lòng nhập tên danh mục.';
    }


    if (
        $trangThai !== 'Hoạt động'
        && $trangThai !== 'Ngừng hoạt động'
    ) {

        $errors['trang_thai'] =
            'Trạng thái không hợp lệ.';
    }


    // ------------------------------
    // THÊM VÀO DATABASE
    // ------------------------------

    if (empty($errors)) {

        if (
            themDanhMuc(
                $tenDanhMuc,
                $moTa,
                $trangThai
            )
        ) {

            header(
                'Location: danhmuc.php?success=created'
            );

            exit;
        }
    }
}


// ======================================================
// XỬ LÝ FORM SỬA
// ======================================================

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'sua'
) {

    $categoryId = (int) ($_POST['category_id'] ?? 0);

    $tenDanhMuc = trim($_POST['ten_danh_muc'] ?? '');
    $moTa = trim($_POST['mo_ta'] ?? '');
    $trangThai = $_POST['trang_thai'] ?? 'Hoạt động';


    // ------------------------------
    // VALIDATION
    // ------------------------------

    if ($categoryId <= 0) {

        $errors['category_id'] =
            'ID danh mục không hợp lệ.';
    }


    if ($tenDanhMuc === '') {

        $errors['ten_danh_muc'] =
            'Vui lòng nhập tên danh mục.';
    }


    if (
        $trangThai !== 'Hoạt động'
        && $trangThai !== 'Ngừng hoạt động'
    ) {

        $errors['trang_thai'] =
            'Trạng thái không hợp lệ.';
    }


    // ------------------------------
    // CẬP NHẬT DATABASE
    // ------------------------------

    if (empty($errors)) {

        if (
            suaDanhMuc(
                $categoryId,
                $tenDanhMuc,
                $moTa,
                $trangThai
            )
        ) {

            header(
                'Location: danhmuc.php?success=updated'
            );

            exit;
        }
    }


    // Nếu lỗi thì lấy lại dữ liệu danh mục đang sửa

    $danhMucDangSua = layDanhMucTheoId(
        $categoryId
    );
}


// ======================================================
// XỬ LÝ XÓA
// ======================================================

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'xoa'
) {

    $categoryId = (int) ($_POST['category_id'] ?? 0);


    if ($categoryId > 0) {

        if (xoaDanhMuc($categoryId)) {

            header(
                'Location: danhmuc.php?success=deleted'
            );

            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

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
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 40px;
            color: #333;
        }

        .navbar {
            background-color: #1e3a8a;
            padding: 15px 25px;
            display: flex;
            gap: 10px;
            margin: -40px -40px 35px -40px;
            flex-wrap: wrap;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
        }

        .navbar a:hover,
        .navbar a.active {
            background-color: #2563eb;
        }

        .container {
            width: 1000px;
            max-width: 100%;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        h2 {
            color: #34495e;
            border-left: 5px solid #3498db;
            padding-left: 10px;
            margin-top: 25px;
        }

        .thong-bao-thanh-cong {
            background-color: #e8f8f0;
            color: #218c5a;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #2ecc71;
        }

        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 4px;
            display: block;
        }

        .input-error {
            border-color: #e74c3c !important;
        }

        form.category-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
            vertical-align: top;
            padding-top: 8px;
        }

        .input-control {
            display: inline-block;
            width: calc(100% - 160px);
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            height: 80px;
            resize: vertical;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 4px rgba(52,152,219,0.3);
        }

        button {
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #3498db;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-warning {
            background-color: #f39c12;
        }

        .btn-warning:hover {
            background-color: #d68910;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-secondary {
            background-color: #7f8c8d;
            text-decoration: none;
            display: inline-block;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
        }

        th {
            background-color: #3498db;
            color: white;
            padding: 12px;
            text-align: center;
        }

        td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #eaf4fb;
        }

        td:first-child {
            text-align: center;
            width: 60px;
        }

        .status-active {
            color: #27ae60;
            font-weight: bold;
        }

        .status-inactive {
            color: #c0392b;
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .actions form {
            margin: 0;
            padding: 0;
            border: none;
            background: none;
        }

        .edit-box {
            background-color: #fff8e1;
            border: 1px solid #f1c40f;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        @media (max-width: 700px) {

            body {
                padding: 15px;
            }

            .navbar {
                margin: -15px -15px 25px -15px;
            }

            label {
                width: 100%;
                margin-bottom: 5px;
            }

            .input-control {
                width: 100%;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }

    </style>

</head>


<body>


<nav class="navbar">

    <a href="../index.php">
        🏠 Trang chủ
    </a>

    <a href="../nguoiDung/User.php">
        👤 Người dùng
    </a>

    <a href="../banSaoSach/bansao.php">
        📖 Bản sao sách
    </a>

    <a href="../phieuMuon/phieumuon.php">
        📋 Phiếu mượn
    </a>

    <a href="danhmuc.php" class="active">
        📚 Danh mục
    </a>

</nav>


<div class="container">

    <h1>
        📚 Quản lý danh mục sách
    </h1>


    <!-- ==================================================
         THÔNG BÁO
    ================================================== -->

    <?php if (!empty($thongBaoThanhCong)): ?>

        <div class="thong-bao-thanh-cong">

            <?= escape($thongBaoThanhCong) ?>

        </div>

    <?php endif; ?>


    <!-- ==================================================
         FORM SỬA
    ================================================== -->

    <?php if ($danhMucDangSua !== null): ?>

        <h2>
            ✏️ Sửa danh mục sách
        </h2>

        <div class="edit-box">

            <form
                method="POST"
                action="danhmuc.php"
                class="category-form"
            >

                <input
                    type="hidden"
                    name="action"
                    value="sua"
                >

                <input
                    type="hidden"
                    name="category_id"
                    value="<?= escape(
                        $danhMucDangSua['category_id']
                    ) ?>"
                >


                <div class="form-group">

                    <label>
                        Tên danh mục <span style="color:red">*</span>:
                    </label>

                    <div class="input-control">

                        <input
                            type="text"
                            name="ten_danh_muc"
                            value="<?= escape(
                                $danhMucDangSua['ten_danh_muc']
                            ) ?>"
                            class="<?= isset($errors['ten_danh_muc'])
                                ? 'input-error'
                                : '' ?>"
                        >

                        <?php if (isset($errors['ten_danh_muc'])): ?>

                            <span class="error-message">
                                <?= escape(
                                    $errors['ten_danh_muc']
                                ) ?>
                            </span>

                        <?php endif; ?>

                    </div>

                </div>


                <div class="form-group">

                    <label>
                        Mô tả:
                    </label>

                    <div class="input-control">

                        <textarea
                            name="mo_ta"
                        ><?= escape(
                            $danhMucDangSua['mo_ta'] ?? ''
                        ) ?></textarea>

                    </div>

                </div>


                <div class="form-group">

                    <label>
                        Trạng thái:
                    </label>

                    <div class="input-control">

                        <select name="trang_thai">

                            <option
                                value="Hoạt động"
                                <?= $danhMucDangSua['trang_thai']
                                    === 'Hoạt động'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Hoạt động
                            </option>

                            <option
                                value="Ngừng hoạt động"
                                <?= $danhMucDangSua['trang_thai']
                                    === 'Ngừng hoạt động'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Ngừng hoạt động
                            </option>

                        </select>

                    </div>

                </div>


                <button
                    type="submit"
                    class="btn-warning"
                >
                    💾 Cập nhật
                </button>


                <a
                    href="danhmuc.php"
                    class="btn-secondary"
                >
                    Hủy
                </a>

            </form>

        </div>

    <?php endif; ?>


    <!-- ==================================================
         FORM THÊM
    ================================================== -->

    <h2>
        ➕ Thêm danh mục sách
    </h2>


    <form
        method="POST"
        action="danhmuc.php"
        class="category-form"
    >

        <input
            type="hidden"
            name="action"
            value="them"
        >


        <div class="form-group">

            <label>
                Tên danh mục
                <span style="color:red">*</span>:
            </label>

            <div class="input-control">

                <input
                    type="text"
                    name="ten_danh_muc"
                    placeholder="Nhập tên danh mục..."
                    value="<?= escape($tenDanhMuc) ?>"
                    class="<?= isset($errors['ten_danh_muc'])
                        ? 'input-error'
                        : '' ?>"
                >

                <?php if (isset($errors['ten_danh_muc'])): ?>

                    <span class="error-message">

                        <?= escape(
                            $errors['ten_danh_muc']
                        ) ?>

                    </span>

                <?php endif; ?>

            </div>

        </div>


        <div class="form-group">

            <label>
                Mô tả:
            </label>

            <div class="input-control">

                <textarea
                    name="mo_ta"
                    placeholder="Nhập mô tả danh mục..."
                ><?= escape($moTa) ?></textarea>

            </div>

        </div>


        <div class="form-group">

            <label>
                Trạng thái:
            </label>

            <div class="input-control">

                <select name="trang_thai">

                    <option
                        value="Hoạt động"
                        <?= $trangThai === 'Hoạt động'
                            ? 'selected'
                            : '' ?>
                    >
                        Hoạt động
                    </option>

                    <option
                        value="Ngừng hoạt động"
                        <?= $trangThai === 'Ngừng hoạt động'
                            ? 'selected'
                            : '' ?>
                    >
                        Ngừng hoạt động
                    </option>

                </select>

            </div>

        </div>


        <button
            type="submit"
            class="btn-primary"
        >
            ➕ Thêm danh mục
        </button>

    </form>


    <!-- ==================================================
         DANH SÁCH
    ================================================== -->

    <h2>
        📋 Danh sách danh mục sách
    </h2>


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
                        style="text-align:center;color:#7f8c8d;"
                    >
                        Chưa có danh mục nào.
                    </td>

                </tr>

            <?php else: ?>

                <?php foreach (
                    $danhSachDanhMuc
                    as $index => $danhMuc
                ): ?>

                    <tr>

                        <td>
                            <?= $index + 1 ?>
                        </td>

                        <td>
                            <?= escape(
                                $danhMuc['ten_danh_muc']
                            ) ?>
                        </td>

                        <td>
                            <?= escape(
                                $danhMuc['mo_ta'] ?? ''
                            ) ?>
                        </td>

                        <td
                            class="<?= $danhMuc['trang_thai']
                                === 'Hoạt động'
                                ? 'status-active'
                                : 'status-inactive' ?>"
                        >
                            <?= escape(
                                $danhMuc['trang_thai']
                            ) ?>
                        </td>

                        <td>

                            <div class="actions">

                                <!-- SỬA -->

                                <a
                                    href="danhmuc.php?edit_id=<?= escape(
                                        $danhMuc['category_id']
                                    ) ?>"
                                    class="btn-warning"
                                    style="text-decoration:none;color:white;padding:10px 18px;border-radius:6px;"
                                >
                                    ✏️ Sửa
                                </a>


                                <!-- XÓA -->

                                <form
                                    method="POST"
                                    action="danhmuc.php"
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
                                        value="<?= escape(
                                            $danhMuc['category_id']
                                        ) ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-danger"
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

</body>

</html>
```
