<?php
require_once "../models/Cart.php";
require_once "../models/Ship.php";
require_once "../models/User.php";
require_once "../models/Order.php";

class OrderController
{
    protected $cart;
    protected $ship;
    protected $user;
    protected $order;
    public function __construct()
    {
        $this->cart = new Cart();
        $this->ship = new Ship();
        $this->user = new User();
        $this->order = new Order();
    }

    public function index()
    {
        $user = $this->user->getUserById($_SESSION['user']['user_Id']);
        $carts = $this->cart->getAllCart();
        $ships = $this->ship->getAllShip();
        // echo '<pre>';
        // print_r($carts);
        // echo '</pre>';
        include '../views/client/check/checkout.php';
    }
    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';
            // Lấy danh sách sản phẩm trong giỏ hàng
            $carts = $this->cart->getAllCart();
            // echo '<pre>';
            // print_r($carts);
            // echo '</pre>';
            // Thêm chi tiết đơn hàng vào bảng order_details
            $orderDetail = $this->order->addOrderDetail(
                $_POST['name'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['address'],
                $_POST['amount'],
                $_POST['note'],
                $_POST['shipping_id'],
                $_POST['coupon_id'],
                $_POST['payment_method']
            );

            // Nếu thêm thành công, lấy ID của chi tiết đơn hàng
            if ($orderDetail) {
                $orderDetail_id = $this->order->getLastInsertId();
                // Duyệt qua từng sản phẩm trong giỏ hàng để thêm vào bảng orders
                foreach ($carts as $cart) {
                    // Thêm đơn hàng vào bảng orders
                    $this->order->addOrder(
                        $cart['product_id'],
                        $cart['variant_id'],
                        $cart['quantity'],
                        $orderDetail_id
                    );

                    // Xóa sản phẩm khỏi giỏ hàng sau khi đã thêm vào đơn hàng
                    $this->cart->deleteCart($cart['cart_id']);
                }

                // Xóa các session liên quan đến giỏ hàng và thông tin đơn hàng
                unset($_SESSION['total']);
                unset($_SESSION['coupon']);
                unset($_SESSION['totalCoupon']);

                // Đưa người dùng về trang chủ và hiển thị thông báo thành công
                $_SESSION['success'] = 'Đặt hàng thành công';
                header("Location: index.php");
                exit();
            } else {
                // Nếu không thành công, hiển thị thông báo lỗi và quay lại trang checkout
                $_SESSION['error'] = 'Đặt hàng không thành công';
                header("Location: /checkout");
                exit();
            }
        }
    }
}
