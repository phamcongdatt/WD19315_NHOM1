<?php
require_once '../models/Product.php';
require_once '../models/Category.php';
class HomeController {
    protected $category;
    protected $product;

    public function __construct() {
        $this->category = new Category();
        $this->product = new Product();
    }
    public function index() {
        $category = $this->category->listCategory();
        $products = $this->product->listProduct();
        include '../views/client/index.php';
    }

    public function getProductDetail()
    {
        $productDetail = $this->product->getProductBySlug($_GET['slug']);
        $productDetail = reset($productDetail);
        // echo '<pre>';
        // print_r ($productDetail);
        // echo "</pre>";
        include '../views/client/product/productDetail.php';
    }
}