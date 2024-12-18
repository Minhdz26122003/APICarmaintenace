<?php
include "D:/Xampp/htdocs/myapi/connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

try {
    $sql = "SELECT COUNT(idtrungtam) AS total_centers FROM trungtam";
    $result = $conn->query($sql);   

    if ($result === false) {
        throw new Exception($conn->error);
    }

    $total_centers = $result->fetch_assoc()['total_centers'];

    echo json_encode([
        'success' => true,
        'total_centers' => $total_centers,
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving center statistics',
        'error' => $e->getMessage()
    ]);
}

$conn->close();
?>
