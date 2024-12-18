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
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Lỗi kết nối cơ sở dữ liệu: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['iduser']) && ctype_digit($_GET['iduser'])) {
        $iduser = intval($_GET['iduser']);

        $sql = "SELECT * FROM taikhoan WHERE iduser = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $iduser);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $tkList = [];
            while ($row = $result->fetch_assoc()) {
                // Escape các ký tự đặc biệt trong dữ liệu trả về (nếu cần thiết)
                $tkList[] = array_map("htmlspecialchars", $row);
            }
            http_response_code(200);
            echo json_encode(["success" => true, "tk" => $tkList]);
        } else {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Không tìm thấy tài khoản nào."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "iduser không hợp lệ."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Phương thức không được hỗ trợ. Chỉ hỗ trợ GET."]);
}

$conn->close();
?>
