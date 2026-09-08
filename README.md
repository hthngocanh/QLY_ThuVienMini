# Dự án Lập trình Web - Nhóm 02

## Hệ thống Quản lý Thư viện Mini

---

## 1. Hướng dẫn cài đặt và chạy dự án (Localhost)

### 1.1. Yêu cầu môi trường

- **Hệ điều hành:** Windows / macOS / Linux
- **Phần mềm máy chủ:** XAMPP (hoặc WampServer / Laragon) tích hợp **PHP >= 8.0** và **MySQL / MariaDB**.
- **Công cụ quản lý mã nguồn:** Git.

### 1.2. Các bước cài đặt

1. **Kéo mã nguồn về thư mục web:**
   - Di chuyển vào thư mục `htdocs` của XAMPP:
     ```bash
     cd /Applications/XAMPP/xamppfiles/htdocs  # trên macOS
     # hoặc
     cd C:\xampp\htdocs                       # trên Windows
     ```
   - Kéo mã nguồn từ GitHub:
     ```bash
     git clone https://github.com/hthngocanh/QLY_ThuVienMini.git
     ```

2. **Khởi tạo Cơ sở Dữ liệu (Database):**
   - Khởi động **Apache** và **MySQL** trên XAMPP Control Panel.
   - Truy cập vào **phpMyAdmin**: `http://localhost/phpmyadmin`
   - Tạo mới cơ sở dữ liệu có tên: `qly_thuvienmini` (Bảng mã: `utf8mb4_unicode_ci`).
   - Nhập (Import) 2 tệp script theo thứ tự:
     1. `database/schema_mysql.sql` (Khởi tạo toàn bộ cấu trúc bảng, khóa chính, khóa ngoại, ràng buộc `CHECK`).
     2. `database/seed_mysql.sql` (Nạp dữ liệu mẫu ban đầu).

3. **Khởi chạy ứng dụng:**
   - Mở trình duyệt web và truy cập:
     - **Trang chủ / Đăng nhập:** `http://localhost/QLY_ThuVienMini/`
     - **Trang giới thiệu đề tài & nhóm:** `http://localhost/QLY_ThuVienMini/index.php?controller=about`

### 1.3. Tài khoản kiểm thử mẫu (Mật khẩu chung: `Thuvien12345!`)

| Vai trò | Email đăng nhập | Mã người dùng | Mật khẩu mặc định |
| :--- | :--- | :--- | :--- |
| **Quản trị viên (Admin)** | `nam@gmail.com` | `AD001` | `Thuvien12345!` |
| **Thủ thư (Librarian)** | `oanh@gmail.com` | `TT001` | `Thuvien12345!` |
| **Độc giả (Reader)** | `an@gmail.com` | `SV001` | `Thuvien12345!` |

---

## 2. Thông tin Nhóm và Phân công nhiệm vụ

- **Tên đề tài:** Hệ thống Quản lý Thư viện Mini
- **Nhóm thực hiện:** Nhóm 02

| STT | Thành viên | Vai trò phụ trách | Phân công nhiệm vụ chi tiết |
| :---: | :--- | :--- | :--- |
| 1 | **Hoàng Thị Ngọc Ánh** (Trưởng nhóm) | Quản lý Người dùng & Xác thực | - Xây dựng module Đăng nhập, Đăng xuất, Đổi mật khẩu, Quên mật khẩu.<br>- Quản lý Người dùng: Danh sách, thêm/sửa, khóa/mở khóa tài khoản.<br>- Quản lý phân quyền (`Quản trị viên`, `Thủ thư`, `Độc giả`).<br>- Phê duyệt các yêu cầu cấp lại mật khẩu từ độc giả/thủ thư. |
| 2 | **Phạm Khánh Linh** | Quản lý Danh mục sách | - Xây dựng CRUD Danh mục sách (`categories`).<br>- Tìm kiếm, phân loại và quản lý trạng thái danh mục (`Hoạt động` / `Ngừng hoạt động`).<br>- Kiểm tra ràng buộc dữ liệu danh mục khi thêm/sửa. |
| 3 | **Nguyễn Thị Mỹ Hạnh** | Quản lý Phiếu mượn - Trả & Hạn mức | - Xây dựng quy trình Tạo phiếu mượn, Duyệt mượn, Trả sách, Quá hạn.<br>- Module Cấu hình hạn mức (Số ngày mượn tối đa, Số sách tối đa).<br>- Dashboard thống kê tình hình mượn trả và cảnh báo quá hạn. |
| 4 | **Nguyễn Khánh Linh** | Quản lý Đầu sách | - Xây dựng CRUD Đầu sách (`books`).<br>- Quản lý thông tin: Mã sách, Tên sách, Tác giả, Thể loại, NXB, Năm XB, ISBN, Giá sách, Mô tả.<br>- Tìm kiếm, lọc đầu sách theo danh mục và trạng thái. |
| 5 | **Trần Thùy Trang** | Quản lý Bản sao sách & Soft Delete | - Xây dựng CRUD Bản sao sách (`book_copies`).<br>- Quản lý mã bản sao, vị trí kệ sách, trạng thái bản sao (`Có sẵn`, `Đang mượn`, `Chưa có sẵn`).<br>- Chức năng Xóa mềm (Soft Delete) và Thùng rác khôi phục bản sao sách dành cho Admin. |

---

## 3. Công nghệ và Kiến trúc Hệ thống

### 3.1. Công nghệ sử dụng

- **Backend:** PHP 8.x thuần, lập trình theo mô hình MVC (Model - View - Controller), truy xuất dữ liệu an toàn qua **PDO**.
- **Frontend:** HTML5, CSS3 hiện đại (Giao diện tùy biến Responsive, Sidebar động, UI Card/Badge, Modal), JavaScript ES6 (Fetch API, Live Filter, Realtime Validation).
- **Cơ sở dữ liệu:** MySQL / MariaDB (Bộ mã `utf8mb4_unicode_ci` hỗ trợ đầy đủ tiếng Việt).
- **Bảo mật:**
  - Mã hóa mật khẩu một chiều chuẩn Bcrypt (`password_hash()`, `password_verify()`).
  - Chống SQL Injection bằng PDO Prepared Statements & Parameter Binding.
  - Chống tấn công XSS bằng cách escape toàn bộ dữ liệu đầu ra qua `htmlspecialchars()`.
  - Kiểm soát phiên đăng nhập (Session Authentication) và Phân quyền truy cập (Role-based Access Control - RBAC).

### 3.2. Cấu trúc thư mục dự án

```text
QLY_ThuVienMini/
├── assets/                  # Tài nguyên tĩnh (CSS, JS, Fonts, Images)
├── database/                # Quản lý CSDL
│   ├── config/
│   │   └── database.php     # Cấu hình kết nối PDO MySQL
│   ├── schema_mysql.sql     # Script khởi tạo cấu trúc CSDL (DDL)
│   └── seed_mysql.sql       # Script nạp dữ liệu mẫu ban đầu (DML)
├── layout/                  # Thành phần giao diện dùng chung
│   ├── sidebar.php          # Thanh điều hướng sidebar phân quyền động
│   └── header.php           # Thanh tiêu đề & thông tin tài khoản
├── src/
│   ├── Controller/          # Bộ điều khiển (Controllers)
│   │   ├── AboutController.php
│   │   ├── AuthController.php
│   │   ├── BaseController.php
│   │   ├── BookController.php
│   │   ├── BookCopyController.php
│   │   ├── BorrowSlipController.php
│   │   ├── CategoryController.php
│   │   ├── HomeController.php
│   │   ├── ReaderHomeController.php
│   │   └── UserController.php
│   └── Model/               # Tương tác CSDL (Models)
│       ├── BookCopyModel.php
│       ├── BookModel.php
│       ├── BorrowSlipModel.php
│       ├── CategoryModel.php
│       ├── DashboardModel.php
│       └── UserModel.php
├── views/                   # Giao diện hiển thị (Views)
│   ├── about/               # Trang giới thiệu
│   ├── auth/                # Đăng nhập, đổi mật khẩu, quên mật khẩu
│   ├── bansao/              # Quản lý bản sao sách & thùng rác
│   ├── danhmuc/             # Quản lý danh mục sách
│   ├── dausach/             # Quản lý đầu sách
│   ├── home/                # Trang tổng quan Dashboard & Tra cứu độc giả
│   ├── nguoidung/           # Quản lý người dùng, nhân sự, yêu cầu cấp lại MK
│   └── phieumuon/           # Quản lý phiếu mượn, tạo phiếu, cấu hình hạn mức
├── index.php                # Front Controller điều hướng tập trung toàn bộ request
└── README.md                # Tài liệu hướng dẫn & mô tả dự án
```

---

## 4. Mô tả Cơ sở dữ liệu (Database Schema)

Hệ thống bao gồm **7 bảng dữ liệu chính**:

1. **`users`** (Người dùng hệ thống):
   - Quản lý tài khoản Độc giả, Thủ thư, Quản trị viên.
   - Các trường: `id`, `ma_nguoi_dung` (Unique), `ho_ten`, `email` (Unique), `mat_khau`, `sdt`, `khoa_lop`, `vai_tro`, `trang_thai` (`Hoạt động` / `Bị khóa`), `ngay_tao`.

2. **`categories`** (Danh mục sách):
   - Phân loại các thể loại sách (Công nghệ thông tin, Văn học, Ngoại ngữ, Kinh tế...).
   - Các trường: `category_id`, `ten_danh_muc`, `mo_ta`, `trang_thai` (`Hoạt động` / `Ngừng hoạt động`).

3. **`books`** (Thông tin đầu sách):
   - Quản lý các thông tin tổng quát của một tác phẩm.
   - Các trường: `id`, `ma_sach` (Unique), `ten_sach`, `ma_tac_gia`, `tac_gia`, `category_id` (FK), `nha_xuat_ban`, `nam_xuat_ban`, `isbn` (Unique), `gia_sach`, `mo_ta`, `trang_thai`.

4. **`book_copies`** (Bản sao sách vật lý):
   - Đại diện cho từng cuốn sách cụ thể trên giá sách.
   - Các trường: `id`, `book_id` (FK), `ma_ban_sao` (Unique), `vi_tri`, `trang_thai` (`Có sẵn` / `Đang mượn` / `Chưa có sẵn` / `Hỏng` / `Mất`), `deleted_at` (Hỗ trợ xóa mềm).

5. **`borrow_slips`** (Phiếu mượn - trả sách):
   - Ghi nhận lịch sử và trạng thái mượn sách của độc giả.
   - Các trường: `ID_PhieuMuon`, `ID_NguoiDung` (FK), `ID_BanSao` (FK), `NgayMuon`, `NgayTra`, `TrangThai` (`Chờ duyệt` / `Đang mượn` / `Quá hạn` / `Đã trả`), `DaXoa`.

6. **`cau_hinh_han_muc`** (Cấu hình hạn mức thư viện):
   - Lưu trữ tham số quy định số lượng sách và thời gian mượn.
   - Các trường: `id`, `so_ngay_muon` (mặc định 14 ngày), `so_sach_toi_da` (mặc định 5 cuốn), `cap_nhat_luc`.

7. **`password_reset_requests`** (Yêu cầu cấp lại mật khẩu):
   - Lưu các yêu cầu đặt lại mật khẩu từ người dùng chờ Admin phê duyệt.
   - Các trường: `id`, `ma_nguoi_dung` (FK), `ho_ten`, `email`, `mat_khau_moi`, `trang_thai` (`Chờ duyệt` / `Đã duyệt` / `Đã từ chối`), `created_at`.

---

## 5. Phân quyền Người dùng (RBAC)

1. **Quản trị viên (Admin):**
   - Toàn quyền quản trị hệ thống: Quản lý Nhân sự (Thủ thư, Admin), Quản lý Độc giả.
   - Khóa / Mở khóa tài khoản người dùng; Phê duyệt yêu cầu cấp lại mật khẩu.
   - Quản lý Cấu hình hạn mức mượn sách.
   - Xem và khôi phục các bản sao sách đã bị xóa (Thùng rác - Soft Delete Restore).
   - Truy cập đầy đủ các chức năng của Thủ thư và Độc giả.

2. **Thủ thư (Librarian):**
   - Quản lý Danh mục sách: Thêm, sửa, tìm kiếm danh mục.
   - Quản lý Đầu sách: Thêm, sửa, tìm kiếm, lọc theo thể loại.
   - Quản lý Bản sao sách: Thêm, sửa vị trí, cập nhật trạng thái bản sao, xóa bản sao.
   - Quản lý Phiếu mượn: Tạo phiếu mượn trực tiếp tại quầy, Duyệt yêu cầu mượn online từ độc giả, Xác nhận trả sách.
   - Tra cứu hồ sơ mượn trả của độc giả.

3. **Độc giả (Reader):**
   - Giao diện trang chủ riêng: Tra cứu đầu sách, tìm kiếm sách theo tên/tác giả/thể loại theo thời gian thực.
   - Xem tình trạng các bản sao sách còn sẵn trên kệ.
   - Gửi yêu cầu mượn sách trực tuyến (chờ thủ thư duyệt).
   - Xem danh sách và lịch sử các phiếu mượn cá nhân.
   - Quản lý thông tin tài khoản cá nhân, đổi mật khẩu, gửi yêu cầu cấp lại mật khẩu khi quên.

---

## 6. Quy tắc Nghiệp vụ và Validation Dữ liệu

### 6.1. Quy tắc xử lý form chung

- Dữ liệu đầu vào đều được loại bỏ khoảng trắng thừa bằng hàm `trim()`.
- Dữ liệu hiển thị ra HTML đều được lọc qua hàm `htmlspecialchars()` để chống tấn công XSS.
- Báo lỗi trực tiếp dưới từng trường nhập sai, làm nổi bật viền đỏ và giữ lại giá trị hợp lệ người dùng đã nhập.

### 6.2. Validation theo từng Module

1. **Module Người dùng & Xác thực:**
   - `ma_nguoi_dung`: Bắt buộc, độ dài từ 3–20 ký tự, không trùng lặp trong hệ thống.
   - `ho_ten`: Bắt buộc, độ dài từ 2–100 ký tự.
   - `email`: Bắt buộc, đúng định dạng email hợp lệ, không trùng lặp.
   - `mat_khau`: Bắt buộc khi tạo mới, tối thiểu 6 ký tự.
   - `vai_tro`: Chỉ nhận một trong các giá trị: `Quản trị viên`, `Thủ thư`, `Độc giả`.
   - `trang_thai`: Chỉ nhận `Hoạt động` hoặc `Bị khóa`.

2. **Module Danh mục sách:**
   - `ten_danh_muc`: Bắt buộc, độ dài từ 2–100 ký tự, phải chứa chữ cái hoặc số.
   - `mo_ta`: Không bắt buộc, tối đa 500 ký tự.
   - `trang_thai`: Bắt buộc chọn `Hoạt động` hoặc `Ngừng hoạt động`.

3. **Module Đầu sách:**
   - `ma_sach`: Bắt buộc, định dạng mã chuẩn (ví dụ: `S001`), không trùng lặp.
   - `ten_sach`: Bắt buộc, độ dài từ 2–255 ký tự.
   - `tac_gia` & `ma_tac_gia`: Bắt buộc, độ dài hợp lệ.
   - `category_id`: Bắt buộc chọn từ danh sách danh mục đang hoạt động.
   - `isbn`: Bắt buộc, đúng chuẩn mã ISBN, không trùng lặp.
   - `nam_xuat_ban`: Số nguyên hợp lệ (nằm trong khoảng từ năm 1000 đến 2100).
   - `gia_sach`: Số thực >= 0.

4. **Module Bản sao sách:**
   - `ma_ban_sao`: Bắt buộc, định dạng hợp lệ (ví dụ: `BS001`), không trùng lặp.
   - `book_id`: Bắt buộc, tham chiếu đến đầu sách hợp lệ.
   - `vi_tri`: Tối đa 100 ký tự (ví dụ: `Kệ A1`, `Kệ B2`).
   - `trang_thai`: Chỉ nhận `Có sẵn`, `Đang mượn`, `Chưa có sẵn`, `Hỏng`, `Mất`.

5. **Module Phiếu mượn - Trả sách:**
   - Độc giả chỉ được tạo/duyệt mượn khi tài khoản ở trạng thái `Hoạt động`.
   - **Kiểm tra hạn mức mượn:** Tổng số sách đang mượn + sách đăng ký mới không được vượt quá `so_sach_toi_da` quy định trong bảng cấu hình (mặc định 5 cuốn).
   - Bản sao sách phải có trạng thái `Có sẵn` và không thuộc phiếu mượn chưa hoàn tất khác.
   - `NgayMuon`: Mặc định là ngày hiện tại hoặc ngày lập phiếu hợp lệ.
   - `Hạn trả`: Tự động tính toán bằng `NgayMuon + so_ngay_muon` (mặc định 14 ngày).
   - `NgayTra`: Ghi nhận khi độc giả thực tế hoàn trả sách (không được trước ngày mượn).
   - Trạng thái tự động cập nhật: `Chờ duyệt` -> `Đang mượn` -> `Đã trả` (hoặc `Quá hạn` nếu quá ngày dự kiến mà chưa trả).

---

## 7. Danh sách Route Điều hướng (Front Controller)

Toàn bộ ứng dụng được điều hướng tập trung qua tệp `index.php`:

| URL Route | Controller | Action | Quyền hạn | Chức năng chính |
| :--- | :--- | :--- | :--- | :--- |
| `index.php?controller=home` | `HomeController` / `ReaderHomeController` | `index` | Tất cả | Trang chủ tổng quan / Dashboard / Trang tra cứu độc giả |
| `index.php?controller=about` | `AboutController` | `index` | Tất cả | Giới thiệu đề tài, giảng viên và nhóm sinh viên thực hiện |
| `index.php?controller=auth&action=login` | `AuthController` | `login` | Khách | Đăng nhập vào hệ thống |
| `index.php?controller=auth&action=logout` | `AuthController` | `logout` | Đã đăng nhập | Đăng xuất tài khoản |
| `index.php?controller=auth&action=forgot_password` | `AuthController` | `forgotPassword` | Khách | Gửi yêu cầu xin cấp lại mật khẩu |
| `index.php?controller=auth&action=change_password` | `AuthController` | `changePassword` | Đã đăng nhập | Đổi mật khẩu cá nhân |
| `index.php?controller=danhmuc` | `CategoryController` | `index` | Thủ thư, Admin | Quản lý danh mục sách (CRUD, tìm kiếm) |
| `index.php?controller=dausach` | `BookController` | `index` | Thủ thư, Admin | Quản lý đầu sách (CRUD, lọc thể loại, tìm kiếm) |
| `index.php?controller=bansao` | `BookCopyController` | `index` | Thủ thư, Admin | Quản lý bản sao sách, thùng rác khôi phục |
| `index.php?controller=phieumuon` | `BorrowSlipController` | `index` | Tất cả | Danh sách & quản lý phiếu mượn (phân quyền theo vai trò) |
| `index.php?controller=phieumuon&action=yeucaumuon` | `BorrowSlipController` | `yeuCauMuon` | Độc giả | Gửi yêu cầu mượn sách trực tuyến |
| `index.php?controller=phieumuon&action=cauhinhhanmuc` | `BorrowSlipController` | `cauHinhHanMuc` | Admin | Cài đặt số ngày mượn và số sách tối đa |
| `index.php?controller=nguoidung` | `UserController` | `index` | Admin | Quản lý danh sách người dùng toàn hệ thống |
| `index.php?controller=nguoidung&action=tracuudocgia` | `UserController` | `traCuuDocGia` | Thủ thư, Admin | Tra cứu thông tin và lịch sử mượn trả của độc giả |
| `index.php?controller=nguoidung&action=yeucaucaplaimatkhau` | `UserController` | `yeuCauCapLaiMatKhau` | Admin | Danh sách và phê duyệt yêu cầu đặt lại mật khẩu |
