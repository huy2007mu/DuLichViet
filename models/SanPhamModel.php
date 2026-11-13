<?php

class SanPhamModel extends BaseModel
{
    protected $bang = 'san_pham';

    // Lấy tất cả tour kèm tên điểm đến (JOIN) - dùng cho Admin
    public function layTatCaKemDanhMuc()
    {
        $sql = "SELECT sp.*, dm.ten_danh_muc
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                ORDER BY sp.id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Lấy 1 tour kèm tên điểm đến
    public function layTheoIdKemDanhMuc($id)
    {
        $sql = "SELECT sp.*, dm.ten_danh_muc
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                WHERE sp.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Lấy tour kèm điểm đánh giá trung bình + số lượt đánh giá
    public function layTheoIdKemDanhGia($id)
    {
        $sql = "SELECT sp.*, dm.ten_danh_muc,
                       COALESCE(AVG(dg.so_sao), 0) as diem_trung_binh,
                       COUNT(dg.id) as so_luot_danh_gia
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                LEFT JOIN danh_gia dg ON dg.san_pham_id = sp.id AND dg.trang_thai = 1
                WHERE sp.id = :id
                GROUP BY sp.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Danh sách tour đang bán cho khách (kèm lọc / tìm kiếm / sắp xếp / phân trang)
    public function timKiemLoc($bo_loc = [])
    {
        $dieu_kien = ['sp.trang_thai = 1'];
        $tham_so   = [];

        if (!empty($bo_loc['tu_khoa'])) {
            $dieu_kien[] = '(sp.ten_san_pham LIKE :tu_khoa OR sp.mo_ta LIKE :tu_khoa)';
            $tham_so[':tu_khoa'] = '%' . $bo_loc['tu_khoa'] . '%';
        }
        if (!empty($bo_loc['danh_muc_id'])) {
            $dieu_kien[] = 'sp.danh_muc_id = :danh_muc_id';
            $tham_so[':danh_muc_id'] = $bo_loc['danh_muc_id'];
        }
        if (isset($bo_loc['gia_tu']) && $bo_loc['gia_tu'] !== '') {
            $dieu_kien[] = 'sp.gia >= :gia_tu';
            $tham_so[':gia_tu'] = $bo_loc['gia_tu'];
        }
        if (isset($bo_loc['gia_den']) && $bo_loc['gia_den'] !== '') {
            $dieu_kien[] = 'sp.gia <= :gia_den';
            $tham_so[':gia_den'] = $bo_loc['gia_den'];
        }

        $sap_xep = match ($bo_loc['sap_xep'] ?? '') {
            'gia_tang'  => 'sp.gia ASC',
            'gia_giam'  => 'sp.gia DESC',
            'ban_chay'  => 'sp.luot_xem DESC',
            default     => 'sp.id DESC',
        };

        $where = implode(' AND ', $dieu_kien);
        $sql = "SELECT sp.*, dm.ten_danh_muc
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                WHERE {$where}
                ORDER BY {$sap_xep}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($tham_so);
        return $stmt->fetchAll();
    }

    // Tour mới nhất (trang chủ)
    public function sanPhamMoiNhat($gioi_han = 8)
    {
        $sql = "SELECT sp.*, dm.ten_danh_muc
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                WHERE sp.trang_thai = 1
                ORDER BY sp.ngay_tao DESC
                LIMIT " . (int)$gioi_han;
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Tour liên quan (cùng điểm đến)
    public function sanPhamLienQuan($danh_muc_id, $tru_id, $gioi_han = 4)
    {
        $sql = "SELECT sp.*, dm.ten_danh_muc
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.id
                WHERE sp.danh_muc_id = :danh_muc_id AND sp.id != :id AND sp.trang_thai = 1
                ORDER BY sp.id DESC
                LIMIT " . (int)$gioi_han;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':danh_muc_id' => $danh_muc_id, ':id' => $tru_id]);
        return $stmt->fetchAll();
    }

    // Tăng lượt xem
    public function tangLuotXem($id)
    {
        $sql = "UPDATE san_pham SET luot_xem = luot_xem + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Trừ số lượng số chỗ còn khi đặt tour
    public function truSoLuong($id, $so_luong)
    {
        $sql = "UPDATE san_pham SET so_luong = so_luong - :sl WHERE id = :id AND so_luong >= :sl2";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':sl' => $so_luong, ':sl2' => $so_luong, ':id' => $id]);
    }

    // Thêm tour mới
    public function them($du_lieu)
    {
        $sql = "INSERT INTO san_pham (danh_muc_id, ten_san_pham, gia, so_luong, thoi_gian, diem_khoi_hanh, phuong_tien, mo_ta, hinh_anh, trang_thai)
                VALUES (:danh_muc_id, :ten_san_pham, :gia, :so_luong, :thoi_gian, :diem_khoi_hanh, :phuong_tien, :mo_ta, :hinh_anh, :trang_thai)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':danh_muc_id'    => $du_lieu['danh_muc_id'],
            ':ten_san_pham'   => $du_lieu['ten_san_pham'],
            ':gia'            => $du_lieu['gia'],
            ':so_luong'       => $du_lieu['so_luong'],
            ':thoi_gian'      => $du_lieu['thoi_gian'] ?? null,
            ':diem_khoi_hanh' => $du_lieu['diem_khoi_hanh'] ?? null,
            ':phuong_tien'    => $du_lieu['phuong_tien'] ?? null,
            ':mo_ta'          => $du_lieu['mo_ta'],
            ':hinh_anh'       => $du_lieu['hinh_anh'] ?? null,
            ':trang_thai'     => $du_lieu['trang_thai'] ?? 1,
        ]);
    }

    // Cập nhật tour (có hoặc không có ảnh mới)
    public function capNhat($id, $du_lieu)
    {
        $cot_anh = !empty($du_lieu['hinh_anh']) ? ', hinh_anh=:hinh_anh' : '';
        $sql = "UPDATE san_pham
                SET danh_muc_id=:danh_muc_id, ten_san_pham=:ten_san_pham, gia=:gia, so_luong=:so_luong,
                    thoi_gian=:thoi_gian, diem_khoi_hanh=:diem_khoi_hanh, phuong_tien=:phuong_tien,
                    mo_ta=:mo_ta, trang_thai=:trang_thai {$cot_anh}
                WHERE id=:id";
        $tham_so = [
            ':danh_muc_id'    => $du_lieu['danh_muc_id'],
            ':ten_san_pham'   => $du_lieu['ten_san_pham'],
            ':gia'            => $du_lieu['gia'],
            ':so_luong'       => $du_lieu['so_luong'],
            ':thoi_gian'      => $du_lieu['thoi_gian'] ?? null,
            ':diem_khoi_hanh' => $du_lieu['diem_khoi_hanh'] ?? null,
            ':phuong_tien'    => $du_lieu['phuong_tien'] ?? null,
            ':mo_ta'          => $du_lieu['mo_ta'],
            ':trang_thai'     => $du_lieu['trang_thai'] ?? 1,
            ':id'             => $id,
        ];
        if ($cot_anh) {
            $tham_so[':hinh_anh'] = $du_lieu['hinh_anh'];
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($tham_so);
    }
}
