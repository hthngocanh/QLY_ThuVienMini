<?php
// src/Controller/HomeController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/DashboardModel.php';

class HomeController extends BaseController
{
    private $dashboardModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->dashboardModel = new DashboardModel();
    }

    public function index()
    {
        $isLoggedIn = isset($_SESSION["user"]);
        $stats = [];

        if ($isLoggedIn) {
            $stats = $this->dashboardModel->layThongKeTongQuan();
        }

        // Thủ thư có dashboard riêng; Admin vẫn dùng dashboard hiện tại.
        $view = (($_SESSION['user']['vai_tro'] ?? '') === 'Thủ thư')
            ? "home/librarian.php"
            : "home/index.php";

        $this->renderView($view, [
            'isLoggedIn' => $isLoggedIn,
            'stats' => $stats,
            'activePage' => 'trangchu'
        ]);
    }
}
