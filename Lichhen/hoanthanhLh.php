<?php
include "D:/Xampp/htdocs/myapi/connect.php"; 

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($data['idlichhen'])) {
        $idlichhen = $data['idlichhen'];

      
        $query = "UPDATE lichhen SET trangthai = 2 WHERE idlichhen = ?";
        

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $idlichhen);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Lịch hẹn đã được xác nhận.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Xác nhận lịch hẹn không thành công.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Thiếu thông tin idlichhen.']);
    }
} else {
    echo json_encode(['message' => 'Phương thức không được hỗ trợ.']);
}

$conn->close();
?>