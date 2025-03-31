<?php
session_start();
require_once '../controllers/admin/CategoryAdminController.php';
require_once '../controllers/admin/ProductAdminController.php';
require_once '../controllers/admin/CouponAdminController.php';
require_once '../controllers/client/HomeController.php';
require_once '../controllers/client/AuthController.php';
require_once '../controllers/client/ProfileController.php';
require_once '../controllers/client/CartController.php';
require_once '../controllers/client/OrderController.php';
require_once '../controllers/client/ShopController.php';


$action = isset($_GET['act']) ? $_GET['act'] : 'index';

// =========================Admin=============================
$categoryAdmin = new CategoryAdminController();
$productAdmin = new ProductAdminController();
$couponAdmin = new CouponAdminController();
// =========================Client=============================
$home = new HomeController();
$auth = new AuthController();
$profile = new ProfileController();
$cart = new CartController();
$order = new OrderController();
$shop = new ShopController();
switch ($action) {
    case 'admin':
        include '../views/admin/index.php';
        break;
    case 'product':
        $productAdmin->index();
        // include '../views/admin/product/list.php';
        break;
    case 'product-create':
        $productAdmin->create();
        // include '../views/admin/product/create.php';
        break;
    case 'product-store':
        $productAdmin->store();
        break;
    case 'product-edit':
        $productAdmin->edit();
        // include '../views/admin/product/edit.php';
        break;
    case 'product-update':
        $productAdmin->update();
        break;
    case 'gallery-delete':
        $productAdmin->deleteGallery();
        break;
    case 'product-variant-delete':
        $productAdmin->deleteProductVariant();
        break;
    case 'product-delete':
        $productAdmin->deleteProduct();
        break;
    case 'category':
        $categoryAdmin->index();
        // include '../views/admin/category/list.php';
        break;
    case 'category-create':
        $categoryAdmin->addCategory();
        // include '../views/admin/category/create.php';
        break;
    case 'category-edit':
        $categoryAdmin->updateCategory();
        // include '../views/admin/category/edit.php';
        break;
    case 'category-delete':
        $categoryAdmin->deleteCategory();
        break;
    case 'coupon':
        $couponAdmin->index();
        break;
    case 'coupon-create':
        $couponAdmin->create();
        break;
    case 'coupon-edit':
        $couponAdmin->edit();
        break;
    case 'coupon-update':
        $couponAdmin->update();
        break;
    case 'coupon-delete':
        $couponAdmin->delete();
        break;




        // ====================================================================================
    case 'index':
        $home->index();
        // include '../views/client/index.php';
        break;
    case 'login':
        $auth->signin();
        // include '../views/client/auth/login.php';
        break;
    case 'register':
        $auth->registers();
        // include '../views/client/auth/register.php';
        break;
    case 'product-detail':
        $home->getProductDetail();
        break;
    case 'profile':
        include '../views/client/profile/profile.php';
        break;
    case 'update-profile':
        $profile->updateProfile();
        break;
    case 'cart':
        $cart->index();
        break;
    case 'addToCart-byNow':
        $cart->addToCartOrByNow();
        break;
    case 'update-cart':
        $cart->update();
        break;
    case 'delete-cart':
        $cart->delete();
        break;
    case 'checkout':
        $order->index();
        break;
    case 'order':
        $order->checkout();
        break;
    case 'shop':
        $shop->index();
        break;
}
