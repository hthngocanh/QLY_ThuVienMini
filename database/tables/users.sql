CREATE TABLE users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    ma_nguoi_dung VARCHAR(20) NOT NULL UNIQUE,
    ho_ten VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mat_khau VARCHAR(255) NOT NULL,
    sdt VARCHAR(15),
    khoa_lop VARCHAR(100),
    role VARCHAR(20) NOT NULL,
    trang_thai VARCHAR(20) NOT NULL DEFAULT 'Hoạt động',
    created_at DATETIME2 NOT NULL DEFAULT GETDATE(),

    CONSTRAINT CK_users_role
        CHECK (role IN ('admin', 'thuthu', 'sinhvien')),

    CONSTRAINT CK_users_trang_thai
        CHECK (trang_thai IN ('Hoạt động', 'Bị khóa'))
);