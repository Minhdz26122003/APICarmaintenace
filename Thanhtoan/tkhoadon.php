<?php
include "D:/Xampp/htdocs/myapi/connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$data = json_decode(file_get_contents("php://input"), true);
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];  

    // Kiểm tra định dạng ngày
    if (strtotime($start_date) && strtotime($end_date)) {
        $query = " SELECT * FROM thanhtoan WHERE ngaythanhtoan BETWEEN ? AND ?";

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('ss', $start_date, $end_date);
            $stmt->execute();
            $result = $stmt->get_result();

            $payments = [];
            while ($row = $result->fetch_assoc()) {
                $payments[] = $row;
            }

            if (empty($payments)) {
                echo json_encode(['success' => false, 'message' => 'Không có hóa đơn nào trong khoảng thời gian này']);
            } else {
                echo json_encode(['success' => true, 'payments' => $payments]);
            }
            $stmt->close(); 
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi chuẩn bị câu truy vấn']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ngày không hợp lệ']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Thiếu tham số ngày']);
}
