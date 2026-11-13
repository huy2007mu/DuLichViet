<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Quản Trị' ?> - Quản trị Mây Việt</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>assets/img/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/mayviet.css?v=5" rel="stylesheet">

    <style>
        body { background:#f6f8fc; color:#16233b; font-family:'Inter',sans-serif; }
        /* ---- SIDEBAR (trắng – xanh, theo mẫu thiết kế) ---- */
        #sidebar { width:256px; min-height:100vh; background:#fff; border-right:1px solid #e3e8f1; position:fixed; top:0; left:0; z-index:999; transition:all .3s; overflow-y:auto; }
        #sidebar .sidebar-brand { padding:20px 20px 16px; border-bottom:1px solid #e3e8f1; }
        #sidebar .nav-link { color:#4b5b75; padding:10px 16px; border-radius:10px; margin:2px 12px; transition:all .25s; font-size:.92rem; font-weight:500; display:flex; align-items:center; }
        #sidebar .nav-link:hover { color:#1a66e6; background:#f1f6ff; transform:translateX(3px); }
        #sidebar .nav-link.active { color:#1a66e6; background:#e8f0fe; font-weight:600; }
        #sidebar .nav-link i { margin-right:10px; font-size:1.05rem; }
        .side-label { color:#8a97ad; font-size:.7rem; letter-spacing:.08em; text-transform:uppercase; font-weight:700; }
        /* ---- MAIN ---- */
        #main-content { margin-left:256px; min-height:100vh; display:flex; flex-direction:column; }
        #topbar { background:rgba(255,255,255,.92); backdrop-filter:blur(12px); border-bottom:1px solid #e3e8f1; padding:14px 28px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:100; }
        .page-content { padding:28px; flex:1; animation:mvPage .5s ease backwards; }
        .table th { background:#f8fafd; font-weight:600; font-size:.78rem; color:#66748a; text-transform:uppercase; letter-spacing:.04em; }
        .table td { vertical-align:middle; font-size:.9rem; }
        .card { border:1px solid #e3e8f1; box-shadow:0 8px 26px rgba(15,43,82,.05); border-radius:14px; }
        .card-header { border-radius:14px 14px 0 0 !important; font-weight:600; background:#fff !important; border-color:#e3e8f1; }
        .img-thumbnail-sm { width:56px; height:56px; object-fit:cover; border-radius:10px; }
        .stat-card { border-radius:16px; padding:22px 20px; color:#fff; display:flex; align-items:center; gap:16px; box-shadow:0 12px 28px rgba(15,43,82,.12); transition:transform .3s, box-shadow .3s; }
        .stat-card:hover { transform:translateY(-5px); box-shadow:0 18px 38px rgba(15,43,82,.2); }
        .stat-card .stat-icon { font-size:2.2rem; opacity:.85; }
        .stat-card .stat-number { font-size:1.7rem; font-weight:700; line-height:1; }
        .stat-card .stat-label { font-size:.85rem; opacity:.9; }
        .bg-primary { background-color:#1a66e6 !important; }
        .bg-success { background-color:#0f8a5f !important; }
        .bg-info { background-color:#2f8fe0 !important; }
        .bg-warning { background-color:#f5a623 !important; }
        .bg-dark { background-color:#0f2b52 !important; }
        .btn-primary { background:#1a66e6; border-color:#1a66e6; }
        .btn-primary:hover { background:#1452bf; border-color:#1452bf; }
        .btn-warning { background:#e8f0fe; border-color:#e8f0fe; color:#1a66e6; }
        .btn-warning:hover { background:#1a66e6; border-color:#1a66e6; color:#fff; }
        .btn-outline-primary { --bs-btn-color:#1a66e6; --bs-btn-border-color:#1a66e6; --bs-btn-hover-bg:#1a66e6; --bs-btn-hover-border-color:#1a66e6; }
        .form-control,.form-select { border-color:#d8e0ee; border-radius:10px; }
        .form-control:focus,.form-select:focus { border-color:#1a66e6; box-shadow:0 0 0 .22rem rgba(26,102,230,.14); }
        a { color:#1a66e6; }
        @media (max-width:768px) { #sidebar { width:0; overflow:hidden; } #main-content { margin-left:0; } }
    </style>
</head>

<body>

<!-- ==================== SIDEBAR ==================== -->
<div id="sidebar">
    <div class="sidebar-brand">
        <a href="<?= BASE_URL ?>" class="brand-logo" style="font-size:1.35rem"><img src="<?= BASE_URL ?>assets/img/logo-icon.svg" alt="" style="height:28px">mâyviệt</a>
        <div class="small text-secondary mt-1">Hệ thống quản trị</div>
    </div>

    <nav class="nav flex-column mt-3">
        <a href="<?= BASE_URL ?>?action=admin"
           class="nav-link <?= (($_GET['action'] ?? '') === 'admin') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Tổng Quan
        </a>

        <div class="px-3 py-2">
            <small class="side-label">
                Quản Lý Tour
            </small>
        </div>

        <a href="<?= BASE_URL ?>?action=admin-danh-muc"
           class="nav-link <?= str_starts_with(($_GET['action'] ?? ''), 'admin-danh-muc') ? 'active' : '' ?>">
            <i class="bi bi-map"></i> Điểm Đến
        </a>
        <a href="<?= BASE_URL ?>?action=admin-san-pham"
           class="nav-link <?= str_starts_with(($_GET['action'] ?? ''), 'admin-san-pham') ? 'active' : '' ?>">
            <i class="bi bi-geo-alt"></i> Tour
        </a>
        <a href="<?= BASE_URL ?>?action=admin-don-hang"
           class="nav-link <?= str_starts_with(($_GET['action'] ?? ''), 'admin-don-hang') ? 'active' : '' ?>">
            <i class="bi bi-receipt"></i> Đơn Đặt Tour
        </a>
        <a href="<?= BASE_URL ?>?action=admin-voucher"
           class="nav-link <?= str_starts_with(($_GET['action'] ?? ''), 'admin-voucher') ? 'active' : '' ?>">
            <i class="bi bi-ticket-perforated"></i> Voucher
        </a>
        <a href="<?= BASE_URL ?>?action=admin-danh-gia"
           class="nav-link <?= str_starts_with(($_GET['action'] ?? ''), 'admin-danh-gia') ? 'active' : '' ?>">
            <i class="bi bi-star"></i> Đánh Giá
        </a>
        <a href="<?= BASE_URL ?>?action=admin-yeu-thich"
           class="nav-link <?= str_starts_with(($_GET['action'] ?? ''), 'admin-yeu-thich') ? 'active' : '' ?>">
            <i class="bi bi-heart"></i> Yêu Thích
        </a>

        <div class="px-3 py-2">
            <small class="side-label">
                Hệ Thống
            </small>
        </div>

        <a href="<?= BASE_URL ?>?action=admin-nguoi-dung"
           class="nav-link <?= str_starts_with(($_GET['action'] ?? ''), 'admin-nguoi-dung') ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Tài Khoản
        </a>
        <a href="<?= BASE_URL ?>" class="nav-link">
            <i class="bi bi-box-arrow-up-right"></i> Xem Website
        </a>
    </nav>
</div>

<!-- ==================== MAIN CONTENT ==================== -->
<div id="main-content">

    <div id="topbar">
        <span class="fw-semibold text-secondary" style="font-size:.95rem">
            <?= $title ?? 'Trang Quản Trị' ?>
        </span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted" style="font-size:.88rem">
                <i class="bi bi-person-circle me-1"></i>
                <?= htmlspecialchars($_SESSION['nguoi_dung']['ho_ten'] ?? '') ?>
                <span class="mv-rank-chip ms-1">Admin</span>
            </span>
            <a href="<?= BASE_URL ?>?action=dang-xuat"
               class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
                <i class="bi bi-box-arrow-right me-1"></i>Đăng Xuất
            </a>
        </div>
    </div>

    <div class="page-content">
        <?= lay_thong_bao() ?>
        <?php if (isset($view)) { require_once PATH_VIEW . $view . '.php'; } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?= BASE_URL ?>assets/js/mayviet.js?v=5"></script>
</body>
</html>
