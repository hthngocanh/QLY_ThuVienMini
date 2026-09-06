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

    /**
     * Sinh CSRF token riêng cho module Bản sao và lưu trong session.
     */
    private function getCsrfToken()
    {
        if (empty($_SESSION['csrf_token_bansao'])) {
            $_SESSION['csrf_token_bansao'] = bin2hex(random_bytes(32));
        }

        return (string)$_SESSION['csrf_token_bansao'];
    }

    /**
     * Xác minh CSRF token bằng hash_equals để tránh so sánh không an toàn.
     */
    private function csrfTokenHopLe($token)
    {
        $tokenTrongSession = (string)($_SESSION['csrf_token_bansao'] ?? '');
        $tokenGuiLen = is_string($token) ? $token : '';

        return $tokenTrongSession !== ''
            && $tokenGuiLen !== ''
            && hash_equals($tokenTrongSession, $tokenGuiLen);
    }

    /**
     * Trả dữ liệu JSON và kết thúc request.
     */
    private function traJson($data, $statusCode = 200)
    {
        http_response_code((int)$statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function index()
    {
        // ================= PHÂN QUYỀN =================
        $vaiTroHienTai = $_SESSION["user"]["vai_tro"] ?? "";
        $duocQuanLyBanSao = in_array($vaiTroHienTai, ["Thủ thư", "Quản trị viên"], true);
        $laQuanTriVien = ($vaiTroHienTai === "Quản trị viên");
        $laDocGia = ($vaiTroHienTai === "Độc giả");

        // ================= CSRF =================
        $csrfToken = $this->getCsrfToken();
        $requestMethod = $_SERVER["REQUEST_METHOD"] ?? "GET";
        $postAction = $_POST["action"] ?? "";

        // Mọi thao tác thay đổi dữ liệu của module Bản sao đều phải có CSRF token hợp lệ.
        if (
            $requestMethod === "POST"
            && in_array($postAction, ["add", "update", "delete", "restore"], true)
            && !$this->csrfTokenHopLe($_POST["csrf_token"] ?? "")
        ) {
            $this->redirect("index.php?controller=bansao&error=csrf");
        }

        // ================= DỮ LIỆU FORM =================
        $bookId = "";
        $maBanSao = "";
        $viTri = "";
        $trangThai = "Có sẵn";
        $editId = "";
        $trangThaiPhieuDangSua = "";

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
            } elseif ($_GET["error"] === "active-slip") {
                $thongBaoLoi = "Không thể xóa vì bản sao đang có phiếu Chờ duyệt, Đang mượn hoặc Quá hạn.";
            } elseif ($_GET["error"] === "delete") {
                $thongBaoLoi = "Không thể xóa bản sao sách.";
            } elseif ($_GET["error"] === "restore") {
                $thongBaoLoi = "Không thể khôi phục bản sao sách.";
            } elseif ($_GET["error"] === "forbidden") {
                $thongBaoLoi = "Bạn không có quyền thực hiện thao tác này.";
            } elseif ($_GET["error"] === "notfound") {
                $thongBaoLoi = "Không tìm thấy bản sao sách cần thao tác.";
            } elseif ($_GET["error"] === "csrf") {
                $thongBaoLoi = "Yêu cầu không hợp lệ hoặc phiên làm việc đã thay đổi. Vui lòng tải lại trang và thử lại.";
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

                $trangThaiPhieuCanXoa = (string)($banSaoCanXoa["trang_thai_phieu"] ?? "");
                if (in_array($trangThaiPhieuCanXoa, ["Chờ duyệt", "Đang mượn", "Quá hạn"], true)) {
                    $this->redirect("index.php?controller=bansao&error=active-slip");
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
                    $trangThaiPhieuDangSua = (string)($banSaoSua["trang_thai_phieu"] ?? "");
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

            // Khi cập nhật phải lấy lại dữ liệu thật từ DB.
            // Không tin giá trị hidden/select gửi từ trình duyệt vì có thể bị sửa bằng DevTools.
            $banSaoHienTai = null;
            if ($action === "update") {
                if (!ctype_digit($editId)) {
                    $thongBaoLoi = "ID bản sao không hợp lệ.";
                } else {
                    $banSaoHienTai = $this->bookCopyModel->layBanSaoTheoId($editId);
                    if (!$banSaoHienTai) {
                        $thongBaoLoi = "Không tìm thấy bản sao đang hoạt động để cập nhật.";
                    }
                }
            }

            // Nếu bản sao đang có phiếu Chờ duyệt / Đang mượn / Quá hạn thì
            // đầu sách và trạng thái vật lý phải do luồng Phiếu mượn kiểm soát.
            // Vẫn cho sửa mã bản sao và vị trí vì phiếu mượn liên kết bằng ID bản sao.
            if ($banSaoHienTai) {
                $trangThaiPhieuDangSua = (string)($banSaoHienTai["trang_thai_phieu"] ?? "");
                $coPhieuDangHieuLuc = in_array(
                    $trangThaiPhieuDangSua,
                    ["Chờ duyệt", "Đang mượn", "Quá hạn"],
                    true
                );

                if ($coPhieuDangHieuLuc || (($banSaoHienTai["trang_thai"] ?? "") === "Đang mượn")) {
                    $bookIdGoc = (string)($banSaoHienTai["book_id"] ?? "");
                    $trangThaiGoc = (string)($banSaoHienTai["trang_thai"] ?? "");

                    if ($bookId !== $bookIdGoc) {
                        $loiBookId = "Bản sao đang gắn với phiếu mượn hiệu lực nên không thể đổi sang đầu sách khác.";
                    }

                    if ($trangThai !== $trangThaiGoc) {
                        $loiTrangThai = "Trạng thái của bản sao đang được Phiếu mượn kiểm soát, không thể thay đổi thủ công.";
                    }

                    // Không tin dữ liệu POST: luôn dùng lại đầu sách và trạng thái thật từ DB.
                    $bookId = $bookIdGoc;
                    $trangThai = $trangThaiGoc;
                }
            }

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

            if ($action === "add") {
                // Bản sao mới không được tạo trực tiếp ở trạng thái Đang mượn.
                // Đang mượn chỉ phát sinh khi Thủ thư duyệt Phiếu mượn.
                $trangThaiHopLe = ["Có sẵn", "Chưa có sẵn"];
                if (!in_array($trangThai, $trangThaiHopLe, true)) {
                    $loiTrangThai = "Bản sao mới chỉ được chọn Có sẵn hoặc Chưa có sẵn.";
                }
            } elseif ($action === "update" && $banSaoHienTai) {
                $coPhieuDangHieuLuc = in_array(
                    (string)($banSaoHienTai["trang_thai_phieu"] ?? ""),
                    ["Chờ duyệt", "Đang mượn", "Quá hạn"],
                    true
                );

                if (!$coPhieuDangHieuLuc && (($banSaoHienTai["trang_thai"] ?? "") !== "Đang mượn")) {
                    // Chỉ bản sao không bị phiếu giữ mới được chỉnh thủ công giữa 2 trạng thái này.
                    $trangThaiHopLe = ["Có sẵn", "Chưa có sẵn"];
                    if (!in_array($trangThai, $trangThaiHopLe, true)) {
                        $loiTrangThai = "Không thể đặt thủ công trạng thái Đang mượn. Trạng thái này do Phiếu mượn cập nhật.";
                    }
                }
            }

            if (
                $thongBaoLoi === ""
                && $loiBookId === ""
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
                        if (!$banSaoHienTai) {
                            throw new Exception("Không tìm thấy bản sao đang hoạt động để cập nhật.");
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
            'trangThaiPhieuDangSua' => $trangThaiPhieuDangSua,
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
            'csrfToken' => $csrfToken,
            'activePage' => 'bansao'
        ]);
    }

    /**
     * Endpoint JSON: kiểm tra nhanh trạng thái bản sao theo đầu sách.
     * Được gọi bằng Fetch API từ giao diện Bản sao.
     */
    public function apiTrangThai()
    {
        if (empty($_SESSION['user'])) {
            $this->traJson([
                'ok' => false,
                'message' => 'Bạn cần đăng nhập để sử dụng chức năng này.'
            ], 401);
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
            $this->traJson([
                'ok' => false,
                'message' => 'Phương thức yêu cầu không hợp lệ.'
            ], 405);
        }

        $bookId = trim((string)($_GET['book_id'] ?? ''));
        if ($bookId === '' || !ctype_digit($bookId) || (int)$bookId <= 0) {
            $this->traJson([
                'ok' => false,
                'message' => 'Đầu sách không hợp lệ.'
            ], 422);
        }

        $data = $this->bookCopyModel->layTinhTrangMotDauSach((int)$bookId);
        if (!$data) {
            $this->traJson([
                'ok' => false,
                'message' => 'Không tìm thấy đầu sách.'
            ], 404);
        }

        $trangThai = (string)($data['trang_thai_ban_sao'] ?? 'Chưa có sẵn');
        $soBanCon = (int)($data['so_ban_con'] ?? 0);

        $this->traJson([
            'ok' => true,
            'book_id' => (int)($data['book_id'] ?? 0),
            'ma_sach' => (string)($data['ma_sach'] ?? ''),
            'ten_sach' => (string)($data['ten_sach'] ?? ''),
            'tong_ban' => (int)($data['tong_ban'] ?? 0),
            'so_ban_con' => $soBanCon,
            'so_ban_dang_muon' => (int)($data['so_ban_dang_muon'] ?? 0),
            'so_ban_chua_co_san' => (int)($data['so_ban_chua_co_san'] ?? 0),
            'trang_thai' => $trangThai,
            'co_the_muon' => $soBanCon > 0,
            'message' => $soBanCon > 0
                ? 'Đầu sách hiện còn bản sao có thể mượn.'
                : 'Hiện không có bản sao nào có thể mượn.'
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
