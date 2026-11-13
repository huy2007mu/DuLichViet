<?php

class DanhMucModel extends BaseModel
{
    protected $bang = 'danh_muc';

    // Thêm điểm đến mới
    public function them($du_lieu)
    {
        $sql = "INSERT INTO danh_muc (ten_danh_muc, mo_ta)
                VALUES (:ten_danh_muc, :mo_ta)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':ten_danh_muc' => $du_lieu['ten_danh_muc'],
            ':mo_ta'        => $du_lieu['mo_ta'],
        ]);
    }

    // Cập nhật điểm đến
    public function capNhat($id, $du_lieu)
    {
        $sql = "UPDATE danh_muc
                SET ten_danh_muc = :ten_danh_muc, mo_ta = :mo_ta
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':ten_danh_muc' => $du_lieu['ten_danh_muc'],
            ':mo_ta'        => $du_lieu['mo_ta'],
            ':id'           => $id,
        ]);
    }

    // Kiểm tra điểm đến có tour không (trước khi xoá)
    public function coSanPham($id)
    {
        $sql  = "SELECT COUNT(*) as tong FROM san_pham WHERE danh_muc_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch()['tong'] > 0;
    }

    // Lấy điểm đến kèm số lượng tour (hiển thị ngoài trang chủ)
    public function layTatCaKemSoLuong()
    {
        $sql = "SELECT dm.*, COUNT(sp.id) as so_san_pham
                FROM danh_muc dm
                LEFT JOIN san_pham sp ON sp.danh_muc_id = dm.id AND sp.trang_thai = 1
                GROUP BY dm.id
                ORDER BY dm.ten_danh_muc ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
