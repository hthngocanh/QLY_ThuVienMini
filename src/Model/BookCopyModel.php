<?php
// src/Model/BookCopyModel.php

require_once __DIR__ . '/../../database/config/database.php';

class BookCopyModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function layDanhSachDauSach()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, ma_sach, ten_sach
                FROM books
                ORDER BY ten_sach ASC
            ");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Danh sách bản sao đang hoạt động.
     * Bản sao đã xóa mềm (deleted_at IS NOT NULL) sẽ không xuất hiện.
     */
    public function layDanhSachBanSao()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    bc.id,
                    bc.book_id,
                    bc.ma_ban_sao,
                    bc.vi_tri,
                    bc.trang_thai,
                    b.ma_sach,
                    b.ten_sach,
                    (
                        SELECT bs.TrangThai
                        FROM borrow_slips bs
                        WHERE bs.ID_BanSao = bc.id
                          AND bs.DaXoa = 0
                          AND bs.TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')
                        ORDER BY
                            CASE bs.TrangThai
                                WHEN 'Quá hạn' THEN 3
                                WHEN 'Đang mượn' THEN 2
                                WHEN 'Chờ duyệt' THEN 1
                                ELSE 0
                            END DESC,
                            bs.ID_PhieuMuon DESC
                        LIMIT 1
                    ) AS trang_thai_phieu
                FROM book_copies bc
                INNER JOIN books b
                    ON bc.book_id = b.id
                WHERE bc.deleted_at IS NULL
                ORDER BY bc.id DESC
            ");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Danh sách bản sao đã xóa mềm - chỉ dùng cho Quản trị viên.
     */
    public function layDanhSachBanSaoDaXoa()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    bc.id,
                    bc.book_id,
                    bc.ma_ban_sao,
                    bc.vi_tri,
                    bc.trang_thai,
                    bc.deleted_at,
                    b.ma_sach,
                    b.ten_sach
                FROM book_copies bc
                INNER JOIN books b
                    ON bc.book_id = b.id
                WHERE bc.deleted_at IS NOT NULL
                ORDER BY bc.deleted_at DESC, bc.id DESC
            ");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Dữ liệu tra cứu dành cho Độc giả.
     * Không lộ ID bản sao riêng lẻ, chỉ tổng hợp số bản còn theo đầu sách.
     */
    public function layTinhTrangDauSach()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    b.id AS book_id,
                    b.ma_sach,
                    b.ten_sach,
                    b.tac_gia,
                    b.mo_ta,
                    c.ten_danh_muc AS danh_muc,

                    COUNT(bc.id) AS tong_ban,

                    SUM(
                        CASE
                            WHEN bc.id IS NOT NULL
                             AND bc.trang_thai = 'Có sẵn'
                             AND COALESCE(pm.bi_giu, 0) = 0
                            THEN 1 ELSE 0
                        END
                    ) AS so_ban_con,

                    SUM(
                        CASE
                            WHEN bc.id IS NOT NULL
                             AND (
                                    bc.trang_thai = 'Đang mượn'
                                    OR COALESCE(pm.dang_muon, 0) = 1
                                 )
                            THEN 1 ELSE 0
                        END
                    ) AS so_ban_dang_muon,

                    SUM(
                        CASE
                            WHEN bc.id IS NOT NULL
                             AND NOT (
                                    bc.trang_thai = 'Có sẵn'
                                    AND COALESCE(pm.bi_giu, 0) = 0
                                 )
                             AND NOT (
                                    bc.trang_thai = 'Đang mượn'
                                    OR COALESCE(pm.dang_muon, 0) = 1
                                 )
                            THEN 1 ELSE 0
                        END
                    ) AS so_ban_chua_co_san,

                    CASE
                        WHEN SUM(
                            CASE
                                WHEN bc.id IS NOT NULL
                                 AND bc.trang_thai = 'Có sẵn'
                                 AND COALESCE(pm.bi_giu, 0) = 0
                                THEN 1 ELSE 0
                            END
                        ) > 0
                        THEN 'Có sẵn'

                        WHEN SUM(
                            CASE
                                WHEN bc.id IS NOT NULL
                                 AND (
                                        bc.trang_thai = 'Đang mượn'
                                        OR COALESCE(pm.dang_muon, 0) = 1
                                     )
                                THEN 1 ELSE 0
                            END
                        ) > 0
                        THEN 'Đang mượn'

                        ELSE 'Chưa có sẵn'
                    END AS trang_thai_ban_sao

                FROM books b

                LEFT JOIN Categories c
                    ON c.category_id = b.category_id

                LEFT JOIN book_copies bc
                    ON bc.book_id = b.id
                    AND bc.deleted_at IS NULL

                LEFT JOIN (
                    SELECT
                        ID_BanSao,
                        MAX(
                            CASE
                                WHEN TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')
                                THEN 1 ELSE 0
                            END
                        ) AS bi_giu,
                        MAX(
                            CASE
                                WHEN TrangThai IN ('Đang mượn', 'Quá hạn')
                                THEN 1 ELSE 0
                            END
                        ) AS dang_muon
                    FROM borrow_slips
                    WHERE DaXoa = 0
                      AND TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')
                    GROUP BY ID_BanSao
                ) pm
                    ON pm.ID_BanSao = bc.id

                GROUP BY
                    b.id,
                    b.ma_sach,
                    b.ten_sach,
                    b.tac_gia,
                    b.mo_ta,
                    c.ten_danh_muc

                ORDER BY b.ten_sach ASC
            ");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function layBanSaoTheoId($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT
                bc.id,
                bc.book_id,
                bc.ma_ban_sao,
                bc.vi_tri,
                bc.trang_thai,
                bc.deleted_at,
                (
                    SELECT bs.TrangThai
                    FROM borrow_slips bs
                    WHERE bs.ID_BanSao = bc.id
                      AND bs.DaXoa = 0
                      AND bs.TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')
                    ORDER BY
                        CASE bs.TrangThai
                            WHEN 'Quá hạn' THEN 3
                            WHEN 'Đang mượn' THEN 2
                            WHEN 'Chờ duyệt' THEN 1
                            ELSE 0
                        END DESC,
                        bs.ID_PhieuMuon DESC
                    LIMIT 1
                ) AS trang_thai_phieu
            FROM book_copies bc
            WHERE bc.id = :id
              AND bc.deleted_at IS NULL
        ");

        $stmt->execute([":id" => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function themBanSao($bookId, $maBanSao, $viTri, $trangThai)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO book_copies
                (book_id, ma_ban_sao, vi_tri, trang_thai)
            VALUES
                (:book_id, :ma_ban_sao, :vi_tri, :trang_thai)
        ");

        return $stmt->execute([
            ":book_id" => (int)$bookId,
            ":ma_ban_sao" => $maBanSao,
            ":vi_tri" => $viTri,
            ":trang_thai" => $trangThai
        ]);
    }

    public function suaBanSao($id, $bookId, $maBanSao, $viTri, $trangThai)
    {
        $stmt = $this->pdo->prepare("
            UPDATE book_copies
            SET
                book_id = :book_id,
                ma_ban_sao = :ma_ban_sao,
                vi_tri = :vi_tri,
                trang_thai = :trang_thai
            WHERE id = :id
              AND deleted_at IS NULL
        ");

        return $stmt->execute([
            ":book_id" => (int)$bookId,
            ":ma_ban_sao" => $maBanSao,
            ":vi_tri" => $viTri,
            ":trang_thai" => $trangThai,
            ":id" => (int)$id
        ]);
    }

    /**
     * Xóa mềm: không DELETE dữ liệu.
     * Không cho xóa nếu bản sao đang có phiếu Chờ duyệt / Đang mượn / Quá hạn.
     * Điều kiện được đặt ngay trong SQL để DB vẫn tự bảo vệ nếu Controller bị bỏ qua.
     */
    public function xoaBanSao($id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE book_copies AS bc
            SET deleted_at = CURRENT_TIMESTAMP
            WHERE bc.id = :id
              AND bc.deleted_at IS NULL
              AND bc.trang_thai <> 'Đang mượn'
              AND NOT EXISTS (
                  SELECT 1
                  FROM borrow_slips bs
                  WHERE bs.ID_BanSao = bc.id
                    AND bs.DaXoa = 0
                    AND bs.TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')
              )
        ");

        $stmt->execute([":id" => (int)$id]);
        return $stmt->rowCount() > 0;
    }

    public function khoiPhucBanSao($id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE book_copies
            SET deleted_at = NULL
            WHERE id = :id
              AND deleted_at IS NOT NULL
        ");

        $stmt->execute([":id" => (int)$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Kiểm tra một bản sao có đang bị ràng buộc bởi phiếu mượn còn hiệu lực hay không.
     * Trả về Chờ duyệt / Đang mượn / Quá hạn, hoặc chuỗi rỗng nếu không có.
     */
    public function layTrangThaiPhieuHieuLucCuaBanSao($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT TrangThai
            FROM borrow_slips
            WHERE ID_BanSao = :id
              AND DaXoa = 0
              AND TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')
            ORDER BY
                CASE TrangThai
                    WHEN 'Quá hạn' THEN 3
                    WHEN 'Đang mượn' THEN 2
                    WHEN 'Chờ duyệt' THEN 1
                    ELSE 0
                END DESC,
                ID_PhieuMuon DESC
            LIMIT 1
        ");
        $stmt->execute([":id" => (int)$id]);
        $trangThai = $stmt->fetchColumn();
        return $trangThai !== false ? (string)$trangThai : "";
    }

    /**
     * Lấy trạng thái tổng hợp của một đầu sách để trả về JSON cho Fetch API.
     * Chỉ tính các bản sao chưa xóa mềm. Bản Có sẵn nhưng đang có phiếu
     * Chờ duyệt / Đang mượn / Quá hạn sẽ không được tính là có thể mượn.
     */
    public function layTinhTrangMotDauSach($bookId)
    {
        try {
            $stmt = $this->pdo->prepare("\n                SELECT\n                    b.id AS book_id,\n                    b.ma_sach,\n                    b.ten_sach,\n                    COUNT(bc.id) AS tong_ban,\n                    SUM(\n                        CASE\n                            WHEN bc.id IS NOT NULL\n                             AND bc.trang_thai = 'Có sẵn'\n                             AND NOT EXISTS (\n                                SELECT 1\n                                FROM borrow_slips bs1\n                                WHERE bs1.ID_BanSao = bc.id\n                                  AND bs1.DaXoa = 0\n                                  AND bs1.TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')\n                             )\n                            THEN 1 ELSE 0\n                        END\n                    ) AS so_ban_con,\n                    SUM(\n                        CASE\n                            WHEN bc.id IS NOT NULL\n                             AND (\n                                  bc.trang_thai = 'Đang mượn'\n                                  OR EXISTS (\n                                      SELECT 1\n                                      FROM borrow_slips bs2\n                                      WHERE bs2.ID_BanSao = bc.id\n                                        AND bs2.DaXoa = 0\n                                        AND bs2.TrangThai IN ('Đang mượn', 'Quá hạn')\n                                  )\n                             )\n                            THEN 1 ELSE 0\n                        END\n                    ) AS so_ban_dang_muon,\n                    SUM(\n                        CASE\n                            WHEN bc.id IS NOT NULL\n                             AND NOT (\n                                  bc.trang_thai = 'Có sẵn'\n                                  AND NOT EXISTS (\n                                      SELECT 1\n                                      FROM borrow_slips bs3\n                                      WHERE bs3.ID_BanSao = bc.id\n                                        AND bs3.DaXoa = 0\n                                        AND bs3.TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')\n                                  )\n                             )\n                             AND NOT (\n                                  bc.trang_thai = 'Đang mượn'\n                                  OR EXISTS (\n                                      SELECT 1\n                                      FROM borrow_slips bs4\n                                      WHERE bs4.ID_BanSao = bc.id\n                                        AND bs4.DaXoa = 0\n                                        AND bs4.TrangThai IN ('Đang mượn', 'Quá hạn')\n                                  )\n                             )\n                            THEN 1 ELSE 0\n                        END\n                    ) AS so_ban_chua_co_san,\n                    CASE\n                        WHEN SUM(\n                            CASE\n                                WHEN bc.id IS NOT NULL\n                                 AND bc.trang_thai = 'Có sẵn'\n                                 AND NOT EXISTS (\n                                    SELECT 1\n                                    FROM borrow_slips bs5\n                                    WHERE bs5.ID_BanSao = bc.id\n                                      AND bs5.DaXoa = 0\n                                      AND bs5.TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')\n                                 )\n                                THEN 1 ELSE 0\n                            END\n                        ) > 0 THEN 'Có sẵn'\n                        WHEN SUM(\n                            CASE\n                                WHEN bc.id IS NOT NULL\n                                 AND (\n                                      bc.trang_thai = 'Đang mượn'\n                                      OR EXISTS (\n                                          SELECT 1\n                                          FROM borrow_slips bs6\n                                          WHERE bs6.ID_BanSao = bc.id\n                                            AND bs6.DaXoa = 0\n                                            AND bs6.TrangThai IN ('Đang mượn', 'Quá hạn')\n                                      )\n                                 )\n                                THEN 1 ELSE 0\n                            END\n                        ) > 0 THEN 'Đang mượn'\n                        ELSE 'Chưa có sẵn'\n                    END AS trang_thai_ban_sao\n                FROM books b\n                LEFT JOIN book_copies bc\n                    ON bc.book_id = b.id\n                   AND bc.deleted_at IS NULL\n                WHERE b.id = :book_id\n                GROUP BY b.id, b.ma_sach, b.ten_sach\n                LIMIT 1\n            ");

            $stmt->execute([':book_id' => (int)$bookId]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ?: false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Thống kê bản sao theo khả năng sử dụng thực tế.
     *
     * - Có sẵn: book_copies = Có sẵn và không bị phiếu Chờ duyệt / Đang mượn / Quá hạn giữ.
     * - Đang mượn: trạng thái vật lý Đang mượn hoặc có phiếu Đang mượn / Quá hạn.
     * - Chưa có sẵn: các bản hoạt động còn lại, bao gồm bản Có sẵn nhưng đang Chờ duyệt.
     */
    public function thongKeBanSao()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    SUM(CASE
                        WHEN bc.deleted_at IS NULL
                        THEN 1 ELSE 0
                    END) AS tong_hoat_dong,

                    SUM(CASE
                        WHEN bc.deleted_at IS NULL
                         AND bc.trang_thai = 'Có sẵn'
                         AND NOT EXISTS (
                            SELECT 1
                            FROM borrow_slips bs1
                            WHERE bs1.ID_BanSao = bc.id
                              AND bs1.DaXoa = 0
                              AND bs1.TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')
                         )
                        THEN 1 ELSE 0
                    END) AS co_san,

                    SUM(CASE
                        WHEN bc.deleted_at IS NULL
                         AND (
                              bc.trang_thai = 'Đang mượn'
                              OR EXISTS (
                                  SELECT 1
                                  FROM borrow_slips bs2
                                  WHERE bs2.ID_BanSao = bc.id
                                    AND bs2.DaXoa = 0
                                    AND bs2.TrangThai IN ('Đang mượn', 'Quá hạn')
                              )
                         )
                        THEN 1 ELSE 0
                    END) AS dang_muon,

                    SUM(CASE
                        WHEN bc.deleted_at IS NULL
                         AND NOT (
                              bc.trang_thai = 'Có sẵn'
                              AND NOT EXISTS (
                                  SELECT 1
                                  FROM borrow_slips bs3
                                  WHERE bs3.ID_BanSao = bc.id
                                    AND bs3.DaXoa = 0
                                    AND bs3.TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')
                              )
                         )
                         AND NOT (
                              bc.trang_thai = 'Đang mượn'
                              OR EXISTS (
                                  SELECT 1
                                  FROM borrow_slips bs4
                                  WHERE bs4.ID_BanSao = bc.id
                                    AND bs4.DaXoa = 0
                                    AND bs4.TrangThai IN ('Đang mượn', 'Quá hạn')
                              )
                         )
                        THEN 1 ELSE 0
                    END) AS chua_co_san,

                    SUM(CASE
                        WHEN bc.deleted_at IS NOT NULL
                        THEN 1 ELSE 0
                    END) AS da_xoa

                FROM book_copies bc
            ");
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

            return [
                'tong_hoat_dong' => (int)($data['tong_hoat_dong'] ?? 0),
                'co_san' => (int)($data['co_san'] ?? 0),
                'dang_muon' => (int)($data['dang_muon'] ?? 0),
                'chua_co_san' => (int)($data['chua_co_san'] ?? 0),
                'da_xoa' => (int)($data['da_xoa'] ?? 0),
            ];
        } catch (PDOException $e) {
            return [
                'tong_hoat_dong' => 0,
                'co_san' => 0,
                'dang_muon' => 0,
                'chua_co_san' => 0,
                'da_xoa' => 0,
            ];
        }
    }

    /**
     * Lấy một bản sao có thể dùng để tạo yêu cầu mượn cho một đầu sách.
     * Bản sao phải còn hoạt động, đang ở trạng thái Có sẵn và chưa nằm
     * trong phiếu Chờ duyệt / Đang mượn / Quá hạn.
     */
    public function layBanSaoCoTheMuon($bookId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    bc.id,
                    bc.book_id,
                    bc.ma_ban_sao,
                    bc.vi_tri,
                    bc.trang_thai
                FROM book_copies bc
                WHERE bc.book_id = :book_id
                  AND bc.trang_thai = 'Có sẵn'
                  AND bc.deleted_at IS NULL
                  AND NOT EXISTS (
                      SELECT 1
                      FROM borrow_slips bs
                      WHERE bs.ID_BanSao = bc.id
                        AND bs.TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')
                        AND bs.DaXoa = 0
                  )
                ORDER BY bc.id ASC
                LIMIT 1
            ");

            $stmt->execute([
                ':book_id' => (int)$bookId
            ]);

            $banSao = $stmt->fetch(PDO::FETCH_ASSOC);
            return $banSao ?: false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Giữ lại cho trang kiemtra.php cũ.
     * Chỉ tra cứu các bản sao chưa bị xóa mềm.
     */
    public function kiemTraTrangThaiBanSao($idBanSao, $idDauSach, $maBanSao, $trangThai)
    {
        $where = ["deleted_at IS NULL"];
        $params = [];

        if ($idBanSao !== "") {
            $where[] = "id = :id";
            $params["id"] = (int)$idBanSao;
        }

        if ($idDauSach !== "") {
            $where[] = "book_id = :book_id";
            $params["book_id"] = (int)$idDauSach;
        }

        if ($maBanSao !== "") {
            $where[] = "ma_ban_sao = :ma";
            $params["ma"] = $maBanSao;
        }

        if ($trangThai !== "") {
            $where[] = "trang_thai = :tt";
            $params["tt"] = $trangThai;
        }

        // Nếu người dùng không nhập tiêu chí nào ngoài điều kiện deleted_at,
        // không trả về toàn bộ dữ liệu.
        if (count($where) === 1) {
            return [];
        }

        $sql = "SELECT * FROM book_copies WHERE " . implode(" AND ", $where);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
