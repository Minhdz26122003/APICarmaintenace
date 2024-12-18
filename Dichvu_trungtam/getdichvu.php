<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookingapp";

// Kết nối database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý yêu cầu GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Lấy idtrungtam từ query string
    $idtrungtam = isset($_GET['idtrungtam']) ? $_GET['idtrungtam'] : null;

    if ($idtrungtam === null) {
        echo json_encode(["message" => "Thiếu tham số idtrungtam."]);
        http_response_code(400);
        $conn->close();
        exit();
    }

    // Câu lệnh SQL
    $sql = "
        SELECT dv.iddichvu, dv.tendichvu
        FROM Dichvu dv
        WHERE dv.iddichvu NOT IN (
            SELECT iddichvu
            FROM Dichvu_trungtam
            WHERE idtrungtam = ?
        )
    ";

    // Chuẩn bị và thực thi câu lệnh SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idtrungtam); // "i" là kiểu số nguyên
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra dữ liệu trả về
    if ($result->num_rows > 0) {
        $services = [];
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        echo json_encode($services); // Trả về dữ liệu dạng JSON
    } else {
        echo json_encode(["message" => "Không tìm thấy dịch vụ nào."]);
    }

    $stmt->close();
} else {
    http_response_code(405); // Phương thức không được hỗ trợ
    echo json_encode(["message" => "Phương thức không được hỗ trợ. Chỉ hỗ trợ GET."]);
}

$conn->close();
?>
