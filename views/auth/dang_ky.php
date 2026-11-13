<?php $_tieu_de_auth = 'Đăng ký'; require __DIR__ . '/_dau_trang.php'; ?>
<div class="mv-auth container mv-container">
    <div class="mv-auth-card" style="max-width:1040px">
        <div class="mv-auth-photo" style="background-image:url('<?= anh_trang_tri('banner-4') ?>')">
            <h2>Thêm một chuyến đi, thêm một kỷ niệm đẹp.</h2>
            <p class="mb-0 opacity-75">Tạo tài khoản miễn phí để nhận voucher chào mừng, tích điểm và lên hạng thành viên Explorer → Traveler → Globetrotter.</p>
        </div>
        <div class="mv-auth-form" style="padding:36px 40px">
            <h3 class="fw-bold" style="color:var(--mv-navy)">Tạo tài khoản mới</h3>
            <p class="text-secondary mb-4">Đăng ký để đặt tour và theo dõi đơn đặt tour.</p>
            <form action="<?= BASE_URL ?>?action=xu-ly-dang-ky" method="POST" novalidate>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Họ và tên</label>
                    <input type="text" name="ho_ten" class="form-control form-control-lg <?= !empty($loi['ho_ten']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['ho_ten'] ?? '') ?>" placeholder="Nguyễn Văn A">
                    <?php if (!empty($loi['ho_ten'])): ?><div class="invalid-feedback"><?= $loi['ho_ten'] ?></div><?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" class="form-control form-control-lg <?= !empty($loi['so_dien_thoai']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['so_dien_thoai'] ?? '') ?>" placeholder="09xxxxxxxx">
                    <?php if (!empty($loi['so_dien_thoai'])): ?><div class="invalid-feedback"><?= $loi['so_dien_thoai'] ?></div><?php endif; ?>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg <?= !empty($loi['email']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="ban@gmail.com">
                    <?php if (!empty($loi['email'])): ?><div class="invalid-feedback"><?= $loi['email'] ?></div><?php endif; ?>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Địa chỉ</label>
                    <input type="text" name="dia_chi" class="form-control form-control-lg"
                           value="<?= htmlspecialchars($_POST['dia_chi'] ?? '') ?>" placeholder="Địa chỉ để nhân viên liên hệ / đón khách...">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mật khẩu</label>
                    <input type="password" name="mat_khau" class="form-control form-control-lg <?= !empty($loi['mat_khau']) ? 'is-invalid' : '' ?>" placeholder="Ít nhất 6 ký tự">
                    <?php if (!empty($loi['mat_khau'])): ?><div class="invalid-feedback"><?= $loi['mat_khau'] ?></div><?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
                    <input type="password" name="xac_nhan_mat_khau" class="form-control form-control-lg <?= !empty($loi['xac_nhan_mat_khau']) ? 'is-invalid' : '' ?>" placeholder="Nhập lại mật khẩu">
                    <?php if (!empty($loi['xac_nhan_mat_khau'])): ?><div class="invalid-feedback"><?= $loi['xac_nhan_mat_khau'] ?></div><?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-cam btn-lg w-100 mt-4 btn-shine">
                Đăng Ký
            </button>
        </form>
            <p class="text-center mt-3 mb-0">Đã có tài khoản? <a href="<?= BASE_URL ?>?action=dang-nhap" class="fw-semibold">Đăng nhập</a></p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/mayviet.js?v=5"></script>
</body>
</html>
