<?php

if (isset($_POST['btn_kiem_tra'])) {

    $id_ban_sao = $_POST['id_ban_sao'];
    $id_dau_sach = $_POST['id_dau_sach'];
    $ma_ban_sao = $_POST['ma_ban_sao'];
    $trang_thai = $_POST['trang_thai'];

}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kiểm tra trạng thái bản sao</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #dbeafe, #ede9fe);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 450px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        h2 {
            text-align: center;
            color: #4f46e5;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            color: #333;
        }

        input,
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 5px rgba(99, 102, 241, 0.3);
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: none;
            border-radius: 8px;
            background: #6366f1;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #4f46e5;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>KIỂM TRA TRẠNG THÁI BẢN SAO</h2>

        <form method="post">

            <div class="form-group">
                <label>ID bản sao:</label>
                <input type="text" name="id_ban_sao">
            </div>

            <div class="form-group">
                <label>ID đầu sách:</label>
                <input type="text" name="id_dau_sach">
            </div>

            <div class="form-group">
                <label>Mã bản sao:</label>
                <input type="text" name="ma_ban_sao">
            </div>

            <div class="form-group">
                <label>Trạng thái:</label>
                <select name="trang_thai">
                    <option value="Đang mượn">Đang mượn</option>
                    <option value="Chưa trả">Chưa trả</option>
                    <option value="Đã trả">Đã trả</option>
                </select>
            </div>

            <button type="submit" name="btn_kiem_tra">
                Kiểm tra trạng thái
            </button>

        </form>

    </div>

</body>
</html>