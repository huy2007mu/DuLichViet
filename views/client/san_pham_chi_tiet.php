<!-- ==================== CHI TIẾT SẢN PHẨM ==================== -->

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?action=san-pham&danh_muc_id=<?= $san_pham['danh_muc_id'] ?>" class="text-decoration-none"><?= htmlspecialchars($san_pham['ten_danh_muc'] ?? '') ?></a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($san_pham['ten_san_pham']) ?></li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card p-2 detail-photo-frame">
            <img src="<?= anh_san_pham($san_pham['hinh_anh'], $san_pham['ten_san_pham'] ?? '') ?>" alt="<?= htmlspecialchars($san_pham['ten_san_pham']) ?>" class="img-fluid rounded" style="aspect-ratio:4/3;object-fit:cover;width:100%">
        </div>
    </div>

    <div class="col-md-7">
        <h2 class="fw-bold mb-2" style="font-size:1.5rem"><?= htmlspecialchars($san_pham['ten_san_pham']) ?></h2>

        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="badge-sao"><?= hien_thi_sao(round($san_pham['diem_trung_binh'])) ?></span>
            <span class="text-muted small">(<?= (int)$san_pham['so_luot_danh_gia'] ?> đánh giá)</span>
            <span class="text-muted small">· <?= (int)$san_pham['luot_xem'] ?> lượt xem</span>
        </div>

        <div class="fs-3 fw-bold mb-3" style="color:var(--mau-chinh)"><?= dinh_dang_tien($san_pham['gia']) ?> <small class="text-muted fs-6 fw-normal">/ khách</small></div>

        <p class="mb-1">
            <b>Tình trạng: </b>
            <?php if ($san_pham['so_luong'] > 0): ?>
                <span class="text-success"><i class="bi bi-check-circle-fill"></i> Còn <?= $san_pham['so_luong'] ?> chỗ</span>
            <?php else: ?>
                <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Tạm hết chỗ</span>
            <?php endif; ?>
        </p>
        <p class="mb-1"><b>Điểm đến:</b> <?= htmlspecialchars($san_pham['ten_danh_muc'] ?? '') ?></p>
        <?php if (!empty($san_pham['thoi_gian'])): ?><p class="mb-1"><b><i class="bi bi-clock me-1"></i>Thời gian:</b> <?= htmlspecialchars($san_pham['thoi_gian']) ?></p><?php endif; ?>
        <?php if (!empty($san_pham['diem_khoi_hanh'])): ?><p class="mb-1"><b><i class="bi bi-geo-alt me-1"></i>Khởi hành từ:</b> <?= htmlspecialchars($san_pham['diem_khoi_hanh']) ?></p><?php endif; ?>
        <?php if (!empty($san_pham['phuong_tien'])): ?><p class="mb-1"><b><i class="bi bi-bus-front me-1"></i>Phương tiện:</b> <?= htmlspecialchars($san_pham['phuong_tien']) ?></p><?php endif; ?>
        <div class="mb-4"></div>

        <form action="<?= BASE_URL ?>?action=gio-hang-them" method="POST" id="form-them-gio-hang">
            <input type="hidden" name="san_pham_id" value="<?= $san_pham['id'] ?>">
            <input type="hidden" name="ve_trang" value="<?= BASE_URL ?>?action=san-pham-chi-tiet&id=<?= $san_pham['id'] ?>">

            <div class="d-flex align-items-center gap-3">
                <div class="input-group" style="width:140px">
                    <button type="button" class="btn btn-outline-secondary" id="btn-giam-sl" <?= $san_pham['so_luong'] <= 0 ? 'disabled' : '' ?>>−</button>
                    <input type="number" name="so_luong" id="input-so-luong" value="1" min="1" max="<?= max(1, $san_pham['so_luong']) ?>"
                           class="form-control text-center" <?= $san_pham['so_luong'] <= 0 ? 'disabled' : '' ?>>
                    <button type="button" class="btn btn-outline-secondary" id="btn-tang-sl" <?= $san_pham['so_luong'] <= 0 ? 'disabled' : '' ?>>+</button>
                </div>

                <button type="submit" class="btn btn-cam px-4" <?= $san_pham['so_luong'] <= 0 ? 'disabled' : '' ?>>
                    <i class="bi bi-suitcase-lg me-2"></i>Thêm Vào Giỏ Tour
                </button>

                <?php $_da_yeu_thich = dang_yeu_thich($san_pham['id']); ?>
                <a href="<?= BASE_URL ?>?action=yeu-thich-toggle&san_pham_id=<?= $san_pham['id'] ?>&ve_trang=<?= urlencode(BASE_URL . '?action=san-pham-chi-tiet&id=' . $san_pham['id']) ?>"
                   class="btn btn-outline-danger px-3" title="<?= $_da_yeu_thich ? 'Bỏ khỏi yêu thích' : 'Thêm vào yêu thích' ?>">
                    <i class="bi <?= $_da_yeu_thich ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                </a>
            </div>

            <div class="mt-3 d-flex align-items-center gap-2">
                <span class="text-muted">Tạm tính (số khách):</span>
                <span class="fs-5 fw-bold" id="tam-tinh-chon" style="color:var(--mau-chinh)" data-gia="<?= (int)$san_pham['gia'] ?>">
                    <?= dinh_dang_tien($san_pham['gia']) ?>
                </span>
            </div>
        </form>

        <script>
            (function () {
                const input      = document.getElementById('input-so-luong');
                const max        = parseInt(input.getAttribute('max') || 1, 10);
                const tamTinhEl  = document.getElementById('tam-tinh-chon');
                const gia        = parseFloat(tamTinhEl.dataset.gia) || 0;

                function dinhDangTien(so) {
                    return Math.round(so).toLocaleString('vi-VN') + ' ₫';
                }

                function capNhatTamTinh() {
                    const sl = parseInt(input.value || 1, 10);
                    tamTinhEl.textContent = dinhDangTien(gia * sl);
                }

                document.getElementById('btn-tang-sl')?.addEventListener('click', function () {
                    let v = parseInt(input.value || 1, 10);
                    if (v < max) { input.value = v + 1; capNhatTamTinh(); }
                });
                document.getElementById('btn-giam-sl')?.addEventListener('click', function () {
                    let v = parseInt(input.value || 1, 10);
                    if (v > 1) { input.value = v - 1; capNhatTamTinh(); }
                });
                input.addEventListener('input', function () {
                    let v = parseInt(input.value || 1, 10);
                    if (isNaN(v) || v < 1) v = 1;
                    if (v > max) v = max;
                    input.value = v;
                    capNhatTamTinh();
                });
            })();
        </script>
    </div>
</div>

<!-- MÔ TẢ SẢN PHẨM -->
<div class="card mt-5">
    <div class="card-header fw-bold"><i class="bi bi-file-text me-2"></i>Giới Thiệu & Lịch Trình</div>
    <div class="card-body">
        <?= nl2br(htmlspecialchars($san_pham['mo_ta'] ?? 'Đang cập nhật lịch trình tour.')) ?>
    </div>
</div>

<!-- ĐÁNH GIÁ SẢN PHẨM -->
<div class="card mt-4">
    <div class="card-header fw-bold">
        <i class="bi bi-star me-2"></i>Đánh Giá Từ Khách Hàng (<?= count($danh_sach_danh_gia) ?>)
    </div>
    <div class="card-body">
        <?php if (empty($danh_sach_danh_gia)): ?>
            <p class="text-muted mb-0">Chưa có đánh giá nào cho tour này. Hãy là người đầu tiên trải nghiệm và chia sẻ!</p>
        <?php else: foreach ($danh_sach_danh_gia as $dg): ?>
            <div class="d-flex gap-3 border-bottom pb-3 mb-3">
                <i class="bi bi-person-circle text-secondary" style="font-size:2rem"></i>
                <div>
                    <div class="fw-semibold"><?= htmlspecialchars($dg['ten_khach']) ?></div>
                    <div class="badge-sao mb-1"><?= hien_thi_sao($dg['so_sao']) ?></div>
                    <p class="mb-1"><?= nl2br(htmlspecialchars($dg['noi_dung'])) ?></p>
                    <small class="text-muted"><?= dinh_dang_ngay($dg['ngay_tao']) ?></small>
                </div>
            </div>
        <?php endforeach; endif; ?>
        <p class="text-muted small mb-0 mt-2">
            <i class="bi bi-info-circle me-1"></i>Bạn chỉ có thể đánh giá tour sau khi chuyến đi đã hoàn thành (xem tại "Tour của tôi").
        </p>
    </div>
</div>

<!-- SẢN PHẨM LIÊN QUAN -->
<?php if (!empty($san_pham_lien_quan)): ?>
<div class="mt-5">
    <h5 class="section-title mb-4">Tour Cùng Điểm Đến</h5>
    <div class="row g-3">
        <?php foreach ($san_pham_lien_quan as $sp): include __DIR__ . '/_the_san_pham.php'; endforeach; ?>
    </div>
</div>
<?php endif; ?>
