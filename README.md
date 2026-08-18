# Dự án Lập trình Web - Nhóm 02

## Hướng dẫn cài đặt và chạy dự án (Localhost)

1. **Yêu cầu hệ thống:** Máy tính cần cài đặt XAMPP.
2. **Kéo mã nguồn:**
   - Di chuyển vào thư mục `htdocs` của XAMPP.
   - Mở Terminal và chạy lệnh: `git clone https://github.com/hthngocanh/QLY_ThuVienMini.git`
3. **Khởi chạy:**
   - Bật Apache và MySQL trên bảng điều khiển XAMPP.
   - Mở trình duyệt và truy cập: `http://localhost/QLY_ThuVienMini/about.php`

## 1. Tên đề tài

**Hệ thống Quản lý Thư viện Mini**

## 2. Thành viên và phân công

| STT | Thành viên         | Phân công             |
| --- | ------------------ | --------------------- |
| 1   | Hoàng Thị Ngọc Ánh | Quản lý người dùng    |
| 2   | Phạm Khánh Linh    | Quản lý danh mục sách |
| 3   | Nguyễn Thị Mỹ Hạnh | Quản lý phiếu mượn    |
| 4   | Nguyễn Khánh Linh  | Quản lý đầu sách      |
| 5   | Trần Thùy Trang    | Quản lý bản sao sách  |

## 3. Các đối tượng dữ liệu chính

- Vai trò
- Người dùng
- Danh mục sách
- Phiếu mượn
- Đầu sách
- Bản sao sách

## 4. Các chức năng dự kiến

- Quản lý người dùng
- Quản lý danh mục sách
- Quản lý đầu sách
- Quản lý bản sao sách
- Quản lý phiếu mượn
- Tra cứu sách
- Quản lý mượn và trả sách
<<<<<<< HEAD

## 5. Các chức năng đã thực hiện đến hết Buổi 2

=======
## 5. Các chức năng đã thực hiện đến hết Buổi 3
>>>>>>> e45042c (Cap nhat README)
1. Quản lý người dùng

- Xây dựng form nhập thông tin người dùng.

2. Quản lý phiếu mượn

- Xây dựng chức năng quản lý phiếu mượn.
- Quản lý thông tin ngày mượn, hạn trả và ngày trả.
- Bước đầu xử lý thông tin mượn và trả sách.

3. Quản lý danh mục sách

- Thêm danh mục.
- Kiểm tra dữ liệu.
- Hiển thị danh sách.

4. Quản lý đầu sách

- Thêm đầu sách.
- Xóa đầu sách
- Kiểm tra thông tin đầu sách.
- Hiển thị danh sách đầu sách.

5. Quản lý bản sao sách:

- Nhập ID bản sao, ID đầu sách và mã bản sao.
- Kiểm tra trạng thái bản sao: Đang mượn, Chưa trả, Đã trả.
- Sử dụng điều kiện để xác định trạng thái bản sao.
- Sử dụng vòng lặp để duyệt và hiển thị dữ liệu.
- Hiển thị kết quả dưới dạng bảng.

## 6. Công nghệ sử dụng

- PHP
- HTML
- CSS
- XAMPP
- Git
- GitHub

## 7. Các form chính của hệ thống - Buổi 3

Các form chính của hệ thống gồm:

- Form người dùng
- Form danh mục sách
- Form đầu sách
- Form bản sao sách
- Form phiếu mượn

### Form bản sao sách

Các trường dữ liệu:

- ID bản sao
- ID đầu sách
- Mã bản sao
- Trạng thái
- Ngày nhập

## 8. Quy tắc Validation

### Quản lý người dùng

- Mã người dùng: bắt buộc, 3–20 ký tự, không trùng.
- Họ tên: bắt buộc, 2–100 ký tự.
- Email: bắt buộc, đúng định dạng email.
- Trạng thái: Hoạt động hoặc Bị khóa.
- Số sách đang mượn: số nguyên từ 0 đến 5.
- Hạn mức mượn: cố định 5 cuốn.
- Dữ liệu được chuẩn hóa bằng trim().
- Dữ liệu được escape bằng htmlspecialchars() trước khi hiển thị.

### Form bản sao sách

- ID bản sao:
  - Không được để trống.
  - Phải bắt đầu bằng 1 chữ cái IN HOA và phía sau là số.
  - Ví dụ hợp lệ: B01, B02, B123.
  - Không chấp nhận ký tự đặc biệt.

- ID đầu sách:
  - Kiểm tra tên sách: không để trống, độ dài 2–100 ký tự, phải chứa chữ hoặc số.
  - Kiểm tra tác giả: không để trống, độ dài 2–100 ký tự, chỉ chứa chữ cái và khoảng trắng.
  - Kiểm tra danh mục: bắt buộc phải chọn.
  - Hiển thị thông báo lỗi khi dữ liệu không hợp lệ.

- Mã bản sao:
  - Không được để trống.
  - Phải bắt đầu bằng 1 chữ cái IN HOA và phía sau là số.
  - Ví dụ hợp lệ: M01, M11.
  - Không chấp nhận ký tự đặc biệt.

- Trạng thái:
  - Chỉ chấp nhận một trong ba giá trị:
    - Đã trả
    - Đang mượn
    - Chưa trả

- Ngày nhập:
  - Không được để trống.
  - Phải có định dạng ngày hợp lệ.

### Xử lý khi dữ liệu không hợp lệ

- Báo lỗi ngay bên dưới trường nhập sai.
- Trường nhập sai được hiển thị viền đỏ và nền đỏ nhạt.
- Giữ lại dữ liệu người dùng đã nhập để không phải nhập lại toàn bộ form.
- Sử dụng trim() để loại bỏ khoảng trắng thừa.

### Form phiếu mượn

- Người mượn: bắt buộc, không được để trống.
- Bản sao sách: bắt buộc, không được để trống.
- Ngày mượn: bắt buộc, phải là ngày hợp lệ.
- Hạn trả: bắt buộc, không được trước ngày mượn.
- Ngày trả: không bắt buộc; nếu nhập thì phải là ngày hợp lệ và không được trước ngày mượn.
- Tình trạng: tự động xác định dựa trên ngày trả và hạn trả: Đã trả, Đang mượn hoặc Quá hạn.
- Dữ liệu được chuẩn hóa bằng trim().
- Dữ liệu được escape bằng htmlspecialchars() trước khi hiển thị.
- Phiếu mượn chỉ được thêm khi dữ liệu hợp lệ.

### Form đầu sách

- Tên sách: bắt buộc, dài từ 2–100 ký tự và phải chứa chữ hoặc số.
- Tác giả: bắt buộc, dài từ 2–100 ký tự, chỉ chứa chữ cái và khoảng trắng.
- Danh mục: bắt buộc phải chọn.
- Dữ liệu được chuẩn hóa bằng trim().
- Dữ liệu được escape bằng htmlspecialchars() trước khi hiển thị.
- Hiển thị thông báo lỗi khi dữ liệu không hợp lệ.
- Đầu sách chỉ được thêm khi dữ liệu hợp lệ.

## 9. Quy tắc nghiệp vụ

### Quản lý người dùng

- Người dùng chỉ được mượn sách khi tài khoản ở trạng thái Hoạt động.
- Mỗi người dùng được mượn tối đa 5 cuốn.
- Người dùng đã mượn đủ 5 cuốn không được mượn thêm.

## 10. Route dự kiến

Các route chính của hệ thống:

- Trang chủ:
  - /index.php

- Người dùng:
  - /nguoiDung/User.php

- Bản sao sách:
  - /banSaoSach/bansao.php
