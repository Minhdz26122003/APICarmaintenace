<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8"); 
include "D:/Xampp/htdocs/myapi/connect.php"; 

// Xử lý yêu cầu GET để lấy lịch hẹn và dịch vụ kèm theo
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['idlichhen'])) {
        $idlichhen = $_GET['idlichhen'];
        $sql = "SELECT 
        lh.idlichhen AS idlichhen,
        lh.iduser AS iduser,
        lh.idxe AS idxe, 
        lh.idtrungtam AS idtrungtam,
        lh.ngayhen AS ngayhen, 
        lh.thoigianhen AS thoigianhen,
        lh.trangthai AS trangthai,
        lh.lydohuy AS lydohuy,
        lh.tongtien AS tongtien,
        tk.username AS username,
        tk.sodienthoai AS sodienthoai,
        dv.iddichvu AS iddichvu,
        dv.tendichvu AS tendichvu,    
        tt.tentrungtam AS tentrungtam,
        
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
    WHERE lh.idlichhen = ? 
    GROUP BY 
        lh.idlichhen";


        if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $idlichhen); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $lichhenList = [];

            while ($row = $result->fetch_assoc()) {
                $lichhenList[] = $row;
            }

            echo json_encode(['success' => true, 'lichhen' => $lichhenList]);
        } else {
            echo json_encode(["success" => false, "message" => "Không tìm thấy lịch hẹn nào."]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi chuẩn bị câu truy vấn']);
    }
    } else {
        echo json_encode(["success" => false, "message" => "Thiếu tham số idlichhen."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Phương thức không được hỗ trợ."]);
}

$conn->close();
?>
