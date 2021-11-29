<?php
// Truyền vào các sản phẩm trong đơn hàng dạng ?
/* echo:
    -1: kết nối database thất bại 
     1: thành công nhưng không có hàng nào thỏa yêu cầu
     2: thành công có hàng thỏa
        1\tMinh Toan\tDang giao\t25000\n
        2\tMinh Toan\tDang giao\t25000\n
        
*/


// Lấy thông tin cửa hàng
if(isset($_POST["store_info"])){
    if(isset($_POST["store_id"])){
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";
        $store_id = $_POST["store_id"];
        // Chỉnh sửa cửa hàng (sửa trạng thái có 1 nút khác)
        $result = storeInfo($store_id);
        echo "0\n";
        while($row = $result->fetch_assoc()) {
            echo "" . $row["tenCH"] . "\t". $row["sodienthoaiCH"]. "\t". $row["diachiCH"] ."\n" ;
        }
    }
}

// Lấy danh sách cửa hàng đã kích hoạt
if(isset($_POST["store_list"])){
    if(isset($_POST["owner_id"])){
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";
        $owner_id = $_POST["owner_id"];
        // Chỉnh sửa cửa hàng (sửa trạng thái có 1 nút khác)
        $result = filterStore($owner_id, 1);
        echo "0\n";
        while($row = $result->fetch_assoc()) {
            echo ""  . $row["tenCH"]. "\t". $row["maCH"] ."\n" ;
            
        }
    }
}

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
    
        require "./function.php";
        $result = filterOrderByOrderId($order_id);

        if(mysqli_num_rows($result) === 0) {
            echo "1"; // Thành công nhưng không có đơn hàng nào thỏa yêu cầu filter -> Trả về ?
        }        
        else {
            echo "0\n";
            while($row = $result->fetch_assoc()) {
                echo "". $row["hotenNN"] . "\t". $row["sodienthoaiNN"] ."\t" . $row["diachiNN"]."\t". $row["sttSP"] ."\t".  $row["tenSP"]."\t".  $row["soluongSP"]."\t".  $row["khoiluongSP"]."\n";
                // format echo: 1\tMinh Toan\tDang giao\t25000\n
            }
        }
    }
}


// Lấy danh sách điểm giao nhận
if(isset($_POST["DGN_list"])){
    // Kết nối tới database
    require './connection.php';

    // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
    require "./function.php";

    $result = DGN();
    echo "0\n";
    while($row = $result->fetch_assoc()) {
        echo "" . $row["sttDGN"] . " - ". $row["khuvucDGN"] . "\t". $row["diachiDGN"] ."\n" ; 
    }
}


// Tạo đơn hàng mới
if(isset($_POST["create_order"])){
    if(isset($_POST["store_id"]) && isset($_POST["size"]) && isset($_POST["resend_addr"])
     && isset($_POST["recv_name"]) && isset($_POST["recv_phone"]) && isset($_POST["recv_addr"]) 
     && isset($_POST["shift"]) &&  isset($_POST["status"])){
        $errors = array();
    
        $store_id = $_POST["store_id"];
        $resend_addr = $_POST["resend_addr"];
        $shift = $_POST["shift"]; 
        $size = $_POST["size"];
        $recv_name = $_POST["recv_name"];
        $recv_phone = $_POST["recv_phone"];
        $recv_addr = $_POST["recv_addr"];
        $status = $_POST["status"];
        $tenDGN = "";

        if(isset($_POST["tenDGN"])){
            $tenDGN = $_POST["tenDGN"];
        }

        require './connection.php';

        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Thêm dữ liệu vào bảng đơn hàng
        addOrder($store_id, $resend_addr, $shift, $size, $recv_name, $recv_addr, $recv_phone, $status, $tenDGN);
        // Echo trong funtion rồi    
    }
}

// Thêm sản phẩm vào đơn hàng
if(isset($_POST["add_item"])){
    if(isset($_POST["order_id"]) && isset($_POST["name"]) && isset($_POST["weight"])
     && isset($_POST["quantity"])){
        $errors = array();
    
        $order_id = $_POST["order_id"];
        $name = $_POST["name"];
        $weight = $_POST["weight"]; 
        $quantity = $_POST["quantity"];

        require './connection.php';

        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Thêm dữ liệu vào bảng đơn hàng
        addItem($order_id, $name, $weight, $quantity);
    }
}

// Lấy thông tin đơn nháp
if(isset($_POST["don_nhap"])){
    if(isset($_POST["order_id"])){
        $errors = array();
        
        $order_id = $_POST["order_id"];
    
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";
        $result = filterOrderByOrderId($order_id);
        if(mysqli_num_rows($result) === 0) {
            echo "1"; // Thành công nhưng không có đơn hàng nào thỏa yêu cầu filter -> Trả về ?
        }        
        else {
            echo "0\n";
            while($row = $result->fetch_assoc()) {
                if(isset($row["sttDGN"]))
                {
                    echo "" . $row["diachitrahang"] . "\t". $row["sttDGN"] ." - ". $row["khuvucDGN"] ."\t" . $row["calayhang"]."\t". $row["hotenNN"]. "\t" . $row["sodienthoaiNN"]. "\t" . $row["diachiNN"]."\n" ;
                }
                else
                {
                    echo "" . $row["diachitrahang"] . "\t". " - " ."\t" . $row["calayhang"]."\t". $row["hotenNN"]. "\t" . $row["sodienthoaiNN"]. "\t" . $row["diachiNN"]."\n" ;
                }
                // format echo: 1\tMinh Toan\tDang giao\t25000\n
            }
            $result2 = filterItemByOrderId($order_id);
            while($row = $result2->fetch_assoc()) {
                echo "" . $row["sttSP"] . "\t". $row["tenSP"] ."\t". $row["soluongSP"] ."\t" . $row["khoiluongSP"]."\n" ;
                // format echo: 1\tMinh Toan\tDang giao\t25000\n
            }
        }
    }
}

if(isset($_POST["delete_order"])){
    if(isset($_POST["order_id"])){
    
        $order_id = $_POST["order_id"];

        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Xóa cửa hàng
        deleteOrder($order_id);
    }
}


if(isset($_POST["save_order"])){
    if(isset($_POST["order_id"]) && isset($_POST["size"]) && isset($_POST["resend_addr"])
     && isset($_POST["recv_name"]) && isset($_POST["recv_phone"]) && isset($_POST["recv_addr"]) 
     && isset($_POST["shift"]) &&  isset($_POST["status"])){
        $errors = array();
    
        $order_id = $_POST["order_id"];
        $resend_addr = $_POST["resend_addr"];
        $shift = $_POST["shift"]; 
        $size = $_POST["size"];
        $recv_name = $_POST["recv_name"];
        $recv_phone = $_POST["recv_phone"];
        $recv_addr = $_POST["recv_addr"];
        $status = $_POST["status"];
        $tenDGN = "";

        if(isset($_POST["tenDGN"])){
            $tenDGN = $_POST["tenDGN"];
        }

        require './connection.php';

        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Thêm dữ liệu vào bảng đơn hàng
        //CALL capNhatDonHang(11111111, 'fucking tired - Tỉnh Hà Giang', TRUE, 500, 1, 'DTLT', '0123456789', 'sfs - Tỉnh Hà Giang', '2 - Sơn Trà' );
        //CALL updateOrder($order_id, $resend_addr, $shift, $size, $status, $recv_name, $recv_phone, $recv_addr, $tenDGN);
        global $db;
        $result = mysqli_query($db, "CALL capNhatDonHang($order_id, '$resend_addr', $shift, $size, $status, '$recv_name', '$recv_phone', '$recv_addr','$tenDGN')");
        echo mysqli_error($db);
        echo "0\n";
    }
}


?>