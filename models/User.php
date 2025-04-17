<?php
require_once '../Connect/connect.php';

class User extends Connect
{
    public function register($name, $email, $password)
    {
        try {
            if (empty($name) || empty($email) || empty($password)) {
                return ['success' => false, 'error' => 'Vui lòng điền đầy đủ thông tin.'];
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'error' => 'Email không hợp lệ.'];
            }
            if (strlen($password) < 6) {
                return ['success' => false, 'error' => 'Mật khẩu phải có ít nhất 6 ký tự.'];
            }

            $sql = 'SELECT COUNT(*) FROM users WHERE email = ?';
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'error' => 'Email đã được sử dụng.'];
            }

            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = 'INSERT INTO users(name, email, password, role_id, created_at) VALUES (?, ?, ?, 3, NOW())';
            $stmt = $this->connect()->prepare($sql);
            $success = $stmt->execute([$name, $email, $hash_password]);

            return ['success' => $success, 'error' => $success ? null : 'Đăng ký thất bại.'];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    public function login($email, $password)
    {
        try {
            if (empty($email) || empty($password)) {
                return false;
            }

            $sql = 'SELECT * FROM users WHERE email = ?';
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getUserById($id)
    {
        try {
            $sql = 'SELECT * FROM users WHERE user_Id = ?';
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function updateUser($userId, $data)
    {
        try {
            if (empty($data['name']) || empty($data['email'])) {
                return ['success' => false, 'error' => 'Tên và email không được để trống.'];
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'error' => 'Email không hợp lệ.'];
            }
            if (!in_array($data['gender'], ['Nam', 'Nu', 'Khac'])) {
                return ['success' => false, 'error' => 'Giới tính không hợp lệ.'];
            }

            $sql = 'SELECT COUNT(*) FROM users WHERE email = ? AND user_Id != ?';
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$data['email'], $userId]);
            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'error' => 'Email đã được sử dụng.'];
            }

            $sql = 'UPDATE users SET name = ?, email = ?, phone = ?, address = ?, gender = ?, updated_at = NOW() WHERE user_Id = ?';
            $stmt = $this->connect()->prepare($sql);
            $success = $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'] ?? null,
                $data['address'] ?? null,
                $data['gender'],
                $userId
            ]);

            return ['success' => $success, 'error' => $success ? null : 'Cập nhật thất bại.'];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    public function changePassword($userId, $currentPassword, $newPassword)
    {
        try {
            if (empty($currentPassword) || empty($newPassword)) {
                return ['success' => false, 'error' => 'Vui lòng điền đầy đủ mật khẩu.'];
            }
            if (strlen($newPassword) < 6) {
                return ['success' => false, 'error' => 'Mật khẩu mới phải có ít nhất 6 ký tự.'];
            }

            $sql = 'SELECT password FROM users WHERE user_Id = ?';
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($currentPassword, $user['password'])) {
                return ['success' => false, 'error' => 'Mật khẩu hiện tại không đúng.'];
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = 'UPDATE users SET password = ?, updated_at = NOW() WHERE user_Id = ?';
            $stmt = $this->connect()->prepare($sql);
            $success = $stmt->execute([$hashedPassword, $userId]);

            return ['success' => $success, 'error' => $success ? null : 'Đổi mật khẩu thất bại.'];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }
}
?>