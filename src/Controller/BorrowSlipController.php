<?php
// src/Controller/BorrowSlipController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/BorrowSlipModel.php';

class BorrowSlipController extends BaseController
{
    private $borrowSlipModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->borrowSlipModel = new BorrowSlipModel();
    }

    private function chuanHoaInput($value)
    {
        $value = trim((string)$value);
        $value = preg_replace('/[ \t]+/u', ' ', $value);

        return $value ?? '';
    }

    private function laNgayHopLe($date)
    {
        if (empty($date)) {
            return false;
        }

        $dateObject = DateTime::createFromFormat('Y-m-d', $date);

        return $dateObject &&
               $dateObject->format('Y-m-d') === $date;
    }

    public function index()
    {
        $this->requireLogin();

        $vaiTro = $_SESSION['user']['vai_tro'] ?? '';

        // =========================
        // ĐỘC GIẢ
        // =========================
        if ($vaiTro === 'Độc giả') {
            $this->xuLyDocGia();
            return;
        }

        // =========================
        // THỦ THƯ + QUẢN TRỊ VIÊN
        // =========================
        $this->requireRole([
            'Thủ thư',
            'Quản trị viên'
        ]);

        $this->xuLyQuanLyPhieuMuon();
    }

    // =========================================================
    // ĐỘC GIẢ
    // =========================================================
    private function xuLyDocGia()
    {
        $idNguoiDung = (int)($_SESSION['user']['id'] ?? 0);

        if ($idNguoiDung <= 0) {
            $this->redirect('index.php');
            return;
        }

        $errors = [];
        $thongBao = '';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

            $action = $_POST['action'] ?? '';

            // -------------------------
            // Đăng ký mượn
            // -------------------------
            if ($action === 'dang_ky_muon') {

                $maBanSao = strtoupper(
                    $this->chuanHoaInput(
                        $_POST['ma_ban_sao'] ?? ''
                    )
                );

                if ($maBanSao === '') {

                    $errors['ma_ban_sao'] =
                        'Vui lòng chọn hoặc nhập mã bản sao sách.';

                } elseif (
                    !preg_match(
                        '/^[A-Za-z0-9_-]+$/',
                        $maBanSao
                    )
                ) {

                    $errors['ma_ban_sao'] =
                        'Mã bản sao chỉ được chứa chữ, số, dấu gạch ngang hoặc dấu gạch dưới.';
                }

                $idBanSao = 0;

                if (
                    $maBanSao !== '' &&
                    !isset($errors['ma_ban_sao'])
                ) {

                    $idBanSao =
                        $this->borrowSlipModel
                            ->getIdBanSaoTheoMa($maBanSao);

                    if ($idBanSao <= 0) {

                        $errors['ma_ban_sao'] =
                            'Mã bản sao sách không tồn tại hoặc đã có người mượn.';
                    }
                }

                $ngayMuon = date('Y-m-d');
                $ngayTra = null;
                $trangThai = 'Chờ duyệt';

                if (empty($errors)) {

                    $ketQua =
                        $this->borrowSlipModel->addPhieuMuon(
                            $idNguoiDung,
                            $idBanSao,
                            $ngayMuon,
                            $ngayTra,
                            $trangThai
                        );

                    if ($ketQua) {

                        $this->redirect(
                            'index.php?controller=phieumuon&msg=registered'
                        );
                        return;

                    } else {

                        $errors['general'] =
                            'Không thể đăng ký mượn. Vui lòng thử lại.';
                    }
                }
            }
        }

        if (
            isset($_GET['msg']) &&
            $_GET['msg'] === 'registered'
        ) {

            $thongBao =
                'Đăng ký mượn thành công! Vui lòng chờ thủ thư duyệt.';
        }

        $danhSachSach =
            $this->borrowSlipModel
                ->getDanhSachSachDeMuon();

        $danhSachPhieuMuon =
            $this->borrowSlipModel
                ->getPhieuMuonCuaNguoiDung($idNguoiDung);

        $this->renderView(
            'phieumuon/quanLyDocGia.php',
            [
                'danhSachSach' =>
                    $danhSachSach,

                'danhSachPhieuMuon' =>
                    $danhSachPhieuMuon,

                'errors' =>
                    $errors,

                'thongBao' =>
                    $thongBao,

                'activePage' =>
                    'phieumuon',

                'activeAction' =>
                    'index'
            ]
        );
    }

    // =========================================================
    // THỦ THƯ + QUẢN TRỊ VIÊN
    // =========================================================
    private function xuLyQuanLyPhieuMuon()
    {
        $errors = [];
        $thongBao = '';

        $vaiTroHienTai =
            $_SESSION['user']['vai_tro'] ?? '';

        $laQuanTriVien =
            ($vaiTroHienTai === 'Quản trị viên');

        $laThuThu =
            ($vaiTroHienTai === 'Thủ thư');

        // Cả Thủ thư và Quản trị viên đều được quản lý phiếu mượn
        $duocQuanLyPhieuMuon =
            ($laQuanTriVien || $laThuThu);

        // -----------------------------------------------------
        // THÔNG BÁO
        // -----------------------------------------------------
        if (isset($_GET['msg'])) {

            switch ($_GET['msg']) {

                case 'added':
                    $thongBao =
                        'Thêm phiếu mượn thành công!';
                    break;

                case 'updated':
                    $thongBao =
                        'Cập nhật phiếu mượn thành công!';
                    break;

                case 'deleted':
                    $thongBao =
                        'Xóa phiếu mượn thành công!';
                    break;
            }
        }

        // -----------------------------------------------------
        // XỬ LÝ POST
        // -----------------------------------------------------
        if (
            ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'
        ) {

            $action =
                $_POST['action'] ?? '';

            $id =
                (int)($_POST['id'] ?? 0);

            // =================================================
            // XÓA PHIẾU MƯỢN
            // =================================================
            if ($action === 'delete') {

                if ($id <= 0) {

                    $errors['id'] =
                        'ID phiếu mượn không hợp lệ.';

                } else {

                    $ketQua =
                        $this->borrowSlipModel
                            ->deletePhieuMuon($id);

                    if ($ketQua) {

                        $this->redirect(
                            'index.php?controller=phieumuon&msg=deleted'
                        );
                        return;

                    } else {

                        $errors['general'] =
                            'Không thể xóa phiếu mượn.';
                    }
                }

            } else {

                // =================================================
                // DỮ LIỆU FORM
                // =================================================
                $maNguoiDung =
                    strtoupper(
                        $this->chuanHoaInput(
                            $_POST['ma_nguoi_dung'] ?? ''
                        )
                    );

                $maBanSao =
                    strtoupper(
                        $this->chuanHoaInput(
                            $_POST['ma_ban_sao'] ?? ''
                        )
                    );

                $ngayMuon =
                    trim(
                        $_POST['ngay_muon'] ?? ''
                    );

                $ngayTra =
                    trim(
                        $_POST['ngay_tra'] ?? ''
                    );

                $trangThai =
                    $this->chuanHoaInput(
                        $_POST['trang_thai'] ?? ''
                    );

                // =================================================
                // KIỂM TRA MÃ NGƯỜI DÙNG
                // =================================================
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
                        'Mã người dùng không chứa ký tự đặc biệt.';
                }

                // =================================================
                // KIỂM TRA MÃ BẢN SAO
                // =================================================
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
                        'Mã bản sao không chứa ký tự đặc biệt.';
                }

                // =================================================
                // LẤY ID NGƯỜI DÙNG
                // =================================================
                $idNguoiDung = 0;

                if (
                    $maNguoiDung !== '' &&
                    !isset($errors['ma_nguoi_dung'])
                ) {

                    $idNguoiDung =
                        $this->borrowSlipModel
                            ->getIdNguoiDungTheoMa(
                                $maNguoiDung
                            );

                    if ($idNguoiDung <= 0) {

                        $errors['ma_nguoi_dung'] =
                            'Mã người dùng không tồn tại hoặc bị khóa.';
                    }
                }

                // =================================================
                // LẤY ID BẢN SAO
                // =================================================
                $idBanSao = 0;

                if (
                    $maBanSao !== '' &&
                    !isset($errors['ma_ban_sao'])
                ) {

                    $idBanSao =
                        $this->borrowSlipModel
                            ->getIdBanSaoTheoMa(
                                $maBanSao
                            );

                    if ($idBanSao <= 0) {

                        $errors['ma_ban_sao'] =
                            'Mã bản sao sách không tồn tại.';
                    }
                }

                // =================================================
                // KIỂM TRA NGÀY MƯỢN
                // =================================================
                if ($ngayMuon === '') {

                    $errors['ngay_muon'] =
                        'Vui lòng chọn ngày mượn.';

                } elseif (!$this->laNgayHopLe($ngayMuon)) {

                    $errors['ngay_muon'] =
                        'Ngày mượn không hợp lệ.';

                } elseif ($ngayMuon > date('Y-m-d')) {

                    $errors['ngay_muon'] =
                        'Ngày mượn không được lớn hơn ngày hiện tại.';
                }

                // =================================================
                // KIỂM TRA NGÀY TRẢ
                // =================================================
                if ($ngayTra !== '') {

                    if (!$this->laNgayHopLe($ngayTra)) {

                        $errors['ngay_tra'] =
                            'Ngày trả không hợp lệ.';

                    } elseif (
                        $ngayMuon !== '' &&
                        $this->laNgayHopLe($ngayMuon) &&
                        $ngayTra < $ngayMuon
                    ) {

                        $errors['ngay_tra'] =
                            'Ngày trả không được trước ngày mượn.';

                    } elseif ($ngayTra > date('Y-m-d')) {

                        $errors['ngay_tra'] =
                            'Ngày trả không được lớn hơn ngày hiện tại.';
                    }
                }

                // =================================================
                // KIỂM TRA TRẠNG THÁI
                // =================================================
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

                // =================================================
                // ADD / EDIT
                // =================================================
                if (empty($errors)) {

                    $ngayTraVal =
                        ($ngayTra === '')
                            ? null
                            : $ngayTra;

                    // -------------------------
                    // THÊM
                    // -------------------------
                    if ($action === 'add') {

                        $ketQua =
                            $this->borrowSlipModel
                                ->addPhieuMuon(
                                    $idNguoiDung,
                                    $idBanSao,
                                    $ngayMuon,
                                    $ngayTraVal,
                                    $trangThai
                                );

                        if ($ketQua) {

                            $this->redirect(
                                'index.php?controller=phieumuon&msg=added'
                            );
                            return;

                        } else {

                            $errors['general'] =
                                'Không thể thêm phiếu mượn.';
                        }
                    }

                    // -------------------------
                    // SỬA
                    // -------------------------
                    elseif (
                        $action === 'edit' &&
                        $id > 0
                    ) {

                        $ketQua =
                            $this->borrowSlipModel
                                ->updatePhieuMuon(
                                    $id,
                                    $idNguoiDung,
                                    $idBanSao,
                                    $ngayMuon,
                                    $ngayTraVal,
                                    $trangThai
                                );

                        if ($ketQua) {

                            $this->redirect(
                                'index.php?controller=phieumuon&msg=updated'
                            );
                            return;

                        } else {

                            $errors['general'] =
                                'Không thể cập nhật phiếu mượn.';
                        }
                    }
                }
            }
        }

        // =========================================================
        // PHIẾU ĐANG SỬA
        // =========================================================
        $id =
            (int)($_GET['edit'] ?? 0);

        $phieuSua = null;

        if ($id > 0) {

            $phieuSua =
                $this->borrowSlipModel
                    ->getPhieuMuonById($id);

            if (!$phieuSua) {
                $id = 0;
            }
        }

        // =========================================================
        // DỮ LIỆU FORM
        // =========================================================
        $maNguoiDung =
            $phieuSua['ma_nguoi_dung']
            ?? ($_POST['ma_nguoi_dung'] ?? '');

        $maBanSao =
            $phieuSua['ma_ban_sao']
            ?? ($_POST['ma_ban_sao'] ?? '');

        $ngayMuon =
            $phieuSua['NgayMuon']
            ?? ($_POST['ngay_muon'] ?? '');

        $ngayTra =
            $phieuSua['NgayTra']
            ?? ($_POST['ngay_tra'] ?? '');

        $trangThai =
            $phieuSua['TrangThai']
            ?? ($_POST['trang_thai'] ?? 'Chờ duyệt');

        // =========================================================
        // DANH SÁCH PHIẾU MƯỢN
        // =========================================================
        $danhSachPhieuMuon =
            $this->borrowSlipModel
                ->getAllPhieuMuon();

        // =========================================================
        // THỐNG KÊ
        // =========================================================
        $thongKePhieuMuon = [
            'tong' => 0,
            'cho_duyet' => 0,
            'dang_muon' => 0,
            'qua_han' => 0,
            'da_tra' => 0
        ];

        foreach ($danhSachPhieuMuon as $phieu) {

            $thongKePhieuMuon['tong']++;

            switch ($phieu['TrangThai']) {

                case 'Chờ duyệt':
                    $thongKePhieuMuon['cho_duyet']++;
                    break;

                case 'Đang mượn':
                    $thongKePhieuMuon['dang_muon']++;
                    break;

                case 'Quá hạn':
                    $thongKePhieuMuon['qua_han']++;
                    break;

                case 'Đã trả':
                    $thongKePhieuMuon['da_tra']++;
                    break;
            }
        }

        // =========================================================
        // TIÊU ĐỀ THEO VAI TRÒ
        // =========================================================
        if ($laQuanTriVien) {

            $pageTitle =
                'KIỂM SOÁT PHIẾU MƯỢN';

            $pageSubtitle =
                'Kiểm soát và quản lý toàn bộ phiếu mượn trong thư viện.';

        } else {

            $pageTitle =
                'QUẢN LÝ PHIẾU MƯỢN';

            $pageSubtitle =
                'Quản lý thông tin & trạng thái phiếu mượn.';
        }

        // =========================================================
        // RENDER VIEW
        // =========================================================
        $this->renderView(
            'phieumuon/index.php',
            [
                'id' =>
                    $id,

                'phieuSua' =>
                    $phieuSua,

                'maNguoiDung' =>
                    $maNguoiDung,

                'maBanSao' =>
                    $maBanSao,

                'ngayMuon' =>
                    $ngayMuon,

                'ngayTra' =>
                    $ngayTra,

                'trangThai' =>
                    $trangThai,

                'errors' =>
                    $errors,

                'thongBao' =>
                    $thongBao,

                'danhSachPhieuMuon' =>
                    $danhSachPhieuMuon,

                'thongKePhieuMuon' =>
                    $thongKePhieuMuon,

                'vaiTroHienTai' =>
                    $vaiTroHienTai,

                'laQuanTriVien' =>
                    $laQuanTriVien,

                'laThuThu' =>
                    $laThuThu,

                'duocQuanLyPhieuMuon' =>
                    $duocQuanLyPhieuMuon,

                'pageTitle' =>
                    $pageTitle,

                'pageSubtitle' =>
                    $pageSubtitle,

                'activePage' =>
                    'phieumuon',

                'activeAction' =>
                    'index'
            ]
        );
    }

    // =========================================================
    // PHIẾU MƯỢN CỦA TÔI
    // =========================================================
    public function cuaToi()
    {
        $this->index();
    }

    // =========================================================
    // CẤU HÌNH HẠN MỨC
    // =========================================================
    public function cauHinhHanMuc()
    {
        $this->requireLogin();

        $this->requireRole([
            'Quản trị viên'
        ]);

        $this->renderView(
            'phieumuon/cauHinhHanMuc.php',
            [
                'activePage' =>
                    'phieumuon',

                'activeAction' =>
                    'cauhinhhanmuc'
            ]
        );
    }

    // =========================================================
    // THỐNG KÊ
    // =========================================================
    public function thongKe()
    {
        $this->requireLogin();

        $this->requireRole([
            'Quản trị viên',
            'Thủ thư'
        ]);

        $this->renderView(
            'phieumuon/thongKe.php',
            [
                'activePage' =>
                    'phieumuon',

                'activeAction' =>
                    'thongke'
            ]
        );
    }
}