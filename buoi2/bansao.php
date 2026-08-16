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

        h2 {
            color: #1e4f8a;
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
            border: 1px solid #bbb;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            border-color: #3b82c4;
            outline: none;
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
            cursor: pointer;
        }

        button:hover {
            background-color: #1e659d;
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

        tr:nth-child(even) {
            background-color: #f4f8fc;
        }

        tr:hover {
            background-color: #e3f0fb;
        }

        .loi {
            text-align: center;
            color: #d93025;
            font-weight: bold;
        }
    </style>
</head>

<body>

<h1>QUẢN LÝ BẢN SAO SÁCH</h1>

<form method="post">

    <label for="id_ban_sao">ID bản sao:</label>
    <input type="text" id="id_ban_sao" name="id_ban_sao" required>

    <label for="id_dau_sach">ID đầu sách:</label>
    <input type="text" id="id_dau_sach" name="id_dau_sach" required>

    <label for="ma_ban_sao">Mã bản sao:</label>
    <input type="text" id="ma_ban_sao" name="ma_ban_sao" required>

    <label for="trang_thai">Trạng thái:</label>
    <select id="trang_thai" name="trang_thai" required>
        <option value="Có sẵn">Có sẵn</option>
        <option value="Đang mượn">Đang mượn</option>
        <option value="Hỏng">Hỏng</option>
        <option value="Mất">Mất</option>
    </select>

    <label for="ngay_nhap">Ngày nhập:</label>
    <input type="date" id="ngay_nhap" name="ngay_nhap" required>

    <button type="submit" name="them_ban_sao">
        Thêm bản sao
    </button>

</form>


<?php

// Hàm tự định nghĩa để kiểm tra khả năng cho mượn
function kiemTraBanSao($trangThai)
{
    if ($trangThai == "Có sẵn") {
        return "Có thể cho mượn";
    } else {
        return "Không thể cho mượn";
    }
}


// Mảng lưu danh sách bản sao
$danhSachBanSao = [];


// Kiểm tra người dùng đã bấm nút thêm bản sao
if (isset($_POST["them_ban_sao"])) {

    // Nhận dữ liệu từ form
    $idBanSao = $_POST["id_ban_sao"];
    $idDauSach = $_POST["id_dau_sach"];
    $maBanSao = $_POST["ma_ban_sao"];
    $trangThai = $_POST["trang_thai"];
    $ngayNhap = $_POST["ngay_nhap"];


    // Kiểm tra dữ liệu
    if (
        empty($idBanSao) ||
        empty($idDauSach) ||
        empty($maBanSao) ||
        empty($trangThai) ||
        empty($ngayNhap)
    ) {

        echo "<p class='loi'>Vui lòng nhập đầy đủ thông tin.</p>";

    } else {

        // Tạo mảng chứa thông tin bản sao
        $banSao = [
            "id_ban_sao" => $idBanSao,
            "id_dau_sach" => $idDauSach,
            "ma_ban_sao" => $maBanSao,
            "trang_thai" => $trangThai,
            "ngay_nhap" => $ngayNhap
        ];


        // Thêm bản sao vào danh sách
        $danhSachBanSao[] = $banSao;


        echo "<div class='ket-qua'>";

        echo "<h2>DANH SÁCH BẢN SAO</h2>";

        echo "<table>";

        echo "<tr>";
        echo "<th>STT</th>";
        echo "<th>ID bản sao</th>";
        echo "<th>ID đầu sách</th>";
        echo "<th>Mã bản sao</th>";
        echo "<th>Trạng thái</th>";
        echo "<th>Ngày nhập</th>";
        echo "<th>Khả năng cho mượn</th>";
        echo "</tr>";


        // Dùng vòng lặp để duyệt danh sách
        $stt = 1;

        foreach ($danhSachBanSao as $banSao) {

            echo "<tr>";

            echo "<td>" . $stt . "</td>";

            echo "<td>" .
                htmlspecialchars($banSao["id_ban_sao"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars($banSao["id_dau_sach"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars($banSao["ma_ban_sao"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars($banSao["trang_thai"]) .
                "</td>";

            echo "<td>" .
                htmlspecialchars($banSao["ngay_nhap"]) .
                "</td>";

            // Gọi hàm tự định nghĩa
            echo "<td>" .
                kiemTraBanSao($banSao["trang_thai"]) .
                "</td>";

            echo "</tr>";

            $stt++;
        }

        echo "</table>";

        echo "</div>";
    }
}

?>

</body>
</html>