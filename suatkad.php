<?php
include "D:/Xampp/htdocs/myapi/connect.php"; // Hoặc đường dẫn chính xác đến connect.php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['username']) && isset($data['email']) && isset($data['phone']) && isset($data['address'])) {
    $username = $data['username'];
    $email = $data['email'];
    $phone = $data['phone'];
    $address = $data['address'];

    // Cập nhật thông tin người dùng
    $query = "UPDATE `taikhoan` SET `email`='$email', `soienthoai`='$phone', `diachi`='$address' WHERE `username`='$username'";
    if (mysqli_query($conn, $query)) {
        $response = [
            'success' => true,
            'message' => "Cập nhật thông tin thành công",
            'user' => [
                'username' => $username,
                'email' => $email,
                'sodienthoai' => $phone,
                'diachi' => $address,
            ],
        ];
    } else {
        $response = [
            'success' => false,
            'message' => "Cập nhật thông tin không thành công.",
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => "Thiếu thông tin cần thiết.",
    ];
}

// Trả về phản hồi JSON
echo json_encode($response);
?>
