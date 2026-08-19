<?php
session_start();

if (!isset($_SESSION['phieu_muon'])) {
    $_SESSION['phieu_muon'] = [];
}


/*
|--------------------------------------------------------------------------
| HÀM HỖ TRỢ
|--------------------------------------------------------------------------
*/

// Chống XSS khi hiển thị dữ liệu ra HTML
function e($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


// Chuẩn hóa dữ liệu text
function chuanHoaInput($value)
{
    $value = trim($value);

    // Gom nhiều khoảng trắng thành 1 khoảng trắng
    $value = preg_replace('/[ \t]+/u', ' ', $value);

    return $value ?? '';
}


// Kiểm tra ngày có đúng định dạng Y-m-d hay không
function laNgayHopLe($date)
{
    if (empty($date)) {
        return false;
    }

    $dateObject = DateTime::createFromFormat(
        'Y-m-d',
        $date
    );

    return $dateObject &&
        $dateObject->format('Y-m-d') === $date;
}


// Đổi ngày từ Y-m-d sang d/m/Y để hiển thị
function hienThiNgay($date)
{
    if (empty($date)) {
        return '';
    }

    $dateObject = DateTime::createFromFormat(
        'Y-m-d',
        $date
    );

    if (!$dateObject) {
        return e($date);
    }

    return $dateObject->format('d/m/Y');
}


// Xác định tình trạng phiếu mượn
function xacDinhTinhTrang($hanTra, $ngayTra)
{
    if (!empty($ngayTra)) {
        return "Đã trả";
    }

    if (date('Y-m-d') > $hanTra) {
        return "Quá hạn";
    }

    return "Đang mượn";
}


/*
|--------------------------------------------------------------------------
| BIẾN DỮ LIỆU FORM
|--------------------------------------------------------------------------
*/

$nguoiMuon = '';
$banSaoSach = '';
$ngayMuon = '';
$hanTra = '';
$ngayTra = '';

$errors = [];
$thongBao = '';


/*
|--------------------------------------------------------------------------
| XỬ LÝ FORM
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
    |--------------------------------------------------------------------------
    | 1. NHẬN VÀ CHUẨN HÓA DỮ LIỆU
    |--------------------------------------------------------------------------
    */

    $nguoiMuon = chuanHoaInput(
        $_POST['nguoi_muon'] ?? ''
    );

    $banSaoSach = chuanHoaInput(
        $_POST['ban_sao_sach'] ?? ''
    );

    $ngayMuon = trim(
        $_POST['ngay_muon'] ?? ''
    );

    $hanTra = trim(
        $_POST['han_tra'] ?? ''
    );

    $ngayTra = trim(
        $_POST['ngay_tra'] ?? ''
    );


    /*
    |--------------------------------------------------------------------------
    | 2. KIỂM TRA TRƯỜNG BẮT BUỘC
    |--------------------------------------------------------------------------
    */

    if ($nguoiMuon === '') {

        $errors['nguoi_muon'] =
            'Vui lòng nhập người mượn.';
    }

    if ($banSaoSach === '') {

        $errors['ban_sao_sach'] =
            'Vui lòng nhập mã bản sao sách.';
    }

    if ($ngayMuon === '') {

        $errors['ngay_muon'] =
            'Vui lòng chọn ngày mượn.';
    }

    if ($hanTra === '') {

        $errors['han_tra'] =
            'Vui lòng chọn hạn trả.';
    }


    /*
    |--------------------------------------------------------------------------
    | 3. KIỂM TRA NGƯỜI MƯỢN
    |--------------------------------------------------------------------------
    */

    if ($nguoiMuon !== '') {

        $doDaiNguoiMuon = mb_strlen(
            $nguoiMuon,
            'UTF-8'
        );

        if (
            $doDaiNguoiMuon < 2 ||
            $doDaiNguoiMuon > 50
        ) {

            $errors['nguoi_muon'] =
                'Người mượn phải từ 2 đến 50 ký tự.';
        }

        // Chỉ cho phép chữ và khoảng trắng
        elseif (
            !preg_match(
                '/^[\p{L}\s]+$/u',
                $nguoiMuon
            )
        ) {

            $errors['nguoi_muon'] =
                'Tên người mượn chỉ được chứa chữ cái và khoảng trắng.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 4. KIỂM TRA BẢN SAO SÁCH
    |--------------------------------------------------------------------------
    */

    if ($banSaoSach !== '') {

        $doDaiBanSao = mb_strlen(
            $banSaoSach,
            'UTF-8'
        );

        if (
            $doDaiBanSao < 3 ||
            $doDaiBanSao > 20
        ) {

            $errors['ban_sao_sach'] =
                'Mã bản sao sách phải từ 3 đến 20 ký tự.';
        } elseif (
            !preg_match(
                '/^[A-Za-z0-9_-]+$/',
                $banSaoSach
            )
        ) {

            $errors['ban_sao_sach'] =
                'Mã bản sao chỉ được chứa chữ, số, dấu gạch ngang hoặc gạch dưới.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 5. KIỂM TRA NGÀY MƯỢN
    |--------------------------------------------------------------------------
    */

    if ($ngayMuon !== '') {

        if (!laNgayHopLe($ngayMuon)) {

            $errors['ngay_muon'] =
                'Ngày mượn không hợp lệ.';
        } elseif ($ngayMuon > date('Y-m-d')) {

            $errors['ngay_muon'] =
                'Ngày mượn không được lớn hơn ngày hiện tại.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 6. KIỂM TRA HẠN TRẢ
    |--------------------------------------------------------------------------
    */

    if ($hanTra !== '') {

        if (!laNgayHopLe($hanTra)) {

            $errors['han_tra'] =
                'Hạn trả không hợp lệ.';
        } elseif (
            $ngayMuon !== '' &&
            laNgayHopLe($ngayMuon) &&
            $hanTra < $ngayMuon
        ) {

            $errors['han_tra'] =
                'Hạn trả không được trước ngày mượn.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 7. KIỂM TRA NGÀY TRẢ
    |--------------------------------------------------------------------------
    */

    if ($ngayTra !== '') {

        if (!laNgayHopLe($ngayTra)) {

            $errors['ngay_tra'] =
                'Ngày trả không hợp lệ.';
        } elseif (
            $ngayMuon !== '' &&
            laNgayHopLe($ngayMuon) &&
            $ngayTra < $ngayMuon
        ) {

            $errors['ngay_tra'] =
                'Ngày trả không được trước ngày mượn.';
        } elseif ($ngayTra > date('Y-m-d')) {

            $errors['ngay_tra'] =
                'Ngày trả không được lớn hơn ngày hiện tại.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 8. NẾU KHÔNG CÓ LỖI → LƯU PHIẾU VÀO SESSION
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $phieu = [
            'nguoi_muon' => $nguoiMuon,
            'ban_sao_sach' => $banSaoSach,
            'ngay_muon' => $ngayMuon,
            'han_tra' => $hanTra,
            'ngay_tra' => $ngayTra
        ];

        $_SESSION['phieu_muon'][] = $phieu;

        $thongBao =
            'Thêm phiếu mượn thành công!';


        /*
        |--------------------------------------------------------------------------
        | XÓA DỮ LIỆU FORM SAU KHI THÀNH CÔNG
        |--------------------------------------------------------------------------
        */

        $nguoiMuon = '';
        $banSaoSach = '';
        $ngayMuon = '';
        $hanTra = '';
        $ngayTra = '';
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Quản lý phiếu mượn</title>


    <style>
        * {
            box-sizing: border-box;
        }

        body {

            font-family: Arial, sans-serif;

            background-color: #f5f5f5;

            margin: 0;

            padding: 30px;
        }


        .container {

            width: 1000px;

            max-width: 100%;

            margin: auto;
        }


        h1 {

            text-align: center;

            margin-bottom: 25px;
        }


        .form-box {

            background-color: white;

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


        input {

            width: 100%;

            padding: 10px;

            border: 1px solid #ccc;

            border-radius: 5px;
        }


        input:focus {

            outline: none;

            border-color: #333;
        }


        .mo-ta {

            color: #777;

            font-size: 13px;

            margin-top: 5px;
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


        button {

            padding: 10px 20px;

            border: none;

            border-radius: 5px;

            background-color: #333;

            color: white;

            cursor: pointer;
        }


        button:hover {

            background-color: #555;
        }


        .thanh-cong {

            background-color: #d4edda;

            color: #155724;

            padding: 12px;

            border-radius: 5px;

            margin-bottom: 15px;
        }


        h2 {

            margin-top: 30px;
        }


        table {

            width: 100%;

            border-collapse: collapse;

            background-color: white;
        }


        th,
        td {

            border: 1px solid #ccc;

            padding: 10px;

            text-align: center;
        }


        th {

            background-color: #eee;
        }


        .trang-thai {

            font-weight: bold;
        }

        .navbar {
            background-color: #1e3a8a;
            padding: 15px 25px;
            display: flex;
            gap: 10px;
            margin: -40px -40px 35px -40px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
        }

        .navbar a:hover {
            background-color: #2563eb;
        }

        .navbar a.active {
            background-color: #2563eb;
        }
    </style>

</head>


<body>

    <nav class="navbar">

        <a href="../index.php">
            🏠 Trang chủ
        </a>

        <a href="../nguoiDung/User.php">👤 Người dùng</a>

        <a href="../banSaoSach/bansao.php">
            📖 Bản sao sách
        </a>
        <a href="phieumuon.php" class="active">📖 Phiếu mượn</a>

    </nav>
    <div class="container">


        <h1>QUẢN LÝ PHIẾU MƯỢN</h1>


        <div class="form-box">


            <?php if (!empty($thongBao)): ?>

                <div class="thanh-cong">

                    <?= e($thongBao) ?>

                </div>

            <?php endif; ?>


            <form method="POST" action="">


                <!-- NGƯỜI MƯỢN -->

                <div class="form-group">

                    <label for="nguoi_muon">

                        Người mượn:

                    </label>


                    <input
                        type="text"
                        id="nguoi_muon"
                        name="nguoi_muon"
                        placeholder="Nhập tên người mượn"
                        maxlength="50"
                        value="<?= e($nguoiMuon) ?>"
                        class="<?= isset($errors['nguoi_muon']) ? 'input-loi' : '' ?>"
                        required>


                    <div class="mo-ta">

                        Từ 2 đến 50 ký tự.

                    </div>


                    <?php if (isset($errors['nguoi_muon'])): ?>

                        <div class="loi-truong">

                            <?= e($errors['nguoi_muon']) ?>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- BẢN SAO SÁCH -->

                <div class="form-group">

                    <label for="ban_sao_sach">

                        Bản sao sách:

                    </label>


                    <input
                        type="text"
                        id="ban_sao_sach"
                        name="ban_sao_sach"
                        placeholder="Ví dụ: BS001"
                        minlength="3"
                        maxlength="20"
                        value="<?= e($banSaoSach) ?>"
                        class="<?= isset($errors['ban_sao_sach']) ? 'input-loi' : '' ?>"
                        required>


                    <div class="mo-ta">

                        3-20 ký tự, chỉ gồm chữ, số, - hoặc _.

                    </div>


                    <?php if (isset($errors['ban_sao_sach'])): ?>

                        <div class="loi-truong">

                            <?= e($errors['ban_sao_sach']) ?>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- NGÀY MƯỢN -->

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
                        class="<?= isset($errors['ngay_muon']) ? 'input-loi' : '' ?>"
                        required>


                    <?php if (isset($errors['ngay_muon'])): ?>

                        <div class="loi-truong">

                            <?= e($errors['ngay_muon']) ?>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- HẠN TRẢ -->

                <div class="form-group">

                    <label for="han_tra">

                        Hạn trả:

                    </label>


                    <input
                        type="date"
                        id="han_tra"
                        name="han_tra"
                        value="<?= e($hanTra) ?>"
                        class="<?= isset($errors['han_tra']) ? 'input-loi' : '' ?>"
                        required>


                    <?php if (isset($errors['han_tra'])): ?>

                        <div class="loi-truong">

                            <?= e($errors['han_tra']) ?>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- NGÀY TRẢ -->

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
                        class="<?= isset($errors['ngay_tra']) ? 'input-loi' : '' ?>">


                    <div class="mo-ta">

                        Có thể để trống nếu sách chưa được trả.

                    </div>


                    <?php if (isset($errors['ngay_tra'])): ?>

                        <div class="loi-truong">

                            <?= e($errors['ngay_tra']) ?>

                        </div>

                    <?php endif; ?>

                </div>


                <button type="submit">

                    Thêm phiếu mượn

                </button>


            </form>

        </div>


        <?php if (!empty($_SESSION['phieu_muon'])): ?>

            <h2>Danh sách phiếu mượn</h2>


            <table>

                <tr>

                    <th>STT</th>

                    <th>Người mượn</th>

                    <th>Bản sao sách</th>

                    <th>Ngày mượn</th>

                    <th>Hạn trả</th>

                    <th>Ngày trả</th>

                    <th>Tình trạng</th>

                </tr>


                <?php

                $stt = 1;

                foreach (
                    $_SESSION['phieu_muon']
                    as $phieu
                ):

                ?>


                    <tr>

                        <td>

                            <?= $stt++ ?>

                        </td>


                        <td>

                            <?= e($phieu['nguoi_muon']) ?>

                        </td>


                        <td>

                            <?= e($phieu['ban_sao_sach']) ?>

                        </td>


                        <td>

                            <?= hienThiNgay(
                                $phieu['ngay_muon']
                            ) ?>

                        </td>


                        <td>

                            <?= hienThiNgay(
                                $phieu['han_tra']
                            ) ?>

                        </td>


                        <td>

                            <?php

                            if (!empty($phieu['ngay_tra'])) {

                                echo hienThiNgay(
                                    $phieu['ngay_tra']
                                );
                            } else {

                                echo "Chưa trả";
                            }

                            ?>

                        </td>


                        <td class="trang-thai">

                            <?= e(
                                xacDinhTinhTrang(
                                    $phieu['han_tra'],
                                    $phieu['ngay_tra']
                                )
                            ) ?>

                        </td>

                    </tr>


                <?php endforeach; ?>

            </table>

        <?php endif; ?>


    </div>


</body>

</html>