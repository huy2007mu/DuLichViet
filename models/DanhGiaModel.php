<?php

class DanhGiaModel extends BaseModel
{
    protected $bang = 'danh_gia';

    // Lấy đánh giá của 1 tour (hiển thị ở trang chi tiết, chỉ lấy đang hiển thị)
    public function layTheoSanPham($san_pham_id)
    {
        $sql = "SELECT dg.*, nd.ho_ten as ten_khach
                FROM danh_gia dg
                JOIN nguoi_dung nd ON nd.id = dg.nguoi_dung_id
                WHERE dg.san_pham_id = :sp AND dg.trang_thai = 1
                ORDER BY dg.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':sp' => $san_pham_id]);
        return $stmt->fetchAll();
    }

    // Toàn bộ đánh giá kèm tên khách + tên tour - dùng cho Admin
    public function layTatCaKemThongTin()
    {
        $sql = "SELECT dg.*, nd.ho_ten as ten_khach, sp.ten_san_pham
                FROM danh_gia dg
                JOIN nguoi_dung nd ON nd.id = dg.nguoi_dung_id
                JOIN san_pham sp ON sp.id = dg.san_pham_id
                ORDER BY dg.id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Kiểm tra đã đánh giá đơn đặt tour - tour này chưa (tránh đánh giá trùng)
    public function daDanhGia($nguoi_dung_id, $san_pham_id, $don_hang_id)
    {
        $sql = "SELECT id FROM danh_gia
                WHERE nguoi_dung_id=:nd AND san_pham_id=:sp AND don_hang_id=:dh
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nd' => $nguoi_dung_id, ':sp' => $san_pham_id, ':dh' => $don_hang_id]);
        return (bool)$stmt->fetch();
    }

    // Thêm đánh giá mới
    public function them($du_lieu)
    {
        $sql = "INSERT INTO danh_gia (san_pham_id, nguoi_dung_id, don_hang_id, so_sao, noi_dung)
                VALUES (:sp, :nd, :dh, :sao, :noi_dung)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':sp'       => $du_lieu['san_pham_id'],
            ':nd'       => $du_lieu['nguoi_dung_id'],
            ':dh'       => $du_lieu['don_hang_id'],
            ':sao'      => $du_lieu['so_sao'],
            ':noi_dung' => $du_lieu['noi_dung'] ?? '',
        ]);
    }

    // Admin ẩn / hiện đánh giá (kiểm duyệt)
    public function doiTrangThai($id, $trang_thai)
    {
        $sql = "UPDATE danh_gia SET trang_thai = :tt WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':tt' => $trang_thai, ':id' => $id]);
    }
}
