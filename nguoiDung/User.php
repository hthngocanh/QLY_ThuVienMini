<?php

require_once "functionsNguoiDung.php";

session_start();

if (!isset($_SESSION["danhSachNguoiDung"])) {
    $_SESSION["danhSachNguoiDung"] = [];
}

$errors = [];
$thongBao = "";
$loaiThongBao = "";

$maNguoiDung = "";
$hoTen = "";
$email = "";
$trangThai = "";
$soSachDangMuon = "";
$hanMucMuon = 5;

if (isset($_POST["xoaTatCa"])) {

    $_SESSION["danhSachNguoiDung"] = [];

    $thongBao = "Đã xóa toàn bộ danh sách người dùng.";
    $loaiThongBao = "success";
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {

    $maNguoiDung = trim($_POST["maNguoiDung"] ?? "");
    $hoTen = trim($_POST["hoTen"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $trangThai = trim($_POST["trangThai"] ?? "");
    $soSachDangMuon = trim($_POST["soSachDangMuon"] ?? "");

    $hanMucMuon = 5;


    if ($maNguoiDung === "") {

        $errors["maNguoiDung"] = "Vui lòng nhập mã người dùng.";
    } elseif (strlen($maNguoiDung) < 3 || strlen($maNguoiDung) > 20) {

        $errors["maNguoiDung"] =
            "Mã người dùng phải từ 3 đến 20 ký tự.";
    }


    if ($hoTen === "") {

        $errors["hoTen"] = "Vui lòng nhập họ tên.";
    } elseif (strlen($hoTen) < 2 || strlen($hoTen) > 100) {

        $errors["hoTen"] =
            "Họ tên phải từ 2 đến 100 ký tự.";
    }

    if ($email === "") {

        $errors["email"] = "Vui lòng nhập email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $errors["email"] = "Email không hợp lệ.";
    }

    if ($trangThai === "") {

        $errors["trangThai"] = "Vui lòng chọn trạng thái.";
    } elseif (
        $trangThai !== "Hoạt động" &&
        $trangThai !== "Bị khóa"
    ) {

        $errors["trangThai"] = "Trạng thái không hợp lệ.";
    }


    if ($soSachDangMuon === "") {

        $errors["soSachDangMuon"] =
            "Vui lòng nhập số sách đang mượn.";
    } elseif (
        filter_var($soSachDangMuon, FILTER_VALIDATE_INT) === false ||
        (int)$soSachDangMuon < 0 ||
        (int)$soSachDangMuon > 5
    ) {

        $errors["soSachDangMuon"] =
            "Số sách đang mượn phải là số nguyên từ 0 đến 5.";
    }

    if (empty($errors)) {

        $soSachDangMuon = (int)$soSachDangMuon;


        $maDaTonTai = false;

        foreach ($_SESSION["danhSachNguoiDung"] as $nguoiDung) {

            if ($nguoiDung["maNguoiDung"] === $maNguoiDung) {

                $maDaTonTai = true;
                break;
            }
        }

        if ($maDaTonTai) {

            $errors["maNguoiDung"] =
                "Mã người dùng đã tồn tại.";
        } else {

            $nguoiDung = [
                "maNguoiDung" => $maNguoiDung,
                "hoTen" => $hoTen,
                "email" => $email,
                "trangThai" => $trangThai,
                "soSachDangMuon" => $soSachDangMuon,
                "hanMucMuon" => $hanMucMuon
            ];

            $_SESSION["danhSachNguoiDung"][] = $nguoiDung;

            $thongBao = "Thêm người dùng thành công.";
            $loaiThongBao = "success";

            // Xóa dữ liệu trên form sau khi thêm thành công
            $maNguoiDung = "";
            $hoTen = "";
            $email = "";
            $trangThai = "";
            $soSachDangMuon = "";
            $hanMucMuon = 5;
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
        content="width=device-width, initial-scale=1.0">

    <title>Quản lý người dùng</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #1e3a8a;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 15px;
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
            border-radius: 6px;
        }

        input:focus,
        select:focus {
            border-color: #2563eb;
            outline: none;
        }

        .input-error {
            border-color: #dc2626;
        }

        .field-error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        button {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background: #2563eb;
            color: white;
            font-weight: bold;
        }

        button:hover {
            opacity: 0.9;
        }

        .btn-danger {
            background: #dc2626;
        }

        .message {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #2563eb;
            color: white;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        .duoc-muon {
            color: #15803d;
            font-weight: bold;
        }

        .khong-duoc-muon {
            color: #dc2626;
            font-weight: bold;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

        .actions {
            margin-top: 20px;
        }

        @media (max-width: 700px) {

            .form-row {
                grid-template-columns: 1fr;
            }

            body {
                padding: 15px;
            }
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

        <a href="User.php" class="active">
            👤 Người dùng
        </a>

        <a href="../banSaoSach/bansao.php">
            📖 Bản sao sách
        </a>
        <a href="../phieuMuon/phieumuon.php">
            📖 Phiếu mượn
        </a>
          <a href="../danhmucsach/danhmuc.php">
            📖 Danh mục
        </a><a href="../danhmucsach/danhmuc.php">
            📖 Danh mục
        </a>
    </nav>
    </nav>

    <div class="container">

        <h1>QUẢN LÝ NGƯỜI DÙNG</h1>

        <?php if ($thongBao !== ""): ?>

            <div class="message <?= htmlspecialchars($loaiThongBao) ?>">
                <?= htmlspecialchars($thongBao) ?>
            </div>

        <?php endif; ?>



        <div class="card">

            <h2>Nhập thông tin người dùng</h2>

            <form method="POST" novalidate>

                <div class="form-row">

                    <div class="form-group">

                        <label for="maNguoiDung">
                            Mã người dùng
                        </label>

                        <input
                            type="text"
                            id="maNguoiDung"
                            name="maNguoiDung"
                            placeholder="VD: ND001"
                            maxlength="20"
                            value="<?= htmlspecialchars($maNguoiDung) ?>"
                            class="<?= isset($errors["maNguoiDung"]) ? "input-error" : "" ?>">

                        <?php if (isset($errors["maNguoiDung"])): ?>

                            <div class="field-error">
                                <?= htmlspecialchars($errors["maNguoiDung"]) ?>
                            </div>

                        <?php endif; ?>

                    </div>


                    <div class="form-group">

                        <label for="hoTen">
                            Họ tên
                        </label>

                        <input
                            type="text"
                            id="hoTen"
                            name="hoTen"
                            placeholder="VD: Nguyễn Văn An"
                            maxlength="100"
                            value="<?= htmlspecialchars($hoTen) ?>"
                            class="<?= isset($errors["hoTen"]) ? "input-error" : "" ?>">

                        <?php if (isset($errors["hoTen"])): ?>

                            <div class="field-error">
                                <?= htmlspecialchars($errors["hoTen"]) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="email">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="VD: an@gmail.com"
                            value="<?= htmlspecialchars($email) ?>"
                            class="<?= isset($errors["email"]) ? "input-error" : "" ?>">

                        <?php if (isset($errors["email"])): ?>

                            <div class="field-error">
                                <?= htmlspecialchars($errors["email"]) ?>
                            </div>

                        <?php endif; ?>

                    </div>


                    <div class="form-group">

                        <label for="trangThai">
                            Trạng thái
                        </label>

                        <select
                            id="trangThai"
                            name="trangThai"
                            class="<?= isset($errors["trangThai"]) ? "input-error" : "" ?>">

                            <option value="">
                                -- Chọn trạng thái --
                            </option>

                            <option
                                value="Hoạt động"
                                <?= $trangThai === "Hoạt động" ? "selected" : "" ?>>
                                Hoạt động
                            </option>

                            <option
                                value="Bị khóa"
                                <?= $trangThai === "Bị khóa" ? "selected" : "" ?>>
                                Bị khóa
                            </option>

                        </select>

                        <?php if (isset($errors["trangThai"])): ?>

                            <div class="field-error">
                                <?= htmlspecialchars($errors["trangThai"]) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="soSachDangMuon">
                            Số sách đang mượn
                        </label>

                        <input
                            type="number"
                            id="soSachDangMuon"
                            name="soSachDangMuon"
                            min="0"
                            max="5"
                            value="<?= htmlspecialchars($soSachDangMuon) ?>"
                            class="<?= isset($errors["soSachDangMuon"]) ? "input-error" : "" ?>">

                        <?php if (isset($errors["soSachDangMuon"])): ?>

                            <div class="field-error">
                                <?= htmlspecialchars($errors["soSachDangMuon"]) ?>
                            </div>

                        <?php endif; ?>

                    </div>


                    <div class="form-group">

                        <label for="hanMucMuon">
                            Hạn mức mượn
                        </label>

                        <input
                            type="text"
                            id="hanMucMuon"
                            value="5 cuốn"
                            readonly>

                    </div>

                </div>


                <button type="submit">
                    Thêm người dùng
                </button>

            </form>

        </div>



        <div class="card">

            <h2>Danh sách người dùng</h2>

            <?php if (count($_SESSION["danhSachNguoiDung"]) === 0): ?>

                <div class="empty">
                    Chưa có người dùng nào.
                </div>

            <?php else: ?>

                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>

                                <th>STT</th>
                                <th>Mã người dùng</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Trạng thái</th>
                                <th>Đang mượn</th>
                                <th>Hạn mức</th>
                                <th>Quyền mượn</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php

                            $stt = 1;

                            foreach (
                                $_SESSION["danhSachNguoiDung"]
                                as $nguoiDung
                            ):

                                $duocMuon = kiemTraDuocMuon(
                                    $nguoiDung["trangThai"],
                                    $nguoiDung["soSachDangMuon"],
                                    $nguoiDung["hanMucMuon"]
                                );

                                $lyDo = layLyDoKhongDuocMuon(
                                    $nguoiDung["trangThai"],
                                    $nguoiDung["soSachDangMuon"],
                                    $nguoiDung["hanMucMuon"]
                                );

                            ?>

                                <tr>

                                    <td>
                                        <?= $stt ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($nguoiDung["maNguoiDung"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($nguoiDung["hoTen"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($nguoiDung["email"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($nguoiDung["trangThai"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string)$nguoiDung["soSachDangMuon"]
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string)$nguoiDung["hanMucMuon"]
                                        ) ?>
                                    </td>

                                    <td>

                                        <?php if ($duocMuon): ?>

                                            <span class="duoc-muon">
                                                Được phép mượn
                                            </span>

                                        <?php else: ?>

                                            <span class="khong-duoc-muon">

                                                Không được mượn

                                                <br>

                                                <small>
                                                    <?= htmlspecialchars($lyDo) ?>
                                                </small>

                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php

                                $stt++;

                            endforeach;

                            ?>

                        </tbody>

                    </table>

                </div>


                <div class="actions">

                    <form method="POST">

                        <button
                            type="submit"
                            name="xoaTatCa"
                            class="btn-danger">

                            Xóa toàn bộ dữ liệu

                        </button>

                    </form>

                </div>

            <?php endif; ?>

        </div>

    </div>

</body>

</html>