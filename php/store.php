<?php
// Truyền vào các sản phẩm trong đơn hàng dạng ?
/* echo:
    -1: kết nối database thất bại 
     1: thành công nhưng không có hàng nào thỏa yêu cầu
     2: thành công có hàng thỏa
        1\tMinh Toan\tDang giao\t25000\n
        2\tMinh Toan\tDang giao\t25000\n
        
*/

// Lấy danh sách cửa hàng đã kích hoạt
if(isset($_POST["store_list"])){
    if(isset($_POST["owner_id"])){
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Chỉnh sửa cửa hàng (sửa trạng thái có 1 nút khác)
        filterStore($owner_id, 1);

        echo "0\n";
        while($row = $result->fetch_assoc()) {
            echo "" . $row["maVD"] . "\t". $row["hotenNN"] ."\t". $row["sodienthoaiNN"] ."\t" . $row["diachiNN"]."\t". $row["trangthai"]. "\t" . "null". "\n" ;
            // format echo: 1\tMinh Toan\tDang giao\t25000\n
        }
    }
}
if(isset($_POST["new_store"])){
    // Mặc định khi thêm là cửa hàng chưa được kích hoạt
    if(isset($_POST["store_id"]) && isset($_POST["owner_id"]) && isset($_POST["store_name"]) 
    && isset($_POST["store_addr"]) && isset($_POST["store_phone"])){
        $errors = array();
    
        $store_id = $_POST["store_id"];
        $owner_id = $_POST["owner_id"];
        $store_name = $_POST["store_name"];
        $store_addr = $_POST["store_addr"]; 
        $store_phone = $_POST["store_phone"];

        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Thêm dữ liệu vào bảng đơn hàng
        $res = addStore($store_id, $owner_id, $store_name, $store_addr, $store_phone);
        if ($res){
            echo "1";
        }
        else {
            echo "0";
        }
    }
}

else if(isset($_POST["filter"])){
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
            echo "0\n";
            while($row = $result->fetch_assoc()) {
                echo "". $row["maCH"] . "\t" . $row["tenCH"] ."\t". $row["trangthaiCH"] ."\t" . $row["sodienthoaiCH"]."\t". $row["diachiCH"]. "\n";
                // format echo: 1\t\tDang giao\t25000\n
            }
        }
    }
}

else if(isset($_POST["delete"])){
    if(isset($_POST["$store_id"])){
        
        $store_id = $_POST["store_id"];
    
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Xóa cửa hàng
        deleteStore($store_id);
    }
}

else if(isset($_POST["update"])){
    if(isset($_POST["$store_id"]) && isset($_POST["$store_name"])
                                  && isset($_POST["$store_addr"]) && isset($_POST["$store_phone"])){
        
        $store_id = $_POST["store_id"];
        $store_name = $_POST["store_name"];
        $store_addr = $_POST["store_addr"];
        $store_phone = $_POST["store_phone"];

        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Chỉnh sửa cửa hàng (sửa trạng thái có 1 nút khác)
        updateStore($store_id, $store_name, $store_addr, $store_phone);
    }
}

else if(isset($_POST["activate"])){
    if(isset($_POST["$store_id"])){
        
        $store_id = $_POST["store_id"];

        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        activateStore($store_id);
    }
}
?>