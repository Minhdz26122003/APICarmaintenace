<?php 
include "../connect.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Lấy dữ liệu từ yêu cầu JSON
$_POST = json_decode(file_get_contents("php://input"), true);

// Kiểm tra nếu dữ liệu bị null
if (is_null($_POST)) {
    echo json_encode(['success' => false, 'message' => 'Không nhận được dữ liệu']);
    error_log("Không nhận được dữ liệu từ yêu cầu JSON");
    exit;
}

// Xử lý yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tendichvu = isset($_POST['tendichvu']) ? $_POST['tendichvu'] : null;
    $mota = isset($_POST['mota']) ? $_POST['mota'] : null;
    $gia = isset($_POST['gia']) ? $_POST['gia'] : null;
    $hinhanh = isset($_POST['hinhanh']) ? $_POST['hinhanh'] : null;
    $thoigianth = isset($_POST['thoigianth']) ? $_POST['thoigianth'] : null;

    if (empty($tendichvu) || empty($mota) || empty($gia) || empty($hinhanh) || empty($thoigianth)) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        error_log("Thiếu dữ liệu đầu vào");
        exit;
    }

    $query = "INSERT INTO dichvu (tendichvu, mota, gia, hinhanh, thoigianth) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssdss', $tendichvu, $mota, $gia, $hinhanh, $thoigianth);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Thêm dịch vụ thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Thêm dịch vụ không thành công']);
    }

    $stmt->close();
}
?>

<!-- <?php 
include "../connect.php";
require 'vendor/autoload.php'; // Nạp thư viện Cloudinary
use Cloudinary\Cloudinary;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Cấu hình Cloudinary
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'duemkqxyp', // Thay bằng thông tin của bạn
        'api_key' => '651935565868957',
        'api_secret' => 'HrmkKLcNGmJyJtqgZ5H-Dtn_COg',
    ],
]);

// Lấy dữ liệu từ yêu cầu JSON
$_POST = json_decode(file_get_contents("php://input"), true);

// Kiểm tra nếu dữ liệu bị null
if (is_null($_POST)) {
    echo json_encode(['success' => false, 'message' => 'Không nhận được dữ liệu']);
    error_log("Không nhận được dữ liệu từ yêu cầu JSON");
    exit;
}

// Xử lý yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tendichvu = isset($_POST['tendichvu']) ? $_POST['tendichvu'] : null;
    $mota = isset($_POST['mota']) ? $_POST['mota'] : null;
    $gia = isset($_POST['gia']) ? $_POST['gia'] : null;
    $hinhanh = isset($_FILES['hinhanh']) ? $_FILES['hinhanh'] : null; // Dùng FILES để nhận ảnh
    $thoigianth = isset($_POST['thoigianth']) ? $_POST['thoigianth'] : null;

    // Kiểm tra dữ liệu
    if (empty($tendichvu) || empty($mota) || empty($gia) || empty($hinhanh) || empty($thoigianth)) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        error_log("Thiếu dữ liệu đầu vào");
        exit;
    }

    try {
        // Upload ảnh lên Cloudinary
        $filePath = $hinhanh['tmp_name']; // Đường dẫn file tạm thời
        $uploadResult = $cloudinary->uploadApi()->upload($filePath, [
            'folder' => 'ImgProject4', // Thư mục trong Cloudinary
        ]);

        // Lấy URL ảnh
        $imageUrl = $uploadResult['secure_url'];

        // Chèn dữ liệu vào CSDL
        $query = "INSERT INTO dichvu (tendichvu, mota, gia, hinhanh, thoigianth) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssdss', $tendichvu, $mota, $gia, $imageUrl, $thoigianth);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Thêm dịch vụ thành công', 'url' => $imageUrl]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Thêm dịch vụ không thành công']);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi upload ảnh', 'error' => $e->getMessage()]);
    }
}
?> -->
