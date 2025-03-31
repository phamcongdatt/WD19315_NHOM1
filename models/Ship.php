<?php 
require_once '../Connect/connect.php';
class Ship extends Connect{
    public function getAllShip(){
        $sql = 'SELECT * FROM ships';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}