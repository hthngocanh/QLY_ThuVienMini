<?php
// src/Model/BorrowSlipModel.php

require_once __DIR__ . '/../../database/config/database.php';

class ReaderBorrowModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function getAllPhieuMuon($idNguoiDung = null)
    {
        $sql = "
            SELECT
                bs.ID_PhieuMuon,
                bs.ID_NguoiDung,
                bs.ID_BanSao,
                nd.ma_nguoi_dung,
                nd.ho_ten,
                bc.ma_ban_sao,
                b.ten_sach,
                bs.NgayMuon,
                bs.NgayTra,
                bs.TrangThai
            FROM borrow_slips bs
            INNER JOIN users nd
                ON bs.ID_NguoiDung = nd.id
            INNER JOIN book_copies bc
                ON bs.ID_BanSao = bc.id
            INNER JOIN books b
                ON bc.book_id = b.id
            WHERE bs.DaXoa = 0
        ";

        $params = [];
        if ($idNguoiDung !== null) {
            $sql .= " AND bs.ID_NguoiDung = ? ";
            $params[] = (int)$idNguoiDung;
        }

        $sql .= " ORDER BY bs.ID_PhieuMuon DESC ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPhieuMuonById($id)
    {
        $sql = "
            SELECT
                bs.ID_PhieuMuon,
                bs.ID_NguoiDung,
                bs.ID_BanSao,
                nd.ma_nguoi_dung,
                nd.ho_ten,
                bc.ma_ban_sao,
                bc.book_id,
                bc.trang_thai AS TrangThaiBanSao,
                b.ten_sach,
                bs.NgayMuon,
                bs.NgayTra,
                bs.TrangThai
            FROM borrow_slips bs
            INNER JOIN users nd
                ON bs.ID_NguoiDung = nd.id
            INNER JOIN book_copies bc
                ON bs.ID_BanSao = bc.id
            INNER JOIN books b
                ON bc.book_id = b.id
            WHERE bs.ID_PhieuMuon = ?
              AND bs.DaXoa = 0
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getIdNguoiDungTheoMa($maNguoiDung)
    {
        $sql = "
            SELECT id
            FROM users
            WHERE ma_nguoi_dung = ?
              AND trang_thai = 'Hoạt động'
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$maNguoiDung]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['id'] : 0;
    }

    public function getIdBanSaoTheoMa($maBanSao)
    {
        $sql = "
            SELECT id
            FROM book_copies
            WHERE ma_ban_sao = ?
              AND deleted_at IS NULL
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$maBanSao]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['id'] : 0;
    }

    public function addPhieuMuon($idNguoiDung, $idBanSao, $ngayMuon, $ngayTra, $trangThai)
    {
        $sql = "
            INSERT INTO borrow_slips
            (
                ID_NguoiDung,
                ID_BanSao,
                NgayMuon,
                NgayTra,
                TrangThai
            )
            VALUES (?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            (int)$idNguoiDung,
            (int)$idBanSao,
            $ngayMuon,
            $ngayTra,
            $trangThai
        ]);
    }

    public function updatePhieuMuon($id, $idNguoiDung, $idBanSao, $ngayMuon, $ngayTra, $trangThai)
    {
        $sql = "
            UPDATE borrow_slips
            SET
                ID_NguoiDung = ?,
                ID_BanSao = ?,
                NgayMuon = ?,
                NgayTra = ?,
                TrangThai = ?
            WHERE ID_PhieuMuon = ?
              AND DaXoa = 0
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            (int)$idNguoiDung,
            (int)$idBanSao,
            $ngayMuon,
            $ngayTra,
            $trangThai,
            (int)$id
        ]);
    }

    /**
     * Không cho xóa phiếu đang mượn hoặc quá hạn vì sẽ làm mất liên kết trạng thái với bản sao.
     */
    public function deletePhieuMuon($id)
    {
        $sql = "
            UPDATE borrow_slips
            SET DaXoa = 1
            WHERE ID_PhieuMuon = ?
              AND DaXoa = 0
              AND TrangThai NOT IN ('Đang mượn', 'Quá hạn')
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Chỉ lấy các đầu sách mà độc giả đang có yêu cầu CHỜ DUYỆT.
     *
     * Trang chủ dùng dữ liệu này để hiện nút "Chờ duyệt" sau khi gửi yêu cầu.
     * Khi phiếu đã được duyệt thành "Đang mượn" hoặc "Quá hạn", việc có cho
     * mượn thêm hay không phải dựa vào bản sao còn rảnh và hạn mức mượn,
     * không chặn chỉ vì cùng một đầu sách.
     */
    public function getTrangThaiDauSachCuaNguoiDung($idNguoiDung)
    {
        $sql = "
            SELECT
                bc.book_id,
                bs.ID_PhieuMuon
            FROM borrow_slips bs
            INNER JOIN book_copies bc
                ON bc.id = bs.ID_BanSao
            WHERE bs.ID_NguoiDung = ?
              AND bs.DaXoa = 0
              AND bs.TrangThai = 'Chờ duyệt'
            ORDER BY bs.ID_PhieuMuon DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([(int)$idNguoiDung]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $row) {
            $bookId = (int)$row['book_id'];
            if (!isset($result[$bookId])) {
                $result[$bookId] = 'Chờ duyệt';
            }
        }

        return $result;
    }

    /**
     * Tạo yêu cầu mượn từ một ĐẦU SÁCH.
     * Hệ thống tự chọn một bản sao Có sẵn và khóa bản ghi trong transaction.
     */
    public function taoYeuCauMuonTheoDauSach($idNguoiDung, $bookId, $ngayMuon)
    {
        $idNguoiDung = (int)$idNguoiDung;
        $bookId = (int)$bookId;

        if ($idNguoiDung <= 0 || $bookId <= 0) {
            return ['success' => false, 'code' => 'invalid'];
        }

        try {
            $this->pdo->beginTransaction();

            // Không chặn chỉ vì độc giả đang mượn một bản khác của cùng đầu sách.
            // Quy tắc bắt buộc là: một BẢN SAO chỉ có tối đa một lượt mượn chưa trả.
            // Vì vậy hệ thống tìm một bản sao khác còn thực sự rảnh.

            $stmtCopy = $this->pdo->prepare("
                SELECT
                    bc.id,
                    bc.ma_ban_sao
                FROM book_copies bc
                WHERE bc.book_id = :book_id
                  AND bc.trang_thai = 'Có sẵn'
                  AND bc.deleted_at IS NULL
                  AND NOT EXISTS (
                      SELECT 1
                      FROM borrow_slips bs
                      WHERE bs.ID_BanSao = bc.id
                        AND bs.DaXoa = 0
                        AND bs.TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')
                  )
                ORDER BY bc.id ASC
                LIMIT 1
                FOR UPDATE
            ");
            $stmtCopy->execute([':book_id' => $bookId]);
            $copy = $stmtCopy->fetch(PDO::FETCH_ASSOC);

            if (!$copy) {
                $this->pdo->rollBack();
                return ['success' => false, 'code' => 'unavailable'];
            }

            $stmtInsert = $this->pdo->prepare("
                INSERT INTO borrow_slips
                    (ID_NguoiDung, ID_BanSao, NgayMuon, NgayTra, TrangThai, DaXoa)
                VALUES
                    (:user_id, :copy_id, :ngay_muon, NULL, 'Chờ duyệt', 0)
            ");
            $stmtInsert->execute([
                ':user_id' => $idNguoiDung,
                ':copy_id' => (int)$copy['id'],
                ':ngay_muon' => $ngayMuon
            ]);

            $phieuId = (int)$this->pdo->lastInsertId();
            $this->pdo->commit();

            return [
                'success' => true,
                'code' => 'success',
                'phieu_id' => $phieuId,
                'ban_sao_id' => (int)$copy['id'],
                'ma_ban_sao' => $copy['ma_ban_sao'] ?? ''
            ];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return ['success' => false, 'code' => 'error'];
        }
    }

    /**
     * Thủ thư/Quản trị viên duyệt một phiếu Chờ duyệt.
     * Cập nhật đồng thời phiếu và bản sao trong cùng transaction.
     */
    public function duyetPhieuMuon($idPhieuMuon)
    {
        $idPhieuMuon = (int)$idPhieuMuon;
        if ($idPhieuMuon <= 0) {
            return ['success' => false, 'code' => 'invalid'];
        }

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("
                SELECT
                    bs.ID_PhieuMuon,
                    bs.ID_BanSao,
                    bs.TrangThai,
                    bc.trang_thai AS TrangThaiBanSao,
                    bc.deleted_at
                FROM borrow_slips bs
                INNER JOIN book_copies bc
                    ON bc.id = bs.ID_BanSao
                WHERE bs.ID_PhieuMuon = :id
                  AND bs.DaXoa = 0
                LIMIT 1
                FOR UPDATE
            ");
            $stmt->execute([':id' => $idPhieuMuon]);
            $phieu = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$phieu) {
                $this->pdo->rollBack();
                return ['success' => false, 'code' => 'not_found'];
            }

            if (($phieu['TrangThai'] ?? '') !== 'Chờ duyệt') {
                $this->pdo->rollBack();
                return ['success' => false, 'code' => 'invalid_status'];
            }

            if (!empty($phieu['deleted_at']) || ($phieu['TrangThaiBanSao'] ?? '') !== 'Có sẵn') {
                $this->pdo->rollBack();
                return ['success' => false, 'code' => 'copy_unavailable'];
            }

            // Bảo vệ trường hợp dữ liệu cũ có một phiếu hoạt động khác cùng bản sao.
            $stmtConflict = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM borrow_slips
                WHERE ID_BanSao = :copy_id
                  AND ID_PhieuMuon <> :slip_id
                  AND DaXoa = 0
                  AND TrangThai IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn')
            ");
            $stmtConflict->execute([
                ':copy_id' => (int)$phieu['ID_BanSao'],
                ':slip_id' => $idPhieuMuon
            ]);

            if ((int)$stmtConflict->fetchColumn() > 0) {
                $this->pdo->rollBack();
                return ['success' => false, 'code' => 'copy_unavailable'];
            }

            $stmtSlip = $this->pdo->prepare("
                UPDATE borrow_slips
                SET TrangThai = 'Đang mượn'
                WHERE ID_PhieuMuon = :id
                  AND DaXoa = 0
                  AND TrangThai = 'Chờ duyệt'
            ");
            $stmtSlip->execute([':id' => $idPhieuMuon]);

            $stmtCopy = $this->pdo->prepare("
                UPDATE book_copies
                SET trang_thai = 'Đang mượn'
                WHERE id = :copy_id
                  AND deleted_at IS NULL
                  AND trang_thai = 'Có sẵn'
            ");
            $stmtCopy->execute([':copy_id' => (int)$phieu['ID_BanSao']]);

            if ($stmtSlip->rowCount() !== 1 || $stmtCopy->rowCount() !== 1) {
                $this->pdo->rollBack();
                return ['success' => false, 'code' => 'update_failed'];
            }

            $this->pdo->commit();
            return ['success' => true, 'code' => 'approved'];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return ['success' => false, 'code' => 'error'];
        }
    }

    /**
     * Xác nhận độc giả đã trả sách.
     * Phiếu -> Đã trả, NgayTra -> hôm nay, bản sao -> Có sẵn.
     */
    public function xacNhanTraSach($idPhieuMuon)
    {
        $idPhieuMuon = (int)$idPhieuMuon;
        if ($idPhieuMuon <= 0) {
            return ['success' => false, 'code' => 'invalid'];
        }

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("
                SELECT
                    bs.ID_PhieuMuon,
                    bs.ID_BanSao,
                    bs.TrangThai
                FROM borrow_slips bs
                WHERE bs.ID_PhieuMuon = :id
                  AND bs.DaXoa = 0
                LIMIT 1
                FOR UPDATE
            ");
            $stmt->execute([':id' => $idPhieuMuon]);
            $phieu = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$phieu) {
                $this->pdo->rollBack();
                return ['success' => false, 'code' => 'not_found'];
            }

            if (!in_array($phieu['TrangThai'] ?? '', ['Đang mượn', 'Quá hạn'], true)) {
                $this->pdo->rollBack();
                return ['success' => false, 'code' => 'invalid_status'];
            }

            $stmtSlip = $this->pdo->prepare("
                UPDATE borrow_slips
                SET
                    TrangThai = 'Đã trả',
                    NgayTra = :ngay_tra
                WHERE ID_PhieuMuon = :id
                  AND DaXoa = 0
                  AND TrangThai IN ('Đang mượn', 'Quá hạn')
            ");
            $stmtSlip->execute([
                ':ngay_tra' => date('Y-m-d'),
                ':id' => $idPhieuMuon
            ]);

            $stmtCopy = $this->pdo->prepare("
                UPDATE book_copies
                SET trang_thai = 'Có sẵn'
                WHERE id = :copy_id
                  AND deleted_at IS NULL
            ");
            $stmtCopy->execute([':copy_id' => (int)$phieu['ID_BanSao']]);

            if ($stmtSlip->rowCount() !== 1) {
                $this->pdo->rollBack();
                return ['success' => false, 'code' => 'update_failed'];
            }

            $this->pdo->commit();
            return ['success' => true, 'code' => 'returned'];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return ['success' => false, 'code' => 'error'];
        }
    }
}
