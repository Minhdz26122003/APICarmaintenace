<?php
include "D:/Xampp/htdocs/myapi/connect.php"; 
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$data = json_decode(file_get_contents("php://input"), true);
if ($data === null) {
    echo json_encode(["success" => false, "message" => "Dữ liệu không hợp lệ!"]);
    exit();
}

// Tạo lịch hẹn
$query = "INSERT INTO Lichhen (iduser, idxe, idtrungtam, ngayhen, thoigianhen,tongtien, trangthai, ngaytao) VALUES (?, ?, ?, ?, ?, ?, 0, NOW())";
$stmt = $conn->prepare($query);

$stmt->bind_param('isissi', $data['iduser'], $data['idxe'], $data['idtrungtam'], $data['ngayhen'], $data['thoigianhen'], $data['tongtien']);

if ($stmt->execute()) {
    $idlichhen = $conn->insert_id;
    $serviceQuery = "INSERT INTO lichhen_dichvu (idlichhen, iddichvu) VALUES (?, ?)";
    $serviceStmt = $conn->prepare($serviceQuery);
    
    foreach ($data['selectedServices'] as $iddichvu) {
        $serviceStmt->bind_param('ii', $idlichhen, $iddichvu);
        $serviceStmt->execute();
    }
    
    echo json_encode(['success' => true, 'message' => 'Đặt lịch thành công']);
} else {
    // Kiểm tra và trả về lỗi từ MySQL
    echo json_encode([
        'success' => false, 
        'message' => 'Có lỗi xảy ra: ' . mysqli_error($conn)
    ]);
}

$conn->close();
?>
