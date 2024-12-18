<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8"); 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookingapp";

// Kết nối đến database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý yêu cầu GET để lấy lịch hẹn và dịch vụ kèm theo
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // $sql = "
    //     SELECT lh.idlichhen,lh.iduser,idxe,lh.idtrungtam, lh.ngayhen, lh.thoigianhen, lh.trangthai, lh.lydohuy,
    //            COALESCE(GROUP_CONCAT(dv.tendichvu), '') as tendichvu
    //     FROM lichhen lh
    //     JOIN lichhen_dichvu lhdv ON lh.idlichhen = lhdv.idlichhen
    //     JOIN dichvu dv ON lhdv.iddichvu = dv.iddichvu
    //     GROUP BY lh.idlichhen
    // ";
    $sql = "
    SELECT 
        lh.idlichhen,
        lh.iduser,
        tk.username,
        lh.idxe,
        dv.iddichvu,
        dv.tendichvu,
        lh.idtrungtam,
        tt.tentrungtam,
        lh.ngayhen, 
        lh.thoigianhen,
        lh.trangthai,
        lh.lydohuy,
        COALESCE(GROUP_CONCAT(dv.tendichvu SEPARATOR ', '), '') AS tendichvu
    FROM 
        lichhen lh
    JOIN 
        lichhen_dichvu lhdv ON lh.idlichhen = lhdv.idlichhen
    JOIN 
        dichvu dv ON lhdv.iddichvu = dv.iddichvu
    JOIN 
        trungtam tt ON lh.idtrungtam = tt.idtrungtam
    JOIN 
        taikhoan tk ON lh.iduser = tk.iduser
    GROUP BY 
        lh.idlichhen
";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Truy vấn thất bại."]);
        $conn->close();
        exit;
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $lichhenList = [];

        while ($row = $result->fetch_assoc()) {
            $lichhenList[] = $row;
        }

        echo json_encode(['success' => true, 'lichhen' => $lichhenList]);
    } else {
        echo json_encode(["success" => false, "message" => "Không tìm thấy lịch hẹn nào."]);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(["message" => "Phương thức không được hỗ trợ."]);
}

$conn->close();
?>