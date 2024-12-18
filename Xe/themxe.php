
<?php
include "D:/Xampp/htdocs/myapi/connect.php"; 
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);
if($_SERVER['REQUEST_METHOD']=='POST') {
   
    // Kiểm tra các trường bắt buộc
    if (!isset($data['idxe'], $data['iduser'], $data['hangxe'], $data['namsx'])) {
        throw new Exception("Thiếu thông tin cần thiết.");
    }

    // Chuẩn bị dữ liệu để thêm vào cơ sở dữ liệu
    $idxe = ($data['idxe']);
    $iduser = ($data['iduser']);
    $hangxe = ($data['hangxe']);
    $namsx = ($data['namsx']);

    // Kiểm tra xem `idxe` đã tồn tại chưa
    $query = "SELECT * FROM xe WHERE idxe = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Lỗi chuẩn bị truy vấn: " . $conn->error);
    }

    $stmt->bind_param("s", $idxe);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Đóng câu truy vấn
        $stmt->close();
        // Trả về lỗi nếu `idxe` đã tồn tại
        echo json_encode([
            'success' => false,
            'message' => "Xe với id này đã tồn tại.",
        ]);
        exit;
    }

    // Chèn xe mới vào cơ sở dữ liệu
    $query = "INSERT INTO xe(idxe, iduser, hangxe, namsx) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
   
    $stmt->bind_param('sisi', $idxe, $iduser, $hangxe, $namsx);
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => "Thêm xe thành công.",
        ]);
    } else {
        throw new Exception("Lỗi thực thi truy vấn: " . $stmt->error);
    }

    // Đóng kết nối và câu truy vấn
    $stmt->close();
    $conn->close();

} 
?>
