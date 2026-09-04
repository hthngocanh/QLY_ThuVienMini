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
        return $dateObject && $dateObject->format('Y-m-d') === $date;
    }

    public function index()
    {
        $this->requireLogin();
        $vaiTro = $_SESSION['user']['vai_tro'] ?? '';

        // =====================================================
        // ĐỘC GIẢ
        // =====================================================
        if ($vaiTro === 'Độc giả') {
            $this->xuLyDocGia();
            return;
        }

        // =====================================================
        // THỦ THƯ & QUẢN TRỊ VIÊN
        // =====================================================
        $this->requireRole(['Thủ thư', 'Quản trị viên']);
        $this->xuLyQuanLyPhieuMuon();
    }

    /**
     * Tách riêng Logic dành cho Độc giả
     */
    private function xuLyDocGia()
    {
        $idNguoiDung = (int)($_SESSION['user']['id'] ?? 0);

        if ($idNguoiDung <= 0) {
            $this->redirect('index.php');
        }

        $errors = [];
        $thongBao = '';

        // Xử lý Đăng ký mượn (POST)
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'dang_ky_muon') {
                $maBanSao = strtoupper($this->chuanHoaInput($_POST['ma_ban_sao'] ?? ''));

                if ($maBanSao === '') {
                    $errors['ma_ban_sao'] = 'Vui lòng chọn hoặc nhập mã bản sao sách.';
                } elseif (!preg_match('/^[A-Za-z0-9_-]+$/', $maBanSao)) {
                    $errors['ma_ban_sao'] = 'Mã bản sao chỉ được chứa chữ, số, dấu gạch ngang hoặc gạch dưới.';
                }

                $idBanSao = 0;
                if ($maBanSao !== '' && !isset($errors['ma_ban_sao'])) {
                    $idBanSao = $this->borrowSlipModel->getIdBanSaoTheoMa($maBanSao);
                    if ($idBanSao <= 0) {
                        $errors['ma_ban_sao'] = 'Mã bản sao sách không tồn tại hoặc đã có người mượn.';
                    }
                }

                $ngayMuon = date('Y-m-d');
                $ngayTra = null;
                $trangThai = 'Chờ duyệt';

                if (empty($errors)) {
                    $ketQua = $this->borrowSlipModel->addPhieuMuon($idNguoiDung, $idBanSao, $ngayMuon, $ngayTra, $trangThai);
                    if ($ketQua) {
                        $this->redirect('index.php?controller=phieumuon&msg=registered');
                    } else {
                        $errors['general'] = 'Không thể đăng ký mượn. Vui lòng thử lại.';
                    }
                }
            }
        }

        if (isset($_GET['msg']) && $_GET['msg'] === 'registered') {
            $thongBao = 'Đăng ký mượn thành công! Vui lòng chờ thủ thư duyệt.';
        }

        $danhSachSach = $this->borrowSlipModel->getDanhSachSachDeMuon();
        $danhSachPhieuMuon = $this->borrowSlipModel->getPhieuMuonCuaNguoiDung($idNguoiDung);

        $this->renderView('phieumuon/quanLyDocGia.php', [
            'danhSachSach' => $danhSachSach,
            'danhSachPhieuMuon' => $danhSachPhieuMuon,
            'errors' => $errors,
            'thongBao' => $thongBao,
            'activePage' => 'phieumuon',
            'activeAction' => 'index'
        ]);
    }

    /**
     * Tách riêng Logic dành cho Thủ thư / QTV
     */
    private function xuLyQuanLyPhieuMuon()
    {
        $errors = [];
        $thongBao = '';

        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'added') $thongBao = 'Thêm phiếu mượn thành công!';
            if ($_GET['msg'] === 'updated') $thongBao = 'Cập nhật phiếu mượn thành công!';
            if ($_GET['msg'] === 'deleted') $thongBao = 'Xóa phiếu mượn thành công!';
        }

        // Xử lý Form POST
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int)($_POST['id'] ?? 0);

            if ($action === 'delete') {
                if ($id <= 0) {
                    $errors['id'] = 'ID phiếu mượn không hợp lệ.';
                } else {
                    $this->borrowSlipModel->deletePhieuMuon($id);
                    $this->redirect('index.php?controller=phieumuon&msg=deleted');
                }
            } else {
                $maNguoiDung = strtoupper($this->chuanHoaInput($_POST['ma_nguoi_dung'] ?? ''));
                $maBanSao = strtoupper($this->chuanHoaInput($_POST['ma_ban_sao'] ?? ''));
                $ngayMuon = trim($_POST['ngay_muon'] ?? '');
                $ngayTra = trim($_POST['ngay_tra'] ?? '');
                $trangThai = $this->chuanHoaInput($_POST['trang_thai'] ?? '');

                if ($maNguoiDung === '') {
                    $errors['ma_nguoi_dung'] = 'Vui lòng nhập mã người dùng.';
                } elseif (!preg_match('/^[A-Za-z0-9_-]+$/', $maNguoiDung)) {
                    $errors['ma_nguoi_dung'] = 'Mã người dùng không chứa ký tự đặc biệt.';
                }

                if ($maBanSao === '') {
                    $errors['ma_ban_sao'] = 'Vui lòng nhập mã bản sao sách.';
                } elseif (!preg_match('/^[A-Za-z0-9_-]+$/', $maBanSao)) {
                    $errors['ma_ban_sao'] = 'Mã bản sao không chứa ký tự đặc biệt.';
                }

                $idNguoiDung = 0;
                if ($maNguoiDung !== '' && !isset($errors['ma_nguoi_dung'])) {
                    $idNguoiDung = $this->borrowSlipModel->getIdNguoiDungTheoMa($maNguoiDung);
                    if ($idNguoiDung <= 0) {
                        $errors['ma_nguoi_dung'] = 'Mã người dùng không tồn tại hoặc bị khóa.';
                    }
                }

                $idBanSao = 0;
                if ($maBanSao !== '' && !isset($errors['ma_ban_sao'])) {
                    $idBanSao = $this->borrowSlipModel->getIdBanSaoTheoMa($maBanSao);
                    if ($idBanSao <= 0) {
                        $errors['ma_ban_sao'] = 'Mã bản sao sách không tồn tại.';
                    }
                }

                if ($ngayMuon === '') {
                    $errors['ngay_muon'] = 'Vui lòng chọn ngày mượn.';
                } elseif (!$this->laNgayHopLe($ngayMuon)) {
                    $errors['ngay_muon'] = 'Ngày mượn không hợp lệ.';
                }

                if ($ngayTra !== '') {
                    if (!$this->laNgayHopLe($ngayTra)) {
                        $errors['ngay_tra'] = 'Ngày trả không hợp lệ.';
                    } elseif ($ngayMuon !== '' && $this->laNgayHopLe($ngayMuon) && $ngayTra < $ngayMuon) {
                        $errors['ngay_tra'] = 'Ngày trả không được trước ngày mượn.';
                    }
                }

                $trangThaiHopLe = ['Chờ duyệt', 'Đang mượn', 'Quá hạn', 'Đã trả'];
                if (!in_array($trangThai, $trangThaiHopLe, true)) {
                    $errors['trang_thai'] = 'Trạng thái không hợp lệ.';
                }

                // Thực hiện lưu
                if (empty($errors)) {
                    $ngayTraVal = ($ngayTra === '') ? null : $ngayTra;

                    if ($action === 'add') {
                        $this->borrowSlipModel->addPhieuMuon($idNguoiDung, $idBanSao, $ngayMuon, $ngayTraVal, $trangThai);
                        $this->redirect('index.php?controller=phieumuon&msg=added');
                    }

                    if ($action === 'edit' && $id > 0) {
                        $this->borrowSlipModel->updatePhieuMuon($id, $idNguoiDung, $idBanSao, $ngayMuon, $ngayTraVal, $trangThai);
                        $this->redirect('index.php?controller=phieumuon&msg=updated');
                    }
                }
            }
        }

        // Lấy dữ liệu sửa
        $id = (int)($_GET['edit'] ?? 0);
        $phieuSua = null;
        if ($id > 0) {
            $phieuSua = $this->borrowSlipModel->getPhieuMuonById($id);
            if (!$phieuSua) {
                $id = 0;
            }
        }

        $maNguoiDung = $phieuSua['ma_nguoi_dung'] ?? ($_POST['ma_nguoi_dung'] ?? '');
        $maBanSao = $phieuSua['ma_ban_sao'] ?? ($_POST['ma_ban_sao'] ?? '');
        $ngayMuon = $phieuSua['NgayMuon'] ?? ($_POST['ngay_muon'] ?? '');
        $ngayTra = $phieuSua['NgayTra'] ?? ($_POST['ngay_tra'] ?? '');
        $trangThai = $phieuSua['TrangThai'] ?? ($_POST['trang_thai'] ?? 'Chờ duyệt');

        $danhSachPhieuMuon = $this->borrowSlipModel->getAllPhieuMuon();

        $this->renderView('phieumuon/index.php', [
            'id' => $id,
            'phieuSua' => $phieuSua,
            'maNguoiDung' => $maNguoiDung,
            'maBanSao' => $maBanSao,
            'ngayMuon' => $ngayMuon,
            'ngayTra' => $ngayTra,
            'trangThai' => $trangThai,
            'errors' => $errors,
            'thongBao' => $thongBao,
            'danhSachPhieuMuon' => $danhSachPhieuMuon,
            'activePage' => 'phieumuon',
            'activeAction' => 'index'
        ]);
    }

    public function cuaToi()
    {
        $this->index();
    }

    public function cauHinhHanMuc()
    {
        $this->requireLogin();
        $this->requireRole(['Quản trị viên']);
        
        $this->renderView('phieumuon/cauHinhHanMuc.php', [
            'activePage' => 'phieumuon',
            'activeAction' => 'cauhinhhanmuc'
        ]);
    }

    public function thongKe()
    {
        $this->requireLogin();
        $this->requireRole(['Quản trị viên', 'Thủ thư']);

        $this->renderView('phieumuon/thongKe.php', [
            'activePage' => 'phieumuon',
            'activeAction' => 'thongke'
        ]);
    }
}