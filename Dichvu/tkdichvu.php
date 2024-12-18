<?php
include "../connect.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $tendichvu = isset($_GET['tendichvu']) ? trim($_GET['tendichvu']) : '';

    if ($tendichvu) {
        // Tìm kiếm dịch vụ theo tên
        $query = "SELECT * FROM dichvu WHERE LOWER(tendichvu) LIKE ?";
        $stmt = $conn->prepare($query);
        $search = "%" . strtolower($tendichvu) . "%";
        $stmt->bind_param('s', $search);
    } else {
        // Nếu không có từ khóa tìm kiếm, trả về tất cả dịch vụ
        $query = "SELECT * FROM dichvu";
        $stmt = $conn->prepare($query);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $services = [];
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        
        // Đảm bảo trả về đúng cấu trúc dữ liệu với khóa 'services'
        echo json_encode(['success' => true, 'services' => $services]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Query execution failed.']);
    }

    $stmt->close();
}
?>
