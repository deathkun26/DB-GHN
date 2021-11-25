<?php
// Truyền vào các sản phẩm trong đơn hàng dạng ?
/* echo:
    -1: kết nối database thất bại 
     1: thành công nhưng không có hàng nào thỏa yêu cầu
     2: thành công có hàng thỏa
        1\tMinh Toan\tDang giao\t25000\n
        2\tMinh Toan\tDang giao\t25000\n
        
*/
if(isset($_POST["btn_tracuu"])){
    if(isset($_POST["owner_id"]) && isset($_POST["status"])){
        $errors = array();
        
        $owner_id = $_POST["owner_id"];
        $status = $_POST["status"];
    
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";
        $result = filterStore($owner_id, $status);
        if(mysqli_num_rows($result) === 0) {
            echo "1"; // Thành công nhưng không có đơn hàng nào thỏa yêu cầu filter -> Trả về ?
        }        
        else {
            while($row = $result->fetch_assoc()) {
                echo "". $row["maCH"] . "\t" . $row["tenCH"] ."\t". $row["trangthaiCH"] ."\t" . $row["sodienthoaiCH"]."\t". $row["diachiCH"]. "\n";
                // format echo: 1\t\tDang giao\t25000\n
            }
        }
    }
}

if(isset($_POST["delete"])){
    if(isset($_POST["$store_id"])){
        
        require dirname(__FILE__) . '/function.php';

        // Xóa cửa hàng
        deleteStore($store_id);
    }
}

if(isset($_POST["update"])){
    if(isset($_POST["$store_id"]) && isset($_POST["$owner_id"]) && isset($_POST["$store_name"])
                                  && isset($_POST["$store_addr"]) && isset($_POST["$store_phone"])){
        require dirname(__FILE__) . '/function.php';

        // Chỉnh sửa cửa hàng (sửa trạng thái có 1 nút khác)
        updateStore($store_id, $owner_id, $store_name, $store_addr, $store_phone);
    }
}

if(isset($_POST["activate"])){
    if(isset($_POST["$store_id"])){
        require dirname(__FILE__) . '/function.php';

        // Chỉnh sửa cửa hàng (sửa trạng thái có 1 nút khác)
        activateStore($store_id);
    }
}
?>