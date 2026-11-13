<?php
// Hiển thị 1 bảng xếp hạng: $ds = top 10, $toi = [hạng, dòng] của người đang đăng nhập, $loai = 'dat_tour' | 'game'
function mv_bang_xh($ds, $toi, $loai)
{
    $huy = ['🥇', '🥈', '🥉'];
    $gia_tri = fn($r) => $loai === 'dat_tour' ? (int)$r['so_chuyen'] : (int)$r['tong_diem'];
    $don_vi = $loai === 'dat_tour' ? 'chuyến' : 'điểm';
    $uid = $_SESSION['nguoi_dung']['id'] ?? 0;
    if (!$ds) { echo '<div class="text-center text-secondary py-5"><i class="bi bi-emoji-neutral fs-1"></i><div class="mt-2">Chưa có dữ liệu trong khoảng thời gian này.</div></div>'; return; }
    ?>
    <div class="podium">
        <?php foreach ([1, 0, 2] as $i): if (!isset($ds[$i])) continue; $r = $ds[$i]; ?>
            <div class="pod pod-<?= $i + 1 ?> <?= (int)$r['id'] === (int)$uid ? 'me' : '' ?>">
                <div class="pod-medal"><?= $huy[$i] ?></div>
                <div class="mv-avatar mx-auto"><?= htmlspecialchars(chu_viet_tat($r['ho_ten'])) ?></div>
                <div class="pod-name"><?= htmlspecialchars(an_ten($r['ho_ten'])) ?><?= (int)$r['id'] === (int)$uid ? ' <span class="mv-rank-chip">Bạn</span>' : '' ?></div>
                <div class="pod-val"><b><?= number_format($gia_tri($r), 0, ',', '.') ?></b> <?= $don_vi ?></div>
                <div class="pod-step"><span><?= $i + 1 ?></span></div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (count($ds) > 3): ?>
    <ul class="rank-list">
        <?php foreach (array_slice($ds, 3, null, true) as $i => $r): $la_toi = (int)$r['id'] === (int)$uid; ?>
            <li class="<?= $la_toi ? 'me' : '' ?>">
                <span class="no"><?= $i + 1 ?></span>
                <span class="mv-avatar"><?= htmlspecialchars(chu_viet_tat($r['ho_ten'])) ?></span>
                <span class="flex-grow-1 text-truncate"><?= htmlspecialchars(an_ten($r['ho_ten'])) ?><?= $la_toi ? ' <span class="mv-rank-chip">Bạn</span>' : '' ?>
                    <?php if ($loai === 'dat_tour'): $cc = CayThanhTichModel::capTuSoChuyen((int)$r['so_chuyen']); if ($cc): ?><small class="text-secondary d-block"><i class="bi bi-tree text-success"></i> <?= CayThanhTichModel::capCay()[$cc]['ten'] ?></small><?php endif; else: ?><small class="text-secondary d-block"><?= (int)$r['so_luot'] ?> lượt · cao nhất <?= (int)$r['diem_cao_nhat'] ?> điểm</small><?php endif; ?></span>
                <b><?= number_format($gia_tri($r), 0, ',', '.') ?></b> <small class="text-secondary"><?= $don_vi ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <?php if ($toi && $toi[0] > 10): ?>
        <div class="rank-me-box">Hạng của bạn: <b>#<?= $toi[0] ?></b> · <?= number_format($gia_tri($toi[1]), 0, ',', '.') ?> <?= $don_vi ?></div>
    <?php elseif (!$toi && $uid): ?>
        <div class="rank-me-box">Bạn chưa có tên trong bảng này. <?= $loai === 'dat_tour' ? 'Đặt tour để lên bảng!' : 'Chơi mini-game sau khi đặt tour để ghi điểm!' ?></div>
    <?php endif;
}
?>
<div class="text-center mb-4">
    <span class="mv-eyebrow"><i class="bi bi-trophy-fill me-1"></i>BẢNG XẾP HẠNG</span>
    <h1 class="section-title">Vinh danh những người đồng hành xuất sắc</h1>
    <p class="section-sub mx-auto" style="max-width:640px">Ai đặt tour nhiều nhất? Ai chơi mini-game đạt điểm cao nhất? Cùng leo hạng để nhận thêm ưu đãi!</p>
    <div class="d-flex justify-content-center flex-wrap gap-2 mt-3">
        <?php foreach (XepHangModel::KY as $k => $ten): ?>
            <a href="<?= BASE_URL ?>?action=bang-xep-hang&ky=<?= $k ?>" class="tab-pill <?= $ky === $k ? 'active' : '' ?>"><?= $ten ?></a>
        <?php endforeach; ?>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card rank-board h-100">
            <div class="rank-board-head"><span class="ico"><i class="bi bi-airplane-fill"></i></span><div><b>Đặt tour nhiều nhất</b><small>Xếp theo số chuyến đã đặt</small></div>
                <?php if ($toi['dat_tour']): ?><span class="ms-auto mv-rank-chip">Bạn: #<?= $toi['dat_tour'][0] ?></span><?php endif; ?></div>
            <?php mv_bang_xh($top_dat_tour, $toi['dat_tour'], 'dat_tour'); ?>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card rank-board h-100">
            <div class="rank-board-head"><span class="ico" style="background:#fff1d6;color:#d68a00"><i class="bi bi-controller"></i></span><div><b>Điểm mini-game cao nhất</b><small>Tổng điểm các lượt chơi nhận quà</small></div>
                <?php if ($toi['game']): ?><span class="ms-auto mv-rank-chip">Bạn: #<?= $toi['game'][0] ?></span><?php endif; ?></div>
            <?php mv_bang_xh($top_game, $toi['game'], 'game'); ?>
        </div>
    </div>
</div>
<div class="text-center mt-4">
    <a href="<?= BASE_URL ?>?action=cay-thanh-tich" class="btn btn-light-blue px-4 me-2"><i class="bi bi-tree me-1"></i>Cây thành tích của tôi</a>
    <a href="<?= BASE_URL ?>?action=choi-thu" class="btn btn-light-blue px-4"><i class="bi bi-controller me-1"></i>Chơi mini-game</a>
</div>
