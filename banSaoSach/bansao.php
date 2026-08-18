<?php

$idBanSao = "";
$idDauSach = "";
$maBanSao = "";
$trangThai = "Đã trả";
$ngayNhap = "";

$loiIdBanSao = "";
$loiIdDauSach = "";
$loiMaBanSao = "";
$loiTrangThai = "";
$loiNgayNhap = "";

$hopLe = false;
$danhSachBanSao = [];

function trangThaiMuonTra($trangThai)
{
    if ($trangThai == "Đã trả") {
        return "Đã trả";
    }

    if ($trangThai == "Đang mượn") {
        return "Đang mượn";
    }

    return "Chưa trả";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idBanSao = trim($_POST["id_ban_sao"] ?? "");
    $idDauSach = trim($_POST["id_dau_sach"] ?? "");
    $maBanSao = trim($_POST["ma_ban_sao"] ?? "");
    $trangThai = trim($_POST["trang_thai"] ?? "");
    $ngayNhap = trim($_POST["ngay_nhap"] ?? "");

    // Kiểm tra ID bản sao
    if ($idBanSao == "") {
        $loiIdBanSao = "Vui lòng nhập ID bản sao.";
    } elseif (!preg_match('/^[A-Z][0-9]+$/', $idBanSao)) {
        $loiIdBanSao =
            "ID bản sao phải gồm 1 chữ IN HOA ở đầu và số ở sau. Ví dụ: B01.";
    }

    // Kiểm tra ID đầu sách
    if ($idDauSach == "") {
        $loiIdDauSach = "Vui lòng nhập ID đầu sách.";
    } elseif (!preg_match('/^[A-Z][0-9]+$/', $idDauSach)) {
        $loiIdDauSach =
            "ID đầu sách phải gồm 1 chữ IN HOA ở đầu và số ở sau. Ví dụ: D01.";
    }

    // Kiểm tra mã bản sao
    if ($maBanSao == "") {
        $loiMaBanSao = "Vui lòng nhập mã bản sao.";
    } elseif (!preg_match('/^[A-Z][0-9]+$/', $maBanSao)) {
        $loiMaBanSao =
            "Mã bản sao phải gồm 1 chữ IN HOA ở đầu và số ở sau. Ví dụ: M01.";
    }

    // Kiểm tra trạng thái
    $trangThaiHopLe = [
        "Đã trả",
        "Đang mượn",
        "Chưa trả"
    ];

    if (!in_array($trangThai, $trangThaiHopLe)) {
        $loiTrangThai = "Trạng thái không hợp lệ.";
    }

    // Kiểm tra ngày nhập
    if ($ngayNhap == "") {
        $loiNgayNhap = "Vui lòng chọn ngày nhập.";
    } else {
        $ngay = DateTime::createFromFormat("Y-m-d", $ngayNhap);

        if (!$ngay || $ngay->format("Y-m-d") != $ngayNhap) {
            $loiNgayNhap = "Ngày nhập không hợp lệ.";
        }
    }

    // Nếu không có lỗi
    if (
        $loiIdBanSao == "" &&
        $loiIdDauSach == "" &&
        $loiMaBanSao == "" &&
        $loiTrangThai == "" &&
        $loiNgayNhap == ""
    ) {
        $hopLe = true;

        $banSao = [
            "id_ban_sao" => $idBanSao,
            "id_dau_sach" => $idDauSach,
            "ma_ban_sao" => $maBanSao,
            "trang_thai" => $trangThai,
            "ngay_nhap" => $ngayNhap
        ];

        $danhSachBanSao[] = $banSao;
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý bản sao sách</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #f0f6ff;
        }

        h1 {
            text-align: center;
            color: #1e4f8a;
            margin-bottom: 30px;
        }

        form {
            width: 500px;
            margin: 0 auto;
            padding: 25px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }

        label {
            display: block;
            margin-top: 12px;
            margin-bottom: 6px;
            color: #333;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 2px solid #bbb;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            border-color: #3b82c4;
            outline: none;
        }

        .input-loi {
            border: 2px solid red !important;
            background-color: #ffeaea !important;
        }

        .loi-truong {
            color: red;
            font-size: 13px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        button {
            display: block;
            margin: 25px auto 0;
            padding: 11px 25px;
            border: none;
            border-radius: 6px;
            background-color: #2f80c0;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #1e659d;
        }

        .thanh-cong {
            width: 500px;
            margin: 20px auto;
            padding: 12px;
            text-align: center;
            color: green;
            background-color: #eafbea;
            border: 1px solid green;
            border-radius: 5px;
            font-weight: bold;
        }

        .ket-qua {
            width: 95%;
            margin: 35px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
        }

        .ket-qua h2 {
            text-align: center;
            color: #1e4f8a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #2f80c0;
            color: white;
            padding: 12px;
        }

        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .da-tra {
            color: green;
            font-weight: bold;
        }

        .dang-muon {
            color: #d89400;
            font-weight: bold;
        }

        .chua-tra {
            color: red;
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
    <a href="../index.php">🏠 Trang chủ</a>
    <a href="../nguoiDung/User.php">👤 Người dùng</a>
    <a href="bansao.php" class="active">📖 Bản sao sách</a>
</nav>

<h1>QUẢN LÝ BẢN SAO SÁCH</h1>

<form method="post">

    <label for="id_ban_sao">ID bản sao:</label>

    <input
        type="text"
        id="id_ban_sao"
        name="id_ban_sao"
        placeholder="Ví dụ: B01"
        value="<?php echo htmlspecialchars($idBanSao); ?>"
        class="<?php echo $loiIdBanSao != "" ? "input-loi" : ""; ?>"
    >

    <?php if ($loiIdBanSao != "") { ?>
        <p class="loi-truong">
            <?php echo htmlspecialchars($loiIdBanSao); ?>
        </p>
    <?php } ?>


    <label for="id_dau_sach">ID đầu sách:</label>

    <input
        type="text"
        id="id_dau_sach"
        name="id_dau_sach"
        placeholder="Ví dụ: D01"
        value="<?php echo htmlspecialchars($idDauSach); ?>"
        class="<?php echo $loiIdDauSach != "" ? "input-loi" : ""; ?>"
    >

    <?php if ($loiIdDauSach != "") { ?>
        <p class="loi-truong">
            <?php echo htmlspecialchars($loiIdDauSach); ?>
        </p>
    <?php } ?>


    <label for="ma_ban_sao">Mã bản sao:</label>

    <input
        type="text"
        id="ma_ban_sao"
        name="ma_ban_sao"
        placeholder="Ví dụ: M01"
        value="<?php echo htmlspecialchars($maBanSao); ?>"
        class="<?php echo $loiMaBanSao != "" ? "input-loi" : ""; ?>"
    >

    <?php if ($loiMaBanSao != "") { ?>
        <p class="loi-truong">
            <?php echo htmlspecialchars($loiMaBanSao); ?>
        </p>
    <?php } ?>


    <label for="trang_thai">Trạng thái:</label>

    <select
        id="trang_thai"
        name="trang_thai"
        class="<?php echo $loiTrangThai != "" ? "input-loi" : ""; ?>"
    >

        <option
            value="Đã trả"
            <?php if ($trangThai == "Đã trả") echo "selected"; ?>
        >
            Đã trả
        </option>

        <option
            value="Đang mượn"
            <?php if ($trangThai == "Đang mượn") echo "selected"; ?>
        >
            Đang mượn
        </option>

        <option
            value="Chưa trả"
            <?php if ($trangThai == "Chưa trả") echo "selected"; ?>
        >
            Chưa trả
        </option>

    </select>

    <?php if ($loiTrangThai != "") { ?>
        <p class="loi-truong">
            <?php echo htmlspecialchars($loiTrangThai); ?>
        </p>
    <?php } ?>


    <label for="ngay_nhap">Ngày nhập:</label>

    <input
        type="date"
        id="ngay_nhap"
        name="ngay_nhap"
        value="<?php echo htmlspecialchars($ngayNhap); ?>"
        class="<?php echo $loiNgayNhap != "" ? "input-loi" : ""; ?>"
    >

    <?php if ($loiNgayNhap != "") { ?>
        <p class="loi-truong">
            <?php echo htmlspecialchars($loiNgayNhap); ?>
        </p>
    <?php } ?>


    <button type="submit">
        Xác nhận
    </button>

</form>


<?php if ($hopLe) { ?>

<div class="thanh-cong">
    Thêm bản sao thành công!
</div>

<div class="ket-qua">

    <h2>DANH SÁCH BẢN SAO</h2>

    <table>

        <tr>
            <th>STT</th>
            <th>ID bản sao</th>
            <th>ID đầu sách</th>
            <th>Mã bản sao</th>
            <th>Trạng thái</th>
            <th>Ngày nhập</th>
            <th>Trạng thái mượn trả</th>
        </tr>

        <?php
        $stt = 1;

        foreach ($danhSachBanSao as $banSao) {

            $classTrangThai = "";

            if ($banSao["trang_thai"] == "Đã trả") {
                $classTrangThai = "da-tra";
            } elseif ($banSao["trang_thai"] == "Đang mượn") {
                $classTrangThai = "dang-muon";
            } else {
                $classTrangThai = "chua-tra";
            }
        ?>

        <tr>

            <td>
                <?php echo $stt; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($banSao["id_ban_sao"]); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($banSao["id_dau_sach"]); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($banSao["ma_ban_sao"]); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($banSao["trang_thai"]); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($banSao["ngay_nhap"]); ?>
            </td>

            <td class="<?php echo $classTrangThai; ?>">
                <?php
                echo htmlspecialchars(
                    trangThaiMuonTra($banSao["trang_thai"])
                );
                ?>
            </td>

        </tr>

        <?php
            $stt++;
        }
        ?>

    </table>

</div>

<?php } ?>

</body>
</html>