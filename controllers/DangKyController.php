<?php

class DangKyController
{
    private $nguoiDungModel;

    public function __construct()
    {
        $this->nguoiDungModel = new NguoiDungModel();
    }

    // Hiển thị form đăng ký
    public function hienThi()
    {
        if (!empty($_SESSION['nguoi_dung'])) {
            chuyen_huong(BASE_URL);
        }

        $title = 'Đăng Ký Tài Khoản';
        require_once PATH_VIEW . 'auth/dang_ky.php';
    }

    // Xử lý đăng ký
    public function xuLy()
    {
        $loi = $this->validate($_POST);

        if (empty($loi)) {
            $this->nguoiDungModel->them([
                'ho_ten'        => trim($_POST['ho_ten']),
                'email'         => trim($_POST['email']),
                'mat_khau'      => $_POST['mat_khau'],
                'so_dien_thoai' => trim($_POST['so_dien_thoai'] ?? ''),
                'dia_chi'       => trim($_POST['dia_chi'] ?? ''),
                'vai_tro'       => 'khach_hang',
            ]);

            dat_thong_bao('success', 'Đăng ký thành công! Vui lòng đăng nhập để đặt tour.');
            chuyen_huong(BASE_URL . '?action=dang-nhap');
        }

        $title = 'Đăng Ký Tài Khoản';
        require_once PATH_VIEW . 'auth/dang_ky.php';
    }

    // Validate dữ liệu đăng ký
    private function validate($du_lieu)
    {
        $loi = [];

        if (empty(trim($du_lieu['ho_ten'] ?? ''))) {
            $loi['ho_ten'] = 'Vui lòng nhập họ tên!';
        }

        $email = trim($du_lieu['email'] ?? '');
        if (empty($email)) {
            $loi['email'] = 'Vui lòng nhập email!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $loi['email'] = 'Email không đúng định dạng!';
        } elseif ($this->nguoiDungModel->kiemTraEmail($email)) {
            $loi['email'] = 'Email này đã được đăng ký!';
        }

        if (empty($du_lieu['so_dien_thoai'])) {
            $loi['so_dien_thoai'] = 'Vui lòng nhập số điện thoại!';
        } elseif (!preg_match('/^[0-9]{9,11}$/', $du_lieu['so_dien_thoai'])) {
            $loi['so_dien_thoai'] = 'Số điện thoại không hợp lệ!';
        }

        if (empty($du_lieu['mat_khau'])) {
            $loi['mat_khau'] = 'Vui lòng nhập mật khẩu!';
        } elseif (strlen($du_lieu['mat_khau']) < 6) {
            $loi['mat_khau'] = 'Mật khẩu ít nhất 6 ký tự!';
        }

        if (($du_lieu['xac_nhan_mat_khau'] ?? '') !== ($du_lieu['mat_khau'] ?? '')) {
            $loi['xac_nhan_mat_khau'] = 'Mật khẩu xác nhận không khớp!';
        }

        return $loi;
    }
}
