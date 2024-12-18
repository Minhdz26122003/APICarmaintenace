// Update appointment
<?php
include "D:/Xampp/htdocs/myapi/connect.php"; // Hoặc đường dẫn chính xác đến connect.php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Lấy dữ liệu từ $data thay vì $_POST
    $idlichhen = $data['idlichhen'];
    $ngayhen = $data['ngayhen'];
    $thoigianhen = $data['thoigianhen'];
    $trangthai = $data['trangthai'];

    // Câu truy vấn cập nhật
    $query = "UPDATE Lichhen SET ngayhen = ?, thoigianhen = ?, trangthai = ? WHERE idlichhen = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssi', $ngayhen, $thoigianhen, $trangthai, $idlichhen);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật lịch hẹn thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cập nhật lịch hẹn không thành công']);
    }

    $stmt->close();
}
?>
