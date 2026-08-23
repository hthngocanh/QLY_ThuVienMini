CREATE TABLE borrow_slips (
    ID_PhieuMuon INT IDENTITY(1,1) PRIMARY KEY,

    ID_NguoiDung INT NOT NULL,
    ID_BanSao INT NOT NULL,

    NgayMuon DATE NOT NULL,
    NgayTra DATE NULL,

    TrangThai NVARCHAR(20) NOT NULL
        CHECK (TrangThai IN (
            N'Chờ duyệt',
            N'Đang mượn',
            N'Quá hạn',
            N'Đã trả'
        )),

    FOREIGN KEY (ID_NguoiDung)
        REFERENCES nguoi_dung(id),

    FOREIGN KEY (ID_BanSao)
        REFERENCES book_copies(id)
);

