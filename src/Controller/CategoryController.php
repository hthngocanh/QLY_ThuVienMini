<?php
// src/Controller/CategoryController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/CategoryModel.php';

class CategoryController extends BaseController
{
    private $categoryModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $errors = [];
        $danhMucDangSua = null;
        $tenDanhMuc = '';
        $moTa = '';
        $trangThai = 'Hoạt động';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'them') {
                $tenDanhMuc = trim($_POST['ten_danh_muc'] ?? '');
                $moTa = trim($_POST['mo_ta'] ?? '');
                $trangThai = $_POST['trang_thai'] ?? 'Hoạt động';

                if ($tenDanhMuc === '') {
                    $errors['ten_danh_muc'] = 'Vui lòng nhập tên danh mục.';
                }

                if (empty($errors)) {
                    if ($this->categoryModel->themDanhMuc($tenDanhMuc, $moTa, $trangThai)) {
                        $this->redirect('index.php?controller=danhmuc&success=created');
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
                    if ($this->categoryModel->suaDanhMuc($categoryId, $tenDanhMuc, $moTa, $trangThai)) {
                        $this->redirect('index.php?controller=danhmuc&success=updated');
                    }
                }
                $danhMucDangSua = $this->categoryModel->layDanhMucTheoId($categoryId);
            }

            if ($action === 'xoa') {
                $categoryId = (int)($_POST['category_id'] ?? 0);
                if ($categoryId > 0) {
                    if ($this->categoryModel->xoaDanhMuc($categoryId)) {
                        $this->redirect('index.php?controller=danhmuc&success=deleted');
                    }
                }
            }
        }

        if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
            $danhMucDangSua = $this->categoryModel->layDanhMucTheoId((int)$_GET['edit_id']);
        }

        $success = $_GET['success'] ?? '';
        $thongBaoThanhCong = '';
        if ($success === 'created') $thongBaoThanhCong = 'Thêm danh mục sách thành công!';
        if ($success === 'updated') $thongBaoThanhCong = 'Cập nhật danh mục sách thành công!';
        if ($success === 'deleted') $thongBaoThanhCong = 'Xóa danh mục sách thành công!';

        $danhSachDanhMuc = $this->categoryModel->layDanhSachDanhMuc();

        $this->renderView('danhmuc/index.php', [
            'tenDanhMuc' => $tenDanhMuc,
            'moTa' => $moTa,
            'trangThai' => $trangThai,
            'errors' => $errors,
            'danhMucDangSua' => $danhMucDangSua,
            'thongBaoThanhCong' => $thongBaoThanhCong,
            'danhSachDanhMuc' => $danhSachDanhMuc,
            'activePage' => 'danhmuc'
        ]);
    }
}
