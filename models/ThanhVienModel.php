<?php

// ==========================================
// HẠNG THÀNH VIÊN (theo ghi chú nhóm): Explorer → Traveler → Globetrotter
// Tính tự động từ các đơn đặt tour đã "Hoàn thành" – không cần thêm bảng.
// ==========================================
class ThanhVienModel extends BaseModel
{
    protected $bang = 'don_hang';

    public const TOUR_PRO   = 5;          // Traveler: từ 5 tour
    public const TIEN_PRO   = 28000000;   //           hoặc đã chi 28 triệu
    public const TOUR_MAX   = 18;         // Globetrotter: từ 18 tour
    public const DIEM_DEN_MAX = 15;       //               hoặc đi 15 điểm đến khác nhau

    public static function danhSachHang()
    {
        return [
            1 => [
                'ten' => 'Explorer', 'ten_vi' => 'Hạng Thường', 'icon' => 'bi-compass', 'he_so' => 1,
                'dieu_kien' => 'Đăng ký tài khoản · Đặt tour đầu tiên',
                'quyen_loi' => ['Tích điểm cơ bản (1 điểm / 10.000 ₫)', 'Nhận 1 lượt quay thưởng khi hoàn thành tour', 'Voucher chào mừng CHAOMUNG10'],
            ],
            2 => [
                'ten' => 'Traveler', 'ten_vi' => 'Hạng Pro', 'icon' => 'bi-airplane', 'he_so' => 1.5,
                'dieu_kien' => 'Hoàn thành 5–10 tour hoặc đã chi tiêu từ 28 triệu ₫',
                'quyen_loi' => ['Nhận x1,5 điểm thưởng', 'Tặng voucher giảm giá sinh nhật', 'Mở khoá trò chơi đặc biệt với tỷ lệ trúng ưu đãi cao hơn'],
            ],
            3 => [
                'ten' => 'Globetrotter', 'ten_vi' => 'Hạng VIP Max', 'icon' => 'bi-globe-asia-australia', 'he_so' => 2,
                'dieu_kien' => 'Hoàn thành từ 18 tour hoặc đi 15 điểm đến khác nhau',
                'quyen_loi' => ['Nhận x2 điểm thưởng', 'Tặng 01 bữa ăn đặc sản địa phương miễn phí mỗi tháng', 'Ưu tiên hỗ trợ 24/7', 'Đổi quà với mức chiết khấu riêng'],
            ],
        ];
    }

    public function thongTin($nguoi_dung_id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS so_tour, COALESCE(SUM(tong_tien),0) AS chi_tieu
                                     FROM don_hang WHERE nguoi_dung_id = :u AND trang_thai = 'hoan_thanh'");
        $stmt->execute([':u' => $nguoi_dung_id]);
        $tong = $stmt->fetch();

        $stmt = $this->pdo->prepare("SELECT ct.ten_san_pham FROM don_hang_chi_tiet ct
                                     JOIN don_hang d ON d.id = ct.don_hang_id
                                     WHERE d.nguoi_dung_id = :u AND d.trang_thai = 'hoan_thanh'");
        $stmt->execute([':u' => $nguoi_dung_id]);
        $ds_diem_den = [];
        foreach ($stmt->fetchAll() as $dong) {
            $ds_diem_den[mb_strtolower(diem_den_tu_ten($dong['ten_san_pham']))] = true;
        }

        $so_tour = (int)$tong['so_tour'];
        $chi_tieu = (float)$tong['chi_tieu'];
        $so_diem_den = count($ds_diem_den);

        $hang = 1;
        if ($so_tour >= self::TOUR_PRO || $chi_tieu >= self::TIEN_PRO) $hang = 2;
        if ($so_tour >= self::TOUR_MAX || $so_diem_den >= self::DIEM_DEN_MAX) $hang = 3;

        $ds = self::danhSachHang();
        $he_so = $ds[$hang]['he_so'];

        // Tiến độ lên hạng kế tiếp (lấy điều kiện nào gần đạt nhất)
        $tien_do = 100; $con_thieu = '';
        if ($hang === 1) {
            $a = $so_tour / self::TOUR_PRO; $b = $chi_tieu / self::TIEN_PRO;
            $tien_do = (int)min(100, round(max($a, $b) * 100));
            $con_thieu = $a >= $b
                ? 'Còn ' . max(0, self::TOUR_PRO - $so_tour) . ' tour hoàn thành nữa để lên Traveler'
                : 'Còn ' . dinh_dang_tien(max(0, self::TIEN_PRO - $chi_tieu)) . ' chi tiêu nữa để lên Traveler';
        } elseif ($hang === 2) {
            $a = $so_tour / self::TOUR_MAX; $b = $so_diem_den / self::DIEM_DEN_MAX;
            $tien_do = (int)min(100, round(max($a, $b) * 100));
            $con_thieu = $a >= $b
                ? 'Còn ' . max(0, self::TOUR_MAX - $so_tour) . ' tour hoàn thành nữa để lên Globetrotter'
                : 'Còn ' . max(0, self::DIEM_DEN_MAX - $so_diem_den) . ' điểm đến khác nhau nữa để lên Globetrotter';
        } else {
            $con_thieu = 'Bạn đang ở hạng cao nhất – cảm ơn đã đồng hành cùng Mây Việt!';
        }

        return [
            'hang' => $hang, 'ten_hang' => $ds[$hang]['ten'], 'ten_vi' => $ds[$hang]['ten_vi'],
            'he_so' => $he_so, 'so_tour' => $so_tour, 'chi_tieu' => $chi_tieu, 'so_diem_den' => $so_diem_den,
            'diem' => (int)floor(($chi_tieu / 10000) * $he_so),
            'tien_do' => $tien_do, 'con_thieu' => $con_thieu,
        ];
    }
}
