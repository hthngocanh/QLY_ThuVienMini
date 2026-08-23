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