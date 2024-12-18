<?php

header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookingapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT dvtt.iddvtt, tt.tentrungtam, dv.tendichvu
FROM Dichvu_trungtam dvtt
JOIN Trungtam tt ON dvtt.idtrungtam = tt.idtrungtam
JOIN Dichvu dv ON dvtt.iddichvu = dv.iddichvu;
";
    $result = $conn->query($sql);

   if ($result->num_rows > 0) {
    $dvtt = [];

    while ($row = $result->fetch_assoc()) {
        $dvtt[] = $row;
    }

    echo json_encode($dvtt);
    } else {
        echo json_encode(["message" => "Không tìm thấy khuyến mãi nào."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Phương thức không được hỗ trợ. Chỉ hỗ trợ GET."]);
}

$conn->close();
?>
