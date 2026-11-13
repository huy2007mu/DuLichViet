<!-- ==================== TỔNG QUAN / DASHBOARD ==================== -->

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background: linear-gradient(135deg,#0d6efd,#3d8bfd);">
            <div class="stat-icon"><i class="bi bi-geo-alt"></i></div>
            <div>
                <div class="stat-number"><?= $tong_san_pham ?></div>
                <div class="stat-label">Tour</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background: linear-gradient(135deg,#198754,#28c76f);">
            <div class="stat-icon"><i class="bi bi-tag"></i></div>
            <div>
                <div class="stat-number"><?= $tong_danh_muc ?></div>
                <div class="stat-label">Điểm Đến</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background: linear-gradient(135deg,#dc3545,#ff6b6b);">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-number"><?= $tong_khach_hang ?></div>
                <div class="stat-label">Khách Hàng</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background: linear-gradient(135deg,#fd7e14,#ffb347);">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-number" style="font-size:1.25rem"><?= dinh_dang_tien($tong_doanh_thu) ?></div>
                <div class="stat-label">Doanh Thu (đơn hoàn thành)</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Chờ xác nhận</div>
                    <div class="fs-4 fw-bold text-secondary"><?= $don_cho_xac_nhan ?></div>
                </div>
                <i class="bi bi-hourglass-split text-secondary" style="font-size:2rem"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Đang diễn ra</div>
                    <div class="fs-4 fw-bold text-warning"><?= $don_dang_giao ?></div>
                </div>
                <i class="bi bi-truck text-warning" style="font-size:2rem"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small">Hoàn thành</div>
                    <div class="fs-4 fw-bold text-success"><?= $don_hoan_thanh ?></div>
                </div>
                <i class="bi bi-check-circle text-success" style="font-size:2rem"></i>
            </div>
        </div>
    </div>
</div>

<!-- Đơn đặt tour gần đây -->
<div class="card mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt me-2"></i>Đơn Đặt Tour Gần Đây</span>
        <a href="<?= BASE_URL ?>?action=admin-don-hang" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($don_hang_gan_day)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Chưa có đơn đặt tour nào</td></tr>
            <?php else: foreach ($don_hang_gan_day as $dh): ?>
                <tr>
                    <td class="fw-semibold">#<?= htmlspecialchars($dh['ma_don_hang']) ?></td>
                    <td><?= htmlspecialchars($dh['ten_khach']) ?></td>
                    <td><?= dinh_dang_tien($dh['tong_tien']) ?></td>
                    <td><?= nhan_trang_thai_don_hang($dh['trang_thai']) ?></td>
                    <td><?= dinh_dang_ngay($dh['ngay_tao']) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>?action=admin-don-hang-chi-tiet&id=<?= $dh['id'] ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Lối tắt quản lý -->
<div class="row g-4">
    <div class="col-md-3 col-sm-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <i class="bi bi-tag-fill text-success" style="font-size:2.2rem"></i>
                <h6 class="fw-bold mt-3">Điểm Đến</h6>
                <a href="<?= BASE_URL ?>?action=admin-danh-muc" class="btn btn-success btn-sm px-4 mt-2">Vào Quản Lý</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <i class="bi bi-geo-alt-fill text-primary" style="font-size:2.2rem"></i>
                <h6 class="fw-bold mt-3">Tour</h6>
                <a href="<?= BASE_URL ?>?action=admin-san-pham" class="btn btn-primary btn-sm px-4 mt-2">Vào Quản Lý</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <i class="bi bi-ticket-perforated-fill text-warning" style="font-size:2.2rem"></i>
                <h6 class="fw-bold mt-3">Voucher</h6>
                <a href="<?= BASE_URL ?>?action=admin-voucher" class="btn btn-warning btn-sm px-4 mt-2">Vào Quản Lý</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <i class="bi bi-people-fill text-danger" style="font-size:2.2rem"></i>
                <h6 class="fw-bold mt-3">Tài Khoản</h6>
                <a href="<?= BASE_URL ?>?action=admin-nguoi-dung" class="btn btn-danger btn-sm px-4 mt-2">Vào Quản Lý</a>
            </div>
        </div>
    </div>
</div>
