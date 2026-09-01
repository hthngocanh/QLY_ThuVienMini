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
        $errors = [];
        $thongBao = '';

        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'added') $thongBao = 'Thêm phiếu mượn thành công!';
            if ($_GET['msg'] === 'updated') $thongBao = 'Cập nhật phiếu mượn thành công!';
            if ($_GET['msg'] === 'deleted') $thongBao = 'Xóa phiếu mượn thành công!';
        }

        // Xử lý POST
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
                    $errors['ma_nguoi_dung'] = 'Mã người dùng chỉ được chứa chữ, số, dấu gạch ngang hoặc gạch dưới.';
                }

                if ($maBanSao === '') {
                    $errors['ma_ban_sao'] = 'Vui lòng nhập mã bản sao sách.';
                } elseif (!preg_match('/^[A-Za-z0-9_-]+$/', $maBanSao)) {
                    $errors['ma_ban_sao'] = 'Mã bản sao chỉ được chứa chữ, số, dấu gạch ngang hoặc gạch dưới.';
                }

                $idNguoiDung = 0;
                if ($maNguoiDung !== '' && !isset($errors['ma_nguoi_dung'])) {
                    $idNguoiDung = $this->borrowSlipModel->getIdNguoiDungTheoMa($maNguoiDung);
                    if ($idNguoiDung <= 0) {
                        $errors['ma_nguoi_dung'] = 'Mã người dùng không tồn tại hoặc tài khoản đã bị khóa.';
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
                } elseif ($ngayMuon > date('Y-m-d')) {
                    $errors['ngay_muon'] = 'Ngày mượn không được lớn hơn ngày hiện tại.';
                }

                if ($ngayTra !== '') {
                    if (!$this->laNgayHopLe($ngayTra)) {
                        $errors['ngay_tra'] = 'Ngày trả không hợp lệ.';
                    } elseif ($ngayMuon !== '' && $this->laNgayHopLe($ngayMuon) && $ngayTra < $ngayMuon) {
                        $errors['ngay_tra'] = 'Ngày trả không được trước ngày mượn.';
                    } elseif ($ngayTra > date('Y-m-d')) {
                        $errors['ngay_tra'] = 'Ngày trả không được lớn hơn ngày hiện tại.';
                    }
                }

                $trangThaiHopLe = ['Chờ duyệt', 'Đang mượn', 'Quá hạn', 'Đã trả'];
                if (!in_array($trangThai, $trangThaiHopLe, true)) {
                    $errors['trang_thai'] = 'Trạng thái không hợp lệ.';
                }

                if (empty($errors)) {
                    $ngayTraVal = ($ngayTra === '') ? null : $ngayTra;

                    if ($action === 'add') {
                        $this->borrowSlipModel->addPhieuMuon($idNguoiDung, $idBanSao, $ngayMuon, $ngayTraVal, $trangThai);
                        $this->redirect('index.php?controller=phieumuon&msg=added');
                    }

                    if ($action === 'edit') {
                        if ($id <= 0) {
                            $errors['id'] = 'ID phiếu mượn không hợp lệ.';
                        } else {
                            $this->borrowSlipModel->updatePhieuMuon($id, $idNguoiDung, $idBanSao, $ngayMuon, $ngayTraVal, $trangThai);
                            $this->redirect('index.php?controller=phieumuon&msg=updated');
                        }
                    }
                }
            }
        }

        // Lấy dữ liệu form edit nếu có
        $id = (int)($_GET['edit'] ?? 0);
        $phieuSua = null;
        if ($id > 0) {
            $phieuSua = $this->borrowSlipModel->getPhieuMuonById($id);
            if (!$phieuSua) {
                $id = 0;
            }
        }

        $maNguoiDung = '';
        $maBanSao = '';
        $ngayMuon = '';
        $ngayTra = '';
        $trangThai = 'Chờ duyệt';

        if ($phieuSua) {
            $maNguoiDung = $phieuSua['ma_nguoi_dung'] ?? '';
            $maBanSao = $phieuSua['ma_ban_sao'] ?? '';
            $ngayMuon = $phieuSua['NgayMuon'] ?? '';
            $ngayTra = $phieuSua['NgayTra'] ?? '';
            $trangThai = $phieuSua['TrangThai'] ?? 'Chờ duyệt';
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && !empty($errors)) {
            $id = (int)($_POST['id'] ?? 0);
            $maNguoiDung = $_POST['ma_nguoi_dung'] ?? '';
            $maBanSao = $_POST['ma_ban_sao'] ?? '';
            $ngayMuon = $_POST['ngay_muon'] ?? '';
            $ngayTra = $_POST['ngay_tra'] ?? '';
            $trangThai = $_POST['trang_thai'] ?? 'Chờ duyệt';
        }

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
            'activePage' => 'phieumuon'
        ]);
    }
}
