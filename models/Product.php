<?php
require_once '../Connect/connect.php';
class Product extends connect
{
    public function getAllColor()
    {
        $sql = "SELECT * FROM variant_colors";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSize()
    {
        $sql = "SELECT * FROM variant_sizes";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategory()
    {
        $sql = "SELECT * FROM categores";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProduct($name, $image, $price, $sale_price, $slug, $description, $category_id)
    {
        $sql = "INSERT INTO products(name, image, price, sale_price, slug, description, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$name, $image, $price, $sale_price, $slug, $description, $category_id]);
        return $this->connect()->lastInsertId();
    }

    public function addGallery($product_id, $image)
    {
        $sql = "INSERT INTO product_galleries(product_id,image) VALUES (?, ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$product_id, $image]);
        return $this->connect()->lastInsertId();
    }

    public function addProductVariant($price, $sale_price, $quantity, $product_id, $variant_color_id, $variant_size_id)
    {
        $sql = "INSERT INTO product_variants (price, sale_price, quantity, product_id, variant_color_id, variant_size_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, now(), now())";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$price, $sale_price, $quantity, $product_id, $variant_color_id, $variant_size_id]);
    }

    public function getLastInsertId()
    {
        return $this->connect()->lastInsertId();
    }

    public function listProduct()
    {
        $sql = "SELECT  
                products.product_Id AS product_id, 
                products.name AS product_name,
                products.price AS product_price,
                products.sale_price AS product_sale_price,
                products.image AS product_image,
                products.slug AS product_slug,
                categores.category_Id AS category_id,
                categores.name AS category_name,
                product_variants.product_Id AS product_variant_Id,
                variant_colors.color_name AS variant_color_name,
                variant_sizes.size_name AS variant_size_name
            FROM products
            LEFT JOIN categores ON products.category_id = categores.category_Id
            LEFT JOIN product_variants ON products.product_Id = product_variants.product_id
            LEFT JOIN variant_colors ON product_variants.variant_color_id = variant_colors.variant_color_Id
            LEFT JOIN variant_sizes ON product_variants.variant_size_id = variant_sizes.variant_size_Id
        ";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $listProduct = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedProducts = [];
        foreach ($listProduct as $product) {
            if (!isset($groupedProducts[$product['product_id']])) {
                $groupedProducts[$product['product_id']] = $product;
                $groupedProducts[$product['product_id']]['variants'] = [];
            }
            $groupedProducts[$product['product_id']]['variants'][] = [
                'product_id' => $product['product_id'],
                'product_variant_Id' => $product['product_variant_Id'],
                'product_variant_color' => $product['variant_color_name'],
                'product_variant_size' => $product['variant_size_name'],
            ];
        }
        return $groupedProducts;
    }

    public function getProductById($product_id)
    {
        $sql = 'SELECT 
                    products.product_Id AS product_id,
                    products.name AS product_name,
                    products.price AS product_price,
                    products.sale_price AS product_sale_price,
                    products.image AS product_image,
                    products.description AS product_description,
                    products.slug AS product_slug,
                    categores.category_Id AS category_id,
                    categores.name AS category_name
                FROM products
                LEFT JOIN categores ON products.category_id = categores.category_Id
                WHERE products.product_Id = ?';

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductVariantById($product_id)
    {
        $sql = 'SELECT 
                    product_variants.product_variant_Id AS product_variant_id,
                    product_variants.price AS variant_price,
                    product_variants.sale_price AS variant_sale_price,
                    product_variants.quantity AS variant_quantity,
                    variant_colors.variant_color_Id AS variant_color_Id,
                    variant_colors.color_name AS variant_color_name,
                    variant_sizes.variant_size_Id AS variant_size_Id,
                    variant_sizes.size_name AS variant_size_name
                FROM product_variants
                LEFT JOIN variant_colors ON product_variants.variant_color_id = variant_colors.variant_color_Id
                LEFT JOIN variant_sizes ON product_variants.variant_size_id = variant_sizes.variant_size_Id
                WHERE product_variants.product_id = ?';

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductGalleryById()
    {
        $sql = 'SELECT * FROM product_galleries WHERE product_id = ?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$_GET['id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProduct($product_id, $name, $image, $price, $sale_price, $slug, $description, $category_id)
    {
        $sql = 'UPDATE products 
            SET name = ?, image = ?, price = ?, sale_price = ?, slug = ?, description = ?, category_id = ? 
            WHERE product_id = ?';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$name, $image, $price, $sale_price, $slug, $description, $category_id, $product_id]);
    }

    public function updateProductVariant($product_variant_Id, $price, $sale_price, $quantity, $product_id, $variant_color_id, $variant_size_id)
    {
        $sql = 'UPDATE product_variants 
            SET product_id = ?, price = ?, sale_price = ?, quantity = ?, variant_color_id = ?, variant_size_id = ?
            WHERE product_variant_Id = ?';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$product_id, $price, $sale_price, $quantity, $variant_color_id, $variant_size_id, $product_variant_Id]);
    }

    public function removeGallery()
    {
        $sql = 'DELETE FROM product_galleries WHERE product_gallery_Id  = ?';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$_GET['gallery_id']]);
    }

    public function getGallery()
    {
        $sql = 'SELECT * FROM product_galleries WHERE product_gallery_Id = ?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$_GET['gallery_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function removeProductVariant()
    {
        $sql = 'DELETE FROM product_variants WHERE product_variant_Id = ?';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$_GET['variant_id']]);
    }

    public function removeProduct()
    {
        $sql = 'DELETE FROM products WHERE product_Id = ?';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$_GET['id']]);
    }

    public function getProductBySlug($slug)
    {
        $sql = 'SELECT
                products.product_Id AS product_id, 
                products.name AS product_name,
                products.price AS product_price,
                products.sale_price AS product_sale_price,
                products.image AS product_image,
                products.slug AS product_slug,
                products.description AS product_description,
                categores.category_Id AS category_id,
                categores.name AS category_name,
                product_variants.product_Id AS product_variant_Id,
                product_variants.product_variant_Id as variant_id,
                product_variants.price AS variant_price,
                product_variants.sale_price AS variant_sale_price,
                product_variants.quantity AS variant_quantity,
                variant_colors.color_code AS variant_color_code,
                variant_colors.color_name AS variant_color_name,
                variant_sizes.size_name AS variant_size_name,
                product_galleries.image As product_gallery_image
            FROM products
            LEFT JOIN categores ON products.category_id = categores.category_Id
            LEFT JOIN product_variants ON products.product_Id = product_variants.product_id
            LEFT JOIN product_galleries ON products.product_Id = product_galleries.product_id
            LEFT JOIN variant_colors ON product_variants.variant_color_id = variant_colors.variant_color_Id
            LEFT JOIN variant_sizes ON product_variants.variant_size_id = variant_sizes.variant_size_Id
            WHERE products.slug = ?
        ';

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$slug]);
        $listProduct = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedProducts = [];
        // lặp qua từng sản phẩm trong danh sách listProduct
        foreach ($listProduct as $product) {
            // kiểm tra xem sản phẩm đã có trong mảng $groupedProducts chưa.
            // nếu chưa có thì thêm sản phẩm vào mảng groupedProducts
            if (!isset($groupedProducts[$product['product_id']])) {
                $groupedProducts[$product['product_id']] = $product;
                $groupedProducts[$product['product_id']]['variants'] = [];
                $groupedProducts[$product['product_id']]['galleries'] = [];
            }
            $exists = false;
            foreach ($groupedProducts[$product['product_id']]['variants'] as &$variant) {
                if (
                    $variant['variant_color_name'] === $product['variant_color_name'] &&
                    $variant['variant_size_name'] === $product['variant_size_name']
                ) {
                    $exists = true;
                    break;
                }
            }


            if (!$exists) {
                $groupedProducts[$product['product_id']]['variants'][] = [
                    'product_variant_Id' => $product['product_variant_Id'],
                    'variant_id' => $product['variant_id'],
                    'variant_color_code' => $product['variant_color_code'],
                    'variant_color_name' => $product['variant_color_name'],
                    'variant_size_name' => $product['variant_size_name'],
                    'product_variant_price' => $product['variant_price'],
                    'product_variant_sale_price' => $product['variant_sale_price'],
                    'product_variant_quantity' => $product['variant_quantity']
                ];
            }

            if (!empty($product['product_gallery_image'] && !in_array($product['product_gallery_image'], $groupedProducts[$product['product_id']]['galleries']))) {
                $groupedProducts[$product['product_id']]['galleries'][] = $product['product_gallery_image'];
            }
        }

        return $groupedProducts;
    }

    public function search($keyword)
{
    $sql = 'SELECT 
                products.product_Id AS product_id,
                products.name AS product_name,
                products.image AS product_image,
                products.price AS product_price,    
                products.sale_price AS product_sale_price,
                products.slug AS product_slug,
                categores.name AS category_name
            FROM products
            LEFT JOIN categores ON products.category_id = categores.category_Id
            WHERE products.name LIKE ? OR products.description LIKE ?';
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute(['%' . $keyword . '%', '%' . $keyword . '%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
