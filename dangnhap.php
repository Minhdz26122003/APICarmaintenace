<?php
include "D:/Xampp/htdocs/myapi/connect.php"; // Hoặc đường dẫn chính xác đến connect.php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra xem email và password có trong dữ liệu không
if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $matkhau = $data['password'];

    // Truy vấn cơ sở dữ liệu để xác thực thông tin đăng nhập
    $query = 'SELECT * FROM `taikhoan` WHERE `email` = "' . $email . '" AND `password` = "' . $matkhau . '"';
    $result = mysqli_query($conn, $query);
    $numrow = mysqli_num_rows($result);

    if ($numrow > 0) {
        $user = mysqli_fetch_assoc($result);
        $response = [
            'success' => true,
            'message' => "Đăng nhập thành công",
            'user' => [
                'iduser' => $user['iduser'],
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => $user['password'],
                'sodienthoai' => $user['sodienthoai'],
                'diachi' => $user['diachi'],
                'vaitro' => $user['vaitro']
            ],
        ];
    } else {
        // Nếu không tìm thấy tài khoản, hãy tìm kiếm lại với username
        $query = 'SELECT * FROM `taikhoan` WHERE `username` = "' . $email . '"';
        $result = mysqli_query($conn, $query);
        $numrow = mysqli_num_rows($result);

        if ($numrow > 0) {
            $user = mysqli_fetch_assoc($result);
            if ($user['username'] === $email && $user['password'] === $matkhau) {
                $response = [
                    'success' => true,
                    'message' => "Đăng nhập thành công",
                    'user' => [
                        'iduser' => $user['iduser'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'password' => $user['password'],
                        'sodienthoai' => $user['sodienthoai'],
                        'diachi' => $user['diachi'],
                        'vaitro' => $user['vaitro']
                    ],
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => "Username không đúng",
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => "Username không đúng",
            ];
        }
    }
} else {
    $response = [
        'success' => false,
        'message' => "Thiếu email hoặc mật khẩu",
    ];
}

// Trả về phản hồi JSON
echo json_encode($response);
?>
