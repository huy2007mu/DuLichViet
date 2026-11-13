<?php

class AdminDanhGiaController
{
    private $danhGiaModel;

    public function __construct()
    {
        $this->danhGiaModel = new DanhGiaModel();
    }

    // Danh sách tất cả đánh giá
    public function danhSach()
    {
        kiem_tra_admin();

        $danh_sach_danh_gia = $this->danhGiaModel->layTatCaKemThongTin();

        $title = 'Quản Lý Bình Luận / Đánh Giá';
        $view  = 'admin/danh_gia/danh_sach';
        require_once PATH_VIEW_ADMIN;
    }

    // Ẩn đánh giá (vi phạm nội dung...)
    public function an()
    {
        kiem_tra_admin();

        $id = (int)($_GET['id'] ?? 0);
        $this->danhGiaModel->doiTrangThai($id, 0);
        dat_thong_bao('success', 'Đã ẩn đánh giá!');

        chuyen_huong(BASE_URL . '?action=admin-danh-gia');
    }

    // Hiện lại đánh giá
    public function hien()
    {
        kiem_tra_admin();

        $id = (int)($_GET['id'] ?? 0);
        $this->danhGiaModel->doiTrangThai($id, 1);
        dat_thong_bao('success', 'Đã hiện lại đánh giá!');

        chuyen_huong(BASE_URL . '?action=admin-danh-gia');
    }

    // Xoá đánh giá
    public function xoa()
    {
        kiem_tra_admin();

        $id = (int)($_GET['id'] ?? 0);
        $this->danhGiaModel->xoa($id);
        dat_thong_bao('success', 'Xoá đánh giá thành công!');

        chuyen_huong(BASE_URL . '?action=admin-danh-gia');
    }
}
