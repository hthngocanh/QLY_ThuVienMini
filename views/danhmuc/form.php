<?php
// src/View/danhmuc/form.php
// Được index.php require, chỉ dùng cho vai trò Thủ thư.
// Các biến $tenDanhMuc, $moTa, $errors, $danhMucDangSua
// đã được CategoryController truyền qua renderView().
?>

<div class="modal-overlay" id="categoryModalOverlay">
    <div class="modal-box">

        <div class="modal-header">
            <h2 class="modal-title" id="categoryModalTitle">
                Thêm danh mục
            </h2>
            <button type="button" class="modal-close" onclick="closeCategoryModal()" aria-label="Đóng">
                &times;
            </button>
        </div>

        <div class="modal-body">

            <form method="POST" action="index.php?controller=danhmuc" id="categoryForm">

                <input type="hidden"
                       name="action"
                       id="categoryFormAction"
                       value="<?= $danhMucDangSua ? 'sua' : 'them' ?>">

                <input type="hidden"
                       name="category_id"
                       id="categoryFormId"
                       value="<?= $danhMucDangSua ? (int)$danhMucDangSua['category_id'] : '' ?>">

                <div class="form-group">
                    <label class="form-label">
                        Tên danh mục
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="ten_danh_muc"
                        id="categoryFormTen"
                        class="form-input <?= isset($errors['ten_danh_muc']) ? 'error' : '' ?>"
                        value="<?= escape($tenDanhMuc) ?>"
                        maxlength="100"
                        placeholder="Nhập tên danh mục"
                        required
                    >

                    <?php if (!empty($errors['ten_danh_muc'])): ?>
                        <div class="error-message"><?= escape($errors['ten_danh_muc']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Mô tả</label>

                    <textarea
                        name="mo_ta"
                        id="categoryFormMoTa"
                        class="form-textarea <?= isset($errors['mo_ta']) ? 'error' : '' ?>"
                        maxlength="255"
                        placeholder="Nhập mô tả cho danh mục"
                    ><?= escape($moTa) ?></textarea>

                    <?php if (!empty($errors['mo_ta'])): ?>
                        <div class="error-message"><?= escape($errors['mo_ta']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="categoryFormSubmitBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        <?= $danhMucDangSua ? 'Lưu thay đổi' : 'Thêm danh mục' ?>
                    </button>

                    <button type="button" class="btn btn-secondary" onclick="closeCategoryModal()">
                        Hủy
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>