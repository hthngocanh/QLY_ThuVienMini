<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Phiếu Mượn - Thủ thư</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 text-primary font-weight-bold"><i class="fa-solid fa-book-bookmark me-2"></i>Quản Lý Phiếu Mượn Sách</h2>
    </div>

    <?php if (!empty($thongBao)): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($thongBao) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= htmlspecialchars($errors['general']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- FORM THÊM / SỬA -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white font-weight-bold">
                    <i class="fa-solid <?= isset($id) && $id > 0 ? 'fa-pen-to-square' : 'fa-plus-circle' ?> me-1"></i>
                    <?= isset($id) && $id > 0 ? 'Cập Nhật Phiếu Mượn #' . $id : 'Thêm Phiếu Mượn Mới' ?>
                </div>
                <div class="card-body">
                    <form action="index.php?controller=phieumuon" method="POST">
                        <input type="hidden" name="action" value="<?= isset($id) && $id > 0 ? 'edit' : 'add' ?>">
                        <input type="hidden" name="id" value="<?= $id ?? '' ?>">

                        <div class="mb-3">
                            <label for="ma_nguoi_dung" class="form-label font-weight-bold">Mã người dùng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['ma_nguoi_dung']) ? 'is-invalid' : '' ?>" 
                                   id="ma_nguoi_dung" name="ma_nguoi_dung" value="<?= htmlspecialchars($maNguoiDung ?? '') ?>" placeholder="VD: ND001">
                            <?php if (isset($errors['ma_nguoi_dung'])): ?>
                                <div class="invalid-feedback"><?= $errors['ma_nguoi_dung'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="ma_ban_sao" class="form-label font-weight-bold">Mã bản sao sách <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['ma_ban_sao']) ? 'is-invalid' : '' ?>" 
                                   id="ma_ban_sao" name="ma_ban_sao" value="<?= htmlspecialchars($maBanSao ?? '') ?>" placeholder="VD: BS001">
                            <?php if (isset($errors['ma_ban_sao'])): ?>
                                <div class="invalid-feedback"><?= $errors['ma_ban_sao'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="ngay_muon" class="form-label font-weight-bold">Ngày mượn <span class="text-danger">*</span></label>
                            <input type="date" class="form-control <?= isset($errors['ngay_muon']) ? 'is-invalid' : '' ?>" 
                                   id="ngay_muon" name="ngay_muon" value="<?= htmlspecialchars($ngayMuon ?? date('Y-m-d')) ?>">
                            <?php if (isset($errors['ngay_muon'])): ?>
                                <div class="invalid-feedback"><?= $errors['ngay_muon'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="ngay_tra" class="form-label font-weight-bold">Ngày trả thực tế</label>
                            <input type="date" class="form-control <?= isset($errors['ngay_tra']) ? 'is-invalid' : '' ?>" 
                                   id="ngay_tra" name="ngay_tra" value="<?= htmlspecialchars($ngayTra ?? '') ?>">
                            <?php if (isset($errors['ngay_tra'])): ?>
                                <div class="invalid-feedback"><?= $errors['ngay_tra'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="trang_thai" class="form-label font-weight-bold">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($errors['trang_thai']) ? 'is-invalid' : '' ?>" id="trang_thai" name="trang_thai">
                                <?php
                                $dsTrangThai = ['Chờ duyệt', 'Đang mượn', 'Quá hạn', 'Đã trả'];
                                foreach ($dsTrangThai as $tt):
                                ?>
                                    <option value="<?= $tt ?>" <?= ($trangThai ?? 'Chờ duyệt') === $tt ? 'selected' : '' ?>><?= $tt ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['trang_thai'])): ?>
                                <div class="invalid-feedback"><?= $errors['trang_thai'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> <?= isset($id) && $id > 0 ? 'Lưu cập nhật' : 'Thêm mới' ?>
                            </button>
                            <?php if (isset($id) && $id > 0): ?>
                                <a href="index.php?controller=phieumuon" class="btn btn-outline-secondary">Hủy bỏ</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- BẢNG DANH SÁCH -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold py-3">
                    <i class="fa-solid fa-list me-1 text-primary"></i> Danh Sách Phiếu Mượn Trong Hệ Thống
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã PM</th>
                                    <th>Độc giả</th>
                                    <th>Mã BS / Tên sách</th>
                                    <th>Ngày mượn</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($danhSachPhieuMuon)): ?>
                                    <?php foreach ($danhSachPhieuMuon as $pm): ?>
                                        <tr>
                                            <td><strong>#<?= $pm['ID_PhieuMuon'] ?></strong></td>
                                            <td>
                                                <div class="fw-bold"><?= htmlspecialchars($pm['ho_ten'] ?? '') ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($pm['ma_nguoi_dung'] ?? '') ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-primary"><?= htmlspecialchars($pm['ten_sach'] ?? '') ?></div>
                                                <small class="badge bg-light text-dark border"><?= htmlspecialchars($pm['ma_ban_sao'] ?? '') ?></small>
                                            </td>
                                            <td>
                                                <div><?= date('d/m/Y', strtotime($pm['NgayMuon'])) ?></div>
                                                <?php if (!empty($pm['NgayTra'])): ?>
                                                    <small class="text-success">Trả: <?= date('d/m/Y', strtotime($pm['NgayTra'])) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $badgeClass = match ($pm['TrangThai']) {
                                                    'Chờ duyệt' => 'bg-warning text-dark',
                                                    'Đang mượn' => 'bg-info text-dark',
                                                    'Quá hạn'   => 'bg-danger',
                                                    'Đã trả'    => 'bg-success',
                                                    default     => 'bg-secondary'
                                                };
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($pm['TrangThai']) ?></span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="index.php?controller=phieumuon&edit=<?= $pm['ID_PhieuMuon'] ?>" class="btn btn-outline-primary" title="Sửa"><i class="fa-solid fa-pen"></i></a>
                                                    <form action="index.php?controller=phieumuon" method="POST" onsubmit="return confirm('Xác nhận xóa phiếu mượn này?');" class="d-inline">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?= $pm['ID_PhieuMuon'] ?>">
                                                        <button type="submit" class="btn btn-outline-danger" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu phiếu mượn.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>