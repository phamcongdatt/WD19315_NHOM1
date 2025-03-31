<?php

require_once '../Connect/connect.php';
class User extends Connect
{
    public function register($name, $email, $password)
    {
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'insert into users(name, email, password, role_id) VALUES (?,?,?,3)';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$name, $email, $hash_password]);
    }

    public function login($email, $password)
    {
        $sql = 'select * from users where email = ?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function updateUser($name, $email, $phone, $address, $gender)
    {
        $sql = 'UPDATE users SET name = ?, email = ?, phone = ?, address = ?, gender = ? WHERE user_Id = ?';
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([$name, $email, $phone, $address, $gender, $_SESSION['user']['user_Id']]); // Không dùng 'params:'
    }
    public function getUserById($id)
    {
        $sql = 'SELECT * FROM users WHERE user_Id = ?';  
        $stmt = $this->connect()->prepare($sql);  
        $stmt->execute([$id]);  
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }



}