-- CÂU 1: Hiển thị danh sách bản sao kèm tên sách
SELECT 
    bc.ma_ban_sao,
    b.ten_sach,
    bc.vi_tri,
    bc.trang_thai
FROM book_copies bc
JOIN books b ON bc.book_id = b.id;


-- CÂU 2: Tìm các bản sao đang có sẵn
SELECT
    ma_ban_sao,
    vi_tri,
    trang_thai
FROM book_copies
WHERE trang_thai = N'Có sẵn';


-- CÂU 3: Tìm các bản sao của sách Lập trình PHP căn bản
SELECT
    bc.ma_ban_sao,
    b.ten_sach,
    bc.vi_tri,
    bc.trang_thai
FROM book_copies bc
JOIN books b ON bc.book_id = b.id
WHERE b.ten_sach = N'Lập trình PHP căn bản';