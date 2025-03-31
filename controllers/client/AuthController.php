<?php
require_once '../models/User.php';
class AuthController extends User
{
    public function registers()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])){
            $errors = [];
            if(empty($_POST['name'])) {
                $errors ['name'] = 'Vui lòng nhập tên của bạn...';
            }
            if(empty($_POST['email'])) {
                $errors ['email'] = 'Email không được để trống!';
            }   
            if(empty($_POST['password'])) {
                $errors ['password'] = 'Vui lòng nhập mật khẩu...';
            }
            
            $_SESSION['errors'] = $errors;
            if(count($errors) >0){
                header('Location: ?act=register');
                exit();
            }

            $register = $this-> register( $_POST['name'], $_POST['email'], $_POST['password']);
            if($register){
                $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập';
                header('Location: ?act=login');
                exit();
            } else {
                $_SESSION['error'] = 'Đăng ký không thành công! Vui lòng đăng ký lại';
                header('Location:' .$_SERVER['HTTP_REFERER']);
                exit();
            }
        }
        include '../views/client/auth/register.php';
    }

    public function signin()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])){
            $errors = [];
            if(empty($_POST['email'])) {
                $errors ['email'] = 'Email không được để trống!';
            }   
            if(empty($_POST['password'])) {
                $errors ['password'] = 'Vui lòng nhập mật khẩu...';
            }
            
            $_SESSION['errors'] = $errors;
            if(count($errors) >0){
                header('Location: '.$_SERVER['HTTP_REFERER']);
                exit();
            }

            $login = $this-> login($_POST['email'], $_POST['password']);
            if($login){
                $_SESSION['user'] = $login; //lưu thông tin người dùng đăng nhập vào session
                $_SESSION['success'] = 'Đăng nhập thành công!';
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['error'] = 'Đăng nhập thất bại! Vui lòng kiểm tra lại thông tin đăng nhập';
                header('Location:' .$_SERVER['HTTP_REFERER']);
                exit();
            }
        }
        include '../views/client/auth/login.php';
    }
}