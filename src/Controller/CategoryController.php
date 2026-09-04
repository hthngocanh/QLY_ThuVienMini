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

    // Lấy vai trò hiện tại
    private function getVaiTro()
    {
        return $_SESSION['user']['vai_tro'] ?? '';
    }

    // Kiểm tra quyền
    private function kiemTraQuyen()
    {
        $vaiTro = $this->getVaiTro();

        if (
            $vaiTro !== 'Thủ thư' &&
            $vaiTro !== 'Quản trị viên'
        ) {
            header('Location: index.php');
            exit;
        }

        return $vaiTro;
    }

    public function index()
    {
        $vaiTro = $this->kiemTraQuyen();

        $errors = [];
        $danhMucDangSua = null;

        $tenDanhMuc = '';
        $moTa = '';

        $tuKhoa = trim($_GET['search'] ?? '');
        $locTrangThai = trim($_GET['trang_thai'] ?? '');

        /*
         * ==========================================
         * XỬ LÝ POST
         * ==========================================
         */

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

            $action = $_POST['action'] ?? '';

            /*
             * THÊM DANH MỤC
             */
            if ($action === 'them') {

                if ($vaiTro !== 'Thủ thư') {
                    header('Location: index.php?controller=danhmuc');
                    exit;
                }

                $tenDanhMuc = trim($_POST['ten_danh_muc'] ?? '');
                $moTa = trim($_POST['mo_ta'] ?? '');

                if ($tenDanhMuc === '') {
                    $errors['ten_danh_muc'] =
                        'Vui lòng nhập tên danh mục.';
                }

                if (strlen($tenDanhMuc) > 100) {
                    $errors['ten_danh_muc'] =
                        'Tên danh mục không được vượt quá 100 ký tự.';
                }

                if (strlen($moTa) > 255) {
                    $errors['mo_ta'] =
                        'Mô tả không được vượt quá 255 ký tự.';
                }

                if (empty($errors)) {

                    if (
                        $this->categoryModel
                            ->kiemTraTenDanhMucTonTai($tenDanhMuc)
                    ) {
                        $errors['ten_danh_muc'] =
                            'Tên danh mục đã tồn tại.';
                    }
                }

                if (empty($errors)) {

                    if (
                        $this->categoryModel
                            ->themDanhMuc($tenDanhMuc, $moTa)
                    ) {
                        $this->redirect(
                            'index.php?controller=danhmuc&success=created'
                        );
                    }

                    $errors['general'] =
                        'Không thể thêm danh mục.';
                }
            }

            /*
             * SỬA DANH MỤC
             */
            elseif ($action === 'sua') {

                if ($vaiTro !== 'Thủ thư') {
                    header('Location: index.php?controller=danhmuc');
                    exit;
                }

                $categoryId = (int)($_POST['category_id'] ?? 0);

                $tenDanhMuc = trim(
                    $_POST['ten_danh_muc'] ?? ''
                );

                $moTa = trim(
                    $_POST['mo_ta'] ?? ''
                );

                if ($categoryId <= 0) {
                    $errors['general'] =
                        'Danh mục không hợp lệ.';
                }

                if ($tenDanhMuc === '') {
                    $errors['ten_danh_muc'] =
                        'Vui lòng nhập tên danh mục.';
                }

                if (strlen($tenDanhMuc) > 100) {
                    $errors['ten_danh_muc'] =
                        'Tên danh mục không được vượt quá 100 ký tự.';
                }

                if (strlen($moTa) > 255) {
                    $errors['mo_ta'] =
                        'Mô tả không được vượt quá 255 ký tự.';
                }

                $danhMucHienTai = null;

                if (empty($errors)) {

                    $danhMucHienTai =
                        $this->categoryModel
                            ->layDanhMucTheoId($categoryId);

                    if (!$danhMucHienTai) {
                        $errors['general'] =
                            'Không tìm thấy danh mục.';
                    }
                }

                if (empty($errors)) {

                    if (
                        $this->categoryModel
                            ->kiemTraTenDanhMucTonTai(
                                $tenDanhMuc,
                                $categoryId
                            )
                    ) {
                        $errors['ten_danh_muc'] =
                            'Tên danh mục đã tồn tại.';
                    }
                }

                if (empty($errors)) {

                    if (
                        $this->categoryModel
                            ->suaDanhMuc(
                                $categoryId,
                                $tenDanhMuc,
                                $moTa
                            )
                    ) {
                        $this->redirect(
                            'index.php?controller=danhmuc&success=updated'
                        );
                    }

                    $errors['general'] =
                        'Không thể cập nhật danh mục.';
                }

                $danhMucDangSua = [
                    'category_id' => $categoryId,
                    'ten_danh_muc' => $tenDanhMuc,
                    'mo_ta' => $moTa,
                    'trang_thai' =>
                        $danhMucHienTai['trang_thai']
                        ?? 'Hoạt động'
                ];
            }

            /*
             * ĐỔI TRẠNG THÁI
             * CHỈ ADMIN
             */
            elseif ($action === 'doi_trang_thai') {

                if ($vaiTro !== 'Quản trị viên') {
                    header('Location: index.php?controller=danhmuc');
                    exit;
                }

                $categoryId =
                    (int)($_POST['category_id'] ?? 0);

                $trangThai =
                    $_POST['trang_thai'] ?? '';

                if ($categoryId <= 0) {
                    header(
                        'Location: index.php?controller=danhmuc'
                    );
                    exit;
                }

                if (
                    $trangThai !== 'Hoạt động' &&
                    $trangThai !== 'Ngừng hoạt động'
                ) {
                    header(
                        'Location: index.php?controller=danhmuc'
                    );
                    exit;
                }

                if (
                    $this->categoryModel
                        ->doiTrangThai(
                            $categoryId,
                            $trangThai
                        )
                ) {
                    $this->redirect(
                        'index.php?controller=danhmuc&success=status'
                    );
                }

                $errors['general'] =
                    'Không thể thay đổi trạng thái.';
            }

            /*
             * XÓA DANH MỤC
             * CHỈ ADMIN
             */
            elseif ($action === 'xoa') {

                if ($vaiTro !== 'Quản trị viên') {
                    header('Location: index.php?controller=danhmuc');
                    exit;
                }

                $categoryId =
                    (int)($_POST['category_id'] ?? 0);

                if ($categoryId <= 0) {
                    header(
                        'Location: index.php?controller=danhmuc'
                    );
                    exit;
                }

                if (
                    $this->categoryModel
                        ->kiemTraCoSachTrongDanhMuc($categoryId)
                ) {
                    $errors['general'] =
                        'Không thể xóa vì vẫn còn sách thuộc danh mục này.';
                } else {

                    if (
                        $this->categoryModel
                            ->xoaDanhMuc($categoryId)
                    ) {
                        $this->redirect(
                            'index.php?controller=danhmuc&success=deleted'
                        );
                    }

                    $errors['general'] =
                        'Không thể xóa danh mục.';
                }
            }
        }
     
        /*
         * ==========================================
         * CHỌN DANH MỤC ĐỂ SỬA
         * ==========================================
         */

        if (
            isset($_GET['edit_id']) &&
            is_numeric($_GET['edit_id'])
        ) {

            if ($vaiTro === 'Thủ thư') {

                $danhMucDangSua =
                    $this->categoryModel
                        ->layDanhMucTheoId(
                            (int)$_GET['edit_id']
                        );

                if ($danhMucDangSua) {
                    $tenDanhMuc =
                        $danhMucDangSua['ten_danh_muc'];

                    $moTa =
                        $danhMucDangSua['mo_ta'];
                }
            }
        }

        /*
         * ==========================================
         * THÔNG BÁO
         * ==========================================
         */

        $success = $_GET['success'] ?? '';

        $thongBaoThanhCong = '';

        if ($success === 'created') {
            $thongBaoThanhCong =
                'Thêm danh mục sách thành công.';
        }

        elseif ($success === 'updated') {
            $thongBaoThanhCong =
                'Cập nhật danh mục sách thành công.';
        }

        elseif ($success === 'status') {
            $thongBaoThanhCong =
                'Cập nhật trạng thái danh mục thành công.';
        }

        elseif ($success === 'deleted') {
            $thongBaoThanhCong =
                'Xóa danh mục sách thành công.';
        }

        /*
         * ==========================================
         * LẤY DANH SÁCH
         * ==========================================
         */

        $danhSachDanhMuc =
            $this->categoryModel
                ->layDanhSachDanhMuc($tuKhoa, $locTrangThai);

        /*
         * ==========================================
         * SỐ LIỆU THỐNG KÊ (CHỈ ADMIN)
         * ==========================================
         */

        $tongDanhMuc = 0;
        $soDangHoatDong = 0;
        $soNgungHoatDong = 0;

        if ($vaiTro === 'Quản trị viên') {
            $tongDanhMuc =
                $this->categoryModel->demTongSoDanhMuc();

            $soDangHoatDong =
                $this->categoryModel
                    ->demTheoTrangThai('Hoạt động');

            $soNgungHoatDong =
                $this->categoryModel
                    ->demTheoTrangThai('Ngừng hoạt động');
        }

        /*
         * ==========================================
         * TIÊU ĐỀ THEO ROLE
         * ==========================================
         */

        if ($vaiTro === 'Thủ thư') {
            $tieuDe = 'Danh mục sách';
        } else {
            $tieuDe = 'Quản lý danh mục';
        }

        $this->renderView(
            'danhmuc/index.php',
            [
                'tenDanhMuc' => $tenDanhMuc,
                'moTa' => $moTa,
                'errors' => $errors,
                'danhMucDangSua' => $danhMucDangSua,
                'thongBaoThanhCong' => $thongBaoThanhCong,
                'danhSachDanhMuc' => $danhSachDanhMuc,
                'vaiTro' => $vaiTro,
                'tieuDe' => $tieuDe,
                'tuKhoa' => $tuKhoa,
                'locTrangThai' => $locTrangThai,
                'tongDanhMuc' => $tongDanhMuc,
                'soDangHoatDong' => $soDangHoatDong,
                'soNgungHoatDong' => $soNgungHoatDong,
                'activePage' => 'danhmuc'
            ]
        );
    }
}