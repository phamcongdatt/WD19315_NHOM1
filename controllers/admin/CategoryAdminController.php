<?php
require_once '../models/Category.php';
class CategoryAdminController extends Category
{
    public function index()
    {
        $listcategories = $this->listCategory();
        // return $listcategories;
        include '../views/admin/category/list.php';
    }

    public function addCategory()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createCategory'])) {
            $errors = [];
            if(empty($_POST['name'])) {
                $errors ['name'] = 'Tên danh mục không được để trống';
            }
            // if(!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            //     $errors ['image'] = 'Ảnh danh mục không được để trống';
            // }
            if(empty($_POST['status'])) {
                $errors ['status'] = 'Trạng thái danh mục không được để trống';
            }   
            if(empty($_POST['description'])) {
                $errors ['description'] = 'Mô tả danh mục không được để trống';
            }

            $_SESSION['errors'] = $errors;

            $file = $_FILES['image'];
            $images = $file['name'];
            if (move_uploaded_file($file['tmp_name'], './images/category/'.$images)) {
                $createCategory = $this->createCategory($_POST['name'], $images, $_POST['status'], $_POST['description']);
                if ($createCategory){
                    $_SESSION['success'] = 'Thêm danh mục thành công';
                    header('Location: index.php?act=category');
                    exit();
                } else {
                    error_log('Failed to move file: ' . print_r($file, true));
                    $_SESSION['error'] = 'Lỗi khi tải lên ảnh. Vui lòng kiểm tra lại.';
                    header('Location: '.$_SERVER['HTTP_REFERER']);
                    exit();
                }
            }
            
        }
        include '../views/admin/category/create.php';
    }

    public function editCategory()
    {
        $getCategory = $this->detail();
        if($getCategory){
            return $getCategory;
        }else{
            $_SESSION['error'] = 'Không tìm thấy danh mục';
        }
    }
    public function updateCategory()
    {
        $getCategory = $this->detail();
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateCategory'])) {
            $errors = [];
            if(empty($_POST['name'])) {
                $errors ['name'] = 'Tên danh mục không được để trống';
            }
            // if(!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            //     $errors ['image'] = 'Ảnh danh mục không được để trống';
            // }
            if(empty($_POST['status'])) {
                $errors ['status'] = 'Trạng thái danh mục không được để trống';
            }   
            if(empty($_POST['description'])) {
                $errors ['description'] = 'Mô tả danh mục không được để trống';
            }

            $_SESSION['errors'] = $errors;

            $file = $_FILES['image'];
            $images = $file['name'];
            if ($file['size'] > 0) {
                move_uploaded_file($file['tmp_name'], './images/category/'.$images);
                if (!empty($_POST['old_image']) && file_exists('./images/category/'.$_POST['old_image'])){
                    unlink('./images/category/'.$_POST['old_image']);
                }
            } else {
                $images = $_POST['old_image'];
            }
            $updateCategory = $this->update($_GET['id'], $_POST['name'], $images, $_POST['status'], $_POST['description']);
            if ($updateCategory){
                $_SESSION['success'] = 'Cập nhật danh mục thành công';
                header('Location: index.php?act=category');
                exit();
            }else {
                $_SESSION['error'] = 'Cập nhật danh mục thất bại. Vui lòng thử lại.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }
        }
        include '../views/admin/category/edit.php';
    }

    public function deleteCategory()
    {
        try{
            $this->delete($_GET['id']);
            $_SESSION['success'] = 'Xóa danh mục thành công';
            header('Location: index.php?act=category');
            exit();
        } catch (\Throwable $th) {
            $_SESSION['error'] = 'Xóa danh mục thất bại. Vui lòng thử lại.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
}

