-- =========================================
-- SEED DATA - QLThuVien
-- =========================================


-- =========================================
-- 1. NGUOI_DUNG
-- =========================================

INSERT INTO nguoi_dung
    (
        ma_nguoi_dung,
        ho_ten,
        email,
        mat_khau,
        sdt,
        khoa_lop,
        vai_tro,
        trang_thai
    )
VALUES
    (
        'SV001',
        N'Nguyễn Văn An',
        'an@gmail.com',
        '123456',
        '0912345678',
        N'Công nghệ thông tin - K68',
        N'Độc giả',
        N'Hoạt động'
    ),

    (
        'SV002',
        N'Trần Minh Anh',
        'manh@gmail.com',
        '123456',
        '0923456789',
        N'Kinh tế - K68',
        N'Độc giả',
        N'Hoạt động'
    ),

    (
        'SV003',
        N'Lê Minh Đức',
        'duc@gmail.com',
        '123456',
        '0934567890',
        N'Công nghệ thông tin - K67',
        N'Độc giả',
        N'Bị khóa'
    ),

    (
        'TT001',
        N'Phạm Kim Oanh',
        'oanh@gmail.com',
        '123456',
        '0945678901',
        NULL,
        N'Thủ thư',
        N'Hoạt động'
    ),

    (
        'AD001',
        N'Hoàng Văn Nam',
        'nam@gmail.com',
        '123456',
        '0956789012',
        NULL,
        N'Quản trị viên',
        N'Hoạt động'
    );


-- =========================================
-- 2. CATEGORIES
-- =========================================

INSERT INTO Categories
    (
        ten_danh_muc,
        mo_ta,
        trang_thai
    )
VALUES
    (
        N'Công nghệ thông tin',
        N'Sách về máy tính và lập trình',
        N'Hoạt động'
    ),

    (
        N'Văn học',
        N'Sách văn học',
        N'Hoạt động'
    ),

    (
        N'Ngoại ngữ',
        N'Sách học ngoại ngữ',
        N'Hoạt động'
    ),

    (
        N'Kinh tế',
        N'Sách về kinh tế',
        N'Hoạt động'
    ),

    (
        N'Khoa học',
        N'Sách về khoa học',
        N'Hoạt động'
    );


-- =========================================
-- 3. BOOKS
-- =========================================

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


-- =========================================
-- 4. BOOK_COPIES
-- =========================================

INSERT INTO book_copies
    (
        book_id,
        ma_ban_sao,
        vi_tri,
        trang_thai
    )
VALUES
    (
        1,
        'BS001',
        N'Kệ A1',
        N'Có sẵn'
    ),

    (
        1,
        'BS002',
        N'Kệ A1',
        N'Đang mượn'
    ),

    (
        2,
        'BS003',
        N'Kệ B2',
        N'Có sẵn'
    ),

    (
        3,
        'BS004',
        N'Kệ C1',
        N'Hỏng'
    );


-- =========================================
-- 5. BORROW_SLIPS
-- =========================================

INSERT INTO borrow_slips
    (
        ID_NguoiDung,
        ID_BanSao,
        NgayMuon,
        NgayTra,
        TrangThai
    )
VALUES
    (
        1,
        1,
        '2026-08-18',
        NULL,
        N'Đang mượn'
    ),

    (
        2,
        2,
        '2026-08-15',
        '2026-08-20',
        N'Đã trả'
    ),

    (
        3,
        3,
        '2026-08-10',
        NULL,
        N'Quá hạn'
    ),

    (
        1,
        4,
        '2026-08-22',
        NULL,
        N'Chờ duyệt'
    );


GO