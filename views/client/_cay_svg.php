<?php
// Cảnh 4 cây theo cấp. Biến vào: $_cay_cap (0-4) – cấp hiện tại của người dùng.
// Cây đã đạt: sáng + lớn dần; cây đang ở: có nhãn; cây chưa đạt: mờ.
$_c = (int)($_cay_cap ?? 0);
$_trang_thai = function ($n) use ($_c) { return $n < $_c ? 'done' : ($n === $_c ? 'current' : 'locked'); };
?>
<svg class="cay-scene" viewBox="0 0 900 340" preserveAspectRatio="xMidYMax slice" role="img" aria-label="Cây thành tích cấp <?= $_c ?>">
    <defs>
        <linearGradient id="cs-sky" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#dfeeff"/><stop offset=".45" stop-color="#9ccbff"/><stop offset="1" stop-color="#eef6ff"/></linearGradient>
        <linearGradient id="cs-grass" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#7ccf5e"/><stop offset="1" stop-color="#3b9a3f"/></linearGradient>
        <linearGradient id="cs-water" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#a9dcf0"/><stop offset="1" stop-color="#7ec3de"/></linearGradient>
        <radialGradient id="cs-sun"><stop offset="0" stop-color="#fff6c2"/><stop offset="1" stop-color="#fff6c2" stop-opacity="0"/></radialGradient>
    </defs>
    <rect width="900" height="340" fill="url(#cs-sky)"/>
    <circle cx="790" cy="62" r="95" fill="url(#cs-sun)"/>
    <circle cx="790" cy="62" r="30" fill="#fff3b0"/>
    <g class="cs-cloud c1"><ellipse cx="130" cy="64" rx="58" ry="16" fill="#fff" opacity=".85"/><ellipse cx="168" cy="54" rx="36" ry="14" fill="#fff" opacity=".85"/></g>
    <g class="cs-cloud c2"><ellipse cx="470" cy="38" rx="50" ry="13" fill="#fff" opacity=".7"/><ellipse cx="500" cy="30" rx="30" ry="11" fill="#fff" opacity=".7"/></g>
    <path d="M0 250 L120 168 L230 236 L350 150 L470 240 L590 176 L720 244 L820 190 L900 236 L900 300 L0 300Z" fill="#8fc2cf" opacity=".75"/>
    <path d="M0 270 L90 214 L190 262 L300 200 L420 268 L540 214 L660 266 L780 216 L900 262 L900 300 L0 300Z" fill="#5fa6a0" opacity=".85"/>
    <rect y="262" width="900" height="44" fill="url(#cs-water)"/>
    <g class="cs-wave" stroke="#fff" stroke-opacity=".55" stroke-width="2" stroke-linecap="round"><path d="M60 278h50M240 286h60M430 276h46M620 284h56M800 278h52"/></g>
    <path d="M0 296 Q150 280 300 294 T600 292 T900 290 L900 340 L0 340Z" fill="url(#cs-grass)"/>

    <!-- Cấp 1: Mầm non -->
    <g class="tree t1 <?= $_trang_thai(1) ?>" style="--d:.1s">
        <ellipse cx="150" cy="304" rx="28" ry="7" fill="#6b4a2f"/>
        <g class="sway"><path d="M150 304 C150 290 153 280 150 266" stroke="#4aa24a" stroke-width="5" fill="none" stroke-linecap="round"/>
        <path d="M150 272 C130 262 118 272 117 284 C134 286 146 282 150 272Z" fill="#5cc05c"/>
        <path d="M150 268 C168 256 182 264 184 278 C166 280 154 276 150 268Z" fill="#46ad4e"/></g>
    </g>
    <!-- Cấp 2: Chồi non -->
    <g class="tree t2 <?= $_trang_thai(2) ?>" style="--d:.25s">
        <ellipse cx="340" cy="304" rx="30" ry="7" fill="#6b4a2f"/>
        <g class="sway"><path d="M340 304 C338 280 344 256 340 226" stroke="#3f9a43" stroke-width="6" fill="none" stroke-linecap="round"/>
        <path d="M340 276 C316 268 304 278 304 292 C322 294 336 288 340 276Z" fill="#5cc05c"/>
        <path d="M340 262 C362 252 378 262 378 276 C358 278 344 272 340 262Z" fill="#46ad4e"/>
        <path d="M340 244 C318 232 304 240 302 254 C322 258 336 254 340 244Z" fill="#6ccc66"/>
        <path d="M340 230 C362 214 380 220 384 236 C364 242 346 240 340 230Z" fill="#4fb255"/>
        <path d="M340 226 C334 208 340 196 350 190 C354 204 350 218 340 226Z" fill="#7bd873"/></g>
    </g>
    <!-- Cấp 3: Cành lá -->
    <g class="tree t3 <?= $_trang_thai(3) ?>" style="--d:.4s">
        <ellipse cx="560" cy="304" rx="40" ry="8" fill="#6b4a2f" opacity=".9"/>
        <path d="M553 304 L555 214 L565 214 L567 304Z" fill="#7a5233"/>
        <path d="M560 250 L536 232 M560 238 L586 220" stroke="#7a5233" stroke-width="5" stroke-linecap="round"/>
        <g class="sway">
            <circle cx="560" cy="186" r="50" fill="#3fa34d"/><circle cx="522" cy="206" r="36" fill="#47b055"/><circle cx="598" cy="206" r="36" fill="#47b055"/>
            <circle cx="560" cy="152" r="32" fill="#58c262"/><circle cx="540" cy="180" r="18" fill="#6fd479" opacity=".7"/><circle cx="586" cy="170" r="14" fill="#6fd479" opacity=".7"/>
        </g>
    </g>
    <!-- Cấp 4: Cây thành tựu -->
    <g class="tree t4 <?= $_trang_thai(4) ?>" style="--d:.55s">
        <ellipse cx="770" cy="304" rx="52" ry="9" fill="#6b4a2f" opacity=".9"/>
        <path d="M759 304 L763 204 L777 204 L781 304Z" fill="#7a5233"/>
        <path d="M770 252 L738 226 M770 240 L806 216 M770 270 L748 252" stroke="#7a5233" stroke-width="6" stroke-linecap="round"/>
        <g class="sway">
            <circle cx="770" cy="162" r="68" fill="#2f9a45"/><circle cx="722" cy="190" r="46" fill="#3aa84d"/><circle cx="818" cy="190" r="46" fill="#3aa84d"/>
            <circle cx="770" cy="112" r="42" fill="#4cbb5c"/><circle cx="734" cy="136" r="34" fill="#4cbb5c"/><circle cx="806" cy="136" r="34" fill="#4cbb5c"/>
            <circle cx="744" cy="170" r="22" fill="#69d579" opacity=".6"/>
            <g class="fruit"><circle cx="740" cy="150" r="7" fill="#f5a623"/><circle cx="790" cy="130" r="7" fill="#e5484d"/><circle cx="768" cy="178" r="7" fill="#f5a623"/>
            <circle cx="812" cy="176" r="7" fill="#e5484d"/><circle cx="716" cy="196" r="6" fill="#e5484d"/><circle cx="770" cy="108" r="6" fill="#f5a623"/><circle cx="830" cy="198" r="6" fill="#f5a623"/></g>
            <g class="spark" fill="#fff"><circle cx="722" cy="118" r="3"/><circle cx="824" cy="150" r="3"/><circle cx="780" cy="86" r="3"/></g>
        </g>
    </g>

    <?php if ($_c === 0): ?>
        <!-- Chưa có chuyến nào: hạt giống chờ nảy mầm -->
        <g class="tree current"><ellipse cx="150" cy="302" rx="20" ry="6" fill="#6b4a2f"/><ellipse cx="150" cy="297" rx="9" ry="6" fill="#a47a4f"/></g>
    <?php endif; ?>

    <?php $_nx = [0 => 150, 1 => 150, 2 => 340, 3 => 560, 4 => 770]; $_ny = [0 => 262, 1 => 238, 2 => 170, 3 => 98, 4 => 40];
          $_nhan = $_c === 0 ? 'Hạt giống của bạn' : 'Cây của bạn'; ?>
    <g transform="translate(<?= $_nx[$_c] ?>,<?= $_ny[$_c] ?>)"><g class="cs-tag">
        <rect x="-52" y="-16" width="104" height="26" rx="13" fill="#fff" stroke="#1a66e6" stroke-width="1.5"/>
        <text x="0" y="2" text-anchor="middle" font-size="12" font-weight="700" fill="#1a66e6" font-family="Inter,sans-serif"><?= $_nhan ?></text>
        <path d="M-6 10 L0 18 L6 10Z" fill="#1a66e6"/>
    </g></g>
</svg>
