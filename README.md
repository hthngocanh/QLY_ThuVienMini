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

---

## 2. Thành viên và phân công

| STT | Thành viên         | Phân công             |
|-----|--------------------|-----------------------|
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

## 5. Các chức năng đã thực hiện đến hết Buổi 2

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

### Form bản sao sách

- ID bản sao:
  - Không được để trống.
  - Phải bắt đầu bằng 1 chữ cái IN HOA và phía sau là số.
  - Ví dụ hợp lệ: B01, B02, B123.
  - Không chấp nhận ký tự đặc biệt.

- ID đầu sách:
  - Không được để trống.
  - Phải bắt đầu bằng 1 chữ cái IN HOA và phía sau là số.
  - Ví dụ hợp lệ: D01, D12.
  - Không chấp nhận ký tự đặc biệt.

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
## 9. Route dự kiến

Các route chính của hệ thống:

- Trang chủ:
  - /index.php

- Người dùng:
  - /nguoiDung/User.php

- Bản sao sách:
  - /banSaoSach/bansao.php
## 10. Công việc thực hiện trong Buổi 3

### Bản sao sách

- Tiếp tục phát triển form bản sao sách từ Buổi 2.
- Bổ sung kiểm tra dữ liệu phía server bằng PHP.
- Kiểm tra định dạng ID bản sao, ID đầu sách và mã bản sao.
- Hiển thị lỗi ngay tại trường nhập sai.
- Giữ lại dữ liệu hợp lệ khi form có trường nhập sai.
- Kiểm tra trạng thái mượn trả của bản sao sách.
- Hiển thị trạng thái:
  - Đã trả: màu xanh.
  - Đang mượn: màu vàng.
  - Chưa trả: màu đỏ.
- Kiểm thử form với dữ liệu đúng, dữ liệu thiếu, dữ liệu sai định dạng và dữ liệu chứa ký tự không hợp lệ.

