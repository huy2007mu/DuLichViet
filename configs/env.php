<?php

// ==========================================
// CẤU HÌNH ĐƯỜNG DẪN VÀ KẾT NỐI DATABASE
// ==========================================

// Đường dẫn web (đổi tên thư mục nếu cần)
define('BASE_URL',              'http://localhost/DuLichViet/');

// Đường dẫn thư mục gốc
define('PATH_ROOT',             __DIR__ . '/../');

// Đường dẫn thư mục views
define('PATH_VIEW',             PATH_ROOT . 'views/');

// Đường dẫn file layout admin & layout client
define('PATH_VIEW_ADMIN',       PATH_ROOT . 'views/layout_admin.php');
define('PATH_VIEW_CLIENT',      PATH_ROOT . 'views/layout_client.php');

// Đường dẫn web tới thư mục ảnh upload
define('BASE_ASSETS_UPLOADS',   BASE_URL . 'assets/uploads/');

// Đường dẫn server tới thư mục ảnh upload
define('PATH_ASSETS_UPLOADS',   PATH_ROOT . 'assets/uploads/');

// Đường dẫn controllers
define('PATH_CONTROLLER',       PATH_ROOT . 'controllers/');

// Đường dẫn models
define('PATH_MODEL',            PATH_ROOT . 'models/');

// ==========================================
// THÔNG TIN KẾT NỐI DATABASE
// ==========================================
define('DB_HOST',       'localhost');
define('DB_PORT',       '3306');
define('DB_USERNAME',   'root');
define('DB_PASSWORD',   '');               // Đổi nếu có mật khẩu MySQL
define('DB_NAME',       'du_lich_viet');
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
