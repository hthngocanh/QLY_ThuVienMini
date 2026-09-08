<?php
// src/Controller/ReaderHomeController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/DashboardModel.php';
require_once __DIR__ . '/../Model/BookCopyModel.php';
require_once __DIR__ . '/../Model/BorrowSlipModel.php';

class ReaderHomeController extends BaseController
{
    private $dashboardModel;
    private $bookCopyModel;
    private $borrowSlipModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->dashboardModel = new DashboardModel();
        $this->bookCopyModel = new BookCopyModel();
        $this->borrowSlipModel = new BorrowSlipModel();
    }

    public function index()
    {
        $isLoggedIn = isset($_SESSION["user"]);
        $stats = [];
        $danhSachSach = [];
        $trangThaiMuonCuaToi = [];

        if ($isLoggedIn) {
            $vaiTro = $_SESSION["user"]["vai_tro"] ?? "";

            if ($vaiTro !== "Độc giả") {
                $stats = $this->dashboardModel->layThongKeTongQuan();
            }

            if ($vaiTro === "Độc giả") {
                $danhSachSach = $this->bookCopyModel->layTinhTrangDauSach();

                $maNguoiDung = $_SESSION["user"]["ma_nguoi_dung"] ?? "";

                if ($maNguoiDung !== "") {
                    // BorrowSlipModel đã có sẵn trong dự án và đang được trang Phiếu mượn dùng.
                    // Lấy lịch sử của chính độc giả đang đăng nhập rồi ánh xạ trạng thái
                    // đang còn hiệu lực theo từng đầu sách.
                    $lichSuMuon = $this->borrowSlipModel
                        ->layLichSuMuonTheoNguoiDung($maNguoiDung, '');

                    $trangThaiTheoMaSach = [];

                    foreach ($lichSuMuon as $phieu) {
                        $maSach = $phieu['ma_sach'] ?? '';
                        $trangThaiPhieu = $phieu['TrangThai'] ?? '';

                        if (
                            $maSach !== ''
                            && !isset($trangThaiTheoMaSach[$maSach])
                            && in_array(
                                $trangThaiPhieu,
                                ['Chờ duyệt', 'Đang mượn', 'Quá hạn'],
                                true
                            )
                        ) {
                            $trangThaiTheoMaSach[$maSach] = $trangThaiPhieu;
                        }
                    }

                    foreach ($danhSachSach as $sach) {
                        $bookId = (int)($sach['book_id'] ?? 0);
                        $maSach = $sach['ma_sach'] ?? '';

                        if (
                            $bookId > 0
                            && $maSach !== ''
                            && isset($trangThaiTheoMaSach[$maSach])
                        ) {
                            $trangThaiMuonCuaToi[$bookId] =
                                $trangThaiTheoMaSach[$maSach];
                        }
                    }
                }
            }
        }

        $this->renderView("home/index.php", [
            'isLoggedIn' => $isLoggedIn,
            'stats' => $stats,
            'danhSachSach' => $danhSachSach,
            'trangThaiMuonCuaToi' => $trangThaiMuonCuaToi,
            'activePage' => 'trangchu'
        ]);
    }
}
