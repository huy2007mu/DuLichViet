<?php

// Khởi động session
session_start();

// Tự động load class Model và Controller
spl_autoload_register(function ($ten_class) {
    $ten_file = "$ten_class.php";

    $duong_dan_model      = PATH_MODEL . $ten_file;
    $duong_dan_controller = PATH_CONTROLLER . $ten_file;

    if (is_readable($duong_dan_model)) {
        require_once $duong_dan_model;
    } elseif (is_readable($duong_dan_controller)) {
        require_once $duong_dan_controller;
    }
});

// Load cấu hình
require_once './configs/env.php';
require_once './configs/helper.php';

// Điều hướng URL
require_once './routes/index.php';
