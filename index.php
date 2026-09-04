<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

switch (strtolower($controller)) {
    case 'auth':
        require_once __DIR__ . '/src/Controller/AuthController.php';
        $authController = new AuthController();
        if ($action === 'login') {
            $authController->login();
        } elseif ($action === 'register') {
            $authController->register();
        } elseif ($action === 'logout') {
            $authController->logout();
        } elseif ($action === 'change_password' || $action === 'changePassword') {
            $authController->changePassword();
        } elseif ($action === 'forgot_password' || $action === 'forgotPassword') {
            $authController->forgotPassword();
        } else {
            $authController->login();
        }
        break;

    case 'danhmuc':
    case 'category':
        require_once __DIR__ . '/src/Controller/CategoryController.php';
        $categoryController = new CategoryController();
        $categoryController->index();
        break;

    case 'dausach':
    case 'book':
        require_once __DIR__ . '/src/Controller/BookController.php';
        $bookController = new BookController();
        $bookController->index();
        break;

    case 'bansao':
    case 'book_copy':
        require_once __DIR__ . '/src/Controller/BookCopyController.php';
        $bookCopyController = new BookCopyController();
        if (strtolower($action) === 'kiemtra') {
            $bookCopyController->kiemTra();
        } else {
            $bookCopyController->index();
        }
        break;

    case 'phieumuon':
    case 'borrow_slip':
        require_once __DIR__ . '/src/Controller/BorrowSlipController.php';
        $borrowSlipController = new BorrowSlipController();
        
        // --- ĐOẠN ĐÃ ĐƯỢC BỔ SUNG CHO PHIẾU MƯỢN ---
        $actionLower = strtolower($action);
        if ($actionLower === 'cauhinhhanmuc') {
            $borrowSlipController->cauHinhHanMuc();
        } elseif ($actionLower === 'thongke') {
            $borrowSlipController->thongKe();
        } else {
            $borrowSlipController->index();
        }
        break;

    case 'nguoidung':
    case 'user':
        require_once __DIR__ . '/src/Controller/UserController.php';
        $userController = new UserController();
        
        // Chuyển action về chữ thường để tránh lỗi phân biệt hoa/thường từ URL
        $actionLower = strtolower($action);
        if ($actionLower === 'profile') {
            $userController->profile();
        } elseif ($actionLower === 'tracuudocgia' || $actionLower === 'tracuu') {
            $userController->traCuuDocGia();
        } elseif ($actionLower === 'quanlydocgia') {
            $userController->quanLyDocGia();
        } elseif ($actionLower === 'quanlynhansu') {
            $userController->quanLyNhanSu();
        } elseif ($actionLower === 'yeucaucaplaimatkhau' || $actionLower === 'yeucau') {
            $userController->yeuCauCapLaiMatKhau();
        } else {
            $userController->index();
        }
        break;

    case 'about':
        require_once __DIR__ . '/src/Controller/AboutController.php';
        $aboutController = new AboutController();
        $aboutController->index();
        break;

    case 'home':
    case 'trangchu':
    default:
        require_once __DIR__ . '/src/Controller/HomeController.php';
        $homeController = new HomeController();
        $homeController->index();
        break;
}