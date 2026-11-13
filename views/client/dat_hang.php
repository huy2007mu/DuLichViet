<!-- ==================== THANH TOÁN ĐƠN HÀNG ==================== -->

<h4 class="fw-bold mb-4"><i class="bi bi-bag-check me-2"></i>Xác Nhận Đặt Tour</h4>

<?php if (!empty($loi['ton_kho'])): ?>
    <div class="alert alert-danger"><?= $loi['ton_kho'] ?></div>
<?php endif; ?>

<div class="row g-4">
    <!-- THÔNG TIN GIAO HÀNG -->
    <div class="col-lg-7">
        <div class="card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-lines-fill me-2"></i>Thông Tin Liên Hệ</h6>

            <form action="<?= BASE_URL ?>?action=dat-hang-xu-ly" method="POST" id="form-dat-hang">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Họ tên người đặt tour <span class="text-danger">*</span></label>
                    <input type="text" name="ho_ten_nhan" class="form-control <?= !empty($loi['ho_ten_nhan']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['ho_ten_nhan'] ?? $_SESSION['nguoi_dung']['ho_ten'] ?? '') ?>">
                    <?php if (!empty($loi['ho_ten_nhan'])): ?><div class="invalid-feedback"><?= $loi['ho_ten_nhan'] ?></div><?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" name="so_dien_thoai" class="form-control <?= !empty($loi['so_dien_thoai']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['so_dien_thoai'] ?? '') ?>">
                    <?php if (!empty($loi['so_dien_thoai'])): ?><div class="invalid-feedback"><?= $loi['so_dien_thoai'] ?></div><?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Địa chỉ liên hệ <span class="text-danger">*</span></label>
                    <input type="text" name="dia_chi_giao" class="form-control <?= !empty($loi['dia_chi_giao']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['dia_chi_giao'] ?? $_SESSION['nguoi_dung']['dia_chi'] ?? '') ?>"
                           placeholder="Địa chỉ để nhân viên liên hệ / đón khách...">
                    <?php if (!empty($loi['dia_chi_giao'])): ?><div class="invalid-feedback"><?= $loi['dia_chi_giao'] ?></div><?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Ngày khởi hành <span class="text-danger">*</span></label>
                    <input type="date" name="ngay_khoi_hanh" min="<?= date('Y-m-d') ?>"
                           class="form-control <?= !empty($loi['ngay_khoi_hanh']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['ngay_khoi_hanh'] ?? '') ?>">
                    <?php if (!empty($loi['ngay_khoi_hanh'])): ?><div class="invalid-feedback"><?= $loi['ngay_khoi_hanh'] ?></div><?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Ghi chú (tuỳ chọn)</label>
                    <textarea name="ghi_chu" rows="2" class="form-control" placeholder="Ví dụ: có trẻ em đi cùng, ăn chay, cần xe đón tận nơi..."><?= htmlspecialchars($_POST['ghi_chu'] ?? '') ?></textarea>
                </div>

                <div class="alert alert-dark border">
                    <i class="bi bi-cash-coin me-2"></i>Thanh toán trực tiếp tại văn phòng hoặc khi nhân viên liên hệ xác nhận — chưa hỗ trợ thanh toán online.
                </div>
            </form>
        </div>
    </div>

    <!-- TÓM TẮT ĐƠN HÀNG -->
    <div class="col-lg-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Tour Bạn Chọn</h6>

            <?php foreach ($gio_hang as $sp): ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <img src="<?= anh_san_pham($sp['hinh_anh'], $sp['ten_san_pham'] ?? '') ?>" class="img-thumbnail-sm" style="width:52px;height:52px;max-width:52px;object-fit:cover;flex:0 0 52px;border-radius:9px">
                        <div>
                            <div style="font-size:.85rem" class="fw-semibold"><?= htmlspecialchars($sp['ten_san_pham']) ?></div>
                            <small class="text-muted">Khách: <?= $sp['so_luong_dat'] ?></small>
                        </div>
                    </div>
                    <span style="font-size:.85rem"><?= dinh_dang_tien($sp['gia'] * $sp['so_luong_dat']) ?></span>
                </div>
            <?php endforeach; ?>

            <hr>

            <!-- MÃ VOUCHER -->
            <?php if ($voucher_ap_dung): ?>
                <div class="alert alert-success d-flex justify-content-between align-items-center py-2 px-3">
                    <span><i class="bi bi-ticket-perforated me-1"></i><b><?= htmlspecialchars($voucher_ap_dung['ma_voucher']) ?></b> đã áp dụng</span>
                    <a href="<?= BASE_URL ?>?action=huy-voucher" class="text-danger small">Huỷ</a>
                </div>
            <?php else: ?>
                <form action="<?= BASE_URL ?>?action=ap-dung-voucher" method="POST" class="d-flex gap-2 mb-3">
                    <input type="text" name="ma_voucher" class="form-control form-control-sm" placeholder="Nhập mã giảm giá...">
                    <button type="submit" class="btn btn-sm btn-vien-cam">Áp dụng</button>
                </form>
            <?php endif; ?>

            <div class="d-flex justify-content-between mb-1">
                <span>Tạm tính:</span><span><?= dinh_dang_tien($tam_tinh) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-1 text-success">
                <span>Giảm giá:</span><span>- <?= dinh_dang_tien($tien_giam) ?></span>
            </div>
            <hr>
            <div class="d-flex justify-content-between fs-5 fw-bold mb-3">
                <span>Tổng cộng:</span>
                <span class="text-danger"><?= dinh_dang_tien($tam_tinh - $tien_giam) ?></span>
            </div>

            <button type="submit" form="form-dat-hang" class="btn btn-cam w-100 py-2">
                <i class="bi bi-check-circle me-2"></i>Đặt Tour
            </button>
        </div>
    </div>
</div>
