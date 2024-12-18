<?php
include "../connect.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $noidung = isset($_GET['noidung']) ? trim($_GET['noidung']) : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'ngaybinhluan';
    $order = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) 
             ? strtoupper($_GET['order']) 
             : 'ASC';

    // Khởi tạo câu truy vấn
    $query = "SELECT dg.iddanhgia, dg.idtrungtam, dg.iduser, dg.noidung, dg.danhgia, dg.ngaybinhluan, tk.username, tt.tentrungtam
            FROM danhgia_binhluan AS dg 
            JOIN taikhoan AS tk 
            ON dg.iduser = tk.iduser
            JOIN trungtam AS tt
            ON dg.idtrungtam = tt.idtrungtam
             WHERE 1=1";
    $params = [];
    $types = "";

    // Thêm điều kiện tìm kiếm
    if ($noidung) {
        $query .= " AND LOWER(noidung) LIKE ?";
        $params[] = "%" . strtolower($noidung) . "%";
        $types .= "s";
    }

    // Thêm sắp xếp
    $query .= " ORDER BY $sort $order";

    // Chuẩn bị và thực thi câu lệnh
    $stmt = $conn->prepare($query);
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        echo json_encode(['success' => true, 'reviews' => $reviews]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn']);
    }

    $stmt->close();
    $conn->close();
}
?>
