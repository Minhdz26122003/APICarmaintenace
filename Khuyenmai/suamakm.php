// Update promo code
<?php
include "../connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Lấy dữ liệu từ yêu cầu JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $idkm = $data['idkm'];
    $mota = $data['mota'];
    $giatri = $data['giatri'];
    $ngaybatdau = $data['ngaybatdau'];
    $ngayketthuc = $data['ngayketthuc'];
    $trangthai = $data['trangthai'];

    $query = "UPDATE Khuyenmai SET mota = ?, giatri = ?, ngaybatdau = ?, ngayketthuc = ?, trangthai = ? WHERE idkm = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sdsssi', $mota, $giatri, $ngaybatdau, $ngayketthuc, $trangthai, $idkm);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật mã khuyến mãi thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cập nhật mã khuyến mãi không thành công']);
    }

    $stmt->close();
}
?>