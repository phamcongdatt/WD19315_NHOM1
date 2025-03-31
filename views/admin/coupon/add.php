<?php include '../views/admin/layout/header.php'; ?>

<div class="page-content">
    <!-- Start Container Fluid -->
    <div class="container-xxl">
        <form action="?act=coupon-create" method="post">
            <div class="row">
                <!-- Cột bên trái -->
                <div class="col-lg-5">
                    <!-- Trạng thái -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Trạng thái phiếu giảm giá</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="active" id="active-status" checked>
                                        <label class="form-check-label" for="active-status">Hiển thị</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="hidden" id="hidden-status">
                                        <label class="form-check-label" for="hidden-status">Ẩn đi</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="future plan" id="future-plan-status">
                                        <label class="form-check-label" for="future-plan-status">Chờ kích hoạt</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Hiển thị lỗi cho 'status' -->
                            <?php if (isset($_SESSION['errors']['status'])): ?>
                                <p class="text-danger"><?= $_SESSION['errors']['status'] ?></p>
                            <?php endif; ?>
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
                                <input type="date" id="start-date" class="form-control" name="start_date" placeholder="dd-mm-yyyy">
                            </div>
                            <!-- Hiển thị lỗi cho 'start_date' -->
                            <?php if (isset($_SESSION['errors']['start_date'])): ?>
                                <p class="text-danger"><?= $_SESSION['errors']['start_date'] ?></p>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="end-date" class="form-label text-dark">Ngày kết thúc</label>
                                <input type="date" id="end-date" class="form-control" name="end_date" placeholder="dd-mm-yyyy">
                            </div>
                            <!-- Hiển thị lỗi cho 'end_date' -->
                            <?php if (isset($_SESSION['errors']['end_date'])): ?>
                                <p class="text-danger"><?= $_SESSION['errors']['end_date'] ?></p>
                            <?php endif; ?>
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
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên mã giảm giá">
                                    </div>
                                    <!-- Hiển thị lỗi cho 'name' -->
                                    <?php if (isset($_SESSION['errors']['name'])): ?>
                                        <p class="text-danger"><?= $_SESSION['errors']['name'] ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="coupon-code" class="form-label">Mã phiếu giảm giá</label>
                                        <input type="text" id="coupon-code" name="coupon_code" class="form-control" placeholder="Nhập mã phiếu giảm giá">
                                    </div>
                                    <!-- Hiển thị lỗi cho 'coupon_code' -->
                                    <?php if (isset($_SESSION['errors']['coupon_code'])): ?>
                                        <p class="text-danger"><?= $_SESSION['errors']['coupon_code'] ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Số lượng</label>
                                        <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Nhập số lượng">
                                    </div>
                                    <!-- Hiển thị lỗi cho 'quantity' -->
                                    <?php if (isset($_SESSION['errors']['quantity'])): ?>
                                        <p class="text-danger"><?= $_SESSION['errors']['quantity'] ?></p>
                                    <?php endif; ?>
                                </div>

                                <!-- Loại coupon -->
                                <h4 class="card-title mb-3 mt-2">Loại phiếu giảm giá</h4>
                                <div class="row mb-3">
                                    <!-- Hiển thị lỗi cho 'type' -->
                                    <?php if (isset($_SESSION['errors']['type'])): ?>
                                        <p class="text-danger"><?= $_SESSION['errors']['type'] ?></p>
                                    <?php endif; ?>

                                    <div class="col-lg-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" value="Fercentage" id="fixed-amount">
                                            <label class="form-check-label" for="fixed-amount">Giảm phần trăm</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" value="Fixed Amount" id="fixed-amount">
                                            <label class="form-check-label" for="fixed-amount">số tiền cố định</label>
                                        </div>
                                        </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-lg-12">
                                            <div class="">
                                                <label for="discount-value" class="form-label">giá trị giảm</label>
                                                <input type="text" name="coupon_value" id="discount-value" class="form-control" placeholder="Nhập giá trị giảm">
                                            </div>
                                        </div>

                                        </div>
                                <!-- Hiển thị lỗi cho 'coupon_value' -->
                                <?php if (isset($_SESSION['errors']['coupon_value'])): ?>
                                    <p class="text-danger"><?= $_SESSION['errors']['coupon_value'] ?></p>
                                <?php endif; ?>

                                <div class="card-footer border-top">
                                    <button type="submit" name="coupon-create" class="btn btn-primary">Tạo phiếu giảm giá</button>
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
<?php 
unset($_SESSION['errors']);
include '../views/admin/layout/footer.php'; ?>
