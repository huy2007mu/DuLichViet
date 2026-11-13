<?php

class ThanhVienController
{
    // Trang "Hạng thành viên & thành tích"
    public function hang()
    {
        $ds_hang = ThanhVienModel::danhSachHang();
        $thong_tin = null;
        $lich_su_game = [];
        if (!empty($_SESSION['nguoi_dung'])) {
            $thong_tin = (new ThanhVienModel())->thongTin($_SESSION['nguoi_dung']['id']);
            $lich_su_game = (new GameModel())->layLichSu($_SESSION['nguoi_dung']['id']);
        }
        $title = 'Hạng thành viên & thành tích';
        $meta_mo_ta = 'Hạng thành viên Mây Việt: Explorer, Traveler, Globetrotter – tích điểm, nhận voucher sinh nhật, bữa ăn đặc sản và hỗ trợ 24/7.';
        $view = 'client/hang_thanh_vien';
        require_once PATH_VIEW_CLIENT;
    }
}
