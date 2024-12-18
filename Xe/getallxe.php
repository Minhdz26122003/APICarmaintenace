<?php
// Đặt các header cho phép truy cập từ bất kỳ đâu và sử dụng nhiều phương thức HTTP
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$servername = "localhost";  
$username = "root";        
$password = "";             
$dbname = "bookingapp";     

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["message" => "Kết nối thất bại: " . $conn->connect_error]));
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT *  FROM xe";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $xeList = [];
        while ($row = $result->fetch_assoc()) {
            $xeList[] = $row;
        }
        echo json_encode($xeList);
    } else {
        echo json_encode(["message" => "Không tìm thấy xe nào."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Phương thức không được hỗ trợ. Chỉ hỗ trợ GET."]);
}

$conn->close();
?>
