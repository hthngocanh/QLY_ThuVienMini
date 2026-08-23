CREATE TABLE users (
    id INT IDENTITY(1,1) PRIMARY KEY,

    ma_nguoi_dung VARCHAR(20) NOT NULL UNIQUE,
    ho_ten NVARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mat_khau VARCHAR(255) NOT NULL,
    sdt VARCHAR(15),
    khoa_lop NVARCHAR(100),

    vai_tro VARCHAR(20) NOT NULL,

    trang_thai NVARCHAR(20) NOT NULL DEFAULT N'Hoạt động',

    ngay_tao DATETIME2 NOT NULL DEFAULT GETDATE(),

    CONSTRAINT CK_users_vai_tro
        CHECK (vai_tro IN ('sinhvien', 'thuthu', 'admin')),

    CONSTRAINT CK_users_trang_thai
        CHECK (trang_thai IN (N'Hoạt động', N'Bị khóa'))
);