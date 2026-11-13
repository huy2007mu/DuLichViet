<?php

class DanhGiaController
{
    private $danhGiaModel;
    private $donHangModel;

    public function __construct()
    {
        $this->danhGiaModel = new DanhGiaModel();
        $this->donHangModel = new DonHangModel();
    }

    // Gửi đánh giá cho 1 tour đã mua trong 1 đơn đặt tour cụ thể
    public function them()
    {
        kiem_tra_dang_nhap();

        $nguoi_dung_id = $_SESSION['nguoi_dung']['id'];
        $san_pham_id   = (int)($_POST['san_pham_id'] ?? 0);
        $don_hang_id   = (int)($_POST['don_hang_id'] ?? 0);
        $so_sao        = (int)($_POST['so_sao'] ?? 0);
        $noi_dung      = trim($_POST['noi_dung'] ?? '');

        // Chỉ được đánh giá tour đã mua và đơn đặt tour đã hoàn thành
        $don_hang = $this->donHangModel->layChiTietDonHang($don_hang_id, $nguoi_dung_id);

        if (!$don_hang || $don_hang['trang_thai'] !== 'hoan_thanh') {
            dat_thong_bao('danger', 'Bạn chỉ có thể đánh giá tour thuộc đơn đặt tour đã hoàn thành!');
            chuyen_huong(BASE_URL . '?action=don-hang-chi-tiet&id=' . $don_hang_id);
        }

        $co_trong_don = false;
        foreach ($don_hang['san_pham'] as $sp) {
            if ((int)$sp['san_pham_id'] === $san_pham_id) {
                $co_trong_don = true;
                break;
            }
        }

        if (!$co_trong_don) {
            dat_thong_bao('danger', 'Tour không thuộc đơn đặt tour này!');
            chuyen_huong(BASE_URL . '?action=don-hang-chi-tiet&id=' . $don_hang_id);
        }

        if ($this->danhGiaModel->daDanhGia($nguoi_dung_id, $san_pham_id, $don_hang_id)) {
            dat_thong_bao('warning', 'Bạn đã đánh giá tour này rồi!');
            chuyen_huong(BASE_URL . '?action=don-hang-chi-tiet&id=' . $don_hang_id);
        }

        if ($so_sao < 1 || $so_sao > 5) {
            dat_thong_bao('danger', 'Vui lòng chọn số sao đánh giá từ 1 đến 5!');
            chuyen_huong(BASE_URL . '?action=don-hang-chi-tiet&id=' . $don_hang_id);
        }

        $this->danhGiaModel->them([
            'san_pham_id'   => $san_pham_id,
            'nguoi_dung_id' => $nguoi_dung_id,
            'don_hang_id'   => $don_hang_id,
            'so_sao'        => $so_sao,
            'noi_dung'      => $noi_dung,
        ]);

        dat_thong_bao('success', 'Cảm ơn bạn đã đánh giá tour!');
        chuyen_huong(BASE_URL . '?action=don-hang-chi-tiet&id=' . $don_hang_id);
    }
}
