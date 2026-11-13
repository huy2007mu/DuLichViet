<!-- ==================== DANH SÁCH ĐƠN HÀNG ==================== -->

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-receipt me-2"></i>Danh Sách Đơn Đặt Tour</span>

        <form method="GET" class="d-flex gap-2">
            <input type="hidden" name="action" value="admin-don-hang">
            <select name="trang_thai" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- Tất cả trạng thái --</option>
                <?php
                $cac_trang_thai = [
                    'cho_xac_nhan' => 'Chờ xác nhận', 'da_xac_nhan' => 'Đã xác nhận',
                    'dang_giao' => 'Đang diễn ra', 'hoan_thanh' => 'Hoàn thành', 'da_huy' => 'Đã huỷ',
                ];
                foreach ($cac_trang_thai as $key => $nhan): ?>
                    <option value="<?= $key ?>" <?= ($_GET['trang_thai'] ?? '') === $key ? 'selected' : '' ?>><?= $nhan ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Mã Đơn</th>
                <th>Khách Hàng</th>
                <th>SĐT</th>
                <th>Tổng Tiền</th>
                <th class="text-center">Trạng Thái</th>
                <th>Ngày Đặt</th>
                <th class="text-center" style="width:90px">Thao Tác</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($danh_sach_don_hang)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Không có đơn đặt tour nào</td></tr>
            <?php else: foreach ($danh_sach_don_hang as $dh): ?>
                <tr>
                    <td class="fw-semibold">#<?= htmlspecialchars($dh['ma_don_hang']) ?></td>
                    <td><?= htmlspecialchars($dh['ten_khach']) ?></td>
                    <td><?= htmlspecialchars($dh['so_dien_thoai']) ?></td>
                    <td><?= dinh_dang_tien($dh['tong_tien']) ?></td>
                    <td class="text-center"><?= nhan_trang_thai_don_hang($dh['trang_thai']) ?></td>
                    <td><?= dinh_dang_ngay($dh['ngay_tao']) ?></td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>?action=admin-don-hang-chi-tiet&id=<?= $dh['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Xem
                        </a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
