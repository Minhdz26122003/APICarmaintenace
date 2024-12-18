<?php
include "D:/Xampp/htdocs/myapi/connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $idxe = $data['idxe'];
    $iduser = $data['iduser'];
    $hangxe = $data['hangxe'];
    $namsx = $data['namsx'];
    if (!$idxe || !$iduser || !$hangxe || !$namsx) {
        echo json_encode(['success' => false, 'message' => 'Thiếu thông tin cần thiết']);
        exit;
    }

    $query = "UPDATE Xe SET hangxe = ?, namsx = ? WHERE idxe = ? AND iduser = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssii', $hangxe, $namsx, $idxe, $iduser);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật xe thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cập nhật xe không thành công']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
}
?>
