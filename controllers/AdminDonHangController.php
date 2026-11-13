<?php

class AdminDonHangController
{
    private $donHangModel;
    private $sanPhamModel;

    public function __construct()
    {
        $this->donHangModel = new DonHangModel();
        $this->sanPhamModel = new SanPhamModel();
    }

    // Danh sách đơn đặt tour
    public function danhSach()
    {
        kiem_tra_admin();

        $danh_sach_don_hang = $this->donHangModel->layTatCaKemKhach();

        // Lọc theo trạng thái (nếu có)
        if (!empty($_GET['trang_thai'])) {
            $danh_sach_don_hang = array_values(array_filter(
                $danh_sach_don_hang,
                fn($dh) => $dh['trang_thai'] === $_GET['trang_thai']
            ));
        }

        $title = 'Quản Lý Đơn Đặt Tour';
        $view  = 'admin/don_hang/danh_sach';
        require_once PATH_VIEW_ADMIN;
    }

    // Xem chi tiết đơn đặt tour
    public function chiTiet()
    {
        kiem_tra_admin();

        $id       = (int)($_GET['id'] ?? 0);
        $don_hang = $this->donHangModel->layChiTietDonHang($id);

        if (!$don_hang) {
            dat_thong_bao('danger', 'Không tìm thấy đơn đặt tour!');
            chuyen_huong(BASE_URL . '?action=admin-don-hang');
        }

        $title = 'Chi Tiết Đơn Đặt Tour #' . $don_hang['ma_don_hang'];
        $view  = 'admin/don_hang/chi_tiet';
        require_once PATH_VIEW_ADMIN;
    }

    // Cập nhật trạng thái đơn đặt tour
    public function capNhatTrangThai()
    {
        kiem_tra_admin();

        $id         = (int)($_POST['id'] ?? 0);
        $trang_thai = $_POST['trang_thai'] ?? '';

        $cac_trang_thai_hop_le = ['cho_xac_nhan', 'da_xac_nhan', 'dang_giao', 'hoan_thanh', 'da_huy'];

        if (in_array($trang_thai, $cac_trang_thai_hop_le, true)) {
            $this->donHangModel->capNhatTrangThai($id, $trang_thai);
            dat_thong_bao('success', 'Cập nhật trạng thái đơn đặt tour thành công!');
        } else {
            dat_thong_bao('danger', 'Trạng thái không hợp lệ!');
        }

        chuyen_huong(BASE_URL . '?action=admin-don-hang-chi-tiet&id=' . $id);
    }
}
