```php
<?php

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

$thong_bao = "";
$loai_thong_bao = "";

require_once __DIR__ . '/../database/config/database.php';

$pdo = getDB();

$stmt = $pdo->query("
    SELECT category_id, ten_danh_muc
    FROM Categories
    ORDER BY category_id ASC
");

$danh_sach_danh_muc = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tu_khoa = trim($_GET["tu_khoa"] ?? "");
$loc_tac_gia = trim($_GET["loc_tac_gia"] ?? "");
$loc_danh_muc = trim($_GET["loc_danh_muc"] ?? "");
$loc_nam = trim($_GET["loc_nam"] ?? "");

$trang = max(1, (int)($_GET["trang"] ?? 1));
$so_sach_moi_trang = 5;
$offset = ($trang - 1) * $so_sach_moi_trang;

$where = [];
$params = [];

if ($tu_khoa !== "") {

    $where[] = "(
        b.ma_sach LIKE :tu_khoa
        OR b.ten_sach LIKE :tu_khoa
        OR b.ma_tac_gia LIKE :tu_khoa
        OR b.tac_gia LIKE :tu_khoa
        OR b.nha_xuat_ban LIKE :tu_khoa
    )";

    $params["tu_khoa"] = "%" . $tu_khoa . "%";
}

if ($loc_tac_gia !== "") {

    $where[] = "b.tac_gia LIKE :loc_tac_gia";

    $params["loc_tac_gia"] = "%" . $loc_tac_gia . "%";
}

if ($loc_danh_muc !== "") {

    $where[] = "b.category_id = :loc_danh_muc";

    $params["loc_danh_muc"] = $loc_danh_muc;
}

if ($loc_nam !== "") {

    $where[] = "b.nam_xuat_ban = :loc_nam";

    $params["loc_nam"] = $loc_nam;
}

$dieu_kien = "";

if (!empty($where)) {
    $dieu_kien = "WHERE " . implode(" AND ", $where);
}

$sql_dem = "
    SELECT COUNT(*)
    FROM books b
    INNER JOIN Categories c
        ON b.category_id = c.category_id
    $dieu_kien
";

$stmt = $pdo->prepare($sql_dem);
$stmt->execute($params);

$tong_so_sach = (int)$stmt->fetchColumn();

$tong_so_trang = max(
    1,
    (int)ceil($tong_so_sach / $so_sach_moi_trang)
);

$sql = "
    SELECT
        b.id,
        b.ma_sach,
        b.ten_sach,
        b.ma_tac_gia,
        b.tac_gia,
        c.ten_danh_muc AS danh_muc,
        b.nha_xuat_ban,
        b.nam_xuat_ban,
        b.isbn,
        b.gia_sach,
        b.mo_ta
    FROM books b
    INNER JOIN Categories c
        ON b.category_id = c.category_id
    $dieu_kien
    ORDER BY b.id ASC
    LIMIT $so_sach_moi_trang OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$danh_sach_sach = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

        $id = (int)$_POST["sua_sach"];

        $stmt = $pdo->prepare("
            SELECT
                b.id,
                b.ma_sach,
                b.ten_sach,
                b.ma_tac_gia,
                b.tac_gia,
                c.ten_danh_muc AS danh_muc,
                b.nha_xuat_ban,
                b.nam_xuat_ban,
                b.isbn,
                b.gia_sach,
                b.mo_ta
            FROM books b
            INNER JOIN Categories c
                ON b.category_id = c.category_id
            WHERE b.id = :id
        ");

        $stmt->execute([
            "id" => $id
        ]);

        $sach = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($sach) {

            $vi_tri_sua = $sach["id"];

            $ma_sach = $sach["ma_sach"];
            $ten_sach = $sach["ten_sach"];
            $ma_tac_gia = $sach["ma_tac_gia"];
            $tac_gia = $sach["tac_gia"];
            $danh_muc = $sach["danh_muc"];
            $nha_xuat_ban = $sach["nha_xuat_ban"];
            $nam_xuat_ban = $sach["nam_xuat_ban"];
            $isbn = $sach["isbn"];
            $gia_sach = $sach["gia_sach"];
            $mo_ta = $sach["mo_ta"];
        }
    }

elseif (isset($_POST["xoa_sach"])) {

    $id = (int)$_POST["xoa_sach"];

    $stmt = $pdo->prepare(
        "DELETE FROM books
         WHERE id = :id"
    );

    $stmt->execute([
        "id" => $id
    ]);

    $thong_bao = "Xóa sách thành công.";
    $loai_thong_bao = "thanh-cong";
}


    elseif (isset($_POST["cap_nhat_sach"])) {

        $vi_tri_sua = (int)($_POST["id_sua"] ?? -1);

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

            $stmt = $pdo->prepare("
                SELECT COUNT(*)
                FROM books
                WHERE ma_sach = :ma_sach
                AND id != :id
            ");

            $stmt->execute([
                "ma_sach" => $ma_sach,
                "id" => $vi_tri_sua
            ]);

            if ($stmt->fetchColumn() > 0) {
                $loi["ma_sach"] = "Mã sách đã tồn tại.";
            }
        }


        if (empty($loi)) {

            $stmt = $pdo->prepare("
                SELECT COUNT(*)
                FROM books
                WHERE isbn = :isbn
                AND id != :id
            ");

            $stmt->execute([
                "isbn" => $isbn,
                "id" => $vi_tri_sua
            ]);

            if ($stmt->fetchColumn() > 0) {
                $loi["isbn"] = "ISBN đã tồn tại.";
            }
        }


        $categoryId = null;

        if (empty($loi)) {

            $stmt = $pdo->prepare("
                SELECT category_id
                FROM Categories
                WHERE ten_danh_muc = :ten_danh_muc
            ");

            $stmt->execute([
                "ten_danh_muc" => $danh_muc
            ]);

            $categoryId = $stmt->fetchColumn();

            if (!$categoryId) {
                $loi["danh_muc"] = "Danh mục không tồn tại.";
            }
        }


        if (empty($loi)) {

            $stmt = $pdo->prepare("
                UPDATE books SET
                    ma_sach = :ma_sach,
                    ten_sach = :ten_sach,
                    ma_tac_gia = :ma_tac_gia,
                    tac_gia = :tac_gia,
                    category_id = :category_id,
                    nha_xuat_ban = :nha_xuat_ban,
                    nam_xuat_ban = :nam_xuat_ban,
                    isbn = :isbn,
                    gia_sach = :gia_sach,
                    mo_ta = :mo_ta
                WHERE id = :id
            ");

            $stmt->execute([
                "ma_sach" => $ma_sach,
                "ten_sach" => $ten_sach,
                "ma_tac_gia" => $ma_tac_gia,
                "tac_gia" => $tac_gia,
                "category_id" => $categoryId,
                "nha_xuat_ban" => $nha_xuat_ban,
                "nam_xuat_ban" => $nam_xuat_ban,
                "isbn" => $isbn,
                "gia_sach" => $gia_sach,
                "mo_ta" => $mo_ta,
                "id" => $vi_tri_sua
            ]);

            $thong_bao = "Cập nhật sách thành công.";
            $loai_thong_bao = "thanh-cong";

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

            $stmt = $pdo->prepare("
                SELECT COUNT(*)
                FROM books
                WHERE ma_sach = :ma_sach
            ");

            $stmt->execute([
                "ma_sach" => $ma_sach
            ]);

            if ($stmt->fetchColumn() > 0) {
                $loi["ma_sach"] = "Mã sách đã tồn tại.";
            }
        }


        if (empty($loi)) {

            $stmt = $pdo->prepare("
                SELECT COUNT(*)
                FROM books
                WHERE isbn = :isbn
            ");

            $stmt->execute([
                "isbn" => $isbn
            ]);

            if ($stmt->fetchColumn() > 0) {
                $loi["isbn"] = "ISBN đã tồn tại.";
            }
        }


        $categoryId = null;

        if (empty($loi)) {

            $stmt = $pdo->prepare("
                SELECT category_id
                FROM Categories
                WHERE ten_danh_muc = :ten_danh_muc
            ");

            $stmt->execute([
                "ten_danh_muc" => $danh_muc
            ]);

            $categoryId = $stmt->fetchColumn();

            if (!$categoryId) {
                $loi["danh_muc"] = "Danh mục không tồn tại.";
            }
        }


        if (empty($loi)) {

            $stmt = $pdo->prepare("
                INSERT INTO books
                (
                    ma_sach,
                    ten_sach,
                    ma_tac_gia,
                    tac_gia,
                    category_id,
                    nha_xuat_ban,
                    nam_xuat_ban,
                    isbn,
                    gia_sach,
                    mo_ta
                )
                VALUES
                (
                    :ma_sach,
                    :ten_sach,
                    :ma_tac_gia,
                    :tac_gia,
                    :category_id,
                    :nha_xuat_ban,
                    :nam_xuat_ban,
                    :isbn,
                    :gia_sach,
                    :mo_ta
                )
            ");

            $stmt->execute([
                "ma_sach" => $ma_sach,
                "ten_sach" => $ten_sach,
                "ma_tac_gia" => $ma_tac_gia,
                "tac_gia" => $tac_gia,
                "category_id" => $categoryId,
                "nha_xuat_ban" => $nha_xuat_ban,
                "nam_xuat_ban" => $nam_xuat_ban,
                "isbn" => $isbn,
                "gia_sach" => $gia_sach,
                "mo_ta" => $mo_ta
            ]);

            $thong_bao = "Thêm sách thành công.";
            $loai_thong_bao = "thanh-cong";

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
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$danh_sach_sach = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

.thong-bao {
    width: 620px;
    margin: 0 auto 20px auto;
    padding: 14px 18px;
    border-radius: 5px;
    text-align: center;
    font-size: 15px;
}

.thanh-cong {
    background-color: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #81c784;
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

.tim-kiem {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto auto;
    gap: 10px;
    margin-bottom: 20px;
}

.tim-kiem input,
.tim-kiem select {
    padding: 10px;
    font-size: 15px;
    border: 1px solid #999;
    border-radius: 3px;
}

table {
    width: 100%;
    min-width: 1300px;
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
td:nth-child(2),
td:nth-child(4),
td:nth-child(8),
td:nth-child(9),
td:nth-child(10) {
    text-align: center;
}

td:nth-child(11) {
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

.phan-trang {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 25px;
}

.phan-trang a {
    padding: 8px 13px;
    border: 1px solid #999;
    text-decoration: none;
    color: #222;
    border-radius: 3px;
}

.phan-trang a:hover {
    background-color: #eeeeee;
}

.phan-trang .dang-chon {
    background-color: #333;
    color: white;
    border-color: #333;
}

@media (max-width: 1450px) {

    .khung-form,
    .thong-bao {
        width: 90%;
    }

    .danh-sach {
        width: 90%;
    }

    .tim-kiem {
        grid-template-columns: 1fr 1fr;
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

<main
    class="main-content"
    style="
        flex: 1;
        padding: 35px 40px;
        overflow-y: auto;
        background: #f8fafc;
    "
>

<h1 class="tieu-de" style="margin-top: 0;">
    HỆ THỐNG QUẢN LÝ THƯ VIỆN MINI
</h1>


<?php if ($thong_bao !== "") { ?>

    <div class="thong-bao <?php echo $loai_thong_bao; ?>">
        <?php echo htmlspecialchars($thong_bao); ?>
    </div>

<?php } ?>


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

<?php if (isset($loi["ma_sach"])) { ?>

<span class="loi">
    <?php echo htmlspecialchars($loi["ma_sach"]); ?>
</span>

<?php } ?>

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

<?php if (isset($loi["ten_sach"])) { ?>

<span class="loi">
    <?php echo htmlspecialchars($loi["ten_sach"]); ?>
</span>

<?php } ?>

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

<?php if (isset($loi["ma_tac_gia"])) { ?>

<span class="loi">
    <?php echo htmlspecialchars($loi["ma_tac_gia"]); ?>
</span>

<?php } ?>

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

<?php if (isset($loi["tac_gia"])) { ?>

<span class="loi">
    <?php echo htmlspecialchars($loi["tac_gia"]); ?>
</span>

<?php } ?>

</div>


<div class="truong">

<label class="nhan">
    Danh mục
</label>

<select class="o-nhap" name="danh_muc">

<option value="">
    -- Chọn danh mục --
</option>

<?php foreach ($danh_sach_danh_muc as $danh_muc_item) { ?>

<option
    value="<?php echo htmlspecialchars($danh_muc_item["ten_danh_muc"]); ?>"
    <?php
    if ($danh_muc == $danh_muc_item["ten_danh_muc"]) {
        echo "selected";
    }
    ?>
>
    <?php echo htmlspecialchars($danh_muc_item["ten_danh_muc"]); ?>
</option>

<?php } ?>

</select>

<?php if (isset($loi["danh_muc"])) { ?>

<span class="loi">
    <?php echo htmlspecialchars($loi["danh_muc"]); ?>
</span>

<?php } ?>

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

<?php if (isset($loi["nha_xuat_ban"])) { ?>

<span class="loi">
    <?php echo htmlspecialchars($loi["nha_xuat_ban"]); ?>
</span>

<?php } ?>

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

<?php if (isset($loi["nam_xuat_ban"])) { ?>

<span class="loi">
    <?php echo htmlspecialchars($loi["nam_xuat_ban"]); ?>
</span>

<?php } ?>

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

<?php if (isset($loi["isbn"])) { ?>

<span class="loi">
    <?php echo htmlspecialchars($loi["isbn"]); ?>
</span>

<?php } ?>

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

<?php if (isset($loi["gia_sach"])) { ?>

<span class="loi">
    <?php echo htmlspecialchars($loi["gia_sach"]); ?>
</span>

<?php } ?>

</div>


<div class="truong">

<label class="nhan">
    Mô tả
</label>

<textarea
    class="o-nhap"
    name="mo_ta"
><?php echo htmlspecialchars($mo_ta); ?></textarea>

<?php if (isset($loi["mo_ta"])) { ?>

<span class="loi">
    <?php echo htmlspecialchars($loi["mo_ta"]); ?>
</span>

<?php } ?>

</div>


<div class="khu-vuc-nut">

<?php if ($vi_tri_sua !== null) { ?>

<a
    href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
    class="nut"
>
    Hủy
</a>

<input
    type="hidden"
    name="id_sua"
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


<!-- TÌM KIẾM NÂNG CAO -->

<form
    method="GET"
    action=""
    class="tim-kiem"
>

<input
    type="text"
    name="tu_khoa"
    placeholder="Mã sách, tên sách, tác giả, NXB..."
    value="<?php echo htmlspecialchars($tu_khoa); ?>"
>


<input
    type="text"
    name="loc_tac_gia"
    placeholder="Tên tác giả"
    value="<?php echo htmlspecialchars($loc_tac_gia); ?>"
>


<select name="loc_danh_muc">

<option value="">
    Tất cả danh mục
</option>

<?php foreach ($danh_sach_danh_muc as $item) { ?>

<option
    value="<?php echo $item["category_id"]; ?>"
    <?php
    if ($loc_danh_muc == $item["category_id"]) {
        echo "selected";
    }
    ?>
>
    <?php echo htmlspecialchars($item["ten_danh_muc"]); ?>
</option>

<?php } ?>

</select>


<input
    type="number"
    name="loc_nam"
    placeholder="Năm xuất bản"
    value="<?php echo htmlspecialchars($loc_nam); ?>"
>


<button
    type="submit"
    class="nut nut-them"
>
    Tìm kiếm
</button>


<a
    href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
    class="nut"
>
    Làm mới
</a>

</form>


<table>

<tr>

<th>STT</th>

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

if (empty($danh_sach_sach)) {

    echo "<tr>";

    echo "<td colspan='12' style='text-align:center;'>";

    echo "Không tìm thấy sách.";

    echo "</td>";

    echo "</tr>";

} else {

    foreach ($danh_sach_sach as $vi_tri => $sach) {

        $stt = $offset + $vi_tri + 1;

        echo "<tr>";

        echo "<td>"
            . $stt
            . "</td>";

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
                value='" . $sach["id"] . "'
            >
                Sửa
            </button>";

        echo "<button
                type='submit'
                name='xoa_sach'
                value='" . $sach["id"] . "'
                onclick=\"return confirm('Bạn có chắc chắn muốn xóa sách này không?');\"
            >
                Xóa
            </button>";

        echo "</form>";

        echo "</td>";

        echo "</tr>";
    }
}

?>

</table>


<?php if ($tong_so_trang > 1) { ?>

<div class="phan-trang">

<?php

for ($i = 1; $i <= $tong_so_trang; $i++) {

    $tham_so = [
        "tu_khoa" => $tu_khoa,
        "loc_tac_gia" => $loc_tac_gia,
        "loc_danh_muc" => $loc_danh_muc,
        "loc_nam" => $loc_nam,
        "trang" => $i
    ];

    $url = $_SERVER["PHP_SELF"] . "?" . http_build_query($tham_so);

?>

<a
    href="<?php echo htmlspecialchars($url); ?>"
    class="<?php echo ($i == $trang) ? "dang-chon" : ""; ?>"
>
    <?php echo $i; ?>
</a>

<?php } ?>

</div>

<?php } ?>

</div>


</main>

</div>

</body>

</html>
```
