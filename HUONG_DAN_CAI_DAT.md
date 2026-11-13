# DU LỊCH VIỆT – Website đặt tour (PHP thuần, MVC - OOP)

Dự án được chuyển từ "Quản lý cửa hàng" sang **website du lịch / đặt tour**.
Kiến trúc, tên bảng, tên controller/model **giữ nguyên** để code vẫn chạy ổn định; chỉ đổi giao diện, nội dung và thêm vài trường cho tour.

## 1. Đối chiếu khái niệm cũ → mới
| Bản bán hàng        | Bản du lịch                                   |
|---------------------|-----------------------------------------------|
| Sản phẩm            | **Tour** (bảng `san_pham`)                    |
| Danh mục            | **Điểm đến** (Miền Bắc/Trung/Nam, Biển đảo)   |
| Tồn kho             | **Số chỗ còn nhận**                           |
| Số lượng mua        | **Số khách**                                  |
| Giỏ hàng            | **Giỏ tour**                                  |
| Đơn hàng / Đặt hàng | **Đơn đặt tour** / Đặt tour                   |
| Địa chỉ giao hàng   | Thông tin liên hệ + **Ngày khởi hành** (mới)  |
| Đang giao           | Đang diễn ra                                  |

Trường mới của tour: `thoi_gian` (VD: 3 ngày 2 đêm), `diem_khoi_hanh`, `phuong_tien`.
Trường mới của đơn: `ngay_khoi_hanh` (bắt buộc khi đặt tour, không được ở quá khứ).

## 2. Cài đặt (XAMPP / Laragon)
1. Copy thư mục `DuLichViet` vào `htdocs` (XAMPP) hoặc `www` (Laragon).
2. Mở phpMyAdmin → **Import** file `database/du_lich_viet.sql` (tự tạo DB `du_lich_viet` + dữ liệu mẫu 23 tour).
3. Kiểm tra `configs/env.php` (BASE_URL, DB_USERNAME, DB_PASSWORD).
4. Truy cập: `http://localhost/DuLichViet/`

Yêu cầu: PHP 8+, bật extension `pdo_mysql` và `mbstring` (XAMPP có sẵn).

## 3. Tài khoản demo (mật khẩu: 123456)
| Vai trò    | Email               |
|------------|---------------------|
| Admin      | admin@gmail.com     |
| Khách hàng | khachhang@gmail.com |

Mã giảm giá mẫu: `CHAOMUNG10`, `GIAM200K`, `DATSOM20`.

## 4. ẢNH TRÊN WEBSITE (đã có sẵn ảnh minh hoạ, thay bằng ảnh thật rất dễ)
Dự án có sẵn bộ ảnh cảnh đẹp vẽ bằng SVG trong `assets/img/` nên web không bị trống. Muốn dùng ảnh thật:

**A. Ảnh trang chủ (banner, điểm đến, thư viện, khuyến mãi)** – chép file ảnh **cùng tên** vào `assets/img/` (đuôi `.jpg`, `.png` hoặc `.webp`), web tự ưu tiên ảnh thật, không cần sửa code:

| Tên file               | Dùng ở đâu                         | Kích thước gợi ý |
|------------------------|------------------------------------|------------------|
| `banner-1` … `banner-4`| 4 banner lớn đầu trang (carousel)  | 1920×760         |
| `khuyenmai-1`          | Dải khuyến mãi giữa trang          | 1200×240         |
| `khuyenmai-2`, `-3`    | 2 banner Hàn Quốc / Thái Lan       | 1200×240         |
| `thuvien-1` … `-6`     | Thư viện "Khoảnh khắc đẹp"         | 800×600          |
| `canh-sapa`, `canh-hoian`, `canh-mekong`, `canh-phuquoc`, `canh-nhatban` | Ảnh các ô Điểm đến | 800×600 |
| `canh-halong`, `canh-danang`, `canh-dalat`, `canh-hanquoc`, `canh-thai`, `canh-bien` | Ảnh minh hoạ mặc định của tour chưa có ảnh | 800×600 |

Ví dụ: chép `banner-1.jpg` vào `assets/img/` là banner đầu tiên đổi ngay.

**B. Ảnh từng tour / vé tour** – đăng nhập admin → menu **Tour** → sửa tour → chọn *Hình Ảnh Tour* → Cập nhật. Ảnh lưu ở `assets/uploads/san_pham/` và tự hiện ở thẻ tour, mục "Vé Tour Hot" và trang chi tiết (nên dùng ảnh ngang 16:10 hoặc 4:3). Tour nào chưa có ảnh sẽ dùng ảnh minh hoạ theo điểm đến (dựa vào tên tour).

## 5. Cấu trúc thư mục
```
DuLichViet/
├── assets/img/        # Ảnh trang chủ (banner, thư viện, khuyến mãi...) - thả ảnh thật cùng tên để thay
├── assets/uploads/
│   └── san_pham/      # Ảnh tour (upload qua admin)
├── configs/           # env.php, helper.php
├── controllers/       # Logic (prefix Admin cho khu quản trị)
├── models/            # Truy vấn DB (PDO Prepared Statement)
├── views/
│   ├── layout_client.php / layout_admin.php
│   ├── client/  admin/  auth/
├── routes/index.php
├── database/du_lich_viet.sql
└── index.php
```

## 6. Chức năng
**Khách hàng:** đăng ký/đăng nhập · xem, tìm kiếm, lọc tour (điểm đến, ngân sách, sắp xếp) · chi tiết tour (thời gian, nơi khởi hành, phương tiện, lịch trình) · giỏ tour · mã giảm giá · đặt tour kèm ngày khởi hành · theo dõi/huỷ đơn · đánh giá tour sau khi hoàn thành · tour yêu thích.

**Admin:** tổng quan · quản lý điểm đến · quản lý tour (CRUD + upload ảnh) · đơn đặt tour & cập nhật trạng thái · voucher · đánh giá · yêu thích · tài khoản.

## 7. Việc nên sửa thêm cho đúng đề tài nhóm
- Tên thương hiệu "Du Lịch Việt", hotline, địa chỉ, email: sửa trong `views/layout_client.php` (top bar + footer).
- Mô tả tour mẫu: sửa trong admin hoặc file SQL.
- Mật khẩu đang dùng `md5()` (đủ cho đồ án cơ bản); nếu muốn an toàn hơn hãy chuyển sang `password_hash()`.

---
## CẬP NHẬT GIAO DIỆN MÂY VIỆT (xanh – trắng)
Xem `HUONG_DAN_GIAO_DIEN_MOI.txt`: giao diện mới, logo mâyviệt, mini-game, hạng thành viên, cẩm nang SEO, ưu đãi cá nhân hoá, hỗ trợ 24/7.
