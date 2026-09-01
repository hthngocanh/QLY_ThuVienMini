<?php
// src/Controller/Controllerdanhmuc.php

require_once __DIR__ . '/../Repository/Repositorydanhmuc.php';

class Controllerdanhmuc
{
    private $categoryRepo;

    public function __construct()
    {
        $this->categoryRepo = new Repositorydanhmuc();
    }

    public function index()
    {
        $errors = [];
        $danhMucDangSua = null;
        $tenDanhMuc = '';
        $moTa = '';
        $trangThai = 'Hoạt động';
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'them') {
                $tenDanhMuc = trim($_POST['ten_danh_muc'] ?? '');
                $moTa = trim($_POST['mo_ta'] ?? '');
                $trangThai = $_POST['trang_thai'] ?? 'Hoạt động';

                if ($tenDanhMuc === '') {
                    $errors['ten_danh_muc'] = 'Vui lòng nhập tên danh mục.';
                }

                if (empty($errors)) {
                    if ($this->categoryRepo->themDanhMuc($tenDanhMuc, $moTa, $trangThai)) {
                        header('Location: index.php?controller=danhmuc&success=created');
                        exit;
                    }
                }
            }

            if ($action === 'sua') {
                $categoryId = (int)($_POST['category_id'] ?? 0);
                $tenDanhMuc = trim($_POST['ten_danh_muc'] ?? '');
                $moTa = trim($_POST['mo_ta'] ?? '');
                $trangThai = $_POST['trang_thai'] ?? 'Hoạt động';

                if ($tenDanhMuc === '') {
                    $errors['ten_danh_muc'] = 'Vui lòng nhập tên danh mục.';
                }

                if (empty($errors)) {
                    if ($this->categoryRepo->suaDanhMuc($categoryId, $tenDanhMuc, $moTa, $trangThai)) {
                        header('Location: index.php?controller=danhmuc&success=updated');
                        exit;
                    }
                }
                $danhMucDangSua = $this->categoryRepo->layDanhMucTheoId($categoryId);
            }

            if ($action === 'xoa') {
                $categoryId = (int)($_POST['category_id'] ?? 0);
                if ($categoryId > 0) {
                    if ($this->categoryRepo->xoaDanhMuc($categoryId)) {
                        header('Location: index.php?controller=danhmuc&success=deleted');
                        exit;
                    }
                }
            }
        }

        if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
            $danhMucDangSua = $this->categoryRepo->layDanhMucTheoId((int)$_GET['edit_id']);
        }

        $success = $_GET['success'] ?? '';
        $thongBaoThanhCong = '';
        if ($success === 'created') $thongBaoThanhCong = 'Thêm danh mục sách thành công!';
        if ($success === 'updated') $thongBaoThanhCong = 'Cập nhật danh mục sách thành công!';
        if ($success === 'deleted') $thongBaoThanhCong = 'Xóa danh mục sách thành công!';

        $danhSachDanhMuc = $this->categoryRepo->layDanhSachDanhMuc();

        // Trỏ thẳng trực tiếp đến file View ở đường dẫn /danhmucsach/danhmuc.php
        require_once __DIR__ . '/../../danhmucsach/danhmuc.php';
    }
}