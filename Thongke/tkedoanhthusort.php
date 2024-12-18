<?php
include "D:/Xampp/htdocs/myapi/connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

try {
    $sql = "
        SELECT SUM(tongtien) AS doanhthu FROM thanhtoan";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        throw new Exception($conn->error);
    }

    // Lấy dữ liệu từ kết quả truy vấn
    $data = $result->fetch_assoc();
    $doanhthu = $data['doanhthu'] ? (float)$data['doanhthu'] : 0;

    // Trả về kết quả JSON
    echo json_encode([
        'success' => true,    
        'doanhthu' => $doanhthu,

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
