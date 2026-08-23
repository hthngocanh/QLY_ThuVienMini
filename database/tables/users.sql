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



-- query

SELECT *
FROM nguoi_dung;

SELECT *
FROM nguoi_dung
WHERE vai_tro = N'Độc giả';

SELECT *
FROM nguoi_dung
WHERE trang_thai = N'Bị khóa';


SELECT *
FROM nguoi_dung
WHERE khoa_lop LIKE N'%Công nghệ thông tin%';