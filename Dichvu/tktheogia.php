<?php
include "../connect.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $tendichvu = isset($_GET['tendichvu']) ? trim($_GET['tendichvu']) : '';
    $minPrice = isset($_GET['minPrice']) ? (int)$_GET['minPrice'] : 0;
    $maxPrice = isset($_GET['maxPrice']) ? (int)$_GET['maxPrice'] : 10000000;
    $query = "SELECT * FROM dichvu WHERE gia BETWEEN ? AND ?";
    if (!empty($tendichvu)) {
        $query .= " AND LOWER(tendichvu) LIKE ?";
    }

    $stmt = $conn->prepare($query);

    if (!empty($tendichvu)) {
        $search = '%' . strtolower($tendichvu) . '%';
        $stmt->bind_param('iis', $minPrice, $maxPrice, $search);
    } else {
        $stmt->bind_param('ii', $minPrice, $maxPrice);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $services = [];
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }

        echo json_encode(['success' => true, 'services' => $services]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Query failed.']);
    }
    $stmt->close();
}

?>
