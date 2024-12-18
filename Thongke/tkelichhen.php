<?php
include "D:/Xampp/htdocs/myapi/connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
try {
    $sql = "SELECT COUNT(idlichhen) AS solich FROM Lichhen ";
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception($conn->error);
    }

    $solich = $result->fetch_assoc()['solich'];

    echo json_encode([
        'success' => true,
        'solich' => $solich,
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving appointment statistics',
        'error' => $e->getMessage()
    ]);
}

$conn->close();
?>
