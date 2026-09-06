<?php
// src/Controller/HomeController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/DashboardModel.php';
require_once __DIR__ . '/../Model/BookModel.php';

class HomeController extends BaseController
{
    private $dashboardModel;
    private $bookModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->dashboardModel = new DashboardModel();
        $this->bookModel = new BookModel();
    }

    public function index()
    {
        $isLoggedIn = isset($_SESSION["user"]);
        $stats = [];
        $danhSachSach = [];

        if ($isLoggedIn) {
            $vaiTro = $_SESSION["user"]["vai_tro"] ?? "Độc giả";
            if ($vaiTro === "Độc giả") {
                $danhSachSach = $this->bookModel->layDanhSachSachChoDocGia();
            } else {
                $stats = $this->dashboardModel->layThongKeTongQuan();
            }
        }

        $this->renderView("home/index.php", [
            'isLoggedIn'   => $isLoggedIn,
            'stats'        => $stats,
            'danhSachSach' => $danhSachSach,
            'activePage'   => 'trangchu'
        ]);
    }
}
