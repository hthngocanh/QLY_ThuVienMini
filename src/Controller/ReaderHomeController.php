<?php
// src/Controller/HomeController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/DashboardModel.php';
require_once __DIR__ . '/../Model/BookCopyModel.php';
require_once __DIR__ . '/../Model/ReaderHomeModel.php';

class ReaderHomeController extends BaseController
{
    private $dashboardModel;
    private $bookCopyModel;
    private $readerHomeModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->dashboardModel = new DashboardModel();
        $this->bookCopyModel = new BookCopyModel();
        $this->readerHomeModel = new ReaderHomeModel();
    }

    public function index()
    {
        $isLoggedIn = isset($_SESSION["user"]);
        $stats = [];
        $danhSachSach = [];
        $trangThaiMuonCuaToi = [];

        if ($isLoggedIn) {
            $vaiTro = $_SESSION["user"]["vai_tro"] ?? "";

            // Thủ thư và Quản trị viên giữ nguyên dashboard tổng quan cũ.
            if ($vaiTro !== "Độc giả") {
                $stats = $this->dashboardModel->layThongKeTongQuan();
            }

            // Riêng Độc giả: lấy danh sách đầu sách + trạng thái bản sao
            // và trạng thái yêu cầu mượn của chính tài khoản đang đăng nhập.
            if ($vaiTro === "Độc giả") {
                $danhSachSach = $this->bookCopyModel->layTinhTrangDauSach();

                $maNguoiDung = $_SESSION["user"]["ma_nguoi_dung"] ?? "";
                if ($maNguoiDung !== "") {
                    $idNguoiDung = $this->readerHomeModel->getIdNguoiDungTheoMa($maNguoiDung);
                    if ($idNguoiDung > 0) {
                        $trangThaiMuonCuaToi = $this->readerHomeModel->getTrangThaiDauSachCuaNguoiDung($idNguoiDung);
                    }
                }
            }
        }

        $this->renderView("home/reader.php", [
            'isLoggedIn' => $isLoggedIn,
            'stats' => $stats,
            'danhSachSach' => $danhSachSach,
            'trangThaiMuonCuaToi' => $trangThaiMuonCuaToi,
            'activePage' => 'trangchu'
        ]);
    }
}
