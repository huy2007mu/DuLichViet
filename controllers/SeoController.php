<?php

class SeoController
{
    // sitemap.xml cho công cụ tìm kiếm (SEO): ?action=sitemap
    public function sitemap()
    {
        header('Content-Type: application/xml; charset=utf-8');
        $urls = [BASE_URL, BASE_URL . '?action=san-pham', BASE_URL . '?action=cam-nang', BASE_URL . '?action=hang-thanh-vien', BASE_URL . '?action=choi-thu'];
        foreach ((new SanPhamModel())->timKiemLoc([]) as $sp) {
            $urls[] = BASE_URL . '?action=san-pham-chi-tiet&id=' . $sp['id'];
        }
        foreach (require PATH_ROOT . 'configs/cam_nang.php' as $b) {
            $urls[] = BASE_URL . '?action=cam-nang-chi-tiet&bai=' . $b['slug'];
        }
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            echo '  <url><loc>' . htmlspecialchars($u, ENT_XML1) . '</loc></url>' . "\n";
        }
        echo '</urlset>';
    }
}
