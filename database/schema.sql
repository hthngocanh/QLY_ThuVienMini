
-- =========================================
-- 1. NGUOI_DUNG
-- =========================================

CREATE TABLE nguoi_dung (
    id INT IDENTITY(1,1) PRIMARY KEY,
    ma_nguoi_dung VARCHAR(20) NOT NULL UNIQUE,
    ho_ten NVARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mat_khau VARCHAR(255) NOT NULL,
    sdt VARCHAR(15) NULL,
    khoa_lop NVARCHAR(100) NULL,
    vai_tro VARCHAR(20) NOT NULL,
    trang_thai NVARCHAR(20) NOT NULL DEFAULT N'Hoạt động',
    ngay_tao DATETIME2 NOT NULL DEFAULT GETDATE(),

    CONSTRAINT CK_nguoi_dung_vai_tro
        CHECK (vai_tro IN (N'Thủ thư', N'Độc giả', N'Quản trị viên')),

    CONSTRAINT CK_nguoi_dung_trang_thai
        CHECK (trang_thai IN (N'Hoạt động', N'Bị khóa'))
);


-- =========================================
-- 2. CATEGORIES
-- =========================================

CREATE TABLE Categories (
    category_id INT IDENTITY(1,1) PRIMARY KEY,

    ten_danh_muc NVARCHAR(100) NOT NULL,

    mo_ta NVARCHAR(255) NULL,

    trang_thai NVARCHAR(20) NOT NULL
        DEFAULT N'Hoạt động',

    CONSTRAINT CK_Categories_TrangThai
        CHECK (
            trang_thai IN (
                N'Hoạt động',
                N'Ngừng hoạt động'
            )
        )
);


-- =========================================
-- 3. BOOKS
-- =========================================

CREATE TABLE books (
    id INT IDENTITY(1,1) PRIMARY KEY,

    ma_sach VARCHAR(50) NOT NULL UNIQUE,

    ten_sach NVARCHAR(255) NOT NULL,

    ma_tac_gia VARCHAR(50) NOT NULL,

    tac_gia NVARCHAR(150) NOT NULL,

    category_id INT NOT NULL,

    nha_xuat_ban NVARCHAR(150) NULL,

    nam_xuat_ban INT NULL,

    isbn VARCHAR(20) NOT NULL UNIQUE,

    gia_sach DECIMAL(12,2) NOT NULL
        DEFAULT 0,

    mo_ta NVARCHAR(500) NULL,

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
    id INT IDENTITY(1,1) PRIMARY KEY,

    book_id INT NOT NULL,

    ma_ban_sao VARCHAR(50) UNIQUE NOT NULL,

    vi_tri NVARCHAR(100),

    trang_thai NVARCHAR(30) NOT NULL
        DEFAULT N'Có sẵn',

    FOREIGN KEY (book_id)
        REFERENCES books(id)
);
-- =========================================
CREATE TABLE borrow_slips (
    ID_PhieuMuon INT IDENTITY(1,1) PRIMARY KEY,

    ID_NguoiDung INT NOT NULL,

    ID_BanSao INT NOT NULL,

    NgayMuon DATE NOT NULL,

    NgayTra DATE NULL,

    TrangThai NVARCHAR(20) NOT NULL,

    CONSTRAINT CK_BorrowSlips_TrangThai
        CHECK (
            TrangThai IN (
                N'Chờ duyệt',
                N'Đang mượn',
                N'Quá hạn',
                N'Đã trả'
            )
        ),

    CONSTRAINT FK_BorrowSlips_Users
        FOREIGN KEY (ID_NguoiDung)
        REFERENCES nguoi_dung(id),

    CONSTRAINT FK_BorrowSlips_BookCopies
        FOREIGN KEY (ID_BanSao)
        REFERENCES book_copies(id)
);
GO