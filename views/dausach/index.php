<!DOCTYPE html>
<html lang="vi">

<head>

<meta charset="UTF-8">

<title>Quản lý đầu sách</title>

<link rel="stylesheet" href="assets/css/design-system.css">

<style>
.tieu-de {
    text-align: center;
    margin-top: 35px;
    margin-bottom: 25px;
    font-size: var(--font-size-page-title);
    color: var(--text-primary);
}

.khung-form {
    width: 620px;
    margin: 0 auto;
    background-color: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-card);
    overflow: hidden;
}

.tieu-de-form {
    text-align: center;
    padding: 20px;
    border-bottom: 1px solid var(--border);
    font-size: var(--font-size-card-title);
    font-weight: var(--font-weight-bold);
    background-color: var(--bg-page);
    color: var(--text-primary);
}

.noi-dung-form {
    padding: 30px 35px;
}

.nhan {
    display: block;
    margin-bottom: 6px;
    font-size: var(--font-size-label);
    font-weight: var(--font-weight-semibold);
    color: var(--text-body);
}

.o-nhap {
    width: 100%;
    min-height: var(--input-height);
    padding: 8px 13px;
    border: 1px solid var(--border);
    font-size: var(--font-size-body);
    border-radius: var(--radius-input);
    margin-bottom: 8px;
    color: var(--text-body);
    background-color: var(--white);
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

textarea.o-nhap {
    min-height: 90px;
    resize: vertical;
}

.o-nhap:focus {
    outline: none;
    border-color: var(--border-blue);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.truong {
    margin-bottom: 18px;
}

.loi {
    display: block;
    color: var(--danger);
    font-size: var(--font-size-error);
    margin-top: 3px;
}

.thong-bao {
    width: 620px;
    margin: 0 auto 20px auto;
    padding: 12px 16px;
    border-radius: 6px;
    text-align: center;
    font-size: var(--font-size-label);
}

.thanh-cong {
    background-color: #F0FDF4;
    color: #166534;
    border: 1px solid #DCFCE7;
    border-left: 4px solid var(--success);
}

.khu-vuc-nut {
    display: flex;
    justify-content: flex-end;
    gap: var(--gap-button);
    margin-top: 10px;
}

.nut {
    padding: 0 18px;
    height: var(--button-height);
    font-size: var(--font-size-button);
    font-weight: var(--font-weight-semibold);
    border: 1px solid var(--border);
    background-color: var(--white);
    color: var(--text-body);
    cursor: pointer;
    border-radius: var(--radius-button);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}

.nut:hover {
    background-color: var(--primary-light);
    border-color: var(--border-blue);
    color: var(--primary);
}

.nut-them {
    background-color: var(--primary);
    color: var(--white);
    border-color: var(--primary);
    box-shadow: var(--shadow-btn);
}

.nut-them:hover {
    background-color: var(--primary-dark);
    color: var(--white);
    transform: translateY(-1px);
}

.danh-sach {
    width: 100%;
    max-width: 1300px;
    margin: 40px auto;
    background-color: var(--white);
    padding: 25px;
    border: 1px solid var(--border);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-card);
    overflow-x: auto;
}

.danh-sach h2 {
    margin-top: 0;
    margin-bottom: 20px;
    text-align: center;
    color: var(--text-primary);
}

.tim-kiem {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto auto;
    gap: 10px;
    margin-bottom: 20px;
}

.tim-kiem input,
.tim-kiem select {
    padding: 8px 12px;
    height: var(--input-height);
    font-size: var(--font-size-body);
    border: 1px solid var(--border);
    border-radius: var(--radius-input);
    color: var(--text-body);
}

table {
    width: 100%;
    min-width: 1300px;
    border-collapse: collapse;
    font-size: var(--font-size-body);
}

th,
td {
    border-bottom: 1px solid var(--border);
    padding: 12px 14px;
    text-align: left;
    vertical-align: middle;
}

th {
    background-color: var(--bg-page);
    color: var(--text-primary);
    font-size: var(--font-size-label);
    font-weight: var(--font-weight-bold);
    text-align: center;
}

tr:hover {
    background-color: var(--bg-page);
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
    min-width: 200px;
}

td:last-child {
    text-align: center;
}

td:last-child form {
    display: flex;
    gap: var(--gap-table-action);
    justify-content: center;
}

td:last-child button {
    padding: 6px 12px;
    border-radius: var(--radius-action);
    cursor: pointer;
    font-size: var(--font-size-caption);
    font-weight: var(--font-weight-semibold);
}

.phan-trang {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 25px;
}

.phan-trang a {
    padding: 8px 14px;
    border: 1px solid var(--border);
    text-decoration: none;
    color: var(--text-body);
    border-radius: var(--radius-button);
    font-size: var(--font-size-caption);
    transition: all 0.15s ease;
}

.phan-trang a:hover {
    background-color: var(--primary-light);
    border-color: var(--border-blue);
    color: var(--primary);
}

.phan-trang .dang-chon {
    background-color: var(--primary);
    color: var(--white);
    border-color: var(--primary);
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
require_once __DIR__ . '/../../layout/sidebar.php';
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

<?php if (!empty($thong_bao)) { ?>
    <div class="thong-bao <?php echo htmlspecialchars($loai_thong_bao); ?>">
        <?php echo htmlspecialchars($thong_bao); ?>
    </div>
<?php } ?>

<div class="khung-form">

<div class="tieu-de-form">
    QUẢN LÝ ĐẦU SÁCH
</div>

<div class="noi-dung-form">

<form method="POST" action="index.php?controller=dausach">

<div class="truong">
<label class="nhan">Mã sách</label>
<input class="o-nhap" type="text" name="ma_sach" value="<?php echo htmlspecialchars($ma_sach ?? ''); ?>">
<?php if (isset($loi["ma_sach"])) { ?>
    <span class="loi"><?php echo htmlspecialchars($loi["ma_sach"]); ?></span>
<?php } ?>
</div>

<div class="truong">
<label class="nhan">Tên sách</label>
<input class="o-nhap" type="text" name="ten_sach" value="<?php echo htmlspecialchars($ten_sach ?? ''); ?>">
<?php if (isset($loi["ten_sach"])) { ?>
    <span class="loi"><?php echo htmlspecialchars($loi["ten_sach"]); ?></span>
<?php } ?>
</div>

<div class="truong">
<label class="nhan">Mã tác giả</label>
<input class="o-nhap" type="text" name="ma_tac_gia" value="<?php echo htmlspecialchars($ma_tac_gia ?? ''); ?>">
<?php if (isset($loi["ma_tac_gia"])) { ?>
    <span class="loi"><?php echo htmlspecialchars($loi["ma_tac_gia"]); ?></span>
<?php } ?>
</div>

<div class="truong">
<label class="nhan">Tác giả</label>
<input class="o-nhap" type="text" name="tac_gia" value="<?php echo htmlspecialchars($tac_gia ?? ''); ?>">
<?php if (isset($loi["tac_gia"])) { ?>
    <span class="loi"><?php echo htmlspecialchars($loi["tac_gia"]); ?></span>
<?php } ?>
</div>

<div class="truong">
<label class="nhan">Danh mục</label>
<select class="o-nhap" name="danh_muc">
<option value="">-- Chọn danh mục --</option>
<?php foreach ($danh_sach_danh_muc as $danh_muc_item) { ?>
<option
    value="<?php echo htmlspecialchars($danh_muc_item["ten_danh_muc"]); ?>"
    <?php if (($danh_muc ?? '') == $danh_muc_item["ten_danh_muc"]) echo "selected"; ?>
>
    <?php echo htmlspecialchars($danh_muc_item["ten_danh_muc"]); ?>
</option>
<?php } ?>
</select>
<?php if (isset($loi["danh_muc"])) { ?>
    <span class="loi"><?php echo htmlspecialchars($loi["danh_muc"]); ?></span>
<?php } ?>
</div>

<div class="truong">
<label class="nhan">Nhà xuất bản</label>
<input class="o-nhap" type="text" name="nha_xuat_ban" value="<?php echo htmlspecialchars($nha_xuat_ban ?? ''); ?>">
<?php if (isset($loi["nha_xuat_ban"])) { ?>
    <span class="loi"><?php echo htmlspecialchars($loi["nha_xuat_ban"]); ?></span>
<?php } ?>
</div>

<div class="truong">
<label class="nhan">Năm xuất bản</label>
<input class="o-nhap" type="number" name="nam_xuat_ban" value="<?php echo htmlspecialchars($nam_xuat_ban ?? ''); ?>">
<?php if (isset($loi["nam_xuat_ban"])) { ?>
    <span class="loi"><?php echo htmlspecialchars($loi["nam_xuat_ban"]); ?></span>
<?php } ?>
</div>

<div class="truong">
<label class="nhan">ISBN</label>
<input class="o-nhap" type="text" name="isbn" value="<?php echo htmlspecialchars($isbn ?? ''); ?>">
<?php if (isset($loi["isbn"])) { ?>
    <span class="loi"><?php echo htmlspecialchars($loi["isbn"]); ?></span>
<?php } ?>
</div>

<div class="truong">
<label class="nhan">Giá sách (VNĐ)</label>
<input class="o-nhap" type="number" name="gia_sach" value="<?php echo htmlspecialchars($gia_sach ?? ''); ?>" min="1">
<?php if (isset($loi["gia_sach"])) { ?>
    <span class="loi"><?php echo htmlspecialchars($loi["gia_sach"]); ?></span>
<?php } ?>
</div>

<div class="truong">
<label class="nhan">Mô tả</label>
<textarea class="o-nhap" name="mo_ta"><?php echo htmlspecialchars($mo_ta ?? ''); ?></textarea>
<?php if (isset($loi["mo_ta"])) { ?>
    <span class="loi"><?php echo htmlspecialchars($loi["mo_ta"]); ?></span>
<?php } ?>
</div>

<div class="khu-vuc-nut">
<?php if (!empty($vi_tri_sua)) { ?>
<a href="index.php?controller=dausach" class="nut">Hủy</a>
<input type="hidden" name="id_sua" value="<?php echo $vi_tri_sua; ?>">
<button type="submit" class="nut nut-them" name="cap_nhat_sach">Cập nhật sách</button>
<?php } else { ?>
<button type="reset" class="nut">Hủy</button>
<button type="submit" class="nut nut-them" name="them_sach">Thêm sách</button>
<?php } ?>
</div>

</form>
</div>
</div>

<div class="danh-sach">
<h2>Danh sách đầu sách</h2>

<!-- TÌM KIẾM NÂNG CAO -->
<form method="GET" action="index.php" class="tim-kiem">
<input type="hidden" name="controller" value="dausach">
<input type="text" name="tu_khoa" placeholder="Mã sách, tên sách, tác giả, NXB..." value="<?php echo htmlspecialchars($tu_khoa ?? ''); ?>">
<input type="text" name="loc_tac_gia" placeholder="Tên tác giả" value="<?php echo htmlspecialchars($loc_tac_gia ?? ''); ?>">

<select name="loc_danh_muc">
<option value="">Tất cả danh mục</option>
<?php foreach ($danh_sach_danh_muc as $item) { ?>
<option
    value="<?php echo $item["category_id"]; ?>"
    <?php if (($loc_danh_muc ?? '') == $item["category_id"]) echo "selected"; ?>
>
    <?php echo htmlspecialchars($item["ten_danh_muc"]); ?>
</option>
<?php } ?>
</select>

<input type="number" name="loc_nam" placeholder="Năm xuất bản" value="<?php echo htmlspecialchars($loc_nam ?? ''); ?>">

<button type="submit" class="nut nut-them">Tìm kiếm</button>
<a href="index.php?controller=dausach" class="nut">Làm mới</a>
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
<th>Trạng thái</th>
<th>Thao tác</th>
</tr>

<?php
if (empty($danh_sach_sach)) {
    echo "<tr><td colspan='13' style='text-align:center;'>Không tìm thấy sách.</td></tr>";
} else {
    foreach ($danh_sach_sach as $vi_tri => $sach) {
        $stt = $offset + $vi_tri + 1;
        echo "<tr>";
        echo "<td>" . $stt . "</td>";
        echo "<td>" . htmlspecialchars($sach["ma_sach"]) . "</td>";
        echo "<td>" . htmlspecialchars($sach["ten_sach"]) . "</td>";
        echo "<td>" . htmlspecialchars($sach["ma_tac_gia"]) . "</td>";
        echo "<td>" . htmlspecialchars($sach["tac_gia"]) . "</td>";
        echo "<td>" . htmlspecialchars($sach["danh_muc"]) . "</td>";
        echo "<td>" . htmlspecialchars($sach["nha_xuat_ban"]) . "</td>";
        echo "<td>" . htmlspecialchars($sach["nam_xuat_ban"]) . "</td>";
        echo "<td>" . htmlspecialchars($sach["isbn"]) . "</td>";
        echo "<td>" . number_format((float)$sach["gia_sach"], 0, ",", ".") . " VNĐ</td>";
        echo "<td>" . htmlspecialchars($sach["mo_ta"]) . "</td>";
        echo "<td>" . htmlspecialchars($sach["trang_thai"] ?? 'Hoạt động') . "</td>";
        echo "<td>";
        echo "<form method='POST' action='index.php?controller=dausach'>";
        echo "<button type='submit' name='sua_sach' value='" . $sach["id"] . "'>Sửa</button>";
        echo "<button type='submit' name='xoa_sach' value='" . $sach["id"] . "' onclick=\"return confirm('Bạn có chắc chắn muốn xóa sách này không?');\">Xóa</button>";
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
        "controller" => "dausach",
        "tu_khoa" => $tu_khoa,
        "loc_tac_gia" => $loc_tac_gia,
        "loc_danh_muc" => $loc_danh_muc,
        "loc_nam" => $loc_nam,
        "trang" => $i
    ];
    $url = "index.php?" . http_build_query($tham_so);
?>
<a href="<?php echo htmlspecialchars($url); ?>" class="<?php echo ($i == $trang) ? "dang-chon" : ""; ?>">
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
