<!-- ==================== CHI TIẾT ĐƠN HÀNG (KHÁCH HÀNG) ==================== -->

<div class="mb-3">
    <a href="<?= BASE_URL ?>?action=don-hang-cua-toi" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Quay lại đơn đặt tour của tôi
    </a>
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h5 class="fw-bold mb-0">Đơn Đặt Tour #<?= htmlspecialchars($don_hang['ma_don_hang']) ?></h5>
    <div>
        <?= nhan_trang_thai_don_hang($don_hang['trang_thai']) ?>
        <?php if ($don_hang['trang_thai'] === 'cho_xac_nhan'): ?>
            <a href="<?= BASE_URL ?>?action=don-hang-huy&id=<?= $don_hang['id'] ?>" class="btn btn-sm btn-outline-danger ms-2"
               onclick="return confirm('Bạn có chắc muốn huỷ đơn đặt tour này?')">
                <i class="bi bi-x-circle me-1"></i>Huỷ Đơn
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- TIẾN TRÌNH ĐƠN HÀNG -->
<?php if ($don_hang['trang_thai'] !== 'da_huy'):
    $cac_buoc = ['cho_xac_nhan' => 'Chờ xác nhận', 'da_xac_nhan' => 'Đã xác nhận', 'dang_giao' => 'Đang diễn ra', 'hoan_thanh' => 'Hoàn thành'];
    $buoc_hien_tai = array_search($don_hang['trang_thai'], array_keys($cac_buoc));
?>
<div class="card p-4 mb-4">
    <div class="d-flex justify-content-between position-relative">
        <?php $i = 0; foreach ($cac_buoc as $key => $nhan): ?>
            <div class="text-center flex-fill">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center
                            <?= $i <= $buoc_hien_tai ? 'bg-success text-white' : 'bg-black bg-opacity-25 text-muted border' ?>"
                     style="width:36px;height:36px">
                    <i class="bi <?= $i <= $buoc_hien_tai ? 'bi-check-lg' : 'bi-circle' ?>"></i>
                </div>
                <small class="d-block mt-2 <?= $i <= $buoc_hien_tai ? 'fw-semibold' : 'text-muted' ?>"><?= $nhan ?></small>
            </div>
        <?php $i++; endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-box-seam me-2"></i>Tour Đã Đặt</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Tour</th>
                        <th class="text-center">Đơn Giá</th>
                        <th class="text-center">SL</th>
                        <th class="text-end">Thành Tiền</th>
                        <?php if ($don_hang['trang_thai'] === 'hoan_thanh'): ?><th class="text-center">Đánh Giá</th><?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($don_hang['san_pham'] as $sp): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?= anh_san_pham($sp['hinh_anh'], $sp['ten_san_pham'] ?? '') ?>" class="img-thumbnail-sm">
                                    <?= htmlspecialchars($sp['ten_san_pham']) ?>
                                </div>
                            </td>
                            <td class="text-center"><?= dinh_dang_tien($sp['gia']) ?></td>
                            <td class="text-center"><?= $sp['so_luong'] ?></td>
                            <td class="text-end fw-semibold"><?= dinh_dang_tien($sp['gia'] * $sp['so_luong']) ?></td>
                            <?php if ($don_hang['trang_thai'] === 'hoan_thanh'): ?>
                                <td class="text-center">
                                    <?php if (!empty($sp['da_danh_gia'])): ?>
                                        <span class="badge bg-success">Đã đánh giá</span>
                                    <?php elseif ($sp['san_pham_id']): ?>
                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalDanhGia<?= $sp['id'] ?>">
                                            <i class="bi bi-star me-1"></i>Đánh giá
                                        </button>

                                        <!-- MODAL ĐÁNH GIÁ -->
                                        <div class="modal fade" id="modalDanhGia<?= $sp['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="<?= BASE_URL ?>?action=danh-gia-them" method="POST">
                                                        <input type="hidden" name="san_pham_id" value="<?= $sp['san_pham_id'] ?>">
                                                        <input type="hidden" name="don_hang_id" value="<?= $don_hang['id'] ?>">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title">Đánh giá: <?= htmlspecialchars($sp['ten_san_pham']) ?></h6>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label class="form-label small fw-semibold">Số sao</label>
                                                            <select name="so_sao" class="form-select mb-3" required>
                                                                <option value="5">5 sao - Xuất sắc</option>
                                                                <option value="4">4 sao - Tốt</option>
                                                                <option value="3">3 sao - Bình thường</option>
                                                                <option value="2">2 sao - Không hài lòng</option>
                                                                <option value="1">1 sao - Rất tệ</option>
                                                            </select>
                                                            <label class="form-label small fw-semibold">Nhận xét</label>
                                                            <textarea name="noi_dung" rows="3" class="form-control" placeholder="Chia sẻ cảm nhận của bạn..."></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Huỷ</button>
                                                            <button type="submit" class="btn btn-cam">Gửi Đánh Giá</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-truck me-2"></i>Thông Tin Liên Hệ</div>
            <div class="card-body">
                <p class="mb-1"><b>Người đặt:</b> <?= htmlspecialchars($don_hang['ho_ten_nhan']) ?></p>
                <p class="mb-1"><b>SĐT:</b> <?= htmlspecialchars($don_hang['so_dien_thoai']) ?></p>
                <p class="mb-1"><b>Ngày khởi hành:</b> <?= dinh_dang_ngay($don_hang['ngay_khoi_hanh'], false) ?></p>
                <p class="mb-1"><b>Địa chỉ:</b> <?= htmlspecialchars($don_hang['dia_chi_giao']) ?></p>
                <?php if (!empty($don_hang['ghi_chu'])): ?>
                    <p class="mb-0"><b>Ghi chú:</b> <?= htmlspecialchars($don_hang['ghi_chu']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Thanh Toán</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1"><span>Tạm tính:</span><span><?= dinh_dang_tien($don_hang['tam_tinh']) ?></span></div>
                <div class="d-flex justify-content-between mb-1 text-success">
                    <span>Giảm giá <?= $don_hang['ma_voucher'] ? '(' . htmlspecialchars($don_hang['ma_voucher']) . ')' : '' ?>:</span>
                    <span>- <?= dinh_dang_tien($don_hang['tien_giam']) ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fs-5 fw-bold">
                    <span>Tổng cộng:</span><span class="text-danger"><?= dinh_dang_tien($don_hang['tong_tien']) ?></span>
                </div>
                <p class="text-muted small mt-2 mb-0">Thanh toán khi nhân viên xác nhận tour</p>
            </div>
        </div>
    </div>
</div>
