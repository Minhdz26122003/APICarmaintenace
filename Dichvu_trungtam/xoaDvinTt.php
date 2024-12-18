<?php
include "../connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (isset($data['iddvtt'])) {
    $iddvtt = $data['iddvtt'];

    $query = "DELETE FROM dichvu_trungtam WHERE iddvtt = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $iddvtt);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Xóa dich vụ thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Xóa dịch vụ không thành công']);
    }

    $stmt->close();
}else{
    echo json_encode(['success' => false, 'message' => 'Thiếu iddvtt']);
}
}
?>