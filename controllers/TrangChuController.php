<?php

class TrangChuController
{
    public function index()
    {
        // Đảm bảo mã ưu đãi chung MAYVIET500 luôn tồn tại (hiển thị ở banner trang chủ)
        try { (new VoucherModel())->damBaoVoucherChung(); } catch (Throwable $e) {}

        $sanPhamModel = new SanPhamModel();
        $danhMucModel = new DanhMucModel();

        $danh_sach_danh_muc   = $danhMucModel->layTatCaKemSoLuong();
        $san_pham_moi_nhat    = $sanPhamModel->sanPhamMoiNhat(60);
        $san_pham_ban_chay    = $sanPhamModel->timKiemLoc(['sap_xep' => 'ban_chay']);
        $san_pham_ban_chay    = array_slice($san_pham_ban_chay, 0, 4);
        $tong_tour            = count($san_pham_moi_nhat);

        $title = 'Việt Nam đẹp lắm, mình đi thôi';
        $view  = 'client/trang_chu';

        require_once PATH_VIEW_CLIENT;
    }
}
