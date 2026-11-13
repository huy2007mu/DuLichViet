<!-- ==================== QUẢN LÝ BÌNH LUẬN / ĐÁNH GIÁ ==================== -->

<div class="card">
    <div class="card-header bg-white">
        <i class="bi bi-star me-2"></i>Danh Sách Bình Luận / Đánh Giá Tour
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Khách Hàng</th>
                <th>Tour</th>
                <th class="text-center">Số Sao</th>
                <th>Nội Dung</th>
                <th>Ngày Đánh Giá</th>
                <th class="text-center">Trạng Thái</th>
                <th class="text-center" style="width:150px">Thao Tác</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($danh_sach_danh_gia)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Chưa có đánh giá nào</td></tr>
            <?php else: foreach ($danh_sach_danh_gia as $dg): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($dg['ten_khach']) ?></td>
                    <td><?= htmlspecialchars($dg['ten_san_pham']) ?></td>
                    <td class="text-center"><?= hien_thi_sao($dg['so_sao']) ?></td>
                    <td style="max-width:280px"><?= nl2br(htmlspecialchars($dg['noi_dung'] ?? '')) ?></td>
                    <td class="small"><?= dinh_dang_ngay($dg['ngay_tao']) ?></td>
                    <td class="text-center">
                        <?= $dg['trang_thai'] ? '<span class="badge bg-success">Hiển thị</span>' : '<span class="badge bg-secondary">Đã ẩn</span>' ?>
                    </td>
                    <td class="text-center">
                        <?php if ($dg['trang_thai']): ?>
                            <a href="<?= BASE_URL ?>?action=admin-danh-gia-an&id=<?= $dg['id'] ?>" class="btn btn-sm btn-outline-warning" title="Ẩn đánh giá">
                                <i class="bi bi-eye-slash"></i>
                            </a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>?action=admin-danh-gia-hien&id=<?= $dg['id'] ?>" class="btn btn-sm btn-outline-success" title="Hiện lại">
                                <i class="bi bi-eye"></i>
                            </a>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>?action=admin-danh-gia-xoa&id=<?= $dg['id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Xoá đánh giá này?')" title="Xoá">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
