<?php

require_once __DIR__ . '/../../database/config/database.php';

class ReaderHomeModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
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
}