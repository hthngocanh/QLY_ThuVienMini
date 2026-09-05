<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

$currentUser = $_SESSION['user'] ?? null;
$currentRole = $currentUser['vai_tro'] ?? '';

// Dùng Unicode escape để tránh lỗi mã hóa tiếng Việt trên Windows.
$roleLibrarian = "Th\u{1EE7} th\u{01B0}";
$roleAdmin = "Qu\u{1EA3}n tr\u{1ECB} vi\u{00EA}n";

// Nếu đã đăng nhập mà KHÔNG phải Thủ thư/Admin thì coi là Độc giả.
// Cách này không còn phụ thuộc vào việc chuỗi "Độc giả" có bị lỗi mã hóa hay không.
$isLoggedIn = !empty($currentUser);
$isStaff = in_array($currentRole, [$roleLibrarian, $roleAdmin], true);
$isReader = $isLoggedIn && !$isStaff;

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

        $actionLower = strtolower($action);

        if ($actionLower === 'kiemtra') {
            $bookCopyController->kiemTra();
        } elseif ($actionLower === 'apitrangthai' || $actionLower === 'api_trang_thai') {
            $bookCopyController->apiTrangThai();
        } else {
            $bookCopyController->index();
        }
        break;

    case 'phieumuon':
    case 'borrow_slip':
        $actionLower = strtolower($action);

        // Độc giả dùng luồng riêng đã khôi phục.
        if ($isReader) {
            require_once __DIR__ . '/src/Controller/ReaderBorrowController.php';
            $readerBorrowController = new ReaderBorrowController();

            if ($actionLower === 'yeucaumuon') {
                $readerBorrowController->yeuCauMuon();
            } else {
                $readerBorrowController->index();
            }
            break;
        }

        // Thủ thư / Quản trị viên vẫn dùng code mới của nhóm.
        require_once __DIR__ . '/src/Controller/BorrowSlipController.php';
        $borrowSlipController = new BorrowSlipController();

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
        // Độc giả vào giao diện tra cứu/mượn sách riêng.
        if ($isReader) {
            require_once __DIR__ . '/src/Controller/ReaderHomeController.php';
            $readerHomeController = new ReaderHomeController();
            $readerHomeController->index();
        } else {
            // Khách, Thủ thư và Quản trị viên giữ trang hiện tại của nhóm.
            require_once __DIR__ . '/src/Controller/HomeController.php';
            $homeController = new HomeController();
            $homeController->index();
        }
        break;
}
