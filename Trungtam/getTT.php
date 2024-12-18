<?php
// Cấu hình thông tin kết nối

header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");  // Cho phép các phương thức HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookingapp";

// Tạo kết nối với cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý yêu cầu GET để lấy tất cả trung tâm
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM trungtam";
    $result = $conn->query($sql);

     // Kiểm tra nếu có dữ liệu trả về
     if ($result->num_rows > 0) {
        $trungtamList = [];

        // Lấy từng hàng dữ liệu từ kết quả truy vấn
        while ($row = $result->fetch_assoc()) {
            // Kiểm tra nếu đường dẫn hình ảnh không bắt đầu bằng http hoặc https, thì coi đó là đường dẫn tương đối và thêm tiền tố
            if (!preg_match('/^(http|https):\/\//', $row['hinhanh'])) {
                $row['hinhanh'] = 'http://192.168.1.5/uploads/' . $row['hinhanh'];
            }

            $trungtamList[] = $row;
        }

        // Trả về kết quả dưới dạng JSON
        echo json_encode($trungtamList);
    } else {
        echo json_encode(["message" => "Không tìm thấy trung tâm nào."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Phương thức không được hỗ trợ. Chỉ hỗ trợ GET."]);
}

$conn->close();
?>
