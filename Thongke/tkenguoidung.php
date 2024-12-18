<?php
   include "D:/Xampp/htdocs/myapi/connect.php";

   header("Access-Control-Allow-Origin: *");
   header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
   header("Access-Control-Allow-Headers: Content-Type, Authorization");

   try{
        $sql= "SELECT COUNT(iduser) AS total_user FROM taikhoan";
        $result= $conn -> query($sql);
        if($result ==false){
            throw new Exception($conn->error);
        }
        $total_user= $result->fetch_assoc()['total_user'];
        echo json_encode([
            'success'=>true,
            'total_user'=>$total_user,
        ]);

   }catch(Exception $e){
    echo json_encode([
        'success' => false,
        'message'=> 'Erro retrieving user statistics',
        'erro'=> $e -> getMessage()
    ]);
   }
   $conn->close();
?>