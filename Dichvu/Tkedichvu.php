// Service statistics
<?php
include "../connect.php";
header('Content-Type: application/json');

// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = "SELECT tendichvu, COUNT(Lichhen.iddichvu) as soluong FROM Dichvu 
              JOIN Lichhen ON Dichvu.iddichvu = Lichhen.iddichvu 
              GROUP BY Dichvu.iddichvu";
    $result = $conn->query($query);

    $statistics = [];
    while ($row = $result->fetch_assoc()) {
        $statistics[] = $row;
    }

    echo json_encode(['success' => true, 'statistics' => $statistics]);
}
?>