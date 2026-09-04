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
        // ================= PHÂN QUYỀN =================
        $vaiTroHienTai = $_SESSION["user"]["vai_tro"] ?? "";
        $duocQuanLyBanSao = in_array($vaiTroHienTai, ["Thủ thư", "Quản trị viên"], true);
        $laQuanTriVien = ($vaiTroHienTai === "Quản trị viên");
        $laDocGia = ($vaiTroHienTai === "Độc giả");

        // ================= DỮ LIỆU FORM =================
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

        // ================= THÔNG BÁO =================
        if (isset($_GET["success"])) {
            if ($_GET["success"] === "add") {
                $thongBao = "Thêm bản sao sách thành công!";
            } elseif ($_GET["success"] === "update") {
                $thongBao = "Cập nhật bản sao sách thành công!";
            } elseif ($_GET["success"] === "delete") {
                $thongBao = "Xóa mềm bản sao sách thành công!";
            } elseif ($_GET["success"] === "restore") {
                $thongBao = "Khôi phục bản sao sách thành công!";
            }
        }

        if (isset($_GET["error"])) {
            if ($_GET["error"] === "borrowing") {
                $thongBaoLoi = "Không thể xóa vì bản sao hiện đang được mượn.";
            } elseif ($_GET["error"] === "delete") {
                $thongBaoLoi = "Không thể xóa bản sao sách.";
            } elseif ($_GET["error"] === "restore") {
                $thongBaoLoi = "Không thể khôi phục bản sao sách.";
            } elseif ($_GET["error"] === "forbidden") {
                $thongBaoLoi = "Bạn không có quyền thực hiện thao tác này.";
            } elseif ($_GET["error"] === "notfound") {
                $thongBaoLoi = "Không tìm thấy bản sao sách cần thao tác.";
            }
        }

        $danhSachDauSach = $this->bookCopyModel->layDanhSachDauSach();

        // ================= XÓA MỀM - CHỈ QUẢN TRỊ VIÊN =================
        if (
            ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST"
            && ($_POST["action"] ?? "") === "delete"
        ) {
            if (!$laQuanTriVien) {
                $this->redirect("index.php?controller=bansao&error=forbidden");
            }

            $deleteId = trim($_POST["delete_id"] ?? "");
            if (!ctype_digit($deleteId)) {
                $this->redirect("index.php?controller=bansao&error=delete");
            }

            try {
                $banSaoCanXoa = $this->bookCopyModel->layBanSaoTheoId($deleteId);

                if (!$banSaoCanXoa) {
                    $this->redirect("index.php?controller=bansao&error=notfound");
                }

                if (($banSaoCanXoa["trang_thai"] ?? "") === "Đang mượn") {
                    $this->redirect("index.php?controller=bansao&error=borrowing");
                }

                if (!$this->bookCopyModel->xoaBanSao($deleteId)) {
                    $this->redirect("index.php?controller=bansao&error=delete");
                }

                $this->redirect("index.php?controller=bansao&success=delete");
            } catch (PDOException $e) {
                $this->redirect("index.php?controller=bansao&error=delete");
            }
        }

        // ================= KHÔI PHỤC - CHỈ QUẢN TRỊ VIÊN =================
        if (
            ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST"
            && ($_POST["action"] ?? "") === "restore"
        ) {
            if (!$laQuanTriVien) {
                $this->redirect("index.php?controller=bansao&error=forbidden");
            }

            $restoreId = trim($_POST["restore_id"] ?? "");
            if (!ctype_digit($restoreId)) {
                $this->redirect("index.php?controller=bansao&error=restore");
            }

            try {
                if (!$this->bookCopyModel->khoiPhucBanSao($restoreId)) {
                    $this->redirect("index.php?controller=bansao&error=restore");
                }

                $this->redirect("index.php?controller=bansao&success=restore");
            } catch (PDOException $e) {
                $this->redirect("index.php?controller=bansao&error=restore");
            }
        }

        // ================= MỞ CHẾ ĐỘ SỬA =================
        if (
            ($_SERVER["REQUEST_METHOD"] ?? "GET") === "GET"
            && isset($_GET["edit"])
        ) {
            if (!$duocQuanLyBanSao) {
                $this->redirect("index.php?controller=bansao&error=forbidden");
            }

            $editId = trim($_GET["edit"]);

            if (!ctype_digit($editId)) {
                $editId = "";
                $thongBaoLoi = "ID bản sao cần sửa không hợp lệ.";
            } else {
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

        // ================= THÊM / CẬP NHẬT - THỦ THƯ + QUẢN TRỊ VIÊN =================
        if (
            ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST"
            && in_array($_POST["action"] ?? "", ["add", "update"], true)
        ) {
            if (!$duocQuanLyBanSao) {
                $this->redirect("index.php?controller=bansao&error=forbidden");
            }

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

            $trangThaiHopLe = ["Có sẵn", "Đang mượn", "Chưa có sẵn"];
            if (!in_array($trangThai, $trangThaiHopLe, true)) {
                $loiTrangThai = "Trạng thái không hợp lệ.";
            }

            if (
                $loiBookId === ""
                && $loiMaBanSao === ""
                && $loiViTri === ""
                && $loiTrangThai === ""
            ) {
                try {
                    if ($action === "add") {
                        $this->bookCopyModel->themBanSao($bookId, $maBanSao, $viTri, $trangThai);
                        $this->redirect("index.php?controller=bansao&success=add");
                    }

                    if ($action === "update") {
                        if (!ctype_digit($editId)) {
                            throw new Exception("ID bản sao không hợp lệ.");
                        }

                        if (!$this->bookCopyModel->suaBanSao($editId, $bookId, $maBanSao, $viTri, $trangThai)) {
                            throw new Exception("Không tìm thấy bản sao đang hoạt động để cập nhật.");
                        }

                        $this->redirect("index.php?controller=bansao&success=update");
                    }
                } catch (PDOException $e) {
                    if (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062) {
                        $loiMaBanSao = "Mã bản sao đã tồn tại.";
                    } else {
                        $thongBaoLoi = "Không thể lưu bản sao sách.";
                    }
                } catch (Exception $e) {
                    $thongBaoLoi = $e->getMessage();
                }
            }
        }

        // ================= DỮ LIỆU HIỂN THỊ =================
        $danhSachBanSao = $this->bookCopyModel->layDanhSachBanSao();
        $danhSachBanSaoDaXoa = $laQuanTriVien
            ? $this->bookCopyModel->layDanhSachBanSaoDaXoa()
            : [];
        $danhSachTraCuu = $this->bookCopyModel->layTinhTrangDauSach();
        $thongKeBanSao = $laQuanTriVien
            ? $this->bookCopyModel->thongKeBanSao()
            : [];

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
            'danhSachBanSaoDaXoa' => $danhSachBanSaoDaXoa,
            'danhSachTraCuu' => $danhSachTraCuu,
            'thongKeBanSao' => $thongKeBanSao,
            'vaiTroHienTai' => $vaiTroHienTai,
            'duocQuanLyBanSao' => $duocQuanLyBanSao,
            'laQuanTriVien' => $laQuanTriVien,
            'laDocGia' => $laDocGia,
            'activePage' => 'bansao'
        ]);
    }

    /**
     * Giữ lại action cũ để không làm hỏng route hiện tại.
     */
    public function kiemTra()
    {
        $id_ban_sao = $_POST['id_ban_sao'] ?? '';
        $id_dau_sach = $_POST['id_dau_sach'] ?? '';
        $ma_ban_sao = $_POST['ma_ban_sao'] ?? '';
        $trang_thai = $_POST['trang_thai'] ?? '';

        $ketQua = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btn_kiem_tra'])) {
            $ketQua = $this->bookCopyModel->kiemTraTrangThaiBanSao(
                $id_ban_sao,
                $id_dau_sach,
                $ma_ban_sao,
                $trang_thai
            );
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
