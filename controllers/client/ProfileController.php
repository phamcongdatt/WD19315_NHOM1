<?php
require_once '../models/User.php';
class ProfileController extends User
{
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update-profile'])) {
            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';
            $errors = [];
            if (empty($_POST['name'])) {
                $errors['name'] = 'Tên không được để trống';
            }
            if (empty($_POST['email'])) {
                $errors['email'] = 'Vui lòng nhập Email';
            }
            if (empty($_POST['phone'])) {
                $errors['phone'] = 'Vui lòng nhập số điện thoại';
            }
            if (empty($_POST['address'])) {
                $errors['address'] = 'Vui lòng nhập địa chỉ';
            }
            if (empty($_POST['gender'])) {
                $errors['gender'] = 'Vui lòng chọn giới tính';
            }
            if (count($errors) > 0) {
                // $_SESSION['errors'] = $errors;
                header('Location: ' .$_SERVER['HTTP_REFERER']);
                exit();
            }

            $_SESSION['errors'] = $errors;
            $user = $this->updateUser(
                $_POST['name'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['address'],
                $_POST['gender']
            );
            if ($user) {
                $_SESSION['users'] = $this->getUserById($_SESSION['user']['user_Id']);
                $_SESSION['success'] = 'Cập nhật thông tin thành công';
                header('Location: '. $_SERVER['HTTP_REFERER']);
                exit();
            } else {
                $_SESSION['error'] = 'Cập nhật thông tin thất bại. Vui lòng kiểm tra lại thông tin';
                header('Location: '. $_SERVER['HTTP_REFERER']);
                exit();
            }
        }
    }
}
