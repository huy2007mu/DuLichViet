<!-- ==================== QUẢN LÝ SẢN PHẨM YÊU THÍCH ==================== -->

<?php if (!empty($top_san_pham)): ?>
<div class="card mb-4">
    <div class="card-header bg-white">
        <i class="bi bi-graph-up me-2"></i>Top Tour Được Yêu Thích Nhiều Nhất
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th style="width:60px">#</th>
                <th>Tour</th>
                <th class="text-center">Giá</th>
                <th class="text-center">Lượt Yêu Thích</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($top_san_pham as $_i => $tp): ?>
                <tr>
                    <td class="text-muted"><?= $_i + 1 ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= anh_san_pham($tp['hinh_anh'], $tp['ten_san_pham'] ?? '') ?>" class="img-thumbnail-sm">
                            <span class="fw-semibold"><?= htmlspecialchars($tp['ten_san_pham']) ?></span>
                        </div>
                    </td>
                    <td class="text-center"><?= dinh_dang_tien($tp['gia']) ?></td>
                    <td class="text-center">
                        <span class="badge bg-danger"><i class="bi bi-heart-fill me-1"></i><?= (int)$tp['so_luot_yeu_thich'] ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-white">
        <i class="bi bi-heart me-2"></i>Toàn Bộ Danh Sách Yêu Thích (<?= count($danh_sach_yeu_thich) ?>)
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Khách Hàng</th>
                <th>Tour</th>
                <th class="text-center">Giá</th>
                <th>Ngày Thêm</th>
                <th class="text-center" style="width:100px">Thao Tác</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($danh_sach_yeu_thich)): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Chưa có tour yêu thích nào</td></tr>
            <?php else: foreach ($danh_sach_yeu_thich as $yt): ?>
                <tr>
                    <td>
                        <div class="fw-semibold"><?= htmlspecialchars($yt['ten_khach']) ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($yt['email_khach']) ?></div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= anh_san_pham($yt['hinh_anh'], $yt['ten_san_pham'] ?? '') ?>" class="img-thumbnail-sm">
                            <?= htmlspecialchars($yt['ten_san_pham']) ?>
                        </div>
                    </td>
                    <td class="text-center"><?= dinh_dang_tien($yt['gia']) ?></td>
                    <td class="small"><?= dinh_dang_ngay($yt['ngay_tao']) ?></td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>?action=admin-yeu-thich-xoa&id=<?= $yt['id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Xoá mục yêu thích này?')" title="Xoá">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
