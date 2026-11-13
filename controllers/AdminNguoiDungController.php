<?php

class AdminNguoiDungController
{
    private $nguoiDungModel;

    public function __construct()
    {
        $this->nguoiDungModel = new NguoiDungModel();
    }

    // Danh sách người dùng
    public function danhSach()
    {
        kiem_tra_admin();

        $danh_sach_nguoi_dung = $this->nguoiDungModel->layTatCa();

        $title = 'Quản Lý Tài Khoản';
        $view  = 'admin/nguoi_dung/danh_sach';
        require_once PATH_VIEW_ADMIN;
    }

    // Hiển thị form thêm
    public function them()
    {
        kiem_tra_admin();

        $title = 'Thêm Tài Khoản';
        $view  = 'admin/nguoi_dung/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Lưu người dùng mới
    public function luu()
    {
        kiem_tra_admin();

        $loi = $this->validateNguoiDung($_POST, false);

        if (empty($loi)) {
            $this->nguoiDungModel->them([
                'ho_ten'        => trim($_POST['ho_ten']),
                'email'         => trim($_POST['email']),
                'mat_khau'      => $_POST['mat_khau'],
                'so_dien_thoai' => trim($_POST['so_dien_thoai'] ?? ''),
                'dia_chi'       => trim($_POST['dia_chi'] ?? ''),
                'vai_tro'       => $_POST['vai_tro'],
            ]);
            dat_thong_bao('success', 'Thêm tài khoản thành công!');
            chuyen_huong(BASE_URL . '?action=admin-nguoi-dung');
        }

        $title = 'Thêm Tài Khoản';
        $view  = 'admin/nguoi_dung/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Hiển thị form sửa
    public function sua()
    {
        kiem_tra_admin();

        $id         = (int)($_GET['id'] ?? 0);
        $nguoi_dung = $this->nguoiDungModel->layTheoId($id);

        if (!$nguoi_dung) {
            dat_thong_bao('danger', 'Không tìm thấy tài khoản!');
            chuyen_huong(BASE_URL . '?action=admin-nguoi-dung');
        }

        $title = 'Sửa Tài Khoản';
        $view  = 'admin/nguoi_dung/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Cập nhật người dùng
    public function capNhat()
    {
        kiem_tra_admin();

        $id  = (int)$_POST['id'];
        $loi = $this->validateNguoiDung($_POST, true, $id);

        if (empty($loi)) {
            $this->nguoiDungModel->capNhat($id, [
                'ho_ten'        => trim($_POST['ho_ten']),
                'email'         => trim($_POST['email']),
                'mat_khau'      => $_POST['mat_khau'],
                'so_dien_thoai' => trim($_POST['so_dien_thoai'] ?? ''),
                'dia_chi'       => trim($_POST['dia_chi'] ?? ''),
                'vai_tro'       => $_POST['vai_tro'],
                'trang_thai'    => isset($_POST['trang_thai']) ? 1 : 0,
            ]);
            dat_thong_bao('success', 'Cập nhật tài khoản thành công!');
            chuyen_huong(BASE_URL . '?action=admin-nguoi-dung');

            // Nếu admin tự sửa hồ sơ chính mình, đồng bộ lại session
            if ($id === (int)$_SESSION['nguoi_dung']['id']) {
                $_SESSION['nguoi_dung']['ho_ten'] = trim($_POST['ho_ten']);
                $_SESSION['nguoi_dung']['email']  = trim($_POST['email']);
            }
        }

        $nguoi_dung = $this->nguoiDungModel->layTheoId($id);
        $title      = 'Sửa Tài Khoản';
        $view       = 'admin/nguoi_dung/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Xoá người dùng
    public function xoa()
    {
        kiem_tra_admin();

        $id = (int)($_GET['id'] ?? 0);

        // Không được tự xoá chính mình
        if ($id === (int)$_SESSION['nguoi_dung']['id']) {
            dat_thong_bao('warning', 'Không thể tự xoá tài khoản của chính mình!');
        } else {
            $this->nguoiDungModel->xoa($id);
            dat_thong_bao('success', 'Xoá tài khoản thành công!');
        }

        chuyen_huong(BASE_URL . '?action=admin-nguoi-dung');
    }

    // Validate người dùng
    private function validateNguoiDung($du_lieu, $dang_sua = false, $id = null)
    {
        $loi = [];

        if (empty(trim($du_lieu['ho_ten'] ?? ''))) {
            $loi['ho_ten'] = 'Họ tên không được để trống!';
        }

        $email = trim($du_lieu['email'] ?? '');
        if (empty($email)) {
            $loi['email'] = 'Email không được để trống!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $loi['email'] = 'Email không đúng định dạng!';
        } elseif ($this->nguoiDungModel->kiemTraEmail($email, $id)) {
            $loi['email'] = 'Email này đã được sử dụng!';
        }

        // Khi thêm mới: bắt buộc nhập mật khẩu
        // Khi sửa: không bắt buộc (để trống = giữ nguyên)
        if (!$dang_sua) {
            if (empty($du_lieu['mat_khau'])) {
                $loi['mat_khau'] = 'Mật khẩu không được để trống!';
            } elseif (strlen($du_lieu['mat_khau']) < 6) {
                $loi['mat_khau'] = 'Mật khẩu ít nhất 6 ký tự!';
            }
        } elseif (!empty($du_lieu['mat_khau']) && strlen($du_lieu['mat_khau']) < 6) {
            $loi['mat_khau'] = 'Mật khẩu ít nhất 6 ký tự!';
        }

        if (empty($du_lieu['vai_tro'])) {
            $loi['vai_tro'] = 'Vui lòng chọn vai trò!';
        }

        return $loi;
    }
}
