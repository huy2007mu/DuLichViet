<?php

// ==========================================
// CÂY THÀNH TÍCH: đặt tour càng nhiều, cây càng lớn, quà càng to
// "Chuyến đã đặt" = số đơn đặt tour chưa bị huỷ.
// Bảng cay_thanh_tich_qua tự tạo lần đầu chạy (không cần import lại SQL).
// ==========================================
class CayThanhTichModel extends BaseModel
{
    protected $bang = 'cay_thanh_tich_qua';

    // Cấp của cây (0 = hạt giống). 'tu' = số chuyến tối thiểu để đạt cấp.
    public static function capCay()
    {
        return [
            1 => ['ten' => 'Mầm non',       'tu' => 1,  'khoang' => '1 – 2 chuyến',  'icon' => 'bi-flower1',
                  'qua_ten' => 'Tích điểm đổi quà',        'qua_mo_ta' => 'Voucher giảm 50.000 ₫',
                  'voucher' => ['loai' => 'tien_mat',  'gia_tri' => 50000,  'toi_da' => null,    'min' => 1000000]],
            2 => ['ten' => 'Chồi non',      'tu' => 3,  'khoang' => '3 – 5 chuyến',  'icon' => 'bi-flower2',
                  'qua_ten' => 'Giảm giá đến 10%',         'qua_mo_ta' => 'Voucher giảm 10% (tối đa 300.000 ₫)',
                  'voucher' => ['loai' => 'phan_tram', 'gia_tri' => 10,     'toi_da' => 300000,  'min' => 2000000]],
            3 => ['ten' => 'Cành lá',       'tu' => 6,  'khoang' => '6 – 10 chuyến', 'icon' => 'bi-tree',
                  'qua_ten' => 'Ưu tiên đặt tour mới',     'qua_mo_ta' => 'Ưu tiên tour mới + voucher giảm 200.000 ₫',
                  'voucher' => ['loai' => 'tien_mat',  'gia_tri' => 200000, 'toi_da' => null,    'min' => 2000000]],
            4 => ['ten' => 'Cây thành tựu', 'tu' => 11, 'khoang' => 'Trên 10 chuyến','icon' => 'bi-trophy',
                  'qua_ten' => 'Giảm 50% + voucher',       'qua_mo_ta' => 'Voucher giảm 50% (tối đa 1.000.000 ₫)',
                  'voucher' => ['loai' => 'phan_tram', 'gia_tri' => 50,     'toi_da' => 1000000, 'min' => 3000000]],
        ];
    }

    public function __construct()
    {
        parent::__construct();
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS cay_thanh_tich_qua (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nguoi_dung_id INT NOT NULL,
            cap TINYINT NOT NULL,
            ma_voucher VARCHAR(50) NOT NULL,
            ngay_nhan DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uq_nd_cap (nguoi_dung_id, cap)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public function demChuyen($nguoi_dung_id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS t FROM don_hang WHERE nguoi_dung_id = :u AND trang_thai <> 'da_huy'");
        $stmt->execute([':u' => $nguoi_dung_id]);
        return (int)$stmt->fetch()['t'];
    }

    // Cấp cây ứng với số chuyến
    public static function capTuSoChuyen($so_chuyen)
    {
        $cap = 0;
        foreach (self::capCay() as $k => $c) { if ($so_chuyen >= $c['tu']) $cap = $k; }
        return $cap;
    }

    public function layQuaDaNhan($nguoi_dung_id)
    {
        $stmt = $this->pdo->prepare("SELECT cap, ma_voucher FROM cay_thanh_tich_qua WHERE nguoi_dung_id = :u");
        $stmt->execute([':u' => $nguoi_dung_id]);
        $kq = [];
        foreach ($stmt->fetchAll() as $r) $kq[(int)$r['cap']] = $r['ma_voucher'];
        return $kq;
    }

    // Thông tin hiển thị: cấp hiện tại, tiến độ, số chuyến còn thiếu
    public function thongTin($nguoi_dung_id)
    {
        return self::tinhThongTin($this->demChuyen($nguoi_dung_id));
    }

    public static function tinhThongTin($so_chuyen)
    {
        $ds = self::capCay();
        $cap = self::capTuSoChuyen($so_chuyen);
        $tiep = $ds[$cap + 1] ?? null;
        $tu = $cap > 0 ? $ds[$cap]['tu'] : 0;
        $pct = 100; $con = 0;
        if ($tiep) {
            $con = $tiep['tu'] - $so_chuyen;
            $pct = (int)round(($so_chuyen - $tu) / max(1, $tiep['tu'] - $tu) * 100);
        }
        $ten = $cap > 0 ? $ds[$cap]['ten'] : 'Hạt giống';
        $mo_ta = $tiep
            ? "Cây đang ở cấp {$ten}. Còn {$con} chuyến nữa để lên cấp tiếp theo ({$tiep['ten']})."
            : "Cây của bạn đã đạt cấp cao nhất – {$ten}! Cảm ơn bạn đã đồng hành cùng Mây Việt.";
        if ($cap === 0) $mo_ta = 'Cây của bạn vẫn là hạt giống. Đặt chuyến đầu tiên để nảy mầm!';
        return ['so_chuyen' => $so_chuyen, 'cap' => $cap, 'ten_cap' => $ten, 'tiep' => $tiep, 'con_thieu' => $con, 'pct' => $pct, 'mo_ta' => $mo_ta];
    }

    // Nhận quà của 1 cấp (mỗi cấp chỉ nhận 1 lần) → tạo voucher cá nhân CAY-<id>-<cấp>-XXXX
    public function nhanQua($nguoi_dung_id, $cap)
    {
        $ds = self::capCay();
        if (!isset($ds[$cap])) throw new Exception('Cấp quà không hợp lệ.');
        if ($this->demChuyen($nguoi_dung_id) < $ds[$cap]['tu']) throw new Exception('Cây của bạn chưa đạt cấp này.');

        $da = $this->layQuaDaNhan($nguoi_dung_id);
        if (isset($da[$cap])) throw new Exception('Bạn đã nhận quà của cấp này rồi.');

        $v = $ds[$cap]['voucher'];
        $ma = 'CAY-' . (int)$nguoi_dung_id . '-' . (int)$cap . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare("INSERT INTO voucher (ma_voucher, loai_giam, gia_tri, giam_toi_da, don_toi_thieu, so_luong, ngay_bat_dau, ngay_ket_thuc, trang_thai)
                                 VALUES (:ma,:loai,:gt,:td,:min,1,CURDATE(),DATE_ADD(CURDATE(), INTERVAL 90 DAY),1)")
                ->execute([':ma' => $ma, ':loai' => $v['loai'], ':gt' => $v['gia_tri'], ':td' => $v['toi_da'], ':min' => $v['min']]);
            $this->pdo->prepare("INSERT INTO cay_thanh_tich_qua (nguoi_dung_id, cap, ma_voucher) VALUES (:u,:c,:m)")
                ->execute([':u' => $nguoi_dung_id, ':c' => $cap, ':m' => $ma]);
            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw new Exception('Không thể nhận quà lúc này, vui lòng thử lại.');
        }
        return $ma;
    }
}
