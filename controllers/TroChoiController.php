<?php

class TroChoiController
{
    private $gameModel;

    public function __construct()
    {
        $this->gameModel = new GameModel();
    }

    // Chơi thử miễn phí (Free-to-play): không cần đăng nhập, không có quà → dẫn người chơi tới đặt tour
    public function choiThu()
    {
        $che_do = 'thu';
        $cap_do_ds = GameModel::capDo();
        $don_hang = null;
        $luot_con_lai = null;
        $title = 'Chơi thử miễn phí – Mây Việt';
        $view = 'client/tro_choi';
        require_once PATH_VIEW_CLIENT;
    }

    // Chơi thật (Play-to-earn): mỗi đơn đặt tour có 2 lượt, nhận voucher theo mức hoàn thành
    public function choiThat()
    {
        kiem_tra_dang_nhap();
        $uid = $_SESSION['nguoi_dung']['id'];
        $don_hang = (new DonHangModel())->layChiTietDonHang((int)($_GET['don_hang_id'] ?? 0), $uid);
        if (!$don_hang || $don_hang['trang_thai'] === 'da_huy') {
            dat_thong_bao('warning', 'Bạn cần có đơn đặt tour hợp lệ để chơi nhận quà.');
            chuyen_huong(BASE_URL . '?action=don-hang-cua-toi');
        }
        $luot_con_lai = max(0, GameModel::SO_LUOT_MOI_DON - $this->gameModel->demLuotDaChoi($uid, $don_hang['id']));
        $che_do = 'that';
        $cap_do_ds = GameModel::capDo();
        $title = 'Mini-game nhận ưu đãi – Mây Việt';
        $view = 'client/tro_choi';
        require_once PATH_VIEW_CLIENT;
    }

    private function json($du_lieu, $ma = 200)
    {
        http_response_code($ma);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($du_lieu, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Bắt đầu 1 lượt chơi (ghi lại thời điểm để chống gian lận)
    public function batDau()
    {
        $che_do = $_POST['che_do'] ?? 'thu';
        $cap_do = $_POST['cap_do'] ?? 'thuong';
        $ds = GameModel::capDo();
        if (!isset($ds[$cap_do])) $this->json(['ok' => false, 'loi' => 'Cấp độ không hợp lệ'], 400);

        $don_id = null;
        if ($che_do === 'that') {
            if (empty($_SESSION['nguoi_dung'])) $this->json(['ok' => false, 'loi' => 'Vui lòng đăng nhập'], 401);
            $uid = $_SESSION['nguoi_dung']['id'];
            $don_id = (int)($_POST['don_hang_id'] ?? 0);
            $don = (new DonHangModel())->layChiTietDonHang($don_id, $uid);
            if (!$don || $don['trang_thai'] === 'da_huy') $this->json(['ok' => false, 'loi' => 'Đơn đặt tour không hợp lệ'], 403);
            if ($this->gameModel->demLuotDaChoi($uid, $don_id) >= GameModel::SO_LUOT_MOI_DON) {
                $this->json(['ok' => false, 'loi' => 'Đơn này đã dùng hết lượt chơi'], 403);
            }
        }
        $_SESSION['game_bat_dau'] = ['t' => time(), 'che_do' => $che_do, 'cap_do' => $cap_do, 'don' => $don_id];
        $this->json(['ok' => true, 'giay' => $ds[$cap_do]['giay']]);
    }

    // Nhận kết quả → tính quà → tạo voucher
    public function ketQua()
    {
        $bd = $_SESSION['game_bat_dau'] ?? null;
        unset($_SESSION['game_bat_dau']);
        if (!$bd) $this->json(['ok' => false, 'loi' => 'Lượt chơi không hợp lệ'], 400);

        $ds = GameModel::capDo();
        $cd = $ds[$bd['cap_do']];
        $so_cap_khop = max(0, min($cd['cap'], (int)($_POST['so_cap_khop'] ?? 0)));
        $phan_tram = (int)round($so_cap_khop / $cd['cap'] * 100);
        $troi_qua = time() - $bd['t'];

        // Chống gian lận: không thể lật xong quá nhanh hoặc quá thời gian cho phép
        if ($troi_qua > $cd['giay'] + 30 || ($so_cap_khop > 0 && $troi_qua < $so_cap_khop * 0.5)) {
            $this->json(['ok' => false, 'loi' => 'Kết quả không hợp lệ, vui lòng chơi lại.'], 400);
        }
        $diem = $so_cap_khop * 10 + ($phan_tram === 100 ? max(0, (int)(($cd['giay'] - $troi_qua) / 5)) : 0);

        // ----- Chơi thử: không có quà -----
        if ($bd['che_do'] !== 'that') {
            $this->json(['ok' => true, 'che_do' => 'thu', 'phan_tram' => $phan_tram, 'diem' => $diem, 'dat_100' => $phan_tram === 100]);
        }

        // ----- Chơi thật -----
        if (empty($_SESSION['nguoi_dung'])) $this->json(['ok' => false, 'loi' => 'Vui lòng đăng nhập'], 401);
        $uid = $_SESSION['nguoi_dung']['id'];
        if ($this->gameModel->demLuotDaChoi($uid, $bd['don']) >= GameModel::SO_LUOT_MOI_DON) {
            $this->json(['ok' => false, 'loi' => 'Đơn này đã dùng hết lượt chơi'], 403);
        }

        $qua = $this->gameModel->tinhPhanThuong($bd['cap_do'], $phan_tram);
        $ma = null; $vong_quay = null; $vi_tri = null;
        if ($qua) {
            // Cấp Khó + quà lớn: vòng quay quyết định điểm vui chơi miễn phí
            if ($bd['cap_do'] === 'kho' && $phan_tram > GameModel::MUC_CAO) {
                $vong_quay = GameModel::diemVuiChoi();
                $vi_tri = array_rand($vong_quay);
                // Mức giảm hiển thị trên vòng quay phải trùng với giá trị voucher thực tế.
                $muc_giam_vong_quay = [500000, 400000, 300000, 250000, 200000, 150000];
                $qua['loai'] = 'tien_mat';
                $qua['gia_tri'] = $muc_giam_vong_quay[$vi_tri];
                $qua['toi_da'] = null;
                $qua['nhan'] = $vong_quay[$vi_tri] . ' khi đặt tour';
            }
            $ma = $this->gameModel->taoVoucherQua($uid, $qua);
        }
        $this->gameModel->ghiLuot([
            'nguoi_dung_id' => $uid, 'don_hang_id' => $bd['don'], 'che_do' => 'that', 'cap_do' => $bd['cap_do'],
            'phan_tram' => $phan_tram, 'diem' => $diem, 'qua_tang' => $qua['nhan'] ?? null, 'ma_voucher' => $ma,
        ]);

        $this->json([
            'ok' => true, 'che_do' => 'that', 'phan_tram' => $phan_tram, 'diem' => $diem,
            'qua' => $qua['nhan'] ?? null, 'ma' => $ma, 'vong_quay' => $vong_quay, 'vi_tri' => $vi_tri,
            'luot_con_lai' => max(0, GameModel::SO_LUOT_MOI_DON - $this->gameModel->demLuotDaChoi($uid, $bd['don'])),
        ]);
    }
}
