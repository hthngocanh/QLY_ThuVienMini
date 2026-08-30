<?php
session_start();

$ma_sach = "";
$ten_sach = "";
$ma_tac_gia = "";
$tac_gia = "";
$danh_muc = "";
$nha_xuat_ban = "";
$nam_xuat_ban = "";
$isbn = "";
$gia_sach = "";
$mo_ta = "";

$loi = [];
$vi_tri_sua = null;


if (!isset($_SESSION["danh_sach_sach"])) {

    $_SESSION["danh_sach_sach"] = [

        [
            "ma_sach" => "S001",
            "ten_sach" => "Nhà giả kim",
            "ma_tac_gia" => "TG001",
            "tac_gia" => "Paulo Coelho",
            "danh_muc" => "Văn học",
            "nha_xuat_ban" => "NXB Hội Nhà Văn",
            "nam_xuat_ban" => "2020",
            "isbn" => "9786041234567",
            "gia_sach" => "85000",
            "mo_ta" => "Câu chuyện về hành trình theo đuổi ước mơ của một chàng trai trẻ."
        ],

        [
            "ma_sach" => "S002",
            "ten_sach" => "Đắc nhân tâm",
            "ma_tac_gia" => "TG002",
            "tac_gia" => "Dale Carnegie",
            "danh_muc" => "Kỹ năng",
            "nha_xuat_ban" => "NXB Trẻ",
            "nam_xuat_ban" => "2019",
            "isbn" => "9786041123456",
            "gia_sach" => "90000",
            "mo_ta" => "Cuốn sách hướng dẫn cách giao tiếp và ứng xử với mọi người."
        ]

    ];
}

$danh_sach_sach = &$_SESSION["danh_sach_sach"];


function kiemTraDauSach(
    $ma_sach,
    $ten_sach,
    $ma_tac_gia,
    $tac_gia,
    $danh_muc,
    $nha_xuat_ban,
    $nam_xuat_ban,
    $isbn,
    $gia_sach,
    $mo_ta
) {

    $loi = [];

    if ($ma_sach == "") {

        $loi["ma_sach"] = "Mã sách không được để trống.";

    } elseif (mb_strlen($ma_sach) < 2 || mb_strlen($ma_sach) > 20) {

        $loi["ma_sach"] = "Mã sách phải có từ 2 đến 20 ký tự.";

    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $ma_sach)) {

        $loi["ma_sach"] = "Mã sách chỉ được chứa chữ cái và số.";
    }


    if ($ten_sach == "") {

        $loi["ten_sach"] = "Tên sách không được để trống.";

    } elseif (mb_strlen($ten_sach) < 2 || mb_strlen($ten_sach) > 100) {

        $loi["ten_sach"] = "Tên sách phải có từ 2 đến 100 ký tự.";

    } elseif (!preg_match("/[\p{L}\p{N}]/u", $ten_sach)) {

        $loi["ten_sach"] = "Tên sách phải chứa chữ cái hoặc số.";
    }


    if ($ma_tac_gia == "") {

        $loi["ma_tac_gia"] = "Mã tác giả không được để trống.";

    } elseif (mb_strlen($ma_tac_gia) < 2 || mb_strlen($ma_tac_gia) > 20) {

        $loi["ma_tac_gia"] = "Mã tác giả phải có từ 2 đến 20 ký tự.";

    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $ma_tac_gia)) {

        $loi["ma_tac_gia"] = "Mã tác giả chỉ được chứa chữ cái và số.";
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


    if ($nha_xuat_ban == "") {

        $loi["nha_xuat_ban"] = "Nhà xuất bản không được để trống.";

    } elseif (mb_strlen($nha_xuat_ban) < 2 || mb_strlen($nha_xuat_ban) > 100) {

        $loi["nha_xuat_ban"] = "Nhà xuất bản phải có từ 2 đến 100 ký tự.";
    }


    if ($nam_xuat_ban == "") {

        $loi["nam_xuat_ban"] = "Năm xuất bản không được để trống.";

    } elseif (!ctype_digit($nam_xuat_ban)) {

        $loi["nam_xuat_ban"] = "Năm xuất bản phải là số.";

    } elseif ((int)$nam_xuat_ban < 1000 || (int)$nam_xuat_ban > date("Y")) {

        $loi["nam_xuat_ban"] = "Năm xuất bản không hợp lệ.";
    }

    if ($isbn == "") {

        $loi["isbn"] = "ISBN không được để trống.";

    } elseif (!preg_match("/^[0-9]{10,13}$/", $isbn)) {

        $loi["isbn"] = "ISBN phải gồm 10 hoặc 13 chữ số.";
    }


    if ($gia_sach == "") {

        $loi["gia_sach"] = "Giá sách không được để trống.";

    } elseif (!is_numeric($gia_sach)) {

        $loi["gia_sach"] = "Giá sách phải là số.";

    } elseif ((float)$gia_sach <= 0) {

        $loi["gia_sach"] = "Giá sách phải lớn hơn 0.";
    }

    if ($mo_ta == "") {

        $loi["mo_ta"] = "Mô tả không được để trống.";

    } elseif (mb_strlen($mo_ta) > 500) {

        $loi["mo_ta"] = "Mô tả không được vượt quá 500 ký tự.";
    }


    return $loi;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["sua_sach"])) {

        $vi_tri_sua = (int)$_POST["sua_sach"];

        if (isset($danh_sach_sach[$vi_tri_sua])) {

            $ma_sach = $danh_sach_sach[$vi_tri_sua]["ma_sach"];
            $ten_sach = $danh_sach_sach[$vi_tri_sua]["ten_sach"];
            $ma_tac_gia = $danh_sach_sach[$vi_tri_sua]["ma_tac_gia"];
            $tac_gia = $danh_sach_sach[$vi_tri_sua]["tac_gia"];
            $danh_muc = $danh_sach_sach[$vi_tri_sua]["danh_muc"];
            $nha_xuat_ban = $danh_sach_sach[$vi_tri_sua]["nha_xuat_ban"];
            $nam_xuat_ban = $danh_sach_sach[$vi_tri_sua]["nam_xuat_ban"];
            $isbn = $danh_sach_sach[$vi_tri_sua]["isbn"];
            $gia_sach = $danh_sach_sach[$vi_tri_sua]["gia_sach"];
            $mo_ta = $danh_sach_sach[$vi_tri_sua]["mo_ta"];
        }
    }


    elseif (isset($_POST["xoa_sach"])) {

        $vi_tri = (int)$_POST["xoa_sach"];

        if (isset($danh_sach_sach[$vi_tri])) {

            unset($danh_sach_sach[$vi_tri]);

            $danh_sach_sach = array_values($danh_sach_sach);

            $_SESSION["danh_sach_sach"] = $danh_sach_sach;
        }
    }

    elseif (isset($_POST["cap_nhat_sach"])) {

        $vi_tri_sua = (int)($_POST["vi_tri_sua"] ?? -1);

        $ma_sach = trim($_POST["ma_sach"] ?? "");
        $ten_sach = trim($_POST["ten_sach"] ?? "");
        $ma_tac_gia = trim($_POST["ma_tac_gia"] ?? "");
        $tac_gia = trim($_POST["tac_gia"] ?? "");
        $danh_muc = trim($_POST["danh_muc"] ?? "");
        $nha_xuat_ban = trim($_POST["nha_xuat_ban"] ?? "");
        $nam_xuat_ban = trim($_POST["nam_xuat_ban"] ?? "");
        $isbn = trim($_POST["isbn"] ?? "");
        $gia_sach = trim($_POST["gia_sach"] ?? "");
        $mo_ta = trim($_POST["mo_ta"] ?? "");


        $loi = kiemTraDauSach(
            $ma_sach,
            $ten_sach,
            $ma_tac_gia,
            $tac_gia,
            $danh_muc,
            $nha_xuat_ban,
            $nam_xuat_ban,
            $isbn,
            $gia_sach,
            $mo_ta
        );

        if (empty($loi)) {

            foreach ($danh_sach_sach as $vi_tri => $sach) {

                if (
                    $vi_tri != $vi_tri_sua &&
                    $sach["ma_sach"] == $ma_sach
                ) {

                    $loi["ma_sach"] = "Mã sách đã tồn tại.";

                    break;
                }
            }
        }

        if (empty($loi)) {

            foreach ($danh_sach_sach as $vi_tri => $sach) {

                if (
                    $vi_tri != $vi_tri_sua &&
                    $sach["isbn"] == $isbn
                ) {

                    $loi["isbn"] = "ISBN đã tồn tại.";

                    break;
                }
            }
        }


        if (
            empty($loi) &&
            $vi_tri_sua >= 0 &&
            isset($danh_sach_sach[$vi_tri_sua])
        ) {

            $danh_sach_sach[$vi_tri_sua] = [

                "ma_sach" => $ma_sach,
                "ten_sach" => $ten_sach,
                "ma_tac_gia" => $ma_tac_gia,
                "tac_gia" => $tac_gia,
                "danh_muc" => $danh_muc,
                "nha_xuat_ban" => $nha_xuat_ban,
                "nam_xuat_ban" => $nam_xuat_ban,
                "isbn" => $isbn,
                "gia_sach" => $gia_sach,
                "mo_ta" => $mo_ta
            ];


            $_SESSION["danh_sach_sach"] = $danh_sach_sach;

            $vi_tri_sua = null;

            $ma_sach = "";
            $ten_sach = "";
            $ma_tac_gia = "";
            $tac_gia = "";
            $danh_muc = "";
            $nha_xuat_ban = "";
            $nam_xuat_ban = "";
            $isbn = "";
            $gia_sach = "";
            $mo_ta = "";
        }
    }

    elseif (isset($_POST["them_sach"])) {

        $ma_sach = trim($_POST["ma_sach"] ?? "");
        $ten_sach = trim($_POST["ten_sach"] ?? "");
        $ma_tac_gia = trim($_POST["ma_tac_gia"] ?? "");
        $tac_gia = trim($_POST["tac_gia"] ?? "");
        $danh_muc = trim($_POST["danh_muc"] ?? "");
        $nha_xuat_ban = trim($_POST["nha_xuat_ban"] ?? "");
        $nam_xuat_ban = trim($_POST["nam_xuat_ban"] ?? "");
        $isbn = trim($_POST["isbn"] ?? "");
        $gia_sach = trim($_POST["gia_sach"] ?? "");
        $mo_ta = trim($_POST["mo_ta"] ?? "");

        $loi = kiemTraDauSach(
            $ma_sach,
            $ten_sach,
            $ma_tac_gia,
            $tac_gia,
            $danh_muc,
            $nha_xuat_ban,
            $nam_xuat_ban,
            $isbn,
            $gia_sach,
            $mo_ta
        );

        if (empty($loi)) {

            foreach ($danh_sach_sach as $sach) {

                if ($sach["ma_sach"] == $ma_sach) {

                    $loi["ma_sach"] = "Mã sách đã tồn tại.";

                    break;
                }
            }
        }

        if (empty($loi)) {

            foreach ($danh_sach_sach as $sach) {

                if ($sach["isbn"] == $isbn) {

                    $loi["isbn"] = "ISBN đã tồn tại.";

                    break;
                }
            }
        }

        if (empty($loi)) {

            $danh_sach_sach[] = [

                "ma_sach" => $ma_sach,
                "ten_sach" => $ten_sach,
                "ma_tac_gia" => $ma_tac_gia,
                "tac_gia" => $tac_gia,
                "danh_muc" => $danh_muc,
                "nha_xuat_ban" => $nha_xuat_ban,
                "nam_xuat_ban" => $nam_xuat_ban,
                "isbn" => $isbn,
                "gia_sach" => $gia_sach,
                "mo_ta" => $mo_ta
            ];


            $_SESSION["danh_sach_sach"] = $danh_sach_sach;


            /* XÓA FORM */

            $ma_sach = "";
            $ten_sach = "";
            $ma_tac_gia = "";
            $tac_gia = "";
            $danh_muc = "";
            $nha_xuat_ban = "";
            $nam_xuat_ban = "";
            $isbn = "";
            $gia_sach = "";
            $mo_ta = "";
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
    width: 620px;
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
    min-height: 48px;
    padding: 10px 12px;
    border: 1px solid #888;
    font-size: 16px;
    border-radius: 3px;
    margin-bottom: 8px;
}

textarea.o-nhap {
    height: 120px;
    resize: vertical;
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
    text-decoration: none;
    display: inline-block;
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
    width: 100%;
    max-width: 1300px;
    margin: 40px auto;
    background-color: white;
    padding: 25px;
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow-x: auto;
}

.danh-sach h2 {
    margin-top: 0;
    text-align: center;
}

table {
    width: 100%;
    min-width: 1250px;
    border-collapse: collapse;
}

th,
td {
    border: 1px solid #999;
    padding: 12px;
    text-align: left;
    vertical-align: top;
}

th {
    background-color: #eeeeee;
    text-align: center;
}

td:nth-child(1),
td:nth-child(3),
td:nth-child(7),
td:nth-child(8),
td:nth-child(9) {
    text-align: center;
}

td:nth-child(10) {
    min-width: 250px;
}

td:last-child {
    text-align: center;
}

td:last-child form {
    display: flex;
    gap: 8px;
    justify-content: center;
}

td:last-child button {
    padding: 7px 12px;
    cursor: pointer;
}

@media (max-width: 1450px) {

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

<div class="layout" style="display: flex; min-height: 100vh;">
    <?php
    $activePage = 'dausach';
    require_once __DIR__ . '/../layout/sidebar.php';
    ?>
    <main class="main-content" style="flex: 1; padding: 35px 40px; overflow-y: auto; background: #f8fafc;">

<h1 class="tieu-de" style="margin-top: 0;">
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
    Mã sách
</label>

<input
    class="o-nhap"
    type="text"
    name="ma_sach"
    value="<?php echo htmlspecialchars($ma_sach); ?>"
>

<?php

if (isset($loi["ma_sach"])) {

    echo "<span class='loi'>"
        . htmlspecialchars($loi["ma_sach"])
        . "</span>";
}

?>

</div>

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
    Mã tác giả
</label>

<input
    class="o-nhap"
    type="text"
    name="ma_tac_gia"
    value="<?php echo htmlspecialchars($ma_tac_gia); ?>"
>

<?php

if (isset($loi["ma_tac_gia"])) {

    echo "<span class='loi'>"
        . htmlspecialchars($loi["ma_tac_gia"])
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

<option value="">
    -- Chọn danh mục --
</option>

<option
    value="Văn học"
    <?php if ($danh_muc == "Văn học") echo "selected"; ?>
>
    Văn học
</option>

<option
    value="Khoa học"
    <?php if ($danh_muc == "Khoa học") echo "selected"; ?>
>
    Khoa học
</option>

<option
    value="Giáo dục"
    <?php if ($danh_muc == "Giáo dục") echo "selected"; ?>
>
    Giáo dục
</option>

<option
    value="Kỹ năng"
    <?php if ($danh_muc == "Kỹ năng") echo "selected"; ?>
>
    Kỹ năng
</option>

<option
    value="Khác"
    <?php if ($danh_muc == "Khác") echo "selected"; ?>
>
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


<div class="truong">

<label class="nhan">
    Nhà xuất bản
</label>

<input
    class="o-nhap"
    type="text"
    name="nha_xuat_ban"
    value="<?php echo htmlspecialchars($nha_xuat_ban); ?>"
>

<?php

if (isset($loi["nha_xuat_ban"])) {

    echo "<span class='loi'>"
        . htmlspecialchars($loi["nha_xuat_ban"])
        . "</span>";
}

?>

</div>

<div class="truong">

<label class="nhan">
    Năm xuất bản
</label>

<input
    class="o-nhap"
    type="number"
    name="nam_xuat_ban"
    value="<?php echo htmlspecialchars($nam_xuat_ban); ?>"
>

<?php

if (isset($loi["nam_xuat_ban"])) {

    echo "<span class='loi'>"
        . htmlspecialchars($loi["nam_xuat_ban"])
        . "</span>";
}

?>

</div>


<div class="truong">

<label class="nhan">
    ISBN
</label>

<input
    class="o-nhap"
    type="text"
    name="isbn"
    value="<?php echo htmlspecialchars($isbn); ?>"
>

<?php

if (isset($loi["isbn"])) {

    echo "<span class='loi'>"
        . htmlspecialchars($loi["isbn"])
        . "</span>";
}

?>

</div>

<div class="truong">

<label class="nhan">
    Giá sách (VNĐ)
</label>

<input
    class="o-nhap"
    type="number"
    name="gia_sach"
    value="<?php echo htmlspecialchars($gia_sach); ?>"
    min="1"
>

<?php

if (isset($loi["gia_sach"])) {

    echo "<span class='loi'>"
        . htmlspecialchars($loi["gia_sach"])
        . "</span>";
}

?>

</div>

<div class="truong">

<label class="nhan">
    Mô tả
</label>

<textarea
    class="o-nhap"
    name="mo_ta"
><?php echo htmlspecialchars($mo_ta); ?></textarea>

<?php

if (isset($loi["mo_ta"])) {

    echo "<span class='loi'>"
        . htmlspecialchars($loi["mo_ta"])
        . "</span>";
}

?>

</div>

<div class="khu-vuc-nut">


<?php if ($vi_tri_sua !== null) { ?>


    <a href="" class="nut">
        Hủy
    </a>


    <input
        type="hidden"
        name="vi_tri_sua"
        value="<?php echo $vi_tri_sua; ?>"
    >


    <button
        type="submit"
        class="nut nut-them"
        name="cap_nhat_sach"
    >
        Cập nhật sách
    </button>


<?php } else { ?>


    <button
        type="reset"
        class="nut"
    >
        Hủy
    </button>


    <button
        type="submit"
        class="nut nut-them"
        name="them_sach"
    >
        Thêm sách
    </button>


<?php } ?>


</div>


</form>


</div>

</div>

<div class="danh-sach">


<h2>
    Danh sách đầu sách
</h2>


<table>


<tr>

<th>Mã sách</th>

<th>Tên sách</th>

<th>Mã tác giả</th>

<th>Tác giả</th>

<th>Danh mục</th>

<th>Nhà xuất bản</th>

<th>Năm xuất bản</th>

<th>ISBN</th>

<th>Giá sách</th>

<th>Mô tả</th>

<th>Thao tác</th>

</tr>


<?php

foreach ($danh_sach_sach as $vi_tri => $sach) {

    echo "<tr>";

    echo "<td>"
        . htmlspecialchars($sach["ma_sach"])
        . "</td>";

    echo "<td>"
        . htmlspecialchars($sach["ten_sach"])
        . "</td>";

    echo "<td>"
        . htmlspecialchars($sach["ma_tac_gia"])
        . "</td>";

    echo "<td>"
        . htmlspecialchars($sach["tac_gia"])
        . "</td>";

    echo "<td>"
        . htmlspecialchars($sach["danh_muc"])
        . "</td>";

    echo "<td>"
        . htmlspecialchars($sach["nha_xuat_ban"])
        . "</td>";

    echo "<td>"
        . htmlspecialchars($sach["nam_xuat_ban"])
        . "</td>";

    echo "<td>"
        . htmlspecialchars($sach["isbn"])
        . "</td>";

    echo "<td>"
        . number_format(
            (float)$sach["gia_sach"],
            0,
            ",",
            "."
        )
        . " VNĐ"
        . "</td>";

    echo "<td>"
        . htmlspecialchars($sach["mo_ta"])
        . "</td>";

    echo "<td>";

    echo "<form method='POST' action=''>";

    echo "<button
            type='submit'
            name='sua_sach'
            value='" . $vi_tri . "'>
            Sửa
          </button>";

    echo "<button
            type='submit'
            name='xoa_sach'
            value='" . $vi_tri . "'>
            Xóa
          </button>";

    echo "</form>";

    echo "</td>";

    echo "</tr>";
}

?>

</table>

</div>


    </main>
</div>

</body>

</html>