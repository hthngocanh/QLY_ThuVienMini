<?php
// src/Controller/BaseController.php

class BaseController
{
    /**
     * Render một view và truyền dữ liệu vào view đó
     *
     * @param string $viewPath Đường dẫn file view (tính từ thư mục gốc hoặc views/)
     * @param array $data Mảng dữ liệu truyền cho view
     */
    protected function renderView(string $viewPath, array $data = [])
    {
        // Giải nén các biến từ mảng dữ liệu để view có thể dùng trực tiếp
        extract($data);

        // Kiểm tra đường dẫn view
        $fullPath = __DIR__ . '/../../views/' . ltrim($viewPath, '/');
        if (!file_exists($fullPath)) {
            // Nếu không tìm thấy trong views/, thử tìm theo đường dẫn tương đối từ gốc
            $fullPath = __DIR__ . '/../../' . ltrim($viewPath, '/');
        }

        if (file_exists($fullPath)) {
            require $fullPath;
        } else {
            die("Không tìm thấy file view: " . htmlspecialchars($viewPath));
        }
    }

    /**
     * Chuyển hướng trang
     *
     * @param string $url URL đích
     */
    protected function redirect(string $url)
    {
        header("Location: " . $url);
        exit;
    }

    /**
     * Kiểm tra người dùng đã đăng nhập chưa
     *
     * @param string $redirectUrl URL chuyển hướng nếu chưa đăng nhập
     * @return array Thông tin user hiện tại
     */
    protected function requireLogin(string $redirectUrl = 'index.php?controller=auth&action=login')
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) && empty($_SESSION['maNguoiDung']) && empty($_SESSION['ma_nguoi_dung'])) {
            $this->redirect($redirectUrl);
        }

        return $_SESSION['user'] ?? [];
    }

    /**
     * Kiểm tra phân quyền theo vai trò
     *
     * @param array $allowedRoles Danh sách vai trò được phép truy cập
     * @param string $redirectUrl URL chuyển hướng nếu không có quyền
     */
    protected function requireRole(array $allowedRoles, string $redirectUrl = 'index.php')
    {
        $user = $this->requireLogin();
        $userRole = $user['vai_tro'] ?? 'Độc giả';

        if (!in_array($userRole, $allowedRoles, true)) {
            $this->redirect($redirectUrl);
        }
    }
}
