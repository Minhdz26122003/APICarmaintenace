<?php
include "../connect.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $iduser = isset($_GET['iduser']) ? intval($_GET['iduser']) : 0; 
    $username = isset($_GET['username']) ? trim($_GET['username']) : '';

    if ($username) {
        $query = "SELECT * FROM taikhoan WHERE LOWER(username) LIKE ? AND iduser != ?";
        $stmt = $conn->prepare($query);
        $search = "%" . strtolower($username) . "%";
        $stmt->bind_param('si', $search, $iduser);
    } else {
        $query = "SELECT * FROM taikhoan WHERE iduser != ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $iduser); 
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $accounts = [];
        while ($row = $result->fetch_assoc()) {
            $accounts[] = $row;
        }

        // Trả về kết quả JSON
        echo json_encode(['success' => true, 'accounts' => $accounts]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Query execution failed.']);
    }

    $stmt->close();
}

?>
