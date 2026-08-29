CREATE TABLE users (
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
        CHECK (vai_tro IN ('Thủ thư', 'Độc giả', 'Quản trị viên')),

    CONSTRAINT CK_nguoi_dung_trang_thai
        CHECK (trang_thai IN ('Hoạt động', 'Bị khóa'))
);