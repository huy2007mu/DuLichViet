<?php

// ==========================================
// BẢNG XẾP HẠNG: người đặt tour nhiều nhất & người chơi mini-game nhiều điểm nhất
// ==========================================
class XepHangModel extends BaseModel
{
    protected $bang = 'don_hang';

    public const KY = ['tuan' => 'Tuần này', 'thang' => 'Tháng này', 'tat_ca' => 'Mọi thời điểm'];

    // Bảng luot_choi_game được xếp hạng truy vấn trực tiếp ở trang chủ,
    // nên phải tự tạo (nếu chưa có) ngay tại đây, không chờ GameModel chạy trước.
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

    private function dieuKienThoiGian($ky, $cot)
    {
        if ($ky === 'tuan')  return " AND $cot >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        if ($ky === 'thang') return " AND $cot >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        return '';
    }

    // Xếp hạng theo số tour đã đặt (không tính đơn đã huỷ). Hoà thì ai chi nhiều hơn xếp trước.
    public function topDatTour($ky = 'tat_ca', $gioi_han = 200)
    {
        $sql = "SELECT nd.id, nd.ho_ten, COUNT(d.id) AS so_chuyen, COALESCE(SUM(d.tong_tien),0) AS chi_tieu
                FROM don_hang d JOIN nguoi_dung nd ON nd.id = d.nguoi_dung_id
                WHERE d.trang_thai <> 'da_huy' AND nd.vai_tro = 'khach_hang'" . $this->dieuKienThoiGian($ky, 'd.ngay_tao') . "
                GROUP BY nd.id, nd.ho_ten
                ORDER BY so_chuyen DESC, chi_tieu DESC, nd.id ASC LIMIT " . (int)$gioi_han;
        return $this->pdo->query($sql)->fetchAll();
    }

    // Xếp hạng theo tổng điểm mini-game (chỉ lượt chơi thật). Hoà thì ai chơi ít lượt hơn xếp trước.
    public function topDiemGame($ky = 'tat_ca', $gioi_han = 200)
    {
        $sql = "SELECT nd.id, nd.ho_ten, SUM(g.diem) AS tong_diem, COUNT(g.id) AS so_luot, MAX(g.diem) AS diem_cao_nhat
                FROM luot_choi_game g JOIN nguoi_dung nd ON nd.id = g.nguoi_dung_id
                WHERE g.che_do = 'that' AND nd.vai_tro = 'khach_hang'" . $this->dieuKienThoiGian($ky, 'g.ngay_tao') . "
                GROUP BY nd.id, nd.ho_ten
                ORDER BY tong_diem DESC, so_luot ASC, nd.id ASC LIMIT " . (int)$gioi_han;
        return $this->pdo->query($sql)->fetchAll();
    }

    // Tìm thứ hạng của 1 người trong danh sách đã sắp xếp (trả về [hạng, dòng] hoặc null)
    public static function timHang($ds, $nguoi_dung_id)
    {
        foreach ($ds as $i => $r) {
            if ((int)$r['id'] === (int)$nguoi_dung_id) return [$i + 1, $r];
        }
        return null;
    }
}
