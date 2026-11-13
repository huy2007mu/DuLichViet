<!-- ==================== DANH SÁCH / TÌM KIẾM / LỌC SẢN PHẨM ==================== -->

<div class="row g-4">
    <!-- BỘ LỌC -->
    <div class="col-lg-3">
        <div class="card p-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-funnel me-1"></i>Bộ Lọc Tour</h6>
            <form action="<?= BASE_URL ?>" method="GET">
                <input type="hidden" name="action" value="san-pham">

                <?php if (!empty($_GET['tu_khoa'])): ?>
                    <input type="hidden" name="tu_khoa" value="<?= htmlspecialchars($_GET['tu_khoa']) ?>">
                <?php endif; ?>

                <label class="form-label small fw-semibold">Điểm đến</label>
                <select name="danh_muc_id" class="form-select form-select-sm mb-3" onchange="this.form.submit()">
                    <option value="">-- Tất cả điểm đến --</option>
                    <?php foreach ($danh_sach_danh_muc as $dm): ?>
                        <option value="<?= $dm['id'] ?>" <?= ($_GET['danh_muc_id'] ?? '') == $dm['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dm['ten_danh_muc']) ?> (<?= $dm['so_san_pham'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="form-label small fw-semibold">Ngân sách / khách (₫)</label>
                <div class="d-flex gap-2 mb-3">
                    <input type="number" name="gia_tu" placeholder="Từ" class="form-control form-control-sm"
                           value="<?= htmlspecialchars($_GET['gia_tu'] ?? '') ?>">
                    <input type="number" name="gia_den" placeholder="Đến" class="form-control form-control-sm"
                           value="<?= htmlspecialchars($_GET['gia_den'] ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-cam btn-sm w-100">
                    <i class="bi bi-search me-1"></i>Áp Dụng
                </button>
                <a href="<?= BASE_URL ?>?action=san-pham" class="btn btn-outline-secondary btn-sm w-100 mt-2">Xoá bộ lọc</a>
            </form>
        </div>
    </div>

    <!-- DANH SÁCH SẢN PHẨM -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0"><?= htmlspecialchars($title) ?> <span class="text-muted fs-6">(<?= count($danh_sach_san_pham) ?> tour)</span></h5>

            <form action="<?= BASE_URL ?>" method="GET" class="d-flex align-items-center gap-2">
                <input type="hidden" name="action" value="san-pham">
                <?php foreach (['tu_khoa', 'danh_muc_id', 'gia_tu', 'gia_den'] as $f): ?>
                    <?php if (!empty($_GET[$f])): ?>
                        <input type="hidden" name="<?= $f ?>" value="<?= htmlspecialchars($_GET[$f]) ?>">
                    <?php endif; ?>
                <?php endforeach; ?>
                <label class="small text-muted mb-0">Sắp xếp:</label>
                <select name="sap_xep" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                    <option value="">Mới nhất</option>
                    <option value="ban_chay" <?= ($_GET['sap_xep'] ?? '') === 'ban_chay' ? 'selected' : '' ?>>Xem nhiều nhất</option>
                    <option value="gia_tang" <?= ($_GET['sap_xep'] ?? '') === 'gia_tang' ? 'selected' : '' ?>>Giá tăng dần</option>
                    <option value="gia_giam" <?= ($_GET['sap_xep'] ?? '') === 'gia_giam' ? 'selected' : '' ?>>Giá giảm dần</option>
                </select>
            </form>
        </div>

        <?php if (empty($danh_sach_san_pham)): ?>
            <div class="text-center py-5">
                <i class="bi bi-emoji-frown" style="font-size:3rem" class="text-muted"></i>
                <p class="text-muted mt-3">Chưa có tour phù hợp. Hãy thử điểm đến khác nhé!</p>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($danh_sach_san_pham as $sp): include __DIR__ . '/_the_san_pham.php'; endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
