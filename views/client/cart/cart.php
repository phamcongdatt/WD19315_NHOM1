<?php include '../views/client/layout/header.php'; ?>
<main>

   <!-- // breadcrumb area start -->
    <section class="breadcrumb__area include-bg pt-95 pb-50">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="breadcrumb__content p-relative z-index-1">
                        <h3 class="breadcrumb__title">Giỏ hàng sản phẩm</h3>
                        <div class="breadcrumb__list">
                            <span><a href="index.php">Trang chủ</a></span>
                            <span>Giỏ hàng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->

    <!-- cart area start -->
    <section class="tp-cart-area pb-120">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <form action="?act=update-cart" method="post">
                        <div class="tp-cart-list mb-25 mr-30">
                            <table class="table">
                                <thead>
                                    <tr style="background-color: #b5b8b8">
                                        <th colspan="2" class="tp-cart-header-product">Tên sản phẩm</th>
                                        <th class="tp-cart-header-price">Giá sản phẩm</th>
                                        <th class="tp-cart-header-price">Màu sắc</th>
                                        <th class="tp-cart-header-price">Dung lượng</th>
                                        <th class="tp-cart-header-quantity">Số lượng</th>
                                        <th class="tp-cart-header-price">Tổng tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sum = 0; // Tổng tiền trước giảm giá
                                    foreach ($carts as $cart): 
                                        $totalPrice = $cart['product_variant_sale_price'] * $cart['quantity'];
                                        $sum += $totalPrice;
                                    ?>
                                    <tr>
                                        <!-- img -->
                                        <td class="tp-cart-img"><a href="product-details.html"> <img
                                                    src="./images/product/<?=$cart['product_image']?>" alt=""></a></td>
                                        <!-- title -->
                                        <td class="tp-cart-title"><a
                                                href="product-details.html"><?= strlen($cart['product_name']) > 20 ? substr($cart['product_name'], 0, 20) . '...' : $cart['product_name'] ?></a>
                                        </td>
                                        <!-- price -->
                                        <td class="tp-cart-price">
                                            <span><?= number_format($cart['product_variant_sale_price'] * 1000) ?> VNĐ</span>
                                        </td>
                                        <td><?= $cart['variant_color_name'] ?></td>
                                        <td><?= $cart['variant_size_name'] ?></td>
                                        <!-- quantity -->
                                        <td class="tp-cart-quantity">
                                            <div class="tp-product-quantity mt-10 mb-10">
                                                <span class="tp-cart-minus">
                                                    <svg width="10" height="2" viewBox="0 0 10 2" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M1 1H9" stroke="currentColor" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                                <input class="tp-cart-input" name="quantity[<?=$cart['cart_id'] ?>]" type="text" value="<?=$cart['quantity'] ?>">
                                                <span class="tp-cart-plus">
                                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M5 1V9" stroke="currentColor" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M1 5H9" stroke="currentColor" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </td>
                                        <!-- action -->
                                        <td class="tp-cart-price">
                                            <span><?= number_format($totalPrice * 1000) ?> VNĐ</span>
                                        </td>
                                        <td class="tp-cart-action">
                                            <a href="?act=delete-cart&cart_id=<?= $cart['cart_id'] ?>" onclick="return confirm ('Bạn có muốn xóa sản phẩm này khỏi giỏ hàng không')";
                                                class="tp-cart-action-btn">
                                                <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M9.53033 1.53033C9.82322 1.23744 9.82322 0.762563 9.53033 0.46967C9.23744 0.176777 8.76256 0.176777 8.46967 0.46967L5 3.93934L1.53033 0.46967C1.23744 0.176777 0.762563 0.176777 0.46967 0.46967C0.176777 0.762563 0.176777 1.23744 0.46967 1.53033L3.93934 5L0.46967 8.46967C0.176777 8.76256 0.176777 9.23744 0.46967 9.53033C0.762563 9.82322 1.23744 9.82322 1.53033 9.53033L5 6.06066L8.46967 9.53033C8.76256 9.82322 9.23744 9.82322 9.53033 9.53033C9.82322 9.23744 9.82322 8.76256 9.53033 8.46967L6.06066 5L9.53033 1.53033Z"
                                                        fill="currentColor" />
                                                </svg>
                                                <span>Xóa</span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tp-cart-bottom">
                            <div class="row align-items-end">
                                <div class="col-xl-6 col-md-8">
                                    <div class="tp-cart-coupon">
                                        <form action="#">
                                            <div class="tp-cart-coupon-input-box">
                                                <label>Mã giảm giá:</label>
                                                <div class="tp-cart-coupon-input d-flex align-items-center">
                                                    <input type="text" name="coupon_code" placeholder="Vui lòng nhập mã giảm giá">
                                                    <button type="submit" name="apply_coupon">Áp dụng</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-4">
                                    <div class="tp-cart-update text-md-end">
                                        <button type="submit" name="update-cart" class="tp-cart-update-btn">Cập nhật</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="tp-cart-checkout-wrapper">
                        <div class="tp-cart-checkout-top d-flex align-items-center justify-content-between">
                            <span class="tp-cart-checkout-top-title">Tổng tiền sản phẩm: </span>
                            <span class="tp-cart-checkout-top-price"><?= number_format($sum * 1000) ?> VNĐ</span>
                        </div>
                        <div class="tp-cart-checkout-top d-flex align-items-center justify-content-between">
                            <span class="tp-cart-checkout-top-title">Mã giảm giá: </span>
                            <span class="tp-cart-checkout-top-price"> - <?= number_format(($_SESSION['totalCoupon']?? 0) * 1000) ?> VNĐ</span>
                        </div>
                        <div class="tp-cart-checkout-total d-flex align-items-center justify-content-between">
                            <span>Tổng cộng: </span>
                            <span><?= number_format(($sum * 1000) - (($_SESSION['totalCoupon']??0) * 1000)) ?> VNĐ</span>
                        </div>
                        <div class="tp-cart-checkout-proceed">
                            <a href="?act=checkout" class="tp-cart-checkout-btn w-100">Tiến hành thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- cart area end -->

</main>
<?php include '../views/client/layout/footer.php'; ?>
