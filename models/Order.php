<?php
require_once '../Connect/connect.php';
class Order extends Connect
{
    public function addOrder($product_id, $variant_id, $quantity, $order_detail_id){
        $sql = 'INSERT INTO orders (user_id, product_id, variant_id, quantity, order_detail_id, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$_SESSION['user']['user_Id'], $product_id, $variant_id, $quantity, $order_detail_id]);
    }
    

    public function addOrderDetail($name, $email, $phone, $address, $amount, $note, $shipping_id, $coupon_id, $payment_method)
    {
        // Câu lệnh SQL chuẩn
        $sql = 'INSERT INTO order_details (name, email, phone, address, user_id, amount, note, shipping_id, coupon_id, payment_method, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$name, $email, $phone, $address, $_SESSION['user']['user_Id'], $amount, $note, $shipping_id, $coupon_id, $payment_method]);
    }

    public function getLastInsertId()
    {
        return $this->connect()->lastInsertId();
    }
    



}