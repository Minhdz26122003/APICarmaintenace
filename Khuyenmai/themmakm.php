
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
    $mota = isset($_POST['mota']) ? $_POST['mota'] : null;
    $giatri = isset($_POST['giatri']) ? $_POST['giatri'] : null;
    $ngaybatdau = isset($_POST['ngaybatdau']) ? $_POST['ngaybatdau'] : null;
    $ngayketthuc = isset($_POST['ngayketthuc']) ? $_POST['ngayketthuc'] : null;
    $trangthai = isset($_POST['trangthai']) ? $_POST['trangthai'] : null;
    
    $query = "INSERT INTO khuyenmai (mota, giatri, ngaybatdau, ngayketthuc, trangthai) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query); 
    $stmt->bind_param('sdsss', $mota, $giatri, $ngaybatdau, $ngayketthuc, $trangthai);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thêm mã khuyến mãi thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Thêm mã khuyến mãi không thành công']);
    }

    $stmt->close();
}
?>