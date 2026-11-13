<!-- ==================== FORM THÊM / SỬA DANH MỤC ==================== -->

<?php $dang_sua = isset($danh_muc); ?>

<div class="card" style="max-width:640px">
    <div class="card-header bg-white">
        <i class="bi bi-tag me-2"></i><?= $dang_sua ? 'Sửa Điểm Đến' : 'Thêm Điểm Đến Mới' ?>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>?action=admin-danh-muc-<?= $dang_sua ? 'cap-nhat' : 'luu' ?>" method="POST">

            <?php if ($dang_sua): ?>
                <input type="hidden" name="id" value="<?= $danh_muc['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tên Điểm Đến <span class="text-danger">*</span></label>
                <input type="text" name="ten_danh_muc"
                       class="form-control <?= !empty($loi['ten_danh_muc']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($_POST['ten_danh_muc'] ?? $danh_muc['ten_danh_muc'] ?? '') ?>"
                       placeholder="VD: Điện thoại, Laptop...">
                <?php if (!empty($loi['ten_danh_muc'])): ?>
                    <div class="invalid-feedback"><?= $loi['ten_danh_muc'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Mô Tả</label>
                <textarea name="mo_ta" class="form-control" rows="3"
                          placeholder="Mô tả ngắn về điểm đến..."><?= htmlspecialchars($_POST['mo_ta'] ?? $danh_muc['mo_ta'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-circle me-1"></i><?= $dang_sua ? 'Cập Nhật' : 'Lưu Điểm Đến' ?>
            </button>
            <a href="<?= BASE_URL ?>?action=admin-danh-muc" class="btn btn-outline-secondary px-4">Huỷ</a>
        </form>
    </div>
</div>
