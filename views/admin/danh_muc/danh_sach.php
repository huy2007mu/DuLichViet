<!-- ==================== DANH SÁCH DANH MỤC ==================== -->

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span><i class="bi bi-tag me-2"></i>Danh Sách Điểm Đến</span>
        <a href="<?= BASE_URL ?>?action=admin-danh-muc-them" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Thêm Điểm Đến
        </a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th style="width:60px">#</th>
                <th>Tên Điểm Đến</th>
                <th>Mô Tả</th>
                <th class="text-center">Số Tour</th>
                <th class="text-center" style="width:140px">Thao Tác</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($danh_sach_danh_muc)): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Chưa có điểm đến nào</td></tr>
            <?php else: foreach ($danh_sach_danh_muc as $dm): ?>
                <tr>
                    <td><?= $dm['id'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($dm['ten_danh_muc']) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($dm['mo_ta'] ?? '') ?></td>
                    <td class="text-center"><span class="badge bg-secondary"><?= $dm['so_san_pham'] ?></span></td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>?action=admin-danh-muc-sua&id=<?= $dm['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= BASE_URL ?>?action=admin-danh-muc-xoa&id=<?= $dm['id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Xoá điểm đến này?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
