<?php
    require_once("connection.php");

    /* Lấy dữ liệu người dùng đăng nhập */
    function getUser($username, $password) {
        global $db;
        $query = "SELECT * FROM nguoi_dung WHERE Tendangnhap = '$username' AND Matkhau='$password'";
        return mysqli_query($db, $query);
    }

    /* Thêm người dùng vào bảng User */
    function addUser($user_id, $id_card_num, $name, $username, $password, $email, $phone) {
        global $db;
        $query = "INSERT INTO nguoi_dung VALUES ('$user_id', '$id_card_num', '$name', '$phone', '$email', '$username', '$password')"; 
        $results = mysqli_query($db, $query);
        return $results;
    }

    function filterOrder($store_id, $status, $time_from, $time_to){
        global $db;
        $query = "SELECT FROM don_hang WHERE"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    function filterOrderByOrderId($order_id){
        global $db;
        $query = "SELECT FROM don_hang WHERE"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    function addOrder($store_id, $order_id, $resend_addr, $shift, $size, $recv_name, $recv_addr, $recv_phone, $STT_DGN, $KV_DGN, $status){
            global $db;
            $query = "INSERT INTO don_hang "; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    function addProduct($order_id, $stt_id, $item_name, $weight, $quantity){
        global $db;
        $query = "INSERT INTO san_pham"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    function filterStore($store, $status){
        global $db;
        $query = "SELECT san_pham"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    function deleteStore($store_id){
        global $db;
        $query = "DELETE san_pham"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    function updateStore($store_id, $owner_id, $store_name, $store_addr, $store_phone){
        global $db;
        $query = "UPDATE san_pham"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    function activateStore($store_id){
        global $db;
        $query = "UPDATE san_pham"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
?>