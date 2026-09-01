<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kiểm tra trạng thái bản sao</title>
    <link rel="stylesheet" href="assets/css/design-system.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 450px;
            background: var(--white);
            padding: 30px;
            border-radius: var(--radius-card);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-card);
        }

        h2 {
            text-align: center;
            color: var(--text-primary);
            margin-bottom: 25px;
            font-size: var(--font-size-section-title);
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: var(--font-weight-semibold);
            font-size: var(--font-size-label);
            color: var(--text-body);
        }

        button {
            width: 100%;
            height: var(--button-height);
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>KIỂM TRA TRẠNG THÁI BẢN SAO</h2>
        <form method="post">
            <div class="form-group">
                <label>ID bản sao:</label>
                <input type="text" name="id_ban_sao" value="<?= htmlspecialchars($id_ban_sao ?? '') ?>">
            </div>

            <div class="form-group">
                <label>ID đầu sách:</label>
                <input type="text" name="id_dau_sach" value="<?= htmlspecialchars($id_dau_sach ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Mã bản sao:</label>
                <input type="text" name="ma_ban_sao" value="<?= htmlspecialchars($ma_ban_sao ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Trạng thái:</label>
                <select name="trang_thai">
                    <option value="Đang mượn" <?= (($trang_thai ?? '') === 'Đang mượn') ? 'selected' : '' ?>>Đang mượn</option>
                    <option value="Chưa trả" <?= (($trang_thai ?? '') === 'Chưa trả') ? 'selected' : '' ?>>Chưa trả</option>
                    <option value="Đã trả" <?= (($trang_thai ?? '') === 'Đã trả') ? 'selected' : '' ?>>Đã trả</option>
                </select>
            </div>

            <button type="submit" name="btn_kiem_tra">
                Kiểm tra trạng thái
            </button>
        </form>
    </div>
</body>
</html>
