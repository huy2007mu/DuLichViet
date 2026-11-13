<?php

class VoucherModel extends BaseModel
{
    protected $bang = 'voucher';

    // Thêm voucher mới
    public function them($du_lieu)
    {
        $sql = "INSERT INTO voucher (ma_voucher, loai_giam, gia_tri, giam_toi_da, don_toi_thieu,
                                      so_luong, ngay_bat_dau, ngay_ket_thuc, trang_thai)
                VALUES (:ma_voucher, :loai_giam, :gia_tri, :giam_toi_da, :don_toi_thieu,
                        :so_luong, :ngay_bat_dau, :ngay_ket_thuc, :trang_thai)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':ma_voucher'    => strtoupper($du_lieu['ma_voucher']),
            ':loai_giam'     => $du_lieu['loai_giam'],
            ':gia_tri'       => $du_lieu['gia_tri'],
            ':giam_toi_da'   => $du_lieu['giam_toi_da'] ?: null,
            ':don_toi_thieu' => $du_lieu['don_toi_thieu'] ?: 0,
            ':so_luong'      => $du_lieu['so_luong'],
            ':ngay_bat_dau'  => $du_lieu['ngay_bat_dau'] ?: null,
            ':ngay_ket_thuc' => $du_lieu['ngay_ket_thuc'] ?: null,
            ':trang_thai'    => $du_lieu['trang_thai'] ?? 1,
        ]);
    }

    // Cập nhật voucher
    public function capNhat($id, $du_lieu)
    {
        $sql = "UPDATE voucher
                SET ma_voucher=:ma_voucher, loai_giam=:loai_giam, gia_tri=:gia_tri,
                    giam_toi_da=:giam_toi_da, don_toi_thieu=:don_toi_thieu, so_luong=:so_luong,
                    ngay_bat_dau=:ngay_bat_dau, ngay_ket_thuc=:ngay_ket_thuc, trang_thai=:trang_thai
                WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':ma_voucher'    => strtoupper($du_lieu['ma_voucher']),
            ':loai_giam'     => $du_lieu['loai_giam'],
            ':gia_tri'       => $du_lieu['gia_tri'],
            ':giam_toi_da'   => $du_lieu['giam_toi_da'] ?: null,
            ':don_toi_thieu' => $du_lieu['don_toi_thieu'] ?: 0,
            ':so_luong'      => $du_lieu['so_luong'],
            ':ngay_bat_dau'  => $du_lieu['ngay_bat_dau'] ?: null,
            ':ngay_ket_thuc' => $du_lieu['ngay_ket_thuc'] ?: null,
            ':trang_thai'    => $du_lieu['trang_thai'] ?? 1,
            ':id'            => $id,
        ]);
    }

    // Kiểm tra mã trùng
    public function kiemTraMa($ma, $tru_id = null)
    {
        if ($tru_id) {
            $sql  = "SELECT COUNT(*) as tong FROM voucher WHERE ma_voucher=:ma AND id != :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':ma' => strtoupper($ma), ':id' => $tru_id]);
        } else {
            $sql  = "SELECT COUNT(*) as tong FROM voucher WHERE ma_voucher=:ma";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':ma' => strtoupper($ma)]);
        }
        return $stmt->fetch()['tong'] > 0;
    }

    // Lấy voucher hợp lệ theo mã (dùng khi khách áp dụng lúc thanh toán)
    public function layVoucherHopLe($ma, $tam_tinh, $nguoi_dung_id = null)
    {
        $sql = "SELECT * FROM voucher
                WHERE ma_voucher = :ma
                  AND trang_thai = 1
                  AND (ngay_bat_dau IS NULL OR ngay_bat_dau <= CURDATE())
                  AND (ngay_ket_thuc IS NULL OR ngay_ket_thuc >= CURDATE())
                  AND da_dung < so_luong
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ma' => strtoupper(trim($ma))]);
        $voucher = $stmt->fetch();

        if (!$voucher) {
            throw new Exception('Mã giảm giá không tồn tại hoặc đã hết hạn / hết lượt dùng!');
        }
        // Voucher quà tặng mini-game (QUA-<id người dùng>-XXXXX) chỉ chủ tài khoản được dùng
        if (preg_match('/^(?:QUA|CAY)-(\d+)-/', $voucher['ma_voucher'], $m) && (int)$m[1] !== (int)$nguoi_dung_id) {
            throw new Exception('Mã quà tặng này thuộc về tài khoản khác!');
        }
        if ($tam_tinh < $voucher['don_toi_thieu']) {
            throw new Exception('Đơn đặt tour chưa đạt giá trị tối thiểu ' . dinh_dang_tien($voucher['don_toi_thieu']) . ' để dùng mã này!');
        }
        return $voucher;
    }

    // Tính số tiền được giảm
    public function tinhTienGiam($voucher, $tam_tinh)
    {
        if ($voucher['loai_giam'] === 'phan_tram') {
            $tien_giam = $tam_tinh * ((float)$voucher['gia_tri'] / 100);
            if (!empty($voucher['giam_toi_da']) && $tien_giam > $voucher['giam_toi_da']) {
                $tien_giam = $voucher['giam_toi_da'];
            }
        } else {
            $tien_giam = (float)$voucher['gia_tri'];
        }
        return min($tien_giam, $tam_tinh);
    }

    // Tăng số lượt đã dùng
    public function tangDaDung($id)
    {
        $sql = "UPDATE voucher SET da_dung = da_dung + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Ưu đãi cá nhân hoá: xem tour ở điểm đến nào → tự có mã giảm giá của điểm đến đó
    public function layHoacTaoVoucherDiemDen($diem_den)
    {
        $slug = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', bo_dau($diem_den)));
        if ($slug === '') return null;
        $ma = 'MAY' . substr($slug, 0, 12) . '8';
        $stmt = $this->pdo->prepare("SELECT * FROM voucher WHERE ma_voucher = :ma LIMIT 1");
        $stmt->execute([':ma' => $ma]);
        $v = $stmt->fetch();
        if (!$v) {
            $ins = $this->pdo->prepare("INSERT INTO voucher (ma_voucher, loai_giam, gia_tri, giam_toi_da, don_toi_thieu, so_luong, ngay_bat_dau, ngay_ket_thuc, trang_thai)
                                        VALUES (:ma,'phan_tram',8,300000,1500000,500,CURDATE(),DATE_ADD(CURDATE(), INTERVAL 1 YEAR),1)");
            $ins->execute([':ma' => $ma]);
            $stmt->execute([':ma' => $ma]);
            $v = $stmt->fetch();
        }
        return $v;
    }

    // Mã ưu đãi chung của chiến dịch cuối năm (MAYVIET500: giảm 500.000 ₫ cho đơn từ 5 triệu)
    public function damBaoVoucherChung()
    {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO voucher (ma_voucher, loai_giam, gia_tri, giam_toi_da, don_toi_thieu, so_luong, ngay_bat_dau, ngay_ket_thuc, trang_thai)
                                     VALUES ('MAYVIET500','tien_mat',500000,NULL,5000000,300,'2026-01-01','2027-12-31',1)");
        $stmt->execute();
    }
}
