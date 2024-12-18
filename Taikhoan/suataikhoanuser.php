<?php
include "D:/Xampp/htdocs/myapi/connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "bookingapp";    

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['iduser'], $data['username'], $data['email'], $data['sodienthoai'], $data['diachi'])) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit();
    }

    $iduser = $data['iduser'];
    $username = $data['username'];
    $email = $data['email'];
    $sodienthoai = $data['sodienthoai'];
    $diachi = $data['diachi'];

    // Kiểm tra dữ liệu nhận được
    error_log("Received Data: iduser = $iduser, username = $username, email = $email, sodienthoai = $sodienthoai, diachi = $diachi");

    // Kiểm tra dữ liệu hiện tại trong cơ sở dữ liệu
    $queryCheck = "SELECT username, email, sodienthoai, diachi FROM Taikhoan WHERE iduser = ?";
    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bind_param('i', $iduser);
    $stmtCheck->execute();
    $stmtCheck->store_result();
    $stmtCheck->bind_result($currentUsername, $currentEmail, $currentSodienthoai, $currentDiachi);
    $stmtCheck->fetch();
    
    // Kiểm tra xem dữ liệu có thay đổi không
    if ($username == $currentUsername && $email == $currentEmail && $sodienthoai == $currentSodienthoai && $diachi == $currentDiachi) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không thay đổi']);
        exit();
    }

    // Câu lệnh UPDATE
    $query = "UPDATE Taikhoan SET username = ?, email = ?, sodienthoai = ?, diachi = ? WHERE iduser = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn SQL']);
        exit();
    }

    // Liên kết tham số
    $stmt->bind_param('ssssi', $username, $email, $sodienthoai, $diachi, $iduser);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật tài khoản thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không thay đổi']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Cập nhật tài khoản không thành công: ' . $stmt->error]);
    }

    $stmt->close();
    $stmtCheck->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
}
?>
