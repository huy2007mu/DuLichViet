<?php

class DangNhapController
{
    private $nguoiDungModel;

    public function __construct()
    {
        $this->nguoiDungModel = new NguoiDungModel();
    }

    // Hiển thị form đăng nhập
    public function hienThi()
    {
        if (!empty($_SESSION['nguoi_dung'])) {
            $this->chuyenHuongSauDangNhap();
        }

        $title = 'Đăng Nhập';
        require_once PATH_VIEW . 'auth/dang_nhap.php';
    }

    // Xử lý đăng nhập
    public function xuLy()
    {
        $loi = [];

        $email    = trim($_POST['email'] ?? '');
        $mat_khau = trim($_POST['mat_khau'] ?? '');

        if (empty($email)) {
            $loi['email'] = 'Vui lòng nhập email!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $loi['email'] = 'Email không đúng định dạng!';
        }

        if (empty($mat_khau)) {
            $loi['mat_khau'] = 'Vui lòng nhập mật khẩu!';
        }

        if (empty($loi)) {
            $nguoi_dung = $this->nguoiDungModel->timTheoEmail($email);

            if (!$nguoi_dung || $nguoi_dung['mat_khau'] !== md5($mat_khau)) {
                $loi['chung'] = 'Email hoặc mật khẩu không chính xác!';
            } elseif ((int)$nguoi_dung['trang_thai'] === 0) {
                $loi['chung'] = 'Tài khoản của bạn đã bị khoá. Vui lòng liên hệ quản trị viên!';
            } else {
                $_SESSION['nguoi_dung'] = [
                    'id'      => $nguoi_dung['id'],
                    'ho_ten'  => $nguoi_dung['ho_ten'],
                    'email'   => $nguoi_dung['email'],
                    'vai_tro' => $nguoi_dung['vai_tro'],
                ];
                dat_thong_bao('success', "Chào mừng <b>{$nguoi_dung['ho_ten']}</b> đã quay trở lại!");
                $this->chuyenHuongSauDangNhap();
            }
        }

        $title = 'Đăng Nhập';
        require_once PATH_VIEW . 'auth/dang_nhap.php';
    }

    // Đăng xuất
    public function dangXuat()
    {
        session_destroy();
        chuyen_huong(BASE_URL . '?action=dang-nhap');
    }

    // Admin -> vào trang quản trị | Khách hàng -> vào trang chủ website
    private function chuyenHuongSauDangNhap()
    {
        if ($_SESSION['nguoi_dung']['vai_tro'] === 'admin') {
            chuyen_huong(BASE_URL . '?action=admin');
        }
        chuyen_huong(BASE_URL);
    }
}
