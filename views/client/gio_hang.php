<!-- ==================== GIỎ HÀNG ==================== -->

<h4 class="fw-bold mb-4"><i class="bi bi-suitcase-lg me-2"></i>Giỏ Tour Của Bạn</h4>

<?php if (empty($gio_hang)): ?>
    <div class="text-center py-5">
        <i class="bi bi-suitcase2" style="font-size:3.5rem" class="text-muted"></i>
        <p class="text-muted mt-3 mb-4">Giỏ tour của bạn đang trống.</p>
        <a href="<?= BASE_URL ?>?action=san-pham" class="btn btn-cam px-4">Tiếp Tục Khám Phá</a>
    </div>
<?php else: ?>

    <?php $tong_gio_hang = 0; ?>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0 cart-table">
                <thead>
                <tr>
                    <th>Tour</th>
                    <th class="text-center">Giá / Khách</th>
                    <th class="text-center" style="width:160px">Số Khách</th>
                    <th class="text-end">Thành Tiền</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($gio_hang as $sp):
                    $thanh_tien = $sp['gia'] * $sp['so_luong_dat'];
                    $tong_gio_hang += $thanh_tien;
                ?>
                    <tr data-gia="<?= (int)$sp['gia'] ?>">
                        <td>
                            <div class="cart-product">
                                <img src="<?= anh_san_pham($sp['hinh_anh'], $sp['ten_san_pham'] ?? '') ?>" class="cart-product-img" alt="<?= htmlspecialchars($sp['ten_san_pham']) ?>" loading="lazy">
                                <a href="<?= BASE_URL ?>?action=san-pham-chi-tiet&id=<?= $sp['id'] ?>" class="text-decoration-none cart-product-name">
                                    <?= htmlspecialchars($sp['ten_san_pham']) ?>
                                </a>
                            </div>
                        </td>
                        <td class="text-center"><?= dinh_dang_tien($sp['gia']) ?></td>
                        <td class="text-center">
                            <div class="input-group input-group-sm justify-content-center cart-qty">
                                <button type="button" class="btn btn-outline-secondary btn-qty-giam">−</button>
                                <input type="number"
                                       class="form-control text-center input-qty"
                                       value="<?= $sp['so_luong_dat'] ?>"
                                       min="1"
                                       max="<?= max(1, $sp['so_luong']) ?>"
                                       data-gio-hang-id="<?= $sp['gio_hang_id'] ?>">
                                <button type="button" class="btn btn-outline-secondary btn-qty-tang">+</button>
                            </div>
                            <div class="canh-bao-ton-kho text-danger mt-1" style="font-size:.75rem; display:<?= $sp['so_luong_dat'] > $sp['so_luong'] ? 'block' : 'none' ?>">
                                Chỉ còn <?= $sp['so_luong'] ?> chỗ
                            </div>
                        </td>
                        <td class="text-end fw-semibold text-danger thanh-tien-cell"><?= dinh_dang_tien($thanh_tien) ?></td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>?action=gio-hang-xoa&id=<?= $sp['gio_hang_id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Xoá tour này khỏi giỏ tour?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row justify-content-end mt-4">
        <div class="col-md-5">
            <div class="card p-3 cart-summary">
                <div class="d-flex justify-content-between fs-5 fw-bold mb-3">
                    <span>Tổng cộng:</span>
                    <span class="text-danger" id="tong-gio-hang"><?= dinh_dang_tien($tong_gio_hang) ?></span>
                </div>
                <a href="<?= BASE_URL ?>?action=dat-hang" class="btn btn-cam w-100">
                    <i class="bi bi-bag-check me-2"></i>Tiến Hành Đặt Tour
                </a>
                <a href="<?= BASE_URL ?>?action=san-pham" class="btn btn-outline-secondary w-100 mt-2">Tiếp Tục Khám Phá</a>
            </div>
        </div>
    </div>

    <script>
    (function () {
        const BASE_URL = '<?= BASE_URL ?>';

        function dinhDangTien(so) {
            return Math.round(so).toLocaleString('vi-VN') + ' ₫';
        }

        function tinhLaiTongCong() {
            let tong = 0;
            document.querySelectorAll('tr[data-gia]').forEach(function (row) {
                const gia = parseFloat(row.dataset.gia) || 0;
                const sl  = parseInt(row.querySelector('.input-qty').value || 0, 10);
                tong += gia * sl;
            });
            document.getElementById('tong-gio-hang').textContent = dinhDangTien(tong);
        }

        document.querySelectorAll('tr[data-gia]').forEach(function (row) {
            const gia          = parseFloat(row.dataset.gia) || 0;
            const input        = row.querySelector('.input-qty');
            const gioHangId     = input.dataset.gioHangId;
            const thanhTienCell = row.querySelector('.thanh-tien-cell');
            const canhBao       = row.querySelector('.canh-bao-ton-kho');
            let hienThoi        = null;

            // Cập nhật hiển thị NGAY LẬP TỨC (không chờ server) để trải nghiệm mượt
            function capNhatHienThiTucThi() {
                const sl = parseInt(input.value || 1, 10);
                thanhTienCell.textContent = dinhDangTien(gia * sl);
                tinhLaiTongCong();
            }

            // Gửi cập nhật lên server (debounce để tránh gửi liên tục khi bấm nhanh)
            function guiCapNhatServer() {
                const sl = parseInt(input.value || 1, 10);
                clearTimeout(hienThoi);
                hienThoi = setTimeout(function () {
                    fetch(BASE_URL + '?action=gio-hang-cap-nhat-ajax', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'gio_hang_id=' + encodeURIComponent(gioHangId) + '&so_luong=' + encodeURIComponent(sl)
                    })
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            if (!data.thanh_cong) return;

                            input.value = data.so_luong;
                            input.max   = data.ton_kho;
                            thanhTienCell.textContent = dinhDangTien(data.thanh_tien);
                            document.getElementById('tong-gio-hang').textContent = dinhDangTien(data.tong_gio_hang);

                            if (data.bi_gioi_han) {
                                canhBao.textContent = 'Chỉ còn ' + data.ton_kho + ' chỗ';
                                canhBao.style.display = 'block';
                            } else {
                                canhBao.style.display = 'none';
                            }
                        })
                        .catch(function () { canhBao.textContent = 'Chưa đồng bộ được số lượng. Vui lòng tải lại trang nếu mạng bị gián đoạn.'; canhBao.style.display = 'block'; });
                }, 350);
            }

            row.querySelector('.btn-qty-tang').addEventListener('click', function () {
                let v = parseInt(input.value || 1, 10);
                const max = parseInt(input.getAttribute('max') || 99, 10);
                if (v < max) {
                    input.value = v + 1;
                    capNhatHienThiTucThi();
                    guiCapNhatServer();
                }
            });

            row.querySelector('.btn-qty-giam').addEventListener('click', function () {
                let v = parseInt(input.value || 1, 10);
                if (v > 1) {
                    input.value = v - 1;
                    capNhatHienThiTucThi();
                    guiCapNhatServer();
                }
            });

            input.addEventListener('input', function () {
                let v = parseInt(input.value || 1, 10);
                if (isNaN(v) || v < 1) v = 1;
                input.value = v;
                capNhatHienThiTucThi();
                guiCapNhatServer();
            });
        });
    })();
    </script>

<?php endif; ?>
