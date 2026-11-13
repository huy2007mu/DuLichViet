<?php

class CayThanhTichController
{
    private $model;

    public function __construct()
    {
        $this->model = new CayThanhTichModel();
    }

    public function cay()
    {
        $dang_nhap = !empty($_SESSION['nguoi_dung']);
        $so_chuyen = $dang_nhap ? $this->model->demChuyen($_SESSION['nguoi_dung']['id']) : 0;
        $cay = CayThanhTichModel::tinhThongTin($so_chuyen);
        $ds_cap = CayThanhTichModel::capCay();
        $qua_da_nhan = $dang_nhap ? $this->model->layQuaDaNhan($_SESSION['nguoi_dung']['id']) : [];

        $title = 'Cây thành tích – đặt tour nhiều hơn, cây lớn hơn';
        $meta_mo_ta = 'Cây thành tích Mây Việt: mỗi chuyến đi là một bước trưởng thành của cây, giúp bạn nhận thêm nhiều ưu đãi hấp dẫn.';
        $view = 'client/cay_thanh_tich';
        require_once PATH_VIEW_CLIENT;
    }

    public function nhanQua()
    {
        kiem_tra_dang_nhap();
        $cap = (int)($_POST['cap'] ?? 0);
        try {
            $ma = $this->model->nhanQua($_SESSION['nguoi_dung']['id'], $cap);
            $ten = CayThanhTichModel::capCay()[$cap]['qua_ten'];
            dat_thong_bao('success', "Đã nhận quà \"$ten\"! Mã voucher của bạn: $ma (dùng ở bước xác nhận đặt tour, hạn 90 ngày).");
        } catch (Exception $e) {
            dat_thong_bao('warning', $e->getMessage());
        }
        chuyen_huong(BASE_URL . '?action=cay-thanh-tich');
    }
}
