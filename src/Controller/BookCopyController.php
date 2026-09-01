<?php
// src/Controller/BookCopyController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Model/BookCopyModel.php';

class BookCopyController extends BaseController
{
    private $bookCopyModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->bookCopyModel = new BookCopyModel();
    }

    public function index()
    {
        $bookId = "";
        $maBanSao = "";
        $viTri = "";
        $trangThai = "Có sẵn";
        $editId = "";

        $loiBookId = "";
        $loiMaBanSao = "";
        $loiViTri = "";
        $loiTrangThai = "";

        $thongBao = "";
        $thongBaoLoi = "";

        if (isset($_GET["success"])) {
            if ($_GET["success"] === "add") {
                $thongBao = "Thêm bản sao sách thành công!";
            }
            if ($_GET["success"] === "update") {
                $thongBao = "Cập nhật bản sao sách thành công!";
            }
            if ($_GET["success"] === "delete") {
                $thongBao = "Xóa bản sao sách thành công!";
            }
        }

        if (isset($_GET["error"])) {
            if ($_GET["error"] === "borrowed") {
                $thongBaoLoi = "Không thể xóa vì bản sao đã có lịch sử mượn.";
            }
            if ($_GET["error"] === "delete") {
                $thongBaoLoi = "Không thể xóa bản sao sách.";
            }
        }

        $danhSachDauSach = $this->bookCopyModel->layDanhSachDauSach();

        // Xử lý Xóa
        if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST" && ($_POST["action"] ?? "") === "delete") {
            $deleteId = trim($_POST["delete_id"] ?? "");
            if (ctype_digit($deleteId)) {
                try {
                    $soPhieuMuon = $this->bookCopyModel->demSoPhieuMuonCuaBanSao($deleteId);
                    if ($soPhieuMuon > 0) {
                        $this->redirect("index.php?controller=bansao&error=borrowed");
                    }

                    $this->bookCopyModel->xoaBanSao($deleteId);
                    $this->redirect("index.php?controller=bansao&success=delete");
                } catch (PDOException $e) {
                    $this->redirect("index.php?controller=bansao&error=delete");
                }
            }
        }

        // Lấy dữ liệu cần sửa
        if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "GET" && isset($_GET["edit"])) {
            $editId = trim($_GET["edit"]);
            if (ctype_digit($editId)) {
                $banSaoSua = $this->bookCopyModel->layBanSaoTheoId($editId);
                if ($banSaoSua) {
                    $bookId = $banSaoSua["book_id"];
                    $maBanSao = $banSaoSua["ma_ban_sao"];
                    $viTri = $banSaoSua["vi_tri"];
                    $trangThai = $banSaoSua["trang_thai"];
                } else {
                    $editId = "";
                    $thongBaoLoi = "Không tìm thấy bản sao cần sửa.";
                }
            }
        }

        // Xử lý Thêm / Cập nhật
        if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST" && in_array($_POST["action"] ?? "", ["add", "update"], true)) {
            $action = $_POST["action"];
            $editId = trim($_POST["edit_id"] ?? "");
            $bookId = trim($_POST["book_id"] ?? "");
            $maBanSao = trim($_POST["ma_ban_sao"] ?? "");
            $viTri = trim($_POST["vi_tri"] ?? "");
            $trangThai = trim($_POST["trang_thai"] ?? "");

            if ($bookId === "") {
                $loiBookId = "Vui lòng chọn đầu sách.";
            } elseif (!ctype_digit($bookId)) {
                $loiBookId = "Đầu sách không hợp lệ.";
            }

            if ($maBanSao === "") {
                $loiMaBanSao = "Vui lòng nhập mã bản sao.";
            } elseif (!preg_match('/^[A-Za-z0-9_-]+$/', $maBanSao)) {
                $loiMaBanSao = "Mã bản sao chỉ được chứa chữ, số, dấu - hoặc _.";
            }

            if ($viTri === "") {
                $loiViTri = "Vui lòng nhập vị trí bản sao.";
            }

            $trangThaiHopLe = ["Có sẵn", "Đang mượn", "Hỏng"];
            if (!in_array($trangThai, $trangThaiHopLe, true)) {
                $loiTrangThai = "Trạng thái không hợp lệ.";
            }

            if ($loiBookId === "" && $loiMaBanSao === "" && $loiViTri === "" && $loiTrangThai === "") {
                try {
                    if ($action === "add") {
                        $this->bookCopyModel->themBanSao($bookId, $maBanSao, $viTri, $trangThai);
                        $this->redirect("index.php?controller=bansao&success=add");
                    }

                    if ($action === "update") {
                        if (!ctype_digit($editId)) {
                            throw new Exception("ID bản sao không hợp lệ.");
                        }

                        $this->bookCopyModel->suaBanSao($editId, $bookId, $maBanSao, $viTri, $trangThai);
                        $this->redirect("index.php?controller=bansao&success=update");
                    }
                } catch (PDOException $e) {
                    if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                        $loiMaBanSao = "Mã bản sao đã tồn tại.";
                    } else {
                        $thongBaoLoi = "Không thể lưu bản sao sách.";
                    }
                } catch (Exception $e) {
                    $thongBaoLoi = $e->getMessage();
                }
            }
        }

        $danhSachBanSao = $this->bookCopyModel->layDanhSachBanSao();

        $this->renderView("bansao/index.php", [
            'bookId' => $bookId,
            'maBanSao' => $maBanSao,
            'viTri' => $viTri,
            'trangThai' => $trangThai,
            'editId' => $editId,
            'loiBookId' => $loiBookId,
            'loiMaBanSao' => $loiMaBanSao,
            'loiViTri' => $loiViTri,
            'loiTrangThai' => $loiTrangThai,
            'thongBao' => $thongBao,
            'thongBaoLoi' => $thongBaoLoi,
            'danhSachDauSach' => $danhSachDauSach,
            'danhSachBanSao' => $danhSachBanSao,
            'activePage' => 'bansao'
        ]);
    }

    public function kiemTra()
    {
        $id_ban_sao = $_POST['id_ban_sao'] ?? '';
        $id_dau_sach = $_POST['id_dau_sach'] ?? '';
        $ma_ban_sao = $_POST['ma_ban_sao'] ?? '';
        $trang_thai = $_POST['trang_thai'] ?? '';

        $ketQua = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_kiem_tra'])) {
            $ketQua = $this->bookCopyModel->kiemTraTrangThaiBanSao($id_ban_sao, $id_dau_sach, $ma_ban_sao, $trang_thai);
        }

        $this->renderView("bansao/kiemtra.php", [
            'id_ban_sao' => $id_ban_sao,
            'id_dau_sach' => $id_dau_sach,
            'ma_ban_sao' => $ma_ban_sao,
            'trang_thai' => $trang_thai,
            'ketQua' => $ketQua
        ]);
    }
}
