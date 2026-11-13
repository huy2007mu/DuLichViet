<?php

class NguoiDungModel extends BaseModel
{
    protected $bang = 'nguoi_dung';

    // Tìm người dùng theo email (dùng cho đăng nhập)
    public function timTheoEmail($email)
    {
        $sql  = "SELECT * FROM nguoi_dung WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // Thêm người dùng mới (đăng ký / admin thêm tài khoản)
    public function them($du_lieu)
    {
        $sql = "INSERT INTO nguoi_dung (ho_ten, email, mat_khau, so_dien_thoai, dia_chi, vai_tro)
                VALUES (:ho_ten, :email, :mat_khau, :so_dien_thoai, :dia_chi, :vai_tro)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':ho_ten'        => $du_lieu['ho_ten'],
            ':email'         => $du_lieu['email'],
            ':mat_khau'      => md5($du_lieu['mat_khau']),
            ':so_dien_thoai' => $du_lieu['so_dien_thoai'] ?? null,
            ':dia_chi'       => $du_lieu['dia_chi'] ?? null,
            ':vai_tro'       => $du_lieu['vai_tro'] ?? 'khach_hang',
        ]);
    }

    // Cập nhật người dùng (admin sửa hoặc khách tự sửa hồ sơ)
    public function capNhat($id, $du_lieu)
    {
        $co_mat_khau = !empty($du_lieu['mat_khau']);

        $sql = "UPDATE nguoi_dung
                SET ho_ten=:ho_ten, email=:email, so_dien_thoai=:so_dien_thoai,
                    dia_chi=:dia_chi, vai_tro=:vai_tro, trang_thai=:trang_thai"
                . ($co_mat_khau ? ", mat_khau=:mat_khau" : "") . "
                WHERE id=:id";

        $tham_so = [
            ':ho_ten'        => $du_lieu['ho_ten'],
            ':email'         => $du_lieu['email'],
            ':so_dien_thoai' => $du_lieu['so_dien_thoai'] ?? null,
            ':dia_chi'       => $du_lieu['dia_chi'] ?? null,
            ':vai_tro'       => $du_lieu['vai_tro'] ?? 'khach_hang',
            ':trang_thai'    => $du_lieu['trang_thai'] ?? 1,
            ':id'            => $id,
        ];
        if ($co_mat_khau) {
            $tham_so[':mat_khau'] = md5($du_lieu['mat_khau']);
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($tham_so);
    }

    // Kiểm tra email đã tồn tại chưa
    public function kiemTraEmail($email, $tru_id = null)
    {
        if ($tru_id) {
            $sql  = "SELECT COUNT(*) as tong FROM nguoi_dung WHERE email=:email AND id != :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':email' => $email, ':id' => $tru_id]);
        } else {
            $sql  = "SELECT COUNT(*) as tong FROM nguoi_dung WHERE email=:email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
        }
        return $stmt->fetch()['tong'] > 0;
    }

    // Chỉ lấy danh sách khách hàng (dùng cho thống kê)
    public function demKhachHang()
    {
        $sql  = "SELECT COUNT(*) as tong FROM nguoi_dung WHERE vai_tro = 'khach_hang'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch()['tong'];
    }
}
