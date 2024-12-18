<?php
include "D:/Xampp/htdocs/myapi/connect.php"; 
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT *  FROM thanhtoan";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $hoadonList = [];
        while ($row = $result->fetch_assoc()) {
            $hoadonList[] = $row;
        }
        echo json_encode($hoadonList);
    } else {
        echo json_encode(["message" => "Không tìm thấy hóa đon nào."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Phương thức không được hỗ trợ. Chỉ hỗ trợ GET."]);
}

$conn->close();
?>
