<?php
include "../connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: DELETE"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
    exit;
}

if (empty($data['iddanhgia'])) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu đầu vào không hợp lệ']);
    exit;
}

$iddanhgia = $data['iddanhgia'];

$query = "DELETE FROM danhgia_binhluan WHERE iddanhgia = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $iddanhgia); 

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Xóa bình luận thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy bình luận hoặc không có quyền xóa']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Xóa bình luận không thành công']);
}

$stmt->close();
$conn->close();
?>
