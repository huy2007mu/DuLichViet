<!-- ==================== DANH SÁCH VOUCHER ==================== -->

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span><i class="bi bi-ticket-perforated me-2"></i>Danh Sách Voucher</span>
        <a href="<?= BASE_URL ?>?action=admin-voucher-them" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Thêm Voucher
        </a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Mã Voucher</th>
                <th>Giá Trị Giảm</th>
                <th>Đơn Tối Thiểu</th>
                <th class="text-center">Đã Dùng / Số Lượng</th>
                <th>Hiệu Lực</th>
                <th class="text-center">Trạng Thái</th>
                <th class="text-center" style="width:120px">Thao Tác</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($danh_sach_voucher)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Chưa có voucher nào</td></tr>
            <?php else: foreach ($danh_sach_voucher as $vc): ?>
                <tr>
                    <td><span class="badge bg-dark"><?= htmlspecialchars($vc['ma_voucher']) ?></span></td>
                    <td>
                        <?= $vc['loai_giam'] === 'phan_tram'
                            ? (int)$vc['gia_tri'] . '%' . (!empty($vc['giam_toi_da']) ? ' (tối đa ' . dinh_dang_tien($vc['giam_toi_da']) . ')' : '')
                            : dinh_dang_tien($vc['gia_tri']) ?>
                    </td>
                    <td><?= dinh_dang_tien($vc['don_toi_thieu']) ?></td>
                    <td class="text-center"><?= $vc['da_dung'] ?> / <?= $vc['so_luong'] ?></td>
                    <td class="small">
                        <?= $vc['ngay_bat_dau'] ? dinh_dang_ngay($vc['ngay_bat_dau'], false) : '—' ?>
                        →
                        <?= $vc['ngay_ket_thuc'] ? dinh_dang_ngay($vc['ngay_ket_thuc'], false) : '—' ?>
                    </td>
                    <td class="text-center">
                        <?= $vc['trang_thai'] ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-secondary">Tắt</span>' ?>
                    </td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>?action=admin-voucher-sua&id=<?= $vc['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= BASE_URL ?>?action=admin-voucher-xoa&id=<?= $vc['id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Xoá voucher này?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
