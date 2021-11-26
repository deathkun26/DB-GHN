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
    /* Lọc order theo trạng thái, thời gian */
    function filterOrder($store_id, $status, $time_from, $time_to){
        global $db;
        $query = "SELECT maVD, hotenNN, sodienthoaiNN, diachiNN, trangthai
                    FROM DON_HANG
                    WHERE maCH = " . $store_id . ";"; 
        $results = mysqli_query($db, $query);
        echo mysqli_error($db);
        return $results;
    }
    /*Lọc thông tin đơn theo mã đơn */
    function filterOrderByOrderId($order_id){
        global $db;
        $query = "SELECT FROM don_hang WHERE"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Thêm đơn mới vào cơ sở dữ liệu*/
    function addOrder($store_id, $order_id, $resend_addr, $shift, $size, $recv_name, $recv_addr, $recv_phone, $STT_DGN, $KV_DGN, $status){
            global $db;
            $query = "INSERT INTO don_hang "; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Thêm sản phẩm mới vào đơn */
    function addProduct($order_id, $stt_id, $item_name, $weight, $quantity){
        global $db;
        $query = "INSERT INTO san_pham"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Lọc cửa hàng theo trạng thái */
    function filterStore($store, $status){
        global $db;
        $query = "SELECT san_pham"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Thêm cửa hàng */
    function addStore($store_id, $owner_id, $store_name, $store_addr, $store_phone){
        global $db;
        $query = "INSERT"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Xóa cửa hảng */
    function deleteStore($store_id){
        global $db;
        $query = "DELETE san_pham"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Cập nhật cửa hàng */
    function updateStore($store_id, $owner_id, $store_name, $store_addr, $store_phone){
        global $db;
        $query = "UPDATE san_pham"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Kích hoạt hoặc ngưng kích hoạt cửa hàng (Thay đổi trạng thái) */
    function activateStore($store_id){
        global $db;
        $query = "UPDATE san_pham"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Lọc nhân viên theo mã cửa hàng*/
    function filterEmployee($store_id){
        global $db;
        $query = "SELECT san_pham"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Xóa nhân viên theo mã nhân viên */
    function deleteEmployee($employee_id){
        global $db;
        $query = "DELETE"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Cập nhật nhân viên cho cửa hàng */
    function updateEmployeeStore($employee_id, $store_id){
        global $db;
        $query = "UPDATE"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Thêm nhân viên vào cửa hàng */
    function addEmployee($store_id, $employee_id){
        global $db;
        $query = "INSERT"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Thêm yêu cầu hỗ trợ */
    function addRequest($user_id, $order_id, $request_id, $type, $content){
        global $db;
        $query = "INSERT"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Lọc yêu cầu hỗ trợ */
    function filterRequest($order_id, $status, $time_from, $time_to){
        global $db;
        $query = "SELECT"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Xóa yêu cầu hỗ trợ */
    function deleteRequest($request_id){
        global $db;
        $query = "DELETE"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Sửa yêu cầu (Cập nhật thời gian?) */
    function updateRequest($store_id, $content){
        global $db;
        $query = "UPDATE"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
?>