<?php

class YeuThichController
{
    private $yeuThichModel;
    private $sanPhamModel;

    public function __construct()
    {
        $this->yeuThichModel = new YeuThichModel();
        $this->sanPhamModel  = new SanPhamModel();
    }

    // Trang "Tour yêu thích" của khách hàng
    public function danhSach()
    {
        kiem_tra_dang_nhap();

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $danh_sach_san_pham = $this->yeuThichModel->layYeuThich($nguoi_dung_id);

        $title = 'Tour Yêu Thích';
        $view  = 'client/yeu_thich';
        require_once PATH_VIEW_CLIENT;
    }

    // Bật / tắt yêu thích cho 1 tour (dùng cho icon trái tim ở thẻ tour / trang chi tiết)
    // Điều hướng lại đúng trang vừa bấm (ve_trang) - không cần AJAX, hoạt động ở mọi nơi.
    public function toggle()
    {
        kiem_tra_dang_nhap();

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $san_pham_id   = (int)($_GET['san_pham_id'] ?? 0);
        $ve_trang      = $_GET['ve_trang'] ?? (BASE_URL . '?action=san-pham');

        $san_pham = $this->sanPhamModel->layTheoId($san_pham_id);

        if (!$san_pham) {
            dat_thong_bao('danger', 'Tour không tồn tại!');
        } else {
            $vua_them = $this->yeuThichModel->toggle($nguoi_dung_id, $san_pham_id);
            if ($vua_them) {
                dat_thong_bao('success', 'Đã thêm "' . htmlspecialchars($san_pham['ten_san_pham']) . '" vào danh sách yêu thích!');
            } else {
                dat_thong_bao('success', 'Đã bỏ "' . htmlspecialchars($san_pham['ten_san_pham']) . '" khỏi danh sách yêu thích!');
            }
        }

        chuyen_huong($ve_trang);
    }

    // Xoá 1 tour khỏi danh sách yêu thích (dùng ở trang danh sách yêu thích)
    public function xoa()
    {
        kiem_tra_dang_nhap();

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $yeu_thich_id  = (int)($_GET['id'] ?? 0);

        $this->yeuThichModel->xoaTheoDong($yeu_thich_id, $nguoi_dung_id);
        dat_thong_bao('success', 'Đã xoá tour khỏi danh sách yêu thích!');

        chuyen_huong(BASE_URL . '?action=yeu-thich');
    }
}
