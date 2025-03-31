<?php
require_once '../Connect/connect.php';
class Category extends connect{
    public function listCategory() {
        $sql = 'SELECT * FROM categores';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createCategory($name, $images, $status, $description) {
        $sql = 'insert into categores(name, image, status, description) values(?,?,?,?)';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$name, $images, $status, $description]);
    }

    public function update($id, $name, $images, $status, $description){
        $sql = 'update categores set name =?, image =?, status =?, description =? where category_Id =?';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$name, $images, $status, $description, $id]);
    }

    public function delete($id) {
        $sql = 'delete from categores where category_Id =?';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$_GET['id']]);
    }

    public function detail(){
        $sql ='select * from categores where category_Id =?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$_GET['id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
