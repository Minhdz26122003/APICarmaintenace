<?php
include "D:/Xampp/htdocs/myapi/connect.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT dg.iddanhgia, dg.idtrungtam, dg.iduser, dg.noidung, dg.danhgia, dg.ngaybinhluan, tk.username, tt.tentrungtam
            FROM danhgia_binhluan AS dg 
            JOIN taikhoan AS tk 
            ON dg.iduser = tk.iduser
            JOIN trungtam AS tt
            ON dg.idtrungtam = tt.idtrungtam";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $result = $stmt->get_result();
        $reviews = [];

        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        if (count($reviews) > 0) {
            echo json_encode(['success' => true, 'ratings' => $reviews]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy đánh giá nào.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn cơ sở dữ liệu.']);
    }
    $conn->close();
}
?>
