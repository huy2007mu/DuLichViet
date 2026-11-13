<!-- ==================== ĐƠN HÀNG CỦA TÔI ==================== -->

<h4 class="fw-bold mb-4"><i class="bi bi-receipt me-2"></i>Tour Của Tôi</h4>

<?php if (empty($danh_sach_don_hang)): ?>
    <div class="text-center py-5">
        <i class="bi bi-inbox" style="font-size:3.5rem" class="text-muted"></i>
        <p class="text-muted mt-3 mb-4">Bạn chưa có đơn đặt tour nào.</p>
        <a href="<?= BASE_URL ?>?action=san-pham" class="btn btn-cam px-4">Khám Phá Ngay</a>
    </div>
<?php else: ?>
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Ngày Đặt</th>
                    <th class="text-end">Tổng Tiền</th>
                    <th class="text-center">Trạng Thái</th>
                    <th class="text-center">Thao Tác</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($danh_sach_don_hang as $dh): ?>
                    <tr>
                        <td class="fw-semibold">#<?= htmlspecialchars($dh['ma_don_hang']) ?></td>
                        <td><?= dinh_dang_ngay($dh['ngay_tao']) ?></td>
                        <td class="text-end fw-semibold text-danger"><?= dinh_dang_tien($dh['tong_tien']) ?></td>
                        <td class="text-center"><?= nhan_trang_thai_don_hang($dh['trang_thai']) ?></td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>?action=don-hang-chi-tiet&id=<?= $dh['id'] ?>" class="btn btn-sm btn-vien-cam">
                                <i class="bi bi-eye me-1"></i>Chi tiết
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
