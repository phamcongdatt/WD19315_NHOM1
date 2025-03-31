<?php
require_once '../Connect/connect.php';
class Cart extends Connect
{

    public function getAllCart()
    {
        $sql = 'SELECT 
                    carts.cart_Id AS cart_id,
                    products.name AS product_name,
                    products.product_Id AS product_id,
                    products.image AS product_image,
                    products.slug AS product_slug,
                    product_variants.product_variant_Id AS variant_id,
                    product_variants.price AS product_variant_price,
                    product_variants.sale_price AS product_variant_sale_price,
                    variant_colors.color_name AS variant_color_name,
                    variant_sizes.size_name AS variant_size_name,
                    carts.quantity AS quantity
                FROM carts  
                LEFT JOIN products ON carts.product_id = products.product_Id
                LEFT JOIN product_variants ON product_variants.product_variant_Id = carts.variant_id
                LEFT JOIN variant_colors ON product_variants.variant_color_id = variant_colors.variant_color_Id
                LEFT JOIN variant_sizes ON product_variants.variant_size_id = variant_sizes.variant_size_Id
                WHERE carts.user_id = ?';
    
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$_SESSION['user']['user_Id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    


    public function validateVariantId($variant_id)
    {
        $sql = 'SELECT COUNT(*) FROM product_variants WHERE product_variant_Id = ?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$variant_id]);
        return $stmt->fetchColumn() > 0; // Trả về true nếu tồn tại
    }

    public function addToCart($user_id, $product_id, $variant_id, $quantity)
    {
        // Kiểm tra variant_id có tồn tại không
        $sql = 'INSERT INTO carts(user_id, product_id, variant_id, quantity) VALUES (?, ?, ?, ?)';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$user_id, $product_id, $variant_id, $quantity]);

    }


    public function checkCart()
    {
        $sql = 'SELECT * FROM carts where user_id = ? and product_id = ? and variant_id = ?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$_SESSION['user']['user_Id'], $_POST['product_id'], $_POST['variant_id']]);
        return $stmt->fetch();
    }
    public function updateCart($user_id, $product_id, $variant_id, $quantity)
    {
        $sql = 'UPDATE carts set quantity = ? where user_id = ? and product_id = ? and variant_id = ? ';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$quantity, $user_id, $product_id, $variant_id]);
    }
    public function updateCartById($cart_id, $quantity)
    {
        $sql = 'UPDATE carts set quantity = ? where cart_Id = ?';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$quantity, $cart_id]);
    }
    public function deleteCart($cart_id)
    {
        $sql = 'DELETE FROM carts WHERE cart_Id = ?';
        $stmt = $this->connect()->prepare($sql);
        if ($stmt->execute([$cart_id])) {
            return true;
        } else {
            // Log the error or throw an exception for debugging
            throw new Exception('Deletion failed');
        }
    }

    public function getCouponByCode($coupon_code)
    {
        $sql = 'select * from coupons where coupon_code = ?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$coupon_code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>