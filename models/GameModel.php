<?php

// ==========================================
// MINI-GAME NHẬN ƯU ĐÃI (Free-to-play & Play-to-earn)
// Bảng luot_choi_game tự tạo lần đầu chạy → không cần import lại SQL.
// ==========================================
class GameModel extends BaseModel
{
    protected $bang = 'luot_choi_game';

    public const SO_LUOT_MOI_DON = 2;   // mỗi đơn đặt tour được chơi 2 lượt
    public const MUC_TRUNG_BINH = 35;   // dưới 35% hoàn thành: không có quà
    public const MUC_CAO = 75;          // trên 75%: quà lớn

    // 3 cấp độ chơi (thời gian tính bằng giây, số cặp thẻ cần lật)
    public static function capDo()
    {
        return [
            'thuong'     => ['ten' => 'Bình thường', 'giay' => 300, 'cap' => 6,  'cot' => 4, 'mo_ta' => 'Thời gian 5 phút · quà là voucher giảm giá theo mức hoàn thành'],
            'trung_binh' => ['ten' => 'Trung bình',  'giay' => 180, 'cap' => 8,  'cot' => 4, 'mo_ta' => 'Thời gian 3 phút · voucher bữa ăn hoặc giảm tiền khách sạn'],
            'kho'        => ['ten' => 'Khó',         'giay' => 100, 'cap' => 10, 'cot' => 5, 'mo_ta' => 'Thời gian 1 phút 40 giây · quà lớn: miễn phí 1 điểm vui chơi do vòng quay quyết định'],
        ];
    }

    public static function diemVuiChoi()
    {
        return ['Voucher giảm 500.000 ₫', 'Voucher giảm 400.000 ₫', 'Voucher giảm 300.000 ₫', 'Voucher giảm 250.000 ₫', 'Voucher giảm 200.000 ₫', 'Voucher giảm 150.000 ₫'];
    }

    public function __construct()
    {
        parent::__construct();
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS luot_choi_game (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nguoi_dung_id INT NOT NULL,
            don_hang_id INT DEFAULT NULL,
            che_do ENUM('thu','that') NOT NULL DEFAULT 'that',
            cap_do VARCHAR(20) NOT NULL,
            phan_tram TINYINT NOT NULL DEFAULT 0,
            diem INT NOT NULL DEFAULT 0,
            qua_tang VARCHAR(255) DEFAULT NULL,
            ma_voucher VARCHAR(50) DEFAULT NULL,
            ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_nd (nguoi_dung_id), INDEX idx_dh (don_hang_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public function demLuotDaChoi($nguoi_dung_id, $don_hang_id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS t FROM luot_choi_game WHERE nguoi_dung_id=:u AND don_hang_id=:d AND che_do='that'");
        $stmt->execute([':u' => $nguoi_dung_id, ':d' => $don_hang_id]);
        return (int)$stmt->fetch()['t'];
    }

    public function layLichSu($nguoi_dung_id, $gioi_han = 10)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM luot_choi_game WHERE nguoi_dung_id=:u AND che_do='that' ORDER BY id DESC LIMIT " . (int)$gioi_han);
        $stmt->execute([':u' => $nguoi_dung_id]);
        return $stmt->fetchAll();
    }

    public function ghiLuot($d)
    {
        $stmt = $this->pdo->prepare("INSERT INTO luot_choi_game (nguoi_dung_id, don_hang_id, che_do, cap_do, phan_tram, diem, qua_tang, ma_voucher)
                                     VALUES (:u,:d,:c,:cap,:p,:diem,:qua,:ma)");
        return $stmt->execute([
            ':u' => $d['nguoi_dung_id'], ':d' => $d['don_hang_id'] ?? null, ':c' => $d['che_do'], ':cap' => $d['cap_do'],
            ':p' => $d['phan_tram'], ':diem' => $d['diem'], ':qua' => $d['qua_tang'] ?? null, ':ma' => $d['ma_voucher'] ?? null,
        ]);
    }

    // Quy đổi cấp độ + % hoàn thành → phần thưởng (null nếu không đạt chỉ tiêu)
    public function tinhPhanThuong($cap_do, $phan_tram)
    {
        if ($phan_tram < self::MUC_TRUNG_BINH) return null;
        $cao = $phan_tram > self::MUC_CAO;
        $bang = [
            'thuong' => [
                false => ['nhan' => 'Voucher giảm 3% tour (tối đa 150.000 ₫)',  'loai' => 'phan_tram', 'gia_tri' => 3, 'toi_da' => 150000, 'min' => 1500000],
                true  => ['nhan' => 'Voucher giảm 5% tour (tối đa 250.000 ₫)',  'loai' => 'phan_tram', 'gia_tri' => 5, 'toi_da' => 250000, 'min' => 1500000],
            ],
            'trung_binh' => [
                false => ['nhan' => 'Voucher giảm 150.000 ₫ khi đặt tour',      'loai' => 'tien_mat',  'gia_tri' => 150000, 'toi_da' => null, 'min' => 1000000],
                true  => ['nhan' => 'Voucher giảm 8% giá tour (tối đa 400.000 ₫)', 'loai' => 'phan_tram', 'gia_tri' => 8, 'toi_da' => 400000, 'min' => 2000000],
            ],
            'kho' => [
                false => ['nhan' => 'Voucher giảm 250.000 ₫ khi đặt tour',         'loai' => 'tien_mat',  'gia_tri' => 250000, 'toi_da' => null, 'min' => 1000000],
                true  => ['nhan' => 'Voucher giảm giá tour từ vòng quay (tối đa 500.000 ₫)', 'loai' => 'tien_mat', 'gia_tri' => 500000, 'toi_da' => null, 'min' => 1500000],
            ],
        ];
        return $bang[$cap_do][$cao] ?? null;
    }

    // Tạo voucher cá nhân (mã QUA-<id>-XXXX, chỉ chủ tài khoản dùng được, hạn 60 ngày)
    public function taoVoucherQua($nguoi_dung_id, $qua)
    {
        $ma = 'QUA-' . (int)$nguoi_dung_id . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
        $stmt = $this->pdo->prepare("INSERT INTO voucher (ma_voucher, loai_giam, gia_tri, giam_toi_da, don_toi_thieu, so_luong, ngay_bat_dau, ngay_ket_thuc, trang_thai)
                                     VALUES (:ma,:loai,:gt,:td,:min,1,CURDATE(),DATE_ADD(CURDATE(), INTERVAL 60 DAY),1)");
        $stmt->execute([':ma' => $ma, ':loai' => $qua['loai'], ':gt' => $qua['gia_tri'], ':td' => $qua['toi_da'], ':min' => $qua['min']]);
        return $ma;
    }
}
