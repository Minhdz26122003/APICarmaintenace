<?php
include "D:/Xampp/htdocs/myapi/connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Phương thức POST để thêm bình luận
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $data = json_decode(file_get_contents("php://input"), true);

    $idtrungtam = isset($data['idtrungtam']) ? $data['idtrungtam'] : null;
    $iduser = isset($data['iduser']) ? $data['iduser'] : null;
    $noidung = isset($data['noidung']) ? $data['noidung'] : null;
    $danhgia = isset($data['danhgia']) ? $data['danhgia'] : null;

    if (empty($idtrungtam) || empty($iduser) || empty($noidung) || empty($danhgia)) {
        echo json_encode(['success' => false, 'message' => 'Thông tin bình luận không đầy đủ.']);
        exit();
    }
    $sql = "INSERT INTO danhgia_binhluan (idtrungtam, iduser, noidung, danhgia, ngaybinhluan) VALUES (?, ?, ?, ?, NOW())";
    
    if ($stmt = $conn->prepare($sql)) {
    
        $stmt->bind_param("iisi", $idtrungtam, $iduser, $noidung, $danhgia);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Bình luận đã được thêm thành công.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm bình luận.']);
        }

        
        $stmt->close();
    } else {
       
        echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn cơ sở dữ liệu.']);
    }

    $conn->close();
}
?>
