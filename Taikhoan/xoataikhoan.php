// Delete account
<?php
include "../connect.php";
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Kiểm tra xem iduser có tồn tại không
    if (isset($data['iduser'])) {
        $iduser = $data['iduser'];

        // Chuẩn bị câu truy vấn
        $query = "DELETE FROM taikhoan WHERE iduser = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $iduser);

        // Thực thi và trả về kết quả
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Xóa tài khoản thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Xóa tài khoản không thành công']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Thiếu iduser']);
    }
}
?>