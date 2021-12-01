<?php
    require_once("connection.php");

    /* Lấy dữ liệu người dùng đăng nhập */
    function getUser($username, $password) {
        global $db;
        $query = "SELECT * FROM nguoi_dung WHERE Tendangnhap = '$username' AND Matkhau='$password'";
        $result = mysqli_query($db, $query);
        
        echo "0\n";
        while($row = $result->fetch_assoc()) {
            $query2 = "SELECT * FROM `chu_cua_hang` WHERE `maND`=" .$row["maND"];
            $result2 = mysqli_query($db, $query2);
            if (mysqli_num_rows($result2) > 0) {
                echo "" . $row["maND"] . "\t". $row["hotenND"] . "\t1\n" ;
            }
            else {
                echo "" . $row["maND"] . "\t". $row["hotenND"] . "\t0\n" ;
            }
        }
        return  $result;
    }

    /* Thêm người dùng vào bảng User */
    function addUser($id_card_num, $name, $username, $password, $email, $phone, $type) {
        global $db;
        $query = "SELECT MAX(maND) AS maND FROM `nguoi_dung`";
        $results = mysqli_query($db, $query);
        $maND = "";
        while($row = $results->fetch_assoc()) {
            $maND = (int)$row["maND"];                 
        }
        $maND = $maND + 1;

        $query = "INSERT INTO nguoi_dung VALUES ('$maND', '$id_card_num', '$name', '$phone', '$email', '$username', '$password')"; 
        $results = mysqli_query($db, $query);
        
        echo "0\n";
        echo "" . $maND ."\n";
        if ($type == "1") { // $stype == 1 ?
            // Thêm vào bảng chủ cửa hảng
            $query = mysqli_query($db,"INSERT INTO chu_cua_hang VALUES ('$maND')");
        }
        else if ($type == "0"){
            // Thêm vào bảng chủ cửa hảng
            $query = mysqli_query($db,"INSERT INTO nhan_vien VALUES ('$maND')");
        }   
        
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
        if ($status == "-1"){
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
        $query = "SELECT * FROM gui_toi WHERE maVD=" .$order_id;
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) > 0) {
            $query = "SELECT `don_hang`.`diachitrahang` AS `diachitrahang`, 
                            `gui_toi`.`sttDGN` AS `sttDGN`,
                            `gui_toi`.`khuvucDGN` AS `khuvucDGN`,
                            `don_hang`.`calayhang` AS `calayhang`,
                            `don_hang`.`hotenNN` AS `hotenNN`,
                            `don_hang`.`sodienthoaiNN` AS `sodienthoaiNN`,
                            `don_hang`.`diachiNN` AS `diachiNN` 
                        FROM `don_hang`, `gui_toi` 
                        WHERE `don_hang`.`maVD`=".$order_id ." AND `gui_toi`.`maVD` = `don_hang`.`maVD`"; 
        }
        else{
            $query = "SELECT `diachitrahang`, `calayhang`, `hotenNN`, `sodienthoaiNN`, `diachiNN` FROM `don_hang`
                        WHERE `don_hang`.`maVD`= ". $order_id; 
        }
        $results = mysqli_query($db, $query);
        echo mysqli_error($db);
        return $results;
    }

    // Lọc thông tin sản phẩm trong đơn
    function filterItemByOrderId($order_id){
        global $db;
        $query = "SELECT * FROM san_pham WHERE maVD=".$order_id; 
        $results = mysqli_query($db, $query);
        echo mysqli_error($db);
        //echo var_dump($results);
        return $results;
    }

    /* Thêm đơn mới vào cơ sở dữ liệu*/
    // MaBT đang null do không biết gán cho ai
    function addOrder($store_id, $resend_addr, $shift, $size, $recv_name, $recv_addr, $recv_phone, $status, $tenDGN){
        global $db;
        $query = "SELECT MAX(maVD) AS maVD FROM `don_hang`";
        $results = mysqli_query($db, $query);
        echo mysqli_error($db);

        $maVD = "";
        while($row = $results->fetch_assoc()) {
           $maVD = (int)$row["maVD"];                 
        }
        
        $maVD = $maVD+1;
        if($resend_addr=="")
        {
            $query = "INSERT INTO don_hang(maVD, kichthuocDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, calayhang, maCH,trangthai, ngaytaoDH)
                  VALUES('$maVD', '$size', NULL, '$recv_name', '$recv_phone', '$recv_addr', '$shift', '$store_id', '$status', \"" . date("Y/m/d") . "\")"; 
        }
        else
        {
            $query = "INSERT INTO don_hang(maVD, kichthuocDH, diachitrahang, hotenNN, sodienthoaiNN, diachiNN, calayhang, maCH,trangthai, ngaytaoDH)
                  VALUES('$maVD', '$size', '$resend_addr', '$recv_name', '$recv_phone', '$recv_addr', '$shift', '$store_id', '$status', \"" . date("Y/m/d") . "\")"; 
        }


        $results = mysqli_query($db, $query);
        
        echo mysqli_error($db);
        echo "0\n" . $maVD;
        
        // Thêm vào điểm giao nhận
        if($tenDGN != "")
        {
            $tenDGN = explode("-", $tenDGN);
            $query = "INSERT INTO gui_toi VALUES('$maVD', '$tenDGN[0]', '$tenDGN[1]')"; 
            $results = mysqli_query($db, $query);
            echo mysqli_error($db);
        }
        return $results;
    }
    function addItem($order_id, $name, $weight, $quantity){
        global $db;
        $query = "SELECT MAX(sttSP) AS sttSP FROM `san_pham` WHERE maVD=" .$order_id;
        $results = mysqli_query($db, $query);
        $sttSP = "";
        while($row = $results->fetch_assoc()) {
            $sttSP = $row["sttSP"];                 
        }
        echo mysqli_error($db);

        $sttSP_next = 1;
        if($sttSP != NULL)
        {
            $sttSP_next = (int)$sttSP + 1;
        }

        $query = "INSERT INTO san_pham VALUES('$order_id', '$sttSP_next', '$name', '$quantity', '$weight')"; 
        $results = mysqli_query($db, $query);
        
        echo mysqli_error($db);
        echo "0";
        return $results;
    }

    function deleteOrder($order_id){
        global $db;
        $query = "DELETE FROM don_hang WHERE maVD = '$order_id'"; 
        echo mysqli_error($db);
        $results = mysqli_query($db, $query);
        echo "0\n";
        $query = "DELETE FROM san_pham WHERE maVD = '$order_id'"; 
        echo mysqli_error($db);
        $results = mysqli_query($db, $query);
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
        $query = "";
        if($status == "-1"){
            $query = "SELECT * FROM `cua_hang` WHERE `maCCH` = ". $owner_id; 
            $results = mysqli_query($db, $query);
        }
        else{
            $query = "SELECT * FROM `cua_hang` WHERE `maCCH` = ". $owner_id. " AND `trangthaiCH`=" .(int)$status; 
            $results = mysqli_query($db, $query);
        }
        
        return $results;
    }
    /* Lọc cửa hàng theo trạng thái Và id nhân viên */
    function filterStoreById($owner_id, $employee_id ,$status){
        global $db;
        $query = "";
        if($status == "-1"){
            $query = "SELECT * FROM `cua_hang`,`lam_viec_tai` WHERE `cua_hang`.`maCCH` =". $owner_id ." AND `lam_viec_tai`.`maND`= ". $employee_id ." AND `lam_viec_tai`.`maCH` = `cua_hang`.`maCH`;";
            $results = mysqli_query($db, $query);
        }
        else{
            $query = "SELECT * FROM `cua_hang`,`lam_viec_tai` WHERE `cua_hang`.`maCCH` =". $owner_id ." AND `lam_viec_tai`.`maND`= ". $employee_id ." AND `lam_viec_tai`.`maCH` = `cua_hang`.`maCH` AND `trangthaiCH`=" .(int)$status .";";
            $results = mysqli_query($db, $query);
        }
        echo mysqli_error($db);
        return $results;
    }

    /* Thêm cửa hàng */
    function addStore($owner_id, $store_name, $store_addr, $store_phone){
        global $db;
        $query = "SELECT MAX(maCH) AS maCH FROM `cua_hang`";
        $result = mysqli_query($db, $query);
        $maCH = "";
        while($row = $result->fetch_assoc()) {
           $maCH = (int)$row["maCH"];                 
        }
        $maCH = $maCH+1;
        $query = "INSERT INTO cua_hang VALUES('$maCH', '$owner_id', '$store_name', '$store_addr', '$store_phone',1,0)"; 
        $results = mysqli_query($db, $query);
        echo mysqli_error($db);
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
    function filterEmployee($store_id,$owner_id){
        global $db;
        $query = "";
        if($store_id == "")
            $query = "SELECT * FROM `nguoi_dung`, `cua_hang`, `lam_viec_tai` 
                    WHERE `cua_hang`.`maCCH` = ".$owner_id ." AND `lam_viec_tai`.`maND` = `nguoi_dung`.`maND` AND `lam_viec_tai`.`maCH` = `cua_hang`.`maCH`;"; 
        else { 
            $query = "SELECT * FROM `nguoi_dung`, `nhan_vien`, `lam_viec_tai` 
                    WHERE `lam_viec_tai`.`maCH` = " . $store_id. " AND `nguoi_dung`.`maND` = `nhan_vien`.`maND` AND `lam_viec_tai`.`maND` = `nguoi_dung`.`maND`;";
        }

        $results = mysqli_query($db, $query);
        return $results;
    }
    /* Xóa nhân viên theo mã nhân viên */
    function deleteEmployee($employee_id, $store_id){
        global $db;
        $query = "DELETE FROM lam_viec_tai WHERE maND = '$employee_id' AND maCH = '$store_id'"; 
        $results = mysqli_query($db, $query);
        if (mysqli_error($db)){ 
            echo "1\n";
        }
        else {
            echo "0\n";
        }
        return $results;
    }
    /* Cập nhật nhân viên cho cửa hàng thừaaaaa*/
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
        $query = "SELECT * FROM lam_viec_tai WHERE maCH='$store_id' AND maND='$employee_id'";
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) > 0) {;} // nếu như vậy thì trả về cái gì? echo 0 thì sao?
        else
        {
            $query = "INSERT INTO lam_viec_tai VALUES($store_id, $employee_id)";
            $results = mysqli_query($db, $query);
        }
        echo "0\n";
        return $result;
    }

    
    /* Thêm yêu cầu hỗ trợ */
    function addRequest($user_id, $order_id, $type, $content){
        global $db;
        $query = "SELECT MAX(maYC) AS maYC FROM `yeu_cau_ho_tro`";
        $results = mysqli_query($db, $query);
        $maYC = "";
        while($row = $results->fetch_assoc()) {
            $maYC = (int)$row["maYC"];                 
        }
        $maYC = $maYC + 1;
        $query = "SELECT * FROM `don_hang` WHERE maVD='$order_id'";
        $results2 = mysqli_query($db, $query);
        if (mysqli_num_rows($results2) == 0) {
            echo "1\n";
        }
        else
        {
            $results = mysqli_query($db, $query);
            // Thêm vào bảng yêu cầu hỗ trợ
            $query = "INSERT INTO yeu_cau_ho_tro VALUES ('$maYC', '$type', '$content', 0)"; 
            mysqli_query($db, $query);
            // Thêm vào bảng gửi yêu cầu
            $query = "INSERT INTO gui_yeu_cau VALUE (" . $user_id . ", " . $maYC . ", \"" . $order_id . "\", \"" . date("Y/m/d") . "\")";
            $results = mysqli_query($db, $query);
            echo "0\n";
            echo "" . $maYC . "\n";
        }
        return $results;
    }

    /* Lấy thông tin nhân viên */
    function getEmployee($employee_id){
        global $db;
        // Lấy thông tin nhân viên của 1 người chủ
        $query = "SELECT `hotenND`, `sodienthoaiND` 
                FROM `nguoi_dung`,`nhan_vien`
                WHERE `nguoi_dung`.`maND` = `nhan_vien`.`maND` AND `nguoi_dung`.`maND`=" .$employee_id ;
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) > 0) {
            echo "0\n";
            while($row = $results->fetch_assoc()) {
                echo "" . $row["hotenND"] . "\t". $row["sodienthoaiND"] . "\n" ;
            }
        }
        else{
            echo "1\n";
        }
        echo mysqli_error($db);
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
        if ($status == "-1"){
            $query = "SELECT `gui_yeu_cau`.`maYC` AS `maYC`,
                             `gui_yeu_cau`.`maVD` AS `maVD`,
                             `gui_yeu_cau`.`thoigiangui` AS `thoigiangui`,
                             `yeu_cau_ho_tro`.`noidungYC` AS `noidungYC`,
                             `yeu_cau_ho_tro`.`trangthaiYC` AS `trangthaiYC`
                      FROM `gui_yeu_cau`, `yeu_cau_ho_tro` 
                      WHERE `gui_yeu_cau`.`maYC`=`yeu_cau_ho_tro`.`maYC` AND `gui_yeu_cau`.`maND` = \"" . $user_id . "\"" . " AND `gui_yeu_cau`.`thoigiangui` > \"" . $time_from . "\" AND `gui_yeu_cau`.`thoigiangui` < \"" .$time_to ."\"";
        }
        else {
            $query = "SELECT `gui_yeu_cau`.`maYC` AS `maYC`,
                             `gui_yeu_cau`.`maVD` AS `maVD`,
                             `gui_yeu_cau`.`thoigiangui` AS `thoigiangui`,
                             `yeu_cau_ho_tro`.`noidungYC` AS `noidungYC`,
                             `yeu_cau_ho_tro`.`trangthaiYC` AS `trangthaiYC`
                      FROM `gui_yeu_cau`, `yeu_cau_ho_tro` 
                      WHERE `gui_yeu_cau`.`maYC`=`yeu_cau_ho_tro`.`maYC` AND `gui_yeu_cau`.`maND`= \"" . $user_id . "\"" . " AND `yeu_cau_ho_tro`.`trangthaiYC` = \"" . $status . "\" AND `gui_yeu_cau`.`thoigiangui` > \"" . $time_from . "\" AND `gui_yeu_cau`.`thoigiangui` < \"" .$time_to ."\"";
        }
        $results = mysqli_query($db, $query);
        echo mysqli_error($db);
        //echo var_dump($results);
        return $results;
    }
    /* Xóa yêu cầu hỗ trợ */
    function deleteRequest($request_id){
        global $db;
        $query = "DELETE FROM yeu_cau_ho_tro WHERE maYC='$request_id'"; 
        $results = mysqli_query($db, $query);
        echo mysqli_error($db);
        echo "0\n";
        return $results;
    }
    /* Sửa yêu cầu (Cập nhật thời gian?) */
    // Yêu cầu hỗ trợ thay đổi không cần cascade gui_yeu_cau
    function updateRequest($request_id, $content){
        global $db;
        $query = "UPDATE yeu_cau_ho_tro SET `noidungYC`=\"" . $content . "\" WHERE maYC= \"" . $request_id . "\""; 
        $results = mysqli_query($db, $query);
        echo "0\n";
        return $results;
    }
    // Cập nhật người dùng
    function update_user($owner_id, $name, $phone, $email){
        global $db;
        $query = "UPDATE nguoi_dung SET `hotenND`=\"" . $name . "\", `sodienthoaiND`=\"" . $phone . "\", `emailND`=\"" . $email . "\" WHERE maND= \"" . $owner_id . "\""; 
        $results = mysqli_query($db, $query);
        echo mysqli_error($db);
        echo "0\n";
        return $results;
    }
?>