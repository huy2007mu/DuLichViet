<div class="text-center mb-5">
    <span class="mv-eyebrow">THÀNH TÍCH & PHÂN HẠNG</span>
    <h1 class="section-title">Đi càng nhiều, quyền lợi càng lớn</h1>
    <p class="section-sub mx-auto" style="max-width:620px">Mỗi tour hoàn thành đều được ghi nhận. Lên hạng để nhận hệ số điểm thưởng cao hơn, voucher riêng và hỗ trợ ưu tiên.</p>
</div>

<?php if ($thong_tin): $h = $ds_hang[$thong_tin['hang']]; ?>
    <div class="card p-4 mb-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-3">
                    <div class="rank-card rank-<?= $thong_tin['hang'] ?> p-0 border-0 shadow-none" style="height:auto;background:none"><div class="rank-ico m-0" style="width:64px;height:64px;font-size:2rem"><i class="bi <?= $h['icon'] ?>"></i></div></div>
                    <div>
                        <div class="text-secondary small">Hạng hiện tại của bạn</div>
                        <div class="fs-3 fw-bold" style="color:var(--mv-navy)"><?= $h['ten'] ?> <small class="fs-6 text-secondary">· <?= $h['ten_vi'] ?></small></div>
                    </div>
                </div>
                <div class="mt-4"><div class="d-flex justify-content-between small mb-1"><span>Tiến độ lên hạng</span><b><?= $thong_tin['tien_do'] ?>%</b></div>
                    <div class="mv-progress"><div data-w="<?= $thong_tin['tien_do'] ?>" style="width:0"></div></div>
                    <div class="small text-secondary mt-2"><?= htmlspecialchars($thong_tin['con_thieu']) ?></div></div>
            </div>
            <div class="col-lg-7">
                <div class="row g-3 text-center">
                    <div class="col-6 col-md-3"><div class="p-3 rounded-4" style="background:var(--mv-blue-tint)"><div class="fs-3 fw-bold text-cam" data-count="<?= $thong_tin['so_tour'] ?>">0</div><small class="text-secondary">Tour hoàn thành</small></div></div>
                    <div class="col-6 col-md-3"><div class="p-3 rounded-4" style="background:var(--mv-blue-tint)"><div class="fs-3 fw-bold text-cam" data-count="<?= $thong_tin['so_diem_den'] ?>">0</div><small class="text-secondary">Điểm đến đã đi</small></div></div>
                    <div class="col-6 col-md-3"><div class="p-3 rounded-4" style="background:var(--mv-blue-tint)"><div class="fs-3 fw-bold text-cam" data-count="<?= $thong_tin['diem'] ?>">0</div><small class="text-secondary">Điểm thưởng (x<?= str_replace('.', ',', (string)$thong_tin['he_so']) ?>)</small></div></div>
                    <div class="col-6 col-md-3"><div class="p-3 rounded-4" style="background:var(--mv-blue-tint)"><div class="fs-6 fw-bold text-cam mt-2"><?= dinh_dang_tien($thong_tin['chi_tieu']) ?></div><small class="text-secondary">Đã chi tiêu</small></div></div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-primary d-flex flex-wrap align-items-center justify-content-between gap-2" style="background:var(--mv-blue-soft);color:var(--mv-navy)">
        <span><i class="bi bi-info-circle me-2"></i>Đăng nhập để xem hạng và điểm thưởng của bạn.</span>
        <a href="<?= BASE_URL ?>?action=dang-nhap" class="btn btn-cam btn-sm">Đăng nhập</a>
    </div>
<?php endif; ?>

<div class="row g-4">
    <?php foreach ($ds_hang as $k => $h): ?>
        <div class="col-md-4">
            <div class="rank-card rank-<?= $k ?> <?= ($thong_tin && $thong_tin['hang'] == $k) ? 'current' : '' ?>">
                <?php if ($thong_tin && $thong_tin['hang'] == $k): ?><span class="mv-rank-chip position-absolute" style="top:16px;right:16px">Hạng của bạn</span><?php endif; ?>
                <div class="rank-ico"><i class="bi <?= $h['icon'] ?>"></i></div>
                <div class="fs-5 fw-bold" style="color:var(--mv-navy)"><?= $h['ten'] ?></div>
                <div class="small text-secondary mb-3"><?= $h['ten_vi'] ?> · nhân x<?= str_replace('.', ',', (string)$h['he_so']) ?> điểm</div>
                <div class="small fw-semibold mb-1" style="color:var(--mv-navy)">Điều kiện đạt được</div>
                <div class="small text-secondary mb-3"><?= $h['dieu_kien'] ?></div>
                <div class="small fw-semibold mb-2" style="color:var(--mv-navy)">Quyền lợi & phần thưởng</div>
                <ul><?php foreach ($h['quyen_loi'] as $q): ?><li><i class="bi bi-check2-circle"></i><span><?= $q ?></span></li><?php endforeach; ?></ul>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if (!empty($lich_su_game)): ?>
    <h2 class="section-title mt-5 mb-3" style="font-size:1.4rem">Quà bạn đã nhận từ mini-game</h2>
    <div class="card"><div class="table-responsive"><table class="table mb-0 align-middle">
        <thead><tr><th>Thời gian</th><th>Cấp độ</th><th>Hoàn thành</th><th>Quà tặng</th><th>Mã voucher</th></tr></thead>
        <tbody><?php foreach ($lich_su_game as $g): ?>
            <tr><td><?= dinh_dang_ngay($g['ngay_tao']) ?></td><td><?= GameModel::capDo()[$g['cap_do']]['ten'] ?? $g['cap_do'] ?></td><td><?= (int)$g['phan_tram'] ?>%</td>
                <td><?= $g['qua_tang'] ? htmlspecialchars($g['qua_tang']) : '<span class="text-secondary">Chưa đạt chỉ tiêu</span>' ?></td>
                <td><b class="text-cam"><?= htmlspecialchars($g['ma_voucher'] ?? '—') ?></b></td></tr>
        <?php endforeach; ?></tbody></table></div></div>
<?php endif; ?>
