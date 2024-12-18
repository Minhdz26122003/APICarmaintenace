<?php
include "D:/Xampp/htdocs/myapi/connect.php";


header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$data = json_decode(file_get_contents("php://input"));

$username = isset($data->username) ? $conn->real_escape_string($data->username) : '';
$email = isset($data->email) ? $conn->real_escape_string($data->email) : '';
$password = isset($data->password) ? $conn->real_escape_string($data->password) : '';
$sodienthoai = isset($data->sodienthoai) ? $conn->real_escape_string($data->sodienthoai) : '';
$diachi = isset($data->diachi) ? $conn->real_escape_string($data->diachi) : '';


if (empty($username) || empty($email) || empty($password) || empty($sodienthoai) || empty($diachi)) {
    echo json_encode([
        'success' => false,
        'message' => "Vui lòng nhập đầy đủ thông tin.",
    ]);
    exit;
}

try {

    if ($conn->connect_error) {
        throw new Exception("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
    }

    $query = "SELECT * FROM `taikhoan` WHERE `email` = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Lỗi chuẩn bị truy vấn: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt->close();
        echo json_encode([
            'success' => false,
            'message' => "Email đã tồn tại.",
        ]);
    } else {
        $query = "INSERT INTO `taikhoan`(`username`, `email`, `password`, `sodienthoai`, `diachi`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }

        $stmt->bind_param("sssss", $username, $email, $password, $sodienthoai, $diachi);
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => "Đăng ký thành công.",
            ]);
        } else {
            throw new Exception("Lỗi thực thi truy vấn: " . $stmt->error);
        }

        $stmt->close();
    }

    $conn->close();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
    exit;
}
?>
