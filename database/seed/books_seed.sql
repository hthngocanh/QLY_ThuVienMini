INSERT INTO books
    (
        ma_sach,
        ten_sach,
        ma_tac_gia,
        tac_gia,
        category_id,
        nha_xuat_ban,
        nam_xuat_ban,
        isbn,
        gia_sach,
        mo_ta
    )
VALUES
(
    'S001',
    N'Clean Code',
    'TG001',
    N'Robert C. Martin',
    1,
    N'Prentice Hall',
    2008,
    '9780132350884',
    250000,
    N'Sách về lập trình và cách viết mã nguồn sạch.'
),

(
    'S002',
    N'Dế Mèn Phiêu Lưu Ký',
    'TG002',
    N'Tô Hoài',
    2,
    N'Nhà xuất bản Kim Đồng',
    1941,
    '9786042192575',
    65000,
    N'Tác phẩm văn học thiếu nhi nổi tiếng của Tô Hoài.'
),

(
    'S003',
    N'English Grammar in Use',
    'TG003',
    N'Raymond Murphy',
    3,
    N'Cambridge University Press',
    2019,
    '9781108457651',
    180000,
    N'Sách học ngữ pháp tiếng Anh.'
);