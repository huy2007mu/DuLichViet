-- ==========================================================
-- CSDL: DU LỊCH VIỆT (Website đặt tour - MVC OOP PHP)
-- ==========================================================

CREATE DATABASE IF NOT EXISTS du_lich_viet
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE du_lich_viet;

-- ----------------------------------------------------------
-- BẢNG: nguoi_dung (Admin + Khách hàng)
-- ----------------------------------------------------------
CREATE TABLE nguoi_dung (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    ho_ten         VARCHAR(100)  NOT NULL,
    email          VARCHAR(150)  NOT NULL UNIQUE,
    mat_khau       VARCHAR(255)  NOT NULL,
    so_dien_thoai  VARCHAR(20)   DEFAULT NULL,
    dia_chi        VARCHAR(255)  DEFAULT NULL,
    vai_tro        ENUM('admin','khach_hang') NOT NULL DEFAULT 'khach_hang',
    trang_thai     TINYINT(1)    NOT NULL DEFAULT 1 COMMENT '1: hoạt động, 0: khoá',
    ngay_tao       DATETIME      DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------------------
-- BẢNG: danh_muc
-- ----------------------------------------------------------
CREATE TABLE danh_muc (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    ten_danh_muc  VARCHAR(100) NOT NULL,
    mo_ta         TEXT,
    ngay_tao      DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------------------
-- BẢNG: san_pham
-- ----------------------------------------------------------
CREATE TABLE san_pham (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    danh_muc_id    INT NOT NULL,
    ten_san_pham   VARCHAR(150)   NOT NULL,
    gia            DECIMAL(12,0)  NOT NULL,
    so_luong       INT            NOT NULL DEFAULT 0 COMMENT 'Số chỗ còn nhận',
    thoi_gian      VARCHAR(50)    DEFAULT NULL COMMENT 'VD: 3 ngày 2 đêm',
    diem_khoi_hanh VARCHAR(100)   DEFAULT NULL,
    phuong_tien    VARCHAR(100)   DEFAULT NULL,
    mo_ta          TEXT,
    hinh_anh       VARCHAR(255)   DEFAULT NULL,
    luot_xem       INT            NOT NULL DEFAULT 0,
    trang_thai     TINYINT(1)     NOT NULL DEFAULT 1 COMMENT '1: đang mở bán, 0: tạm ngưng',
    ngay_tao       DATETIME       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (danh_muc_id) REFERENCES danh_muc(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------
-- BẢNG: voucher (giảm giá)
-- ----------------------------------------------------------
CREATE TABLE voucher (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    ma_voucher     VARCHAR(50)    NOT NULL UNIQUE,
    loai_giam      ENUM('phan_tram','tien_mat') NOT NULL DEFAULT 'phan_tram',
    gia_tri        DECIMAL(12,0)  NOT NULL COMMENT 'Số % hoặc số tiền giảm',
    giam_toi_da    DECIMAL(12,0)  DEFAULT NULL COMMENT 'Áp dụng khi loại giảm = phần trăm',
    don_toi_thieu  DECIMAL(12,0)  NOT NULL DEFAULT 0,
    so_luong       INT            NOT NULL DEFAULT 0,
    da_dung        INT            NOT NULL DEFAULT 0,
    ngay_bat_dau   DATE           DEFAULT NULL,
    ngay_ket_thuc  DATE           DEFAULT NULL,
    trang_thai     TINYINT(1)     NOT NULL DEFAULT 1,
    ngay_tao       DATETIME       DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ----------------------------------------------------------
-- BẢNG: gio_hang_chi_tiet (giỏ hàng lưu theo tài khoản)
-- ----------------------------------------------------------
CREATE TABLE gio_hang_chi_tiet (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_dung_id  INT NOT NULL,
    san_pham_id    INT NOT NULL,
    so_luong       INT NOT NULL DEFAULT 1,
    ngay_tao       DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_gio_hang (nguoi_dung_id, san_pham_id),
    FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE,
    FOREIGN KEY (san_pham_id)   REFERENCES san_pham(id)   ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------
-- BẢNG: yeu_thich (danh sách sản phẩm yêu thích lưu theo tài khoản)
-- ----------------------------------------------------------
CREATE TABLE yeu_thich (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_dung_id  INT NOT NULL,
    san_pham_id    INT NOT NULL,
    ngay_tao       DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_yeu_thich (nguoi_dung_id, san_pham_id),
    FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE,
    FOREIGN KEY (san_pham_id)   REFERENCES san_pham(id)   ON DELETE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------
-- BẢNG: don_hang
-- ----------------------------------------------------------
CREATE TABLE don_hang (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    ma_don_hang    VARCHAR(20)   NOT NULL UNIQUE,
    nguoi_dung_id  INT           NOT NULL,
    ho_ten_nhan    VARCHAR(100)  NOT NULL,
    so_dien_thoai  VARCHAR(20)   NOT NULL,
    dia_chi_giao   VARCHAR(255)  NOT NULL,
    ngay_khoi_hanh DATE          DEFAULT NULL,
    ghi_chu        VARCHAR(255)  DEFAULT NULL,
    voucher_id     INT           DEFAULT NULL,
    tam_tinh       DECIMAL(12,0) NOT NULL DEFAULT 0,
    tien_giam      DECIMAL(12,0) NOT NULL DEFAULT 0,
    tong_tien      DECIMAL(12,0) NOT NULL DEFAULT 0,
    trang_thai     ENUM('cho_xac_nhan','da_xac_nhan','dang_giao','hoan_thanh','da_huy')
                   NOT NULL DEFAULT 'cho_xac_nhan',
    ngay_tao       DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id),
    FOREIGN KEY (voucher_id)    REFERENCES voucher(id)
) ENGINE=InnoDB;

-- ----------------------------------------------------------
-- BẢNG: don_hang_chi_tiet
-- ----------------------------------------------------------
CREATE TABLE don_hang_chi_tiet (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    don_hang_id    INT NOT NULL,
    san_pham_id    INT DEFAULT NULL,
    ten_san_pham   VARCHAR(150)  NOT NULL,
    hinh_anh       VARCHAR(255)  DEFAULT NULL,
    gia            DECIMAL(12,0) NOT NULL,
    so_luong       INT NOT NULL,
    FOREIGN KEY (don_hang_id) REFERENCES don_hang(id) ON DELETE CASCADE,
    FOREIGN KEY (san_pham_id) REFERENCES san_pham(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ----------------------------------------------------------
-- BẢNG: danh_gia (bình luận / đánh giá sản phẩm đã mua)
-- ----------------------------------------------------------
CREATE TABLE danh_gia (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    san_pham_id    INT NOT NULL,
    nguoi_dung_id  INT NOT NULL,
    don_hang_id    INT NOT NULL,
    so_sao         TINYINT NOT NULL DEFAULT 5 COMMENT '1 - 5 sao',
    noi_dung       TEXT,
    trang_thai     TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: hiển thị, 0: đã ẩn',
    ngay_tao       DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_danh_gia (nguoi_dung_id, san_pham_id, don_hang_id),
    FOREIGN KEY (san_pham_id)   REFERENCES san_pham(id)   ON DELETE CASCADE,
    FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE,
    FOREIGN KEY (don_hang_id)   REFERENCES don_hang(id)   ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================================================
-- DỮ LIỆU MẪU
-- ==========================================================

-- Tài khoản: admin (mật khẩu: 123456) & khách hàng mẫu (mật khẩu: 123456)
INSERT INTO nguoi_dung (ho_ten, email, mat_khau, so_dien_thoai, dia_chi, vai_tro) VALUES
('Quản Trị Viên', 'admin@gmail.com', MD5('123456'), '0900000000', 'Hà Nội', 'admin'),
('Nguyễn Văn A',  'khachhang@gmail.com', MD5('123456'), '0911111111', 'Quận 1, TP.HCM', 'khach_hang');

-- Điểm đến (danh mục)
INSERT INTO danh_muc (ten_danh_muc, mo_ta) VALUES
('Miền Bắc', 'Hà Nội, Hạ Long, Sa Pa, Ninh Bình, Hà Giang...'),
('Miền Trung', 'Huế, Đà Nẵng, Hội An, Quy Nhơn, Phong Nha...'),
('Miền Nam', 'TP.HCM, Cần Thơ, Miền Tây sông nước, Đà Lạt...'),
('Biển đảo', 'Phú Quốc, Côn Đảo, Nha Trang, Cô Tô...'),
('Nước ngoài', 'Nhật Bản, Hàn Quốc, Thái Lan, Singapore...');

-- Tour mẫu (hình ảnh để trống - web tự dùng ảnh minh hoạ; thêm ảnh thật qua Admin > Tour)
INSERT INTO san_pham (danh_muc_id, ten_san_pham, gia, so_luong, thoi_gian, diem_khoi_hanh, phuong_tien, mo_ta, luot_xem) VALUES
(1, 'Hạ Long - Du thuyền 5 sao 2 ngày 1 đêm', 3290000, 30, '2 ngày 1 đêm', 'Hà Nội', 'Xe limousine + Du thuyền', 'Ngắm vịnh Hạ Long trên du thuyền sang trọng, chèo kayak, thăm hang Sửng Sốt và thưởng thức hải sản tươi.\n\nBao gồm: xe đưa đón, 3 bữa ăn, vé tham quan, hướng dẫn viên.\nKhông bao gồm: chi phí cá nhân, đồ uống ngoài chương trình.', 120),
(1, 'Sa Pa - Fansipan - Bản Cát Cát 3 ngày 2 đêm', 4190000, 25, '3 ngày 2 đêm', 'Hà Nội', 'Xe giường nằm', 'Chinh phục đỉnh Fansipan bằng cáp treo, dạo bản Cát Cát, ngắm ruộng bậc thang và thưởng thức ẩm thực vùng cao.', 95),
(1, 'Ninh Bình - Tràng An - Hang Múa 1 ngày', 990000, 40, '1 ngày', 'Hà Nội', 'Xe du lịch', 'Đi thuyền qua quần thể Tràng An, leo 500 bậc đá Hang Múa ngắm toàn cảnh, ăn trưa đặc sản dê núi.', 80),
(1, 'Hà Giang - Cao nguyên đá Đồng Văn 3 ngày 2 đêm', 3890000, 20, '3 ngày 2 đêm', 'Hà Nội', 'Xe limousine', 'Cung đường Hạnh Phúc, đèo Mã Pí Lèng, cột cờ Lũng Cú, phố cổ Đồng Văn.', 70),
(1, 'Cát Bà - Vịnh Lan Hạ 2 ngày 1 đêm', 2490000, 32, '2 ngày 1 đêm', 'Hà Nội', 'Xe du lịch + Tàu', 'Đạp xe quanh đảo Cát Bà, chèo kayak vịnh Lan Hạ, tắm biển và BBQ hải sản.', 60),
(2, 'Đà Nẵng - Hội An - Bà Nà Hills 4 ngày 3 đêm', 6990000, 20, '4 ngày 3 đêm', 'TP.HCM', 'Máy bay + Xe du lịch', 'Check-in Cầu Vàng, phố cổ Hội An về đêm, bán đảo Sơn Trà và bãi biển Mỹ Khê. Khách sạn 4 sao trung tâm.', 150),
(2, 'Huế - Phong Nha - Động Thiên Đường 3 ngày 2 đêm', 4890000, 18, '3 ngày 2 đêm', 'Đà Nẵng', 'Xe du lịch', 'Khám phá Đại Nội, lăng tẩm Huế, du thuyền sông Hương và động Thiên Đường kỳ vĩ.', 75),
(2, 'Hội An - Thả đèn hoa đăng - Làng gốm Thanh Hà 2 ngày', 2790000, 28, '2 ngày 1 đêm', 'Đà Nẵng', 'Xe du lịch', 'Dạo phố cổ, thả đèn hoa đăng trên sông Hoài, học làm gốm và thưởng thức cao lầu.', 88),
(2, 'Quy Nhơn - Kỳ Co - Eo Gió 3 ngày 2 đêm', 3990000, 24, '3 ngày 2 đêm', 'TP.HCM', 'Máy bay', 'Tắm biển Kỳ Co nước trong vắt, check-in Eo Gió, Tháp Đôi và hải sản Quy Nhơn.', 55),
(3, 'Miền Tây - Chợ nổi Cái Răng - Cần Thơ 2 ngày 1 đêm', 2190000, 35, '2 ngày 1 đêm', 'TP.HCM', 'Xe du lịch + Thuyền', 'Trải nghiệm chợ nổi, vườn trái cây, làm bánh dân gian và nghe đờn ca tài tử trên sông.', 65),
(3, 'Đà Lạt - Thành phố ngàn hoa 3 ngày 2 đêm', 3590000, 28, '3 ngày 2 đêm', 'TP.HCM', 'Xe limousine', 'Săn mây đồi chè Cầu Đất, thung lũng Tình Yêu, cà phê view đồi thông và chợ đêm Đà Lạt.', 110),
(3, 'Đà Lạt - Măng Đen - Săn mây đồi chè 4 ngày 3 đêm', 4590000, 16, '4 ngày 3 đêm', 'Hà Nội', 'Máy bay + Xe du lịch', 'Combo nghỉ dưỡng cao nguyên: homestay view đồi thông, săn mây, BBQ ngoài trời.', 45),
(4, 'Phú Quốc - Vinwonders - Hòn Thơm 4 ngày 3 đêm', 7890000, 22, '4 ngày 3 đêm', 'Hà Nội', 'Máy bay', 'Nghỉ dưỡng resort biển, cáp treo vượt biển dài nhất thế giới, lặn ngắm san hô và chợ đêm Dinh Cậu.', 140),
(4, 'Nha Trang - 4 đảo - Lặn biển 3 ngày 2 đêm', 4390000, 26, '3 ngày 2 đêm', 'TP.HCM', 'Xe giường nằm + Cano', 'Tour 4 đảo bằng cano, lặn ngắm san hô, tắm bùn khoáng và thưởng thức hải sản vịnh Nha Trang.', 90),
(4, 'Côn Đảo - Hành trình tâm linh 3 ngày 2 đêm', 6290000, 15, '3 ngày 2 đêm', 'TP.HCM', 'Máy bay', 'Viếng nghĩa trang Hàng Dương, thăm di tích lịch sử, tắm biển hoang sơ Đầm Trầu.', 50),
(4, 'Phú Quốc - Nghỉ dưỡng 5 sao trọn gói 3 ngày 2 đêm', 8990000, 12, '3 ngày 2 đêm', 'Hà Nội', 'Máy bay', 'Resort 5 sao sát biển, buffet sáng, đưa đón sân bay, tặng vé Safari.', 100),
(4, 'Mũi Né - Phan Thiết - Đồi cát bay 2 ngày 1 đêm', 1990000, 38, '2 ngày 1 đêm', 'TP.HCM', 'Xe limousine', 'Trượt cát đồi Bạch, làng chài, Suối Tiên và hải sản tươi sống.', 40),
(5, 'Thưởng thu Nhật Bản: Osaka - Kyoto - Núi Phú Sĩ - Tokyo 6N5Đ', 34900000, 20, '6 ngày 5 đêm', 'Hà Nội', 'Vietjet Air + Ô tô', 'Ngắm lá đỏ mùa thu, tham quan lâu đài Osaka, đền vàng Kyoto, núi Phú Sĩ và ngâm onsen.', 200),
(5, 'Nhật Bản cung đường vàng: Tokyo - Núi Phú Sĩ - Nagoya - Osaka 5N4Đ', 41990000, 18, '5 ngày 4 đêm', 'Hà Nội', 'Vietnam Airlines', 'Bay hãng quốc gia, khách sạn 4 sao, tàu Shinkansen và tham quan Tokyo Skytree.', 180),
(5, 'Hàn Quốc: Seoul - Nami - Everland mùa hoa anh đào 5N4Đ', 17900000, 25, '5 ngày 4 đêm', 'Hà Nội', 'Vietnam Airlines', 'Dạo đảo Nami, công viên Everland, cung Gyeongbokgung và mua sắm Myeongdong.', 130),
(5, 'Hàn Quốc: Jeju - Seoul - Busan nghỉ dưỡng 6N5Đ', 21900000, 16, '6 ngày 5 đêm', 'TP.HCM', 'Máy bay', 'Khám phá đảo Jeju thơ mộng, thủ đô Seoul sôi động và thành phố biển Busan.', 85),
(5, 'Thái Lan: Bangkok - Pattaya 5 ngày 4 đêm', 7990000, 30, '5 ngày 4 đêm', 'TP.HCM', 'Máy bay', 'Cung điện Hoàng Gia, chợ nổi, show Alcazar, đảo Coral và ẩm thực đường phố.', 115),
(5, 'Singapore - Malaysia 5 ngày 4 đêm', 13900000, 22, '5 ngày 4 đêm', 'Hà Nội', 'Máy bay', 'Vườn Gardens by the Bay, Universal Studios, tháp đôi Petronas và Genting.', 70);

-- Voucher
INSERT INTO voucher (ma_voucher, loai_giam, gia_tri, giam_toi_da, don_toi_thieu, so_luong, ngay_bat_dau, ngay_ket_thuc) VALUES
('CHAOMUNG10', 'phan_tram', 10, 500000, 2000000, 100, '2026-01-01', '2027-12-31'),
('GIAM200K',   'tien_mat',  200000, NULL,  1500000, 200, '2026-01-01', '2027-12-31'),
('DATSOM20',   'phan_tram', 20, 1500000, 5000000, 50, '2026-01-01', '2027-12-31'),
('MAYVIET500', 'tien_mat',  500000, NULL, 5000000, 300, '2026-01-01', '2027-12-31');

-- ----------------------------------------------------------
-- BẢNG: luot_choi_game (mini-game nhận quà: chơi thử & chơi thật)
-- (Bảng này cũng tự tạo khi chạy web, nên DB cũ không cần import lại)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS luot_choi_game (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_dung_id INT NOT NULL,
    don_hang_id INT DEFAULT NULL,
    che_do ENUM('thu','that') NOT NULL DEFAULT 'that',
    cap_do VARCHAR(20) NOT NULL,
    phan_tram TINYINT NOT NULL DEFAULT 0,
    diem INT NOT NULL DEFAULT 0,
    qua_tang VARCHAR(255) DEFAULT NULL,
    ma_voucher VARCHAR(50) DEFAULT NULL,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nd (nguoi_dung_id), INDEX idx_dh (don_hang_id)
) ENGINE=InnoDB;

-- ----------------------------------------------------------
-- BẢNG: cay_thanh_tich_qua (quà đã nhận theo từng cấp của Cây thành tích)
-- (Bảng này cũng tự tạo khi chạy web, nên DB cũ không cần import lại)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS cay_thanh_tich_qua (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_dung_id INT NOT NULL,
    cap TINYINT NOT NULL,
    ma_voucher VARCHAR(50) NOT NULL,
    ngay_nhan DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_nd_cap (nguoi_dung_id, cap)
) ENGINE=InnoDB;
