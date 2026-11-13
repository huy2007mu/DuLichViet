<?php

class XepHangController
{
    public function bang()
    {
        $ky = $_GET['ky'] ?? 'tuan';
        if (!isset(XepHangModel::KY[$ky])) $ky = 'tuan';

        $model = new XepHangModel();
        $ds_dat_tour = $model->topDatTour($ky);
        $ds_game     = $model->topDiemGame($ky);

        $toi = ['dat_tour' => null, 'game' => null];
        if (!empty($_SESSION['nguoi_dung'])) {
            $toi['dat_tour'] = XepHangModel::timHang($ds_dat_tour, $_SESSION['nguoi_dung']['id']);
            $toi['game']     = XepHangModel::timHang($ds_game, $_SESSION['nguoi_dung']['id']);
        }

        $top_dat_tour = array_slice($ds_dat_tour, 0, 10);
        $top_game     = array_slice($ds_game, 0, 10);

        $title = 'Bảng xếp hạng – đặt tour nhiều nhất & điểm mini-game cao nhất';
        $meta_mo_ta = 'Bảng xếp hạng Mây Việt: vinh danh khách hàng đặt tour nhiều nhất và người chơi mini-game đạt điểm cao nhất.';
        $view = 'client/bang_xep_hang';
        require_once PATH_VIEW_CLIENT;
    }
}
