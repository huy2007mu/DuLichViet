<?php

class AdminVoucherController
{
    private $voucherModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
    }

    // Danh sách voucher
    public function danhSach()
    {
        kiem_tra_admin();

        $danh_sach_voucher = $this->voucherModel->layTatCa();

        $title = 'Quản Lý Voucher Giảm Giá';
        $view  = 'admin/voucher/danh_sach';
        require_once PATH_VIEW_ADMIN;
    }

    // Hiển thị form thêm
    public function them()
    {
        kiem_tra_admin();

        $title = 'Thêm Voucher';
        $view  = 'admin/voucher/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Lưu voucher mới
    public function luu()
    {
        kiem_tra_admin();

        $loi = $this->validateVoucher($_POST);

        if (empty($loi)) {
            $this->voucherModel->them($_POST);
            dat_thong_bao('success', 'Thêm voucher thành công!');
            chuyen_huong(BASE_URL . '?action=admin-voucher');
        }

        $title = 'Thêm Voucher';
        $view  = 'admin/voucher/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Hiển thị form sửa
    public function sua()
    {
        kiem_tra_admin();

        $id      = (int)($_GET['id'] ?? 0);
        $voucher = $this->voucherModel->layTheoId($id);

        if (!$voucher) {
            dat_thong_bao('danger', 'Không tìm thấy voucher!');
            chuyen_huong(BASE_URL . '?action=admin-voucher');
        }

        $title = 'Sửa Voucher';
        $view  = 'admin/voucher/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Cập nhật voucher
    public function capNhat()
    {
        kiem_tra_admin();

        $id  = (int)($_POST['id'] ?? 0);
        $loi = $this->validateVoucher($_POST, $id);

        if (empty($loi)) {
            $this->voucherModel->capNhat($id, $_POST);
            dat_thong_bao('success', 'Cập nhật voucher thành công!');
            chuyen_huong(BASE_URL . '?action=admin-voucher');
        }

        $voucher = $this->voucherModel->layTheoId($id);
        $title   = 'Sửa Voucher';
        $view    = 'admin/voucher/them_sua';
        require_once PATH_VIEW_ADMIN;
    }

    // Xoá voucher
    public function xoa()
    {
        kiem_tra_admin();

        $id = (int)($_GET['id'] ?? 0);
        $this->voucherModel->xoa($id);
        dat_thong_bao('success', 'Xoá voucher thành công!');

        chuyen_huong(BASE_URL . '?action=admin-voucher');
    }

    // Validate dữ liệu voucher
    private function validateVoucher($du_lieu, $tru_id = null)
    {
        $loi = [];

        $ma = trim($du_lieu['ma_voucher'] ?? '');
        if (empty($ma)) {
            $loi['ma_voucher'] = 'Vui lòng nhập mã voucher!';
        } elseif ($this->voucherModel->kiemTraMa($ma, $tru_id)) {
            $loi['ma_voucher'] = 'Mã voucher này đã tồn tại!';
        }

        if (!isset($du_lieu['gia_tri']) || $du_lieu['gia_tri'] === '' || $du_lieu['gia_tri'] < 0) {
            $loi['gia_tri'] = 'Giá trị giảm không hợp lệ!';
        } elseif ($du_lieu['loai_giam'] === 'phan_tram' && $du_lieu['gia_tri'] > 100) {
            $loi['gia_tri'] = 'Phần trăm giảm không được vượt quá 100!';
        }

        if (!isset($du_lieu['so_luong']) || $du_lieu['so_luong'] === '' || $du_lieu['so_luong'] < 0) {
            $loi['so_luong'] = 'Số lượng không hợp lệ!';
        }

        if (!empty($du_lieu['ngay_bat_dau']) && !empty($du_lieu['ngay_ket_thuc'])
            && $du_lieu['ngay_bat_dau'] > $du_lieu['ngay_ket_thuc']) {
            $loi['ngay_ket_thuc'] = 'Ngày kết thúc phải sau ngày bắt đầu!';
        }

        return $loi;
    }
}
