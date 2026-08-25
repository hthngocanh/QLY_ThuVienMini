<?php

require_once "../database/config/database.php";

$bookId = "";
$maBanSao = "";
$viTri = "";
$trangThai = "Có sẵn";
$editId = "";

$loiBookId = "";
$loiMaBanSao = "";
$loiViTri = "";
$loiTrangThai = "";

$thongBao = "";
$thongBaoLoi = "";


/* =========================================
   THÔNG BÁO SAU KHI CHUYỂN TRANG
   ========================================= */

if (isset($_GET["success"])) {

    if ($_GET["success"] === "add") {
        $thongBao = "Thêm bản sao sách thành công!";
    }

    if ($_GET["success"] === "update") {
        $thongBao = "Cập nhật bản sao sách thành công!";
    }

    if ($_GET["success"] === "delete") {
        $thongBao = "Xóa bản sao sách thành công!";
    }
}

if (isset($_GET["error"])) {

    if ($_GET["error"] === "borrowed") {
        $thongBaoLoi =
            "Không thể xóa vì bản sao đã có lịch sử mượn.";
    }

    if ($_GET["error"] === "delete") {
        $thongBaoLoi =
            "Không thể xóa bản sao sách.";
    }
}


/* =========================================
   LẤY DANH SÁCH ĐẦU SÁCH
   ========================================= */

try {

    $stmtBooks = $pdo->query("
        SELECT id, ma_sach, ten_sach
        FROM books
        ORDER BY ten_sach ASC
    ");

    $danhSachDauSach =
        $stmtBooks->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    $danhSachDauSach = [];
}


/* =========================================
   XỬ LÝ XÓA
   ========================================= */

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && ($_POST["action"] ?? "") === "delete"
) {

    $deleteId = trim($_POST["delete_id"] ?? "");

    if (ctype_digit($deleteId)) {

        try {

            // Kiểm tra bản sao đã có phiếu mượn chưa
            $stmtCheck = $pdo->prepare("
                SELECT COUNT(*)
                FROM borrow_slips
                WHERE ID_BanSao = :id
            ");

            $stmtCheck->execute([
                ":id" => (int)$deleteId
            ]);

            $soPhieuMuon = (int)$stmtCheck->fetchColumn();


            if ($soPhieuMuon > 0) {

                header(
                    "Location: bansao.php?error=borrowed"
                );

                exit;
            }


            $stmtDelete = $pdo->prepare("
                DELETE FROM book_copies
                WHERE id = :id
            ");

            $stmtDelete->execute([
                ":id" => (int)$deleteId
            ]);


            header(
                "Location: bansao.php?success=delete"
            );

            exit;


        } catch (PDOException $e) {

            header(
                "Location: bansao.php?error=delete"
            );

            exit;
        }
    }
}


/* =========================================
   LẤY DỮ LIỆU BẢN SAO CẦN SỬA
   ========================================= */

if (
    $_SERVER["REQUEST_METHOD"] === "GET"
    && isset($_GET["edit"])
) {

    $editId = trim($_GET["edit"]);

    if (ctype_digit($editId)) {

        try {

            $stmtEdit = $pdo->prepare("
                SELECT
                    id,
                    book_id,
                    ma_ban_sao,
                    vi_tri,
                    trang_thai
                FROM book_copies
                WHERE id = :id
            ");

            $stmtEdit->execute([
                ":id" => (int)$editId
            ]);

            $banSaoSua =
                $stmtEdit->fetch(PDO::FETCH_ASSOC);


            if ($banSaoSua) {

                $bookId =
                    $banSaoSua["book_id"];

                $maBanSao =
                    $banSaoSua["ma_ban_sao"];

                $viTri =
                    $banSaoSua["vi_tri"];

                $trangThai =
                    $banSaoSua["trang_thai"];

            } else {

                $editId = "";

                $thongBaoLoi =
                    "Không tìm thấy bản sao cần sửa.";
            }

        } catch (PDOException $e) {

            $editId = "";

            $thongBaoLoi =
                "Không thể lấy thông tin bản sao.";
        }
    }
}


/* =========================================
   XỬ LÝ THÊM / CẬP NHẬT
   ========================================= */

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && in_array(
        $_POST["action"] ?? "",
        ["add", "update"],
        true
    )
) {

    $action =
        $_POST["action"];

    $editId =
        trim($_POST["edit_id"] ?? "");

    $bookId =
        trim($_POST["book_id"] ?? "");

    $maBanSao =
        trim($_POST["ma_ban_sao"] ?? "");

    $viTri =
        trim($_POST["vi_tri"] ?? "");

    $trangThai =
        trim($_POST["trang_thai"] ?? "");


    /* ===== KIỂM TRA ĐẦU SÁCH ===== */

    if ($bookId === "") {

        $loiBookId =
            "Vui lòng chọn đầu sách.";

    } elseif (!ctype_digit($bookId)) {

        $loiBookId =
            "Đầu sách không hợp lệ.";
    }


    /* ===== KIỂM TRA MÃ BẢN SAO ===== */

    if ($maBanSao === "") {

        $loiMaBanSao =
            "Vui lòng nhập mã bản sao.";

    } elseif (
        !preg_match(
            '/^[A-Za-z0-9_-]+$/',
            $maBanSao
        )
    ) {

        $loiMaBanSao =
            "Mã bản sao chỉ được chứa chữ, số, dấu - hoặc _.";
    }


    /* ===== KIỂM TRA VỊ TRÍ ===== */

    if ($viTri === "") {

        $loiViTri =
            "Vui lòng nhập vị trí bản sao.";
    }


    /* ===== KIỂM TRA TRẠNG THÁI ===== */

    $trangThaiHopLe = [
        "Có sẵn",
        "Đang mượn",
        "Hỏng"
    ];

    if (
        !in_array(
            $trangThai,
            $trangThaiHopLe,
            true
        )
    ) {

        $loiTrangThai =
            "Trạng thái không hợp lệ.";
    }


    /* =====================================
       KHÔNG CÓ LỖI
       ===================================== */

    if (
        $loiBookId === ""
        && $loiMaBanSao === ""
        && $loiViTri === ""
        && $loiTrangThai === ""
    ) {

        try {

            /* ===== THÊM ===== */

            if ($action === "add") {

                $stmt = $pdo->prepare("
                    INSERT INTO book_copies
                        (
                            book_id,
                            ma_ban_sao,
                            vi_tri,
                            trang_thai
                        )
                    VALUES
                        (
                            :book_id,
                            :ma_ban_sao,
                            :vi_tri,
                            :trang_thai
                        )
                ");

                $stmt->execute([
                    ":book_id" =>
                        (int)$bookId,

                    ":ma_ban_sao" =>
                        $maBanSao,

                    ":vi_tri" =>
                        $viTri,

                    ":trang_thai" =>
                        $trangThai
                ]);


                header(
                    "Location: bansao.php?success=add"
                );

                exit;
            }


            /* ===== CẬP NHẬT ===== */

            if ($action === "update") {

                if (!ctype_digit($editId)) {

                    throw new Exception(
                        "ID bản sao không hợp lệ."
                    );
                }


                $stmt = $pdo->prepare("
                    UPDATE book_copies

                    SET
                        book_id = :book_id,
                        ma_ban_sao = :ma_ban_sao,
                        vi_tri = :vi_tri,
                        trang_thai = :trang_thai

                    WHERE id = :id
                ");

                $stmt->execute([
                    ":book_id" =>
                        (int)$bookId,

                    ":ma_ban_sao" =>
                        $maBanSao,

                    ":vi_tri" =>
                        $viTri,

                    ":trang_thai" =>
                        $trangThai,

                    ":id" =>
                        (int)$editId
                ]);


                header(
                    "Location: bansao.php?success=update"
                );

                exit;
            }


        } catch (PDOException $e) {

            if (
                isset($e->errorInfo[1])
                && $e->errorInfo[1] == 1062
            ) {

                $loiMaBanSao =
                    "Mã bản sao đã tồn tại.";

            } else {

                $thongBaoLoi =
                    "Không thể lưu bản sao sách.";
            }

        } catch (Exception $e) {

            $thongBaoLoi =
                $e->getMessage();
        }
    }
}


/* =========================================
   LẤY DANH SÁCH BẢN SAO
   ========================================= */

try {

    $stmtDanhSach = $pdo->query("
        SELECT
            bc.id,
            bc.book_id,
            bc.ma_ban_sao,
            bc.vi_tri,
            bc.trang_thai,
            b.ma_sach,
            b.ten_sach

        FROM book_copies bc

        INNER JOIN books b
            ON bc.book_id = b.id

        ORDER BY bc.id DESC
    ");

    $danhSachBanSao =
        $stmtDanhSach->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    $danhSachBanSao = [];
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


/* =========================
   MENU
   ========================= */

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

.navbar a:hover,
.navbar a.active {
    background-color: #2563eb;
}


/* =========================
   FORM
   ========================= */

.form-ban-sao {
    width: 550px;
    margin: 0 auto;
    padding: 25px;
    background-color: white;
    border-radius: 10px;

    box-shadow:
        0 3px 10px
        rgba(0,0,0,0.15);
}

label {
    display: block;
    margin-top: 12px;
    margin-bottom: 6px;
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
    background-color: #ffeaea;
}

.loi-truong {
    color: red;
    font-size: 13px;
    font-weight: bold;
}


/* =========================
   THÔNG BÁO
   ========================= */

.thanh-cong,
.thanh-loi {

    width: 550px;
    margin: 20px auto;
    padding: 12px;

    text-align: center;
    border-radius: 6px;

    font-weight: bold;

    transition:
        opacity 0.5s ease,
        transform 0.5s ease;
}

.thanh-cong {

    color: green;
    background-color: #eafbea;
    border: 1px solid green;
}

.thanh-loi {

    color: #b91c1c;
    background-color: #fee2e2;
    border: 1px solid #dc2626;
}

.thong-bao-an {

    opacity: 0;
    transform: translateY(-10px);
}


/* =========================
   BUTTON
   ========================= */

.btn-chinh {

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

.btn-chinh:hover {
    background-color: #1e659d;
}

.huy-sua {

    display: block;
    width: fit-content;

    margin: 12px auto 0;

    text-decoration: none;
    color: #555;
}


/* =========================
   DANH SÁCH
   ========================= */

.ket-qua {

    width: 95%;
    margin: 35px auto;

    background-color: white;

    padding: 20px;

    border-radius: 10px;

    box-shadow:
        0 3px 10px
        rgba(0,0,0,0.12);
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


/* =========================
   TRẠNG THÁI
   ========================= */

.co-san {

    color: green;
    font-weight: bold;
}

.dang-muon {

    color: #d89400;
    font-weight: bold;
}

.hong {

    color: red;
    font-weight: bold;
}


/* =========================
   THAO TÁC
   ========================= */

.thao-tac {

    display: flex;

    justify-content: center;

    gap: 8px;
}

.btn-sua {

    display: inline-block;

    padding: 7px 12px;

    background-color: #f59e0b;

    color: white;

    text-decoration: none;

    border-radius: 5px;

    font-size: 13px;
}

.btn-xoa {

    border: none;

    padding: 7px 12px;

    background-color: #dc2626;

    color: white;

    border-radius: 5px;

    cursor: pointer;

    font-size: 13px;
}

.form-xoa {

    margin: 0;
    padding: 0;

    width: auto;

    background: none;

    box-shadow: none;
}

</style>

</head>


<body>


<nav class="navbar">

    <a href="../index.php">
        🏠 Trang chủ
    </a>

    <a href="../nguoiDung/User.php">
        👤 Người dùng
    </a>

    <a
        href="bansao.php"
        class="active"
    >
        📖 Bản sao sách
    </a>

    <a href="../phieuMuon/phieumuon.php">
        📋 Phiếu mượn
    </a>

</nav>


<h1>
    QUẢN LÝ BẢN SAO SÁCH
</h1>


<?php if ($thongBao !== "") { ?>

    <div class="thanh-cong thong-bao">

        <?php
        echo htmlspecialchars(
            $thongBao
        );
        ?>

    </div>

<?php } ?>


<?php if ($thongBaoLoi !== "") { ?>

    <div class="thanh-loi thong-bao">

        <?php
        echo htmlspecialchars(
            $thongBaoLoi
        );
        ?>

    </div>

<?php } ?>


<form
    method="post"
    class="form-ban-sao"
>

    <input
        type="hidden"
        name="action"
        value="<?php
            echo $editId !== ""
                ? "update"
                : "add";
        ?>"
    >

    <input
        type="hidden"
        name="edit_id"
        value="<?php
            echo htmlspecialchars(
                $editId
            );
        ?>"
    >


    <label for="book_id">
        Đầu sách:
    </label>

    <select
        id="book_id"
        name="book_id"
        class="<?php
            echo $loiBookId !== ""
                ? "input-loi"
                : "";
        ?>"
    >

        <option value="">
            -- Chọn đầu sách --
        </option>


        <?php
        foreach (
            $danhSachDauSach
            as $dauSach
        ) {
        ?>

            <option
                value="<?php
                    echo $dauSach["id"];
                ?>"

                <?php

                if (
                    (string)$bookId
                    ===
                    (string)$dauSach["id"]
                ) {

                    echo "selected";
                }

                ?>
            >

                <?php

                echo htmlspecialchars(
                    $dauSach["ma_sach"]
                    . " - "
                    . $dauSach["ten_sach"]
                );

                ?>

            </option>

        <?php } ?>

    </select>


    <?php
    if ($loiBookId !== "") {
    ?>

        <p class="loi-truong">

            <?php
            echo htmlspecialchars(
                $loiBookId
            );
            ?>

        </p>

    <?php } ?>


    <label for="ma_ban_sao">
        Mã bản sao:
    </label>

    <input
        type="text"
        id="ma_ban_sao"
        name="ma_ban_sao"

        placeholder="Ví dụ: BS005"

        value="<?php
            echo htmlspecialchars(
                $maBanSao
            );
        ?>"

        class="<?php
            echo $loiMaBanSao !== ""
                ? "input-loi"
                : "";
        ?>"
    >


    <?php
    if ($loiMaBanSao !== "") {
    ?>

        <p class="loi-truong">

            <?php
            echo htmlspecialchars(
                $loiMaBanSao
            );
            ?>

        </p>

    <?php } ?>


    <label for="vi_tri">
        Vị trí:
    </label>

    <input
        type="text"
        id="vi_tri"
        name="vi_tri"

        placeholder="Ví dụ: Kệ A1"

        value="<?php
            echo htmlspecialchars(
                $viTri
            );
        ?>"

        class="<?php
            echo $loiViTri !== ""
                ? "input-loi"
                : "";
        ?>"
    >


    <?php
    if ($loiViTri !== "") {
    ?>

        <p class="loi-truong">

            <?php
            echo htmlspecialchars(
                $loiViTri
            );
            ?>

        </p>

    <?php } ?>


    <label for="trang_thai">
        Trạng thái:
    </label>

    <select
        id="trang_thai"
        name="trang_thai"
    >

        <option
            value="Có sẵn"

            <?php
            if (
                $trangThai
                === "Có sẵn"
            ) {

                echo "selected";
            }
            ?>
        >
            Có sẵn
        </option>


        <option
            value="Đang mượn"

            <?php
            if (
                $trangThai
                === "Đang mượn"
            ) {

                echo "selected";
            }
            ?>
        >
            Đang mượn
        </option>


        <option
            value="Hỏng"

            <?php
            if (
                $trangThai
                === "Hỏng"
            ) {

                echo "selected";
            }
            ?>
        >
            Hỏng
        </option>

    </select>


    <button
        type="submit"
        class="btn-chinh"
    >

        <?php

        echo $editId !== ""
            ? "Cập nhật bản sao"
            : "Thêm bản sao";

        ?>

    </button>


    <?php
    if ($editId !== "") {
    ?>

        <a
            href="bansao.php"
            class="huy-sua"
        >
            Hủy sửa
        </a>

    <?php } ?>

</form>



<div class="ket-qua">

<h2>
    DANH SÁCH BẢN SAO SÁCH
</h2>


<table>

<tr>

    <th>STT</th>
    <th>ID</th>
    <th>Mã bản sao</th>
    <th>Mã sách</th>
    <th>Tên sách</th>
    <th>Vị trí</th>
    <th>Trạng thái</th>
    <th>Thao tác</th>

</tr>


<?php

$stt = 1;

foreach (
    $danhSachBanSao
    as $banSao
) {


    if (
        $banSao["trang_thai"]
        === "Có sẵn"
    ) {

        $classTrangThai =
            "co-san";

    } elseif (
        $banSao["trang_thai"]
        === "Đang mượn"
    ) {

        $classTrangThai =
            "dang-muon";

    } else {

        $classTrangThai =
            "hong";
    }

?>


<tr>

<td>
    <?php echo $stt; ?>
</td>


<td>

    <?php
    echo htmlspecialchars(
        $banSao["id"]
    );
    ?>

</td>


<td>

    <?php
    echo htmlspecialchars(
        $banSao["ma_ban_sao"]
    );
    ?>

</td>


<td>

    <?php
    echo htmlspecialchars(
        $banSao["ma_sach"]
    );
    ?>

</td>


<td>

    <?php
    echo htmlspecialchars(
        $banSao["ten_sach"]
    );
    ?>

</td>


<td>

    <?php
    echo htmlspecialchars(
        $banSao["vi_tri"]
    );
    ?>

</td>


<td
    class="<?php
        echo $classTrangThai;
    ?>"
>

    <?php
    echo htmlspecialchars(
        $banSao["trang_thai"]
    );
    ?>

</td>


<td>

<div class="thao-tac">


<a
    class="btn-sua"

    href="bansao.php?edit=<?php
        echo $banSao["id"];
    ?>"
>
    Sửa
</a>


<form
    method="post"

    class="form-xoa"

    onsubmit="
        return confirm(
            'Bạn có chắc muốn xóa bản sao này không?'
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
    name="delete_id"

    value="<?php
        echo $banSao["id"];
    ?>"
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


<?php

$stt++;

}

?>

</table>

</div>


<script>

/* =========================================
   TỰ ẨN THÔNG BÁO SAU 3 GIÂY
   ========================================= */

setTimeout(function () {

    const thongBao =
        document.querySelectorAll(
            ".thong-bao"
        );


    thongBao.forEach(
        function (item) {

            item.classList.add(
                "thong-bao-an"
            );


            setTimeout(
                function () {

                    item.remove();

                },
                500
            );

        }
    );

}, 3000);

</script>


</body>

</html>