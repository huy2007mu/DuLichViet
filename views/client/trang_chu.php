<?php
// Ảnh minh hoạ cho từng điểm đến (có thể thay bằng ảnh thật: chép file vào assets/img/ - xem HUONG_DAN_CAI_DAT.md)
$_anh_diem_den = ['Miền Bắc' => 'canh-sapa', 'Miền Trung' => 'canh-hoian', 'Miền Nam' => 'canh-mekong', 'Biển đảo' => 'canh-phuquoc', 'Nước ngoài' => 'canh-nhatban'];
$_slides = ['banner-3', 'banner-2', 'banner-4', 'banner-1'];
$_video = video_hero();
$_cam_nang = array_slice(require PATH_ROOT . 'configs/cam_nang.php', 0, 3);
$_ds_hang = ThanhVienModel::danhSachHang();
$_xh = new XepHangModel();
$_top_tour = array_slice($_xh->topDatTour('tat_ca', 3), 0, 3);
$_top_game = array_slice($_xh->topDiemGame('tat_ca', 3), 0, 3);
$_cay_cap = !empty($_SESSION['nguoi_dung']) ? CayThanhTichModel::tinhThongTin((new CayThanhTichModel())->demChuyen($_SESSION['nguoi_dung']['id']))['cap'] : 3;
?>

<!-- ==================== 1. HERO ==================== -->
<section class="mv-hero">
    <div class="mv-hero-bg">
        <?php if ($_video): ?>
            <video class="mv-hero-video" autoplay muted loop playsinline poster="<?= anh_trang_tri($_slides[0]) ?>"><source src="<?= $_video ?>" type="video/mp4"></video>
        <?php else: ?>
            <?php foreach ($_slides as $i => $_s): ?>
                <div class="mv-hero-slide <?= $i === 0 ? 'active' : '' ?>" style="background-image:url('<?= anh_trang_tri($_s) ?>')"></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="mv-hero-shade"></div>
        <div class="mv-cloud c1"></div><div class="mv-cloud c2"></div><div class="mv-cloud c3"></div>
    </div>

    <div class="container mv-container mv-hero-content">
        <div class="mv-hero-eyebrow">MÂY VIỆT · HÀNH TRÌNH CỦA BẠN, NIỀM VUI CỦA CHÚNG TÔI</div>
        <h1 class="mv-hero-title"><span class="line">Việt Nam đẹp lắm.</span><span class="line">Mình đi thôi!</span></h1>
        <p class="mv-hero-sub">Chạm những vùng đất mới, gom những kỷ niệm thật.<br>Hơn 200 hành trình được chọn lọc, dành riêng cho bạn.</p>
        <div class="mv-hero-dots" id="mv-hero-dots">
            <?php if (!$_video): foreach ($_slides as $i => $_s): ?><button type="button" class="<?= $i === 0 ? 'active' : '' ?>" data-i="<?= $i ?>" aria-label="Ảnh <?= $i + 1 ?>"></button><?php endforeach; endif; ?>
        </div>
    </div>

    <!-- Thẻ tìm kiếm nổi -->
    <div class="container mv-container mv-search-wrap">
        <form class="mv-search" action="<?= BASE_URL ?>" method="GET" id="mv-search">
            <input type="hidden" name="action" value="san-pham">
            <div class="mv-search-tabs" id="mv-search-tabs">
                <button type="button" class="active" data-ph="Điểm đến, tên tour..."><i class="bi bi-compass"></i>Tour trọn gói</button>
                <button type="button" data-ph="Bạn muốn thiết kế hành trình thế nào?"><i class="bi bi-pencil-square"></i>Tour riêng theo yêu cầu</button>
                <button type="button" data-ph="Làng nghề, ẩm thực, văn hoá bản địa..."><i class="bi bi-balloon-heart"></i>Trải nghiệm địa phương</button>
            </div>
            <div class="mv-search-fields">
                <label class="mv-field"><i class="bi bi-geo-alt"></i><span><b>Bạn muốn đi đâu?</b><input type="text" name="tu_khoa" id="mv-search-input" placeholder="Điểm đến, tên tour..." autocomplete="off"></span></label>
                <label class="mv-field"><i class="bi bi-calendar3"></i><span><b>Ngày khởi hành</b><input type="date" name="ngay" min="<?= date('Y-m-d') ?>"></span></label>
                <label class="mv-field"><i class="bi bi-people"></i><span><b>Số hành khách</b>
                    <select name="khach"><option>1 người lớn</option><option selected>2 người lớn</option><option>3 người lớn</option><option>4 người lớn</option><option>5+ người</option></select></span></label>
                <button class="btn btn-cam btn-shine mv-search-btn" type="submit">Tìm tour ngay</button>
            </div>
            <div class="mv-popular">Được yêu thích:
                <?php foreach (['Phú Quốc', 'Đà Nẵng', 'Sa Pa', 'Hạ Long', 'Đà Lạt'] as $_p): ?>
                    <a href="<?= BASE_URL ?>?action=san-pham&tu_khoa=<?= urlencode($_p) ?>"><?= $_p ?></a>
                <?php endforeach; ?>
            </div>
        </form>
    </div>
</section>

<!-- ==================== 2. CAM KẾT ==================== -->
<section class="mv-perks no-reveal">
    <div class="container mv-container">
        <div class="row g-3">
            <?php foreach ([
                ['bi-shield-check', 'Đặt an tâm, đi trọn vẹn', 'Đối tác được kiểm chứng, lịch trình rõ ràng.'],
                ['bi-patch-check', 'Giá tốt, không phí ẩn', 'Trọn gói thuế phí ngay từ lần xem đầu.'],
                ['bi-headset', 'Đồng hành suốt chuyến đi', 'Hỗ trợ 24/7 qua hotline & Zalo khi có sự cố.'],
            ] as $i => [$ic, $t, $d]): ?>
                <div class="col-md-4"><div class="mv-perk" style="animation-delay:<?= $i * .12 ?>s"><span class="ico"><i class="bi <?= $ic ?>"></i></span><div><b><?= $t ?></b><small><?= $d ?></small></div></div></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="container mv-container">

    <!-- ==================== 3. ĐIỂM ĐẾN ==================== -->
    <section class="mt-5 pt-3">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h2 class="section-title mb-0">Mạng một chút cảm hứng lên đường</h2>
                <p class="section-sub mb-0">Chọn miền đất bạn muốn khám phá cùng Mây Việt.</p>
            </div>
            <a href="<?= BASE_URL ?>?action=san-pham" class="btn btn-cam">Xem tất cả điểm đến <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-3">
            <?php foreach ($danh_sach_danh_muc as $i => $dm):
                $anh = $_anh_diem_den[$dm['ten_danh_muc']] ?? 'canh-bien'; ?>
                <div class="col-6 <?= $i === 0 ? 'col-lg-4' : 'col-lg' ?>">
                    <a href="<?= BASE_URL ?>?action=san-pham&danh_muc_id=<?= $dm['id'] ?>" class="dest-tile">
                        <span class="dest-bg" style="background-image:url('<?= anh_trang_tri($anh) ?>')"></span>
                        <div class="dest-info">
                            <h5><?= htmlspecialchars($dm['ten_danh_muc']) ?></h5>
                            <small><?= $dm['so_san_pham'] ?> tour <i class="bi bi-arrow-right"></i></small>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ==================== 4. TOUR ĐỀ XUẤT ==================== -->
    <section class="mt-5 pt-3" id="tour">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
            <div>
                <h2 class="section-title mb-0">Đi đâu cũng đáng nhớ</h2>
                <p class="section-sub mb-0">Những hành trình được khách hàng Mây Việt yêu thích nhất.</p>
            </div>
            <a href="<?= BASE_URL ?>?action=san-pham" class="btn btn-cam">Khám phá tất cả tour <i class="bi bi-arrow-right ms-1"></i></a>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-4" id="tab-diem-den">
            <button class="tab-pill active" data-dm="all">Tất cả</button>
            <?php foreach ($danh_sach_danh_muc as $dm): ?>
                <button class="tab-pill" data-dm="<?= $dm['id'] ?>"><?= htmlspecialchars($dm['ten_danh_muc']) ?></button>
            <?php endforeach; ?>
        </div>

        <div class="row g-4" id="luoi-tour">
            <?php $_cot_the = 'col-12 col-sm-6 col-lg-3'; foreach ($san_pham_moi_nhat as $sp): include __DIR__ . '/_the_san_pham.php'; endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <button class="btn btn-vien-cam px-4" id="nut-xem-them">Xem thêm tour <i class="bi bi-chevron-down ms-1"></i></button>
        </div>
    </section>

    <!-- ==================== 5. BANNER ƯU ĐÃI ==================== -->
    <section class="mt-5 pt-3">
        <div class="mv-promo">
            <div class="mv-promo-glow"></div>
            <div class="mv-promo-left">
                <span class="mv-promo-tag">CHÀO MÙA DU LỊCH CUỐI NĂM</span>
                <h3>Đi cùng nhau,<br>tiết kiệm nhiều hơn.</h3>
                <p>Nhập mã khi xác nhận đặt tour để nhận ưu đãi dành riêng cho nhóm bạn.</p>
            </div>
            <div class="mv-promo-right">
                <div class="mv-promo-code" id="promo-code" title="Bấm để sao chép">MAYVIET500</div>
                <a href="<?= BASE_URL ?>?action=san-pham&sap_xep=gia_tang" class="btn btn-cam btn-lg px-4">Khám phá ưu đãi</a>
            </div>
        </div>
    </section>

    <!-- ==================== 6. VÉ TOUR ==================== -->
    <section class="mt-5 pt-3" id="ve-tour">
        <div class="mb-4">
            <h2 class="section-title mb-0">Vé tour hot trong tuần</h2>
            <p class="section-sub mb-0">Săn vé tour giá tốt — giữ chỗ chỉ trong 1 phút.</p>
        </div>
        <div class="row g-3">
            <?php foreach ($san_pham_ban_chay as $i => $sp): ?>
                <div class="col-12 col-lg-6">
                    <div class="ve-tour">
                        <div class="ve-anh" style="background-image:url('<?= anh_san_pham($sp['hinh_anh'], $sp['ten_san_pham']) ?>')">
                            <span class="ve-tem">VÉ TOUR</span>
                        </div>
                        <div class="ve-cat"></div>
                        <div class="ve-noidung">
                            <small class="text-secondary"><i class="bi bi-geo-alt-fill text-cam"></i> <?= htmlspecialchars($sp['ten_danh_muc'] ?? '') ?> · <?= htmlspecialchars($sp['thoi_gian'] ?? '') ?></small>
                            <div class="ve-ten"><?= htmlspecialchars($sp['ten_san_pham']) ?></div>
                            <div class="d-flex justify-content-between align-items-end mt-auto">
                                <div>
                                    <small class="text-secondary d-block">Khởi hành <?= lich_gan_nhat((int)$sp['id'], 1)[0] ?></small>
                                    <span class="sp-gia"><?= dinh_dang_tien($sp['gia']) ?></span>
                                </div>
                                <a href="<?= BASE_URL ?>?action=san-pham-chi-tiet&id=<?= $sp['id'] ?>" class="btn btn-cam btn-sm px-3">Đặt vé</a>
                            </div>
                        </div>
                        <div class="ve-ma"><span>No.</span><b><?= str_pad((string)$sp['id'], 4, '0', STR_PAD_LEFT) ?></b></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ==================== 7. MINI-GAME + HẠNG THÀNH VIÊN ==================== -->
    <section class="mt-5 pt-3">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5">
                <div class="mv-game-teaser h-100">
                    <span class="mv-eyebrow" style="background:rgba(255,255,255,.16);color:#fff">MINI-GAME</span>
                    <h3>Chơi nhỏ, quà to cùng Mây Việt</h3>
                    <p>Lật thẻ ghép cặp địa danh nổi tiếng. Chơi thử miễn phí để làm quen, đặt tour xong nhận ngay <b>2 lượt chơi thật</b> để săn voucher giảm giá, bữa ăn đặc sản hoặc vé vui chơi miễn phí.</p>
                    <ul>
                        <li><i class="bi bi-1-circle"></i> Dưới 35% hoàn thành: chưa có quà</li>
                        <li><i class="bi bi-2-circle"></i> Từ 35% đến 75%: quà vừa</li>
                        <li><i class="bi bi-3-circle"></i> Trên 75%: quà lớn</li>
                    </ul>
                    <a href="<?= BASE_URL ?>?action=choi-thu" class="btn btn-light fw-bold px-4"><i class="bi bi-controller me-2"></i>Chơi thử miễn phí</a>
                    <div class="mv-game-cards" aria-hidden="true"><i class="bi bi-airplane"></i><i class="bi bi-tree"></i><i class="bi bi-umbrella"></i><i class="bi bi-camera"></i></div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="mb-3">
                    <h2 class="section-title mb-0">Đi càng nhiều, ưu đãi càng lớn</h2>
                    <p class="section-sub mb-0">Lên hạng thành viên để nhận x1,5 – x2 điểm thưởng và nhiều quyền lợi riêng.</p>
                </div>
                <div class="row g-3">
                    <?php foreach ($_ds_hang as $k => $h): ?>
                        <div class="col-md-4">
                            <div class="rank-card rank-<?= $k ?>">
                                <div class="rank-ico"><i class="bi <?= $h['icon'] ?>"></i></div>
                                <div class="fw-bold" style="color:var(--mv-navy)"><?= $h['ten'] ?></div>
                                <div class="small text-secondary mb-2"><?= $h['ten_vi'] ?> · x<?= str_replace('.', ',', (string)$h['he_so']) ?> điểm</div>
                                <ul><?php foreach (array_slice($h['quyen_loi'], 0, 2) as $q): ?><li><i class="bi bi-check2-circle"></i><span><?= $q ?></span></li><?php endforeach; ?></ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="<?= BASE_URL ?>?action=hang-thanh-vien" class="btn btn-light-blue mt-3 px-4">Xem chi tiết quyền lợi <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </section>

    <!-- ==================== 7b. CÂY THÀNH TÍCH + BẢNG XẾP HẠNG ==================== -->
    <section class="mt-5 pt-3">
        <div class="home-cay">
            <div class="scene"><?php include __DIR__ . '/_cay_svg.php'; ?></div>
            <div class="info">
                <span class="mv-eyebrow"><i class="bi bi-tree me-1"></i>Cây thành tích</span>
                <h3 class="fw-bold" style="color:var(--mv-navy);letter-spacing:-.02em">Đặt tour nhiều hơn, cây lớn hơn</h3>
                <p class="text-secondary mb-3">Từ mầm non đến cây thành tựu – mỗi chuyến đi giúp cây phát triển và mở khoá thêm ưu đãi, có cấp được giảm đến 50%.</p>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <a href="<?= BASE_URL ?>?action=cay-thanh-tich" class="btn btn-cam px-4">Xem cây của tôi</a>
                    <a href="<?= BASE_URL ?>?action=bang-xep-hang" class="btn btn-vien-cam px-4"><i class="bi bi-trophy me-1"></i>Bảng xếp hạng</a>
                </div>
                <?php if ($_top_tour || $_top_game): ?>
                <div class="row g-3">
                    <?php foreach ([['Đặt tour nhiều nhất', $_top_tour, 'so_chuyen', 'chuyến'], ['Điểm mini-game cao nhất', $_top_game, 'tong_diem', 'điểm']] as [$_tt, $_ds, $_cot, $_dv]): ?>
                        <div class="col-sm-6"><div class="small fw-semibold" style="color:var(--mv-navy)"><?= $_tt ?></div>
                            <ul class="top3-mini"><?php foreach ($_ds as $_i => $_r): ?><li><span><?= ['🥇', '🥈', '🥉'][$_i] ?></span><span class="flex-grow-1 text-truncate"><?= htmlspecialchars(an_ten($_r['ho_ten'])) ?></span><b class="text-cam"><?= (int)$_r[$_cot] ?></b><small class="text-secondary"><?= $_dv ?></small></li><?php endforeach; ?></ul></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ==================== 8. THƯ VIỆN ẢNH + MẠNG XÃ HỘI ==================== -->
    <section class="mt-5 pt-3">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h2 class="section-title mb-0">Khoảnh khắc đẹp</h2>
                <p class="section-sub mb-0">Những góc nhìn khiến bạn muốn xách vali lên ngay.</p>
            </div>
            <div class="mv-social m-0">
                <a href="https://www.tiktok.com/" target="_blank" rel="noopener" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                <a href="https://www.instagram.com/" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="https://www.facebook.com/" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            </div>
        </div>
        <div class="gallery">
            <?php foreach ([['canh-sapa', 'Sa Pa'], ['canh-bien', 'Nha Trang'], ['canh-dalat', 'Đà Lạt'], ['canh-mekong', 'Miền Tây'], ['canh-hoian', 'Hội An'], ['canh-danang', 'Đà Nẵng']] as $k => [$anh, $nhan]): ?>
                <a href="<?= BASE_URL ?>?action=san-pham&tu_khoa=<?= urlencode($nhan) ?>" class="gallery-item g<?= $k + 1 ?>"><span class="gbg" style="background-image:url('<?= anh_trang_tri($anh) ?>')"></span><span class="glabel"><?= $nhan ?></span></a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ==================== 9. CẨM NANG ==================== -->
    <section class="mt-5 pt-3">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h2 class="section-title mb-0">Cẩm nang du lịch</h2>
                <p class="section-sub mb-0">Kinh nghiệm, lịch trình và mẹo hay từ đội ngũ Mây Việt.</p>
            </div>
            <a href="<?= BASE_URL ?>?action=cam-nang" class="btn btn-vien-cam">Xem tất cả bài viết</a>
        </div>
        <div class="row g-4">
            <?php foreach ($_cam_nang as $b): ?>
                <div class="col-md-4">
                    <a class="guide-card" href="<?= BASE_URL ?>?action=cam-nang-chi-tiet&bai=<?= $b['slug'] ?>">
                        <div class="img-wrap"><div class="img" style="background-image:url('<?= anh_trang_tri($b['anh']) ?>')"></div></div>
                        <div class="body">
                            <span class="mv-eyebrow"><?= $b['the'] ?> · <?= $b['doc'] ?> phút đọc</span>
                            <h3><?= htmlspecialchars($b['tieu_de']) ?></h3>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ==================== 10. HỖ TRỢ 24/7 ==================== -->
    <section class="mt-5 pt-3">
        <div class="support-box">
            <div class="support-avatar mv-float"><i class="bi bi-headset"></i></div>
            <div class="flex-grow-1">
                <h3 class="fw-bold mb-1" style="color:var(--mv-navy)">Cần hỗ trợ? Mây Việt luôn ở đây 24/7</h3>
                <p class="mb-0 text-secondary">Có sự cố phát sinh trong chuyến đi? Gọi hotline hoặc nhắn Zalo, đội ngũ chăm sóc khách hàng sẽ phản hồi ngay.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="tel:19006868" class="btn btn-cam btn-lg px-4"><i class="bi bi-telephone me-2"></i>1900 6868</a>
                <a href="https://zalo.me/19006868" target="_blank" rel="noopener" class="btn btn-vien-cam btn-lg px-4"><i class="bi bi-chat-dots me-2"></i>Chat Zalo</a>
            </div>
        </div>
    </section>

    <!-- ==================== 11. VÌ SAO CHỌN ==================== -->
    <section class="mt-5 pt-3">
        <div class="text-center mb-4"><h2 class="section-title">Vì sao chọn Mây Việt</h2></div>
        <div class="row g-3 text-center">
            <?php foreach ([
                ['bi-patch-check', 'Giá minh bạch', 'Không phát sinh chi phí ẩn'],
                ['bi-map', 'Lịch trình tối ưu', 'Đi trọn điểm nổi bật'],
                ['bi-people', 'HDV giàu kinh nghiệm', 'Am hiểu văn hoá địa phương'],
                ['bi-arrow-repeat', 'Linh hoạt đổi lịch', 'Miễn phí đổi ngày khởi hành'],
            ] as [$icon, $tieu_de, $mo_ta]): ?>
                <div class="col-6 col-lg-3">
                    <div class="card h-100 p-4 why-card">
                        <span class="why-ico"><i class="bi <?= $icon ?>"></i></span>
                        <div class="fw-semibold mt-3" style="color:var(--mv-navy)"><?= $tieu_de ?></div>
                        <small class="text-secondary"><?= $mo_ta ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ==================== 12. CÂU HỎI THƯỜNG GẶP ==================== -->
    <section class="mt-5 pt-3 mx-auto" style="max-width:860px" id="hoi-dap">
        <div class="text-center mb-4"><h2 class="section-title">Câu hỏi thường gặp</h2></div>
        <div class="accordion" id="faq">
            <?php foreach ([
                ['Thủ tục đi tour nước ngoài cần những giấy tờ gì?', 'Bạn cần hộ chiếu còn hạn tối thiểu 6 tháng, ảnh thẻ và các giấy tờ theo yêu cầu từng quốc gia. Nhân viên sẽ hướng dẫn chi tiết sau khi bạn đặt tour.'],
                ['Đặt tour trên website như thế nào?', 'Chọn tour → chọn số khách → thêm vào giỏ tour → điền thông tin và ngày khởi hành → xác nhận. Nhân viên sẽ liên hệ lại để chốt tour.'],
                ['Có thể đổi hoặc huỷ tour không?', 'Bạn có thể huỷ khi đơn còn ở trạng thái “Chờ xác nhận”. Việc đổi ngày khởi hành được hỗ trợ miễn phí theo chính sách từng tour.'],
                ['Mã giảm giá và mini-game nhận quà hoạt động thế nào?', 'Nhập mã ở bước xác nhận đặt tour (ví dụ CHAOMUNG10, GIAM200K, DATSOM20, MAYVIET500). Sau khi đặt tour, bạn có 2 lượt chơi mini-game cho mỗi đơn để nhận thêm voucher cá nhân.'],
            ] as $i => [$q, $a]): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button <?= $i ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>"><?= $i + 1 ?>. <?= $q ?></button>
                    </h2>
                    <div id="faq<?= $i ?>" class="accordion-collapse collapse <?= $i ? '' : 'show' ?>" data-bs-parent="#faq">
                        <div class="accordion-body text-secondary"><?= $a ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<script>
(function () {
    // ----- Hero: đổi ảnh nền mượt + chấm điều hướng -----
    var slides = document.querySelectorAll('.mv-hero-slide'), dots = document.querySelectorAll('#mv-hero-dots button'), idx = 0, timer;
    function go(n) {
        if (!slides.length) return;
        slides[idx].classList.remove('active'); dots[idx] && dots[idx].classList.remove('active');
        idx = (n + slides.length) % slides.length;
        slides[idx].classList.add('active'); dots[idx] && dots[idx].classList.add('active');
    }
    function auto() { clearInterval(timer); timer = setInterval(function () { go(idx + 1); }, 5500); }
    dots.forEach(function (d) { d.addEventListener('click', function () { go(+d.dataset.i); auto(); }); });
    if (slides.length > 1) auto();

    // ----- Tab trong ô tìm kiếm -----
    var inp = document.getElementById('mv-search-input');
    document.querySelectorAll('#mv-search-tabs button').forEach(function (b) {
        b.addEventListener('click', function () {
            document.querySelectorAll('#mv-search-tabs button').forEach(function (x) { x.classList.remove('active'); });
            b.classList.add('active'); inp.placeholder = b.dataset.ph; inp.focus();
        });
    });

    // ----- Sao chép mã ưu đãi -----
    var pc = document.getElementById('promo-code');
    if (pc) pc.addEventListener('click', function () {
        if (navigator.clipboard) navigator.clipboard.writeText('MAYVIET500');
        var o = pc.textContent; pc.textContent = 'Đã sao chép ✓'; setTimeout(function () { pc.textContent = o; }, 1400);
    });

    // ----- Lọc tour theo điểm đến + nút "Xem thêm" -----
    var HIEN_BAN_DAU = 8;
    var items = Array.from(document.querySelectorAll('#luoi-tour .tour-item'));
    var nutThem = document.getElementById('nut-xem-them');
    var dm = 'all', dangHien = HIEN_BAN_DAU;
    function capNhat() {
        var khop = items.filter(function (i) { return dm === 'all' || i.dataset.dm === dm; });
        items.forEach(function (i) { i.style.display = 'none'; });
        khop.slice(0, dangHien).forEach(function (i) {
            i.style.display = '';
            var c = i.querySelector('.sp-card'); if (c) { c.classList.add('is-visible'); }
        });
        nutThem.style.display = khop.length > dangHien ? '' : 'none';
    }
    document.querySelectorAll('#tab-diem-den .tab-pill').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#tab-diem-den .tab-pill').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active'); dm = this.dataset.dm; dangHien = HIEN_BAN_DAU; capNhat();
        });
    });
    nutThem.addEventListener('click', function () { dangHien += 4; capNhat(); });
    capNhat();
})();
</script>
