<?php
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Cấu hình thông tin kết nối
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookingapp";

// Tạo kết nối với cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM dichvu GROUP BY gia ORDER BY gia ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $dichvuList = [];
        while ($row = $result->fetch_assoc()) {
           
            if (!preg_match('/^(http|https):\/\//', $row['hinhanh'])) {
                $row['hinhanh'] = 'http://192.168.1.8/uploads/' . $row['hinhanh'];
            }
            $dichvuList[] = $row;
        }
        echo json_encode($dichvuList);
    } else {
        
        echo json_encode(["message" => "Không tìm thấy dịch vụ nào."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Phương thức không được hỗ trợ. Chỉ hỗ trợ GET."]);
}

$conn->close();
