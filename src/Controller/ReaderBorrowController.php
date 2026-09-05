<?php
// src/Controller/BorrowSlipController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/ReaderBorrowModel.php';

class ReaderBorrowController extends BaseController
{
    private $borrowSlipModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->borrowSlipModel = new ReaderBorrowModel();
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

    private function laNguoiQuanLyPhieuMuon()
    {
        $vaiTro = $_SESSION['user']['vai_tro'] ?? '';
        return in_array($vaiTro, ['Thủ thư', 'Quản trị viên'], true);
    }

    /**
     * Độc giả gửi yêu cầu mượn từ nút Mượn ở Trang chủ.
     */
    public function yeuCauMuon()
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->redirect('index.php');
        }

        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            $this->redirect('index.php?controller=auth&action=login');
        }

        $vaiTro = $user['vai_tro'] ?? '';
        if ($vaiTro !== 'Độc giả') {
            $this->redirect('index.php?controller=home&borrow=forbidden');
        }

        $bookId = (int)($_POST['book_id'] ?? 0);
        if ($bookId <= 0) {
            $this->redirect('index.php?controller=home&borrow=invalid');
        }

        $maNguoiDung = strtoupper($this->chuanHoaInput($user['ma_nguoi_dung'] ?? ''));
        $idNguoiDung = $this->borrowSlipModel->getIdNguoiDungTheoMa($maNguoiDung);

        if ($idNguoiDung <= 0) {
            $this->redirect('index.php?controller=home&borrow=user_invalid');
        }

        $ngayMuon = date('Y-m-d');

        $ketQua = $this->borrowSlipModel->taoYeuCauMuonTheoDauSach(
            $idNguoiDung,
            $bookId,
            $ngayMuon
        );

        if (!empty($ketQua['success'])) {
            $this->redirect('index.php?controller=home&borrow=success&book_id=' . $bookId);
        }

        $code = $ketQua['code'] ?? 'error';


        if ($code === 'limit_reached') {
            $limit = (int)($ketQua['limit'] ?? 5);
            $this->redirect(
                'index.php?controller=home&borrow=limit_reached&limit=' . $limit
            );
        }
        if ($code === 'user_invalid') {
             $this->redirect('index.php?controller=home&borrow=user_invalid');
        }
        if ($code === 'unavailable') {
            $this->redirect('index.php?controller=home&borrow=unavailable&book_id=' . $bookId);
        }

        $this->redirect('index.php?controller=home&borrow=error');
    }

    public function index()
    {
        $errors = [];
        $thongBao = '';
        $thongBaoLoi = '';

        $user = $_SESSION['user'] ?? null;
        $vaiTroHienTai = $user['vai_tro'] ?? '';
        $laDocGia = ($vaiTroHienTai === 'Độc giả');
        $duocQuanLy = $this->laNguoiQuanLyPhieuMuon();

        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'added') $thongBao = 'Thêm phiếu mượn thành công!';
            if ($_GET['msg'] === 'updated') $thongBao = 'Cập nhật phiếu mượn thành công!';
            if ($_GET['msg'] === 'deleted') $thongBao = 'Xóa phiếu mượn thành công!';
            if ($_GET['msg'] === 'approved') $thongBao = 'Duyệt mượn thành công. Bản sao đã chuyển sang trạng thái Đang mượn.';
            if ($_GET['msg'] === 'returned') $thongBao = 'Xác nhận trả sách thành công. Bản sao đã chuyển về Có sẵn.';
        }

        if (isset($_GET['error'])) {
            if ($_GET['error'] === 'forbidden') $thongBaoLoi = 'Bạn không có quyền thực hiện thao tác này.';
            if ($_GET['error'] === 'approve') $thongBaoLoi = 'Không thể duyệt phiếu mượn. Bản sao có thể không còn sẵn hoặc phiếu không còn ở trạng thái Chờ duyệt.';
            if ($_GET['error'] === 'return') $thongBaoLoi = 'Không thể xác nhận trả sách. Phiếu phải ở trạng thái Đang mượn hoặc Quá hạn.';
            if ($_GET['error'] === 'delete_active') $thongBaoLoi = 'Không thể xóa phiếu đang mượn hoặc quá hạn.';
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int)($_POST['id'] ?? 0);

            // Độc giả không được thao tác quản lý trực tiếp trong màn Phiếu mượn.
            if (!$duocQuanLy) {
                $this->redirect('index.php?controller=phieumuon&error=forbidden');
            }

            if ($action === 'approve') {
                $ketQua = $this->borrowSlipModel->duyetPhieuMuon($id);
                if (!empty($ketQua['success'])) {
                    $this->redirect('index.php?controller=phieumuon&msg=approved');
                }
                $this->redirect('index.php?controller=phieumuon&error=approve');
            }

            if ($action === 'return') {
                $ketQua = $this->borrowSlipModel->xacNhanTraSach($id);
                if (!empty($ketQua['success'])) {
                    $this->redirect('index.php?controller=phieumuon&msg=returned');
                }
                $this->redirect('index.php?controller=phieumuon&error=return');
            }

            if ($action === 'delete') {
                if ($id <= 0) {
                    $errors['id'] = 'ID phiếu mượn không hợp lệ.';
                } else {
                    $daXoa = $this->borrowSlipModel->deletePhieuMuon($id);
                    if ($daXoa) {
                        $this->redirect('index.php?controller=phieumuon&msg=deleted');
                    }
                    $this->redirect('index.php?controller=phieumuon&error=delete_active');
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

        $id = 0;
        $phieuSua = null;

        // Chỉ Thủ thư/Quản trị viên được mở form sửa.
        if ($duocQuanLy) {
            $id = (int)($_GET['edit'] ?? 0);
            if ($id > 0) {
                $phieuSua = $this->borrowSlipModel->getPhieuMuonById($id);
                if (!$phieuSua) {
                    $id = 0;
                }
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

        if ($laDocGia) {
            $maNguoiDungDangNhap = strtoupper($this->chuanHoaInput($user['ma_nguoi_dung'] ?? ''));
            $idNguoiDungDangNhap = $this->borrowSlipModel->getIdNguoiDungTheoMa($maNguoiDungDangNhap);
            $danhSachPhieuMuon = $idNguoiDungDangNhap > 0
                ? $this->borrowSlipModel->getAllPhieuMuon($idNguoiDungDangNhap)
                : [];
        } else {
            $danhSachPhieuMuon = $this->borrowSlipModel->getAllPhieuMuon();
        }

        $this->renderView('phieumuon/reader.php', [
            'id' => $id,
            'phieuSua' => $phieuSua,
            'maNguoiDung' => $maNguoiDung,
            'maBanSao' => $maBanSao,
            'ngayMuon' => $ngayMuon,
            'ngayTra' => $ngayTra,
            'trangThai' => $trangThai,
            'errors' => $errors,
            'thongBao' => $thongBao,
            'thongBaoLoi' => $thongBaoLoi,
            'danhSachPhieuMuon' => $danhSachPhieuMuon,
            'activePage' => 'phieumuon',
            'vaiTroHienTai' => $vaiTroHienTai,
            'laDocGia' => $laDocGia,
            'duocQuanLy' => $duocQuanLy
        ]);
    }
}
