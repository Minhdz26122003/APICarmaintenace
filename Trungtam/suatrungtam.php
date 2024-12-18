// Update center
<?php
include "../connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");


// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $idtrungtam = $data['idtrungtam'];
    $tentrungtam = $data['tentrungtam'];
    $diachi = $data['diachi'];
    $sodienthoai = $data['sodienthoai'];
    $email = $data['email'];
    $hinhanh = $data['hinhanh'];
    $toadox = $data['toadox'];
    $toadoy = $data['toadoy'];

    $query = "UPDATE trungtam SET tentrungtam = ?, diachi = ?, sodienthoai = ?,email= ? ,hinhanh = ? ,toadox = ?, toadoy = ? WHERE idtrungtam = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssddi', $tentrungtam, $diachi, $sodienthoai,$email,$hinhanh ,$toadox, $toadoy, $idtrungtam);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật trung tâm thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cập nhật trung tâm không thành công']);
    }

    $stmt->close();
}
?>
