<?php
session_start();
class ShopController {
    protected $product;
    protected $category;

    public function __construct() {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function index() {
        $categories = $this->category->listCategory();
        $products = $this->product->listProduct();

        // Xử lý tìm kiếm (POST)
        $searchResult = $this->searchProduct();
        if (!empty($searchResult)) {
            $products = $searchResult;
        }

        // Xử lý lọc sản phẩm (GET với act=shop)
        $filterResult = $this->filterProducts();
        if (!empty($filterResult)) {
            $products = $filterResult;
        }

        // Gộp kết quả tìm kiếm và lọc nếu cả hai đều có
        if (!empty($searchResult) && !empty($filterResult)) {
            $searchGrouped = [];
            foreach ($searchResult as $item) {
                $searchGrouped[$item['product_id']] = $item;
            }
            $products = array_intersect_key($searchGrouped, $filterResult);
        }

        // Đảm bảo act luôn là 'shop' cho trang này
        if (!isset($_GET['act']) || $_GET['act'] !== 'shop') {
            $_GET['act'] = 'shop';
        }

        include '../views/client/shop/shop.php';
    }

    public function searchProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
            $result = $this->product->search($_POST['keyword']);
            $_SESSION['keyword'] = $_POST['keyword'];
            if ($result) {
                $_SESSION['success'] = 'Kết quả tìm kiếm với từ khóa ' . $_POST['keyword'];
                return $result;
            } else {
                $_SESSION['error'] = 'Không tìm thấy kết quả cho từ khóa ' . $_POST['keyword'];
                return [];
            }
        }
        return null;
    }

    public function filterProducts() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['act']) && $_GET['act'] == 'shop') {
            $filters = [];

            if (!empty($_GET['category'])) {
                $filters['category'] = $_GET['category'];
            }

            // Kiểm tra và xử lý giá (không chia 1000 nếu database lưu giá bằng VNĐ)
            if (!empty($_GET['min_price']) && is_numeric($_GET['min_price']) && $_GET['min_price'] >= 0) {
                $filters['min_price'] = $_GET['min_price'];
            }

            if (!empty($_GET['max_price']) && is_numeric($_GET['max_price']) && $_GET['max_price'] >= 0) {
                $filters['max_price'] = $_GET['max_price'];
            }

            // Kiểm tra min_price không lớn hơn max_price
            if (isset($filters['min_price']) && isset($filters['max_price']) && $filters['min_price'] > $filters['max_price']) {
                $_SESSION['error'] = 'Giá tối thiểu không thể lớn hơn giá tối đa';
                return [];
            }

            if (!empty($filters)) {
                $result = $this->product->filterProducts($filters);
                if ($result) {
                    $_SESSION['success'] = 'Đã áp dụng bộ lọc sản phẩm';
                    return $result;
                } else {
                    $_SESSION['error'] = 'Không tìm thấy sản phẩm phù hợp với bộ lọc';
                    return [];
                }
            }
        }
        return null;
    }
}
?>