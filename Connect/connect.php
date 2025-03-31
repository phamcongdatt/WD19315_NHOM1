<?php
// kết nối với cơ sở dữ liệu 
class Connect
{
    // Thuộc tính để lưu kết nối
    private $conn;

    // Phương thức khởi tạo kết nối, chỉ tạo một lần
    public function connect()
    {
        // Kiểm tra xem đã có kết nối hay chưa
        if ($this->conn === null) {
            $serverName = 'localhost';
            $userName = 'root';
            $password = '';
            $myDB = 'team8_duan1';
            try {
                // Tạo kết nối PDO và lưu vào thuộc tính
                $this->conn = new PDO("mysql:host=$serverName;dbname=$myDB", $userName, $password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\Throwable $th) {
                // Xử lý lỗi và đưa ra thông báo cụ thể
                echo 'Kết nối thất bại: ' . $th->getMessage();
                return null;
            }
        }

        // Trả về kết nối nếu đã có
        return $this->conn;
    }
}
?>
