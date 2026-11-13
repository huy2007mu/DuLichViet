<!-- ==================== FORM THÊM / SỬA TÀI KHOẢN ==================== -->

<?php $dang_sua = isset($nguoi_dung); ?>

<div class="card" style="max-width:640px">
    <div class="card-header bg-white">
        <i class="bi bi-people me-2"></i><?= $dang_sua ? 'Sửa Tài Khoản' : 'Thêm Tài Khoản Mới' ?>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>?action=admin-nguoi-dung-<?= $dang_sua ? 'cap-nhat' : 'luu' ?>" method="POST">

            <?php if ($dang_sua): ?>
                <input type="hidden" name="id" value="<?= $nguoi_dung['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">Họ Tên <span class="text-danger">*</span></label>
                <input type="text" name="ho_ten" class="form-control <?= !empty($loi['ho_ten']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($_POST['ho_ten'] ?? $nguoi_dung['ho_ten'] ?? '') ?>">
                <?php if (!empty($loi['ho_ten'])): ?><div class="invalid-feedback"><?= $loi['ho_ten'] ?></div><?php endif; ?>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control <?= !empty($loi['email']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['email'] ?? $nguoi_dung['email'] ?? '') ?>">
                    <?php if (!empty($loi['email'])): ?><div class="invalid-feedback"><?= $loi['email'] ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Số Điện Thoại</label>
                    <input type="text" name="so_dien_thoai" class="form-control"
                           value="<?= htmlspecialchars($_POST['so_dien_thoai'] ?? $nguoi_dung['so_dien_thoai'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label fw-semibold">Địa Chỉ</label>
                <input type="text" name="dia_chi" class="form-control"
                       value="<?= htmlspecialchars($_POST['dia_chi'] ?? $nguoi_dung['dia_chi'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Mật Khẩu <?= $dang_sua ? '<small class="text-muted">(để trống nếu không đổi)</small>' : '<span class="text-danger">*</span>' ?>
                </label>
                <input type="password" name="mat_khau" class="form-control <?= !empty($loi['mat_khau']) ? 'is-invalid' : '' ?>">
                <?php if (!empty($loi['mat_khau'])): ?><div class="invalid-feedback"><?= $loi['mat_khau'] ?></div><?php endif; ?>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Vai Trò <span class="text-danger">*</span></label>
                    <?php $vt = $_POST['vai_tro'] ?? $nguoi_dung['vai_tro'] ?? 'khach_hang'; ?>
                    <select name="vai_tro" class="form-select <?= !empty($loi['vai_tro']) ? 'is-invalid' : '' ?>">
                        <option value="khach_hang" <?= $vt === 'khach_hang' ? 'selected' : '' ?>>Khách hàng</option>
                        <option value="admin" <?= $vt === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <?php if (!empty($loi['vai_tro'])): ?><div class="invalid-feedback"><?= $loi['vai_tro'] ?></div><?php endif; ?>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="trang_thai" id="trang_thai"
                               <?= (!$dang_sua || ($nguoi_dung['trang_thai'] ?? 1)) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="trang_thai">Hoạt động (bỏ chọn để khoá tài khoản)</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-1"></i><?= $dang_sua ? 'Cập Nhật' : 'Lưu Tài Khoản' ?>
                </button>
                <a href="<?= BASE_URL ?>?action=admin-nguoi-dung" class="btn btn-outline-secondary px-4">Huỷ</a>
            </div>
        </form>
    </div>
</div>
