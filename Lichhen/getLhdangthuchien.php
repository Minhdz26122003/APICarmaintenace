<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8"); 
include "D:/Xampp/htdocs/myapi/connect.php"; 

// Xử lý yêu cầu GET để lấy lịch hẹn và dịch vụ kèm theo
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['iduser'])) {
        $iduser = $_GET['iduser'];
        $sql = "
    SELECT lh.idlichhen, lh.idxe, lh.ngayhen, lh.thoigianhen, lh.ngaytao,lh.tongtien, lh.trangthai, GROUP_CONCAT(dv.tendichvu) as tendichvu
    FROM lichhen lh
    JOIN lichhen_dichvu lhdv ON lh.idlichhen = lhdv.idlichhen
    JOIN dichvu dv ON lhdv.iddichvu = dv.iddichvu
    WHERE lh.iduser = ? AND lh.trangthai = 1
    GROUP BY lh.idlichhen";


        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $iduser); 
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
        echo json_encode(["success" => false, "message" => "Thiếu tham số iduser."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Phương thức không được hỗ trợ."]);
}

$conn->close();
?>
