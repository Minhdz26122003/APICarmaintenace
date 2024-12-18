<?php
include "D:/Xampp/htdocs/myapi/connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$year = isset($_GET['year']) ? (int)$_GET['year'] : null;

try {
    if (!$year) {
        throw new Exception("Year parameter is required.");
    }
    $sql = "
        SELECT 
            MONTH(ngayhen) AS appointment_month,
            COUNT(idlichhen) AS total_appointments
        FROM Lichhen
        WHERE YEAR(ngayhen) = ?
        GROUP BY MONTH(ngayhen)
        ORDER BY appointment_month ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        throw new Exception($conn->error);
    }

    $data = $result->fetch_all(MYSQLI_ASSOC);
    $total_appointments = array_sum(array_column($data, 'total_appointments'));

    // Trả về kết quả JSON
    echo json_encode([
        'success' => true,
        'statistics' => [
            'year' => $year,
            'total_appointments' => $total_appointments,
            'monthly_appointments' => $data
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving statistics',
        'error' => $e->getMessage()
    ]);
}
$conn->close();
?>