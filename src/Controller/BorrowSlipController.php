<?php
// src/Controller/BorrowSlipController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/BorrowSlipModel.php';

class BorrowSlipController extends BaseController
{
    private $model;

    public function __construct()
    {
        // Khởi động Session nếu chưa được khởi động
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Khởi tạo Model
        $this->model = new BorrowSlipModel();
    }


    // =========================================================
    // LẤY VAI TRÒ NGƯỜI DÙNG
    // =========================================================
    private function getVaiTro()
    {
        return $_SESSION['user']['vai_tro'] ?? '';
    }


    // =========================================================
    // LẤY MÃ NGƯỜI DÙNG
    // =========================================================
    private function getMaNguoiDung()
    {
        return $_SESSION['user']['ma_nguoi_dung'] ?? '';
    }


    // =========================================================
    // KIỂM TRA QUYỀN TRUY CẬP
    // =========================================================
    private function kiemTraQuyen()
    {
        $vaiTro = $this->getVaiTro();

        $vaiTroHopLe = [
            'Thủ thư',
            'Quản trị viên',
            'Độc giả'
        ];

        // Nếu chưa đăng nhập hoặc vai trò không hợp lệ
        if (!in_array($vaiTro, $vaiTroHopLe, true)) {
            header('Location: index.php');
            exit;
        }

        return $vaiTro;
    }


    // =========================================================
    // ĐỘC GIẢ GỬI YÊU CẦU MƯỢN TỪ TRANG CHỦ
    // =========================================================
    public function yeuCauMuon()
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: index.php');
            exit;
        }

        if ($this->getVaiTro() !== 'Độc giả') {
            header('Location: index.php?controller=phieumuon');
            exit;
        }

        $bookId = (int)($_POST['book_id'] ?? 0);

        if ($bookId <= 0) {
            header('Location: index.php?controller=home&borrow=invalid');
            exit;
        }

        $maNguoiDung = trim($this->getMaNguoiDung());

        if ($maNguoiDung === '') {
            header('Location: index.php?controller=home&borrow=user_invalid');
            exit;
        }

        // Tự chọn một bản sao đang Có sẵn của đầu sách.
        // Không bắt Độc giả phải biết/chọn mã bản sao.
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT bc.id, bc.ma_ban_sao
            FROM book_copies bc
            WHERE bc.book_id = :book_id
              AND bc.trang_thai = 'Có sẵn'
            ORDER BY bc.id ASC
            LIMIT 1
        ");

        $stmt->execute([
            'book_id' => $bookId
        ]);

        $banSao = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$banSao) {
            header('Location: index.php?controller=home&borrow=unavailable&book_id=' . $bookId);
            exit;
        }

        $result = $this->model->themPhieuMuon(
            $maNguoiDung,
            $banSao['ma_ban_sao'],
            date('Y-m-d'),
            null,
            'Chờ duyệt'
        );

        if ($result) {
            // Chuyển thẳng sang Phiếu mượn để Độc giả thấy yêu cầu vừa tạo.
            header('Location: index.php?controller=phieumuon&success=created');
            exit;
        }

        header('Location: index.php?controller=home&borrow=error');
        exit;
    }


    // =========================================================
    // TRANG QUẢN LÝ PHIẾU MƯỢN
    // =========================================================
    public function index()
    {
        $vaiTro = $this->kiemTraQuyen();

        $laQuanTriVien = ($vaiTro === 'Quản trị viên');
        $laThuThu = ($vaiTro === 'Thủ thư');

        $errors = [];
        $thongBao = '';


        // =====================================================
        // XỬ LÝ FORM POST
        // =====================================================
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

            $action = $_POST['action'] ?? '';


            // Kiểm tra quyền thêm / sửa
            if (
                ($action === 'add' || $action === 'edit')
                && !$laThuThu
                && !$laQuanTriVien
            ) {
                header('Location: index.php?controller=phieumuon');
                exit;
            }


            // Chỉ Admin được xóa
            if ($action === 'delete' && !$laQuanTriVien) {
                header('Location: index.php?controller=phieumuon');
                exit;
            }


            // =================================================
            // THÊM HOẶC SỬA PHIẾU MƯỢN
            // =================================================
            if ($action === 'add' || $action === 'edit') {

                $id = (int)($_POST['id'] ?? 0);

                $maNguoiDung = trim(
                    $_POST['ma_nguoi_dung'] ?? ''
                );

                $maBanSao = trim(
                    $_POST['ma_ban_sao'] ?? ''
                );

                $ngayMuon = trim(
                    $_POST['ngay_muon'] ?? ''
                );

                $ngayTra = trim(
                    $_POST['ngay_tra'] ?? ''
                );

                $trangThai = trim(
                    $_POST['trang_thai'] ?? ''
                );


                // =============================================
                // VALIDATE
                // =============================================

                if ($maNguoiDung === '') {
                    $errors['ma_nguoi_dung']
                        = 'Vui lòng nhập mã người dùng.';
                }


                if ($maBanSao === '') {
                    $errors['ma_ban_sao']
                        = 'Vui lòng nhập mã bản sao.';
                }


                if ($ngayMuon === '') {
                    $errors['ngay_muon']
                        = 'Vui lòng chọn ngày mượn.';
                }


                if (
                    $ngayTra !== ''
                    && $ngayMuon !== ''
                    && $ngayTra < $ngayMuon
                ) {
                    $errors['ngay_tra']
                        = 'Ngày trả không được trước ngày mượn.';
                }


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
                    $errors['trang_thai']
                        = 'Trạng thái không hợp lệ.';
                }


                // =============================================
                // XỬ LÝ DATABASE
                // =============================================

                if (empty($errors)) {

                    // THÊM
                    if ($action === 'add') {

                        $result = $this->model->themPhieuMuon(
                            $maNguoiDung,
                            $maBanSao,
                            $ngayMuon,
                            $ngayTra ?: null,
                            $trangThai
                        );


                        if ($result) {

                            header(
                                'Location: index.php?controller=phieumuon&success=created'
                            );

                            exit;
                        }


                        $errors['general']
                            = 'Không thể thêm phiếu mượn.';
                    }


                    // SỬA
                    if ($action === 'edit') {

                        if ($id <= 0) {

                            $errors['general']
                                = 'Phiếu mượn không hợp lệ.';

                        } else {

                            $result = $this->model->suaPhieuMuon(
                                $id,
                                $maNguoiDung,
                                $maBanSao,
                                $ngayMuon,
                                $ngayTra ?: null,
                                $trangThai
                            );


                            if ($result) {

                                header(
                                    'Location: index.php?controller=phieumuon&success=updated'
                                );

                                exit;
                            }


                            $errors['general']
                                = 'Không thể cập nhật phiếu mượn.';
                        }
                    }
                }
            }


            // =================================================
            // XÓA PHIẾU MƯỢN
            // =================================================
            if ($action === 'delete') {

                $id = (int)($_POST['id'] ?? 0);


                if (
                    $id > 0
                    && $this->model->xoaPhieuMuon($id)
                ) {

                    header(
                        'Location: index.php?controller=phieumuon&success=deleted'
                    );

                    exit;
                }


                $errors['general']
                    = 'Không thể xóa phiếu mượn.';
            }
        }


        // =====================================================
        // THÔNG BÁO THÀNH CÔNG
        // =====================================================
        $success = $_GET['success'] ?? '';


        if ($success === 'created') {

            $thongBao = 'Thêm phiếu mượn thành công.';

        } elseif ($success === 'updated') {

            $thongBao = 'Cập nhật phiếu mượn thành công.';

        } elseif ($success === 'deleted') {

            $thongBao = 'Xóa phiếu mượn thành công.';
        }


        // =====================================================
        // ĐỘC GIẢ - CHỈ XEM LỊCH SỬ CỦA BẢN THÂN
        // =====================================================
        if ($vaiTro === 'Độc giả') {

            $maNguoiDung = $this->getMaNguoiDung();

            $trangThaiLoc = trim(
                $_GET['trang_thai'] ?? ''
            );


            $danhSachPhieuMuon =
                $this->model->layLichSuMuonTheoNguoiDung(
                    $maNguoiDung,
                    $trangThaiLoc
                );


            // Lấy cấu hình hạn mượn
            $cauHinh =
                $this->model->layCauHinhHanMuc();

            $soNgayMuon =
                (int)($cauHinh['so_ngay_muon'] ?? 14);


            // Tính hạn trả
            foreach ($danhSachPhieuMuon as &$phieu) {

                if (
                    empty($phieu['NgayTra'])
                    && !empty($phieu['NgayMuon'])
                ) {

                    $phieu['HanTra'] =
                        $this->model->tinhHanTra(
                            $phieu['NgayMuon'],
                            $soNgayMuon
                        );

                } else {

                    $phieu['HanTra'] = null;
                }
            }

            unset($phieu);


            $this->renderView(
                'phieumuon/lich_su.php',
                [
                    'danhSachPhieuMuon' =>
                        $danhSachPhieuMuon,

                    'thongBao' =>
                        $thongBao,

                    'tuKhoa' =>
                        '',

                    'trangThaiLoc' =>
                        $trangThaiLoc,

                    'activePage' =>
                        'phieumuon'
                ]
            );

            return;
        }


        // =====================================================
        // THỦ THƯ VÀ ADMIN - XEM TOÀN BỘ PHIẾU
        // =====================================================

        $tuKhoa = trim(
            $_GET['search'] ?? ''
        );

        $trangThaiLoc = trim(
            $_GET['trang_thai'] ?? ''
        );


        $danhSachPhieuMuon =
            $this->model->layDanhSachPhieuMuon(
                $tuKhoa,
                $trangThaiLoc
            );


        $thongKePhieuMuon =
            $this->model->demThongKeTongQuan();


        $this->renderView(
            'phieumuon/index.php',
            [

                'danhSachPhieuMuon' =>
                    $danhSachPhieuMuon,

                'thongKePhieuMuon' =>
                    $thongKePhieuMuon,

                'pageTitle' =>
                    'QUẢN LÝ PHIẾU MƯỢN',

                'pageSubtitle' =>
                    'Quản lý thông tin & trạng thái phiếu mượn',

                'errors' =>
                    $errors,

                'thongBao' =>
                    $thongBao,

                'laThuThu' =>
                    $laThuThu,

                'laQuanTriVien' =>
                    $laQuanTriVien,

                'tuKhoa' =>
                    $tuKhoa,

                'trangThaiLoc' =>
                    $trangThaiLoc,

                'activePage' =>
                    'phieumuon'
            ]
        );
    }


    // =========================================================
    // CẤU HÌNH HẠN MỨC - CHỈ ADMIN
    // =========================================================
    public function cauHinhHanMuc()
    {
        $vaiTro = $this->kiemTraQuyen();

        if ($vaiTro !== 'Quản trị viên') {

            header(
                'Location: index.php?controller=phieumuon'
            );

            exit;
        }


        $errors = [];
        $thongBao = '';


        $cauHinh =
            $this->model->layCauHinhHanMuc();


        if (
            ($_SERVER['REQUEST_METHOD'] ?? 'GET')
            === 'POST'
        ) {

            $soNgayMuon =
                (int)($_POST['so_ngay_muon'] ?? 0);

            $soSachToiDa =
                (int)($_POST['so_sach_toi_da'] ?? 0);


            if ($soNgayMuon <= 0) {

                $errors['so_ngay_muon']
                    = 'Số ngày mượn phải lớn hơn 0.';
            }


            if ($soSachToiDa <= 0) {

                $errors['so_sach_toi_da']
                    = 'Số sách tối đa phải lớn hơn 0.';
            }


            if (empty($errors)) {

                $result =
                    $this->model->capNhatCauHinhHanMuc(
                        $soNgayMuon,
                        $soSachToiDa
                    );


                if ($result) {

                    header(
                        'Location: index.php?controller=phieumuon&action=cauHinhHanMuc&success=1'
                    );

                    exit;
                }


                $errors['general']
                    = 'Không thể cập nhật cấu hình.';
            }


            // Giữ lại dữ liệu khi nhập lỗi
            $cauHinh['so_ngay_muon']
                = $soNgayMuon;

            $cauHinh['so_sach_toi_da']
                = $soSachToiDa;
        }


        if (
            ($_GET['success'] ?? '')
            === '1'
        ) {

            $thongBao =
                'Cập nhật cấu hình hạn mức thành công.';
        }


        $this->renderView(
            'phieumuon/cau_hinh_han_muc.php',
            [

                'cauHinh' =>
                    $cauHinh,

                'errors' =>
                    $errors,

                'thongBao' =>
                    $thongBao,

                'activePage' =>
                    'phieumuon'
            ]
        );
    }


    // =========================================================
    // THỐNG KÊ - CHỈ ADMIN
    // =========================================================
    public function thongKe()
    {
        $vaiTro = $this->kiemTraQuyen();


        if ($vaiTro !== 'Quản trị viên') {

            header(
                'Location: index.php?controller=phieumuon'
            );

            exit;
        }


        $nam =
            (int)($_GET['nam'] ?? date('Y'));


        $thongKeTheoThang =
            $this->model->layThongKeTheoThang(
                $nam
            );


        $topSach =
            $this->model->layTopSachDuocMuonNhieu(5);


        $thongKeTongQuan =
            $this->model->demThongKeTongQuan();


        $this->renderView(
            'phieumuon/thong_ke.php',
            [

                'thongKeTheoThang' =>
                    $thongKeTheoThang,

                'topSach' =>
                    $topSach,

                'thongKeTongQuan' =>
                    $thongKeTongQuan,

                'nam' =>
                    $nam,

                'activePage' =>
                    'phieumuon'
            ]
        );
    }
}