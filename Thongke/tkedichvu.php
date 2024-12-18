<?php
include "D:/Xampp/htdocs/myapi/connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

try {
    $sql = "SELECT COUNT(iddichvu) AS total_services FROM Dichvu";
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception($conn->error);
    }

    $total_services = $result->fetch_assoc()['total_services'];

    echo json_encode([
        'success' => true,
        'total_services' => $total_services,
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving service statistics',
        'error' => $e->getMessage()
    ]);
}

$conn->close();
?>
