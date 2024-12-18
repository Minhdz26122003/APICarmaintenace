// Add accounts
<?php
include "../connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];  // Không mã hóa nếu không cần
    $sodienthoai = $data['sodienthoai'];
    $diachi = $data['diachi'];
    $vaitro = $data['vaitro'];

    $query = "INSERT INTO taikhoan (username, email, password, sodienthoai, diachi, vaitro) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssi', $username, $email, $password, $sodienthoai, $diachi, $vaitro);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thêm tài khoản thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Thêm tài khoản không thành công']);
    }

    $stmt->close();
}
