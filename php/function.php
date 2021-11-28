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
    /* Lọc order theo trạng thái, thời gian 
    REF: So sánh date time: 
        select PK, userID, lr.Date, lr.Time, Half, lr.InOut, Op_UserID, About
        from loginrecord lr
        where lr.Date Between '2017-05-17' AND '2017-05-17'
        and lr.InOut = 1
        and TIME_FORMAT(lr.Time, '%H:%i') > '10:30'
    */
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
                    WHERE maCH = '$store_id' AND ngaytaoDH >= '$time_from' AND ngaytaoDH <= '$time_to' ORDER BY ngaytaoDH DESC";
        }
        else {
            $query = "SELECT maVD, hotenNN, sodienthoaiNN, diachiNN, trangthai
                    FROM DON_HANG
                    WHERE trangthai = '$status' AND maCH = '$store_id' AND ngaytaoDH >= '$time_from' AND ngaytaoDH <= '$time_to' ORDER BY ngaytaoDH DESC";
        }

        $results = mysqli_query($db, $query);
        echo mysqli_error($db);
        return $results;
    }
    
    function storeInfo($store_id){
        global $db;

        $query = "SELECT tenCH, sodienthoaiCH, diachiCH FROM cua_hang WHERE maCH=" .$store_id; 
        $results = mysqli_query($db, $query);
        return $results;
    }

    /*Lọc thông tin đơn theo mã đơn */
    function filterOrderByOrderId($order_id){
        global $db;
        $query = "SELECT `don_hang`.`diachitrahang` AS diachitrahang, 
                        `gui_toi`.`sttDGN` AS sttDGN,
                        `gui_toi`.`khuvucDGN` AS khuvucDGN,
                        `gui_toi`.`calayhang` AS calayhang,
                        `gui_toi`.`hotenNN` AS hotenNN,
                        `gui_toi`.`sodienthoaiNN` AS sodienthoaiNN,
                        `gui_toi`.`diachiNN` AS diachiNN,
                        FROM don_hang, gui_toi WHERE maVD=".$order_id; 
        $results = mysqli_query($db, $query);
        return $results;
    }

    // Lọc thông tin sản phẩm trong đơn
    function filterItemByOrderId($order_id){
        global $db;
        $query = "SELECT * FROM san_pham WHERE maVD=".$order_id; 
        $results = mysqli_query($db, $query);
        return $results;
    }

    /* Thêm đơn mới vào cơ sở dữ liệu*/
    // MaBT đang null do không biết gán cho ai
    function addOrder($store_id, $resend_addr, $shift, $size, $recv_name, $recv_addr, $recv_phone, $status, $tenDGN){
        global $db;
        $query = "SELECT MAX(maVD) AS maVD FROM `don_hang`";
        $results = mysqli_query($db, $query);
        $maVD = "";
        while($row = $results->fetch_assoc()) {
           $maVD = (int)$row["maVD"];                 
        }
        $maVD = $maVD+1;
        $query = "INSERT INTO don_hang(maVD, kichthuocDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, calayhang, maCH,trangthai, ngaytaoDH)
                  VALUES('$maVD', '$size', '$resend_addr', '$recv_name', '$recv_phone', '$recv_addr', '$shift', '$store_id', '$status', 'DATE()')"; 
        $results = mysqli_query($db, $query);
        
        echo "0\n" . $maVD;
        
        // THêm vào điểm giao nhận
        $tenDGN = explode("-", $tenDGN);
        $query = "INSERT INTO gui_toi VALUES('$maVD', '$tenDGN[0]', '$tenDGN[1]')"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    function addItem($order_id, $name, $weight, $quantity){
        global $db;
        $query = "SELECT MAX(sttSP) AS sttSP FROM `san_pham` WHERE maVD=" .$order_id;
        $results = mysqli_query($db, $query);
        $sttSP = "";
        while($row = $results->fetch_assoc()) {
            $sttSP = (int)$row["sttSP"];                 
        }
        $sttSP = $sttSP + 1;
        $query = "INSERT INTO san_pham VALUES('$order_id', '$sttSP', '$name', '$quantity', '$weight')"; 
        $results = mysqli_query($db, $query);
        
        echo "0";
        return $results;
    }
    function DGN(){
        global $db;
        $query = "SELECT * FROM diem_giao_nhan"; 
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
            $query = "SELECT * FROM `nguoi_dung`, `nhan_vien`, `lam_viec_tai` WHERE `nguoi_dung`.`maND` = `nhan_vien`.`maND` AND `lam_viec_tai`.`maND` = `nguoi_dung`.`maND`;"; 
        else { 
            $query = "SELECT * FROM `nguoi_dung`, `nhan_vien`, `lam_viec_tai` WHERE `lam_viec_tai`.`maCH`" . $store_id. " AND `nguoi_dung`.`maND` = `nhan_vien`.`maND` AND `lam_viec_tai`.`maND` = `nguoi_dung`.`maND`;";
        }

        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Xóa nhân viên theo mã nhân viên */
    function deleteEmployee($employee_id){
        global $db;
        $query = "DELETE FROM nguoi_dung WHERE maND = '$employee_id'"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Cập nhật nhân viên cho cửa hàng */
    function updateEmployeeStore($employee_id, $store_id, $value){
        global $db;
        $query = "";
        if($value){
            $query = "INSERT INTO lam_viec_tai VALUES($store_id, $employee_id)"; 
        }
        else {
            $query = "DELETE FROM lam_viec_tai WHERE maND= '$employee_id'";
        }
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Thêm nhân viên vào cửa hàng */
    function addEmployee($store_id, $employee_id){
        global $db;
        // Kiểm tra nhân viên đó đã có trong bảng chưa
        $query = "SELECT FROM lam_viec_tai WHERE maCH='$store_id' maND='$employee_id'";
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) > 0) {
            return "2"; // Đã tổn tại
        }
        $query = "INSERT INTO lam_viec_tai VALUES($store_id, $employee_id)";
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Thêm yêu cầu hỗ trợ */
    function addRequest($user_id, $order_id, $request_id, $type, $content){
        global $db;
        // Thêm vào bảng yêu cầu hỗ trợ
        $query = "INSERT INTO yeu_cau_ho_tro VALUES ('$request_id', '$type', '$content', 0)"; 
        mysqli_query($db, $query);
        // Thêm vào bảng gửi yêu cầu
        $query = "INSERT INTO gui_yeu_cau VALUES ('$user_id', '$request_id', '$order_id', 'DATE()')"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Lọc yêu cầu hỗ trợ */
    function filterRequest($user_id, $status, $time_from, $time_to){
        global $db;
        $query = "";
        if($time_from == "") {
            $time_from = "1970-01-01";
        }
        if($time_to == "") {
            $time_to = "2970-01-01";
        } 
        if ($status == ""){
            $query = "SELECT `gui_yeu_cau`.`maYC` AS `maYC`,
                             `gui_yeu_cau`.`maVD` AS `maVD`,
                             `gui_yeu_cau`.`thoigiangui` AS `thoigiangui`,
                             `yeu_cau_ho_tro`.`noidungYC` AS `noidungYC`,
                             `yeu_cau_ho_tro`.`trangthaiYC` AS `trangthaiYC`
                      FROM `gui_yeu_cau`, `yeu_cau_ho_tro` 
                      WHERE `gui_yeu_cau`.`maYC`=`yeu_cau_ho_tro`.`maYC` AND `gui_yeu_cau`.`maND`=" . $user_id;
        }
        else {
            $query = "SELECT `gui_yeu_cau`.`maYC` AS `maYC`,
                             `gui_yeu_cau`.`maVD` AS `maVD`,
                             `gui_yeu_cau`.`thoigiangui` AS `thoigiangui`,
                             `yeu_cau_ho_tro`.`noidungYC` AS `noidungYC`,
                             `yeu_cau_ho_tro`.`trangthaiYC` AS `trangthaiYC`
                      FROM `gui_yeu_cau`, `yeu_cau_ho_tro` 
                      WHERE `gui_yeu_cau`.`maYC`=`yeu_cau_ho_tro`.`maYC` AND `gui_yeu_cau`.`maND`=" . $user_id. "AND `yeu_cau_ho_tro`.`trangthaiYC`=" . $status;
        }
        $results = mysqli_query($db, $query);
        echo mysqli_error($db);
        return $results;
    }
    /* Xóa yêu cầu hỗ trợ */
    function deleteRequest($request_id){
        global $db;
        $query = "DELETE FROM yeu_cau_ho_tro WHERE maCY='$request_id'"; 
        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Sửa yêu cầu (Cập nhật thời gian?) */
    // Yêu cầu hỗ trợ thay đổi không cần cascade gui_yeu_cau
    function updateRequest($request_id, $content){
        global $db;
        $query = "UPDATE yeu_cau_ho_tro SET `noidungYC`=" . $content . "WHERE maYC=" . $request_id; 
        $results = mysqli_query($db, $query);
        return $results;
    }
?>