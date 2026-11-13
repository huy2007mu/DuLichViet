-- DỮ LIỆU MẪU CHO BẢNG XẾP HẠNG & CÂY THÀNH TÍCH (chỉ để demo, chạy 1 lần, xoá được: DELETE FROM nguoi_dung WHERE email LIKE 'mau%@gmail.com')

CREATE TABLE IF NOT EXISTS luot_choi_game (id INT AUTO_INCREMENT PRIMARY KEY, nguoi_dung_id INT NOT NULL, don_hang_id INT DEFAULT NULL, che_do ENUM('thu','that') NOT NULL DEFAULT 'that', cap_do VARCHAR(20) NOT NULL, phan_tram TINYINT NOT NULL DEFAULT 0, diem INT NOT NULL DEFAULT 0, qua_tang VARCHAR(255) DEFAULT NULL, ma_voucher VARCHAR(50) DEFAULT NULL, ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX idx_nd (nguoi_dung_id), INDEX idx_dh (don_hang_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO nguoi_dung (ho_ten, email, mat_khau, so_dien_thoai, dia_chi, vai_tro) VALUES

('Trần Minh Khoa', 'mau1@gmail.com', MD5('123456'), '0953464097', 'Việt Nam', 'khach_hang'),
('Lê Thu Hà', 'mau2@gmail.com', MD5('123456'), '0930246633', 'Việt Nam', 'khach_hang'),
('Phạm Quốc Bảo', 'mau3@gmail.com', MD5('123456'), '0962992312', 'Việt Nam', 'khach_hang'),
('Nguyễn Hoàng Yến', 'mau4@gmail.com', MD5('123456'), '0997366946', 'Việt Nam', 'khach_hang'),
('Đặng Gia Huy', 'mau5@gmail.com', MD5('123456'), '0916480894', 'Việt Nam', 'khach_hang'),
('Vũ Ngọc Anh', 'mau6@gmail.com', MD5('123456'), '0919722233', 'Việt Nam', 'khach_hang'),
('Bùi Thanh Tùng', 'mau7@gmail.com', MD5('123456'), '0981924865', 'Việt Nam', 'khach_hang'),
('Hoàng Mai Linh', 'mau8@gmail.com', MD5('123456'), '0922633920', 'Việt Nam', 'khach_hang');

INSERT INTO don_hang (ma_don_hang, nguoi_dung_id, ho_ten_nhan, so_dien_thoai, dia_chi_giao, tam_tinh, tong_tien, trang_thai, ngay_tao) VALUES
('DEMO0001',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',5990000,5990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 3 DAY)),
('DEMO0002',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 40 DAY)),
('DEMO0003',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',4190000,4190000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 1 DAY)),
('DEMO0004',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 15 DAY)),
('DEMO0005',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',7990000,7990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 2 DAY)),
('DEMO0006',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',4190000,4190000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 2 DAY)),
('DEMO0007',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 15 DAY)),
('DEMO0008',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 2 DAY)),
('DEMO0009',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',4190000,4190000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 1 DAY)),
('DEMO0010',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 15 DAY)),
('DEMO0011',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 5 DAY)),
('DEMO0012',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 40 DAY)),
('DEMO0013',(SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'Trần Minh Khoa','0900000000','Việt Nam',4190000,4190000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 6 DAY));

INSERT INTO luot_choi_game (nguoi_dung_id, che_do, cap_do, phan_tram, diem, ngay_tao) VALUES
((SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'that','thuong',89,58,DATE_SUB(NOW(), INTERVAL 2 DAY)),
((SELECT id FROM nguoi_dung WHERE email='mau1@gmail.com'),'that','kho',87,55,DATE_SUB(NOW(), INTERVAL 1 DAY));

INSERT INTO don_hang (ma_don_hang, nguoi_dung_id, ho_ten_nhan, so_dien_thoai, dia_chi_giao, tam_tinh, tong_tien, trang_thai, ngay_tao) VALUES
('DEMO0014',(SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'Lê Thu Hà','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 2 DAY)),
('DEMO0015',(SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'Lê Thu Hà','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 3 DAY)),
('DEMO0016',(SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'Lê Thu Hà','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 5 DAY)),
('DEMO0017',(SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'Lê Thu Hà','0900000000','Việt Nam',5990000,5990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 2 DAY)),
('DEMO0018',(SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'Lê Thu Hà','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 2 DAY)),
('DEMO0019',(SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'Lê Thu Hà','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 1 DAY)),
('DEMO0020',(SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'Lê Thu Hà','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 5 DAY)),
('DEMO0021',(SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'Lê Thu Hà','0900000000','Việt Nam',7990000,7990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 40 DAY)),
('DEMO0022',(SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'Lê Thu Hà','0900000000','Việt Nam',7990000,7990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 9 DAY)),
('DEMO0023',(SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'Lê Thu Hà','0900000000','Việt Nam',7990000,7990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 20 DAY)),
('DEMO0024',(SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'Lê Thu Hà','0900000000','Việt Nam',5990000,5990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 6 DAY));

INSERT INTO luot_choi_game (nguoi_dung_id, che_do, cap_do, phan_tram, diem, ngay_tao) VALUES
((SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'that','thuong',84,48,DATE_SUB(NOW(), INTERVAL 2 DAY)),
((SELECT id FROM nguoi_dung WHERE email='mau2@gmail.com'),'that','kho',84,48,DATE_SUB(NOW(), INTERVAL 2 DAY));

INSERT INTO don_hang (ma_don_hang, nguoi_dung_id, ho_ten_nhan, so_dien_thoai, dia_chi_giao, tam_tinh, tong_tien, trang_thai, ngay_tao) VALUES
('DEMO0025',(SELECT id FROM nguoi_dung WHERE email='mau3@gmail.com'),'Phạm Quốc Bảo','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 3 DAY)),
('DEMO0026',(SELECT id FROM nguoi_dung WHERE email='mau3@gmail.com'),'Phạm Quốc Bảo','0900000000','Việt Nam',5990000,5990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 40 DAY)),
('DEMO0027',(SELECT id FROM nguoi_dung WHERE email='mau3@gmail.com'),'Phạm Quốc Bảo','0900000000','Việt Nam',7990000,7990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 9 DAY)),
('DEMO0028',(SELECT id FROM nguoi_dung WHERE email='mau3@gmail.com'),'Phạm Quốc Bảo','0900000000','Việt Nam',7990000,7990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 6 DAY)),
('DEMO0029',(SELECT id FROM nguoi_dung WHERE email='mau3@gmail.com'),'Phạm Quốc Bảo','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 2 DAY)),
('DEMO0030',(SELECT id FROM nguoi_dung WHERE email='mau3@gmail.com'),'Phạm Quốc Bảo','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 40 DAY)),
('DEMO0031',(SELECT id FROM nguoi_dung WHERE email='mau3@gmail.com'),'Phạm Quốc Bảo','0900000000','Việt Nam',7990000,7990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 3 DAY)),
('DEMO0032',(SELECT id FROM nguoi_dung WHERE email='mau3@gmail.com'),'Phạm Quốc Bảo','0900000000','Việt Nam',5990000,5990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 3 DAY));

INSERT INTO luot_choi_game (nguoi_dung_id, che_do, cap_do, phan_tram, diem, ngay_tao) VALUES
((SELECT id FROM nguoi_dung WHERE email='mau3@gmail.com'),'that','thuong',82,45,DATE_SUB(NOW(), INTERVAL 8 DAY)),
((SELECT id FROM nguoi_dung WHERE email='mau3@gmail.com'),'that','kho',81,42,DATE_SUB(NOW(), INTERVAL 1 DAY));

INSERT INTO don_hang (ma_don_hang, nguoi_dung_id, ho_ten_nhan, so_dien_thoai, dia_chi_giao, tam_tinh, tong_tien, trang_thai, ngay_tao) VALUES
('DEMO0033',(SELECT id FROM nguoi_dung WHERE email='mau4@gmail.com'),'Nguyễn Hoàng Yến','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 3 DAY)),
('DEMO0034',(SELECT id FROM nguoi_dung WHERE email='mau4@gmail.com'),'Nguyễn Hoàng Yến','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 9 DAY)),
('DEMO0035',(SELECT id FROM nguoi_dung WHERE email='mau4@gmail.com'),'Nguyễn Hoàng Yến','0900000000','Việt Nam',5990000,5990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 9 DAY)),
('DEMO0036',(SELECT id FROM nguoi_dung WHERE email='mau4@gmail.com'),'Nguyễn Hoàng Yến','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 20 DAY)),
('DEMO0037',(SELECT id FROM nguoi_dung WHERE email='mau4@gmail.com'),'Nguyễn Hoàng Yến','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 20 DAY)),
('DEMO0038',(SELECT id FROM nguoi_dung WHERE email='mau4@gmail.com'),'Nguyễn Hoàng Yến','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 2 DAY));

INSERT INTO luot_choi_game (nguoi_dung_id, che_do, cap_do, phan_tram, diem, ngay_tao) VALUES
((SELECT id FROM nguoi_dung WHERE email='mau4@gmail.com'),'that','thuong',78,37,DATE_SUB(NOW(), INTERVAL 8 DAY)),
((SELECT id FROM nguoi_dung WHERE email='mau4@gmail.com'),'that','kho',77,35,DATE_SUB(NOW(), INTERVAL 1 DAY));

INSERT INTO don_hang (ma_don_hang, nguoi_dung_id, ho_ten_nhan, so_dien_thoai, dia_chi_giao, tam_tinh, tong_tien, trang_thai, ngay_tao) VALUES
('DEMO0039',(SELECT id FROM nguoi_dung WHERE email='mau5@gmail.com'),'Đặng Gia Huy','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 3 DAY)),
('DEMO0040',(SELECT id FROM nguoi_dung WHERE email='mau5@gmail.com'),'Đặng Gia Huy','0900000000','Việt Nam',5990000,5990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 20 DAY)),
('DEMO0041',(SELECT id FROM nguoi_dung WHERE email='mau5@gmail.com'),'Đặng Gia Huy','0900000000','Việt Nam',5990000,5990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 15 DAY)),
('DEMO0042',(SELECT id FROM nguoi_dung WHERE email='mau5@gmail.com'),'Đặng Gia Huy','0900000000','Việt Nam',5990000,5990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 1 DAY));

INSERT INTO luot_choi_game (nguoi_dung_id, che_do, cap_do, phan_tram, diem, ngay_tao) VALUES
((SELECT id FROM nguoi_dung WHERE email='mau5@gmail.com'),'that','thuong',77,34,DATE_SUB(NOW(), INTERVAL 4 DAY)),
((SELECT id FROM nguoi_dung WHERE email='mau5@gmail.com'),'that','kho',75,31,DATE_SUB(NOW(), INTERVAL 2 DAY));

INSERT INTO don_hang (ma_don_hang, nguoi_dung_id, ho_ten_nhan, so_dien_thoai, dia_chi_giao, tam_tinh, tong_tien, trang_thai, ngay_tao) VALUES
('DEMO0043',(SELECT id FROM nguoi_dung WHERE email='mau6@gmail.com'),'Vũ Ngọc Anh','0900000000','Việt Nam',2890000,2890000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 1 DAY)),
('DEMO0044',(SELECT id FROM nguoi_dung WHERE email='mau6@gmail.com'),'Vũ Ngọc Anh','0900000000','Việt Nam',7990000,7990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 1 DAY)),
('DEMO0045',(SELECT id FROM nguoi_dung WHERE email='mau6@gmail.com'),'Vũ Ngọc Anh','0900000000','Việt Nam',4190000,4190000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 6 DAY));

INSERT INTO luot_choi_game (nguoi_dung_id, che_do, cap_do, phan_tram, diem, ngay_tao) VALUES
((SELECT id FROM nguoi_dung WHERE email='mau6@gmail.com'),'that','thuong',70,21,DATE_SUB(NOW(), INTERVAL 2 DAY)),
((SELECT id FROM nguoi_dung WHERE email='mau6@gmail.com'),'that','kho',70,20,DATE_SUB(NOW(), INTERVAL 8 DAY));

INSERT INTO don_hang (ma_don_hang, nguoi_dung_id, ho_ten_nhan, so_dien_thoai, dia_chi_giao, tam_tinh, tong_tien, trang_thai, ngay_tao) VALUES
('DEMO0046',(SELECT id FROM nguoi_dung WHERE email='mau7@gmail.com'),'Bùi Thanh Tùng','0900000000','Việt Nam',7990000,7990000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 2 DAY)),
('DEMO0047',(SELECT id FROM nguoi_dung WHERE email='mau7@gmail.com'),'Bùi Thanh Tùng','0900000000','Việt Nam',3290000,3290000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 3 DAY));

INSERT INTO luot_choi_game (nguoi_dung_id, che_do, cap_do, phan_tram, diem, ngay_tao) VALUES
((SELECT id FROM nguoi_dung WHERE email='mau7@gmail.com'),'that','thuong',69,18,DATE_SUB(NOW(), INTERVAL 8 DAY)),
((SELECT id FROM nguoi_dung WHERE email='mau7@gmail.com'),'that','kho',67,15,DATE_SUB(NOW(), INTERVAL 4 DAY));

INSERT INTO don_hang (ma_don_hang, nguoi_dung_id, ho_ten_nhan, so_dien_thoai, dia_chi_giao, tam_tinh, tong_tien, trang_thai, ngay_tao) VALUES
('DEMO0048',(SELECT id FROM nguoi_dung WHERE email='mau8@gmail.com'),'Hoàng Mai Linh','0900000000','Việt Nam',4190000,4190000,'hoan_thanh',DATE_SUB(NOW(), INTERVAL 2 DAY));

INSERT INTO luot_choi_game (nguoi_dung_id, che_do, cap_do, phan_tram, diem, ngay_tao) VALUES
((SELECT id FROM nguoi_dung WHERE email='mau8@gmail.com'),'that','thuong',67,14,DATE_SUB(NOW(), INTERVAL 4 DAY)),
((SELECT id FROM nguoi_dung WHERE email='mau8@gmail.com'),'that','kho',65,10,DATE_SUB(NOW(), INTERVAL 8 DAY));
