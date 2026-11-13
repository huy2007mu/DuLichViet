<?php

class YeuThichModel extends BaseModel
{
    protected $bang = 'yeu_thich';

    // Lấy toàn bộ danh sách yêu thích của 1 người dùng, kèm thông tin tour
    public function layYeuThich($nguoi_dung_id)
    {
        $sql = "SELECT sp.*, dm.ten_danh_muc, yt.id as yeu_thich_id, yt.ngay_tao as ngay_them_yeu_thich
                FROM yeu_thich yt
                JOIN san_pham sp ON sp.id = yt.san_pham_id
                LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                WHERE yt.nguoi_dung_id = :nd
                ORDER BY yt.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nd' => $nguoi_dung_id]);
        return $stmt->fetchAll();
    }

    // Lấy danh sách ID tour mà 1 người dùng đã yêu thích (dùng để tô đậm icon trái tim)
    public function layDanhSachIdDaYeuThich($nguoi_dung_id)
    {
        $sql = "SELECT san_pham_id FROM yeu_thich WHERE nguoi_dung_id = :nd";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nd' => $nguoi_dung_id]);
        return array_column($stmt->fetchAll(), 'san_pham_id');
    }

    // Kiểm tra 1 tour đã được yêu thích bởi người dùng chưa
    public function daYeuThich($nguoi_dung_id, $san_pham_id)
    {
        $sql = "SELECT id FROM yeu_thich WHERE nguoi_dung_id = :nd AND san_pham_id = :sp";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nd' => $nguoi_dung_id, ':sp' => $san_pham_id]);
        return (bool)$stmt->fetch();
    }

    // Đếm số tour yêu thích của 1 người dùng - hiển thị badge trên header
    public function demSoLuong($nguoi_dung_id)
    {
        $sql = "SELECT COUNT(*) as tong FROM yeu_thich WHERE nguoi_dung_id = :nd";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nd' => $nguoi_dung_id]);
        return (int)$stmt->fetch()['tong'];
    }

    // Thêm tour vào danh sách yêu thích (bỏ qua nếu đã tồn tại)
    public function themYeuThich($nguoi_dung_id, $san_pham_id)
    {
        $sql = "INSERT IGNORE INTO yeu_thich (nguoi_dung_id, san_pham_id) VALUES (:nd, :sp)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nd' => $nguoi_dung_id, ':sp' => $san_pham_id]);
    }

    // Xoá tour khỏi danh sách yêu thích theo san_pham_id (dùng cho nút trái tim)
    public function xoaTheoSanPham($nguoi_dung_id, $san_pham_id)
    {
        $sql = "DELETE FROM yeu_thich WHERE nguoi_dung_id = :nd AND san_pham_id = :sp";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nd' => $nguoi_dung_id, ':sp' => $san_pham_id]);
    }

    // Xoá 1 dòng khỏi danh sách yêu thích theo id dòng (dùng ở trang "Tour yêu thích")
    public function xoaTheoDong($yeu_thich_id, $nguoi_dung_id)
    {
        $sql = "DELETE FROM yeu_thich WHERE id = :id AND nguoi_dung_id = :nd";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $yeu_thich_id, ':nd' => $nguoi_dung_id]);
    }

    // Bật / tắt yêu thích cho 1 tour - trả về true nếu vừa được THÊM, false nếu vừa bị XOÁ
    public function toggle($nguoi_dung_id, $san_pham_id)
    {
        if ($this->daYeuThich($nguoi_dung_id, $san_pham_id)) {
            $this->xoaTheoSanPham($nguoi_dung_id, $san_pham_id);
            return false;
        }

        $this->themYeuThich($nguoi_dung_id, $san_pham_id);
        return true;
    }

    // ===================== DÙNG CHO ADMIN =====================

    // Lấy toàn bộ danh sách yêu thích của mọi khách hàng, kèm thông tin khách + tour
    public function layTatCaKemThongTin()
    {
        $sql = "SELECT yt.*, nd.ho_ten as ten_khach, nd.email as email_khach,
                       sp.ten_san_pham, sp.hinh_anh, sp.gia
                FROM yeu_thich yt
                JOIN nguoi_dung nd ON nd.id = yt.nguoi_dung_id
                JOIN san_pham sp   ON sp.id = yt.san_pham_id
                ORDER BY yt.id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Thống kê top tour được yêu thích nhiều nhất (cho admin xem nhanh xu hướng khách hàng)
    public function thongKeSanPhamDuocYeuThichNhieuNhat($gioi_han = 5)
    {
        $sql = "SELECT sp.id, sp.ten_san_pham, sp.hinh_anh, sp.gia, COUNT(yt.id) as so_luot_yeu_thich
                FROM yeu_thich yt
                JOIN san_pham sp ON sp.id = yt.san_pham_id
                GROUP BY sp.id
                ORDER BY so_luot_yeu_thich DESC
                LIMIT " . (int)$gioi_han;
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
