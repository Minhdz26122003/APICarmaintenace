<?php
include "D:/Xampp/htdocs/myapi/connect.php"; 

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idlichhen = $data['idlichhen'];
    $lydohuy = $data['lydohuy'];

    if (empty($idlichhen) || empty($lydohuy)) {
        echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
        exit;
    }

    $query = "UPDATE Lichhen SET trangthai = 4, lydohuy = ? WHERE idlichhen = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $lydohuy, $idlichhen);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Hủy lịch hẹn thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Hủy lịch hẹn thất bại']);
    }

    $stmt->close();
}
$conn->close();
?>  