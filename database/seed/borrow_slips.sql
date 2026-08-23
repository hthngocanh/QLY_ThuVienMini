INSERT INTO borrow_slips
    (ID_NguoiDung, ID_BanSao, NgayMuon, NgayTra, TrangThai)
VALUES
    (1, 1, '2026-08-18', NULL, N'Đang mượn'),

    (2, 2, '2026-08-15', '2026-08-20', N'Đã trả'),

    (3, 3, '2026-08-10', NULL, N'Quá hạn'),

    (1, 4, '2026-08-22', NULL, N'Chờ duyệt');
GO