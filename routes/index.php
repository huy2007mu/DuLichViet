<?php

// Lấy action từ URL, mặc định là trang chủ
$action = $_GET['action'] ?? '/';

// ==========================================
// ĐIỀU HƯỚNG URL → CONTROLLER → METHOD
// ==========================================
match ($action) {

    // ============================================================
    // KHU VỰC KHÁCH HÀNG (PUBLIC - Website bán hàng)
    // ============================================================

    // ---------- TRANG CHỦ ----------
    '/'                          => (new TrangChuController)->index(),

    // ---------- CẨM NANG (SEO) / HẠNG THÀNH VIÊN / MINI-GAME / SITEMAP ----------
    'cam-nang'                   => (new CamNangController)->danhSach(),
    'cam-nang-chi-tiet'          => (new CamNangController)->chiTiet(),
    'hang-thanh-vien'            => (new ThanhVienController)->hang(),
    'choi-thu'                   => (new TroChoiController)->choiThu(),
    'tro-choi'                   => (new TroChoiController)->choiThat(),
    'tro-choi-bat-dau'           => (new TroChoiController)->batDau(),
    'tro-choi-ket-qua'           => (new TroChoiController)->ketQua(),
    'cay-thanh-tich'             => (new CayThanhTichController)->cay(),
    'cay-nhan-qua'               => (new CayThanhTichController)->nhanQua(),
    'bang-xep-hang'              => (new XepHangController)->bang(),
    'sitemap'                    => (new SeoController)->sitemap(),

    // ---------- ĐĂNG NHẬP / ĐĂNG KÝ / ĐĂNG XUẤT (dùng chung) ----------
    'dang-nhap'                  => (new DangNhapController)->hienThi(),
    'xu-ly-dang-nhap'            => (new DangNhapController)->xuLy(),
    'dang-xuat'                  => (new DangNhapController)->dangXuat(),
    'dang-ky'                    => (new DangKyController)->hienThi(),
    'xu-ly-dang-ky'              => (new DangKyController)->xuLy(),

    // ---------- SẢN PHẨM: DANH SÁCH / TÌM KIẾM / LỌC / CHI TIẾT ----------
    'san-pham'                   => (new SanPhamController)->danhSach(),
    'san-pham-chi-tiet'          => (new SanPhamController)->chiTiet(),

    // ---------- GIỎ HÀNG ----------
    'gio-hang'                   => (new GioHangController)->xemGio(),
    'gio-hang-them'              => (new GioHangController)->them(),
    'gio-hang-cap-nhat'          => (new GioHangController)->capNhat(),
    'gio-hang-cap-nhat-ajax'     => (new GioHangController)->capNhatAjax(),
    'gio-hang-xoa'               => (new GioHangController)->xoa(),

    // ---------- SẢN PHẨM YÊU THÍCH ----------
    'yeu-thich'                  => (new YeuThichController)->danhSach(),
    'yeu-thich-toggle'           => (new YeuThichController)->toggle(),
    'yeu-thich-xoa'              => (new YeuThichController)->xoa(),

    // ---------- ĐẶT HÀNG (không thanh toán online) ----------
    'dat-hang'                   => (new DonHangController)->hienThiDatHang(),
    'ap-dung-voucher'            => (new DonHangController)->apDungVoucher(),
    'huy-voucher'                => (new DonHangController)->huyVoucher(),
    'dat-hang-xu-ly'             => (new DonHangController)->xuLy(),
    'dat-hang-thanh-cong'        => (new DonHangController)->thanhCong(),

    // ---------- THEO DÕI ĐƠN HÀNG ----------
    'don-hang-cua-toi'           => (new DonHangController)->donHangCuaToi(),
    'don-hang-chi-tiet'          => (new DonHangController)->chiTiet(),
    'don-hang-huy'               => (new DonHangController)->huy(),

    // ---------- BÌNH LUẬN / ĐÁNH GIÁ SẢN PHẨM ĐÃ MUA ----------
    'danh-gia-them'              => (new DanhGiaController)->them(),

    // ============================================================
    // KHU VỰC QUẢN TRỊ (ADMIN)
    // ============================================================

    // ---------- TỔNG QUAN ----------
    'admin'                      => (new AdminTrangChuController)->index(),

    // ---------- QUẢN LÝ DANH MỤC ----------
    'admin-danh-muc'             => (new AdminDanhMucController)->danhSach(),
    'admin-danh-muc-them'        => (new AdminDanhMucController)->them(),
    'admin-danh-muc-luu'         => (new AdminDanhMucController)->luu(),
    'admin-danh-muc-sua'         => (new AdminDanhMucController)->sua(),
    'admin-danh-muc-cap-nhat'    => (new AdminDanhMucController)->capNhat(),
    'admin-danh-muc-xoa'         => (new AdminDanhMucController)->xoa(),

    // ---------- QUẢN LÝ SẢN PHẨM ----------
    'admin-san-pham'             => (new AdminSanPhamController)->danhSach(),
    'admin-san-pham-them'        => (new AdminSanPhamController)->them(),
    'admin-san-pham-luu'         => (new AdminSanPhamController)->luu(),
    'admin-san-pham-sua'         => (new AdminSanPhamController)->sua(),
    'admin-san-pham-cap-nhat'    => (new AdminSanPhamController)->capNhat(),
    'admin-san-pham-xoa'         => (new AdminSanPhamController)->xoa(),

    // ---------- QUẢN LÝ TÀI KHOẢN ----------
    'admin-nguoi-dung'           => (new AdminNguoiDungController)->danhSach(),
    'admin-nguoi-dung-them'      => (new AdminNguoiDungController)->them(),
    'admin-nguoi-dung-luu'       => (new AdminNguoiDungController)->luu(),
    'admin-nguoi-dung-sua'       => (new AdminNguoiDungController)->sua(),
    'admin-nguoi-dung-cap-nhat'  => (new AdminNguoiDungController)->capNhat(),
    'admin-nguoi-dung-xoa'       => (new AdminNguoiDungController)->xoa(),

    // ---------- QUẢN LÝ ĐƠN HÀNG ----------
    'admin-don-hang'                  => (new AdminDonHangController)->danhSach(),
    'admin-don-hang-chi-tiet'         => (new AdminDonHangController)->chiTiet(),
    'admin-don-hang-cap-nhat-trang-thai' => (new AdminDonHangController)->capNhatTrangThai(),

    // ---------- QUẢN LÝ VOUCHER GIẢM GIÁ ----------
    'admin-voucher'              => (new AdminVoucherController)->danhSach(),
    'admin-voucher-them'         => (new AdminVoucherController)->them(),
    'admin-voucher-luu'          => (new AdminVoucherController)->luu(),
    'admin-voucher-sua'          => (new AdminVoucherController)->sua(),
    'admin-voucher-cap-nhat'     => (new AdminVoucherController)->capNhat(),
    'admin-voucher-xoa'          => (new AdminVoucherController)->xoa(),

    // ---------- QUẢN LÝ SẢN PHẨM YÊU THÍCH ----------
    'admin-yeu-thich'            => (new AdminYeuThichController)->danhSach(),
    'admin-yeu-thich-xoa'        => (new AdminYeuThichController)->xoa(),

    // ---------- QUẢN LÝ BÌNH LUẬN / ĐÁNH GIÁ ----------
    'admin-danh-gia'             => (new AdminDanhGiaController)->danhSach(),
    'admin-danh-gia-an'          => (new AdminDanhGiaController)->an(),
    'admin-danh-gia-hien'        => (new AdminDanhGiaController)->hien(),
    'admin-danh-gia-xoa'         => (new AdminDanhGiaController)->xoa(),

    // ---------- TRANG KHÔNG TỒN TẠI ----------
    default => (function () {
        http_response_code(404);
        echo '<h1 style="text-align:center;margin-top:100px;font-family:sans-serif">404 - Trang không tồn tại</h1>
              <p style="text-align:center"><a href="' . BASE_URL . '">Về trang chủ</a></p>';
    })(),
};
