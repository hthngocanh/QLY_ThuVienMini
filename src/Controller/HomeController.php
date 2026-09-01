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

        $this->renderView("home/index.php", [
            'isLoggedIn' => $isLoggedIn,
            'stats' => $stats,
            'activePage' => 'trangchu'
        ]);
    }
}
