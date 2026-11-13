<?php

// ==========================================
// CÁC HÀM TIỆN ÍCH DÙNG CHUNG
// ==========================================

// In dữ liệu debug (dùng khi test)
if (!function_exists('kiem_tra')) {
    function kiem_tra($du_lieu)
    {
        echo '<pre>';
        print_r($du_lieu);
        die;
    }
}

// Upload ảnh lên server
if (!function_exists('upload_anh')) {
    function upload_anh($thu_muc, $file)
    {
        $duoi_cho_phep = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $duoi_file     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($duoi_file, $duoi_cho_phep)) {
            throw new Exception('Chỉ chấp nhận file ảnh: jpg, jpeg, png, gif, webp!');
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception('Kích thước ảnh không được vượt quá 5MB!');
        }

        $ten_file_moi = $thu_muc . '/' . time() . '-' . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], PATH_ASSETS_UPLOADS . $ten_file_moi)) {
            return $ten_file_moi;
        }

        throw new Exception('Upload ảnh không thành công!');
    }
}

// Xoá ảnh cũ trên server
if (!function_exists('xoa_anh')) {
    function xoa_anh($duong_dan_anh)
    {
        if (!empty($duong_dan_anh)) {
            $duong_dan_day_du = PATH_ASSETS_UPLOADS . $duong_dan_anh;
            if (file_exists($duong_dan_day_du)) {
                unlink($duong_dan_day_du);
            }
        }
    }
}


// Anh chup du lich theo dia diem. Cac anh nay la anh that tu cac trang du lich/nguon anh cong khai.
// Can ket noi Internet de tai anh; anh upload trong quan tri van duoc uu tien.
if (!function_exists('anh_tour_that')) {
    function anh_tour_that($slug)
    {
        $anh = [
            'tour-ha-long' => 'https://www.unex.ru/uploads/2019/af38f3da8a764ea29336e13eeae5f320.jpg',
            'tour-sa-pa' => 'https://bv-dam.s3.amazonaws.com/dam/photos/ed/58/f8/c1/815fa8c8f96495a5260bf8016150d0df7abc81de0e905f26f2487b72.jpg',
            'tour-ninh-binh' => 'https://vietravelasia.com/media/images/2023/3/shutterstock1266213631-2c70bfcfabf9eb7fb0e6e6af275bb88a.jpg',
            'tour-ha-giang' => 'https://www.mrlinhadventure.com/UserFiles/image/Pierre/Meo-Vac_Ma-Pi-Leng.jpg',
            'tour-cat-ba' => 'https://d21ie9jll6nw9d.cloudfront.net/uploads/blog-migration/0a8e4a15-e384-4c4c-890b-1c4efd87425b.webp',
            'tour-da-nang' => 'https://vietnamtour.com/images/Vietnam_Attractions/goldens-bridge-da-nang_f05ab.jpg',
            'tour-hue' => 'https://www.celebritycruises.com/blog/content/uploads/2025/04/things-to-do-in-hue-imperial-citadel-1024x683.jpg',
            'tour-hoi-an' => 'https://cdn.kimkim.com/files/a/images/2495cb60fae976ff72df8458036b5aeb52831550/original-343b4575ff777028726fa1ea2cf93c9f.jpg',
            'tour-quy-nhon' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=85',
            'tour-mien-tay' => 'https://cdn.prod.rexby.com/image/968125be19b64231bfbb3fac3317c574',
            'tour-mang-den' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=85',
            'tour-da-lat' => 'https://cdn.sweetescape.com/images/cities/da-lat/cover/b622dd74-dddc-43f9-a05a-79421890ddd6.jpg',
            'tour-phu-quoc-vinwonders' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Phu%20quoc%20plage.jpg?width=1200',
            'tour-phu-quoc-nghi-duong' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1200&q=85',
            'tour-nha-trang' => 'https://ik.imagekit.io/tvlk/blog/2024/04/t8UKUqp4-image4-1024x683.jpg?tr=dpr-2%2Cw-675',
            'tour-con-dao' => 'https://images.unsplash.com/photo-1518837695005-2083093ee35b?auto=format&fit=crop&w=1200&q=85',
            'tour-mui-ne' => 'https://images.unsplash.com/photo-1509316785289-025f5b846b35?auto=format&fit=crop&w=1200&q=85',
            'tour-nhat-ban-osaka-kyoto-fuji' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=1200&q=85',
            'tour-nhat-ban-cung-duong-vang' => 'https://images.unsplash.com/photo-1536098561742-ca998e48cbcc?auto=format&fit=crop&w=1200&q=85',
            'tour-han-quoc-seoul-nami' => 'https://images.unsplash.com/photo-1517154421773-0529f29ea451?auto=format&fit=crop&w=1200&q=85',
            'tour-han-quoc-jeju' => 'https://images.unsplash.com/photo-1519608487953-e999c86e7455?auto=format&fit=crop&w=1200&q=85',
            'tour-thai-lan' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&w=1200&q=85',
            'tour-singapore-malaysia' => 'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&w=1200&q=85',
        ];
        if (empty($anh[$slug])) return null;

        // Co file anh that trong assets/img/ (vd tour-ha-long.jpg) thi uu tien dung - khong phu thuoc Internet
        foreach (['webp', 'jpg', 'jpeg', 'png'] as $duoi) {
            if (defined('PATH_ROOT') && file_exists(PATH_ROOT . "assets/img/{$slug}.{$duoi}")) {
                return BASE_URL . "assets/img/{$slug}.{$duoi}";
            }
        }

        // Anh du phong: neu website nguon chan hotlink / loi mang thi trinh duyet tu thu anh Unsplash,
        // roi cuoi cung dung ban ve local assets/img/<slug>.svg (xem assets/js/mayviet.js).
        $du_phong = [
            'tour-ha-long' => 'photo-1500534623283-312aade485b7', 'tour-sa-pa' => 'photo-1519681393784-d120267933ba',
            'tour-ninh-binh' => 'photo-1501785888041-af3ef285b470', 'tour-ha-giang' => 'photo-1464822759023-fed622ff2c3b',
            'tour-cat-ba' => 'photo-1500534623283-312aade485b7', 'tour-da-nang' => 'photo-1507525428034-b723cf961d3e',
            'tour-hue' => 'photo-1528181304800-259b08848526', 'tour-hoi-an' => 'photo-1523906834658-6e24ef2386f9',
            'tour-mien-tay' => 'photo-1470770841072-f978cf4d019e', 'tour-da-lat' => 'photo-1501785888041-af3ef285b470',
            'tour-nha-trang' => 'photo-1518837695005-2083093ee35b', 'tour-phu-quoc-vinwonders' => 'photo-1519046904884-53103b34b206',
        ];
        $chuoi = [];
        if (isset($du_phong[$slug])) $chuoi[] = 'https://images.unsplash.com/' . $du_phong[$slug] . '?auto=format&fit=crop&w=1200&q=85';
        if (defined('BASE_URL')) $chuoi[] = BASE_URL . 'assets/img/' . $slug . '.svg';
        return $anh[$slug] . ($chuoi ? '#fb=' . implode('|', $chuoi) : '');
    }
}

// Ảnh trang trí (banner, thư viện, khuyến mãi...) trong assets/img/
// Muốn dùng ảnh thật: chỉ cần chép file cùng tên (jpg/jpeg/png/webp) vào assets/img/ - tự thay thế bản vẽ mặc định (.svg)
if (!function_exists('anh_trang_tri')) {
    function anh_trang_tri($ten)
    {
        // Anh chup theo dia diem: uu tien URL anh that truoc cac SVG minh hoa cu.
        $anh_theo_ten = [
            'banner-1' => anh_tour_that('tour-ha-long'),
            'banner-2' => anh_tour_that('tour-hoi-an'),
            'banner-3' => anh_tour_that('tour-phu-quoc-nghi-duong'),
            'banner-4' => anh_tour_that('tour-da-nang'),
            'canh-sapa' => anh_tour_that('tour-sa-pa'),
            'canh-hoian' => anh_tour_that('tour-hoi-an'),
            'canh-halong' => anh_tour_that('tour-ha-long'),
            'canh-mekong' => anh_tour_that('tour-mien-tay'),
            'canh-phuquoc' => anh_tour_that('tour-phu-quoc-nghi-duong'),
            'canh-bien' => anh_tour_that('tour-nha-trang'),
            'canh-nhatban' => anh_tour_that('tour-nhat-ban-osaka-kyoto-fuji'),
            'canh-hanquoc' => anh_tour_that('tour-han-quoc-seoul-nami'),
            'canh-thai' => anh_tour_that('tour-thai-lan'),
            'canh-dalat' => anh_tour_that('tour-da-lat'),
            'canh-ninhbinh' => anh_tour_that('tour-ninh-binh'),
            'canh-danang' => anh_tour_that('tour-da-nang'),
            'canh-hagiang' => anh_tour_that('tour-ha-giang'),
            'canh-nuocngoai' => anh_tour_that('tour-singapore-malaysia'),
            'khuyenmai-1' => anh_tour_that('tour-ha-long'),
            'khuyenmai-2' => anh_tour_that('tour-da-lat'),
            'khuyenmai-3' => anh_tour_that('tour-nha-trang'),
            'khuyenmai-4' => anh_tour_that('tour-hoi-an'),
            'thuvien-1' => anh_tour_that('tour-cat-ba'),
            'thuvien-2' => anh_tour_that('tour-ninh-binh'),
            'thuvien-3' => anh_tour_that('tour-sa-pa'),
            'thuvien-4' => anh_tour_that('tour-mien-tay'),
            'thuvien-5' => anh_tour_that('tour-hue'),
            'thuvien-6' => anh_tour_that('tour-mui-ne'),
            'thuvien-7' => anh_tour_that('tour-ha-giang'),
            'thuvien-8' => anh_tour_that('tour-nhat-ban-cung-duong-vang'),
        ];
        if (!empty($anh_theo_ten[$ten])) {
            return $anh_theo_ten[$ten];
        }
        // Neu admin da them anh local khong phai SVG thi dung anh do.
        foreach (['webp', 'jpg', 'jpeg', 'png'] as $duoi) {
            if (file_exists(PATH_ROOT . "assets/img/{$ten}.{$duoi}")) {
                return BASE_URL . "assets/img/{$ten}.{$duoi}";
            }
        }
        return anh_tour_that('tour-nha-trang');
    }
}

// Chọn ảnh minh hoạ mặc định theo từ khoá trong tên tour (khi tour chưa có ảnh upload)
if (!function_exists('canh_mac_dinh')) {
    function canh_mac_dinh($ten_tour = '')
    {
        $ds = [
            'halong' => ['hạ long', 'cát bà', 'lan hạ', 'du thuyền', 'cô tô'],
            'sapa'   => ['sa pa', 'sapa', 'hà giang', 'mù cang', 'fansipan', 'ninh bình', 'tràng an'],
            'hoian'  => ['hội an', 'huế', 'phong nha'],
            'danang' => ['đà nẵng', 'bà nà', 'quy nhơn'],
            'phuquoc'=> ['phú quốc', 'côn đảo', 'nha trang', 'phan thiết', 'mũi né'],
            'dalat'  => ['đà lạt', 'măng đen'],
            'mekong' => ['miền tây', 'cần thơ', 'chợ nổi', 'mekong'],
            'nhatban'=> ['nhật bản', 'tokyo', 'osaka', 'phú sĩ', 'kyoto'],
            'hanquoc'=> ['hàn quốc', 'seoul', 'jeju', 'busan'],
            'thai'   => ['thái lan', 'bangkok', 'pattaya', 'singapore', 'bali'],
        ];
        $ten = mb_strtolower($ten_tour);
        foreach ($ds as $canh => $tu_khoas) {
            foreach ($tu_khoas as $tu) {
                if ($ten !== '' && str_contains($ten, $tu)) return $canh;
            }
        }
        $tat_ca = array_keys($ds);
        return $tat_ca[abs(crc32($ten)) % count($tat_ca)];
    }
}

// Trả về đường dẫn ảnh tour: ảnh upload nếu có, nếu chưa có thì dùng ảnh minh hoạ theo điểm đến
if (!function_exists('anh_san_pham')) {
    function anh_san_pham($duong_dan_anh, $ten_tour = '')
    {
        if (!empty($duong_dan_anh)) {
            return BASE_ASSETS_UPLOADS . $duong_dan_anh;
        }

        // Anh rieng cho tung tour trong bo du lieu mau. Moi tour co mot file local khac nhau.
        $ten = mb_strtolower((string)$ten_tour, 'UTF-8');
        // Xu ly cac tour cung quoc gia/diem den nhung co chuong trinh rieng.
        if (str_contains($ten, 'phú quốc') && (str_contains($ten, 'nghỉ dưỡng') || str_contains($ten, 'resort'))) {
            return anh_tour_that('tour-phu-quoc-nghi-duong');
        }
        if (str_contains($ten, 'nhật bản') && str_contains($ten, 'cung đường vàng')) {
            return anh_tour_that('tour-nhat-ban-cung-duong-vang');
        }
        $anh_rieng = [
            'hạ long' => 'tour-ha-long',
            'sa pa' => 'tour-sa-pa', 'sapa' => 'tour-sa-pa',
            'ninh bình' => 'tour-ninh-binh', 'tràng an' => 'tour-ninh-binh',
            'hà giang' => 'tour-ha-giang', 'đồng văn' => 'tour-ha-giang',
            'cát bà' => 'tour-cat-ba', 'lan hạ' => 'tour-cat-ba',
            'đà nẵng' => 'tour-da-nang', 'bà nà' => 'tour-da-nang',
            'huế' => 'tour-hue', 'phong nha' => 'tour-hue',
            'hội an' => 'tour-hoi-an',
            'quy nhơn' => 'tour-quy-nhon', 'kỳ co' => 'tour-quy-nhon',
            'miền tây' => 'tour-mien-tay', 'cái răng' => 'tour-mien-tay',
            'măng đen' => 'tour-mang-den',
            'đà lạt' => 'tour-da-lat',
            'vinwonders' => 'tour-phu-quoc-vinwonders', 'hòn thơm' => 'tour-phu-quoc-vinwonders',
            'phú quốc' => (str_contains($ten, 'nghỉ dưỡng') || str_contains($ten, 'resort')) ? 'tour-phu-quoc-nghi-duong' : 'tour-phu-quoc-vinwonders',
            'nha trang' => 'tour-nha-trang', '4 đảo' => 'tour-nha-trang',
            'côn đảo' => 'tour-con-dao',
            'mũi né' => 'tour-mui-ne', 'phan thiết' => 'tour-mui-ne', 'đồi cát' => 'tour-mui-ne',
            'osaka' => 'tour-nhat-ban-osaka-kyoto-fuji', 'kyoto' => 'tour-nhat-ban-osaka-kyoto-fuji',
            'tokyo' => 'tour-nhat-ban-cung-duong-vang', 'nagoya' => 'tour-nhat-ban-cung-duong-vang',
            'hàn quốc' => (str_contains($ten, 'jeju') || str_contains($ten, 'busan')) ? 'tour-han-quoc-jeju' : 'tour-han-quoc-seoul-nami',
            'seoul' => 'tour-han-quoc-seoul-nami', 'nami' => 'tour-han-quoc-seoul-nami',
            'jeju' => 'tour-han-quoc-jeju', 'busan' => 'tour-han-quoc-jeju',
            'thái lan' => 'tour-thai-lan', 'bangkok' => 'tour-thai-lan', 'pattaya' => 'tour-thai-lan',
            'singapore' => 'tour-singapore-malaysia', 'malaysia' => 'tour-singapore-malaysia',
        ];
        foreach ($anh_rieng as $tu_khoa => $tep) {
            if (str_contains($ten, $tu_khoa)) {
                $anh_that = anh_tour_that($tep);
                if ($anh_that) return $anh_that;
            }
        }
        return anh_trang_tri('canh-' . canh_mac_dinh($ten_tour));
    }
}

// Sinh vài ngày khởi hành gần nhất (hiển thị "Lịch gần nhất" trên thẻ tour)
if (!function_exists('lich_gan_nhat')) {
    function lich_gan_nhat($tour_id, $so_ngay = 4)
    {
        $ngay = [];
        $bat_dau = 3 + ($tour_id * 2) % 9;
        for ($i = 0; $i < $so_ngay; $i++) {
            $ngay[] = date('d/m', strtotime('+' . ($bat_dau + $i * 6 + ($tour_id % 3)) . ' days'));
        }
        return $ngay;
    }
}

// Chuyển hướng trang
if (!function_exists('chuyen_huong')) {
    function chuyen_huong($url)
    {
        header('Location: ' . $url);
        exit;
    }
}

// Kiểm tra đã đăng nhập chưa (dùng chung cho cả admin & khách hàng)
if (!function_exists('kiem_tra_dang_nhap')) {
    function kiem_tra_dang_nhap()
    {
        if (empty($_SESSION['nguoi_dung'])) {
            dat_thong_bao('warning', 'Vui lòng đăng nhập để tiếp tục!');
            chuyen_huong(BASE_URL . '?action=dang-nhap');
        }
    }
}

// Kiểm tra quyền admin
if (!function_exists('kiem_tra_admin')) {
    function kiem_tra_admin()
    {
        kiem_tra_dang_nhap();
        if ($_SESSION['nguoi_dung']['vai_tro'] !== 'admin') {
            dat_thong_bao('danger', 'Bạn không có quyền truy cập khu vực quản trị!');
            chuyen_huong(BASE_URL);
        }
    }
}

// Định dạng tiền VNĐ
if (!function_exists('dinh_dang_tien')) {
    function dinh_dang_tien($so_tien)
    {
        return number_format($so_tien, 0, ',', '.') . ' ₫';
    }
}

// Định dạng ngày giờ Việt Nam
if (!function_exists('dinh_dang_ngay')) {
    function dinh_dang_ngay($ngay, $co_gio = true)
    {
        if (empty($ngay)) return '';
        $dt = new DateTime($ngay);
        return $dt->format($co_gio ? 'H:i d/m/Y' : 'd/m/Y');
    }
}

// Sinh mã đơn đặt tour duy nhất
if (!function_exists('tao_ma_don_hang')) {
    function tao_ma_don_hang()
    {
        return 'DH' . date('ymd') . strtoupper(substr(uniqid(), -5));
    }
}

// Hiển thị nhãn trạng thái đơn đặt tour (badge Bootstrap)
if (!function_exists('nhan_trang_thai_don_hang')) {
    function nhan_trang_thai_don_hang($trang_thai)
    {
        $ds = [
            'cho_xac_nhan' => ['Chờ xác nhận', 'secondary'],
            'da_xac_nhan'  => ['Đã xác nhận',  'info'],
            'dang_giao'    => ['Đang diễn ra',    'warning'],
            'hoan_thanh'   => ['Hoàn thành',   'success'],
            'da_huy'       => ['Đã huỷ',       'danger'],
        ];
        [$nhan, $mau] = $ds[$trang_thai] ?? ['Không xác định', 'dark'];
        return "<span class='badge bg-{$mau}'>{$nhan}</span>";
    }
}

// Hiển thị sao đánh giá (1-5)
if (!function_exists('hien_thi_sao')) {
    function hien_thi_sao($so_sao)
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            $html .= $i <= $so_sao
                ? "<i class='bi bi-star-fill text-warning'></i>"
                : "<i class='bi bi-star text-warning'></i>";
        }
        return $html;
    }
}

// Kiểm tra 1 tour đã được khách hàng hiện tại yêu thích chưa
// (chỉ query CSDL 1 lần / request rồi cache lại, dùng an toàn trong vòng lặp thẻ tour)
if (!function_exists('dang_yeu_thich')) {
    function dang_yeu_thich($san_pham_id)
    {
        static $ds_id_yeu_thich = null;

        if (empty($_SESSION['nguoi_dung'])) {
            return false;
        }

        if ($ds_id_yeu_thich === null) {
            $yeuThichModel   = new YeuThichModel();
            $ds_id_yeu_thich = $yeuThichModel->layDanhSachIdDaYeuThich($_SESSION['nguoi_dung']['id']);
        }

        return in_array($san_pham_id, $ds_id_yeu_thich);
    }
}

// Hiển thị thông báo flash
if (!function_exists('lay_thong_bao')) {
    function lay_thong_bao()
    {
        if (!empty($_SESSION['thong_bao'])) {
            $loai    = $_SESSION['thong_bao']['loai'];
            $noi_dung = $_SESSION['thong_bao']['noi_dung'];
            unset($_SESSION['thong_bao']);
            return "<div class='alert alert-{$loai} alert-dismissible fade show' role='alert'>
                        {$noi_dung}
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                    </div>";
        }
        return '';
    }
}

// Đặt thông báo flash
if (!function_exists('dat_thong_bao')) {
    function dat_thong_bao($loai, $noi_dung)
    {
        $_SESSION['thong_bao'] = ['loai' => $loai, 'noi_dung' => $noi_dung];
    }
}

// ==========================================
// TIỆN ÍCH BỔ SUNG – GIAO DIỆN MÂY VIỆT
// ==========================================

// Bỏ dấu tiếng Việt (dùng làm slug / mã voucher)
if (!function_exists('bo_dau')) {
    function bo_dau($chuoi)
    {
        $chuoi = preg_replace('/[àáạảãâầấậẩẫăằắặẳẵ]/u', 'a', $chuoi);
        $chuoi = preg_replace('/[èéẹẻẽêềếệểễ]/u', 'e', $chuoi);
        $chuoi = preg_replace('/[ìíịỉĩ]/u', 'i', $chuoi);
        $chuoi = preg_replace('/[òóọỏõôồốộổỗơờớợởỡ]/u', 'o', $chuoi);
        $chuoi = preg_replace('/[ùúụủũưừứựửữ]/u', 'u', $chuoi);
        $chuoi = preg_replace('/[ỳýỵỷỹ]/u', 'y', $chuoi);
        $chuoi = preg_replace('/đ/u', 'd', $chuoi);
        $chuoi = preg_replace('/[ÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴ]/u', 'A', $chuoi);
        $chuoi = preg_replace('/[ÈÉẸẺẼÊỀẾỆỂỄ]/u', 'E', $chuoi);
        $chuoi = preg_replace('/[ÌÍỊỈĨ]/u', 'I', $chuoi);
        $chuoi = preg_replace('/[ÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠ]/u', 'O', $chuoi);
        $chuoi = preg_replace('/[ÙÚỤỦŨƯỪỨỰỬỮ]/u', 'U', $chuoi);
        $chuoi = preg_replace('/[ỲÝỴỶỸ]/u', 'Y', $chuoi);
        $chuoi = preg_replace('/Đ/u', 'D', $chuoi);
        return $chuoi;
    }
}

// Lấy tên điểm đến từ tên tour: "Hạ Long - Du thuyền 5 sao" → "Hạ Long"
if (!function_exists('diem_den_tu_ten')) {
    function diem_den_tu_ten($ten_tour)
    {
        $phan = preg_split('/\s+[-–—]\s+/u', trim($ten_tour));
        return trim($phan[0] ?? $ten_tour);
    }
}

// Chữ cái đầu của họ tên (avatar)
if (!function_exists('chu_viet_tat')) {
    function chu_viet_tat($ho_ten)
    {
        $tu = preg_split('/\s+/u', trim($ho_ten));
        $dau = mb_substr($tu[0] ?? '', 0, 1);
        if (count($tu) > 1) $dau .= mb_substr(end($tu), 0, 1);
        return mb_strtoupper($dau);
    }
}

// Số sao & lượt đánh giá "ổn định" để hiển thị trên thẻ tour (nếu chưa có đánh giá thật)
if (!function_exists('diem_danh_gia_mau')) {
    function diem_danh_gia_mau($tour_id)
    {
        return ['sao' => number_format(4.6 + (($tour_id * 7) % 4) / 10, 1, ',', ''), 'luot' => 48 + (($tour_id * 37) % 140)];
    }
}

// Đường dẫn ảnh video nền trang chủ (nếu có file assets/video/hero.mp4 sẽ tự dùng)
if (!function_exists('video_hero')) {
    function video_hero()
    {
        return file_exists(PATH_ROOT . 'assets/video/hero.mp4') ? BASE_URL . 'assets/video/hero.mp4' : null;
    }
}

// Ẩn bớt họ tên trên bảng xếp hạng (bảo vệ riêng tư): "Nguyễn Văn An" → "Nguyễn V. An"
if (!function_exists('an_ten')) {
    function an_ten($ho_ten)
    {
        $tu = preg_split('/\s+/u', trim($ho_ten));
        if (count($tu) <= 2) return implode(' ', $tu);
        $kq = [$tu[0]];
        for ($i = 1; $i < count($tu) - 1; $i++) $kq[] = mb_substr($tu[$i], 0, 1) . '.';
        $kq[] = end($tu);
        return implode(' ', $kq);
    }
}
