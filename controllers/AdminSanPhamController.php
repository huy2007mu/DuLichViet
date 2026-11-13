<?php

class AdminSanPhamController
{
    private $sanPhamModel;
    private $danhMucModel;

    public function __construct()
    {
        $this->sanPhamModel = new SanPhamModel();
        $this->danhMucModel = new DanhMucModel();
    }

    // Danh sách tour
    public function danhSach()
    {
        kiem_tra_admin();

        $danh_sach_san_pham = $this->sanPhamModel->layTatCaKemDanhMuc();

        $title = 'Quản Lý Tour';
        $view  = 'admin/san_pham/danh_sach';
        require_once PATH_VIEW_ADMIN;
    }

    // Hiển thị form thêm
    public function them()
    {
        kiem_tra_admin();

        $danh_sach_danh_muc = $this->danhMucModel->layTatCa();

        $title = 'Thêm Tour';
        $view  = 'admin/san_pham/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Lưu tour mới
    public function luu()
    {
        kiem_tra_admin();

        $loi  = $this->validateSanPham($_POST, $_FILES);
        $hinh_anh = null;

        if (empty($loi)) {
            // Xử lý upload ảnh
            if (!empty($_FILES['hinh_anh']['name'])) {
                try {
                    $hinh_anh = upload_anh('san_pham', $_FILES['hinh_anh']);
                } catch (Exception $e) {
                    $loi['hinh_anh'] = $e->getMessage();
                }
            }
        }

        if (empty($loi)) {
            $this->sanPhamModel->them([
                'danh_muc_id'  => (int)$_POST['danh_muc_id'],
                'ten_san_pham' => trim($_POST['ten_san_pham']),
                'gia'          => (float)$_POST['gia'],
                'so_luong'     => (int)$_POST['so_luong'],
                'thoi_gian'      => trim($_POST['thoi_gian'] ?? ''),
                'diem_khoi_hanh' => trim($_POST['diem_khoi_hanh'] ?? ''),
                'phuong_tien'    => trim($_POST['phuong_tien'] ?? ''),
                'mo_ta'        => trim($_POST['mo_ta'] ?? ''),
                'hinh_anh'     => $hinh_anh,
                'trang_thai'   => isset($_POST['trang_thai']) ? 1 : 0,
            ]);
            dat_thong_bao('success', 'Thêm tour thành công!');
            chuyen_huong(BASE_URL . '?action=admin-san-pham');
        }

        $danh_sach_danh_muc = $this->danhMucModel->layTatCa();
        $title = 'Thêm Tour';
        $view  = 'admin/san_pham/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Hiển thị form sửa
    public function sua()
    {
        kiem_tra_admin();

        $id       = (int)($_GET['id'] ?? 0);
        $san_pham = $this->sanPhamModel->layTheoIdKemDanhMuc($id);

        if (!$san_pham) {
            dat_thong_bao('danger', 'Không tìm thấy tour!');
            chuyen_huong(BASE_URL . '?action=admin-san-pham');
        }

        $danh_sach_danh_muc = $this->danhMucModel->layTatCa();
        $title = 'Sửa Tour';
        $view  = 'admin/san_pham/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Cập nhật tour
    public function capNhat()
    {
        kiem_tra_admin();

        $id       = (int)$_POST['id'];
        $san_pham = $this->sanPhamModel->layTheoId($id);
        $loi      = $this->validateSanPham($_POST, $_FILES);
        $hinh_anh = null;

        if (empty($loi) && !empty($_FILES['hinh_anh']['name'])) {
            try {
                $hinh_anh = upload_anh('san_pham', $_FILES['hinh_anh']);
                // Xoá ảnh cũ nếu có
                if (!empty($san_pham['hinh_anh'])) {
                    xoa_anh($san_pham['hinh_anh']);
                }
            } catch (Exception $e) {
                $loi['hinh_anh'] = $e->getMessage();
            }
        }

        if (empty($loi)) {
            $this->sanPhamModel->capNhat($id, [
                'danh_muc_id'  => (int)$_POST['danh_muc_id'],
                'ten_san_pham' => trim($_POST['ten_san_pham']),
                'gia'          => (float)$_POST['gia'],
                'so_luong'     => (int)$_POST['so_luong'],
                'thoi_gian'      => trim($_POST['thoi_gian'] ?? ''),
                'diem_khoi_hanh' => trim($_POST['diem_khoi_hanh'] ?? ''),
                'phuong_tien'    => trim($_POST['phuong_tien'] ?? ''),
                'mo_ta'        => trim($_POST['mo_ta'] ?? ''),
                'hinh_anh'     => $hinh_anh,
                'trang_thai'   => isset($_POST['trang_thai']) ? 1 : 0,
            ]);
            dat_thong_bao('success', 'Cập nhật tour thành công!');
            chuyen_huong(BASE_URL . '?action=admin-san-pham');
        }

        $san_pham           = $this->sanPhamModel->layTheoIdKemDanhMuc($id);
        $danh_sach_danh_muc = $this->danhMucModel->layTatCa();
        $title = 'Sửa Tour';
        $view  = 'admin/san_pham/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Xoá tour
    public function xoa()
    {
        kiem_tra_admin();

        $id       = (int)($_GET['id'] ?? 0);
        $san_pham = $this->sanPhamModel->layTheoId($id);

        if ($san_pham) {
            // Xoá ảnh nếu có
            if (!empty($san_pham['hinh_anh'])) {
                xoa_anh($san_pham['hinh_anh']);
            }
            $this->sanPhamModel->xoa($id);
            dat_thong_bao('success', 'Xoá tour thành công!');
        } else {
            dat_thong_bao('danger', 'Không tìm thấy tour!');
        }

        chuyen_huong(BASE_URL . '?action=admin-san-pham');
    }

    // Validate dữ liệu tour
    private function validateSanPham($du_lieu, $files = [])
    {
        $loi = [];

        if (empty(trim($du_lieu['ten_san_pham'] ?? ''))) {
            $loi['ten_san_pham'] = 'Tên tour không được để trống!';
        } elseif (mb_strlen($du_lieu['ten_san_pham']) > 150) {
            $loi['ten_san_pham'] = 'Tên tour không quá 150 ký tự!';
        }

        if (empty($du_lieu['danh_muc_id'])) {
            $loi['danh_muc_id'] = 'Vui lòng chọn điểm đến!';
        }

        if (!isset($du_lieu['gia']) || $du_lieu['gia'] === '') {
            $loi['gia'] = 'Giá không được để trống!';
        } elseif (!is_numeric($du_lieu['gia']) || $du_lieu['gia'] < 0) {
            $loi['gia'] = 'Giá phải là số và không được âm!';
        }

        if (!isset($du_lieu['so_luong']) || $du_lieu['so_luong'] === '') {
            $loi['so_luong'] = 'Số chỗ không được để trống!';
        } elseif (!ctype_digit((string)$du_lieu['so_luong']) || $du_lieu['so_luong'] < 0) {
            $loi['so_luong'] = 'Số chỗ phải là số nguyên không âm!';
        }

        return $loi;
    }
}
