<?php
// Đếm số lượng trong giỏ tour / yêu thích để hiển thị badge (chỉ khi đã đăng nhập)
$_so_luong_gio_hang = 0;
$_so_luong_yeu_thich = 0;
$_hang_tv = null;
if (!empty($_SESSION['nguoi_dung'])) {
    $_gioHangModel = new GioHangModel();
    $_so_luong_gio_hang = $_gioHangModel->demSoLuong($_SESSION['nguoi_dung']['id']);

    $_yeuThichModelNav = new YeuThichModel();
    $_so_luong_yeu_thich = $_yeuThichModelNav->demSoLuong($_SESSION['nguoi_dung']['id']);

    try { $_hang_tv = (new ThanhVienModel())->thongTin($_SESSION['nguoi_dung']['id']); } catch (Throwable $e) { $_hang_tv = null; }
}
// Điểm đến cho menu điều hướng
$_danhMucModelNav = new DanhMucModel();
$_danh_muc_nav = $_danhMucModelNav->layTatCa();
$_action_hien_tai = $_GET['action'] ?? '/';

// Ưu đãi cá nhân hoá (chỉ hiện 1 lần sau khi khách xem tour của 1 điểm đến)
$_uu_dai = $_SESSION['uu_dai_ca_nhan'] ?? null;
unset($_SESSION['uu_dai_ca_nhan']);

// SEO
$_tieu_de = isset($title) ? $title . ' | Mây Việt' : 'Mây Việt – Việt Nam đẹp lắm, mình đi thôi!';
$_mo_ta = $meta_mo_ta ?? 'Mây Việt – đặt tour du lịch trọn gói khắp Việt Nam và châu Á: giá minh bạch, lịch trình rõ ràng, hỗ trợ 24/7. Hơn 200 hành trình chọn lọc dành riêng cho bạn.';
$_anh_og = $meta_anh ?? anh_trang_tri('banner-3');
$_anh_og = preg_replace('/#fb=.*$/', '', (string)$_anh_og);
$_url_hien_tai = BASE_URL . (empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING']);
?>
<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($_tieu_de) ?></title>
    <meta name="description" content="<?= htmlspecialchars($_mo_ta) ?>">
    <meta name="theme-color" content="#1a66e6">
    <meta name="referrer" content="no-referrer">
    <link rel="canonical" href="<?= htmlspecialchars($_url_hien_tai) ?>">
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>assets/img/favicon.svg">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Mây Việt">
    <meta property="og:title" content="<?= htmlspecialchars($_tieu_de) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($_mo_ta) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($_anh_og) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($_url_hien_tai) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <script type="application/ld+json"><?= preg_replace('/#fb=[^"]*/', '', json_encode($json_ld ?? [
        '@context' => 'https://schema.org', '@type' => 'TravelAgency', 'name' => 'Mây Việt',
        'url' => BASE_URL, 'telephone' => '+84-1900-6868', 'description' => $_mo_ta,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/mayviet.css?v=5" rel="stylesheet">
</head>

<body>

<!-- ==================== THANH TRÊN CÙNG ==================== -->
<div class="mv-topbar d-none d-md-block">
    <div class="container mv-container">
        <span>Đi để cảm nhận, về để nhớ thương.</span>
        <span>
            <a href="tel:19006868"><i class="bi bi-headset me-1"></i>Hỗ trợ 24/7: 1900 6868</a>
            <span class="dot">•</span>Tiếng Việt<span class="dot">•</span>VNĐ
        </span>
    </div>
</div>

<!-- ==================== HEADER ==================== -->
<header class="site-header" id="site-header">
    <div class="container mv-container d-flex align-items-center gap-3 mv-nav-row">
        <a href="<?= BASE_URL ?>" class="brand-logo" aria-label="Mây Việt - Trang chủ"><img src="<?= BASE_URL ?>assets/img/logo-icon.svg" alt="">mâyviệt</a>

        <nav class="d-none d-lg-flex mx-auto align-items-center">
            <a href="<?= BASE_URL ?>" class="nav-pill <?= $_action_hien_tai === '/' ? 'active' : '' ?>">Trang chủ</a>
            <a href="<?= BASE_URL ?>?action=san-pham" class="nav-pill <?= ($_action_hien_tai === 'san-pham' && empty($_GET['danh_muc_id']) && empty($_GET['sap_xep'])) ? 'active' : '' ?>">Tour du lịch</a>
            <div class="dropdown">
                <a href="#" class="nav-pill dropdown-toggle <?= !empty($_GET['danh_muc_id']) ? 'active' : '' ?>" data-bs-toggle="dropdown">Điểm đến</a>
                <ul class="dropdown-menu">
                    <?php foreach ($_danh_muc_nav as $_dm): ?>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=san-pham&danh_muc_id=<?= $_dm['id'] ?>"><?= htmlspecialchars($_dm['ten_danh_muc']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="<?= BASE_URL ?>?action=san-pham&sap_xep=gia_tang" class="nav-pill <?= ($_GET['sap_xep'] ?? '') === 'gia_tang' ? 'active' : '' ?>">Ưu đãi</a>
            <a href="<?= BASE_URL ?>?action=cam-nang" class="nav-pill <?= str_starts_with($_action_hien_tai, 'cam-nang') ? 'active' : '' ?>">Cẩm nang</a>
            <div class="dropdown">
                <a href="#" class="nav-pill dropdown-toggle <?= in_array($_action_hien_tai, ['choi-thu', 'tro-choi', 'cay-thanh-tich', 'bang-xep-hang', 'hang-thanh-vien'], true) ? 'active' : '' ?>" data-bs-toggle="dropdown">Thành tích</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=cay-thanh-tich"><i class="bi bi-tree me-2"></i>Cây thành tích</a></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=bang-xep-hang"><i class="bi bi-trophy me-2"></i>Bảng xếp hạng</a></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=choi-thu"><i class="bi bi-controller me-2"></i>Mini-game nhận quà</a></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=hang-thanh-vien"><i class="bi bi-award me-2"></i>Hạng thành viên</a></li>
                </ul>
            </div>
        </nav>

        <div class="d-flex align-items-center gap-3 gap-md-4 ms-auto">
            <a href="#" class="icon-action" data-bs-toggle="collapse" data-bs-target="#thanh-tim-kiem" title="Tìm tour"><i class="bi bi-search"></i></a>

            <a href="<?= BASE_URL ?>?action=yeu-thich" class="icon-action" title="Tour yêu thích">
                <i class="bi bi-heart"></i>
                <?php if ($_so_luong_yeu_thich > 0): ?><span class="badge-count"><?= $_so_luong_yeu_thich ?></span><?php endif; ?>
            </a>

            <a href="<?= BASE_URL ?>?action=gio-hang" class="icon-action" title="Giỏ tour">
                <i class="bi bi-bag"></i>
                <?php if ($_so_luong_gio_hang > 0): ?><span class="badge-count"><?= $_so_luong_gio_hang ?></span><?php endif; ?>
            </a>

            <?php if (!empty($_SESSION['nguoi_dung'])): ?>
                <div class="dropdown">
                    <a href="#" class="mv-user dropdown-toggle" data-bs-toggle="dropdown">
                        <span class="mv-avatar"><?= htmlspecialchars(chu_viet_tat($_SESSION['nguoi_dung']['ho_ten'])) ?></span>
                        <span class="d-none d-md-inline"><?= htmlspecialchars($_SESSION['nguoi_dung']['ho_ten']) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width:240px">
                        <?php if ($_hang_tv): ?>
                            <li class="px-3 py-2">
                                <span class="mv-rank-chip"><i class="bi <?= ThanhVienModel::danhSachHang()[$_hang_tv['hang']]['icon'] ?>"></i><?= $_hang_tv['ten_hang'] ?></span>
                                <div class="small text-secondary mt-1"><?= number_format($_hang_tv['diem'], 0, ',', '.') ?> điểm thưởng</div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <?php if ($_SESSION['nguoi_dung']['vai_tro'] === 'admin'): ?>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=admin"><i class="bi bi-speedometer2 me-2"></i>Trang quản trị</a></li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=don-hang-cua-toi"><i class="bi bi-ticket-perforated me-2"></i>Tour của tôi</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=cay-thanh-tich"><i class="bi bi-tree me-2"></i>Cây thành tích</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=bang-xep-hang"><i class="bi bi-trophy me-2"></i>Bảng xếp hạng</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=hang-thanh-vien"><i class="bi bi-award me-2"></i>Hạng thành viên</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>?action=choi-thu"><i class="bi bi-controller me-2"></i>Mini-game nhận quà</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>?action=dang-xuat" onclick="return confirm('Bạn có chắc muốn đăng xuất?')"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>?action=dang-nhap" class="btn btn-cam btn-sm px-3">Đăng nhập</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Thanh tìm kiếm (mở khi bấm biểu tượng kính lúp) -->
    <div class="collapse <?= !empty($_GET['tu_khoa']) ? 'show' : '' ?>" id="thanh-tim-kiem">
        <div class="container mv-container pt-2 pb-3">
            <form action="<?= BASE_URL ?>" method="GET">
                <input type="hidden" name="action" value="san-pham">
                <div class="input-group">
                    <input type="text" name="tu_khoa" class="form-control" placeholder="Bạn muốn đi đâu? VD: Đà Nẵng, Phú Quốc, Nhật Bản..."
                           value="<?= htmlspecialchars($_GET['tu_khoa'] ?? '') ?>">
                    <button class="btn btn-cam" type="submit"><i class="bi bi-search me-1"></i>Tìm tour</button>
                </div>
            </form>
        </div>
    </div>
</header>

<!-- ==================== NỘI DUNG TRANG ==================== -->
<?php $_la_trang_chu = ($_action_hien_tai === '/'); ?>
<main class="<?= $_la_trang_chu ? '' : 'container mv-container py-4 page-enter' ?>">
    <?php if (!$_la_trang_chu): ?><?= lay_thong_bao() ?><?php else: ?><div class="container mv-container pt-3"><?= lay_thong_bao() ?></div><?php endif; ?>
    <?php if (isset($view)) { require_once PATH_VIEW . $view . '.php'; } ?>
</main>

<!-- ==================== FOOTER ==================== -->
<div class="container mv-container mt-5">
    <div class="footer-nl d-flex flex-wrap align-items-center gap-3">
        <i class="bi bi-envelope-heart text-cam fs-1"></i>
        <div class="flex-grow-1">
            <div class="fw-bold fs-5" style="color:var(--mv-navy)">Một chút ưu đãi cho chuyến đi tới</div>
            <small class="text-secondary">Đăng ký để nhận ưu đãi bí mật và cẩm nang du lịch mới nhất từ Mây Việt.</small>
        </div>
        <form class="d-flex gap-2" onsubmit="alert('Cảm ơn bạn đã đăng ký nhận ưu đãi!'); this.reset(); return false;">
            <input type="email" required class="form-control" placeholder="Địa chỉ email" style="min-width:240px">
            <button class="btn btn-cam px-4" type="submit">Đăng ký ngay</button>
        </form>
    </div>
</div>

<footer class="site-footer">
    <div class="container mv-container pb-3">
        <div class="row g-4">
            <div class="col-lg-4">
                <a href="<?= BASE_URL ?>" class="brand-logo mb-3"><img src="<?= BASE_URL ?>assets/img/logo-icon.svg" alt="">mâyviệt</a>
                <p class="mb-3">Thiết kế những hành trình trọn vẹn khắp Việt Nam: biển đảo, núi rừng, di sản và ẩm thực địa phương.</p>
                <p class="mb-1"><i class="bi bi-telephone me-2 text-cam"></i>1900 6868 (24/7)</p>
                <p class="mb-1"><i class="bi bi-envelope me-2 text-cam"></i>support@mayviet.vn</p>
                <p class="mb-0"><i class="bi bi-geo-alt me-2 text-cam"></i>[Địa chỉ công ty], Hà Nội</p>
                <div class="mv-social">
                    <a href="https://www.facebook.com/" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://www.instagram.com/" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="https://www.tiktok.com/" target="_blank" rel="noopener" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                    <a href="https://www.youtube.com/" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Về Mây Việt</h6>
                <p class="mb-2"><a href="<?= BASE_URL ?>">Giới thiệu</a></p>
                <p class="mb-2"><a href="<?= BASE_URL ?>?action=cam-nang">Cẩm nang du lịch</a></p>
                <p class="mb-2"><a href="<?= BASE_URL ?>?action=cay-thanh-tich">Cây thành tích</a></p>
                <p class="mb-2"><a href="<?= BASE_URL ?>?action=bang-xep-hang">Bảng xếp hạng</a></p>
                <p class="mb-2"><a href="<?= BASE_URL ?>?action=hang-thanh-vien">Hạng thành viên</a></p>
                <p class="mb-2"><a href="<?= BASE_URL ?>?action=choi-thu">Mini-game nhận quà</a></p>
            </div>
            <div class="col-6 col-lg-3">
                <h6>Khám phá</h6>
                <p class="mb-2"><a href="<?= BASE_URL ?>?action=san-pham">Tất cả tour</a></p>
                <?php foreach ($_danh_muc_nav as $_dm): ?>
                    <p class="mb-2"><a href="<?= BASE_URL ?>?action=san-pham&danh_muc_id=<?= $_dm['id'] ?>"><?= htmlspecialchars($_dm['ten_danh_muc']) ?></a></p>
                <?php endforeach; ?>
            </div>
            <div class="col-6 col-lg-3">
                <h6>Hỗ trợ khách hàng</h6>
                <p class="mb-2"><a href="<?= BASE_URL ?>?action=don-hang-cua-toi">Theo dõi tour đã đặt</a></p>
                <p class="mb-2"><a href="<?= BASE_URL ?>?action=yeu-thich">Tour yêu thích</a></p>
                <p class="mb-2"><a href="<?= BASE_URL ?>#hoi-dap">Câu hỏi thường gặp</a></p>
                <p class="mb-2"><a href="https://zalo.me/19006868" target="_blank" rel="noopener">Chat Zalo 24/7</a></p>
            </div>
        </div>
        <hr class="mt-4" style="border-color:var(--mv-line)">
        <p class="text-center mb-0" style="font-size:.8rem">© <?= date('Y') ?> Mây Việt. Đồ án môn PHP - Mô hình MVC OOP.</p>
    </div>
</footer>

<!-- ==================== NÚT HỖ TRỢ 24/7 (CSKH) ==================== -->
<div class="mv-fab" id="mv-fab">
    <div class="mv-fab-menu">
        <a class="mv-fab-item" href="tel:19006868"><span class="ico" style="background:#1a66e6"><i class="bi bi-telephone-fill"></i></span>Gọi hotline 1900 6868</a>
        <a class="mv-fab-item" href="https://zalo.me/19006868" target="_blank" rel="noopener"><span class="ico" style="background:#0068ff"><i class="bi bi-chat-dots-fill"></i></span>Chat Zalo 24/7</a>
        <a class="mv-fab-item" href="https://m.me/" target="_blank" rel="noopener"><span class="ico" style="background:#a033ff"><i class="bi bi-messenger"></i></span>Messenger</a>
    </div>
    <button class="mv-fab-btn" type="button" aria-label="Hỗ trợ 24/7" id="mv-fab-btn"><i class="bi bi-headset"></i></button>
</div>

<?php if ($_uu_dai): ?>
<!-- ==================== ƯU ĐÃI CÁ NHÂN HOÁ ==================== -->
<div class="mv-offer" id="mv-offer" role="status">
    <button class="x" type="button" aria-label="Đóng" onclick="document.getElementById('mv-offer').remove()">×</button>
    <div class="ico"><i class="bi bi-gift-fill"></i></div>
    <div>
        <div class="fw-bold" style="color:var(--mv-navy)">Quà riêng cho chuyến đi <?= htmlspecialchars($_uu_dai['diem_den']) ?>!</div>
        <div class="small text-secondary">Mây Việt tặng bạn giảm <?= (int)$_uu_dai['gia_tri'] ?>% khi đặt tour <?= htmlspecialchars($_uu_dai['diem_den']) ?>.</div>
        <span class="code" id="mv-offer-code" title="Bấm để sao chép"><?= htmlspecialchars($_uu_dai['ma']) ?></span>
        <a class="small ms-2" href="<?= BASE_URL ?>?action=san-pham&tu_khoa=<?= urlencode($_uu_dai['diem_den']) ?>">Xem tour →</a>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/mayviet.js?v=5"></script>

</body>
</html>
