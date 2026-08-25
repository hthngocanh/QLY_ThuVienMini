CREATE DATABASE IF NOT EXISTS QLThuVien
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE QLThuVien;


-- =========================================
-- 1. NGUOI_DUNG
-- =========================================

CREATE TABLE nguoi_dung (
    id INT AUTO_INCREMENT PRIMARY KEY,

    ma_nguoi_dung VARCHAR(20) NOT NULL UNIQUE,

    ho_ten VARCHAR(100) NOT NULL,

    email VARCHAR(255) NOT NULL UNIQUE,

    mat_khau VARCHAR(255) NOT NULL,

    sdt VARCHAR(15) NULL,

    khoa_lop VARCHAR(100) NULL,

    vai_tro VARCHAR(20) NOT NULL,

    trang_thai VARCHAR(20) NOT NULL DEFAULT 'Hoạt động',

    ngay_tao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT CK_nguoi_dung_vai_tro
        CHECK (
            vai_tro IN (
                'Thủ thư',
                'Độc giả',
                'Quản trị viên'
            )
        ),

    CONSTRAINT CK_nguoi_dung_trang_thai
        CHECK (
            trang_thai IN (
                'Hoạt động',
                'Bị khóa'
            )
        )
);


-- =========================================
-- 2. CATEGORIES
-- =========================================

CREATE TABLE Categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,

    ten_danh_muc VARCHAR(100) NOT NULL,

    mo_ta VARCHAR(255) NULL,

    trang_thai VARCHAR(20) NOT NULL
        DEFAULT 'Hoạt động',

    CONSTRAINT CK_Categories_TrangThai
        CHECK (
            trang_thai IN (
                'Hoạt động',
                'Ngừng hoạt động'
            )
        )
);


-- =========================================
-- 3. BOOKS
-- =========================================

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,

    ma_sach VARCHAR(50) NOT NULL UNIQUE,

    ten_sach VARCHAR(255) NOT NULL,

    ma_tac_gia VARCHAR(50) NOT NULL,

    tac_gia VARCHAR(150) NOT NULL,

    category_id INT NOT NULL,

    nha_xuat_ban VARCHAR(150) NULL,

    nam_xuat_ban INT NULL,

    isbn VARCHAR(20) NOT NULL UNIQUE,

    gia_sach DECIMAL(12,2) NOT NULL DEFAULT 0,

    mo_ta VARCHAR(500) NULL,

    CONSTRAINT FK_Books_Categories
        FOREIGN KEY (category_id)
        REFERENCES Categories(category_id),

    CONSTRAINT CK_Books_GiaSach
        CHECK (gia_sach >= 0),

    CONSTRAINT CK_Books_NamXuatBan
        CHECK (
            nam_xuat_ban IS NULL
            OR nam_xuat_ban BETWEEN 1000 AND 2100
        )
);


-- =========================================
-- 4. BOOK_COPIES
-- =========================================

CREATE TABLE book_copies (
    id INT AUTO_INCREMENT PRIMARY KEY,

    book_id INT NOT NULL,

    ma_ban_sao VARCHAR(50) NOT NULL UNIQUE,

    vi_tri VARCHAR(100),

    trang_thai VARCHAR(30) NOT NULL
        DEFAULT 'Có sẵn',

    CONSTRAINT FK_BookCopies_Books
        FOREIGN KEY (book_id)
        REFERENCES books(id)
);


-- =========================================
-- 5. BORROW_SLIPS
-- =========================================

CREATE TABLE borrow_slips (
    ID_PhieuMuon INT AUTO_INCREMENT PRIMARY KEY,

    ID_NguoiDung INT NOT NULL,

    ID_BanSao INT NOT NULL,

    NgayMuon DATE NOT NULL,

    NgayTra DATE NULL,

    TrangThai VARCHAR(20) NOT NULL,

    CONSTRAINT CK_BorrowSlips_TrangThai
        CHECK (
            TrangThai IN (
                'Chờ duyệt',
                'Đang mượn',
                'Quá hạn',
                'Đã trả'
            )
        ),

    CONSTRAINT FK_BorrowSlips_Users
        FOREIGN KEY (ID_NguoiDung)
        REFERENCES nguoi_dung(id),

    CONSTRAINT FK_BorrowSlips_BookCopies
        FOREIGN KEY (ID_BanSao)
        REFERENCES book_copies(id)
);