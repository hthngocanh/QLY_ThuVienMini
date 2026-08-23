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