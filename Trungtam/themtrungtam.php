<?php
include "../connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");


$_POST = json_decode(file_get_contents("php://input"), true);
// Kiểm tra nếu dữ liệu bị null
if (is_null($_POST)) {
    echo json_encode(['success' => false, 'message' => 'Không nhận được dữ liệu']);
    error_log("Không nhận được dữ liệu từ yêu cầu JSON");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tentrungtam =isset( $_POST['tentrungtam']) ? $_POST['tentrungtam'] : null;
    $diachi = isset($_POST['diachi']) ? $_POST['diachi'] : null;
    $sodienthoai = isset($_POST['sodienthoai']) ? $_POST['sodienthoai'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $hinhanh = isset($_POST['hinhanh']) ? $_POST['hinhanh'] : null;
    $toadox = isset($_POST['toadox']) ? $_POST['toadox'] : null;
    $toadoy =isset($_POST['toadoy']) ? $_POST['toadoy'] : null;

    if (empty($tentrungtam)) {
        error_log("tentrungtam không hợp lệ");
    }
    if (empty($diachi)) {
        error_log("diachi không hợp lệ");
    }
    if (empty($sodienthoai)) {
        error_log("sodienthoai không hợp lệ");
    }
    if (empty($email)) {
        error_log("email không hợp lệ");
    }
    if (empty($hinhanh)) {
        error_log("hinhanh không hợp lệ");
    }
    if (empty($toadox)) {
        error_log("x_location không hợp lệ");
    }
    if (empty($toadoy)) {
        error_log("y_location không hợp lệ");
    }

    $query = "INSERT INTO trungtam (tentrungtam, diachi, sodienthoai, email, hinhanh, toadox, toadoy) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssdd', $tentrungtam, $diachi, $sodienthoai,$email,$hinhanh,$toadox,$toadoy);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thêm trung tâm thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Thêm trung tâm không thành công']);
    }

    $stmt->close();
}
?>