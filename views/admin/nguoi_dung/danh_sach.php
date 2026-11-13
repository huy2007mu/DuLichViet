<!-- ==================== DANH SÁCH TÀI KHOẢN ==================== -->

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people me-2"></i>Danh Sách Tài Khoản</span>
        <a href="<?= BASE_URL ?>?action=admin-nguoi-dung-them" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Thêm Tài Khoản
        </a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th style="width:50px">#</th>
                <th>Họ Tên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th class="text-center">Vai Trò</th>
                <th class="text-center">Trạng Thái</th>
                <th class="text-center" style="width:140px">Thao Tác</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($danh_sach_nguoi_dung)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Chưa có tài khoản nào</td></tr>
            <?php else: foreach ($danh_sach_nguoi_dung as $nd): ?>
                <tr>
                    <td><?= $nd['id'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($nd['ho_ten']) ?></td>
                    <td><?= htmlspecialchars($nd['email']) ?></td>
                    <td><?= htmlspecialchars($nd['so_dien_thoai'] ?? '—') ?></td>
                    <td class="text-center">
                        <?= $nd['vai_tro'] === 'admin'
                            ? '<span class="badge bg-primary">Admin</span>'
                            : '<span class="badge bg-info-subtle text-info-emphasis">Khách hàng</span>' ?>
                    </td>
                    <td class="text-center">
                        <?= $nd['trang_thai'] ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-secondary">Đã khoá</span>' ?>
                    </td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>?action=admin-nguoi-dung-sua&id=<?= $nd['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if ((int)$nd['id'] !== (int)$_SESSION['nguoi_dung']['id']): ?>
                            <a href="<?= BASE_URL ?>?action=admin-nguoi-dung-xoa&id=<?= $nd['id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Xoá tài khoản này?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
