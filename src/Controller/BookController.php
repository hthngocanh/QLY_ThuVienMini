<?php
// src/Controller/BookController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/BookModel.php';

class BookController extends BaseController
{
    private $bookModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->bookModel = new BookModel();
    }

    private function kiemTraDauSach(
        $ma_sach,
        $ten_sach,
        $ma_tac_gia,
        $tac_gia,
        $danh_muc,
        $nha_xuat_ban,
        $nam_xuat_ban,
        $isbn,
        $gia_sach,
        $mo_ta
    ) {
        $loi = [];

        if ($ma_sach == "") {
            $loi["ma_sach"] = "Mã sách không được để trống.";
        } elseif (mb_strlen($ma_sach) < 2 || mb_strlen($ma_sach) > 20) {
            $loi["ma_sach"] = "Mã sách phải có từ 2 đến 20 ký tự.";
        } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $ma_sach)) {
            $loi["ma_sach"] = "Mã sách chỉ được chứa chữ cái và số.";
        }

        if ($ten_sach == "") {
            $loi["ten_sach"] = "Tên sách không được để trống.";
        } elseif (mb_strlen($ten_sach) < 2 || mb_strlen($ten_sach) > 100) {
            $loi["ten_sach"] = "Tên sách phải có từ 2 đến 100 ký tự.";
        } elseif (!preg_match("/[\p{L}\p{N}]/u", $ten_sach)) {
            $loi["ten_sach"] = "Tên sách phải chứa chữ cái hoặc số.";
        }

        if ($ma_tac_gia == "") {
            $loi["ma_tac_gia"] = "Mã tác giả không được để trống.";
        } elseif (mb_strlen($ma_tac_gia) < 2 || mb_strlen($ma_tac_gia) > 20) {
            $loi["ma_tac_gia"] = "Mã tác giả phải có từ 2 đến 20 ký tự.";
        } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $ma_tac_gia)) {
            $loi["ma_tac_gia"] = "Mã tác giả chỉ được chứa chữ cái và số.";
        }

        if ($tac_gia == "") {
            $loi["tac_gia"] = "Tác giả không được để trống.";
        } elseif (mb_strlen($tac_gia) < 2 || mb_strlen($tac_gia) > 100) {
            $loi["tac_gia"] = "Tác giả phải có từ 2 đến 100 ký tự.";
        } elseif (!preg_match("/^[\p{L}\s]+$/u", $tac_gia)) {
            $loi["tac_gia"] = "Tác giả chỉ được chứa chữ cái và khoảng trắng.";
        }

        if ($danh_muc == "") {
            $loi["danh_muc"] = "Vui lòng chọn danh mục.";
        }

        if ($nha_xuat_ban == "") {
            $loi["nha_xuat_ban"] = "Nhà xuất bản không được để trống.";
        } elseif (mb_strlen($nha_xuat_ban) < 2 || mb_strlen($nha_xuat_ban) > 100) {
            $loi["nha_xuat_ban"] = "Nhà xuất bản phải có từ 2 đến 100 ký tự.";
        }

        if ($nam_xuat_ban == "") {
            $loi["nam_xuat_ban"] = "Năm xuất bản không được để trống.";
        } elseif (!ctype_digit($nam_xuat_ban)) {
            $loi["nam_xuat_ban"] = "Năm xuất bản phải là số.";
        } elseif ((int)$nam_xuat_ban < 1000 || (int)$nam_xuat_ban > (int)date("Y")) {
            $loi["nam_xuat_ban"] = "Năm xuất bản không hợp lệ.";
        }

        if ($isbn == "") {
            $loi["isbn"] = "ISBN không được để trống.";
        } elseif (!preg_match("/^[0-9]{10,13}$/", $isbn)) {
            $loi["isbn"] = "ISBN phải gồm 10 hoặc 13 chữ số.";
        }

        if ($gia_sach == "") {
            $loi["gia_sach"] = "Giá sách không được để trống.";
        } elseif (!is_numeric($gia_sach)) {
            $loi["gia_sach"] = "Giá sách phải là số.";
        } elseif ((float)$gia_sach <= 0) {
            $loi["gia_sach"] = "Giá sách phải lớn hơn 0.";
        }

        if ($mo_ta == "") {
            $loi["mo_ta"] = "Mô tả không được để trống.";
        } elseif (mb_strlen($mo_ta) > 500) {
            $loi["mo_ta"] = "Mô tả không được vượt quá 500 ký tự.";
        }

        return $loi;
    }

    public function index()
    {
        // =========================
        // DỮ LIỆU FORM
        // =========================
        $ma_sach = "";
        $ten_sach = "";
        $ma_tac_gia = "";
        $tac_gia = "";
        $danh_muc = "";
        $nha_xuat_ban = "";
        $nam_xuat_ban = "";
        $isbn = "";
        $gia_sach = "";
        $mo_ta = "";

        // =========================
        // TRẠNG THÁI
        // =========================
        $loi = [];
        $vi_tri_sua = null;
        $thong_bao = "";
        $loai_thong_bao = "";

        // Biến này chỉ dùng cho POPUP THÊM
        // Ban đầu luôn đóng
        $hien_popup_them = false;

        // =========================
        // DANH MỤC
        // =========================
        $danh_sach_danh_muc = $this->bookModel->layDanhSachDanhMuc();

        // =========================
        // TÌM KIẾM / LỌC
        // =========================
        $tu_khoa = trim($_GET["tu_khoa"] ?? "");
        $loc_tac_gia = trim($_GET["loc_tac_gia"] ?? "");
        $loc_danh_muc = trim($_GET["loc_danh_muc"] ?? "");
        $loc_nam = trim($_GET["loc_nam"] ?? "");

        $trang = max(1, (int)($_GET["trang"] ?? 1));
        $so_sach_moi_trang = 5;
        $offset = ($trang - 1) * $so_sach_moi_trang;

        // =========================
        // XỬ LÝ POST
        // =========================
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

            // =========================
            // SỬA SÁCH
            // =========================
            if (isset($_POST["sua_sach"])) {

                $id = (int)$_POST["sua_sach"];
                $sach = $this->bookModel->layDauSachTheoId($id);

                if ($sach) {
                    $vi_tri_sua = $sach["id"];

                    $ma_sach = $sach["ma_sach"];
                    $ten_sach = $sach["ten_sach"];
                    $ma_tac_gia = $sach["ma_tac_gia"];
                    $tac_gia = $sach["tac_gia"];
                    $danh_muc = $sach["danh_muc"];
                    $nha_xuat_ban = $sach["nha_xuat_ban"];
                    $nam_xuat_ban = $sach["nam_xuat_ban"];
                    $isbn = $sach["isbn"];
                    $gia_sach = $sach["gia_sach"];
                    $mo_ta = $sach["mo_ta"];
                }
            }

            // =========================
            // XÓA SÁCH
            // =========================
            elseif (isset($_POST["xoa_sach"])) {

                $id = (int)$_POST["xoa_sach"];

                $this->bookModel->xoaDauSach($id);

                $thong_bao = "Xóa sách thành công.";
                $loai_thong_bao = "thanh-cong";
            }

            // =========================
            // CẬP NHẬT SÁCH
            // =========================
            elseif (isset($_POST["cap_nhat_sach"])) {

                $vi_tri_sua = (int)($_POST["id_sua"] ?? -1);

                // Lấy dữ liệu người dùng nhập
                $ma_sach = trim($_POST["ma_sach"] ?? "");
                $ten_sach = trim($_POST["ten_sach"] ?? "");
                $ma_tac_gia = trim($_POST["ma_tac_gia"] ?? "");
                $tac_gia = trim($_POST["tac_gia"] ?? "");
                $danh_muc = trim($_POST["danh_muc"] ?? "");
                $nha_xuat_ban = trim($_POST["nha_xuat_ban"] ?? "");
                $nam_xuat_ban = trim($_POST["nam_xuat_ban"] ?? "");
                $isbn = trim($_POST["isbn"] ?? "");
                $gia_sach = trim($_POST["gia_sach"] ?? "");
                $mo_ta = trim($_POST["mo_ta"] ?? "");

                // Validate
                $loi = $this->kiemTraDauSach(
                    $ma_sach,
                    $ten_sach,
                    $ma_tac_gia,
                    $tac_gia,
                    $danh_muc,
                    $nha_xuat_ban,
                    $nam_xuat_ban,
                    $isbn,
                    $gia_sach,
                    $mo_ta
                );

                // Kiểm tra mã sách trùng
                if (empty($loi)) {
                    if ($this->bookModel->kiemTraMaSachTonTai($ma_sach, $vi_tri_sua)) {
                        $loi["ma_sach"] = "Mã sách đã tồn tại.";
                    }
                }

                // Kiểm tra ISBN trùng
                if (empty($loi)) {
                    if ($this->bookModel->kiemTraIsbnTonTai($isbn, $vi_tri_sua)) {
                        $loi["isbn"] = "ISBN đã tồn tại.";
                    }
                }

                $categoryId = null;

                // Kiểm tra danh mục
                if (empty($loi)) {
                    $categoryId = $this->bookModel->layCategoryIdTheoTen($danh_muc);

                    if (!$categoryId) {
                        $loi["danh_muc"] = "Danh mục không tồn tại.";
                    }
                }

                // Cập nhật
                if (empty($loi)) {

                    $this->bookModel->suaDauSach($vi_tri_sua, [
                        'ma_sach' => $ma_sach,
                        'ten_sach' => $ten_sach,
                        'ma_tac_gia' => $ma_tac_gia,
                        'tac_gia' => $tac_gia,
                        'category_id' => $categoryId,
                        'nha_xuat_ban' => $nha_xuat_ban,
                        'nam_xuat_ban' => $nam_xuat_ban,
                        'isbn' => $isbn,
                        'gia_sach' => $gia_sach,
                        'mo_ta' => $mo_ta
                    ]);

                    $thong_bao = "Cập nhật sách thành công.";
                    $loai_thong_bao = "thanh-cong";

                    $vi_tri_sua = null;

                    $ma_sach = $ten_sach = $ma_tac_gia = $tac_gia = $danh_muc = "";
                    $nha_xuat_ban = $nam_xuat_ban = $isbn = $gia_sach = $mo_ta = "";
                }
            }

            // =========================
            // THÊM SÁCH
            // =========================
            elseif (isset($_POST["them_sach"])) {

                // Chỉ khi FORM THÊM được submit
                // mới cho phép popup tự mở lại
                $hien_popup_them = true;

                // Lấy dữ liệu người dùng nhập
                $ma_sach = trim($_POST["ma_sach"] ?? "");
                $ten_sach = trim($_POST["ten_sach"] ?? "");
                $ma_tac_gia = trim($_POST["ma_tac_gia"] ?? "");
                $tac_gia = trim($_POST["tac_gia"] ?? "");
                $danh_muc = trim($_POST["danh_muc"] ?? "");
                $nha_xuat_ban = trim($_POST["nha_xuat_ban"] ?? "");
                $nam_xuat_ban = trim($_POST["nam_xuat_ban"] ?? "");
                $isbn = trim($_POST["isbn"] ?? "");
                $gia_sach = trim($_POST["gia_sach"] ?? "");
                $mo_ta = trim($_POST["mo_ta"] ?? "");

                // Validate
                $loi = $this->kiemTraDauSach(
                    $ma_sach,
                    $ten_sach,
                    $ma_tac_gia,
                    $tac_gia,
                    $danh_muc,
                    $nha_xuat_ban,
                    $nam_xuat_ban,
                    $isbn,
                    $gia_sach,
                    $mo_ta
                );

                // Kiểm tra ISBN trùng
                if (empty($loi)) {
                    if ($this->bookModel->kiemTraIsbnTonTai($isbn)) {
                        $loi["isbn"] = "ISBN đã tồn tại.";
                    }
                }

                $categoryId = null;

                // Kiểm tra danh mục
                if (empty($loi)) {
                    $categoryId = $this->bookModel->layCategoryIdTheoTen($danh_muc);

                    if (!$categoryId) {
                        $loi["danh_muc"] = "Danh mục không tồn tại.";
                    }
                }

                // =========================
                // THÊM VÀO DATABASE
                // =========================
                if (empty($loi)) {

                    try {

                        $this->bookModel->themDauSach([
                            'ma_sach' => $ma_sach,
                            'ten_sach' => $ten_sach,
                            'ma_tac_gia' => $ma_tac_gia,
                            'tac_gia' => $tac_gia,
                            'category_id' => $categoryId,
                            'nha_xuat_ban' => $nha_xuat_ban,
                            'nam_xuat_ban' => $nam_xuat_ban,
                            'isbn' => $isbn,
                            'gia_sach' => $gia_sach,
                            'mo_ta' => $mo_ta
                        ]);

                        $thong_bao = "Thêm sách thành công.";
                        $loai_thong_bao = "thanh-cong";

                        // Thêm thành công thì đóng popup
                        $hien_popup_them = false;

                        // Xóa dữ liệu form
                        $ma_sach = $ten_sach = $ma_tac_gia = $tac_gia = $danh_muc = "";
                        $nha_xuat_ban = $nam_xuat_ban = $isbn = $gia_sach = $mo_ta = "";

                    } catch (PDOException $e) {

                        if (
                            isset($e->errorInfo[1]) &&
                            $e->errorInfo[1] == 1062
                        ) {
                            $loi["ma_sach"] = "Mã sách đã tồn tại. Vui lòng nhập mã sách mới.";
                        } else {
                            throw $e;
                        }
                    }
                }
            }
        }

        // =========================
        // PHÂN TRANG
        // =========================
        $tong_so_sach = $this->bookModel->demDauSach(
            $tu_khoa,
            $loc_tac_gia,
            $loc_danh_muc,
            $loc_nam
        );

        $tong_so_trang = max(
            1,
            (int)ceil($tong_so_sach / $so_sach_moi_trang)
        );

        $danh_sach_sach = $this->bookModel->layDanhSachDauSach(
            $tu_khoa,
            $loc_tac_gia,
            $loc_danh_muc,
            $loc_nam,
            $so_sach_moi_trang,
            $offset
        );

        // =========================
        // RENDER VIEW
        // =========================
        $this->renderView("dausach/index.php", [
            'ma_sach' => $ma_sach,
            'ten_sach' => $ten_sach,
            'ma_tac_gia' => $ma_tac_gia,
            'tac_gia' => $tac_gia,
            'danh_muc' => $danh_muc,
            'nha_xuat_ban' => $nha_xuat_ban,
            'nam_xuat_ban' => $nam_xuat_ban,
            'isbn' => $isbn,
            'gia_sach' => $gia_sach,
            'mo_ta' => $mo_ta,

            'loi' => $loi,
            'vi_tri_sua' => $vi_tri_sua,

            // Biến mới chỉ để điều khiển popup thêm
            'hien_popup_them' => $hien_popup_them,

            'thong_bao' => $thong_bao,
            'loai_thong_bao' => $loai_thong_bao,

            'danh_sach_danh_muc' => $danh_sach_danh_muc,

            'tu_khoa' => $tu_khoa,
            'loc_tac_gia' => $loc_tac_gia,
            'loc_danh_muc' => $loc_danh_muc,
            'loc_nam' => $loc_nam,

            'trang' => $trang,
            'tong_so_trang' => $tong_so_trang,
            'offset' => $offset,

            'danh_sach_sach' => $danh_sach_sach,

            'activePage' => 'dausach'
        ]);
    }
}