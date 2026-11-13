<?php

// ==========================================
// MODEL GỐC - tất cả model kế thừa từ đây
// ==========================================
class BaseModel
{
    protected $bang;   // Tên bảng trong database
    protected $pdo;    // Đối tượng kết nối PDO

    // Kết nối cơ sở dữ liệu
    public function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            DB_HOST, DB_PORT, DB_NAME
        );

        try {
            $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
        } catch (PDOException $loi) {
            die("❌ Kết nối cơ sở dữ liệu thất bại: {$loi->getMessage()}");
        }
    }

    // Đóng kết nối khi xong
    public function __destruct()
    {
        $this->pdo = null;
    }

    // Lấy tất cả bản ghi
    public function layTatCa()
    {
        $sql  = "SELECT * FROM {$this->bang} ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Lấy 1 bản ghi theo ID
    public function layTheoId($id)
    {
        $sql  = "SELECT * FROM {$this->bang} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Xoá bản ghi theo ID
    public function xoa($id)
    {
        $sql  = "DELETE FROM {$this->bang} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Đếm số bản ghi
    public function demTatCa()
    {
        $sql  = "SELECT COUNT(*) as tong FROM {$this->bang}";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch()['tong'];
    }
}
