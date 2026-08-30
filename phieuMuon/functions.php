<?php

/*
|--------------------------------------------------------------------------
| KẾT NỐI DATABASE
|--------------------------------------------------------------------------
*/

$host = "localhost";
$dbname = "qly_thuvienmini";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}


/*
|--------------------------------------------------------------------------
| HÀM HỖ TRỢ
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}


function chuanHoaInput($value)
{
    $value = trim((string)$value);

    $value = preg_replace(
        '/[ \t]+/u',
        ' ',
        $value
    );

    return $value ?? '';
}


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


/*
|--------------------------------------------------------------------------
| READ - DANH SÁCH PHIẾU MƯỢN
|--------------------------------------------------------------------------
*/

function getAllPhieuMuon($pdo)
{
    $sql = "
        SELECT
            bs.ID_PhieuMuon,
            bs.ID_NguoiDung,
            bs.ID_BanSao,

            nd.ma_nguoi_dung,
            nd.ho_ten,

            bc.ma_ban_sao,

            b.ten_sach,

            bs.NgayMuon,
            bs.NgayTra,
            bs.TrangThai

        FROM borrow_slips bs

        INNER JOIN nguoi_dung nd
            ON bs.ID_NguoiDung = nd.id

        INNER JOIN book_copies bc
            ON bs.ID_BanSao = bc.id

        INNER JOIN books b
            ON bc.book_id = b.id

        ORDER BY bs.ID_PhieuMuon DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*
|--------------------------------------------------------------------------
| READ - LẤY 1 PHIẾU MƯỢN
|--------------------------------------------------------------------------
*/

function getPhieuMuonById($pdo, $id)
{
    $sql = "
        SELECT
            bs.ID_PhieuMuon,
            bs.ID_NguoiDung,
            bs.ID_BanSao,

            nd.ma_nguoi_dung,
            bc.ma_ban_sao,

            bs.NgayMuon,
            bs.NgayTra,
            bs.TrangThai

        FROM borrow_slips bs

        INNER JOIN nguoi_dung nd
            ON bs.ID_NguoiDung = nd.id

        INNER JOIN book_copies bc
            ON bs.ID_BanSao = bc.id

        WHERE bs.ID_PhieuMuon = ?
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


/*
|--------------------------------------------------------------------------
| TÌM ID NGƯỜI DÙNG THEO MÃ
|--------------------------------------------------------------------------
*/

function getIdNguoiDungTheoMa($pdo, $maNguoiDung)
{
    $sql = "
        SELECT id
        FROM nguoi_dung
        WHERE ma_nguoi_dung = ?
          AND trang_thai = 'Hoạt động'
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$maNguoiDung]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? (int)$result['id'] : 0;
}


/*
|--------------------------------------------------------------------------
| TÌM ID BẢN SAO THEO MÃ
|--------------------------------------------------------------------------
*/

function getIdBanSaoTheoMa($pdo, $maBanSao)
{
    $sql = "
        SELECT id
        FROM book_copies
        WHERE ma_ban_sao = ?
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$maBanSao]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? (int)$result['id'] : 0;
}


/*
|--------------------------------------------------------------------------
| CREATE
|--------------------------------------------------------------------------
*/

function addPhieuMuon(
    $pdo,
    $idNguoiDung,
    $idBanSao,
    $ngayMuon,
    $ngayTra,
    $trangThai
) {

    $sql = "
        INSERT INTO borrow_slips
        (
            ID_NguoiDung,
            ID_BanSao,
            NgayMuon,
            NgayTra,
            TrangThai
        )
        VALUES (?, ?, ?, ?, ?)
    ";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        $idNguoiDung,
        $idBanSao,
        $ngayMuon,
        $ngayTra,
        $trangThai
    ]);
}


/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

function updatePhieuMuon(
    $pdo,
    $id,
    $idNguoiDung,
    $idBanSao,
    $ngayMuon,
    $ngayTra,
    $trangThai
) {

    $sql = "
        UPDATE borrow_slips
        SET
            ID_NguoiDung = ?,
            ID_BanSao = ?,
            NgayMuon = ?,
            NgayTra = ?,
            TrangThai = ?

        WHERE ID_PhieuMuon = ?
    ";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        $idNguoiDung,
        $idBanSao,
        $ngayMuon,
        $ngayTra,
        $trangThai,
        $id
    ]);
}


/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

function deletePhieuMuon($pdo, $id)
{
    $sql = "
        DELETE FROM borrow_slips
        WHERE ID_PhieuMuon = ?
    ";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([$id]);
}


/*
|--------------------------------------------------------------------------
| XỬ LÝ POST
|--------------------------------------------------------------------------
*/

function xuLyPhieuMuon($pdo)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

        return [
            'errors' => [],
            'thongBao' => '',
            'redirect' => null
        ];
    }


    $action = $_POST['action'] ?? '';

    $id = (int)($_POST['id'] ?? 0);


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    if ($action === 'delete') {

        if ($id <= 0) {

            return [
                'errors' => [
                    'id' => 'ID phiếu mượn không hợp lệ.'
                ],
                'thongBao' => '',
                'redirect' => null
            ];
        }

        deletePhieuMuon($pdo, $id);

        return [
            'errors' => [],
            'thongBao' => '',
            'redirect' => 'phieumuon.php?msg=deleted'
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | NHẬN DỮ LIỆU NHẬP TAY
    |--------------------------------------------------------------------------
    */

    $maNguoiDung = strtoupper(
        chuanHoaInput(
            $_POST['ma_nguoi_dung'] ?? ''
        )
    );

    $maBanSao = strtoupper(
        chuanHoaInput(
            $_POST['ma_ban_sao'] ?? ''
        )
    );

    $ngayMuon = trim(
        $_POST['ngay_muon'] ?? ''
    );

    $ngayTra = trim(
        $_POST['ngay_tra'] ?? ''
    );

    $trangThai = chuanHoaInput(
        $_POST['trang_thai'] ?? ''
    );


    $errors = [];


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA MÃ NGƯỜI DÙNG
    |--------------------------------------------------------------------------
    */

    if ($maNguoiDung === '') {

        $errors['ma_nguoi_dung'] =
            'Vui lòng nhập mã người dùng.';

    } elseif (
        !preg_match(
            '/^[A-Za-z0-9_-]+$/',
            $maNguoiDung
        )
    ) {

        $errors['ma_nguoi_dung'] =
            'Mã người dùng chỉ được chứa chữ, số, dấu gạch ngang hoặc gạch dưới.';

    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA MÃ BẢN SAO
    |--------------------------------------------------------------------------
    */

    if ($maBanSao === '') {

        $errors['ma_ban_sao'] =
            'Vui lòng nhập mã bản sao sách.';

    } elseif (
        !preg_match(
            '/^[A-Za-z0-9_-]+$/',
            $maBanSao
        )
    ) {

        $errors['ma_ban_sao'] =
            'Mã bản sao chỉ được chứa chữ, số, dấu gạch ngang hoặc gạch dưới.';

    }


    /*
    |--------------------------------------------------------------------------
    | TÌM ID TỪ MÃ NGƯỜI DÙNG
    |--------------------------------------------------------------------------
    */

    $idNguoiDung = 0;

    if (
        $maNguoiDung !== '' &&
        !isset($errors['ma_nguoi_dung'])
    ) {

        $idNguoiDung =
            getIdNguoiDungTheoMa(
                $pdo,
                $maNguoiDung
            );

        if ($idNguoiDung <= 0) {

            $errors['ma_nguoi_dung'] =
                'Mã người dùng không tồn tại hoặc tài khoản đã bị khóa.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TÌM ID TỪ MÃ BẢN SAO
    |--------------------------------------------------------------------------
    */

    $idBanSao = 0;

    if (
        $maBanSao !== '' &&
        !isset($errors['ma_ban_sao'])
    ) {

        $idBanSao =
            getIdBanSaoTheoMa(
                $pdo,
                $maBanSao
            );

        if ($idBanSao <= 0) {

            $errors['ma_ban_sao'] =
                'Mã bản sao sách không tồn tại.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA NGÀY MƯỢN
    |--------------------------------------------------------------------------
    */

    if ($ngayMuon === '') {

        $errors['ngay_muon'] =
            'Vui lòng chọn ngày mượn.';

    } elseif (!laNgayHopLe($ngayMuon)) {

        $errors['ngay_muon'] =
            'Ngày mượn không hợp lệ.';

    } elseif ($ngayMuon > date('Y-m-d')) {

        $errors['ngay_muon'] =
            'Ngày mượn không được lớn hơn ngày hiện tại.';
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA NGÀY TRẢ
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
    | KIỂM TRA TRẠNG THÁI
    |--------------------------------------------------------------------------
    */

    $trangThaiHopLe = [
        'Chờ duyệt',
        'Đang mượn',
        'Quá hạn',
        'Đã trả'
    ];

    if (
        !in_array(
            $trangThai,
            $trangThaiHopLe,
            true
        )
    ) {

        $errors['trang_thai'] =
            'Trạng thái không hợp lệ.';
    }


    /*
    |--------------------------------------------------------------------------
    | NẾU CÓ LỖI
    |--------------------------------------------------------------------------
    */

    if (!empty($errors)) {

        return [
            'errors' => $errors,
            'thongBao' => '',
            'redirect' => null
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | NGÀY TRẢ TRỐNG → NULL
    |--------------------------------------------------------------------------
    */

    $ngayTra =
        ($ngayTra === '')
        ? null
        : $ngayTra;


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    if ($action === 'add') {

        addPhieuMuon(
            $pdo,
            $idNguoiDung,
            $idBanSao,
            $ngayMuon,
            $ngayTra,
            $trangThai
        );

        return [
            'errors' => [],
            'thongBao' => '',
            'redirect' => 'phieumuon.php?msg=added'
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    if ($action === 'edit') {

        if ($id <= 0) {

            return [
                'errors' => [
                    'id' =>
                        'ID phiếu mượn không hợp lệ.'
                ],
                'thongBao' => '',
                'redirect' => null
            ];
        }

        updatePhieuMuon(
            $pdo,
            $id,
            $idNguoiDung,
            $idBanSao,
            $ngayMuon,
            $ngayTra,
            $trangThai
        );

        return [
            'errors' => [],
            'thongBao' => '',
            'redirect' => 'phieumuon.php?msg=updated'
        ];
    }


    return [
        'errors' => [],
        'thongBao' => '',
        'redirect' => 'phieumuon.php'
    ];
}

?>