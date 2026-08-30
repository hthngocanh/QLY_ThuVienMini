<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "functions.php";


/*
|--------------------------------------------------------------------------
| XỬ LÝ POST
|--------------------------------------------------------------------------
*/

$ketQua = xuLyPhieuMuon($pdo);

if (!empty($ketQua['redirect'])) {
    header("Location: " . $ketQua['redirect']);
    exit;
}

$errors = $ketQua['errors'];
$thongBao = $ketQua['thongBao'];


/*
|--------------------------------------------------------------------------
| DỮ LIỆU FORM
|--------------------------------------------------------------------------
*/

$id = (int)($_GET['edit'] ?? 0);

$phieuSua = null;

if ($id > 0) {

    $phieuSua = getPhieuMuonById($pdo, $id);

    if (!$phieuSua) {
        $id = 0;
    }
}


/*
|--------------------------------------------------------------------------
| DỮ LIỆU MẶC ĐỊNH KHI THÊM
|--------------------------------------------------------------------------
*/

$maNguoiDung = '';
$maBanSao = '';
$ngayMuon = '';
$ngayTra = '';
$trangThai = 'Chờ duyệt';


/*
|--------------------------------------------------------------------------
| NẾU ĐANG SỬA
|--------------------------------------------------------------------------
*/

if ($phieuSua) {

    $maNguoiDung =
        $phieuSua['ma_nguoi_dung'] ?? '';

    $maBanSao =
        $phieuSua['ma_ban_sao'] ?? '';

    $ngayMuon =
        $phieuSua['NgayMuon'] ?? '';

    $ngayTra =
        $phieuSua['NgayTra'] ?? '';

    $trangThai =
        $phieuSua['TrangThai'] ?? 'Chờ duyệt';
}


/*
|--------------------------------------------------------------------------
| NẾU POST BỊ LỖI → GIỮ LẠI DỮ LIỆU ĐÃ NHẬP
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && !empty($errors)
) {

    $id =
        (int)($_POST['id'] ?? 0);

    $maNguoiDung =
        $_POST['ma_nguoi_dung'] ?? '';

    $maBanSao =
        $_POST['ma_ban_sao'] ?? '';

    $ngayMuon =
        $_POST['ngay_muon'] ?? '';

    $ngayTra =
        $_POST['ngay_tra'] ?? '';

    $trangThai =
        $_POST['trang_thai'] ?? 'Chờ duyệt';
}


/*
|--------------------------------------------------------------------------
| LẤY DANH SÁCH PHIẾU MƯỢN
|--------------------------------------------------------------------------
*/

$danhSachPhieuMuon =
    getAllPhieuMuon($pdo);


/*
|--------------------------------------------------------------------------
| THÔNG BÁO
|--------------------------------------------------------------------------
*/

if (isset($_GET['msg'])) {

    if ($_GET['msg'] === 'added') {

        $thongBao =
            'Thêm phiếu mượn thành công!';
    }

    if ($_GET['msg'] === 'updated') {

        $thongBao =
            'Cập nhật phiếu mượn thành công!';
    }

    if ($_GET['msg'] === 'deleted') {

        $thongBao =
            'Xóa phiếu mượn thành công!';
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

    <title>Quản lý phiếu mượn</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f8fafc;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        /* FORM */

        .form-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #ccc;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
        }

        .loi-truong {
            color: #c62828;
            font-size: 14px;
            margin-top: 6px;
            font-weight: bold;
        }

        .input-loi {
            border: 1px solid #c62828;
        }

        .mo-ta {
            color: #777;
            font-size: 13px;
            margin-top: 5px;
        }

        /* BUTTON */

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background: #333;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #555;
        }

        .btn-sua {
            background: #2563eb;
        }

        .btn-xoa {
            background: #c62828;
        }

        .btn-huy {
            background: #777;
        }

        /* THÔNG BÁO */

        .thanh-cong {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        /* TABLE */

        h2 {
            margin-top: 30px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #eee;
        }

        .trang-thai {
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 6px;
            justify-content: center;
        }

        .actions a,
        .actions button {
            padding: 7px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
        }

    </style>

</head>

<body>

<div class="layout">

    <?php require_once "../layout/sidebar.php"; ?>

    <main class="main-content">

        <div class="container">

            <h1>
                QUẢN LÝ PHIẾU MƯỢN
            </h1>



    <!-- =================================================
         THÔNG BÁO
    ================================================== -->

    <?php if ($thongBao !== ''): ?>

        <div class="thanh-cong">

            <?= e($thongBao) ?>

        </div>

    <?php endif; ?>



    <!-- =================================================
         FORM THÊM / SỬA
    ================================================== -->

    <div class="form-box">


        <h2>

            <?= $id > 0
                ? 'Sửa phiếu mượn'
                : 'Thêm phiếu mượn'
            ?>

        </h2>



        <form
            method="POST"
            action="phieumuon.php"
        >


            <!-- ACTION -->

            <input
                type="hidden"
                name="action"
                value="<?= $id > 0 ? 'edit' : 'add' ?>"
            >



            <!-- ID KHI SỬA -->

            <?php if ($id > 0): ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?= e($id) ?>"
                >

            <?php endif; ?>



            <!-- =================================================
                 NGƯỜI MƯỢN
            ================================================== -->

            <div class="form-group">


                <label for="ma_nguoi_dung">

                    Người mượn:

                </label>


                <input
                    type="text"
                    id="ma_nguoi_dung"
                    name="ma_nguoi_dung"
                    value="<?= e($maNguoiDung) ?>"
                    placeholder="Nhập mã người dùng, ví dụ AD001"
                    maxlength="20"
                    autocomplete="off"
                    class="<?= isset($errors['ma_nguoi_dung'])
                        ? 'input-loi'
                        : '' ?>"
                    required
                >



                <?php if (isset($errors['ma_nguoi_dung'])): ?>

                    <div class="loi-truong">

                        <?= e($errors['ma_nguoi_dung']) ?>

                    </div>

                <?php endif; ?>


            </div>



            <!-- =================================================
                 BẢN SAO SÁCH
            ================================================== -->

            <div class="form-group">


                <label for="ma_ban_sao">

                    Bản sao sách:

                </label>


                <input
                    type="text"
                    id="ma_ban_sao"
                    name="ma_ban_sao"
                    value="<?= e($maBanSao) ?>"
                    placeholder="Nhập mã bản sao, ví dụ BS001"
                    maxlength="50"
                    autocomplete="off"
                    class="<?= isset($errors['ma_ban_sao'])
                        ? 'input-loi'
                        : '' ?>"
                    required
                >



                <?php if (isset($errors['ma_ban_sao'])): ?>

                    <div class="loi-truong">

                        <?= e($errors['ma_ban_sao']) ?>

                    </div>

                <?php endif; ?>


            </div>



            <!-- =================================================
                 NGÀY MƯỢN
            ================================================== -->

            <div class="form-group">


                <label for="ngay_muon">

                    Ngày mượn:

                </label>


                <input
                    type="date"
                    id="ngay_muon"
                    name="ngay_muon"
                    max="<?= date('Y-m-d') ?>"
                    value="<?= e($ngayMuon) ?>"
                    class="<?= isset($errors['ngay_muon'])
                        ? 'input-loi'
                        : '' ?>"
                    required
                >


                <?php if (isset($errors['ngay_muon'])): ?>

                    <div class="loi-truong">

                        <?= e($errors['ngay_muon']) ?>

                    </div>

                <?php endif; ?>


            </div>



            <!-- =================================================
                 NGÀY TRẢ
            ================================================== -->

            <div class="form-group">


                <label for="ngay_tra">

                    Ngày trả:

                </label>


                <input
                    type="date"
                    id="ngay_tra"
                    name="ngay_tra"
                    max="<?= date('Y-m-d') ?>"
                    value="<?= e($ngayTra) ?>"
                    class="<?= isset($errors['ngay_tra'])
                        ? 'input-loi'
                        : '' ?>"
                >


                <div class="mo-ta">

                    Để trống nếu sách chưa được trả.

                </div>


                <?php if (isset($errors['ngay_tra'])): ?>

                    <div class="loi-truong">

                        <?= e($errors['ngay_tra']) ?>

                    </div>

                <?php endif; ?>


            </div>



            <!-- =================================================
                 TRẠNG THÁI
            ================================================== -->

            <div class="form-group">


                <label for="trang_thai">

                    Trạng thái:

                </label>


                <select
                    id="trang_thai"
                    name="trang_thai"
                    class="<?= isset($errors['trang_thai'])
                        ? 'input-loi'
                        : '' ?>"
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


                    <?php foreach (
                        $trangThaiOptions
                        as $option
                    ): ?>


                        <option
                            value="<?= e($option) ?>"
                            <?= $trangThai === $option
                                ? 'selected'
                                : '' ?>
                        >

                            <?= e($option) ?>

                        </option>


                    <?php endforeach; ?>


                </select>


                <?php if (isset($errors['trang_thai'])): ?>

                    <div class="loi-truong">

                        <?= e($errors['trang_thai']) ?>

                    </div>

                <?php endif; ?>


            </div>



            <!-- =================================================
                 NÚT
            ================================================== -->

            <button type="submit">

                <?= $id > 0
                    ? 'Cập nhật phiếu mượn'
                    : 'Thêm phiếu mượn'
                ?>

            </button>



            <?php if ($id > 0): ?>

                <a
                    href="phieumuon.php"
                    class="btn-huy"
                    style="
                        display:inline-block;
                        padding:10px 20px;
                        color:white;
                        text-decoration:none;
                        border-radius:5px;
                    "
                >

                    Hủy sửa

                </a>

            <?php endif; ?>


        </form>


    </div>



    <!-- =================================================
         DANH SÁCH PHIẾU MƯỢN
    ================================================== -->

    <h2>

        Danh sách phiếu mượn

    </h2>


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

                    <th>Thao tác</th>

                </tr>

            </thead>



            <tbody>


            <?php if (empty($danhSachPhieuMuon)): ?>

                <tr>

                    <td colspan="8">

                        Chưa có dữ liệu.

                    </td>

                </tr>


            <?php else: ?>


                <?php foreach (
                    $danhSachPhieuMuon
                    as $phieu
                ): ?>


                    <tr>


                        <!-- ID -->

                        <td>

                            <?= e(
                                $phieu['ID_PhieuMuon']
                            ) ?>

                        </td>



                        <!-- NGƯỜI MƯỢN -->

                        <td>

                            <?= e(
                                $phieu['ma_nguoi_dung']
                            ) ?>

                            -

                            <?= e(
                                $phieu['ho_ten']
                            ) ?>

                        </td>



                        <!-- BẢN SAO -->

                        <td>

                            <?= e(
                                $phieu['ma_ban_sao']
                            ) ?>

                        </td>



                        <!-- TÊN SÁCH -->

                        <td>

                            <?= e(
                                $phieu['ten_sach']
                            ) ?>

                        </td>



                        <!-- NGÀY MƯỢN -->

                        <td>

                            <?= hienThiNgay(
                                $phieu['NgayMuon']
                            ) ?>

                        </td>



                        <!-- NGÀY TRẢ -->

                        <td>

                            <?php if (
                                !empty($phieu['NgayTra'])
                            ): ?>

                                <?= hienThiNgay(
                                    $phieu['NgayTra']
                                ) ?>

                            <?php else: ?>

                                Chưa trả

                            <?php endif; ?>

                        </td>



                        <!-- TRẠNG THÁI -->

                        <td class="trang-thai">

                            <?= e(
                                $phieu['TrangThai']
                            ) ?>

                        </td>



                        <!-- THAO TÁC -->

                        <td>


                            <div class="actions">


                                <!-- SỬA -->

                                <a
                                    href="phieumuon.php?edit=<?= e(
                                        $phieu['ID_PhieuMuon']
                                    ) ?>"
                                    class="btn-sua"
                                >

                                    Sửa

                                </a>



                                <!-- XÓA -->

                                <form
                                    method="POST"
                                    action="phieumuon.php"
                                    onsubmit="
                                        return confirm(
                                            'Bạn có chắc muốn xóa phiếu mượn này?'
                                        );
                                    "
                                >


                                    <input
                                        type="hidden"
                                        name="action"
                                        value="delete"
                                    >


                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= e(
                                            $phieu['ID_PhieuMuon']
                                        ) ?>"
                                    >


                                    <button
                                        type="submit"
                                        class="btn-xoa"
                                    >

                                        Xóa

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


        </div>

    </main>

</div>

</body>

</html>