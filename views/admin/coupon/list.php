<?php include '../views/admin/layout/header.php'; ?>

<div class="page-content">
    <!-- Start Container Fluid -->
    <div class="container-xxl">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">Danh sách mã giảm giá</h4>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light rounded"
                                data-bs-toggle="dropdown">This Month</a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#!" class="dropdown-item">Download</a>
                                <a href="#!" class="dropdown-item">Export</a>
                                <a href="#!" class="dropdown-item">Import</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck1">
                                        </div>
                                    </th>
                                    <th>Tên mã giảm giá</th>
                                    <th>Giảm giá</th>
                                    <th>Mã phiếu giảm giá</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listCoupons as $coupon): ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck2">
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($coupon['name']) ?></td>
                                        <td><?= $coupon['type'] == 'Percentage' ? $coupon['coupon_value'] . '%' : number_format($coupon['coupon_value'] * 1000, 0, ',', '.') . ' đ' ?>
                                        </td>
                                        <td><?= htmlspecialchars($coupon['coupon_code']) ?></td>
                                        <td><?= htmlspecialchars($coupon['start_date']) ?></td>
                                        <td><?= htmlspecialchars($coupon['end_date']) ?></td>

                                        <td>
                                            <?php if ($coupon['status'] == 'active'): ?>
                                                <span class="badge text-success bg-success-subtle fs-12"><i
                                                        class="bx bx-check-double"></i> Active</span>
                                            <?php elseif ($coupon['status'] == 'hidden'): ?>
                                                <span class="badge text-danger bg-danger-subtle fs-12"><i
                                                        class="bx bx-hide"></i> Hidden</span>
                                            <?php else: ?>
                                                <span class="badge text-muted bg-muted-subtle fs-12"><i class="bx bx-error"></i>
                                                    Future</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="?act=coupon-detail&coupon_id=<?= $coupon['coupon_Id'] ?>"
                                                    class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>
                                                <a href="?act=coupon-edit&coupon_id=<?= $coupon['coupon_Id'] ?>"
                                                    class="btn btn-soft-primary btn-sm"><iconify-icon
                                                        icon="solar:pen-2-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>
                                                <a onclick="return confirm('Bạn chắc chắn muốn xóa mã giảm giá này không ?')" href="?act=coupon-delete&coupon_id=<?= $coupon['coupon_Id'] ?>"
                                                    class="btn btn-soft-danger btn-sm"><iconify-icon
                                                        icon="solar:trash-bin-minimalistic-2-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer border-top">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item"><a class="page-link" href="#">Trước</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">Tiếp</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../views/admin/layout/footer.php'; ?>