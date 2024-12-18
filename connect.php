<?php
$servername = "localhost";  // Tên server hoặc địa chỉ IP của máy chủ MySQL
$username = "root";         // Tên người dùng MySQL
$password = "";             // Mật khẩu cho người dùng MySQL
$dbname = "bookingapp";  // Tên cơ sở dữ liệu bạn muốn kết nối

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
