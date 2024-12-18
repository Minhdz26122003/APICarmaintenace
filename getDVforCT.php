<?php
// Kết nối cơ sở dữ liệu
include "D:/Xampp/htdocs/myapi/connect.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Lấy id trung tâm từ URL
    $idtrungtam = isset($_GET['idtrungtam']) ? intval($_GET['idtrungtam']) : 0;

    if ($idtrungtam > 0) {
        // Truy vấn để lấy dịch vụ từ bảng trung gian dichvu_trungtam
        $query = "
            SELECT dichvu.*
            FROM dichvu_trungtam
            INNER JOIN dichvu ON dichvu_trungtam.iddichvu = dichvu.iddichvu
            WHERE dichvu_trungtam.idtrungtam = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idtrungtam);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $services = [];

            // Lưu kết quả vào mảng
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }

            // Trả về kết quả dưới dạng JSON
            echo json_encode(['success' => true, 'services' => $services]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Truy vấn thất bại.']);
        }

        // Đóng kết nối
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'ID trung tâm không hợp lệ.']);
    }
}

$conn->close();
?>
