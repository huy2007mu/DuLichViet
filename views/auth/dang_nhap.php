<?php $_tieu_de_auth = 'Đăng nhập'; require __DIR__ . '/_dau_trang.php'; ?>
<div class="mv-auth container mv-container">
    <div class="mv-auth-card">
        <div class="mv-auth-photo" style="background-image:url('<?= anh_trang_tri('banner-2') ?>')">
            <h2>Chào bạn trở lại!</h2>
            <p class="mb-0 opacity-75">Đăng nhập để đặt tour, theo dõi đơn, chơi mini-game nhận quà và xem hạng thành viên của bạn.</p>
        </div>
        <div class="mv-auth-form">
            <h3 class="fw-bold" style="color:var(--mv-navy)">Đăng nhập</h3>
            <p class="text-secondary mb-4">Chào mừng bạn đến với Mây Việt.</p>

            <?php if (!empty($loi['chung'])): ?>
                <div class="alert alert-danger py-2"><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($loi['chung']) ?></div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>?action=xu-ly-dang-nhap" method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg <?= !empty($loi['email']) ? 'is-invalid' : '' ?>" placeholder="ban@gmail.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <?php if (!empty($loi['email'])): ?><div class="invalid-feedback"><?= $loi['email'] ?></div><?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Mật khẩu</label>
                    <div class="input-group">
                        <input type="password" name="mat_khau" id="mat_khau" class="form-control form-control-lg <?= !empty($loi['mat_khau']) ? 'is-invalid' : '' ?>" placeholder="Nhập mật khẩu">
                        <button type="button" class="btn btn-outline-secondary" id="btn-hien-mk" title="Hiện/ẩn mật khẩu"><i class="bi bi-eye" id="icon-mk"></i></button>
                        <?php if (!empty($loi['mat_khau'])): ?><div class="invalid-feedback"><?= $loi['mat_khau'] ?></div><?php endif; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-cam btn-lg w-100 btn-shine">Đăng nhập</button>
            </form>

            <p class="text-center mt-4 mb-2">Chưa có tài khoản? <a href="<?= BASE_URL ?>?action=dang-ky" class="fw-semibold">Đăng ký ngay</a></p>
            <hr>
            <p class="text-center text-muted mb-0" style="font-size:.8rem">
                Demo Admin: <b>admin@gmail.com</b> / <b>123456</b><br>Demo Khách hàng: <b>khachhang@gmail.com</b> / <b>123456</b>
            </p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('btn-hien-mk').addEventListener('click', function () {
        var i = document.getElementById('mat_khau'), ic = document.getElementById('icon-mk');
        if (i.type === 'password') { i.type = 'text'; ic.className = 'bi bi-eye-slash'; } else { i.type = 'password'; ic.className = 'bi bi-eye'; }
    });
</script>
<script src="<?= BASE_URL ?>assets/js/mayviet.js?v=5"></script>
</body>
</html>
