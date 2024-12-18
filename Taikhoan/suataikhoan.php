
<?php
include "../connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $iduser = $data['iduser'];
    $username = $data['username'];
    $email = $data['email'];
    $password = ($data['password']);
    $sodienthoai = $data['sodienthoai'];
    $diachi = $data['diachi'];
    $vaitro = $data['vaitro'];

    $query = "UPDATE Taikhoan SET username = ?, email = ?, password = ?, sodienthoai = ?, diachi = ?, vaitro=? WHERE iduser = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssis', $username, $email, $password, $sodienthoai, $diachi, $vaitro, $iduser);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật tài khoản thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cập nhật tài khoản không thành công']);
    }

    $stmt->close();
}
?>