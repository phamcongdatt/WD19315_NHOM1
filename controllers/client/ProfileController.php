<?php
require_once '../models/User.php';

class ProfileController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Hiển thị trang hồ sơ
     */
    public function showProfile()
    {
        if (!isset($_SESSION['user']['user_Id'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập để tiếp tục.";
            header('Location: index.php?act=login');
            exit();
        }
        require_once '../views/client/profile.php';
    }

    /**
     * Cập nhật thông tin hồ sơ
     */
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['update-profile'])) {
            $_SESSION['error'] = "Yêu cầu không hợp lệ.";
            header('Location: index.php?act=profile');
            exit();
        }

        // Kiểm tra CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'] || time() - $_SESSION['csrf_token_time'] > 3600) {
            $_SESSION['error'] = "Phiên làm việc không hợp lệ hoặc đã hết hạn.";
            header('Location: index.php?act=profile');
            exit();
        }

        // Kiểm tra session
        if (!isset($_SESSION['user']['user_Id'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập để tiếp tục.";
            header('Location: index.php?act=login');
            exit();
        }

        // Kiểm tra dữ liệu đầu vào
        $errors = [];
        if (empty(trim($_POST['name']))) {
            $errors['name'] = 'Tên không được để trống.';
        }
        if (empty(trim($_POST['email']))) {
            $errors['email'] = 'Vui lòng nhập email.';
        }
        if (!in_array($_POST['gender'], ['Nam', 'Nu', 'Khac'])) {
            $errors['gender'] = 'Vui lòng chọn giới tính hợp lệ.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?act=profile');
            exit();
        }

        // Chuẩn bị dữ liệu
        $userId = $_SESSION['user']['user_Id'];
        $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'phone' => trim($_POST['phone']) ?: null,
            'address' => trim($_POST['address']) ?: null,
            'gender' => $_POST['gender']
        ];

        // Cập nhật thông tin
        $result = $this->userModel->updateUser($userId, $data);

        if ($result['success']) {
            $_SESSION['success'] = "Cập nhật thông tin thành công.";
            // Cập nhật session
            $_SESSION['user'] = $this->userModel->getUserById($userId);
            // Tạo CSRF token mới
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        } else {
            $_SESSION['errors'] = ['general' => $result['error']];
        }

        header('Location: index.php?act=profile');
        exit();
    }

    /**
     * Đổi mật khẩu
     */
    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['update-password'])) {
            $_SESSION['error'] = "Yêu cầu không hợp lệ.";
            header('Location: index.php?act=profile');
            exit();
        }

        // Kiểm tra CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'] || time() - $_SESSION['csrf_token_time'] > 3600) {
            $_SESSION['error'] = "Phiên làm việc không hợp lệ hoặc đã hết hạn.";
            header('Location: index.php?act=profile');
            exit();
        }

        // Kiểm tra session
        if (!isset($_SESSION['user']['user_Id'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập để tiếp tục.";
            header('Location: index.php?act=login');
            exit();
        }

        // Kiểm tra dữ liệu đầu vào
        $errors = [];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword)) {
            $errors['current_password'] = 'Vui lòng nhập mật khẩu hiện tại.';
        }
        if (empty($newPassword)) {
            $errors['new_password'] = 'Vui lòng nhập mật khẩu mới.';
        }
        if ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?act=profile');
            exit();
        }

        // Đổi mật khẩu
        $userId = $_SESSION['user']['user_Id'];
        $result = $this->userModel->changePassword($userId, $currentPassword, $newPassword);

        if ($result['success']) {
            $_SESSION['success'] = "Đổi mật khẩu thành công.";
            // Tạo CSRF token mới
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        } else {
            $_SESSION['errors'] = ['general' => $result['error']];
        }

        header('Location: index.php?act=profile');
        exit();
    }
}
?>