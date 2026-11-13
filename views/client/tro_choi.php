<?php $_that = ($che_do === 'that'); ?>
<div class="text-center mb-4">
    <span class="mv-eyebrow"><?= $_that ? 'PLAY-TO-EARN · CHƠI NHẬN QUÀ' : 'FREE-TO-PLAY · CHƠI THỬ MIỄN PHÍ' ?></span>
    <h1 class="section-title">Lật thẻ ghép cặp – <?= $_that ? 'săn ưu đãi cho chuyến đi' : 'thử tài cùng Mây Việt' ?></h1>
    <?php if ($_that): ?>
        <p class="section-sub">Đơn <b>#<?= htmlspecialchars($don_hang['ma_don_hang']) ?></b> · Bạn còn <b class="text-cam" id="luot-con-lai"><?= $luot_con_lai ?></b>/<?= GameModel::SO_LUOT_MOI_DON ?> lượt chơi</p>
    <?php else: ?>
        <p class="section-sub mx-auto" style="max-width:640px">Chơi thoải mái để giải trí, không có quà. Đạt 100 điểm, hãy <a href="<?= BASE_URL ?>?action=san-pham">đặt tour ngay hôm nay</a> để nhận lượt chơi thật và voucher bữa ăn miễn phí!</p>
    <?php endif; ?>
</div>

<?php if ($_that && $luot_con_lai <= 0): ?>
    <div class="card p-5 text-center mx-auto" style="max-width:560px">
        <i class="bi bi-emoji-smile fs-1 text-cam"></i>
        <h4 class="mt-2">Bạn đã dùng hết lượt chơi của đơn này</h4>
        <p class="text-secondary">Mỗi đơn đặt tour có 2 lượt chơi. Đặt thêm tour để nhận thêm lượt nhé!</p>
        <div><a href="<?= BASE_URL ?>?action=hang-thanh-vien" class="btn btn-cam me-2">Xem quà đã nhận</a><a href="<?= BASE_URL ?>?action=san-pham" class="btn btn-vien-cam">Tìm tour mới</a></div>
    </div>
<?php else: ?>

<!-- Chọn cấp độ -->
<div id="man-chon" class="row g-3 justify-content-center mb-4">
    <?php $_i = 0; foreach ($cap_do_ds as $ma => $cd): $_i++; ?>
        <div class="col-md-4">
            <button type="button" class="level-card <?= $_i === 1 ? 'active' : '' ?>" data-cap="<?= $ma ?>">
                <span class="lv-badge"><?= $_i ?></span>
                <b><?= $cd['ten'] ?></b>
                <small><?= $cd['cap'] ?> cặp thẻ · <?= floor($cd['giay'] / 60) ?>:<?= str_pad((string)($cd['giay'] % 60), 2, '0', STR_PAD_LEFT) ?> phút</small>
                <em><?= $_that ? $cd['mo_ta'] : 'Chơi thử, không có quà' ?></em>
            </button>
        </div>
    <?php endforeach; ?>
    <div class="col-12 text-center">
        <button class="btn btn-cam btn-lg px-5 btn-shine" id="nut-bat-dau"><i class="bi bi-play-fill me-1"></i>Bắt đầu chơi</button>
        <?php if ($_that): ?>
        <div class="small text-secondary mt-3">Dưới <?= GameModel::MUC_TRUNG_BINH ?>% hoàn thành: chưa có quà · <?= GameModel::MUC_TRUNG_BINH ?>–<?= GameModel::MUC_CAO ?>%: quà vừa · trên <?= GameModel::MUC_CAO ?>%: quà lớn.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Bàn chơi -->
<div id="man-choi" class="d-none">
    <div class="game-hud">
        <div><small>Thời gian</small><b id="hud-time">0:00</b></div>
        <div class="flex-grow-1 px-3">
            <div class="d-flex justify-content-between small"><span>Hoàn thành</span><b id="hud-pct">0%</b></div>
            <div class="game-bar"><div id="hud-bar"></div><i style="left:35%"></i><i style="left:75%"></i></div>
        </div>
        <div><small>Điểm</small><b id="hud-score">0</b></div>
    </div>
    <div id="game-board" class="game-board"></div>
</div>

<!-- Kết quả -->
<div class="modal fade" id="modal-ket-qua" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm mv-game-result-dialog"><div class="modal-content" style="border-radius:22px;border:0">
        <button type="button" class="btn-close mv-game-result-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        <div class="modal-body text-center p-4" id="kq-noi-dung"></div>
    </div></div>
</div>

<script>
(function () {
    var CHE_DO = <?= json_encode($che_do) ?>, DON_ID = <?= (int)($don_hang['id'] ?? 0) ?>, BASE = <?= json_encode(BASE_URL) ?>;
    var CAP = <?= json_encode($cap_do_ds, JSON_UNESCAPED_UNICODE) ?>;
    var ICONS = ['bi-airplane','bi-tree','bi-umbrella','bi-camera','bi-geo-alt','bi-sun','bi-compass','bi-water','bi-cup-hot','bi-backpack','bi-moon-stars','bi-bicycle'];
    var cap = 'thuong', dangChoi = false, giay = 0, conLai = 0, timer = null, khop = 0, tong = 0, khoa = false, lat = [], diem = 0, t0 = 0;

    var $ = function (id) { return document.getElementById(id); };
    function fmt(s) { return Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0'); }

    document.querySelectorAll('.level-card').forEach(function (b) {
        b.addEventListener('click', function () {
            document.querySelectorAll('.level-card').forEach(function (x) { x.classList.remove('active'); });
            b.classList.add('active'); cap = b.dataset.cap;
        });
    });

    function post(url, data) {
        var fd = new FormData(); Object.keys(data).forEach(function (k) { fd.append(k, data[k]); });
        return fetch(BASE + '?action=' + url, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { return r.text().then(function (txt) { try { return JSON.parse(txt); } catch (e) { throw new Error('Máy chủ trả về dữ liệu không hợp lệ. Hãy tải lại trang và thử lại.'); } }); });
    }

    $('nut-bat-dau').addEventListener('click', function () {
        var nut = $('nut-bat-dau'); nut.disabled = true; nut.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang chuẩn bị...';
        post('tro-choi-bat-dau', { che_do: CHE_DO, cap_do: cap, don_hang_id: DON_ID }).then(function (r) {
            nut.disabled = false; nut.innerHTML = '<i class="bi bi-play-fill me-1"></i>Bắt đầu chơi';
            if (!r.ok) { alert(r.loi || 'Không thể bắt đầu'); if (r.loi && /hết lượt/.test(r.loi)) location.reload(); return; }
            batDau(r.giay);
        }).catch(function (e) { nut.disabled = false; nut.innerHTML = '<i class="bi bi-play-fill me-1"></i>Bắt đầu chơi'; alert(e.message || 'Không thể kết nối máy chủ. Vui lòng thử lại.'); });
    });

    function batDau(gs) {
        var cd = CAP[cap]; giay = gs; conLai = gs; khop = 0; diem = 0; lat = []; khoa = false; tong = cd.cap; t0 = Date.now();
        $('man-chon').classList.add('d-none'); $('man-choi').classList.remove('d-none');
        var cards = []; ICONS.slice(0, cd.cap).forEach(function (ic) { cards.push(ic, ic); });
        cards.sort(function () { return Math.random() - .5; });
        var board = $('game-board'); board.innerHTML = ''; board.style.setProperty('--cols', cd.cot);
        cards.forEach(function (ic, i) {
            var el = document.createElement('button'); el.type = 'button'; el.className = 'gcard'; el.dataset.ic = ic;
            el.style.animationDelay = (i * 40) + 'ms';
            el.innerHTML = '<span class="face front"><i class="bi bi-cloud-sun"></i></span><span class="face back"><i class="bi ' + ic + '"></i></span>';
            el.addEventListener('click', function () { chonThe(el); });
            board.appendChild(el);
        });
        capNhatHud(); clearInterval(timer);
        timer = setInterval(function () { conLai--; capNhatHud(); if (conLai <= 0) ketThuc(); }, 1000);
    }

    function capNhatHud() {
        $('hud-time').textContent = fmt(Math.max(0, conLai));
        $('hud-time').classList.toggle('low', conLai <= 15);
        var p = Math.round(khop / tong * 100);
        $('hud-pct').textContent = p + '%'; $('hud-bar').style.width = p + '%'; $('hud-score').textContent = diem;
    }

    function chonThe(el) {
        if (khoa || el.classList.contains('flip') || el.classList.contains('done')) return;
        el.classList.add('flip'); lat.push(el);
        if (lat.length === 2) {
            khoa = true;
            if (lat[0].dataset.ic === lat[1].dataset.ic) {
                var a = lat[0], b = lat[1];
                setTimeout(function () { a.classList.add('done'); b.classList.add('done'); khop++; diem += 10; lat = []; khoa = false; capNhatHud(); if (khop >= tong) ketThuc(); }, 350);
            } else {
                var x = lat[0], y = lat[1];
                setTimeout(function () { x.classList.remove('flip'); y.classList.remove('flip'); lat = []; khoa = false; }, 750);
            }
        }
    }

    function ketThuc() {
        clearInterval(timer); khoa = true;
        var tru = Math.max(1, Math.round((Date.now() - t0) / 1000));
        // Gửi kết quả (đợi tối thiểu để khớp kiểm tra chống gian lận phía server)
        post('tro-choi-ket-qua', { so_cap_khop: khop }).then(hienKetQua).catch(function (e) { alert((e && e.message) || 'Có lỗi kết nối khi lưu kết quả. Vui lòng tải lại trang.'); location.reload(); });
    }

    function nutChoiLai(conLuot) {
        if (CHE_DO === 'that') {
            return (conLuot > 0 ? '<button class="btn btn-cam px-4 me-2" onclick="location.reload()">Chơi tiếp (còn ' + conLuot + ' lượt)</button>' : '') +
                   '<a class="btn btn-vien-cam px-4" href="' + BASE + '?action=hang-thanh-vien">Xem quà của tôi</a>';
        }
        return '<button class="btn btn-cam px-4 me-2" onclick="location.reload()">Chơi lại</button><a class="btn btn-vien-cam px-4 js-go-tours" href="' + BASE + '?action=san-pham">Đặt tour nhận quà</a>';
    }

    function hienKetQua(r) {
        var box = $('kq-noi-dung'), html = '';
        if (!r.ok) { html = '<i class="bi bi-exclamation-circle fs-1 text-danger"></i><h4 class="mt-2">' + (r.loi || 'Có lỗi xảy ra') + '</h4><button class="btn btn-cam mt-3" onclick="location.reload()">Chơi lại</button>'; }
        else if (r.che_do === 'thu') {
            if (r.dat_100) {
                html = '<div class="kq-ico">🎉</div><h3 class="fw-bold">Bạn đã hoàn thành trò chơi!</h3><p class="text-primary fw-bold mb-2">Điểm thực tế: ' + r.diem + ' điểm</p>' +
                       '<p class="text-secondary">Đổi tour ngay hôm nay để nhận <b>lượt chơi thật</b> và voucher bữa ăn miễn phí!</p>' +
                       '<a class="btn btn-cam btn-lg px-4 btn-shine mb-2 js-go-tours" href="' + BASE + '?action=san-pham">Đặt tour ngay</a><div>' + nutChoiLai(0) + '</div>';
            } else {
                html = '<div class="kq-ico">🎮</div><h3 class="fw-bold">Bạn hoàn thành ' + r.phan_tram + '% – ' + r.diem + ' điểm</h3><p class="text-secondary">Đây là chế độ chơi thử nên chưa có quà. Hãy đặt tour để chơi thật nhé!</p><div>' + nutChoiLai(0) + '</div>';
            }
        } else {
            if (!r.qua) {
                html = '<div class="kq-ico">😅</div><h3 class="fw-bold">Hoàn thành ' + r.phan_tram + '%</h3><p class="text-secondary">Chưa đạt chỉ tiêu <?= GameModel::MUC_TRUNG_BINH ?>% nên lượt này chưa có quà. Cố lên nhé!</p><div>' + nutChoiLai(r.luot_con_lai) + '</div>';
            } else {
                var lon = r.phan_tram > <?= GameModel::MUC_CAO ?>;
                html = '<div class="kq-ico">' + (lon ? '🏆' : '🎁') + '</div><h3 class="fw-bold">Hoàn thành ' + r.phan_tram + '% – ' + (lon ? 'Quà lớn!' : 'Quà vừa!') + '</h3>' +
                       (r.vong_quay ? '<div class="wheel-wrap"><div class="wheel-pin"></div><canvas id="wheel" width="220" height="220"></canvas></div><p class="small text-secondary" id="wheel-note">Vòng quay quyết định đang quay...</p>' : '') +
                       '<div id="kq-qua" ' + (r.vong_quay ? 'style="display:none"' : '') + '><p class="fs-5 mb-2">' + r.qua + '</p>' +
                       '<div class="voucher-ticket" id="vc" role="button" title="Bấm để sao chép">' + r.ma + '</div><p class="small text-secondary mt-2">Bấm vào mã để sao chép. Mã chỉ dùng cho tài khoản của bạn, có hạn 60 ngày và dùng 1 lần. Khi đặt tour tiếp theo, nhập mã tại bước xác nhận đặt tour.</p><a class="btn btn-cam btn-sm mt-2" href="' + BASE + '?action=san-pham">Chọn tour để sử dụng mã</a></div>' +
                       '<div class="mt-3">' + nutChoiLai(r.luot_con_lai) + '</div>';
            }
        }
        box.innerHTML = html;
        // Đảm bảo nút đi đặt tour đóng hộp kết quả trước khi chuyển trang.
        box.querySelectorAll('.js-go-tours').forEach(function (link) {
            link.addEventListener('click', function (ev) {
                ev.preventDefault();
                var target = link.href;
                var modalEl = $('modal-ket-qua');
                if (window.bootstrap && bootstrap.Modal) {
                    var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    var daChuyen = false;
                    var chuyenTrang = function () { if (!daChuyen) { daChuyen = true; window.location.assign(target); } };
                    modalEl.addEventListener('hidden.bs.modal', chuyenTrang, { once: true });
                    modal.hide();
                    setTimeout(chuyenTrang, 450);
                } else { window.location.assign(target); }
            });
        });
        if (window.bootstrap && bootstrap.Modal) { new bootstrap.Modal($('modal-ket-qua'), { backdrop: true, keyboard: true }).show(); }
        else { $('modal-ket-qua').classList.add('show'); $('modal-ket-qua').style.display = 'block'; }

        if (r.ok && r.vong_quay) quayVong(r.vong_quay, r.vi_tri);
        var vc = $('vc'); if (vc) vc.addEventListener('click', function () { var ma = vc.textContent.trim(); if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(ma).then(function(){ vc.dataset.c = 1; vc.textContent = ma + ' · Đã sao chép'; }).catch(function(){ window.prompt('Sao chép mã voucher:', ma); }); } else { window.prompt('Sao chép mã voucher:', ma); } });
    }

    // Vòng quay quyết định (cấp Khó + quà lớn)
    function quayVong(ds, viTri) {
        var cv = $('wheel'), c = cv.getContext('2d'), n = ds.length, R = 106, cx = 110, cy = 110;
        var mau = ['#1a66e6', '#4f93ff', '#0f2b52', '#7db1ff', '#1452bf', '#a9c9ff'];
        for (var i = 0; i < n; i++) {
            var a0 = (i / n) * Math.PI * 2 - Math.PI / 2, a1 = ((i + 1) / n) * Math.PI * 2 - Math.PI / 2;
            c.beginPath(); c.moveTo(cx, cy); c.arc(cx, cy, R, a0, a1); c.closePath(); c.fillStyle = mau[i % mau.length]; c.fill();
            c.save(); c.translate(cx, cy); c.rotate((a0 + a1) / 2); c.fillStyle = '#fff'; c.font = '600 12px Inter,sans-serif'; c.textAlign = 'right';
            var mm = ds[i].match(/(\d+)\.000/), t = mm ? mm[1] + 'K' : (ds[i].length > 10 ? ds[i].slice(0, 9) + '…' : ds[i]); c.font = '700 15px Inter,sans-serif'; c.fillText(t, R - 12, 5); c.restore();
        }
        c.beginPath(); c.arc(cx, cy, 14, 0, 7); c.fillStyle = '#fff'; c.fill();
        // góc để ô viTri nằm dưới mũi tên (ở trên cùng)
        var seg = 360 / n, target = 360 * 5 - (viTri * seg + seg / 2);
        cv.style.transition = 'transform 4.2s cubic-bezier(.12,.7,.1,1)';
        requestAnimationFrame(function () { cv.style.transform = 'rotate(' + target + 'deg)'; });
        setTimeout(function () { $('wheel-note').textContent = 'Vòng quay đã chọn: ' + ds[viTri]; $('kq-qua').style.display = ''; $('kq-qua').classList.add('page-enter'); }, 4400);
    }
})();
</script>
<?php endif; ?>
