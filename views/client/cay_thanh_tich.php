<?php $_cay_cap = $cay['cap']; ?>
<section class="cay-hero">
    <div class="cay-hero-text">
        <span class="mv-eyebrow"><i class="bi bi-star-fill me-1"></i>Thành tích của bạn</span>
        <h1>Đặt tour nhiều hơn,<br><span>Cây thành tích lớn hơn</span></h1>
        <p>Mỗi chuyến đi là một bước trưởng thành của cây, giúp cây của bạn ngày càng phát triển và nhận thêm nhiều ưu đãi hấp dẫn.</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>?action=san-pham" class="btn btn-cam btn-lg px-4 btn-shine">Khám phá ngay <i class="bi bi-arrow-right ms-1"></i></a>
            <a href="<?= BASE_URL ?>?action=bang-xep-hang" class="btn btn-vien-cam btn-lg px-4"><i class="bi bi-trophy me-1"></i>Bảng xếp hạng</a>
        </div>
    </div>
    <div class="cay-hero-scene"><?php include __DIR__ . '/_cay_svg.php'; ?></div>

    <!-- 4 cấp của cây -->
    <div class="cay-levels">
        <?php foreach ($ds_cap as $k => $c): $st = $k < $cay['cap'] ? 'done' : ($k === $cay['cap'] ? 'current' : 'locked'); ?>
            <div class="cay-level <?= $st ?>">
                <b><?= $c['ten'] ?></b><small><?= $c['khoang'] ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Thanh tiến độ -->
    <div class="cay-progress">
        <div class="d-flex align-items-center gap-3">
            <span class="small text-secondary text-nowrap">Số chuyến đã đặt</span>
            <div class="mv-progress flex-grow-1"><div data-w="<?= $cay['pct'] ?>" style="width:0"></div></div>
            <b class="fs-5" style="color:var(--mv-navy)"><span data-count="<?= $cay['so_chuyen'] ?>"><?= $cay['so_chuyen'] ?></span></b>
        </div>
        <div class="small mt-2" style="color:var(--mv-navy)"><i class="bi bi-info-circle text-cam me-1"></i><?= htmlspecialchars($cay['mo_ta']) ?></div>
        <?php if (!$dang_nhap): ?>
            <div class="small mt-2"><a href="<?= BASE_URL ?>?action=dang-nhap" class="fw-semibold">Đăng nhập</a> để xem cây thật của bạn và nhận quà theo từng cấp.</div>
        <?php endif; ?>
    </div>
</section>

<!-- Quà theo cấp -->
<h2 class="section-title mt-5 mb-1">Quà tặng theo từng cấp</h2>
<p class="section-sub mb-4">Cây lên cấp nào, bạn mở khoá quà của cấp đó. Mỗi cấp được nhận 1 lần, mã dùng được 90 ngày.</p>
<div class="row g-3">
    <?php foreach ($ds_cap as $k => $c):
        $mo = $cay['so_chuyen'] >= $c['tu']; $ma = $qua_da_nhan[$k] ?? null; ?>
        <div class="col-6 col-lg-3">
            <div class="cay-perk <?= $mo ? 'open' : 'lock' ?>">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <span class="ico"><i class="bi <?= $c['qua_ten'] === 'Tích điểm đổi quà' ? 'bi-star-fill' : ($k === 2 ? 'bi-percent' : ($k === 3 ? 'bi-gem' : 'bi-gift-fill')) ?>"></i></span>
                    <div><div class="fw-bold" style="color:var(--mv-navy);line-height:1.25"><?= $c['qua_ten'] ?></div><small class="text-secondary">Từ <?= $c['tu'] ?> chuyến · <?= $c['ten'] ?></small></div>
                </div>
                <div class="small text-secondary mb-3"><?= $c['qua_mo_ta'] ?></div>
                <?php if ($ma): ?>
                    <div class="voucher-ticket w-100 text-center" style="font-size:1rem;letter-spacing:.06em"><?= htmlspecialchars($ma) ?></div>
                    <div class="small text-success mt-2"><i class="bi bi-check-circle-fill"></i> Đã nhận</div>
                <?php elseif ($mo && $dang_nhap): ?>
                    <form action="<?= BASE_URL ?>?action=cay-nhan-qua" method="POST"><input type="hidden" name="cap" value="<?= $k ?>">
                        <button class="btn btn-cam w-100 btn-shine"><i class="bi bi-gift me-1"></i>Nhận quà ngay</button></form>
                <?php else: ?>
                    <button class="btn btn-outline-secondary w-100" disabled><i class="bi bi-lock me-1"></i>Cần <?= max(0, $c['tu'] - $cay['so_chuyen']) ?> chuyến nữa</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<p class="small text-secondary mt-3 mb-0">Mẹo: chơi mini-game sau mỗi lần đặt tour để lấy thêm voucher và leo bảng xếp hạng điểm. <a href="<?= BASE_URL ?>?action=bang-xep-hang">Xem bảng xếp hạng →</a></p>
