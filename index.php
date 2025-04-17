<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../controllers/ProfileController.php';

$controller = new ProfileController();
$act = $_GET['act'] ?? '';

error_log("Action requested: $act"); // Log hành động

switch ($act) {
    case 'profile':
        $controller->showProfile();
        break;
    case 'update-profile':
        $controller->updateProfile();
        break;
    case 'update-password':
        $controller->updatePassword();
        break;
    case 'login':
        // Thêm logic cho login nếu cần
        echo "Login page";
        break;
    case 'logout':
        session_destroy();
        header('Location: index.php?act=login');
        exit();
    default:
        echo "Trang không tồn tại";
        break;
}