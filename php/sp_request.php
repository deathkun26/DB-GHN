<?php
// Truyền vào các sản phẩm trong đơn hàng dạng ?
/* echo:
    -1: kết nối database thất bại 
     1: thành công nhưng không có hàng nào thỏa yêu cầu
     2: thành công có hàng thỏa
        1\tMinh Toan\tDang giao\t25000\n
        2\tMinh Toan\tDang giao\t25000\n
        
*/

if(isset($_POST["add_request"])){
    if(isset($_POST["user_id"]) && isset($_POST["order_id"]) && ($_POST["request_id"])
                                && isset($_POST["request_type"]) && isset($_POST["content"])){
        $errors = array();
    
        $user_id = $_POST["user_id"];
        $order_id = $_POST["order_id"];
        $request_id = $_POST["request_id"];
        $type = $_POST["type"];
        $content = $_POST["content"];

        // Kết nối tới database
        require './connection.php';
        require "./function.php";
        addRequest($user_id, $order_id, $request_id, $type, $content);
    }
}

else if(isset($_POST["filter_request"])){
    if(isset($_POST["user_id"]) && isset($_POST["order_id"]) && isset($_POST["status"]) && isset($_POST["time_from"]) && isset($_POST["time_to"])){
        $errors = array();
        
        $user_id = $_POST["user_id"];
        $order_id = $_POST["order_id"];
        $status = $_POST["status"];
        $time_from = $_POST["time_from"];
        $time_to = $_POST["time_to"];
    
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";
        $result = filterRequest($user_id, $order_id, $status, $time_from, $time_to);
        if(mysqli_num_rows($result) === 0) {
            echo "1"; // Thành công nhưng không có đơn hàng nào thỏa yêu cầu filter -> Trả về ?
        }        
        else {
            echo "0\n";
            while($row = $result->fetch_assoc()) {
                echo "". $row["code"] . "\t". $row["receiver"] ."\t" . $row["status"]."\t". $row["fee"]. "\n";
                // format echo: 1\tMinh Toan\tDang giao\t25000\n
            }
        }
    }
}

else if(isset($_POST["delete"])){
    if(isset($_POST["$request_id"])){
    
        $request_id = $_POST["request_id"];
    
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Xóa cửa hàng
        deleteRequest($request_id);
    }
}

else if(isset($_POST["update"])){
    if(isset($_POST["$request_id"]) && isset($_POST["$content"])){ // Nhận nhiều giá trị?  
        
        $store_id = $_POST["store_id"];
        $content = $_POST["content"];

        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Chỉnh sửa cửa hàng (sửa trạng thái có 1 nút khác)
        updateRequest($request_id, $content);
    }
}

?>