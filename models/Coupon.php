<?php
require_once "../Connect/Connect.php";
class Coupon extends Connect
{
    public function listCoupon()
    {
        $sql = "SELECT * FROM `coupons`";
        $stmt = $this->connect()->prepare( $sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addCoupon($name, $coupon_code, $start_date, $end_date, $quantity, $status, $type, $coupon_value)
    {
        $sql = 'INSERT INTO coupons (name,coupon_code,start_date,end_date,quantity,status,type,coupon_value, created_at,updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, now(), now())';

        $stmt = $this->connect()->prepare($sql);

        return $stmt->execute([$name,$coupon_code,$start_date,$end_date,$quantity,$status,$type, $coupon_value]);
    }
    // public function editCoupon()
    // {
    //     $sql = "SELECT * FROM coupons WHERE coupon_Id = ?";
    //     $stmt = $this->connect()->prepare($sql);
    //     $stmt->execute([$_GET['coupon_Id']]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function editCoupon()
    {
        $sql = 'SELECT * FROM coupons WHERE coupon_Id = ?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$_GET['coupon_id']]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCoupon($name, $coupon_code, $start_date, $end_date, $quantity, $status, $type, $coupon_value){
        $sql = 'UPDATE coupons SET name = ?, coupon_code = ?, start_date = ?, end_date = ?, quantity = ?, status = ?,type = ?, coupon_value = ?, updated_at = now() WHERE coupon_Id = ?';
        $stmt = $this->connect()->prepare($sql);
        
        return $stmt->execute([$name, $coupon_code, $start_date, $end_date, $quantity, $status, $type, $coupon_value, $_GET['coupon_id']]);
    }
    
    // Xóa coupon
    public function deleteCoupon()
{
    $sql = "DELETE FROM coupons WHERE coupon_Id = ?";
    $stmt = $this->connect()->prepare($sql);
    return $stmt->execute([$_GET['coupon_id']]);
}

}


