<?php

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  
header("Access-Control-Allow-Headers: Content-Type, Authorization");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookingapp";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM khuyenmai";
    $result = $conn->query($sql);

   if ($result->num_rows > 0) {
    $KmaiList = [];

    while ($row = $result->fetch_assoc()) {
        $KmaiList[] = $row;
    }

    echo json_encode($KmaiList);
    } else {
        echo json_encode(["message" => "Không tìm thấy khuyến mãi nào."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Phương thức không được hỗ trợ. Chỉ hỗ trợ GET."]);
}

$conn->close();
?>
