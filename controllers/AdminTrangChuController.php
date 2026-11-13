<?php

class AdminTrangChuController
{
    public function index()
    {
        kiem_tra_admin();

        $sanPhamModel   = new SanPhamModel();
        $danhMucModel   = new DanhMucModel();
        $nguoiDungModel = new NguoiDungModel();
        $donHangModel   = new DonHangModel();

        $tong_san_pham   = $sanPhamModel->demTatCa();
        $tong_danh_muc   = $danhMucModel->demTatCa();
        $tong_khach_hang = $nguoiDungModel->demKhachHang();
        $tong_doanh_thu  = $donHangModel->tongDoanhThu();

        $don_cho_xac_nhan = $donHangModel->demTheoTrangThai('cho_xac_nhan');
        $don_dang_giao    = $donHangModel->demTheoTrangThai('dang_giao');
        $don_hoan_thanh   = $donHangModel->demTheoTrangThai('hoan_thanh');

        // 5 đơn đặt tour gần nhất
        $don_hang_gan_day = array_slice($donHangModel->layTatCaKemKhach(), 0, 5);

        $title = 'Tổng Quan - Trang Quản Trị';
        $view  = 'admin/trang_chu';

        require_once PATH_VIEW_ADMIN;
    }
}
