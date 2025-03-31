<?php
require_once "../Connect/connect.php";
require_once "../models/Coupon.php";

class CouponAdminController extends Coupon
{
    public function index()
    {
        $listCoupons = $this->listCoupon();
        include "../views/admin/coupon/list.php";
    }

    public function create()
    {

        $errors = [];
        // Kiểm tra xem yêu cầu có phải là POST và form có dữ liệu từ 'coupon-create'
        if (isset($_SERVER['REQUEST_METHOD']) == 'POST' && isset($_POST['coupon-create'])) {

            if (empty($_POST['name'])) {
                $errors['name'] = 'Vui lòng nhập tên mã giảm giá';
            }
            if (empty($_POST['status'])) {
                $errors['status'] = 'Vui lòng nhập status';
            }
            if (empty($_POST['quantity'])) {
                $errors['quantity'] = 'Vui lòng nhập số lượng';
            }
            if (empty($_POST['coupon_value'])) {
                $errors['coupon_value'] = 'Vui lòng nhập coupon_value';
            }
            if (empty($_POST['type'])) {
                $errors['type'] = 'Vui lòng chọn type';
            }
            if (empty($_POST['coupon_code'])) {
                $errors['coupon_code'] = 'Vui lòng chọn coupon_code';
            }

            // Kiểm tra ngày bắt đầu (start_date)

            $startDate = new DateTime($_POST['start_date'] ?? '2021-01-01');
            if (empty($_POST['start_date']) || $startDate < date('Y-m-d')) {
                $errors['start_date'] = 'Vui lòng chọn ngày bắt đầu và ngày bắt đầu phải lớn hơn hoặc bằng hiện tại';
            }

            $endDate = new DateTime($_POST['end_date'] ?? '2021-01-01');
            // Kiểm tra ngày kết thúc (end_date)
            if (empty($_POST['end_date']) || $endDate < $startDate) {
                $errors['end_date'] = 'Vui lòng chọn ngày kết thúc và ngày kết thúc phải lớn hơn ngày bắt đầu';
            }

            if (count($errors) > 0) {
                $_SESSION['errors'] = $errors;
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }

            $coupon = $this->addCoupon(
                $_POST['name'],
                $_POST['coupon_code'],
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['quantity'],
                $_POST['status'],
                $_POST['type'],
                $_POST['coupon_value']
            );
            if ($coupon) {
                $_SESSION['success'] = 'Thêm mã giảm giá thành công';
                header("Location: ?act=coupon");
                exit();
            } else {
                $_SESSION['error'] = 'Thêm mã giảm giá không thành công. Vui lòng kiểm tra lại';
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }

        }
        include "../views/admin/coupon/add.php";
    }


    public function edit()
    {
        $coupon = $this->editCoupon();

        if (!$coupon) {
            $_SESSION['error'] = 'Mã giảm giá không hợp lệ!';
            header('Location: ?act=coupon');
            exit();
        }

        include '../views/admin/coupon/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];

            if (empty($_POST['name'])) {
                $errors['name'] = 'Vui lòng nhập tên mã giảm giá';
            }
            if (empty($_POST['status'])) {
                $errors['status'] = 'Vui lòng nhập status';
            }
            if (empty($_POST['quantity'])) {
                $errors['quantity'] = 'Vui lòng nhập số lượng';
            }
            if (empty($_POST['coupon_value'])) {
                $errors['coupon_value'] = 'Vui lòng nhập coupon_value';
            }
            if (empty($_POST['type'])) {
                $errors['type'] = 'Vui lòng chọn type';
            }
            if (empty($_POST['coupon_code'])) {
                $errors['coupon_code'] = 'Vui lòng chọn coupon_code';
            }

            // Kiểm tra ngày bắt đầu (start_date)

            $startDate = new DateTime($_POST['start_date'] ?? '2021-01-01');
            if (empty($_POST['start_date']) || $startDate < date('Y-m-d')) {
                $errors['start_date'] = 'Vui lòng chọn ngày bắt đầu và ngày bắt đầu phải lớn hơn hoặc bằng hiện tại';
            }

            $endDate = new DateTime($_POST['end_date'] ?? '2021-01-01');
            // Kiểm tra ngày kết thúc (end_date)
            if (empty($_POST['end_date']) || $endDate < $startDate) {
                $errors['end_date'] = 'Vui lòng chọn ngày kết thúc và ngày kết thúc phải lớn hơn ngày bắt đầu';
            }

            if (count($errors) > 0) {
                $_SESSION['errors'] = $errors;
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }

            $coupon = $this->updateCoupon(
                $_POST['name'],
                $_POST['coupon_code'],
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['quantity'],
                $_POST['status'],
                $_POST['type'],
                $_POST['coupon_value']
            );


            if ($coupon) {
                $_SESSION['success'] = 'Sửa mã giảm giá thành công';
                header("Location: ?act=coupon");
                exit();
            } else {
                $_SESSION['error'] = 'Sửa mã giảm giá không thành công. Vui lòng kiểm tra lại';
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }
        }
    }

    public function delete()
    {
        // Kiểm tra tham số coupon_id
        $coupon_id = $_GET['coupon_id'] ?? null;

        if (!$coupon_id || !is_numeric($coupon_id)) {
            $_SESSION['error'] = 'Mã giảm giá không hợp lệ!';
            header('Location: ?act=coupon');
            exit();
        }

        try {
            // Gọi hàm xóa trong model
            $result = $this->deleteCoupon($coupon_id);

            if ($result) {
                $_SESSION['success'] = 'Xóa mã giảm giá thành công!';
            } else {
                $_SESSION['error'] = 'Xóa mã giảm giá thất bại!';
            }
            header('Location: ?act=coupon');
            exit();
        } catch (Throwable $th) {
            $_SESSION['error'] = 'Xảy ra lỗi khi xóa mã giảm giá!';
            header('Location: ?act=coupon');
            exit();
        }
    }
}

?>