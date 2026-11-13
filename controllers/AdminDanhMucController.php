<?php

class AdminDanhMucController
{
    private $danhMucModel;

    public function __construct()
    {
        $this->danhMucModel = new DanhMucModel();
    }

    // Danh sách điểm đến
    public function danhSach()
    {
        kiem_tra_admin();

        $danh_sach_danh_muc = $this->danhMucModel->layTatCaKemSoLuong();

        $title = 'Quản Lý Điểm Đến';
        $view  = 'admin/danh_muc/danh_sach';
        require_once PATH_VIEW_ADMIN;
    }

    // Hiển thị form thêm
    public function them()
    {
        kiem_tra_admin();

        $title = 'Thêm Điểm Đến';
        $view  = 'admin/danh_muc/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Lưu điểm đến mới
    public function luu()
    {
        kiem_tra_admin();

        $loi = $this->validateDanhMuc($_POST);

        if (empty($loi)) {
            $this->danhMucModel->them([
                'ten_danh_muc' => trim($_POST['ten_danh_muc']),
                'mo_ta'        => trim($_POST['mo_ta'] ?? ''),
            ]);
            dat_thong_bao('success', 'Thêm điểm đến thành công!');
            chuyen_huong(BASE_URL . '?action=admin-danh-muc');
        }

        $title = 'Thêm Điểm Đến';
        $view  = 'admin/danh_muc/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Hiển thị form sửa
    public function sua()
    {
        kiem_tra_admin();

        $id       = (int)($_GET['id'] ?? 0);
        $danh_muc = $this->danhMucModel->layTheoId($id);

        if (!$danh_muc) {
            dat_thong_bao('danger', 'Không tìm thấy điểm đến!');
            chuyen_huong(BASE_URL . '?action=admin-danh-muc');
        }

        $title = 'Sửa Điểm Đến';
        $view  = 'admin/danh_muc/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Cập nhật điểm đến
    public function capNhat()
    {
        kiem_tra_admin();

        $id  = (int)($_POST['id'] ?? 0);
        $loi = $this->validateDanhMuc($_POST);

        if (empty($loi)) {
            $this->danhMucModel->capNhat($id, [
                'ten_danh_muc' => trim($_POST['ten_danh_muc']),
                'mo_ta'        => trim($_POST['mo_ta'] ?? ''),
            ]);
            dat_thong_bao('success', 'Cập nhật điểm đến thành công!');
            chuyen_huong(BASE_URL . '?action=admin-danh-muc');
        }

        $danh_muc = $this->danhMucModel->layTheoId($id);
        $title    = 'Sửa Điểm Đến';
        $view     = 'admin/danh_muc/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Xoá điểm đến
    public function xoa()
    {
        kiem_tra_admin();

        $id = (int)($_GET['id'] ?? 0);

        // Kiểm tra khoá ngoại: điểm đến có tour không?
        if ($this->danhMucModel->coSanPham($id)) {
            dat_thong_bao('warning', 'Không thể xoá! Điểm đến này còn tour. Hãy xoá tour trước.');
        } else {
            $this->danhMucModel->xoa($id);
            dat_thong_bao('success', 'Xoá điểm đến thành công!');
        }

        chuyen_huong(BASE_URL . '?action=admin-danh-muc');
    }

    // Validate dữ liệu điểm đến
    private function validateDanhMuc($du_lieu)
    {
        $loi = [];

        if (empty(trim($du_lieu['ten_danh_muc'] ?? ''))) {
            $loi['ten_danh_muc'] = 'Tên điểm đến không được để trống!';
        } elseif (mb_strlen($du_lieu['ten_danh_muc']) > 100) {
            $loi['ten_danh_muc'] = 'Tên điểm đến không quá 100 ký tự!';
        }

        return $loi;
    }
}
