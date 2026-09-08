-- =========================================================
-- DATABASE SCHEMA: qly_thuvienmini
-- Hệ thống Quản lý Thư viện Mini
-- Hệ Quản trị CSDL: MySQL / MariaDB (utf8mb4)
-- =========================================================

CREATE DATABASE IF NOT EXISTS `qly_thuvienmini`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `qly_thuvienmini`;

-- Tắt kiểm tra khóa ngoại khi khởi tạo lại bảng
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `password_reset_requests`;
DROP TABLE IF EXISTS `borrow_slips`;
DROP TABLE IF EXISTS `book_copies`;
DROP TABLE IF EXISTS `books`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `cau_hinh_han_muc`;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- 1. BẢNG `users` (Người dùng: Quản trị viên, Thủ thư, Độc giả)
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ma_nguoi_dung` VARCHAR(20) NOT NULL,
  `ho_ten` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `mat_khau` VARCHAR(255) NOT NULL,
  `sdt` VARCHAR(15) DEFAULT NULL,
  `khoa_lop` VARCHAR(100) DEFAULT NULL,
  `vai_tro` VARCHAR(20) NOT NULL,
  `trang_thai` VARCHAR(20) NOT NULL DEFAULT 'Hoạt động',
  `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_ma_nguoi_dung` (`ma_nguoi_dung`),
  UNIQUE KEY `uq_users_email` (`email`),
  CONSTRAINT `CK_users_vai_tro` CHECK (`vai_tro` IN ('Thủ thư', 'Độc giả', 'Quản trị viên')),
  CONSTRAINT `CK_users_trang_thai` CHECK (`trang_thai` IN ('Hoạt động', 'Bị khóa'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. BẢNG `categories` (Danh mục sách)
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `category_id` INT(11) NOT NULL AUTO_INCREMENT,
  `ten_danh_muc` VARCHAR(100) NOT NULL,
  `mo_ta` VARCHAR(255) DEFAULT NULL,
  `trang_thai` VARCHAR(20) NOT NULL DEFAULT 'Hoạt động',
  PRIMARY KEY (`category_id`),
  CONSTRAINT `CK_categories_trang_thai` CHECK (`trang_thai` IN ('Hoạt động', 'Ngừng hoạt động'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. BẢNG `books` (Thông tin đầu sách)
-- --------------------------------------------------------
CREATE TABLE `books` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ma_sach` VARCHAR(50) NOT NULL,
  `ten_sach` VARCHAR(255) NOT NULL,
  `ma_tac_gia` VARCHAR(50) NOT NULL,
  `tac_gia` VARCHAR(150) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `nha_xuat_ban` VARCHAR(150) DEFAULT NULL,
  `nam_xuat_ban` INT(11) DEFAULT NULL,
  `isbn` VARCHAR(20) NOT NULL,
  `gia_sach` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `mo_ta` VARCHAR(500) DEFAULT NULL,
  `trang_thai` VARCHAR(20) NOT NULL DEFAULT 'Hoạt động',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_books_ma_sach` (`ma_sach`),
  UNIQUE KEY `uq_books_isbn` (`isbn`),
  KEY `FK_Books_Categories` (`category_id`),
  CONSTRAINT `FK_Books_Categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE,
  CONSTRAINT `CK_books_gia_sach` CHECK (`gia_sach` >= 0),
  CONSTRAINT `CK_books_nam_xuat_ban` CHECK (`nam_xuat_ban` IS NULL OR `nam_xuat_ban` BETWEEN 1000 AND 2100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. BẢNG `book_copies` (Bản sao sách vật lý / cuốn sách cụ thể)
-- --------------------------------------------------------
CREATE TABLE `book_copies` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `book_id` INT(11) NOT NULL,
  `ma_ban_sao` VARCHAR(50) NOT NULL,
  `vi_tri` VARCHAR(100) DEFAULT NULL,
  `trang_thai` VARCHAR(30) NOT NULL DEFAULT 'Có sẵn',
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_book_copies_ma_ban_sao` (`ma_ban_sao`),
  KEY `FK_BookCopies_Books` (`book_id`),
  CONSTRAINT `FK_BookCopies_Books` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. BẢNG `borrow_slips` (Phiếu mượn - trả sách)
-- --------------------------------------------------------
CREATE TABLE `borrow_slips` (
  `ID_PhieuMuon` INT(11) NOT NULL AUTO_INCREMENT,
  `ID_NguoiDung` INT(11) NOT NULL,
  `ID_BanSao` INT(11) NOT NULL,
  `NgayMuon` DATE NOT NULL,
  `NgayTra` DATE DEFAULT NULL,
  `TrangThai` VARCHAR(20) NOT NULL,
  `DaXoa` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID_PhieuMuon`),
  KEY `FK_BorrowSlips_Users` (`ID_NguoiDung`),
  KEY `FK_BorrowSlips_BookCopies` (`ID_BanSao`),
  CONSTRAINT `FK_BorrowSlips_Users` FOREIGN KEY (`ID_NguoiDung`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_BorrowSlips_BookCopies` FOREIGN KEY (`ID_BanSao`) REFERENCES `book_copies` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `CK_BorrowSlips_TrangThai` CHECK (`TrangThai` IN ('Chờ duyệt', 'Đang mượn', 'Quá hạn', 'Đã trả'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. BẢNG `cau_hinh_han_muc` (Cấu hình quy định mượn sách)
-- --------------------------------------------------------
CREATE TABLE `cau_hinh_han_muc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `so_ngay_muon` INT(11) NOT NULL DEFAULT 14,
  `so_sach_toi_da` INT(11) NOT NULL DEFAULT 5,
  `cap_nhat_luc` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. BẢNG `password_reset_requests` (Yêu cầu đặt lại mật khẩu)
-- --------------------------------------------------------
CREATE TABLE `password_reset_requests` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ma_nguoi_dung` VARCHAR(20) NOT NULL,
  `ho_ten` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `mat_khau_moi` VARCHAR(255) NOT NULL,
  `trang_thai` VARCHAR(20) NOT NULL DEFAULT 'Chờ duyệt',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_ResetRequests_Users` (`ma_nguoi_dung`),
  CONSTRAINT `FK_ResetRequests_Users` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `users` (`ma_nguoi_dung`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CK_ResetRequests_TrangThai` CHECK (`trang_thai` IN ('Chờ duyệt', 'Đã duyệt', 'Đã từ chối'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;