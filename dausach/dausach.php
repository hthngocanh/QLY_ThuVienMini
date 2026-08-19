<?php

$ten_sach = "";
$tac_gia = "";
$danh_muc = "";

$danh_sach_sach = [
    [
        "ten_sach" => "Nhà giả kim",
        "tac_gia" => "Paulo Coelho",
        "danh_muc" => "Văn học"
    ],
    [
        "ten_sach" => "Đắc nhân tâm",
        "tac_gia" => "Dale Carnegie",
        "danh_muc" => "Kỹ năng sống"
    ]
];

function kiemTraDauSach($ten_sach, $tac_gia, $danh_muc)
{
    $loi = [];

    if ($ten_sach == "") {
        $loi["ten_sach"] = "Tên sách không được để trống.";
    } elseif (mb_strlen($ten_sach) < 2 || mb_strlen($ten_sach) > 100) {
        $loi["ten_sach"] = "Tên sách phải có từ 2 đến 100 ký tự.";
    } elseif (!preg_match("/[\p{L}\p{N}]/u", $ten_sach)) {
        $loi["ten_sach"] = "Tên sách phải chứa chữ cái hoặc số.";
    }

    if ($tac_gia == "") {
        $loi["tac_gia"] = "Tác giả không được để trống.";
    } elseif (mb_strlen($tac_gia) < 2 || mb_strlen($tac_gia) > 100) {
        $loi["tac_gia"] = "Tác giả phải có từ 2 đến 100 ký tự.";
    } elseif (!preg_match("/^[\p{L}\s]+$/u", $tac_gia)) {
        $loi["tac_gia"] = "Tác giả chỉ được chứa chữ cái và khoảng trắng.";
    }

    if ($danh_muc == "") {
    $loi["danh_muc"] = "Vui lòng chọn danh mục.";
    }

    return $loi;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["xoa_sach"])) {

        $vi_tri = $_POST["xoa_sach"];

        unset($danh_sach_sach[$vi_tri]);
        $danh_sach_sach = array_values($danh_sach_sach);
    }

    else {
    $ten_sach = trim($_POST["ten_sach"] ?? "");
    $tac_gia = trim($_POST["tac_gia"] ?? "");
    $danh_muc = trim($_POST["danh_muc"] ?? "");

    $loi = kiemTraDauSach($ten_sach, $tac_gia, $danh_muc);

    if (empty($loi)) {

    $danh_sach_sach[] = [
        "ten_sach" => $ten_sach,
        "tac_gia" => $tac_gia,
        "danh_muc" => $danh_muc
    ];

    $ten_sach = "";
    $tac_gia = "";
    $danh_muc = "";
    }
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Quản lý đầu sách</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            color: #222;
        }

        .tieu-de {
            text-align: center;
            margin-top: 35px;
            margin-bottom: 25px;
            font-size: 28px;
        }

        .khung-form {
            width: 520px;
            margin: 0 auto;
            background-color: white;
            border: 1px solid #999;
            border-radius: 20px;
            overflow: hidden;
        }

        .tieu-de-form {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #999;
            font-size: 18px;
            font-weight: bold;
        }

        .noi-dung-form {
            padding: 30px 35px;
        }

        .nhan {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .o-nhap {
            width: 100%;
            height: 48px;
            padding: 10px 12px;
            border: 1px solid #888;
            font-size: 16px;
            border-radius: 3px;
            margin-bottom: 8px;
        }

        .o-nhap:focus {
            outline: none;
            border: 2px solid #555;
        }

        .truong {
            margin-bottom: 25px;
        }

        .loi {
            display: block;
            color: #c62828;
            font-size: 14px;
            margin-top: 3px;
        }

        .khu-vuc-nut {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 10px;
        }

        .nut {
            padding: 12px 25px;
            font-size: 15px;
            border: 1px solid #555;
            background-color: white;
            cursor: pointer;
            border-radius: 3px;
        }

        .nut:hover {
            background-color: #eeeeee;
        }

        .nut-them {
            background-color: #333;
            color: white;
            border-color: #333;
        }

        .nut-them:hover {
            background-color: #555;
        }

        .danh-sach {
            width: 900px;
            margin: 40px auto;
            background-color: white;
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .danh-sach h2 {
            margin-top: 0;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #eeeeee;
            text-align: center;
        }

        @media (max-width: 950px) {

            .khung-form {
                width: 90%;
            }

            .danh-sach {
                width: 90%;
            }
        }

    </style>

</head>


<body>

    <h1 class="tieu-de">
        HỆ THỐNG QUẢN LÝ THƯ VIỆN MINI
    </h1>

    <div class="khung-form">

        <div class="tieu-de-form">
            QUẢN LÝ ĐẦU SÁCH
        </div>


        <div class="noi-dung-form">

            <form method="POST" action="">

                <div class="truong">

                    <label class="nhan">
                        Tên sách
                    </label>

                    <input
                        class="o-nhap"
                        type="text"
                        name="ten_sach"
                        value="<?php echo htmlspecialchars($ten_sach); ?>"
                    >

                    <?php
                    if (isset($loi["ten_sach"])) {
                        echo "<span class='loi'>"
                            . htmlspecialchars($loi["ten_sach"])
                            . "</span>";
                    }
                    ?>

                </div>

                <div class="truong">

                    <label class="nhan">
                        Tác giả
                    </label>

                    <input
                        class="o-nhap"
                        type="text"
                        name="tac_gia"
                        value="<?php echo htmlspecialchars($tac_gia); ?>"
                    >

                    <?php
                    if (isset($loi["tac_gia"])) {
                        echo "<span class='loi'>"
                            . htmlspecialchars($loi["tac_gia"])
                            . "</span>";
                    }
                    ?>

                </div>

                <div class="truong">

                     <label class="nhan">
                        Danh mục
                     </label>

<select class="o-nhap" name="danh_muc">

    <option value="">-- Chọn danh mục --</option>

    <option value="Văn học"
        <?php if ($danh_muc == "Văn học") echo "selected"; ?>>
        Văn học
    </option>

    <option value="Khoa học"
        <?php if ($danh_muc == "Khoa học") echo "selected"; ?>>
        Khoa học
    </option>

    <option value="Giáo dục"
        <?php if ($danh_muc == "Giáo dục") echo "selected"; ?>>
        Giáo dục
    </option>

    <option value="Kỹ năng"
        <?php if ($danh_muc == "Kỹ năng") echo "selected"; ?>>
        Kỹ năng
    </option>

    <option value="Khác"
        <?php if ($danh_muc == "Khác") echo "selected"; ?>>
        Khác
    </option>

</select>

<?php
if (isset($loi["danh_muc"])) {
    echo "<span class='loi'>"
        . htmlspecialchars($loi["danh_muc"])
        . "</span>";
}
?>
                </div>

                <div class="khu-vuc-nut">

                    <button
                        type="reset"
                        class="nut"
                    >
                        Hủy
                    </button>

                    <button
                        type="submit"
                        class="nut nut-them"
                    >
                        Thêm sách
                    </button>

                </div>


            </form>

        </div>

    </div>

    <div class="danh-sach">

        <h2>Danh sách đầu sách</h2>

        <table>

            <tr>
                <th>Tên sách</th>
                <th>Tác giả</th>
                <th>Danh mục</th>
                <th>Thao tác</th>
            </tr>

            <?php

            foreach ($danh_sach_sach as $vi_tri => $sach) {

                echo "<tr>";

                echo "<td>"
                    . htmlspecialchars($sach["ten_sach"])
                    . "</td>";

                echo "<td>"
                    . htmlspecialchars($sach["tac_gia"])
                    . "</td>";

              echo "<td>"
    . htmlspecialchars($sach["danh_muc"])
    . "</td>";

echo "<td>";

echo "<form method='POST' action=''>";

echo "<button type='submit' name='xoa_sach' value='" . $vi_tri . "'>";
echo "Xóa";
echo "</button>";

echo "</form>";

echo "</td>";

echo "</tr>";
            }

            ?>

        </table>

    </div>


</body>

</html>