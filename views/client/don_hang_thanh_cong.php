<!-- ==================== ĐẶT HÀNG THÀNH CÔNG ==================== -->

<div class="text-center py-5">
    <i class="bi bi-check-circle-fill mv-float d-inline-block" style="font-size:4.5rem;color:var(--mv-green)"></i>
    <h3 class="fw-bold mt-3">Đặt Tour Thành Công!</h3>
    <p class="text-muted">Cảm ơn bạn đã đặt tour. Mã đơn đặt tour của bạn là:</p>
    <h4 class="fw-bold" style="color:var(--mau-chinh)">#<?= htmlspecialchars($don_hang['ma_don_hang']) ?></h4>

    <div class="card d-inline-block text-start mt-4 p-4" style="max-width:480px; width:100%">
        <p class="mb-1"><b>Người đặt:</b> <?= htmlspecialchars($don_hang['ho_ten_nhan']) ?></p>
        <p class="mb-1"><b>SĐT:</b> <?= htmlspecialchars($don_hang['so_dien_thoai']) ?></p>
        <p class="mb-1"><b>Ngày khởi hành:</b> <?= dinh_dang_ngay($don_hang['ngay_khoi_hanh'], false) ?></p>
        <p class="mb-1"><b>Địa chỉ:</b> <?= htmlspecialchars($don_hang['dia_chi_giao']) ?></p>
        <p class="mb-1"><b>Tổng tiền:</b> <span class="fw-bold text-cam"><?= dinh_dang_tien($don_hang['tong_tien']) ?></span></p>
        <p class="mb-0"><b>Thanh toán:</b> Thanh toán khi nhân viên xác nhận tour</p>
    </div>

    <div class="card d-block mx-auto text-start mt-3 p-3" style="max-width:480px;width:100%;background:var(--mv-blue-tint)">
        <div class="d-flex align-items-center gap-3">
            <span class="fs-1">🌳</span>
            <div class="flex-grow-1">
                <div class="fw-bold" style="color:var(--mv-navy)">Cây thành tích của bạn: <?= htmlspecialchars($cay['ten_cap']) ?> · <?= $cay['so_chuyen'] ?> chuyến</div>
                <div class="small text-secondary"><?= htmlspecialchars($cay['mo_ta']) ?></div>
                <div class="mv-progress mt-2" style="height:8px"><div data-w="<?= $cay['pct'] ?>" style="width:0"></div></div>
            </div>
        </div>
        <a href="<?= BASE_URL ?>?action=cay-thanh-tich" class="small fw-semibold mt-2 d-inline-block">Xem cây & nhận quà →</a>
    </div>

    <div class="mt-4 d-flex gap-2 justify-content-center">
        <a href="<?= BASE_URL ?>?action=don-hang-chi-tiet&id=<?= $don_hang['id'] ?>" class="btn btn-vien-cam px-4">
            <i class="bi bi-receipt me-1"></i>Xem Chi Tiết Đơn Đặt Tour
        </a>
        <a href="<?= BASE_URL ?>?action=tro-choi&don_hang_id=<?= (int)$don_hang['id'] ?>" class="btn btn-cam px-4 btn-shine">
            <i class="bi bi-controller me-1"></i>Chơi mini-game nhận quà
        </a>
    </div>
</div>

<!-- Lời mời chơi mini-game nhận ưu đãi (Play-to-earn) -->
<div class="modal fade" id="modal-moi-choi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="border-radius:22px;border:0;overflow:hidden">
        <div class="mv-game-teaser" style="border-radius:0;padding:34px 30px 18px">
            <span class="mv-eyebrow" style="background:rgba(255,255,255,.16);color:#fff">QUÀ TẶNG SAU KHI ĐẶT TOUR</span>
            <h3 class="mb-1">Bạn có muốn tham gia một trò chơi nhỏ để nhận thêm ưu đãi?</h3>
            <p class="mb-0">Chọn 1 trong 3 mức: bình thường, trung bình hoặc khó. Mỗi đơn có <b><?= GameModel::SO_LUOT_MOI_DON ?> lượt chơi</b> – hoàn thành càng nhiều, quà càng lớn.</p>
        </div>
        <div class="modal-body d-flex flex-wrap gap-2 justify-content-center py-4">
            <a href="<?= BASE_URL ?>?action=tro-choi&don_hang_id=<?= (int)$don_hang['id'] ?>" class="btn btn-cam btn-lg px-4 btn-shine"><i class="bi bi-controller me-2"></i>Chơi ngay</a>
            <button type="button" class="btn btn-vien-cam btn-lg px-4" data-bs-dismiss="modal">Để sau</button>
        </div>
    </div></div>
</div>
<script>window.addEventListener('load', function () { setTimeout(function () { new bootstrap.Modal(document.getElementById('modal-moi-choi')).show(); }, 900); });</script>
