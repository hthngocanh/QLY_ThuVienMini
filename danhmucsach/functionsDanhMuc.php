<?php
require_once __DIR__ . '/../database/config/database.php';
// ======================================================
// LẤY TẤT CẢ DANH MỤC
// ======================================================

function layDanhSachDanhMuc()
{
    $pdo = getDB();

    $sql = "SELECT
                category_id,
                ten_danh_muc,
                mo_ta,
                trang_thai
            FROM Categories
            ORDER BY category_id DESC";

    $stmt = $pdo->query($sql);

    return $stmt->fetchAll();
}


// ======================================================
// LẤY DANH MỤC THEO ID
// ======================================================

function layDanhMucTheoId($categoryId)
{
    $pdo = getDB();

    $sql = "SELECT
                category_id,
                ten_danh_muc,
                mo_ta,
                trang_thai
            FROM Categories
            WHERE category_id = :category_id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'category_id' => $categoryId
    ]);

    return $stmt->fetch();
}


// ======================================================
// THÊM DANH MỤC
// ======================================================

function themDanhMuc($tenDanhMuc, $moTa, $trangThai)
{
    $pdo = getDB();

    $sql = "INSERT INTO Categories
                (ten_danh_muc, mo_ta, trang_thai)
            VALUES
                (:ten_danh_muc, :mo_ta, :trang_thai)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        'ten_danh_muc' => $tenDanhMuc,
        'mo_ta' => $moTa,
        'trang_thai' => $trangThai
    ]);
}


// ======================================================
// SỬA DANH MỤC
// ======================================================

function suaDanhMuc(
    $categoryId,
    $tenDanhMuc,
    $moTa,
    $trangThai
) {
    $pdo = getDB();

    $sql = "UPDATE Categories
            SET
                ten_danh_muc = :ten_danh_muc,
                mo_ta = :mo_ta,
                trang_thai = :trang_thai
            WHERE category_id = :category_id";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        'category_id' => $categoryId,
        'ten_danh_muc' => $tenDanhMuc,
        'mo_ta' => $moTa,
        'trang_thai' => $trangThai
    ]);
}


// ======================================================
// XÓA DANH MỤC
// ======================================================

function xoaDanhMuc($categoryId)
{
    $pdo = getDB();

    $sql = "DELETE FROM Categories
            WHERE category_id = :category_id";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        'category_id' => $categoryId
    ]);
}