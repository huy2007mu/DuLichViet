<?php

class AdminYeuThichController
{
    private $yeuThichModel;

    public function __construct()
    {
        $this->yeuThichModel = new YeuThichModel();
    }

    // Danh sách toàn bộ tour yêu thích của mọi khách hàng + top tour được yêu thích nhiều nhất
    public function danhSach()
    {
        kiem_tra_admin();

        $danh_sach_yeu_thich = $this->yeuThichModel->layTatCaKemThongTin();
        $top_san_pham        = $this->yeuThichModel->thongKeSanPhamDuocYeuThichNhieuNhat(5);

        $title = 'Quản Lý Tour Yêu Thích';
        $view  = 'admin/yeu_thich/danh_sach';
        require_once PATH_VIEW_ADMIN;
    }

    // Admin xoá 1 dòng yêu thích (ví dụ: dữ liệu rác, tài khoản vi phạm...)
    public function xoa()
    {
        kiem_tra_admin();

        $id = (int)($_GET['id'] ?? 0);
        $this->yeuThichModel->xoa($id);
        dat_thong_bao('success', 'Đã xoá mục yêu thích!');

        chuyen_huong(BASE_URL . '?action=admin-yeu-thich');
    }
}
