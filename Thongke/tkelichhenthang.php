<?php
include "D:/Xampp/htdocs/myapi/connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$month = isset($_GET['month']) ? $_GET['month'] : null;
$year = isset($_GET['year']) ? $_GET['year'] : null;

try {
    $where_clause = '';
    if ($month && $year) {
        $start_date = "$year-$month-01";
        $end_date = date("Y-m-d", strtotime("+1 month", strtotime($start_date)));
        $where_clause = "WHERE ngayhen >= '$start_date' AND ngayhen < '$end_date'";
    } elseif ($year) {
        $where_clause = "WHERE YEAR(ngayhen) = $year";
    }

    // Câu truy vấn thống kê
    $sql = "SELECT 
                COUNT(DISTINCT iduser) AS total_users,
                COUNT(idlichhen) AS total_appointments,
                DATE(ngayhen) AS appointment_date,
                COUNT(idlichhen) AS daily_appointments
            FROM Lichhen
            $where_clause
            GROUP BY DATE(ngayhen)";
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception($conn->error);
    }

    $data = $result->fetch_all(MYSQLI_ASSOC);
    $total_users_query = "SELECT COUNT(DISTINCT iduser) AS total_users FROM Lichhen";
    $total_users_result = $conn->query($total_users_query);
    $total_users = $total_users_result->fetch_assoc()['total_users'];

    echo json_encode([
        'success' => true,
        'statistics' => [
            'total_users' => $total_users,
            'total_appointments' => array_sum(array_column($data, 'daily_appointments')),
            'daily_appointments' => $data
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