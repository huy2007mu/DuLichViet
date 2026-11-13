<!-- ==================== FORM THÊM / SỬA VOUCHER ==================== -->

<?php $dang_sua = isset($voucher); ?>

<div class="card" style="max-width:720px">
    <div class="card-header bg-white">
        <i class="bi bi-ticket-perforated me-2"></i><?= $dang_sua ? 'Sửa Voucher' : 'Thêm Voucher Mới' ?>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>?action=admin-voucher-<?= $dang_sua ? 'cap-nhat' : 'luu' ?>" method="POST">

            <?php if ($dang_sua): ?>
                <input type="hidden" name="id" value="<?= $voucher['id'] ?>">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mã Voucher <span class="text-danger">*</span></label>
                    <input type="text" name="ma_voucher" style="text-transform:uppercase"
                           class="form-control <?= !empty($loi['ma_voucher']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['ma_voucher'] ?? $voucher['ma_voucher'] ?? '') ?>" placeholder="VD: SALE20">
                    <?php if (!empty($loi['ma_voucher'])): ?><div class="invalid-feedback"><?= $loi['ma_voucher'] ?></div><?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Loại Giảm Giá</label>
                    <?php $loai = $_POST['loai_giam'] ?? $voucher['loai_giam'] ?? 'phan_tram'; ?>
                    <select name="loai_giam" class="form-select">
                        <option value="phan_tram" <?= $loai === 'phan_tram' ? 'selected' : '' ?>>Theo phần trăm (%)</option>
                        <option value="tien_mat" <?= $loai === 'tien_mat' ? 'selected' : '' ?>>Số tiền cố định (₫)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Giá Trị Giảm <span class="text-danger">*</span></label>
                    <input type="number" step="1" name="gia_tri"
                           class="form-control <?= !empty($loi['gia_tri']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['gia_tri'] ?? $voucher['gia_tri'] ?? '') ?>">
                    <?php if (!empty($loi['gia_tri'])): ?><div class="invalid-feedback"><?= $loi['gia_tri'] ?></div><?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Giảm Tối Đa (₫) <small class="text-muted">(chỉ áp dụng loại %)</small></label>
                    <input type="number" step="1000" name="giam_toi_da" class="form-control"
                           value="<?= htmlspecialchars($_POST['giam_toi_da'] ?? $voucher['giam_toi_da'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Đơn Đặt Tour Tối Thiểu (₫)</label>
                    <input type="number" step="1000" name="don_toi_thieu" class="form-control"
                           value="<?= htmlspecialchars($_POST['don_toi_thieu'] ?? $voucher['don_toi_thieu'] ?? 0) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Số Lượng Mã <span class="text-danger">*</span></label>
                    <input type="number" name="so_luong"
                           class="form-control <?= !empty($loi['so_luong']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['so_luong'] ?? $voucher['so_luong'] ?? 100) ?>">
                    <?php if (!empty($loi['so_luong'])): ?><div class="invalid-feedback"><?= $loi['so_luong'] ?></div><?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ngày Bắt Đầu</label>
                    <input type="date" name="ngay_bat_dau" class="form-control"
                           value="<?= htmlspecialchars($_POST['ngay_bat_dau'] ?? $voucher['ngay_bat_dau'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ngày Kết Thúc</label>
                    <input type="date" name="ngay_ket_thuc"
                           class="form-control <?= !empty($loi['ngay_ket_thuc']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['ngay_ket_thuc'] ?? $voucher['ngay_ket_thuc'] ?? '') ?>">
                    <?php if (!empty($loi['ngay_ket_thuc'])): ?><div class="invalid-feedback"><?= $loi['ngay_ket_thuc'] ?></div><?php endif; ?>
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="trang_thai" id="trang_thai"
                               <?= (!$dang_sua || ($voucher['trang_thai'] ?? 1)) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="trang_thai">Kích hoạt voucher</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-1"></i><?= $dang_sua ? 'Cập Nhật' : 'Lưu Voucher' ?>
                </button>
                <a href="<?= BASE_URL ?>?action=admin-voucher" class="btn btn-outline-secondary px-4">Huỷ</a>
            </div>
        </form>
    </div>
</div>
