
USE `qly_thuvienmini`;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE `password_reset_requests`;
TRUNCATE TABLE `borrow_slips`;
TRUNCATE TABLE `book_copies`;
TRUNCATE TABLE `books`;
TRUNCATE TABLE `categories`;
TRUNCATE TABLE `cau_hinh_han_muc`;
TRUNCATE TABLE `users`;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `categories` (`category_id`, `ten_danh_muc`, `mo_ta`, `trang_thai`) VALUES
(1, 'Công nghệ thông tin', 'Sách về máy tính và lập trình', 'Hoạt động'),
(2, 'Văn học', 'Sách văn học', 'Hoạt động'),
(3, 'Ngoại ngữ', 'Sách học ngoại ngữ', 'Hoạt động'),
(4, 'Kinh tế', 'Sách về kinh tế', 'Hoạt động'),
(5, 'Khoa học', 'Sách về khoa học', 'Hoạt động');

-- --------------------------------------------------------
-- 2. SEED `books` (Đầu sách)
-- --------------------------------------------------------
INSERT INTO `books` (`id`, `ma_sach`, `ten_sach`, `ma_tac_gia`, `tac_gia`, `category_id`, `nha_xuat_ban`, `nam_xuat_ban`, `isbn`, `gia_sach`, `mo_ta`, `trang_thai`) VALUES
(1, 'S001', 'Clean Code', 'TG001', 'Robert C. Martin', 1, 'Prentice Hall', 2008, '9780132350884', 250000.00, 'Sách về lập trình và cách viết mã nguồn sạch.', 'Hoạt động'),
(2, 'S002', 'Dế Mèn Phiêu Lưu Ký', 'TG002', 'Tô Hoài', 2, 'Nhà xuất bản Kim Đồng', 1941, '9786042192575', 65000.00, 'Tác phẩm văn học thiếu nhi nổi tiếng của Tô Hoài.', 'Hoạt động'),
(3, 'S003', 'English Grammar in Use', 'TG003', 'Raymond Murphy', 3, 'Cambridge University Press', 2019, '9781108457651', 180000.00, 'Sách học ngữ pháp tiếng Anh.', 'Hoạt động'),
(4, 'S004', 'Chí Phèo', 'TG004', 'Nam Cao', 2, 'Đời mới', 1941, '909183737464', 19983.00, 'Tái hiện bức tranh chân thực về nông thôn Việt Nam trước 1945, nghèo đói, xơ xác trên con đường phá sản, bần cùng, hết sức thê thảm. Người nông dân bị đẩy vào con đường tha hóa, lưu manh hóa.', 'Hoạt động'),
(5, 'S005', 'Bánh trôi nước', 'TG005', 'Hồ Xuân Hương', 2, 'Tác giả', 1914, '909183736987', 200000.00, 'Là tiếng lòng cảm thông cho thân phận chìm nổi và lời ngợi ca vẻ đẹp phẩm chất son sắt của người phụ nữ trong xã hội phong kiến', 'Hoạt động'),
(6, 'S006', 'Lão Hạc', 'TG004', 'Nam Cao', 2, 'Văn học', 1943, '909183739834', 250000.00, 'Phản ánh số phận đau thương của người nông dân nghèo khổ, bế tắc trong xã hội cũ và ca ngợi vẻ đẹp phẩm chất cao quý, lòng tự trọng của họ', 'Hoạt động');

-- --------------------------------------------------------
-- 3. SEED `book_copies` (Bản sao sách)
-- --------------------------------------------------------
INSERT INTO `book_copies` (`id`, `book_id`, `ma_ban_sao`, `vi_tri`, `trang_thai`, `deleted_at`) VALUES
(1, 1, 'BS001', 'Kệ A1', 'Có sẵn', NULL),
(2, 1, 'BS002', 'Kệ A1', 'Đang mượn', NULL),
(3, 2, 'BS003', 'Kệ B2', 'Đang mượn', NULL),
(4, 3, 'BS004', 'Kệ C1', 'Đang mượn', NULL),
(5, 1, 'BS005', 'Kệ A1', 'Có sẵn', NULL),
(8, 2, 'BS006', 'Kệ A1', 'Chưa có sẵn', NULL),
(11, 1, 'BS008', 'Kệ A3', 'Đang mượn', NULL),
(12, 2, 'BS009', 'Kệ A1', 'Đang mượn', NULL),
(13, 4, 'BS007', 'Kệ A3', 'Có sẵn', NULL),
(15, 1, 'BS000', 'Kệ A3', 'Chưa có sẵn', '2026-09-04 16:50:00'),
(17, 3, 'BS040', 'Kệ A3', 'Đang mượn', NULL),
(18, 3, 'BS088', 'Kệ C1', 'Có sẵn', NULL),
(19, 2, 'BS077', 'Kệ A1', 'Có sẵn', NULL),
(20, 4, 'BS090', 'Kệ A3', 'Có sẵn', NULL),
(21, 1, 'BS099', 'Kệ A3', 'Có sẵn', NULL),
(22, 3, 'BS089', 'Kệ A3', 'Có sẵn', NULL),
(23, 2, 'BS081', 'Kệ A3', 'Có sẵn', NULL),
(24, 4, 'BS078', 'Kệ A1', 'Có sẵn', NULL),
(25, 1, 'BS055', 'Kệ A3', 'Có sẵn', NULL),
(26, 2, 'BS073', 'Kệ A3', 'Có sẵn', NULL),
(27, 5, 'BS083', 'Kệ A3', 'Có sẵn', NULL),
(29, 5, 'BS066', 'Kệ A3', 'Có sẵn', NULL),
(30, 5, 'BS091', 'Kệ A3', 'Có sẵn', NULL),
(32, 5, 'BS087', 'Kệ A1', 'Có sẵn', NULL),
(34, 5, 'BS011', 'Kệ A1', 'Có sẵn', '2026-09-07 14:00:38'),
(36, 5, 'BS069', 'Kệ A1', 'Có sẵn', NULL);

-- --------------------------------------------------------
-- 4. SEED `users` (Người dùng)
-- Mật khẩu mẫu: Thuvien12345! (Bcrypt hash: $2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i)
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `ma_nguoi_dung`, `ho_ten`, `email`, `mat_khau`, `sdt`, `khoa_lop`, `vai_tro`, `trang_thai`, `ngay_tao`) VALUES
(1, 'SV001', 'Nguyễn Văn An', 'an@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0912345678', 'Công nghệ thông tin - K68', 'Độc giả', 'Hoạt động', '2026-09-01 14:08:48'),
(2, 'SV002', 'Trần Minh Anh', 'manh@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0923456789', 'Kinh tế - K68', 'Độc giả', 'Hoạt động', '2026-09-01 14:08:48'),
(3, 'SV003', 'Lê Minh Đức', 'duc@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0934567890', 'Công nghệ thông tin - K67', 'Độc giả', 'Bị khóa', '2026-09-01 14:08:48'),
(4, 'TT001', 'Phạm Kim Oanh', 'oanh@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0945678901', NULL, 'Thủ thư', 'Hoạt động', '2026-09-01 14:08:48'),
(5, 'AD001', 'Hoàng Văn Nam', 'nam@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0956789012', NULL, 'Quản trị viên', 'Hoạt động', '2026-09-01 14:08:48'),
(6, 'SV016', 'Nguyễn Minh Anh', 'sv016@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000016', 'Công nghệ thông tin - K68', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(7, 'SV017', 'Trần Quốc Huy', 'sv017@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000017', 'Kinh tế - K68', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(8, 'SV018', 'Lê Thu Trang', 'sv018@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000018', 'Công nghệ thông tin - K67', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(9, 'SV019', 'Phạm Hoàng Long', 'sv019@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000019', 'Công nghệ thông tin - K68', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(10, 'SV020', 'Đỗ Ngọc Mai', 'sv020@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000020', 'Kinh tế - K68', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(11, 'SV021', 'Vũ Đức Minh', 'sv021@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000021', 'Công nghệ thông tin - K67', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(12, 'SV022', 'Bùi Khánh Linh', 'sv022@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000022', 'Công nghệ thông tin - K68', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(13, 'SV023', 'Hoàng Đức Anh', 'sv023@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000023', 'Kinh tế - K67', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(14, 'SV024', 'Nguyễn Hà My', 'sv024@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000024', 'Công nghệ thông tin - K68', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(15, 'SV025', 'Trần Minh Khang', 'sv025@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000025', 'Công nghệ thông tin - K67', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(16, 'SV026', 'Phan Thùy Dương', 'sv026@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000026', 'Kinh tế - K68', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(17, 'SV027', 'Lý Gia Hân', 'sv027@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000027', 'Công nghệ thông tin - K68', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(18, 'SV028', 'Đặng Quốc Bảo', 'sv028@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000028', 'Công nghệ thông tin - K67', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(19, 'SV029', 'Nguyễn Phương Thảo', 'sv029@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000029', 'Kinh tế - K68', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(20, 'SV030', 'Phạm Tuấn Anh', 'sv030@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0901000030', 'Công nghệ thông tin - K68', 'Độc giả', 'Hoạt động', '2026-09-06 15:40:19'),
(21, 'TT003', 'Nguyễn Thị Hương', 'tt003@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0902000003', NULL, 'Thủ thư', 'Hoạt động', '2026-09-06 15:40:19'),
(22, 'TT004', 'Trần Văn Đức', 'tt004@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0902000004', NULL, 'Thủ thư', 'Hoạt động', '2026-09-06 15:40:19'),
(23, 'TT005', 'Lê Thị Mai', 'tt005@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0902000005', NULL, 'Thủ thư', 'Hoạt động', '2026-09-06 15:40:19'),
(24, 'TT006', 'Phạm Văn Nam', 'tt006@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0902000006', NULL, 'Thủ thư', 'Hoạt động', '2026-09-06 15:40:19'),
(25, 'TT007', 'Hoàng Thị Lan', 'tt007@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0902000007', NULL, 'Thủ thư', 'Hoạt động', '2026-09-06 15:40:19'),
(26, 'TT008', 'Vũ Minh Quân', 'tt008@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0902000008', NULL, 'Thủ thư', 'Hoạt động', '2026-09-06 15:40:19'),
(27, 'TT009', 'Đặng Thu Hà', 'tt009@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0902000009', NULL, 'Thủ thư', 'Hoạt động', '2026-09-06 15:40:19'),
(28, 'TT010', 'Bùi Quốc Khánh', 'tt010@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0902000010', NULL, 'Thủ thư', 'Hoạt động', '2026-09-06 15:40:19'),
(29, 'TT011', 'Đỗ Thị Ngọc', 'tt011@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0902000011', NULL, 'Thủ thư', 'Hoạt động', '2026-09-06 15:40:19'),
(30, 'TT012', 'Nguyễn Hoàng Nam', 'tt012@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0902000012', NULL, 'Thủ thư', 'Hoạt động', '2026-09-06 15:40:19'),
(31, 'AD002', 'Nguyễn Văn Hùng', 'admin002@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0903000002', NULL, 'Quản trị viên', 'Hoạt động', '2026-09-06 15:40:19'),
(32, 'AD003', 'Trần Thị Mai', 'admin003@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0903000003', NULL, 'Quản trị viên', 'Hoạt động', '2026-09-06 15:40:19'),
(33, 'AD004', 'Lê Minh Hoàng', 'admin004@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', '0903000004', NULL, 'Quản trị viên', 'Hoạt động', '2026-09-06 15:40:19');

-- --------------------------------------------------------
-- 5. SEED `cau_hinh_han_muc` (Hạn mức mặc định)
-- --------------------------------------------------------
INSERT INTO `cau_hinh_han_muc` (`id`, `so_ngay_muon`, `so_sach_toi_da`, `cap_nhat_luc`) VALUES
(1, 14, 5, NOW());

-- --------------------------------------------------------
-- 6. SEED `borrow_slips` (Phiếu mượn)
-- --------------------------------------------------------
INSERT INTO `borrow_slips` (`ID_PhieuMuon`, `ID_NguoiDung`, `ID_BanSao`, `NgayMuon`, `NgayTra`, `TrangThai`, `DaXoa`) VALUES
(1, 1, 1, '2026-08-18', '2026-09-05', 'Đã trả', 0),
(2, 2, 2, '2026-08-15', '2026-08-20', 'Đã trả', 0),
(5, 1, 3, '2026-09-04', NULL, 'Đang mượn', 0),
(7, 1, 12, '2026-09-04', NULL, 'Đang mượn', 0),
(9, 1, 17, '2026-09-05', NULL, 'Đang mượn', 0),
(17, 1, 13, '2026-09-06', NULL, 'Chờ duyệt', 0),
(18, 1, 13, '2026-09-06', NULL, 'Chờ duyệt', 0),
(19, 1, 1, '2026-09-06', NULL, 'Chờ duyệt', 0);

-- --------------------------------------------------------
-- 7. SEED `password_reset_requests` (Yêu cầu đặt lại mật khẩu)
-- --------------------------------------------------------
INSERT INTO `password_reset_requests` (`id`, `ma_nguoi_dung`, `ho_ten`, `email`, `mat_khau_moi`, `trang_thai`, `created_at`) VALUES
(1, 'TT001', 'Phạm Kim Oanh', 'oanh@gmail.com', '$2y$12$CBpY3D15LHKMqJtx4P5Yr.Uj1JCwJF8DMKxu0/3cVG5Z.mGDjop9i', 'Đã duyệt', '2026-09-03 16:26:06');