<?php

// ĐÂY là trang cho người dùng nhe

if(isset($_POST["update_user"])){
    if(isset($_POST["user_id"]) && isset($_POST["name"]) && isset($_POST["phone"]) && isset($_POST["email"])){
        
        $user_id = $_POST["user_id"];
        $name = $_POST["name"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
    
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";
        $result = update_user($user_id, $name, $phone, $email);        
    }
}


?>