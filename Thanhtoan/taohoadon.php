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

// Tạo thanh toán
$query = "INSERT INTO Thanhtoan (idlichhen, ngaythanhtoan, hinhthuc, tongtien) VALUES (?, NOW(), ?, ?)";
$stmt = $conn->prepare($query);

$stmt->bind_param('isi' , $data['idlichhen'], $data['hinhthuc'], $data['tongtien']);

if ($stmt->execute()) {
    $idthanhtoan = $conn->insert_id;
    echo json_encode(['success' => true, 'message' => 'Tạo thanh toán thành công', 'idthanhtoan' => $idthanhtoan]);
} else {
   
    echo json_encode([
        'success' => false,
        'message' => 'Có lỗi xảy ra: ' . mysqli_error($conn)
    ]);
    
}

$conn->close();
?>
