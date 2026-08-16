<?php

require_once "functionsNguoiDung.php";

session_start();

if (!isset($_SESSION["danhSachNguoiDung"])) {
    $_SESSION["danhSachNguoiDung"] = [];
}

$thongBao = "";
$loaiThongBao = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $maNguoiDung = trim($_POST["maNguoiDung"] ?? "");
    $hoTen = trim($_POST["hoTen"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $trangThai = $_POST["trangThai"] ?? "";
    $soSachDangMuon = (int) ($_POST["soSachDangMuon"] ?? 0);
    $hanMucMuon = (int) ($_POST["hanMucMuon"] ?? 0);


    if ($maNguoiDung === "") {

        $thongBao = "Vui lòng nhập mã người dùng.";
        $loaiThongBao = "error";
    } elseif ($hoTen === "") {

        $thongBao = "Vui lòng nhập họ tên.";
        $loaiThongBao = "error";
    } elseif ($email === "") {

        $thongBao = "Vui lòng nhập email.";
        $loaiThongBao = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $thongBao = "Email không hợp lệ.";
        $loaiThongBao = "error";
    } elseif ($trangThai === "") {

        $thongBao = "Vui lòng chọn trạng thái.";
        $loaiThongBao = "error";
    } elseif ($soSachDangMuon < 0) {

        $thongBao = "Số sách đang mượn không được nhỏ hơn 0.";
        $loaiThongBao = "error";
    } elseif ($hanMucMuon <= 0) {

        $thongBao = "Hạn mức mượn phải lớn hơn 0.";
        $loaiThongBao = "error";
    } else {

        $maDaTonTai = false;

        foreach ($_SESSION["danhSachNguoiDung"] as $nguoiDung) {

            if ($nguoiDung["maNguoiDung"] === $maNguoiDung) {
                $maDaTonTai = true;
                break;
            }
        }


        if ($maDaTonTai) {

            $thongBao = "Mã người dùng đã tồn tại.";
            $loaiThongBao = "error";
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
        }
    }
}

if (isset($_POST["xoaTatCa"])) {

    $_SESSION["danhSachNguoiDung"] = [];

    $thongBao = "Đã xóa toàn bộ danh sách người dùng.";
    $loaiThongBao = "success";
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
            margin: -30px -30px 30px -30px;
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
        <a href="../index.php">🏠 Trang chủ</a>
        <a href="User.php" class="active">👤 Người dùng</a>
        <a href="../banSaoSach/bansao.php">📖 Bản sao sách</a>
    </nav>
    <div class="container">
        <h1>QUẢN LÝ NGƯỜI DÙNG</h1>
        <?php if ($thongBao !== ""): ?>

            <div class="message <?= $loaiThongBao ?>">
                <?= htmlspecialchars($thongBao) ?>
            </div>

        <?php endif; ?>
        <div class="card">
            <h2>Nhập thông tin người dùng</h2>
            <form method="POST">
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
                            required>
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
                            required>
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
                            required>
                    </div>
                    <div class="form-group">

                        <label for="trangThai">
                            Trạng thái
                        </label>
                        <select
                            id="trangThai"
                            name="trangThai"
                            required>

                            <option value="">
                                -- Chọn trạng thái --
                            </option>

                            <option value="Hoạt động">
                                Hoạt động
                            </option>

                            <option value="Bị khóa">
                                Bị khóa
                            </option>

                        </select>

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
                            value="0"
                            required>
                    </div>
                    <div class="form-group">

                        <label for="hanMucMuon">
                            Hạn mức mượn
                        </label>

                        <input
                            type="number"
                            id="hanMucMuon"
                            name="hanMucMuon"
                            min="1"
                            value="5"
                            required>

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
                                        <?= htmlspecialchars(
                                            $nguoiDung["maNguoiDung"]
                                        ) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(
                                            $nguoiDung["hoTen"]
                                        ) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(
                                            $nguoiDung["email"]
                                        ) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(
                                            $nguoiDung["trangThai"]
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= $nguoiDung["soSachDangMuon"] ?>
                                    </td>

                                    <td>
                                        <?= $nguoiDung["hanMucMuon"] ?>
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