<div class="text-center mb-5">
    <span class="mv-eyebrow">CẨM NANG MÂY VIỆT</span>
    <h1 class="section-title">Cẩm nang du lịch Việt Nam</h1>
    <p class="section-sub mx-auto" style="max-width:620px">Kinh nghiệm thực tế, lịch trình gợi ý và mẹo săn tour giá tốt để chuyến đi của bạn trọn vẹn hơn.</p>
</div>
<div class="row g-4">
    <?php foreach ($danh_sach_bai as $b): ?>
        <div class="col-md-6 col-lg-4">
            <a class="guide-card" href="<?= BASE_URL ?>?action=cam-nang-chi-tiet&bai=<?= $b['slug'] ?>">
                <div class="img-wrap"><div class="img" style="background-image:url('<?= anh_trang_tri($b['anh']) ?>')"></div></div>
                <div class="body">
                    <span class="mv-eyebrow"><?= $b['the'] ?> · <?= $b['doc'] ?> phút đọc</span>
                    <h3><?= htmlspecialchars($b['tieu_de']) ?></h3>
                    <p><?= htmlspecialchars($b['mo_ta']) ?></p>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
