// Delete center
<?php
include "../connect.php";
header('Content-Type: application/json');

header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (isset($data['idtrungtam'])) {
    $idtrungtam = $data['idtrungtam'];

    $query = "DELETE FROM trungtam WHERE idtrungtam = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $idtrungtam);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Xóa trung tâm thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Xóa trung tâm không thành công']);
    }

    $stmt->close();
}
else{
    echo json_encode(['success' => false, 'message' => 'Thiếu idtrungtam']);
}
}
?>