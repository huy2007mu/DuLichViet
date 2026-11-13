<!-- ==================== DANH SÁCH SẢN PHẨM ==================== -->

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span><i class="bi bi-geo-alt me-2"></i>Danh Sách Tour</span>
        <a href="<?= BASE_URL ?>?action=admin-san-pham-them" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Thêm Tour
        </a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên Tour</th>
                <th>Điểm Đến</th>
                <th>Giá</th>
                <th class="text-center">Chỗ Còn</th>
                <th class="text-center">Trạng Thái</th>
                <th class="text-center" style="width:140px">Thao Tác</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($danh_sach_san_pham)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Chưa có tour nào</td></tr>
            <?php else: foreach ($danh_sach_san_pham as $sp): ?>
                <tr>
                    <td><img src="<?= anh_san_pham($sp['hinh_anh'], $sp['ten_san_pham'] ?? '') ?>" class="img-thumbnail-sm"></td>
                    <td class="fw-semibold"><?= htmlspecialchars($sp['ten_san_pham']) ?></td>
                    <td><?= htmlspecialchars($sp['ten_danh_muc'] ?? '—') ?></td>
                    <td><?= dinh_dang_tien($sp['gia']) ?></td>
                    <td class="text-center">
                        <span class="badge <?= $sp['so_luong'] > 0 ? 'bg-info-subtle text-info-emphasis' : 'bg-danger-subtle text-danger-emphasis' ?>">
                            <?= $sp['so_luong'] ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <?= $sp['trang_thai'] ? '<span class="badge bg-success">Đang mở bán</span>' : '<span class="badge bg-secondary">Ngừng bán</span>' ?>
                    </td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>?action=admin-san-pham-sua&id=<?= $sp['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= BASE_URL ?>?action=admin-san-pham-xoa&id=<?= $sp['id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Xoá tour này?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
