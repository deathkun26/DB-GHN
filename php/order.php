<?php
// Truyền vào các sản phẩm trong đơn hàng dạng ?
/* echo:
    -1: kết nối database thất bại 
     1: thành công nhưng không có hàng nào thỏa yêu cầu
     2: thành công có hàng thỏa
        1\tMinh Toan\tDang giao\t25000\n
        2\tMinh Toan\tDang giao\t25000\n
        
*/
if(isset($_POST["tracuu"])){
    if(isset($_POST["store_id"]) && isset($_POST["status"]) && isset($_POST["time_from"]) && isset($_POST["time_to"])){
        $errors = array();
        
        $store_id = $_POST["store_id"];
        $status = $_POST["status"];
        $time_from = $_POST["time_from"];
        $time_to = $_POST["time_to"];
    
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";
        $result = filterOrder($store_id, $status, $time_from, $time_to);
        if(mysqli_num_rows($result) === 0) {
            echo "1"; // Thành công nhưng không có đơn hàng nào thỏa yêu cầu filter -> Trả về ?
        }        
        else {
            echo "0\n";
            while($row = $result->fetch_assoc()) {
                echo "" . $row["maVD"] . "\t". $row["hotenNN"] ."\t". $row["sodienthoaiNN"] ."\t" . $row["diachiNN"]."\t". $row["trangthai"]. "\t" . "null". "\n" ;
                // format echo: 1\tMinh Toan\tDang giao\t25000\n
            }
        }
    }
}

if(isset($_POST["tracuu_order_id"])){
    if(isset($_POST["order_id"])){
        $errors = array();
        
        $order_id = $_POST["order_id"];
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";
        $result = filterOrderByOrderId($order_id);
        if(mysqli_num_rows($result) === 0) {
            echo "1"; // Thành công nhưng không có đơn hàng nào thỏa yêu cầu filter -> Trả về ?
        }        
        else { // Thiếu tổng khối lượng
            echo "". $row["hotenNN"] . "\t". $row["sodienthoaiNN"] ."\t" . $row["diachiNN"]."\t" . $row["trangthai"] . "\t". $row["size"] ."\t" . $row["diachitrahang"]  . $row["phigiaohang"] ."\n";
        }
        if(mysqli_num_rows($result) === 0) {
            echo "1"; // Thành công nhưng không có đơn hàng nào thỏa yêu cầu filter -> Trả về ?
        }        
        else {
            while($row = $result->fetch_assoc()) {
                echo "". $row["hotenNN"] . "\t". $row["sodienthoaiNN"] ."\t" . $row["diachiNN"]."\t". $row["sttSP"] ."\t".  $row["tenSP"]."\t".  $row["soluongSP"]."\t".  $row["khoiluongSP"]."\t"."\n";
                // format echo: 1\tMinh Toan\tDang giao\t25000\n
            }
        }
    }
}

if(isset($_POST["new_order"])){
    if(isset($_POST["order_id"]) && isset($_POST["store_id"]) && isset($_POST["shift"]) 
    && isset($_POST["size"]) && isset($_POST["recv_name"]) && isset($_POST["recv_phone"])
    && isset($_POST["recv_addr"]) && isset($_POST["STT_DGN"]) && isset($_POST["KV_DGN"])
    && isset($_POST["item_name"]) && isset($_POST["weight"]) && isset($_POST["quantity"]) && isset($_POST["status"])){
        $errors = array();
    
        $store_id = $_POST["store_id"];
        $order_id = $_POST["order_id"];
        $resend_addr = $_POST["resend_addr"];
        $shift = $_POST["shift"]; 
        $size = $_POST["size"];
        $recv_name = $_POST["recv_name"];
        $recv_phone = $_POST["recv_phone"];
        $recv_addr = $_POST["recv_addr"];
        $STT_DGN = $_POST["STT_DGN"];
        $KV_DGN = $_POST["KV_DGN"];
        $item = $_POST["item_name"];
        $weight = $_POST["weight"];
        $quantity = $_POST["quantity"];
        $status = $_POST["quantity"];

        // Kiểm tra dữ liệu thêm vào
        if ($resend_tick && empty($resend_addr)) {
            $errors[] = "Vui lòng nhập địa chỉ trả hàng.";
        }

        if (empty($recv_name)) {
            $errors[] = "Vui lòng nhập tên người nhận.";
        }
        if (empty($recv_phone)) {
            $errors[] = "Vui lòng nhập số điện thoại người nhận.";
        }

        if (empty($recv_addr)) {
            $errors[] = "Vui lòng nhập địa chỉ người nhận.";
        }
        if (empty($item) || empty($quantity) || empty($weight)) {
            $errors[] = "Vui lòng nhập đầy đủ thông tin sản phẩm.";

        }   

        if(count($errors) == 0){
            require dirname(__FILE__) . '/function.php';

            // Thêm dữ liệu vào bảng đơn hàng
            addOrder($store_id, $order_id, $resend_addr, $shift, $size, $recv_name, $recv_addr, $recv_phone, $STT_DGN, $KV_DGN, $status);
            
            // Thêm dữ liệu vào bảng sản phẩm
            addProduct($order_id, $stt_id, $item_name, $weight, $quantity);
            // Thêm dữ liệu vào bảng trả hàng

            // Thêm dữ liệu vào bảng gửi tới
        }    
    }
}
?>