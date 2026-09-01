<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý bản sao sách</title>
    <link rel="stylesheet" href="assets/css/design-system.css">

    <style>
        .form-ban-sao {
            width: min(580px, 100%);
            box-sizing: border-box;
            margin: 0 auto;
            padding: 30px;
            background-color: var(--white);
            border-radius: var(--radius-card);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-card);
        }

        label {
            display: block;
            margin-top: 14px;
            margin-bottom: 6px;
            font-weight: var(--font-weight-semibold);
            font-size: var(--font-size-label);
            color: var(--text-body);
        }

        .input-loi {
            border: 1px solid var(--danger) !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12) !important;
        }

        .loi-truong {
            color: var(--danger);
            font-size: var(--font-size-error);
            font-weight: var(--font-weight-medium);
            margin-top: 4px;
        }

        .thanh-cong,
        .thanh-loi {
            width: min(580px, 100%);
            box-sizing: border-box;
            margin: 20px auto;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: var(--font-size-label);
            font-weight: var(--font-weight-medium);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .thanh-cong {
            color: #166534;
            background-color: #F0FDF4;
            border: 1px solid #DCFCE7;
            border-left: 4px solid var(--success);
        }

        .thanh-loi {
            color: #991B1B;
            background-color: #FEF2F2;
            border: 1px solid #FEE2E2;
            border-left: 4px solid var(--danger);
        }

        .thong-bao-an {
            opacity: 0;
            transform: translateY(-10px);
        }

        .btn-chinh {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 25px auto 0;
            height: var(--button-height);
            padding: 0 24px;
            border: none;
            border-radius: var(--radius-button);
            background-color: var(--primary);
            color: var(--white);
            font-size: var(--font-size-button);
            font-weight: var(--font-weight-semibold);
            cursor: pointer;
            box-shadow: var(--shadow-btn);
            transition: all 0.15s ease;
        }

        .btn-chinh:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .huy-sua {
            display: block;
            width: fit-content;
            margin: 12px auto 0;
            text-decoration: none;
            color: var(--text-secondary);
            font-size: var(--font-size-label);
        }

        .huy-sua:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .ket-qua {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            margin: 35px auto;
            background-color: var(--white);
            padding: 25px;
            border-radius: var(--radius-card);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-card);
        }

        .ket-qua h2 {
            text-align: center;
            color: var(--text-primary);
            margin-bottom: 20px;
            font-size: var(--font-size-card-title);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: var(--font-size-body);
        }

        th {
            background-color: var(--bg-page);
            color: var(--text-primary);
            font-size: var(--font-size-label);
            font-weight: var(--font-weight-bold);
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            text-align: center;
        }

        td {
            border-bottom: 1px solid var(--border);
            padding: 12px 14px;
            text-align: center;
            color: var(--text-body);
        }

        tr:hover {
            background-color: var(--bg-page);
        }

        .co-san {
            color: var(--success);
            font-weight: var(--font-weight-bold);
        }

        .dang-muon {
            color: var(--warning);
            font-weight: var(--font-weight-bold);
        }

        .hong {
            color: var(--danger);
            font-weight: var(--font-weight-bold);
        }

        .thao-tac {
            display: flex;
            justify-content: center;
            gap: var(--gap-table-action);
        }

        .btn-sua {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            background-color: #FFFBEB;
            color: #92400E;
            border: 1px solid #FEF3C7;
            text-decoration: none;
            border-radius: var(--radius-action);
            font-size: var(--font-size-caption);
            font-weight: var(--font-weight-semibold);
            transition: all 0.15s ease;
        }

        .btn-sua:hover {
            background-color: var(--warning);
            color: var(--white);
        }

        .btn-xoa {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: 1px solid #FEE2E2;
            padding: 6px 12px;
            background-color: #FEF2F2;
            color: var(--danger);
            border-radius: var(--radius-action);
            cursor: pointer;
            font-size: var(--font-size-caption);
            font-weight: var(--font-weight-semibold);
            transition: all 0.15s ease;
        }

        .btn-xoa:hover {
            background-color: var(--danger);
            color: var(--white);
        }

        .form-xoa {
            margin: 0;
            padding: 0;
            width: auto;
            background: none;
            box-shadow: none;
            border: none;
        }

        /* POPUP XÁC NHẬN SỬA / XÓA */
        .xac-nhan-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.22s ease, visibility 0.22s ease;
        }

        .xac-nhan-overlay.hien {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .xac-nhan-hop {
            width: 420px;
            max-width: 100%;
            background: #ffffff;
            border-radius: 14px;
            padding: 26px;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.25);
            text-align: center;
            font-family: inherit;
            transform: scale(0.82);
            transform-origin: center;
            opacity: 0;
            transition: transform 0.22s ease, opacity 0.22s ease;
        }

        .xac-nhan-overlay.hien .xac-nhan-hop {
            transform: scale(1);
            opacity: 1;
        }

        .xac-nhan-bieu-tuong {
            width: 54px;
            height: 54px;
            margin: 0 auto 14px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            font-size: 26px;
        }

        .xac-nhan-tieu-de {
            margin: 0 0 10px;
            color: #1e4f8a;
            font-size: clamp(18px, 2vw, 21px);
            line-height: 1.3;
            word-break: break-word;
        }

        .xac-nhan-noi-dung {
            margin: 0;
            color: #475569;
            line-height: 1.6;
            font-size: clamp(14px, 1.5vw, 15px);
            word-break: break-word;
        }

        .xac-nhan-ma {
            color: #1e4f8a;
            font-weight: bold;
        }

        .xac-nhan-nut {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 22px;
        }

        .xac-nhan-nut button {
            border: none;
            border-radius: 7px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .nut-huy-xac-nhan {
            background: #e5e7eb;
            color: #374151;
        }

        .nut-huy-xac-nhan:hover {
            background: #d1d5db;
        }

        .nut-dong-y {
            background: #2f80c0;
            color: white;
        }

        .nut-dong-y:hover {
            background: #1e659d;
        }

        .nut-dong-y.xoa {
            background: #dc2626;
        }

        .nut-dong-y.xoa:hover {
            background: #b91c1c;
        }

        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }

        .layout, .main-content {
            min-width: 0;
        }

        .form-ban-sao, .thanh-cong, .thanh-loi, .ket-qua {
            max-width: 100%;
        }

        .ket-qua {
            overflow: hidden;
        }

        table {
            table-layout: auto;
        }

        th, td {
            overflow-wrap: anywhere;
            word-break: normal;
        }

        @media (max-width: 1180px) {
            h1 {
                font-size: clamp(22px, 3vw, 30px);
                margin-bottom: 20px;
            }

            .ket-qua {
                padding: 14px;
            }

            th, td {
                padding: 8px 6px;
                font-size: 12px;
            }

            .btn-sua, .btn-xoa {
                padding: 6px 8px;
                font-size: 12px;
            }

            .thao-tac {
                gap: 5px;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 820px) {
            .main-content {
                padding: 14px 10px !important;
            }

            .form-ban-sao {
                padding: 18px;
            }

            .ket-qua {
                margin: 24px auto;
                padding: 12px;
                background: transparent;
                box-shadow: none;
            }

            .ket-qua h2 {
                font-size: 19px;
            }

            table, tbody, tr, td {
                display: block;
                width: 100%;
            }

            table {
                margin-top: 14px;
            }

            table tr:first-child {
                display: none;
            }

            table tr:not(:first-child) {
                margin-bottom: 14px;
                padding: 10px 12px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
            }

            table tr:not(:first-child) td {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 16px;
                padding: 9px 0;
                border: none;
                border-bottom: 1px solid #edf1f5;
                text-align: right;
                font-size: 13px;
            }

            table tr:not(:first-child) td:last-child {
                border-bottom: none;
            }

            table tr:not(:first-child) td::before {
                content: attr(data-label);
                flex: 0 0 42%;
                text-align: left;
                font-weight: 700;
                color: #475569;
            }

            .thao-tac {
                justify-content: flex-end;
                width: 100%;
            }

            .btn-sua, .btn-xoa {
                padding: 7px 12px;
            }
        }

        @media (max-width: 520px) {
            h1 {
                font-size: 21px;
            }

            .form-ban-sao {
                padding: 15px;
            }

            input, select {
                font-size: 13px;
                padding: 9px;
            }

            table tr:not(:first-child) td {
                gap: 10px;
                font-size: 12px;
            }

            table tr:not(:first-child) td::before {
                flex-basis: 40%;
            }

            .xac-nhan-hop {
                width: 92vw;
                padding: 20px;
            }

            .xac-nhan-nut {
                flex-direction: column-reverse;
            }

            .xac-nhan-nut button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="layout" style="display: flex; min-height: 100vh;">
    <?php
    $activePage = 'bansao';
    require_once __DIR__ . '/../../layout/sidebar.php';
    ?>
    <main class="main-content" style="flex: 1; min-width: 0; padding: clamp(14px, 3vw, 35px) clamp(12px, 3vw, 40px); overflow-y: auto; background: #f8fafc;">

    <h1 style="margin-top: 0;">QUẢN LÝ BẢN SAO SÁCH</h1>

    <?php if (!empty($thongBao)) { ?>
        <div class="thanh-cong thong-bao">
            <?= htmlspecialchars($thongBao) ?>
        </div>
    <?php } ?>

    <?php if (!empty($thongBaoLoi)) { ?>
        <div class="thanh-loi thong-bao">
            <?= htmlspecialchars($thongBaoLoi) ?>
        </div>
    <?php } ?>

    <form method="post" action="index.php?controller=bansao" class="form-ban-sao">
        <input type="hidden" name="action" value="<?= !empty($editId) ? "update" : "add"; ?>">
        <input type="hidden" name="edit_id" value="<?= htmlspecialchars($editId ?? ''); ?>">

        <label for="book_id">Đầu sách:</label>
        <select id="book_id" name="book_id" class="<?= !empty($loiBookId) ? "input-loi" : ""; ?>">
            <option value="">-- Chọn đầu sách --</option>
            <?php foreach ($danhSachDauSach as $dauSach) { ?>
                <option value="<?= $dauSach["id"]; ?>" <?= ((string)($bookId ?? '') === (string)$dauSach["id"]) ? "selected" : ""; ?>>
                    <?= htmlspecialchars($dauSach["ma_sach"] . " - " . $dauSach["ten_sach"]); ?>
                </option>
            <?php } ?>
        </select>
        <?php if (!empty($loiBookId)) { ?>
            <p class="loi-truong"><?= htmlspecialchars($loiBookId); ?></p>
        <?php } ?>

        <label for="ma_ban_sao">Mã bản sao:</label>
        <input type="text" id="ma_ban_sao" name="ma_ban_sao" placeholder="Ví dụ: BS005" value="<?= htmlspecialchars($maBanSao ?? ''); ?>" class="<?= !empty($loiMaBanSao) ? "input-loi" : ""; ?>">
        <?php if (!empty($loiMaBanSao)) { ?>
            <p class="loi-truong"><?= htmlspecialchars($loiMaBanSao); ?></p>
        <?php } ?>

        <label for="vi_tri">Vị trí:</label>
        <input type="text" id="vi_tri" name="vi_tri" placeholder="Ví dụ: Kệ A1" value="<?= htmlspecialchars($viTri ?? ''); ?>" class="<?= !empty($loiViTri) ? "input-loi" : ""; ?>">
        <?php if (!empty($loiViTri)) { ?>
            <p class="loi-truong"><?= htmlspecialchars($loiViTri); ?></p>
        <?php } ?>

        <label for="trang_thai">Trạng thái:</label>
        <select id="trang_thai" name="trang_thai">
            <option value="Có sẵn" <?= (($trangThai ?? '') === "Có sẵn") ? "selected" : ""; ?>>Có sẵn</option>
            <option value="Đang mượn" <?= (($trangThai ?? '') === "Đang mượn") ? "selected" : ""; ?>>Đang mượn</option>
            <option value="Hỏng" <?= (($trangThai ?? '') === "Hỏng") ? "selected" : ""; ?>>Hỏng</option>
        </select>

        <button type="submit" class="btn-chinh">
            <?= !empty($editId) ? "Cập nhật bản sao" : "Thêm bản sao"; ?>
        </button>

        <?php if (!empty($editId)) { ?>
            <a href="index.php?controller=bansao" class="huy-sua">Hủy sửa</a>
        <?php } ?>
    </form>

    <div class="ket-qua">
        <h2>DANH SÁCH BẢN SAO SÁCH</h2>
        <table>
            <tr>
                <th>STT</th>
                <th>ID</th>
                <th>Mã bản sao</th>
                <th>Mã sách</th>
                <th>Tên sách</th>
                <th>Vị trí</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            <?php
            $stt = 1;
            foreach ($danhSachBanSao as $banSao) {
                if ($banSao["trang_thai"] === "Có sẵn") {
                    $classTrangThai = "co-san";
                } elseif ($banSao["trang_thai"] === "Đang mượn") {
                    $classTrangThai = "dang-muon";
                } else {
                    $classTrangThai = "hong";
                }
            ?>
                <tr>
                    <td data-label="STT"><?= $stt; ?></td>
                    <td data-label="ID"><?= htmlspecialchars($banSao["id"]); ?></td>
                    <td data-label="Mã bản sao"><?= htmlspecialchars($banSao["ma_ban_sao"]); ?></td>
                    <td data-label="Mã sách"><?= htmlspecialchars($banSao["ma_sach"]); ?></td>
                    <td data-label="Tên sách"><?= htmlspecialchars($banSao["ten_sach"]); ?></td>
                    <td data-label="Vị trí"><?= htmlspecialchars($banSao["vi_tri"]); ?></td>
                    <td data-label="Trạng thái" class="<?= $classTrangThai; ?>"><?= htmlspecialchars($banSao["trang_thai"]); ?></td>
                    <td data-label="Thao tác">
                        <div class="thao-tac">
                            <a class="btn-sua btn-sua-xac-nhan" href="index.php?controller=bansao&edit=<?= $banSao["id"]; ?>" data-ma="<?= htmlspecialchars($banSao["ma_ban_sao"], ENT_QUOTES, "UTF-8"); ?>">
                                Sửa
                            </a>
                            <form method="post" action="index.php?controller=bansao" class="form-xoa" data-ma="<?= htmlspecialchars($banSao["ma_ban_sao"], ENT_QUOTES, "UTF-8"); ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="delete_id" value="<?= $banSao["id"]; ?>">
                                <button type="button" class="btn-xoa btn-xoa-xac-nhan">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php
                $stt++;
            }
            ?>
        </table>
    </div>

    <!-- POPUP XÁC NHẬN -->
    <div class="xac-nhan-overlay" id="popupXacNhan">
        <div class="xac-nhan-hop">
            <div class="xac-nhan-bieu-tuong" id="popupBieuTuong">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <h3 class="xac-nhan-tieu-de" id="popupTieuDe">Xác nhận thao tác</h3>
            <p class="xac-nhan-noi-dung" id="popupNoiDung"></p>
            <div class="xac-nhan-nut">
                <button type="button" class="nut-huy-xac-nhan" id="nutHuyXacNhan">Hủy</button>
                <button type="button" class="nut-dong-y" id="nutDongYXacNhan">Có, xác nhận</button>
            </div>
        </div>
    </div>

    <script>
        const popupXacNhan = document.getElementById("popupXacNhan");
        const popupTieuDe = document.getElementById("popupTieuDe");
        const popupNoiDung = document.getElementById("popupNoiDung");
        const popupBieuTuong = document.getElementById("popupBieuTuong");
        const nutHuyXacNhan = document.getElementById("nutHuyXacNhan");
        const nutDongYXacNhan = document.getElementById("nutDongYXacNhan");
        let hanhDongSauXacNhan = null;

        function moPopupXacNhan(loai, maBanSao, hanhDong) {
            hanhDongSauXacNhan = hanhDong;
            if (loai === "sua") {
                popupBieuTuong.innerHTML = `<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>`;
                popupTieuDe.textContent = "XÁC NHẬN SỬA";
                popupNoiDung.innerHTML = 'Bạn có chắc chắn muốn sửa bản sao <span class="xac-nhan-ma">' + maBanSao + '</span> không?';
                nutDongYXacNhan.textContent = "Có, sửa";
                nutDongYXacNhan.classList.remove("xoa");
            } else {
                popupBieuTuong.innerHTML = `<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>`;
                popupTieuDe.textContent = "XÁC NHẬN XÓA";
                popupNoiDung.innerHTML = 'Bạn có chắc chắn muốn xóa bản sao <span class="xac-nhan-ma">' + maBanSao + '</span> không?';
                nutDongYXacNhan.textContent = "Có, xóa";
                nutDongYXacNhan.classList.add("xoa");
            }
            popupXacNhan.classList.add("hien");
        }

        function dongPopupXacNhan() {
            popupXacNhan.classList.remove("hien");
            hanhDongSauXacNhan = null;
        }

        nutHuyXacNhan.addEventListener("click", dongPopupXacNhan);
        popupXacNhan.addEventListener("click", function(event) {
            if (event.target === popupXacNhan) dongPopupXacNhan();
        });
        document.addEventListener("keydown", function(event) {
            if (event.key === "Escape" && popupXacNhan.classList.contains("hien")) dongPopupXacNhan();
        });

        nutDongYXacNhan.addEventListener("click", function() {
            if (typeof hanhDongSauXacNhan === "function") {
                const hanhDong = hanhDongSauXacNhan;
                dongPopupXacNhan();
                hanhDong();
            }
        });

        document.querySelectorAll(".btn-sua-xac-nhan").forEach(function(nutSua) {
            nutSua.addEventListener("click", function(event) {
                event.preventDefault();
                const duongDan = nutSua.getAttribute("href");
                const maBanSao = nutSua.dataset.ma;
                moPopupXacNhan("sua", maBanSao, function() {
                    window.location.href = duongDan;
                });
            });
        });

        document.querySelectorAll(".btn-xoa-xac-nhan").forEach(function(nutXoa) {
            nutXoa.addEventListener("click", function() {
                const formXoa = nutXoa.closest(".form-xoa");
                const maBanSao = formXoa.dataset.ma;
                moPopupXacNhan("xoa", maBanSao, function() {
                    formXoa.submit();
                });
            });
        });

        setTimeout(function() {
            const thongBao = document.querySelectorAll(".thong-bao");
            thongBao.forEach(function(item) {
                item.classList.add("thong-bao-an");
                setTimeout(function() {
                    item.remove();
                }, 500);
            });
        }, 3000);
    </script>
    </main>
</div>
</body>
</html>
