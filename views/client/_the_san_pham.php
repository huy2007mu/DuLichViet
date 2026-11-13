<?php
// Thẻ hiển thị 1 tour - component dùng chung, biến $sp phải tồn tại khi include
$_het_hang = $sp['so_luong'] <= 0;
$_da_yeu_thich = dang_yeu_thich($sp['id']);
$_ve_trang_yeu_thich = urlencode(BASE_URL . '?' . http_build_query($_GET));
$_url_chi_tiet = BASE_URL . '?action=san-pham-chi-tiet&id=' . $sp['id'];
$_dg = diem_danh_gia_mau((int)$sp['id']);
$_cot = $_cot_the ?? 'col-12 col-sm-6 col-lg-4';
?>
<div class="<?= $_cot ?> tour-item" data-dm="<?= (int)$sp['danh_muc_id'] ?>">
    <div class="sp-card position-relative">
        <a href="<?= BASE_URL ?>?action=yeu-thich-toggle&san_pham_id=<?= $sp['id'] ?>&ve_trang=<?= $_ve_trang_yeu_thich ?>"
           class="btn-yeu-thich <?= $_da_yeu_thich ? 'da-yeu-thich' : '' ?>"
           title="<?= $_da_yeu_thich ? 'Bỏ khỏi yêu thích' : 'Lưu tour yêu thích' ?>">
            <i class="bi <?= $_da_yeu_thich ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
        </a>

        <a href="<?= $_url_chi_tiet ?>" class="text-decoration-none">
            <div class="sp-img-wrap">
                <?php if ($_het_hang): ?>
                    <span class="nhan-goc het"><i class="bi bi-x-circle me-1"></i>Hết chỗ</span>
                <?php else: ?>
                    <span class="nhan-goc">Ưu đãi đến 20%</span>
                <?php endif; ?>
                <img src="<?= anh_san_pham($sp['hinh_anh'], $sp['ten_san_pham'] ?? '') ?>" alt="<?= htmlspecialchars($sp['ten_san_pham']) ?>" loading="lazy">
            </div>
        </a>

        <div class="sp-body">
            <div class="sp-meta">
                <?= htmlspecialchars($sp['ten_danh_muc'] ?? '') ?><?= !empty($sp['thoi_gian']) ? ' · ' . htmlspecialchars($sp['thoi_gian']) : '' ?>
            </div>
            <a href="<?= $_url_chi_tiet ?>" class="text-decoration-none"><div class="sp-ten"><?= htmlspecialchars($sp['ten_san_pham']) ?></div></a>

            <div class="sp-rate"><i class="bi bi-star-fill"></i> <?= $_dg['sao'] ?> · Tuyệt vời · <?= $_dg['luot'] ?> đánh giá</div>
            <div class="sp-ok"><i class="bi bi-check2"></i> Xác nhận ngay · Lịch trình trọn gói</div>

            <div class="d-flex justify-content-between align-items-end mt-auto pt-3">
                <div>
                    <span class="sp-gia-cu"><?= dinh_dang_tien($sp['gia'] * 1.12) ?></span>
                    <span class="sp-gia"><?= dinh_dang_tien($sp['gia']) ?></span>
                    <div class="sp-per">/ người · đã gồm thuế phí</div>
                </div>
                <div class="d-flex gap-2">
                    <form action="<?= BASE_URL ?>?action=gio-hang-them" method="POST" class="m-0">
                        <input type="hidden" name="san_pham_id" value="<?= $sp['id'] ?>">
                        <input type="hidden" name="so_luong" value="1">
                        <input type="hidden" name="ve_trang" value="<?= htmlspecialchars(BASE_URL . '?' . http_build_query($_GET)) ?>">
                        <button type="submit" class="btn btn-sm btn-vien-cam" title="Thêm vào giỏ tour" <?= $_het_hang ? 'disabled' : '' ?>><i class="bi bi-bag-plus"></i></button>
                    </form>
                    <a href="<?= $_url_chi_tiet ?>" class="btn btn-sm btn-vien-cam px-3">Xem tour</a>
                </div>
            </div>
        </div>
    </div>
</div>
