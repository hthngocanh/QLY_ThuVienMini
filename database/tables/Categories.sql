CREATE TABLE Categories (
    category_id INT IDENTITY(1,1) PRIMARY KEY,

    ten_danh_muc NVARCHAR(100) NOT NULL,

    mo_ta NVARCHAR(255) NULL,

    trang_thai NVARCHAR(20) NOT NULL
        DEFAULT N'Hoạt động',

    CONSTRAINT CK_Categories_TrangThai
        CHECK (trang_thai IN (N'Hoạt động', N'Ngừng hoạt động'))
);