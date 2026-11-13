<!-- ==================== FORM THÊM / SỬA SẢN PHẨM ==================== -->

<?php $dang_sua = isset($san_pham); ?>

<div class="card" style="max-width:760px">
    <div class="card-header bg-white">
        <i class="bi bi-geo-alt me-2"></i><?= $dang_sua ? 'Sửa Tour' : 'Thêm Tour Mới' ?>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>?action=admin-san-pham-<?= $dang_sua ? 'cap-nhat' : 'luu' ?>" method="POST" enctype="multipart/form-data">

            <?php if ($dang_sua): ?>
                <input type="hidden" name="id" value="<?= $san_pham['id'] ?>">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tên Tour <span class="text-danger">*</span></label>
                    <input type="text" name="ten_san_pham"
                           class="form-control <?= !empty($loi['ten_san_pham']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['ten_san_pham'] ?? $san_pham['ten_san_pham'] ?? '') ?>">
                    <?php if (!empty($loi['ten_san_pham'])): ?><div class="invalid-feedback"><?= $loi['ten_san_pham'] ?></div><?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Điểm Đến <span class="text-danger">*</span></label>
                    <select name="danh_muc_id" class="form-select <?= !empty($loi['danh_muc_id']) ? 'is-invalid' : '' ?>">
                        <option value="">-- Chọn điểm đến --</option>
                        <?php
                        $dm_chon = $_POST['danh_muc_id'] ?? $san_pham['danh_muc_id'] ?? '';
                        foreach ($danh_sach_danh_muc as $dm): ?>
                            <option value="<?= $dm['id'] ?>" <?= $dm_chon == $dm['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dm['ten_danh_muc']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($loi['danh_muc_id'])): ?><div class="invalid-feedback"><?= $loi['danh_muc_id'] ?></div><?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Giá Tour / Khách (₫) <span class="text-danger">*</span></label>
                    <input type="number" step="1000" name="gia"
                           class="form-control <?= !empty($loi['gia']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['gia'] ?? $san_pham['gia'] ?? '') ?>">
                    <?php if (!empty($loi['gia'])): ?><div class="invalid-feedback"><?= $loi['gia'] ?></div><?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Số Chỗ Còn Nhận <span class="text-danger">*</span></label>
                    <input type="number" name="so_luong"
                           class="form-control <?= !empty($loi['so_luong']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['so_luong'] ?? $san_pham['so_luong'] ?? '') ?>">
                    <?php if (!empty($loi['so_luong'])): ?><div class="invalid-feedback"><?= $loi['so_luong'] ?></div><?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Thời Gian</label>
                    <input type="text" name="thoi_gian" class="form-control" placeholder="VD: 3 ngày 2 đêm"
                           value="<?= htmlspecialchars($_POST['thoi_gian'] ?? $san_pham['thoi_gian'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Khởi Hành Từ</label>
                    <input type="text" name="diem_khoi_hanh" class="form-control" placeholder="VD: Hà Nội"
                           value="<?= htmlspecialchars($_POST['diem_khoi_hanh'] ?? $san_pham['diem_khoi_hanh'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Phương Tiện</label>
                    <input type="text" name="phuong_tien" class="form-control" placeholder="VD: Máy bay / Xe du lịch"
                           value="<?= htmlspecialchars($_POST['phuong_tien'] ?? $san_pham['phuong_tien'] ?? '') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Mô Tả / Lịch Trình Tour</label>
                    <textarea name="mo_ta" rows="4" class="form-control"
                              placeholder="Điểm nhấn, lịch trình từng ngày, dịch vụ bao gồm / không bao gồm..."><?= htmlspecialchars($_POST['mo_ta'] ?? $san_pham['mo_ta'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Hình Ảnh Tour</label>
                    <input type="file" name="hinh_anh" class="form-control <?= !empty($loi['hinh_anh']) ? 'is-invalid' : '' ?>" accept="image/*">
                    <?php if (!empty($loi['hinh_anh'])): ?><div class="invalid-feedback d-block"><?= $loi['hinh_anh'] ?></div><?php endif; ?>
                    <?php if ($dang_sua && !empty($san_pham['hinh_anh'])): ?>
                        <img src="<?= anh_san_pham($san_pham['hinh_anh'], $san_pham['ten_san_pham'] ?? '') ?>" class="img-thumbnail-sm mt-2" style="width:90px;height:90px">
                        <div class="form-text">Để trống nếu không muốn đổi ảnh</div>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="trang_thai" id="trang_thai"
                               <?= (isset($_POST['trang_thai']) || (!isset($_POST['ten_san_pham']) && ($san_pham['trang_thai'] ?? 1))) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="trang_thai">Đang mở bán (hiển thị cho khách hàng)</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-1"></i><?= $dang_sua ? 'Cập Nhật' : 'Lưu Tour' ?>
                </button>
                <a href="<?= BASE_URL ?>?action=admin-san-pham" class="btn btn-outline-secondary px-4">Huỷ</a>
            </div>
        </form>
    </div>
</div>
