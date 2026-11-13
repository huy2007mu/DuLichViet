<?php

class GioHangModel extends BaseModel
{
    protected $bang = 'gio_hang_chi_tiet';

    // Lấy toàn bộ giỏ tour của 1 người dùng, kèm thông tin tour
    // Lưu ý: sp.so_luong (số chỗ còn) và gh.so_luong (số lượng khách chọn) TRÙNG TÊN cột,
    // nên phải đặt bí danh riêng - tránh việc PDO ghi đè lẫn nhau khi fetch mảng kết hợp.
    public function layGioHang($nguoi_dung_id)
    {
        $sql = "SELECT sp.*, gh.id as gio_hang_id, gh.so_luong as so_luong_dat
                FROM gio_hang_chi_tiet gh
                JOIN san_pham sp ON sp.id = gh.san_pham_id
                WHERE gh.nguoi_dung_id = :nd
                ORDER BY gh.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nd' => $nguoi_dung_id]);
        return $stmt->fetchAll();
    }

    // Lấy số lượng hiện có trong giỏ của 1 tour (dùng để kiểm tra số chỗ còn trước khi cộng dồn)
    public function laySoLuongDaCo($nguoi_dung_id, $san_pham_id)
    {
        $sql = "SELECT so_luong FROM gio_hang_chi_tiet WHERE nguoi_dung_id = :nd AND san_pham_id = :sp";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nd' => $nguoi_dung_id, ':sp' => $san_pham_id]);
        $row = $stmt->fetch();
        return $row ? (int)$row['so_luong'] : 0;
    }

    // Đếm tổng số tour (số dòng) trong giỏ - hiển thị badge trên header
    public function demSoLuong($nguoi_dung_id)
    {
        $sql = "SELECT COALESCE(SUM(so_luong),0) as tong FROM gio_hang_chi_tiet WHERE nguoi_dung_id = :nd";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nd' => $nguoi_dung_id]);
        return (int)$stmt->fetch()['tong'];
    }

    // Thêm tour vào giỏ (nếu đã có thì cộng dồn số lượng)
    public function themVaoGio($nguoi_dung_id, $san_pham_id, $so_luong = 1)
    {
        $sql = "INSERT INTO gio_hang_chi_tiet (nguoi_dung_id, san_pham_id, so_luong)
                VALUES (:nd, :sp, :sl)
                ON DUPLICATE KEY UPDATE so_luong = so_luong + VALUES(so_luong)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nd' => $nguoi_dung_id, ':sp' => $san_pham_id, ':sl' => $so_luong]);
    }

    // Cập nhật số lượng 1 dòng giỏ tour
    public function capNhatSoLuong($gio_hang_id, $nguoi_dung_id, $so_luong)
    {
        $sql = "UPDATE gio_hang_chi_tiet SET so_luong = :sl WHERE id = :id AND nguoi_dung_id = :nd";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':sl' => $so_luong, ':id' => $gio_hang_id, ':nd' => $nguoi_dung_id]);
    }

    // Xoá 1 dòng khỏi giỏ tour
    public function xoaKhoiGio($gio_hang_id, $nguoi_dung_id)
    {
        $sql = "DELETE FROM gio_hang_chi_tiet WHERE id = :id AND nguoi_dung_id = :nd";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $gio_hang_id, ':nd' => $nguoi_dung_id]);
    }

    // Xoá sạch giỏ tour sau khi đặt tour thành công
    public function xoaSachGio($nguoi_dung_id)
    {
        $sql = "DELETE FROM gio_hang_chi_tiet WHERE nguoi_dung_id = :nd";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nd' => $nguoi_dung_id]);
    }
}
