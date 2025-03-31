<?php include '../views/client/layout/header.php'; ?>

<!-- header area end -->
<main>
    <!-- breadcrumb area start -->
    <section class="breadcrumb__area include-bg pt-95 pb-50" data-bg-color="#EFF1F5">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="breadcrumb__content p-relative z-index-1">
                        <h3 class="breadcrumb__title">Thanh toán</h3>
                        <div class="breadcrumb__list">
                            <span><a href="index.php">Trang chủ</a></span>
                            <span>Thanh toán</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->
    <form action="?act=order" method="post">
        <section class="tp-checkout-area pb-120" data-bg-color="#EFF1F5">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-7">
                        <!-- Có thể là giỏ hàng hoặc các thông tin khác -->
                    </div>
                    <div class="col-lg-7">
                        <div class="tp-checkout-bill-area">
                            <h3 class="tp-checkout-bill-title">Chi tiết thanh toán</h3>

                            <div class="tp-checkout-bill-form">
                                <div class="tp-checkout-bill-inner">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="tp-checkout-input">
                                                <label>Tên<span>*</span></label>
                                                <input type="text" name="name" value="<?= $user['name'] ?>" placeholder="Nhập tên của bạn" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="tp-checkout-input">
                                                <label>Địa chỉ</label>
                                                <input type="text" name="address" value="<?= $user['address'] ?>" placeholder="House number and street name" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="tp-checkout-input">
                                                <label>Điện thoại<span>*</span></label>
                                                <input type="text" name="phone" value="<?= $user['phone'] ?>" placeholder="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="tp-checkout-input">
                                                <label>Địa chỉ Email<span>*</span></label>
                                                <input type="email" name="email" value="<?= $user['email'] ?>" placeholder="email" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="tp-checkout-input">
                                                <label>Ghi chú đơn hàng (tùy chọn)</label>
                                                <textarea name="note" placeholder="Ghi chú về đơn đặt hàng của bạn, ví dụ: ghi chú đặc biệt để giao hàng."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <!-- checkout place order -->
                        <div class="tp-checkout-place white-bg">
                            <h3 class="tp-checkout-place-title">Đơn hàng của bạn</h3>

                            <div class="tp-order-info-list">
                                <ul>
                                    <li class="tp-order-info-list-header">
                                        <h4>Sản phẩm</h4>
                                        <h4>Tổng cộng</h4>
                                    </li>

                                    <!-- Item list -->
                                    <?php foreach ($carts as $cart): ?>
                                        <li class="tp-order-info-list-desc">
                                            <p><?= $cart['product_name'] ?><span> x <?= $cart['quantity'] ?></span></p>
                                            <span><?= number_format($cart['product_variant_sale_price'] * 1000) ?></span>
                                        </li>
                                    <?php endforeach; ?>

                                    <!-- Subtotal -->
                                    <li class="tp-order-info-list-subtotal">
                                        <span>Tổng phụ</span>
                                        <input type="hidden" name="amount" value="<?= $_SESSION['total'] ?>">
                                        <span><?= number_format($_SESSION['total'] * 1000) ?>đ</span>
                                    </li>

                                    <!-- Coupon -->
                                    <?php if (isset($_SESSION['coupon'])): ?>
                                        <li class="tp-order-info-list-subtotal">
                                            <input type="hidden" name="coupon_id" value="<?= $_SESSION['coupon']['coupon_Id'] ?>">
                                            <span>Mã giảm giá</span>
                                            <span>- <?= number_format($_SESSION['totalCoupon'] * 1000) ?>đ</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Shipping -->
                                    <li class="tp-order-info-list-shipping">
                                        <span>Vận chuyển</span>
                                        <div class="tp-order-info-list-shipping-item d-flex flex-column align-items-end">
                                            <?php foreach ($ships as $key => $ship): ?>
                                                <span>
                                                    <input id="flat_rate-<?= $key + 1 ?>" type="radio" name="shipping_id" value="<?= $ship['ship_Id'] ?>" required>
                                                    <label for="flat_rate-<?= $key + 1 ?>"><?= $ship['shipping_name'] ?>: <span><?= number_format($ship['shipping_price']) ?>đ</span></label>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </li>

                                    <!-- Total -->
                                    <?php if (isset($_SESSION['coupon'])): ?>
                                        <li class="tp-order-info-list-total">
                                            <span>Tổng cộng</span>
                                            <span><?= number_format(($_SESSION['total'] - $_SESSION['totalCoupon']) * 1000) ?>đ</span>
                                        </li>
                                    <?php else: ?>
                                        <li class="tp-order-info-list-total">
                                            <span>Tổng cộng</span>
                                            <span><?= number_format($_SESSION['total'] * 1000) ?>đ</span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <div class="tp-checkout-payment">
                                <div class="tp-checkout-payment-item">
                                    <input type="radio" id="cod" name="payment_method" value="cod" required>
                                    <label for="cod">Thanh toán tiền mặt khi giao hàng</label>
                                    <div class="tp-checkout-payment-desc cash-on-delivery">
                                        <p>Thanh toán trực tiếp khi nhận hàng. Đơn đặt hàng của bạn sẽ được vận chuyển sau khi nhận được thanh toán.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="tp-checkout-agree">
                                <div class="tp-checkout-option">
                                    <input id="read_all" type="checkbox" required>
                                    <label for="read_all">Tôi đã đọc và đồng ý với các điều khoản và điều kiện của trang web.</label>
                                </div>
                            </div>

                            <div class="tp-checkout-btn-wrapper">
                                <button type="submit" name="checkout" class="tp-checkout-btn w-100">Đặt hàng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
</main>

<!-- footer area start -->
<?php include '../views/client/layout/footer.php'; ?>