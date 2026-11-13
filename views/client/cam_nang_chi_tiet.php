<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb small">
    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?action=cam-nang">Cẩm nang</a></li>
    <li class="breadcrumb-item active"><?= htmlspecialchars($bai['the']) ?></li>
</ol></nav>
<article class="article">
    <span class="mv-eyebrow"><?= $bai['the'] ?> · <?= $bai['doc'] ?> phút đọc</span>
    <h1><?= htmlspecialchars($bai['tieu_de']) ?></h1>
    <p class="text-secondary"><?= htmlspecialchars($bai['mo_ta']) ?></p>
    <img src="<?= anh_trang_tri($bai['anh']) ?>" alt="<?= htmlspecialchars($bai['tieu_de']) ?>" class="img-fluid rounded-4 my-3 w-100" style="max-height:440px;object-fit:cover">
    <?php foreach ($bai['noi_dung'] as [$h, $p]): ?>
        <h2><?= htmlspecialchars($h) ?></h2>
        <p><?= htmlspecialchars($p) ?></p>
    <?php endforeach; ?>
    <div class="support-box mt-5">
        <div class="flex-grow-1">
            <div class="fw-bold fs-5" style="color:var(--mv-navy)">Sẵn sàng lên đường?</div>
            <div class="text-secondary">Xem các tour trọn gói giá minh bạch, đã gồm di chuyển, khách sạn và lịch trình.</div>
        </div>
        <a href="<?= BASE_URL ?>?action=san-pham" class="btn btn-cam px-4">Khám phá tour</a>
    </div>
</article>
<div class="mt-5">
    <h2 class="section-title mb-4" style="font-size:1.5rem">Bài viết liên quan</h2>
    <div class="row g-4">
        <?php foreach ($bai_lien_quan as $b): ?>
            <div class="col-md-4">
                <a class="guide-card" href="<?= BASE_URL ?>?action=cam-nang-chi-tiet&bai=<?= $b['slug'] ?>">
                    <div class="img-wrap"><div class="img" style="background-image:url('<?= anh_trang_tri($b['anh']) ?>')"></div></div>
                    <div class="body"><h3><?= htmlspecialchars($b['tieu_de']) ?></h3></div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
