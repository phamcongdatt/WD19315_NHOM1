<?php include '../views/admin/layout/header.php'; ?>

<div class="page-content">
    <!-- Start Container Fluid -->
    <div class="container-xxl">
        <form action="?act=coupon-update&coupon_id=<?= htmlspecialchars($coupon['coupon_Id'] ?? '') ?>" method="post">

            <div class="row">
                <!-- Cột bên trái -->
                <div class="col-lg-5">
                    <!-- Trạng thái -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Trạng thái mã giảm giá</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="active" id="active-status"
                                            <?= $coupon['status'] === 'active' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="active-status">Hiển thị</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="hidden" id="hidden-status"
                                            <?= $coupon['status'] === 'hidden' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="hidden-status">Ẩn đi</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="future plan" id="future-plan-status"
                                            <?= $coupon['status'] === 'future plan' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="future-plan-status">Chờ kích hoạt</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thời gian -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Lịch trình ngày</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="start-date" class="form-label text-dark">Ngày bắt đầu</label>
                                <input type="date" id="start-date" class="form-control" name="start_date" value="<?= htmlspecialchars($coupon['start_date']) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="end-date" class="form-label text-dark">Ngày kết thúc</label>
                                <input type="date" id="end-date" class="form-control" name="end_date" value="<?= htmlspecialchars($coupon['end_date']) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cột bên phải -->
                <div class="col-lg-7">
                    <!-- Thông tin coupon -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thông tin mã giảm giá</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Tên mã giảm giá</label>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter coupon name" value="<?= htmlspecialchars($coupon['name']) ?>">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="coupon-code" class="form-label">Mã phiếu giảm giá</label>
                                        <input type="text" id="coupon-code" name="coupon_code" class="form-control" placeholder="Enter coupon code" value="<?= htmlspecialchars($coupon['coupon_code']) ?>">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Số lượng</label>
                                        <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Enter quantity" value="<?= htmlspecialchars($coupon['quantity']) ?>">
                                    </div>
                                </div>

                                <!-- Loại coupon -->
                                <h4 class="card-title mb-3 mt-2">Loại mã giảm giá</h4>
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" value="Fercentage" id="fercentage"
                                                <?= $coupon['type'] === 'Fercentage' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="fercentage">Giảm phần trăm</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" value="Fixed Amount" id="fixed-amount"
                                                <?= $coupon['type'] === 'Fixed Amount' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="fixed-amount">số tiền cố định</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="coupon_value" class="form-label">Giá trị phiếu giảm giá</label>
                                    <input type="number" id="coupon_value" name="coupon_value" class="form-control" placeholder="Enter coupon value" value="<?= htmlspecialchars($coupon['coupon_value']) ?>">
                                </div>

                                <div class="card-footer border-top">
                                    <button type="submit" name="coupon-update" class="btn btn-primary">Cập nhật mã giảm giá</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- End Container Fluid -->
</div>

<?php include '../views/admin/layout/footer.php'; ?>