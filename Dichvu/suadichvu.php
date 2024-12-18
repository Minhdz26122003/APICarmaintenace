// Update service
<?php
include "../connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $iddichvu = $data['iddichvu'];
    $tendichvu = $data['tendichvu'];
    $mota = $data['mota'];
    $gia = $data['gia'];
    $hinhanh = $data['hinhanh'];
    $thoigianth = $data['thoigianth'];

    $query = "UPDATE dichvu SET tendichvu = ?, mota = ?, gia = ? , hinhanh = ?,thoigianth = ? WHERE iddichvu = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssdsss', $tendichvu, $mota, $gia, $hinhanh, $thoigianth, $iddichvu);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật dịch vụ thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cập nhật dịch vụ không thành công']);
    }

    $stmt->close();
}
?>