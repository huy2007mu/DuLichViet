<?php

class GioHangController
{
    private $gioHangModel;
    private $sanPhamModel;

    public function __construct()
    {
        $this->gioHangModel = new GioHangModel();
        $this->sanPhamModel = new SanPhamModel();
    }

    // Xem giỏ tour
    public function xemGio()
    {
        kiem_tra_dang_nhap();

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $gio_hang      = $this->gioHangModel->layGioHang($nguoi_dung_id);

        $title = 'Giỏ Tour Của Bạn';
        $view  = 'client/gio_hang';
        require_once PATH_VIEW_CLIENT;
    }

    // Thêm tour vào giỏ
    public function them()
    {
        kiem_tra_dang_nhap();

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $san_pham_id   = (int)($_POST['san_pham_id'] ?? 0);
        $so_luong      = max(1, (int)($_POST['so_luong'] ?? 1));

        $san_pham = $this->sanPhamModel->layTheoId($san_pham_id);

        if (!$san_pham || (int)$san_pham['trang_thai'] === 0) {
            dat_thong_bao('danger', 'Tour không tồn tại!');
        } elseif ($san_pham['so_luong'] < 1) {
            dat_thong_bao('warning', 'Tour hiện đã hết chỗ!');
        } else {
            // Kiểm tra tổng số lượng (đã có trong giỏ + thêm mới) không vượt quá số chỗ còn
            $da_co = $this->gioHangModel->laySoLuongDaCo($nguoi_dung_id, $san_pham_id);
            if ($da_co + $so_luong > $san_pham['so_luong']) {
                $so_luong = max(0, $san_pham['so_luong'] - $da_co);
            }

            if ($so_luong < 1) {
                dat_thong_bao('warning', 'Tour "' . htmlspecialchars($san_pham['ten_san_pham']) . '" trong giỏ tour đã đạt số chỗ còn lại tối đa!');
            } else {
                $this->gioHangModel->themVaoGio($nguoi_dung_id, $san_pham_id, $so_luong);
                dat_thong_bao('success', 'Đã thêm "' . htmlspecialchars($san_pham['ten_san_pham']) . '" vào giỏ tour!');
            }
        }

        $ve_trang = $_POST['ve_trang'] ?? (BASE_URL . '?action=san-pham');
        chuyen_huong($ve_trang);
    }

    // Cập nhật số lượng trong giỏ
    public function capNhat()
    {
        kiem_tra_dang_nhap();

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $gio_hang_id   = (int)($_POST['gio_hang_id'] ?? 0);
        $so_luong      = max(1, (int)($_POST['so_luong'] ?? 1));

        // Giới hạn số lượng cập nhật không vượt quá số chỗ còn thực tế của tour
        $gio_hang = $this->gioHangModel->layGioHang($nguoi_dung_id);
        foreach ($gio_hang as $dong) {
            if ((int)$dong['gio_hang_id'] === $gio_hang_id && $so_luong > $dong['so_luong']) {
                $so_luong = max(1, (int)$dong['so_luong']);
                dat_thong_bao('warning', 'Số lượng đã được điều chỉnh về tối đa số chỗ hiện có (' . $so_luong . ')!');
            }
        }

        $this->gioHangModel->capNhatSoLuong($gio_hang_id, $nguoi_dung_id, $so_luong);
        if (empty($_SESSION['thong_bao'])) {
            dat_thong_bao('success', 'Đã cập nhật giỏ tour!');
        }

        chuyen_huong(BASE_URL . '?action=gio-hang');
    }

    // Cập nhật số lượng qua AJAX (trả về JSON) - dùng cho nút tăng/giảm real-time
    public function capNhatAjax()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (empty($_SESSION['nguoi_dung'])) {
            http_response_code(401);
            echo json_encode(['thanh_cong' => false, 'thong_bao' => 'Phiên đăng nhập đã hết hạn, vui lòng tải lại trang!']);
            exit;
        }

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $gio_hang_id   = (int)($_POST['gio_hang_id'] ?? 0);
        $so_luong      = max(1, (int)($_POST['so_luong'] ?? 1));

        $gio_hang = $this->gioHangModel->layGioHang($nguoi_dung_id);
        $dong_hien_tai = null;
        foreach ($gio_hang as $dong) {
            if ((int)$dong['gio_hang_id'] === $gio_hang_id) {
                $dong_hien_tai = $dong;
                break;
            }
        }

        if (!$dong_hien_tai) {
            http_response_code(404);
            echo json_encode(['thanh_cong' => false, 'thong_bao' => 'Không tìm thấy tour trong giỏ tour!']);
            exit;
        }

        // Giới hạn số lượng không vượt quá số chỗ còn thực tế (sp.so_luong)
        $ton_kho     = (int)$dong_hien_tai['so_luong'];
        $bi_gioi_han = $so_luong > $ton_kho;
        if ($bi_gioi_han) {
            $so_luong = max(1, $ton_kho);
        }

        $this->gioHangModel->capNhatSoLuong($gio_hang_id, $nguoi_dung_id, $so_luong);

        // Tính lại tổng cộng toàn bộ giỏ tour với số lượng mới
        $tong_gio_hang = 0;
        foreach ($gio_hang as $dong) {
            $sl = ((int)$dong['gio_hang_id'] === $gio_hang_id) ? $so_luong : (int)$dong['so_luong_dat'];
            $tong_gio_hang += $dong['gia'] * $sl;
        }

        echo json_encode([
            'thanh_cong'    => true,
            'so_luong'      => $so_luong,
            'thanh_tien'    => $dong_hien_tai['gia'] * $so_luong,
            'tong_gio_hang' => $tong_gio_hang,
            'ton_kho'       => $ton_kho,
            'bi_gioi_han'   => $bi_gioi_han,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Xoá 1 tour khỏi giỏ
    public function xoa()
    {
        kiem_tra_dang_nhap();

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $gio_hang_id   = (int)($_GET['id'] ?? 0);

        $this->gioHangModel->xoaKhoiGio($gio_hang_id, $nguoi_dung_id);
        dat_thong_bao('success', 'Đã xoá tour khỏi giỏ tour!');

        chuyen_huong(BASE_URL . '?action=gio-hang');
    }
}
