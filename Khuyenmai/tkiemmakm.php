<?php
// Bao gồm kết nối cơ sở dữ liệu
include "../connect.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
$giatri = isset($_GET['giatri']) ? trim((float)$_GET['giatri']) : 0;

// Kiểm tra nếu giá trị không hợp lệ
if ($giatri <= 0) {
    echo json_encode([
        'success' => false,
        'message' => "Giá trị không hợp lệ",
    ]);
    exit;
}

if($giatri){

    $query = "SELECT * FROM khuyenmai WHERE giatri >= ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("d", $giatri);

}else{
     // Nếu không có từ khóa tìm kiếm, trả về tất cả trung tâm
     $query = "SELECT * FROM khuyenmai";
     $stmt = $conn->prepare($query);
}


if ($stmt->execute()) {
    $result = $stmt->get_result();
    $sales = [];    
    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }
    
    // Đảm bảo trả về đúng cấu trúc dữ liệu
    echo json_encode(['success' => true, 'sales' => $sales]); // Đổi 'centers' thành 'sales'
} else {
    echo json_encode(['success' => false, 'message' => 'Query execution failed.']);
}

// Đóng kết nối
$stmt->close();
$conn->close();

}
?>
