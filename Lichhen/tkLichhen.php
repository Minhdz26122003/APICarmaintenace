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
    if (strtotime($start_date) && strtotime($end_date)) {
        
        $query = "
        SELECT 
        lh.idlichhen,
        lh.iduser,
        tk.username,
        lh.idxe,
        dv.iddichvu,
        dv.tendichvu,
        lh.idtrungtam,
        tt.tentrungtam,
        lh.ngayhen, 
        lh.thoigianhen,
        lh.trangthai,
        lh.lydohuy,
        COALESCE(GROUP_CONCAT(dv.tendichvu SEPARATOR ', '), '') AS tendichvu
    FROM 
        lichhen lh
    JOIN 
        lichhen_dichvu lhdv ON lh.idlichhen = lhdv.idlichhen
    JOIN 
        dichvu dv ON lhdv.iddichvu = dv.iddichvu
    JOIN 
        trungtam tt ON lh.idtrungtam = tt.idtrungtam
    JOIN 
        taikhoan tk ON lh.iduser = tk.iduser
        
    WHERE lh.ngayhen BETWEEN ? AND ?

    GROUP BY 
        lh.idlichhen

        ";
        if ($stmt = $conn->prepare($query)) {
      
            $stmt->bind_param('ss', $start_date, $end_date);
            $stmt->execute();
            $result = $stmt->get_result();

            $appointments = [];
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
        
            if (empty($appointments)) {
                echo json_encode(['success' => false, 'message' => 'Không có lịch hẹn nào trong khoảng thời gian này']);
            } else {
               
                echo json_encode(['success' => true, 'appointments' => $appointments]);
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
?>