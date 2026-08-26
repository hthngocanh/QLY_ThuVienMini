<?php
// Khởi tạo Session để lưu danh sách danh mục tạm thời giữa các lần submit (Do chưa dùng CSDL)
session_start();

if (!isset($_SESSION['danhSachDanhMuc'])) {
    $_SESSION['danhSachDanhMuc'] = [];
}

// Khai báo mảng chứa lỗi và thông báo
$errors = [];
$thongBaoThanhCong = "";

// Khởi tạo các biến để giữ lại giá trị người dùng đã nhập (Old Input)
$tenDanhMuc = "";
$moTa = "";
$trangThai = "1";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 1. CHUẨN HÓA DỮ LIỆU ĐẦU VÀO (Sanitization)
    $tenDanhMuc = isset($_POST['ten_danh_muc']) ? trim($_POST['ten_danh_muc']) : '';
    $moTa = isset($_POST['mo_ta']) ? trim($_POST['mo_ta']) : '';
    $trangThai = isset($_POST['trang_thai']) ? trim($_POST['trang_thai']) : '1';

    // 2. KIỂM TRA DỮ LIỆU PHÍA SERVER (Validation)
    
    // Kiểm tra tên danh mục (Bắt buộc)
    if (empty($tenDanhMuc)) {
        $errors['ten_danh_muc'] = "Tên danh mục không được để trống!";
    } elseif (mb_strlen($tenDanhMuc) < 3) {
        $errors['ten_danh_muc'] = "Tên danh mục phải có tối thiểu 3 ký tự!";
    } elseif (mb_strlen($tenDanhMuc) > 100) {
        $errors['ten_danh_muc'] = "Tên danh mục không được vượt quá 100 ký tự!";
    }

    // Kiểm tra độ dài mô tả (Tùy chọn nhưng nếu nhập thì kiểm tra độ dài)
    if (!empty($moTa) && mb_strlen($moTa) > 500) {
        $errors['mo_ta'] = "Mô tả không được vượt quá 500 ký tự!";
    }

    // Kiểm tra trạng thái hợp lệ
    if (!in_array($trangThai, ['0', '1'])) {
        $errors['trang_thai'] = "Trạng thái không hợp lệ!";
    }

    // 3. XỬ LÝ KHI DỮ LIỆU HỢP LỆ
    if (empty($errors)) {
        $danhMuc = [
            'ten' => $tenDanhMuc,
            'mo_ta' => $moTa,
            'trang_thai' => $trangThai
        ];

        // Thêm vào session
        $_SESSION['danhSachDanhMuc'][] = $danhMuc;

        $thongBaoThanhCong = "Thêm danh mục sách thành công!";

        // RS form sau khi thêm thành công
        $tenDanhMuc = "";
        $moTa = "";
        $trangThai = "1";
    }
}

// Lấy danh sách từ Session
$danhSachDanhMuc = $_SESSION['danhSachDanhMuc'];

// Hàm hiển thị trạng thái
function hienThiTrangThai($trangThai)
{
    return ($trangThai == 1) ? "Đang sử dụng" : "Ngừng sử dụng";
}

// Hàm chống XSS cho các đầu ra
function escape($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý danh mục sách</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 40px;
            color: #333;
        }

        .container {
            width: 900px;
            max-width: 100%;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        h2 {
            color: #34495e;
            border-left: 5px solid #3498db;
            padding-left: 10px;
            margin-top: 25px;
        }

        .thong-bao-thanh-cong {
            background-color: #e8f8f0;
            color: #218c5a;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #2ecc71;
        }

        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 4px;
            display: block;
        }

        .input-error {
            border-color: #e74c3c !important;
        }

        form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: inline-block;
            width: 130px;
            font-weight: bold;
            vertical-align: top;
            padding-top: 8px;
        }

        .input-control {
            display: inline-block;
            width: 70%;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            height: 80px;
            resize: vertical;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 4px rgba(52, 152, 219, 0.3);
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 11px 20px;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
        }

        th {
            background-color: #3498db;
            color: white;
            padding: 12px;
            text-align: center;
        }

        td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #eaf4fb;
        }

        td:first-child {
            text-align: center;
            width: 60px;
        }

        td:last-child {
            text-align: center;
            font-weight: bold;
        }

        .status-active {
            color: #27ae60;
        }

        .status-inactive {
            color: #c0392b;
        }
    </style>
</head>

<body>
    <!-- CSS bổ sung (đặt trong thẻ <head>) -->
<style>
  /* Style cho thanh Menu */
    .navbar {
            background-color: #1e3a8a;
            padding: 15px 25px;
            display: flex;
            gap: 10px;
            margin: -40px -40px 35px -40px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
        }

        .navbar a:hover {
            background-color: #2563eb;
        }

        .navbar a.active {
            background-color: #2563eb;
        }
</style>

<nav class="navbar">

        <a href="../index.php">
            🏠 Trang chủ
        </a>

       <a href="../nguoiDung/User.php" class="nav-item">
    <span>👤</span> Người dùng
  </a>

        <a href="../banSaoSach/bansao.php">
            📖 Bản sao sách
        </a>
        <a href="../phieuMuon/phieumuon.php">
            📖 Phiếu mượn
        </a>
         <a href="User.php" class="active">
            📖 Danh Mục
</a>
    </nav>


<div class="container">

    <h1>📚 Quản lý danh mục sách</h1>

    <?php if (!empty($thongBaoThanhCong)): ?>
        <div class="thong-bao-thanh-cong">
            <?php echo escape($thongBaoThanhCong); ?>
        </div>
    <?php endif; ?>

    <h2>➕ Thêm danh mục sách</h2>

    <form method="post" action="">

        <div class="form-group">
            <label for="ten_danh_muc">Tên danh mục <span style="color:red">*</span>:</label>
            <div class="input-control">
                <input type="text" 
                       id="ten_danh_muc" 
                       name="ten_danh_muc" 
                       placeholder="Nhập tên danh mục..." 
                       value="<?php echo escape($tenDanhMuc); ?>"
                       class="<?php echo isset($errors['ten_danh_muc']) ? 'input-error' : ''; ?>">
                <?php if (isset($errors['ten_danh_muc'])): ?>
                    <span class="error-message"><?php echo escape($errors['ten_danh_muc']); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="mo_ta">Mô tả:</label>
            <div class="input-control">
                <textarea id="mo_ta" 
                          name="mo_ta" 
                          placeholder="Nhập mô tả danh mục..."
                          class="<?php echo isset($errors['mo_ta']) ? 'input-error' : ''; ?>"><?php echo escape($moTa); ?></textarea>
                <?php if (isset($errors['mo_ta'])): ?>
                    <span class="error-message"><?php echo escape($errors['mo_ta']); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="trang_thai">Trạng thái:</label>
            <div class="input-control">
                <select id="trang_thai" name="trang_thai">
                    <option value="1" <?php echo ($trangThai == '1') ? 'selected' : ''; ?>>Đang sử dụng</option>
                    <option value="0" <?php echo ($trangThai == '0') ? 'selected' : ''; ?>>Ngừng sử dụng</option>
                </select>
                <?php if (isset($errors['trang_thai'])): ?>
                    <span class="error-message"><?php echo escape($errors['trang_thai']); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit">➕ Thêm danh mục</button>

    </form>

    <h2>📋 Danh sách danh mục sách</h2>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($danhSachDanhMuc)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #7f8c8d;">Chưa có danh mục nào được tạo.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($danhSachDanhMuc as $index => $danhMuc): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <!-- Dùng escape() để chống tấn công XSS khi hiển thị lại dữ liệu -->
                        <td><?php echo escape($danhMuc['ten']); ?></td>
                        <td><?php echo escape($danhMuc['mo_ta']); ?></td>
                        <td class="<?php echo $danhMuc['trang_thai'] == 1 ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo escape(hienThiTrangThai($danhMuc['trang_thai'])); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>

</html>