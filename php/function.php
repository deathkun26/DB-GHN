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
        $query = "";
        if($time_from == "") {
            $time_from = "1970-01-01";
        }
        if($time_to == "") {
            $time_to = "2970-01-01";
        } 
        if ($status == ""){
            $query = "SELECT maVD, hotenNN, sodienthoaiNN, diachiNN, trangthai
                    FROM DON_HANG
                    WHERE maCH = '$store_id' AND ngaytaoDH = '$time_from' AND ngaytaoDH = '$time_to' ORDER BY ngaytaoDH DESC";
        }
        else {
            $query = "SELECT maVD, hotenNN, sodienthoaiNN, diachiNN, trangthai
                    FROM DON_HANG
                    WHERE trangthai = '$status' AND maCH = '$store_id' AND ngaytaoDH = '$time_from' AND ngaytaoDH = '$time_to' ORDER BY ngaytaoDH DESC";
        }

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
    // MaBT đang null do không biết gán cho ai
    function addOrder($store_id, $order_id, $resend_addr, $shift, $size, $recv_name, $recv_addr, $recv_phone, $status){
        global $db;
        $query = "INSERT INTO don_hang VALUES('$order_id', '$size', '$resend_addr', '$recv_name', '$recv_phone', '$recv_addr', 'NULL', '$shift', '$store_id', '$status', 'DATE()', 0)"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Thêm sản phẩm mới vào đơn */
    function addProduct($order_id, $stt_id, $item_name, $weight, $quantity){
        global $db;
        $query = "INSERT INTO san_pham VALUES('$order_id', $stt_id, $item_name, $quantity, $weight)"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Lọc cửa hàng theo trạng thái */
    function filterStore($owner_id, $status){
        global $db;
        $query = "SELECT * FROM cua_hang WHERE maCCH = '$owner_id' and trangthaiCH='$status"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Thêm cửa hàng */
    function addStore($store_id, $owner_id, $store_name, $store_addr, $store_phone){
        global $db;
        $query = "INSERT INTO cua_hang VALUES('$store_id, '$owner_id', '$store_name', '$store_addr', '$store_phone')"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Xóa cửa hảng */
    function deleteStore($store_id){
        global $db;
        $query = "DELETE FROM cua_hang WHERE maCH = '$store_id'"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Cập nhật cửa hàng */
    function updateStore($store_id, $store_name, $store_addr, $store_phone){
        global $db;
        $query = "UPDATE cua_hang SET tenCH='$store_name', diachiCH='$store_addr', sodienthoaiCH='$store_phone' WHERE maCH='$store_id'";
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Kích hoạt hoặc ngưng kích hoạt cửa hàng (Thay đổi trạng thái) */
    function activateStore($store_id){
        global $db;
        $query = "SELECT trangthaiCH FROM cua_hang WHERE maCH='$store_id'";
        $result = mysqli_query($db, $query);
        $status = "";
        while($row = $result->fetch_assoc()) {
            $status = $row["trangthaiCH"];
        }
        if($status){
            $query = "UPDATE cua_hang SET trangthaiCH = 0 WHERE maCH='$store_id'";
        }
        else{
            $query = "UPDATE cua_hang SET trangthaiCH = 1 WHERE maCH='$store_id'";
        }
        
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Lọc nhân viên theo mã cửa hàng*/
    function filterEmployee($store_id){
        global $db;
        $query = "";
        if($store_id == "")
            $query = "SELECT * FROM nguoi_dung, nhan_vien, lam_viec_tai WHERE nguoi_dung.maND = nhan_vien.maND AND lam_viec_tai.maND = nguoi_dung.maND;"; 
        else { 
            $query = "SELECT * FROM nguoi_dung, nhan_vien, lam_viec_tai WHERE lam_viec_tai.maCH = '$store_id' AND nguoi_dung.maND = nhan_vien.maND AND lam_viec_tai.maND = nguoi_dung.maND;";
        }

        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Xóa nhân viên theo mã nhân viên */
    function deleteEmployee($employee_id){
        global $db;
        $query = "DELETE FROM nhan_vien WHERE maND = '$employee_id'"; 
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