<?php
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "bookingapp";     
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Lấy iduser từ tham số GET
    $iduser = isset($_GET['iduser']) ? intval($_GET['iduser']) : 0; 

    
    $sql = "SELECT * FROM taikhoan WHERE iduser != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $iduser);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $taikhoanList = [];
        while ($row = $result->fetch_assoc()) {
            $taikhoanList[] = $row;
        }
        echo json_encode($taikhoanList);
    } else {
        echo json_encode(["message" => "Không tìm thấy tài khoản nào."]);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(["message" => "Phương thức không được hỗ trợ. Chỉ hỗ trợ GET."]);
}

$conn->close();
?>
