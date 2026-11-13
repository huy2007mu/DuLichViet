<?php

class SanPhamController
{
    private $sanPhamModel;
    private $danhMucModel;
    private $danhGiaModel;

    public function __construct()
    {
        $this->sanPhamModel = new SanPhamModel();
        $this->danhMucModel = new DanhMucModel();
        $this->danhGiaModel = new DanhGiaModel();
    }

    // Danh sách tour - kèm tìm kiếm / lọc / sắp xếp
    public function danhSach()
    {
        $bo_loc = [
            'tu_khoa'     => trim($_GET['tu_khoa'] ?? ''),
            'danh_muc_id' => $_GET['danh_muc_id'] ?? '',
            'gia_tu'      => $_GET['gia_tu'] ?? '',
            'gia_den'     => $_GET['gia_den'] ?? '',
            'sap_xep'     => $_GET['sap_xep'] ?? '',
        ];

        $danh_sach_san_pham = $this->sanPhamModel->timKiemLoc($bo_loc);
        $danh_sach_danh_muc = $this->danhMucModel->layTatCaKemSoLuong();

        $title = !empty($bo_loc['tu_khoa'])
            ? "Kết quả tìm kiếm cho \"{$bo_loc['tu_khoa']}\""
            : 'Tất Cả Tour';
        $view = 'client/san_pham_danh_sach';

        require_once PATH_VIEW_CLIENT;
    }

    // Chi tiết tour
    public function chiTiet()
    {
        $id       = (int)($_GET['id'] ?? 0);
        $san_pham = $this->sanPhamModel->layTheoIdKemDanhGia($id);

        if (!$san_pham || (int)$san_pham['trang_thai'] === 0) {
            dat_thong_bao('danger', 'Tour không tồn tại hoặc đã ngừng kinh doanh!');
            chuyen_huong(BASE_URL . '?action=san-pham');
        }

        $this->sanPhamModel->tangLuotXem($id);

        $danh_sach_danh_gia = $this->danhGiaModel->layTheoSanPham($id);
        $san_pham_lien_quan = $this->sanPhamModel->sanPhamLienQuan($san_pham['danh_muc_id'], $id, 4);

        // Ưu đãi cá nhân hoá: xem tour ở điểm đến nào → gửi mã giảm giá của điểm đến đó (mỗi điểm đến hiện 1 lần / phiên)
        $diem_den = diem_den_tu_ten($san_pham['ten_san_pham']);
        $_SESSION['da_goi_uu_dai'] = $_SESSION['da_goi_uu_dai'] ?? [];
        if ($diem_den !== '' && !in_array($diem_den, $_SESSION['da_goi_uu_dai'], true)) {
            try {
                $v = (new VoucherModel())->layHoacTaoVoucherDiemDen($diem_den);
                if ($v) {
                    $_SESSION['uu_dai_ca_nhan'] = ['diem_den' => $diem_den, 'ma' => $v['ma_voucher'], 'gia_tri' => (int)$v['gia_tri']];
                    $_SESSION['da_goi_uu_dai'][] = $diem_den;
                }
            } catch (Throwable $e) { /* không để lỗi ưu đãi làm hỏng trang tour */ }
        }

        $title = $san_pham['ten_san_pham'];
        $meta_mo_ta = mb_substr(preg_replace('/\s+/u', ' ', strip_tags($san_pham['mo_ta'] ?? '')), 0, 155);
        $meta_anh = anh_san_pham($san_pham['hinh_anh'], $san_pham['ten_san_pham']);
        $json_ld = [
            '@context' => 'https://schema.org', '@type' => 'Product',
            'name' => $san_pham['ten_san_pham'], 'image' => $meta_anh, 'description' => $meta_mo_ta,
            'offers' => ['@type' => 'Offer', 'priceCurrency' => 'VND', 'price' => (int)$san_pham['gia'],
                         'availability' => $san_pham['so_luong'] > 0 ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut'],
        ];
        $view  = 'client/san_pham_chi_tiet';

        require_once PATH_VIEW_CLIENT;
    }
}
