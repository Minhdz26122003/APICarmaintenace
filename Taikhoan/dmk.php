<?php
include "D:/Xampp/htdocs/myapi/connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Đọc dữ liệu từ body JSON
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu có đầy đủ không
if (!isset($data['iduser']) || !isset($data['currentPassword']) || !isset($data['newPassword'])) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không đầy đủ']);
    exit(); // Kết thúc kịch bản sớm
}

$iduser = $data['iduser'];
$currentPassword = $data['currentPassword'];
$newPassword = $data['newPassword'];

// Truy vấn mật khẩu cũ từ cơ sở dữ liệu
$query = "SELECT password FROM Taikhoan WHERE iduser = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $iduser);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Người dùng không tồn tại']);
    exit();
}

$user = $result->fetch_assoc();
$storedPassword = $user['password']; // Mật khẩu đã lưu trong cơ sở dữ liệu

// So sánh mật khẩu cũ người dùng nhập với mật khẩu đã lưu trong cơ sở dữ liệu
if ($storedPassword !== $currentPassword) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu cũ không đúng']);
    exit();
}   

// Cập nhật mật khẩu mới vào cơ sở dữ liệu
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
?>
