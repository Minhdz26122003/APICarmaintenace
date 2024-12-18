<?php
include "../connect.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $tentrungtam = isset($_GET['tentrungtam']) ? trim($_GET['tentrungtam']) : '';
    $diachi = isset($_GET['diachi']) ? trim($_GET['diachi']) : '';

    if ($tentrungtam || $diachi) {
        // Điều kiện WHERE linh hoạt theo các tham số
        $query = "SELECT * FROM trungtam WHERE 1=1";
        $params = [];
        $types = "";

        if ($tentrungtam) {
            $query .= " AND LOWER(tentrungtam) LIKE ?";
            $params[] = "%" . strtolower($tentrungtam) . "%";
            $types .= "s";
        }

        if ($diachi) {
            $query .= " AND LOWER(diachi) LIKE ?";
            $params[] = "%" . strtolower($diachi) . "%";
            $types .= "s";
        }

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
    } else {
        // Nếu không có từ khóa tìm kiếm, trả về tất cả trung tâm
        $query = "SELECT * FROM trungtam";
        $stmt = $conn->prepare($query);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $centers = [];
        while ($row = $result->fetch_assoc()) {
            $centers[] = $row;
        }
        
        echo json_encode(['success' => true, 'centers' => $centers]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Query execution failed.']);
    }

    $stmt->close();
}

?>
