<?php

class CamNangController
{
    private function layBai()
    {
        return require PATH_ROOT . 'configs/cam_nang.php';
    }

    public function danhSach()
    {
        $danh_sach_bai = $this->layBai();
        $title = 'Cẩm nang du lịch Việt Nam – kinh nghiệm, lịch trình, mẹo hay';
        $meta_mo_ta = 'Cẩm nang du lịch Mây Việt: kinh nghiệm đi Phú Quốc, Sa Pa, Hạ Long, Hội An – Đà Nẵng, lịch trình chi tiết và mẹo săn tour giá tốt.';
        $view = 'client/cam_nang_danh_sach';
        require_once PATH_VIEW_CLIENT;
    }

    public function chiTiet()
    {
        $slug = $_GET['bai'] ?? '';
        $ds = $this->layBai();
        $bai = null;
        foreach ($ds as $b) { if ($b['slug'] === $slug) { $bai = $b; break; } }
        if (!$bai) {
            dat_thong_bao('warning', 'Bài viết không tồn tại.');
            chuyen_huong(BASE_URL . '?action=cam-nang');
        }
        $bai_lien_quan = array_values(array_filter($ds, fn($b) => $b['slug'] !== $slug));
        $bai_lien_quan = array_slice($bai_lien_quan, 0, 3);

        $title = $bai['tieu_de'];
        $meta_mo_ta = $bai['mo_ta'];
        $meta_anh = anh_trang_tri($bai['anh']);
        $json_ld = [
            '@context' => 'https://schema.org', '@type' => 'Article',
            'headline' => $bai['tieu_de'], 'description' => $bai['mo_ta'], 'image' => $meta_anh,
            'author' => ['@type' => 'Organization', 'name' => 'Mây Việt'],
            'publisher' => ['@type' => 'Organization', 'name' => 'Mây Việt'],
        ];
        $view = 'client/cam_nang_chi_tiet';
        require_once PATH_VIEW_CLIENT;
    }
}
