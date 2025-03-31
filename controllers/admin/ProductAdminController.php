<?php
require_once '../models/Product.php';
class ProductAdminController extends Product
{
    public function index()
    {
        $ListProducts = $this->listProduct();
        include '../views/admin/product/list.php';
    }

    public function create()
    {
        $ListColors = $this->getAllColor();
        $ListSizes = $this->getAllSize();
        $ListCategories = $this->getAllCategory();
        include '../views/admin/product/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_products'])) {
            $errors = [];
            if (empty($_POST['product_name'])) {
                $errors['product_name'] = 'Tên sản phẩm không được để trống';
            }
            if (empty($_POST['product_price'])) {
                $errors['product_price'] = 'Vui lòng nhập giá sản phẩm';
            }
            if (empty($_POST['product_sale_price'])) {
                $errors['product_sale_price'] = 'Vui lòng nhập giá khuyến mãi của sản phẩm';
            }
            // if(!isset($_FILES['gallery_image']) || $_FILES['gallery_image']['error'] !== UPLOAD_ERR_OK) {
            //     $errors ['gallery_image'] = 'Vui lòng chọn 1 file ảnh hợp lệ';
            // }
            if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
                $errors['product_image'] = 'Ảnh sản phẩm không được để trống';
            }
            if (empty($_POST['product_slug'])) {
                $errors['product_slug'] = 'Đường dẫn sản phẩm không được để trống';
            }
            if (empty($_POST['variant_size'])) {
                $errors['variant_size'] = 'Vui lòng chọn kích thước sản phẩm';
            }
            if (empty($_POST['variant_color'])) {
                $errors['variant_color'] = 'Vui lòng chọn màu sắc sản phẩm';
            }
            if (empty($_POST['product_description'])) {
                $errors['product_description'] = 'Vui lòng nhập mô tả sản phẩm';
            }

            // kiểm tra từng phần tử trong các mảng
            foreach ($_POST['variant_quantity'] as $key => $variant_quantity) {
                if (empty($variant_quantity)) {
                    $errors['variant_quantity'][$key] = 'Vui lòng nhập số lượng sản phẩm' . ($key + 1);
                }
            }
            foreach ($_POST['variant_price'] as $key => $variant_price) {
                if (empty($variant_price)) {
                    $errors['variant_price'][$key] = 'Vui lòng nhập giá biến thể sản phẩm' . ($key + 1);
                }
            }
            foreach ($_POST['variant_sale_price'] as $key => $variant_sale_price) {
                if (empty($variant_sale_price)) {
                    $errors['variant_sale_price'][$key] = 'Vui lòng nhập giá khuyến mãi biến thể sản phẩm' . ($key + 1);
                }
            }
            $_SESSION['errors'] = $errors;
            if ($errors) {
                header('location: ?act=product-create');
            }

            $file = $_FILES['product_image'];
            $product_image = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9]/', '', basename($file['name']));
            if (move_uploaded_file($file['tmp_name'], './images/product/' . $product_image)) {
                $addProduct = $this->addProduct(
                    $_POST['product_name'],
                    $product_image,
                    $_POST['product_price'],
                    $_POST['product_sale_price'],
                    $_POST['product_slug'],
                    $_POST['product_description'],
                    $_POST['category_id'],
                );
                if ($addProduct) {
                    $product_id = $this->getLastInsertId();
                    echo '<pre>';
                    print_r($product_id);
                    echo '<pre>';
                    if (isset($_POST['variant_size']) && isset($_POST['variant_color'])) {
                        foreach ($_POST['variant_size'] as $key => $size) {
                            $addProductVariant = $this->addProductVariant(
                                $_POST['variant_price'][$key],
                                $_POST['variant_sale_price'][$key],
                                $_POST['variant_quantity'][$key],
                                $product_id,
                                $_POST['variant_color'][$key],
                                $size,
                            );
                        }
                    }
                    if (!empty($_FILES['gallery_image']['name']['0'])) {
                        $file = $_FILES['gallery_image'];
                        for ($i = 0; $i < count($file['name']); $i++) {
                            $fileName = basename($file['name'][$i]);
                            $imageArray = uniqid() . '-' . preg_replace('/[^A-Za-z0-9\-_\.]+/', '-', $fileName);
                            move_uploaded_file($file['tmp_name'][$i], "./images/product_gallery/" .  $imageArray);
                            $this->addGallery($product_id, $imageArray);
                        }
                    }
                }
                $_SESSION['success'] = 'Thêm sản phẩm mới thành công';
                header('Location:?act=product');
                exit();
            } else {
                $_SESSION['error'] = 'Thêm sản phẩm mới không thành công';
                header('Location:' . $_SERVER['HTTP_REFERER']);
                exit();
            }
        }
    }

    public function edit()
    {
        $product = $this->getProductById($_GET['id']);
        $variants = $this->getProductVariantById($_GET['id']);
        $gallery = $this->getProductGalleryById();
        $ListCategories = $this->getAllCategory();
        $ListColors = $this->getAllColor();
        $ListSizes = $this->getAllSize();
        // echo '<pre>';
        // print_r($variants);
        // echo '<pre>';
        include '../views/admin/product/edit.php';
    }

    public function update()
    {
        $errors = [];
        if (empty($_POST['product_name'])) {
            $errors['product_name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($_POST['product_price'])) {
            $errors['product_price'] = 'Vui lòng nhập giá sản phẩm';
        }
        if (empty($_POST['product_sale_price'])) {
            $errors['product_sale_price'] = 'Vui lòng nhập giá khuyến mãi của sản phẩm';
        }
        // if(!isset($_FILES['gallery_image']) || $_FILES['gallery_image']['error'] !== UPLOAD_ERR_OK) {
        //     $errors ['gallery_image'] = 'Vui lòng chọn 1 file ảnh hợp lệ';
        // }
        // if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
        //     $errors['product_image'] = 'Ảnh sản phẩm không được để trống';
        // }
        if (empty($_POST['product_slug'])) {
            $errors['product_slug'] = 'Đường dẫn sản phẩm không được để trống';
        }
        if (empty($_POST['variant_size'])) {
            $errors['variant_size'] = 'Vui lòng chọn kích thước sản phẩm';
        }
        if (empty($_POST['variant_color'])) {
            $errors['variant_color'] = 'Vui lòng chọn màu sắc sản phẩm';
        }
        if (empty($_POST['product_description'])) {
            $errors['product_description'] = 'Vui lòng nhập mô tả sản phẩm';
        }

        // kiểm tra từng phần tử trong các mảng
        foreach ($_POST['variant_quantity'] as $key => $variant_quantity) {
            if (empty($variant_quantity)) {
                $errors['variant_quantity'][$key] = 'Vui lòng nhập số lượng sản phẩm' . ($key + 1);
            }
        }
        foreach ($_POST['variant_price'] as $key => $variant_price) {
            if (empty($variant_price)) {
                $errors['variant_price'][$key] = 'Vui lòng nhập giá biến thể sản phẩm' . ($key + 1);
            }
        }
        foreach ($_POST['variant_sale_price'] as $key => $variant_sale_price) {
            if (empty($variant_sale_price)) {
                $errors['variant_sale_price'][$key] = 'Vui lòng nhập giá khuyến mãi biến thể sản phẩm' . ($key + 1);
            }
        }
        $_SESSION['errors'] = $errors;
        if (count($errors) > 0) {
            header('location:' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update-product'])) {
            $file = $_FILES['product_image'];
            $product_image = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9]/', '', basename($file['name']));
            if ($file['size'] > 0) {
                if (move_uploaded_file($file['tmp_name'], './images/product/' . $product_image)) {
                    if (isset($_POST['old_product_image']) && file_exists('./images/product/' . $_POST['old_product_image'])) {
                        unlink('./images/product/' . $_POST['old_product_image']);
                    }
                }
            } else {
                $product_image = $_POST['old_product_image'];
            }
            //Cập nhật thông tin sản phẩm
            $updateProduct = $this->updateProduct(
                $_POST['product_id'],
                $_POST['product_name'],
                $product_image,
                $_POST['product_price'],
                $_POST['product_sale_price'],
                $_POST['product_slug'],
                $_POST['product_description'],
                $_POST['category_id'],
            );
            if ($updateProduct) {
                $product_id = $_POST['product_id'];
                // Cập nhật biến thể sản phẩm
                if (isset($_POST['variant_size']) && isset($_POST['variant_color'])) {
                    foreach ($_POST['variant_size'] as $key => $size) {
                        // kiểm tra em biến thể này đã tồn tại hay chưa
                        if (isset($_POST['product_variant_Id'][$key]) && !empty($_POST['product_variant_Id'][$key])) {
                            // cập nhật biến thể hiến có
                            $this->updateProductVariant(
                                $_POST['product_variant_Id'][$key],
                                $_POST['variant_price'][$key],
                                $_POST['variant_sale_price'][$key],
                                $_POST['variant_quantity'][$key],
                                $product_id,
                                $_POST['variant_color'][$key],
                                $size,


                            );
                        } else {
                            $addProductVariant = $this->addProductVariant(
                                $_POST['variant_price'][$key],
                                $_POST['variant_sale_price'][$key],
                                $_POST['variant_quantity'][$key],
                                $product_id,
                                $_POST['variant_color'][$key],
                                $size,
                            );
                        }
                    }
                }
                // cập nhật ảnh
                // echo '<pre>';
                // print_r($_POST);
                // echo '<pre>';
                if (!empty($_FILES['gallery_image']['name'][0])) {
                    if (!empty($_FILES['gallery_image']['name']['0'])) {
                        $file = $_FILES['gallery_image'];
                        for ($i = 0; $i < count($file['name']); $i++) {
                            $fileName = basename($file['name'][$i]);
                            $imageArray = uniqid() . '-' . preg_replace('/[^A-Za-z0-9\-_\.]+/', '-', $fileName);
                            move_uploaded_file($file['tmp_name'][$i], "./images/product_gallery/" .  $imageArray);
                            $this->addGallery($_GET['id'], $imageArray);
                        }
                    }
                } else {
                    $imageArray = $_POST['old_gallery_image'];
                }

                //thông báo
                $_SESSION['success'] = 'Cập nhật sản phẩm thành công';
                header('location:?act=product');
                exit();
            } else {
                $_SESSION['errors'] = 'Cập nhật sản phẩm thất bại.  Vui lòng thử lại';
                header('location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }
        }
    }

    public function deleteGallery()
    {
        try {
            $gallery = $this->getGallery();

            if (file_exists('./images/product_gallery/' . $gallery['image'])) {
                unlink('./images/product_gallery/' . $gallery['image']);
            }
            $this->removeGallery();
            $_SESSION['success'] = 'Xóa ảnh khỏi kho lưu trữ ảnh thành công thành công';
            header('location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } catch (\Throwable $th) {
            echo $th->getMessage();
            header('location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    public function deleteProductVariant()
    {
        try {
            $this->removeProductVariant();
            $_SESSION['success'] = 'Xóa biến thể thành công thành công';
            header('location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } catch (\Throwable $th) {
            echo $th->getMessage();
            header('location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    public function deleteProduct()
    {
        try {
            $getlleries = $this->getProductGalleryById();
            // echo '<pre>';
            // print_r($getlleries);
            // echo '<pre>';
            foreach ($getlleries as $gallery) {
                if (file_exists('./images/product_gallery/' . $gallery['image'])) {
                    unlink('./images/product_gallery/' . $gallery['image']);
                }
            }
            $this->removeProduct();
            $_SESSION['success'] = 'Xóa sản phẩm thành công thành công';
            header('location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } catch (\Throwable $th) {
            echo $th->getMessage();
            header('location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
}
