<?php

class DonHangModel extends BaseModel
{
    protected $bang = 'don_hang';

    // Tạo đơn đặt tour mới + chi tiết đơn đặt tour (transaction)
    public function taoDonHang($du_lieu, $danh_sach_san_pham)
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO don_hang (ma_don_hang, nguoi_dung_id, ho_ten_nhan, so_dien_thoai,
                                           dia_chi_giao, ngay_khoi_hanh, ghi_chu, voucher_id, tam_tinh, tien_giam, tong_tien)
                    VALUES (:ma, :nd, :ho_ten, :sdt, :dia_chi, :ngay_kh, :ghi_chu, :voucher_id, :tam_tinh, :tien_giam, :tong_tien)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':ma'         => $du_lieu['ma_don_hang'],
                ':nd'         => $du_lieu['nguoi_dung_id'],
                ':ho_ten'     => $du_lieu['ho_ten_nhan'],
                ':sdt'        => $du_lieu['so_dien_thoai'],
                ':dia_chi'    => $du_lieu['dia_chi_giao'],
                ':ngay_kh'    => $du_lieu['ngay_khoi_hanh'] ?? null,
                ':ghi_chu'    => $du_lieu['ghi_chu'] ?? null,
                ':voucher_id' => $du_lieu['voucher_id'] ?? null,
                ':tam_tinh'   => $du_lieu['tam_tinh'],
                ':tien_giam'  => $du_lieu['tien_giam'],
                ':tong_tien'  => $du_lieu['tong_tien'],
            ]);
            $don_hang_id = $this->pdo->lastInsertId();

            $sql_ct = "INSERT INTO don_hang_chi_tiet (don_hang_id, san_pham_id, ten_san_pham, hinh_anh, gia, so_luong)
                       VALUES (:dh, :sp, :ten, :anh, :gia, :sl)";
            $stmt_ct = $this->pdo->prepare($sql_ct);
            foreach ($danh_sach_san_pham as $sp) {
                $stmt_ct->execute([
                    ':dh'  => $don_hang_id,
                    ':sp'  => $sp['id'],
                    ':ten' => $sp['ten_san_pham'],
                    ':anh' => $sp['hinh_anh'],
                    ':gia' => $sp['gia'],
                    ':sl'  => $sp['so_luong_mua'],
                ]);

                // Giữ chỗ ngay trong transaction để tránh hai khách đặt vượt tồn kho.
                $tru_cho = $this->pdo->prepare("UPDATE san_pham SET so_luong = so_luong - :sl WHERE id = :id AND trang_thai = 1 AND so_luong >= :sl");
                $tru_cho->execute([':sl' => (int)$sp['so_luong_mua'], ':id' => (int)$sp['id']]);
                if ($tru_cho->rowCount() !== 1) {
                    throw new Exception('Tour "' . $sp['ten_san_pham'] . '" vừa hết chỗ hoặc không còn đủ chỗ. Vui lòng quay lại giỏ tour.');
                }
            }

            // Ghi nhận lượt sử dụng voucher cùng transaction với đơn hàng.
            if (!empty($du_lieu['voucher_id'])) {
                $dung_voucher = $this->pdo->prepare("UPDATE voucher SET da_dung = da_dung + 1 WHERE id = :id AND trang_thai = 1 AND da_dung < so_luong AND (ngay_bat_dau IS NULL OR ngay_bat_dau <= CURDATE()) AND (ngay_ket_thuc IS NULL OR ngay_ket_thuc >= CURDATE())");
                $dung_voucher->execute([':id' => (int)$du_lieu['voucher_id']]);
                if ($dung_voucher->rowCount() !== 1) {
                    throw new Exception('Mã giảm giá vừa hết lượt hoặc hết hạn. Vui lòng áp dụng mã khác.');
                }
            }

            $this->pdo->commit();
            return $don_hang_id;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception('Không thể tạo đơn đặt tour: ' . $e->getMessage());
        }
    }

    // Danh sách đơn đặt tour của 1 khách hàng (theo dõi đơn đặt tour)
    public function layDonHangCuaKhach($nguoi_dung_id)
    {
        $sql = "SELECT * FROM don_hang WHERE nguoi_dung_id = :nd ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nd' => $nguoi_dung_id]);
        return $stmt->fetchAll();
    }

    // Toàn bộ đơn đặt tour kèm tên khách - dùng cho Admin
    public function layTatCaKemKhach()
    {
        $sql = "SELECT dh.*, nd.ho_ten as ten_khach, nd.email
                FROM don_hang dh
                JOIN nguoi_dung nd ON nd.id = dh.nguoi_dung_id
                ORDER BY dh.id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Chi tiết 1 đơn đặt tour (kèm kiểm tra chủ sở hữu nếu truyền nguoi_dung_id)
    public function layChiTietDonHang($id, $nguoi_dung_id = null)
    {
        $sql = "SELECT dh.*, nd.ho_ten as ten_khach, nd.email, v.ma_voucher
                FROM don_hang dh
                JOIN nguoi_dung nd ON nd.id = dh.nguoi_dung_id
                LEFT JOIN voucher v ON v.id = dh.voucher_id
                WHERE dh.id = :id";
        $tham_so = [':id' => $id];
        if ($nguoi_dung_id) {
            $sql .= " AND dh.nguoi_dung_id = :nd";
            $tham_so[':nd'] = $nguoi_dung_id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($tham_so);
        $don_hang = $stmt->fetch();

        if ($don_hang) {
            $sql2 = "SELECT * FROM don_hang_chi_tiet WHERE don_hang_id = :id";
            $stmt2 = $this->pdo->prepare($sql2);
            $stmt2->execute([':id' => $id]);
            $don_hang['san_pham'] = $stmt2->fetchAll();
        }
        return $don_hang;
    }

    // Cập nhật trạng thái đơn đặt tour (Admin)
    public function capNhatTrangThai($id, $trang_thai)
    {
        $sql = "UPDATE don_hang SET trang_thai = :tt WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':tt' => $trang_thai, ':id' => $id]);
    }

    // Khách tự huỷ đơn (chỉ khi đang chờ xác nhận)
    public function huyDonHang($id, $nguoi_dung_id)
    {
        $sql = "UPDATE don_hang SET trang_thai = 'da_huy'
                WHERE id = :id AND nguoi_dung_id = :nd AND trang_thai = 'cho_xac_nhan'";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id, ':nd' => $nguoi_dung_id]);
    }

    // Tổng doanh thu (đơn hoàn thành) - Dashboard admin
    public function tongDoanhThu()
    {
        $sql  = "SELECT COALESCE(SUM(tong_tien),0) as tong FROM don_hang WHERE trang_thai = 'hoan_thanh'";
        $stmt = $this->pdo->query($sql);
        return (float)$stmt->fetch()['tong'];
    }

    // Đếm đơn đặt tour theo trạng thái
    public function demTheoTrangThai($trang_thai)
    {
        $sql  = "SELECT COUNT(*) as tong FROM don_hang WHERE trang_thai = :tt";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':tt' => $trang_thai]);
        return (int)$stmt->fetch()['tong'];
    }

    // Kiểm tra khách đã mua tour này chưa (đơn hoàn thành) - điều kiện để đánh giá
    public function daMuaSanPham($nguoi_dung_id, $san_pham_id)
    {
        $sql = "SELECT dh.id
                FROM don_hang dh
                JOIN don_hang_chi_tiet ct ON ct.don_hang_id = dh.id
                WHERE dh.nguoi_dung_id = :nd AND ct.san_pham_id = :sp AND dh.trang_thai = 'hoan_thanh'
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nd' => $nguoi_dung_id, ':sp' => $san_pham_id]);
        return $stmt->fetch();
    }
}
