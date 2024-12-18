<?php
include "D:/Xampp/htdocs/myapi/connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $iduser = $data['iduser'];
    $currentPassword = $data['currentPassword']; 
    $newPassword = $data['password']; 

    $query = "SELECT password FROM Taikhoan WHERE iduser = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $iduser);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || $user['password'] !== $currentPassword) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu cũ không chính xác']);
        exit;
    }

    // Cập nhật mật khẩu mới
    $query = "UPDATE Taikhoan SET password = ? WHERE iduser = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $newPassword, $iduser);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đổi mật khẩu thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Đổi mật khẩu không thành công']);
    }

    $stmt->close();
    $conn->close();
}
?>
