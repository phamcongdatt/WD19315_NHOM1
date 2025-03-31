<?php
require_once '../models/Cart.php';
class CartController extends Cart
{
    public function index()
    {
        $carts = $this->getAllCart();
        $sum = 0;
        foreach ($carts as $cart) {
            $sum += $cart['product_variant_sale_price'] * $cart['quantity'];
        }
        // echo '<pre>';
        // print_r($sum);
        // echo '<pre>';
        $_SESSION['total'] = $sum;
        include "../views/client/cart/cart.php";
    }
    public function addToCartOrByNow()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
            if (empty($_POST['variant_id'])) {
                $_SESSION['error'] = 'Vui lòng chọn màu sác và kính thước sản phẩm';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }
            echo '<pre>';
            print_r($_POST);
            echo '<pre>';

            $checkCart = $this->checkCart();
            if ($checkCart) {
                $quantity = $checkCart['quantity'] + $_POST['quantity'];
                $updateCart = $this->updateCart(
                    $_SESSION['user']['user_Id'],
                    $_POST['product_id'],
                    $_POST['variant_id'],
                    $quantity
                );
                $_SESSION['success'] = "Cập nhật giỏ hàng thành công";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            } else {
                $addToCart = $this->addToCart(
                    $_SESSION['user']['user_Id'],
                    $_POST['product_id'],
                    $_POST['variant_id'],
                    $_POST['quantity']
                );
                $_SESSION['success'] = "Thêm vào giỏ hàng thành công";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buy_now'])) {
            if (empty($_POST['variant_id'])) {
                $_SESSION['error'] = 'Vui lòng chọn màu sác và kính thước sản phẩm';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }
            $checkCart = $this->checkCart();
            if ($checkCart) {
                $quantity = $checkCart['quantity'] + $_POST['quantity'];
                $updateCart = $this->updateCart(
                    $_SESSION['user']['user_Id'],
                    $_POST['product_id'],
                    $_POST['variant_id'],
                    $quantity
                );
                // $_SESSION['success'] = "Cập nhật giỏ hàng thành công";
                header('Location:?act=cart');
                exit();
            } else {
                $addToCart = $this->addToCart(
                    $_SESSION['user']['user_Id'],
                    $_POST['product_id'],
                    $_POST['variant_id'],
                    $_POST['quantity']
                );
                // $_SESSION['success'] = "Thêm vào giỏ hàng thành công";
                header('Location:?act=cart');
                exit();
            }
        }
    }
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update-cart'])) {
            if (isset($_POST['quantity'])) {
                foreach ($_POST['quantity'] as $cart_id => $quantity) {
                    //gọi phương thức Cập nhật giỏ hàng và thêm cố cart_id và quantity
                    $this->updateCartById($cart_id, $quantity);
                }
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                $_SESSION['success'] = "Cập nhật giỏ hàng thành công";
                exit();
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_coupon'])) {
            // Lấy coupon từ mã giảm giá
            $coupon = $this->getCouponByCode($_POST['coupon_code']);
            // echo '<pre>';
            // print_r($coupon);
            // echo '<pre>';
            if (!$coupon) {
                $_SESSION['error'] = 'Mã giảm giá không tồn tại.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }

            // Kiểm tra nếu đã có coupon trong session
            if (isset($_SESSION['coupon'])) {
                // echo '<pre>';
                // print_r($_SESSION['totalCoupon']);
                // echo '<pre>';
                $_SESSION['error'] = 'Chỉ được sử dụng 1 mã giảm giá cho 1 đơn hàng.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }

            // Nếu coupon hợp lệ, lưu vào session
            if ($coupon) {
                $_SESSION['coupon'] = $coupon;
                $totalCoupon = $this->handleCoupon($coupon, $_SESSION['total']);
                $_SESSION['totalCoupon'] = $totalCoupon;
                $_SESSION['success'] = 'Áp dụng mã giảm giá thành công.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }
        }
    }
    public function delete()
    {
        if (isset($_GET['cart_id'])) {
            try {
                $this->deleteCart($_GET['cart_id']);
                $_SESSION['success'] = 'Xóa sản phẩm trong giỏ hàng thành công';
            } catch (\Throwable $th) {
                $_SESSION['error'] = 'Xóa sản phẩm trong giỏ hàng thất bại. Vui lòng thử lại.';
            }
        } else {
            $_SESSION['error'] = 'Mã giỏ hàng không hợp lệ.';
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
    public function handleCoupon($coupon, $total): mixed
    {
        if ($coupon['type'] == 'Fercentage') {
            $totalCoupon = ($total * ($coupon['coupon_value']) / 100);
        } elseif ($coupon['type'] == 'Fixed Amount') {
            $totalCoupon = $coupon['coupon_value'];
        }

        return $totalCoupon;
    }
}
