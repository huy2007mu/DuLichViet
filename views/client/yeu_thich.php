<!-- ==================== SẢN PHẨM YÊU THÍCH ==================== -->

<h4 class="fw-bold mb-4"><i class="bi bi-heart-fill me-2 text-danger"></i>Tour Yêu Thích Của Bạn</h4>

<?php if (empty($danh_sach_san_pham)): ?>
    <div class="text-center py-5">
        <i class="bi bi-heart text-muted" style="font-size:3.5rem"></i>
        <p class="text-muted mt-3 mb-4">Bạn chưa có tour yêu thích nào.</p>
        <a href="<?= BASE_URL ?>?action=san-pham" class="btn btn-cam px-4">Khám Phá Tour</a>
    </div>
<?php else: ?>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>Tour</th>
                    <th>Điểm Đến</th>
                    <th class="text-center">Giá</th>
                    <th class="text-center">Tình Trạng</th>
                    <th>Ngày Thêm</th>
                    <th class="text-center" style="width:170px">Thao Tác</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($danh_sach_san_pham as $sp): $_het_hang = $sp['so_luong'] <= 0; ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?= anh_san_pham($sp['hinh_anh'], $sp['ten_san_pham'] ?? '') ?>" class="img-thumbnail-sm">
                                <a href="<?= BASE_URL ?>?action=san-pham-chi-tiet&id=<?= $sp['id'] ?>" class="text-decoration-none text-light fw-semibold">
                                    <?= htmlspecialchars($sp['ten_san_pham']) ?>
                                </a>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($sp['ten_danh_muc'] ?? '') ?></td>
                        <td class="text-center fw-semibold" style="color:var(--mau-chinh)"><?= dinh_dang_tien($sp['gia']) ?></td>
                        <td class="text-center">
                            <?php if ($_het_hang): ?>
                                <span class="badge bg-danger">Hết chỗ</span>
                            <?php else: ?>
                                <span class="badge bg-success">Còn chỗ</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted"><?= dinh_dang_ngay($sp['ngay_them_yeu_thich']) ?></td>
                        <td class="text-center">
                            <form action="<?= BASE_URL ?>?action=gio-hang-them" method="POST" class="d-inline">
                                <input type="hidden" name="san_pham_id" value="<?= $sp['id'] ?>">
                                <input type="hidden" name="so_luong" value="1">
                                <input type="hidden" name="ve_trang" value="<?= BASE_URL ?>?action=yeu-thich">
                                <button type="submit" class="btn btn-sm btn-cam" <?= $_het_hang ? 'disabled' : '' ?> title="Thêm vào giỏ tour">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </form>
                            <a href="<?= BASE_URL ?>?action=yeu-thich-xoa&id=<?= $sp['yeu_thich_id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Bỏ tour này khỏi danh sách yêu thích?')" title="Bỏ yêu thích">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="<?= BASE_URL ?>?action=san-pham" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Tiếp Tục Khám Phá
        </a>
    </div>

<?php endif; ?>
