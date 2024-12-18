<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8"); 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookingapp";

// Kết nối đến database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['iduser'])) {
        $iduser = intval($_GET['iduser']);
        $sql = "SELECT dg.iddanhgia, dg.idtrungtam, dg.iduser, dg.noidung, dg.danhgia, dg.ngaybinhluan, tt.tentrungtam
        FROM danhgia_binhluan AS dg 
        JOIN trungtam AS tt
        ON dg.idtrungtam = tt.idtrungtam
        WHERE dg.iduser = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $iduser);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $dgList = [];
            while ($row = $result->fetch_assoc()) {
                $dgList[] = $row;
            }
            echo json_encode(["success" => true, "danhgia" => $dgList]);
        } else {
            echo json_encode(["success" => false, "message" => "Không tìm thấy đánh giá nào."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "iduser không được cung cấp."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Phương thức không được hỗ trợ. Chỉ hỗ trợ GET."]);
}

$conn->close();
?>
