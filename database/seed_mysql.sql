-- =========================================
-- SEED DATA - QLThuVien
-- MYSQL VERSION
-- =========================================

USE qly_thuvienmini;


-- =========================================
-- 1. NGUOI_DUNG
-- =========================================
-- Mật khẩu mẫu của tất cả tài khoản:
-- 123456
--
-- Mật khẩu đã được mã hóa bằng password_hash()
-- để phù hợp với yêu cầu bảo mật của bài.


INSERT INTO users
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
    'Nguyễn Văn An',
    'an@gmail.com',
    ''$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i'',
    '0912345678',
    'Công nghệ thông tin - K68',
    'Độc giả',
    'Hoạt động'
),

(
    'SV002',
    'Trần Minh Anh',
    'manh@gmail.com',
    ''$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i'',
    '0923456789',
    'Kinh tế - K68',
    'Độc giả',
    'Hoạt động'
),

(
    'SV003',
    'Lê Minh Đức',
    'duc@gmail.com',
    ''$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i'',
    '0934567890',
    'Công nghệ thông tin - K67',
    'Độc giả',
    'Bị khóa'
),

(
    'TT001',
    'Phạm Kim Oanh',
    'oanh@gmail.com',
    ''$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i'',
    '0945678901',
    NULL,
    'Thủ thư',
    'Hoạt động'
),

(
    'AD001',
    'Hoàng Văn Nam',
    'nam@gmail.com',
    ''$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i'',
    '0956789012',
    NULL,
    'Quản trị viên',
    'Hoạt động'
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
    'Công nghệ thông tin',
    'Sách về máy tính và lập trình',
    'Hoạt động'
),

(
    'Văn học',
    'Sách văn học',
    'Hoạt động'
),

(
    'Ngoại ngữ',
    'Sách học ngoại ngữ',
    'Hoạt động'
),

(
    'Kinh tế',
    'Sách về kinh tế',
    'Hoạt động'
),

(
    'Khoa học',
    'Sách về khoa học',
    'Hoạt động'
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
    'Clean Code',
    'TG001',
    'Robert C. Martin',
    1,
    'Prentice Hall',
    2008,
    '9780132350884',
    250000,
    'Sách về lập trình và cách viết mã nguồn sạch.'
),

(
    'S002',
    'Dế Mèn Phiêu Lưu Ký',
    'TG002',
    'Tô Hoài',
    2,
    'Nhà xuất bản Kim Đồng',
    1941,
    '9786042192575',
    65000,
    'Tác phẩm văn học thiếu nhi nổi tiếng của Tô Hoài.'
),

(
    'S003',
    'English Grammar in Use',
    'TG003',
    'Raymond Murphy',
    3,
    'Cambridge University Press',
    2019,
    '9781108457651',
    180000,
    'Sách học ngữ pháp tiếng Anh.'
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
    'Kệ A1',
    'Có sẵn'
),

(
    1,
    'BS002',
    'Kệ A1',
    'Đang mượn'
),

(
    2,
    'BS003',
    'Kệ B2',
    'Có sẵn'
),

(
    3,
    'BS004',
    'Kệ C1',
    'Hỏng'
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
    'Đang mượn'
),

(
    2,
    2,
    '2026-08-15',
    '2026-08-20',
    'Đã trả'
),

(
    3,
    3,
    '2026-08-10',
    NULL,
    'Quá hạn'
),

(
    1,
    4,
    '2026-08-22',
    NULL,
    'Chờ duyệt'
);