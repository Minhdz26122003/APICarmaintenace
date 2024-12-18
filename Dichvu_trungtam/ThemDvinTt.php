
<?php
include "../connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$_POST = json_decode(file_get_contents("php://input"), true);

if (is_null($_POST)) {
    echo json_encode(['success' => false, 'message' => 'Không nhận được dữ liệu']);
    error_log("Không nhận được dữ liệu từ yêu cầu JSON");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $iddichvu = isset($_POST['iddichvu']) ? $_POST['iddichvu'] : null;
    $idtrungtam = isset($_POST['idtrungtam']) ? $_POST['idtrungtam'] : null;
    $query = "INSERT INTO dichvu_trungtam (iddichvu, idtrungtam) VALUES (?, ?)";
    $stmt = $conn->prepare($query); 
    $stmt->bind_param('ii', $iddichvu, $idtrungtam);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thêm mã dịch vụ thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Thêm mã dịch vụ không thành công']);
    }

    $stmt->close();
}
?>