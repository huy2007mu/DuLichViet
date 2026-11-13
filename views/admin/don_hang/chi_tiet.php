<!-- ==================== CHI TIẾT ĐƠN HÀNG (ADMIN) ==================== -->

<div class="mb-3">
    <a href="<?= BASE_URL ?>?action=admin-don-hang" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <i class="bi bi-box-seam me-2"></i>Tour Trong Đơn #<?= htmlspecialchars($don_hang['ma_don_hang']) ?>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tour</th>
                        <th class="text-center">Đơn Giá</th>
                        <th class="text-center">SL</th>
                        <th class="text-end">Thành Tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($don_hang['san_pham'] as $sp): ?>
                        <tr>
                            <td><img src="<?= anh_san_pham($sp['hinh_anh'], $sp['ten_san_pham'] ?? '') ?>" class="img-thumbnail-sm"></td>
                            <td><?= htmlspecialchars($sp['ten_san_pham']) ?></td>
                            <td class="text-center"><?= dinh_dang_tien($sp['gia']) ?></td>
                            <td class="text-center"><?= $sp['so_luong'] ?></td>
                            <td class="text-end fw-semibold"><?= dinh_dang_tien($sp['gia'] * $sp['so_luong']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-body border-top">
                <div class="d-flex justify-content-between"><span>Tạm tính:</span><span><?= dinh_dang_tien($don_hang['tam_tinh']) ?></span></div>
                <div class="d-flex justify-content-between text-success">
                    <span>Giảm giá <?= $don_hang['ma_voucher'] ? '(' . htmlspecialchars($don_hang['ma_voucher']) . ')' : '' ?>:</span>
                    <span>- <?= dinh_dang_tien($don_hang['tien_giam']) ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fs-5 fw-bold">
                    <span>Tổng cộng:</span><span class="text-danger"><?= dinh_dang_tien($don_hang['tong_tien']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-white"><i class="bi bi-person me-2"></i>Thông Tin Liên Hệ</div>
            <div class="card-body">
                <p class="mb-1"><b>Người đặt:</b> <?= htmlspecialchars($don_hang['ho_ten_nhan']) ?></p>
                <p class="mb-1"><b>SĐT:</b> <?= htmlspecialchars($don_hang['so_dien_thoai']) ?></p>
                <p class="mb-1"><b>Ngày khởi hành:</b> <?= dinh_dang_ngay($don_hang['ngay_khoi_hanh'], false) ?></p>
                <p class="mb-1"><b>Địa chỉ:</b> <?= htmlspecialchars($don_hang['dia_chi_giao']) ?></p>
                <?php if (!empty($don_hang['ghi_chu'])): ?>
                    <p class="mb-1"><b>Ghi chú:</b> <?= htmlspecialchars($don_hang['ghi_chu']) ?></p>
                <?php endif; ?>
                <hr>
                <p class="mb-1"><b>Tài khoản đặt:</b> <?= htmlspecialchars($don_hang['ten_khach']) ?></p>
                <p class="mb-1"><b>Email:</b> <?= htmlspecialchars($don_hang['email']) ?></p>
                <p class="mb-0"><b>Ngày đặt:</b> <?= dinh_dang_ngay($don_hang['ngay_tao']) ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white"><i class="bi bi-arrow-repeat me-2"></i>Cập Nhật Trạng Thái</div>
            <div class="card-body">
                <p class="mb-2">Trạng thái hiện tại: <?= nhan_trang_thai_don_hang($don_hang['trang_thai']) ?></p>

                <?php if ($don_hang['trang_thai'] !== 'da_huy' && $don_hang['trang_thai'] !== 'hoan_thanh'): ?>
                    <form action="<?= BASE_URL ?>?action=admin-don-hang-cap-nhat-trang-thai" method="POST">
                        <input type="hidden" name="id" value="<?= $don_hang['id'] ?>">
                        <select name="trang_thai" class="form-select mb-2">
                            <option value="cho_xac_nhan" <?= $don_hang['trang_thai'] === 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                            <option value="da_xac_nhan" <?= $don_hang['trang_thai'] === 'da_xac_nhan' ? 'selected' : '' ?>>Đã xác nhận</option>
                            <option value="dang_giao" <?= $don_hang['trang_thai'] === 'dang_giao' ? 'selected' : '' ?>>Đang diễn ra</option>
                            <option value="hoan_thanh">Hoàn thành</option>
                            <option value="da_huy">Đã huỷ</option>
                        </select>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle me-1"></i>Cập Nhật
                        </button>
                    </form>
                <?php else: ?>
                    <p class="text-muted small mb-0">Đơn đặt tour đã kết thúc, không thể thay đổi trạng thái.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
