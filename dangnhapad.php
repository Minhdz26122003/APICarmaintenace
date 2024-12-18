<?php
include "D:/Xampp/htdocs/myapi/connect.php"; // Hoặc đường dẫn chính xác đến connect.php


header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['username']) && isset($data['password'])) {
    $username = $data['username'];
    $matkhau = $data['password'];

    // Truy vấn cơ sở dữ liệu để xác thực thông tin đăng nhập
    $query = 'SELECT * FROM `taikhoan` WHERE `username` = "' . $username . '" AND `password` = "' . $matkhau . '" AND `vaitro`=2'; // Chỉ cho phép vaitro = 2
    $result = mysqli_query($conn, $query);
    $numrow = mysqli_num_rows($result);

    if ($numrow > 0) {
        $user = mysqli_fetch_assoc($result);
        $response = [
            'success' => true,
            'message' => "Đăng nhập thành công",
            'user' => [
                'id' => $user['iduser'],
                'username' => $user['username'],
                'email' => $user['email'],
                'sodienthoai' => $user['sodienthoai'],
                'diachi' => $user['diachi'],
                'vaitro' => $user['vaitro']
            ],
        ];
    } else {
        $response = [
            'success' => false,
            'message' => "Tên đăng nhập hoặc mật khẩu không đúng hoặc bạn không có quyền truy cập.",
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => "Thiếu tên đăng nhập hoặc mật khẩu.",
    ];
}

// Trả về phản hồi JSON
echo json_encode($response);
?>
