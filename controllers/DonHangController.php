<?php

class DonHangController
{
    private $donHangModel;
    private $gioHangModel;
    private $sanPhamModel;
    private $voucherModel;

    public function __construct()
    {
        $this->donHangModel = new DonHangModel();
        $this->gioHangModel = new GioHangModel();
        $this->sanPhamModel = new SanPhamModel();
        $this->voucherModel = new VoucherModel();
    }

    // Hiển thị trang đặt tour (checkout)
    public function hienThiDatHang()
    {
        kiem_tra_dang_nhap();

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $gio_hang      = $this->gioHangModel->layGioHang($nguoi_dung_id);

        if (empty($gio_hang)) {
            dat_thong_bao('warning', 'Giỏ tour của bạn đang trống!');
            chuyen_huong(BASE_URL . '?action=gio-hang');
        }

        $tam_tinh = array_sum(array_map(fn($sp) => $sp['gia'] * $sp['so_luong_dat'], $gio_hang));
        $voucher_ap_dung = $_SESSION['voucher_ap_dung'] ?? null;
        $tien_giam = $voucher_ap_dung ? $this->voucherModel->tinhTienGiam($voucher_ap_dung, $tam_tinh) : 0;

        $title = 'Xác Nhận Đặt Tour';
        $view  = 'client/dat_hang';
        require_once PATH_VIEW_CLIENT;
    }

    // Áp dụng mã voucher
    public function apDungVoucher()
    {
        kiem_tra_dang_nhap();

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $gio_hang      = $this->gioHangModel->layGioHang($nguoi_dung_id);
        $tam_tinh      = array_sum(array_map(fn($sp) => $sp['gia'] * $sp['so_luong_dat'], $gio_hang));

        try {
            $voucher = $this->voucherModel->layVoucherHopLe(trim($_POST['ma_voucher'] ?? ''), $tam_tinh, $nguoi_dung_id);
            $_SESSION['voucher_ap_dung'] = $voucher;
            dat_thong_bao('success', 'Áp dụng mã "' . $voucher['ma_voucher'] . '" thành công!');
        } catch (Exception $e) {
            unset($_SESSION['voucher_ap_dung']);
            dat_thong_bao('danger', $e->getMessage());
        }

        chuyen_huong(BASE_URL . '?action=dat-hang');
    }

    // Huỷ mã voucher đã áp dụng
    public function huyVoucher()
    {
        kiem_tra_dang_nhap();
        unset($_SESSION['voucher_ap_dung']);
        dat_thong_bao('success', 'Đã huỷ mã giảm giá!');
        chuyen_huong(BASE_URL . '?action=dat-hang');
    }

    // Xử lý đặt tour (không thanh toán online)
    public function xuLy()
    {
        kiem_tra_dang_nhap();

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $gio_hang      = $this->gioHangModel->layGioHang($nguoi_dung_id);

        if (empty($gio_hang)) {
            dat_thong_bao('warning', 'Giỏ tour của bạn đang trống!');
            chuyen_huong(BASE_URL . '?action=gio-hang');
        }

        $loi = $this->validateDatHang($_POST);

        // Kiểm tra số chỗ còn trước khi đặt (so_luong = số chỗ còn, so_luong_dat = số lượng khách chọn)
        foreach ($gio_hang as $sp) {
            if ($sp['so_luong'] < $sp['so_luong_dat']) {
                $loi['ton_kho'] = 'Tour "' . htmlspecialchars($sp['ten_san_pham']) . '" chỉ còn ' . $sp['so_luong'] . ' chỗ, vui lòng kiểm tra lại giỏ tour!';
            }
        }

        if (!empty($loi)) {
            $title = 'Xác Nhận Đặt Tour';
            $tam_tinh = array_sum(array_map(fn($sp) => $sp['gia'] * $sp['so_luong_dat'], $gio_hang));
            $voucher_ap_dung = $_SESSION['voucher_ap_dung'] ?? null;
            $tien_giam = $voucher_ap_dung ? $this->voucherModel->tinhTienGiam($voucher_ap_dung, $tam_tinh) : 0;
            $view = 'client/dat_hang';
            require_once PATH_VIEW_CLIENT;
            return;
        }

        $tam_tinh        = array_sum(array_map(fn($sp) => $sp['gia'] * $sp['so_luong_dat'], $gio_hang));
        $voucher_ap_dung = $_SESSION['voucher_ap_dung'] ?? null;
        $tien_giam       = $voucher_ap_dung ? $this->voucherModel->tinhTienGiam($voucher_ap_dung, $tam_tinh) : 0;
        $tong_tien       = $tam_tinh - $tien_giam;

        $danh_sach_san_pham = array_map(fn($sp) => [
            'id'           => $sp['id'],
            'ten_san_pham' => $sp['ten_san_pham'],
            'hinh_anh'     => $sp['hinh_anh'],
            'gia'          => $sp['gia'],
            'so_luong_mua' => $sp['so_luong_dat'],
        ], $gio_hang);

        try {
            $don_hang_id = $this->donHangModel->taoDonHang([
                'ma_don_hang'   => tao_ma_don_hang(),
                'nguoi_dung_id' => $nguoi_dung_id,
                'ho_ten_nhan'   => trim($_POST['ho_ten_nhan']),
                'so_dien_thoai' => trim($_POST['so_dien_thoai']),
                'dia_chi_giao'  => trim($_POST['dia_chi_giao']),
                'ngay_khoi_hanh' => $_POST['ngay_khoi_hanh'],
                'ghi_chu'       => trim($_POST['ghi_chu'] ?? ''),
                'voucher_id'    => $voucher_ap_dung['id'] ?? null,
                'tam_tinh'      => $tam_tinh,
                'tien_giam'     => $tien_giam,
                'tong_tien'     => $tong_tien,
            ], $danh_sach_san_pham);

            // Số chỗ và lượt dùng voucher đã được cập nhật nguyên tử bên trong DonHangModel.
            // Dọn giỏ tour & voucher tạm
            $this->gioHangModel->xoaSachGio($nguoi_dung_id);
            unset($_SESSION['voucher_ap_dung']);

            dat_thong_bao('success', 'Đặt tour thành công! Cảm ơn bạn đã đặt tour.');
            chuyen_huong(BASE_URL . '?action=dat-hang-thanh-cong&id=' . $don_hang_id);
        } catch (Exception $e) {
            dat_thong_bao('danger', $e->getMessage());
            chuyen_huong(BASE_URL . '?action=dat-hang');
        }
    }

    // Trang đặt tour thành công
    public function thanhCong()
    {
        kiem_tra_dang_nhap();

        $id       = (int)($_GET['id'] ?? 0);
        $don_hang = $this->donHangModel->layChiTietDonHang($id, $_SESSION['nguoi_dung']['id']);

        if (!$don_hang) {
            chuyen_huong(BASE_URL);
        }

        // Cây thành tích: đơn vừa đặt giúp cây lớn thêm
        $cay = CayThanhTichModel::tinhThongTin((new CayThanhTichModel())->demChuyen($_SESSION['nguoi_dung']['id']));

        $title = 'Đặt Tour Thành Công';
        $view  = 'client/don_hang_thanh_cong';
        require_once PATH_VIEW_CLIENT;
    }

    // Danh sách đơn đặt tour của tôi (theo dõi đơn đặt tour)
    public function donHangCuaToi()
    {
        kiem_tra_dang_nhap();

        $danh_sach_don_hang = $this->donHangModel->layDonHangCuaKhach($_SESSION['nguoi_dung']['id']);

        $title = 'Tour Của Tôi';
        $view  = 'client/don_hang_cua_toi';
        require_once PATH_VIEW_CLIENT;
    }

    // Chi tiết 1 đơn đặt tour của khách + form đánh giá cho đơn đã hoàn thành
    public function chiTiet()
    {
        kiem_tra_dang_nhap();

        $id            = (int)($_GET['id'] ?? 0);
        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $don_hang      = $this->donHangModel->layChiTietDonHang($id, $nguoi_dung_id);

        if (!$don_hang) {
            dat_thong_bao('danger', 'Không tìm thấy đơn đặt tour!');
            chuyen_huong(BASE_URL . '?action=don-hang-cua-toi');
        }

        // Đánh dấu tour nào đã được đánh giá (nếu đơn đã hoàn thành)
        $danhGiaModel = new DanhGiaModel();
        if ($don_hang['trang_thai'] === 'hoan_thanh') {
            foreach ($don_hang['san_pham'] as &$sp) {
                $sp['da_danh_gia'] = $danhGiaModel->daDanhGia($nguoi_dung_id, $sp['san_pham_id'], $id);
            }
            unset($sp);
        }

        $title = 'Chi Tiết Đơn Đặt Tour #' . $don_hang['ma_don_hang'];
        $view  = 'client/don_hang_chi_tiet';
        require_once PATH_VIEW_CLIENT;
    }

    // Khách tự huỷ đơn đặt tour (chỉ khi đang chờ xác nhận)
    public function huy()
    {
        kiem_tra_dang_nhap();

        $id = (int)($_GET['id'] ?? 0);

        if ($this->donHangModel->huyDonHang($id, $_SESSION['nguoi_dung']['id'])) {
            dat_thong_bao('success', 'Đã huỷ đơn đặt tour thành công!');
        } else {
            dat_thong_bao('warning', 'Không thể huỷ đơn đặt tour này (có thể đã được xử lý)!');
        }

        chuyen_huong(BASE_URL . '?action=don-hang-chi-tiet&id=' . $id);
    }

    // Validate thông tin dịch vụ
    private function validateDatHang($du_lieu)
    {
        $loi = [];

        if (empty(trim($du_lieu['ho_ten_nhan'] ?? ''))) {
            $loi['ho_ten_nhan'] = 'Vui lòng nhập họ tên người đặt tour!';
        }
        if (empty($du_lieu['so_dien_thoai']) || !preg_match('/^[0-9]{9,11}$/', $du_lieu['so_dien_thoai'])) {
            $loi['so_dien_thoai'] = 'Số điện thoại không hợp lệ!';
        }
        if (empty(trim($du_lieu['dia_chi_giao'] ?? ''))) {
            $loi['dia_chi_giao'] = 'Vui lòng nhập địa chỉ liên hệ!';
        }

        if (empty($du_lieu['ngay_khoi_hanh'])) {
            $loi['ngay_khoi_hanh'] = 'Vui lòng chọn ngày khởi hành!';
        } elseif (strtotime($du_lieu['ngay_khoi_hanh']) < strtotime('today')) {
            $loi['ngay_khoi_hanh'] = 'Ngày khởi hành không được ở trong quá khứ!';
        }

        return $loi;
    }
}
