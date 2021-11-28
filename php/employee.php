<?php
// Truyền vào các sản phẩm trong đơn hàng dạng ?
/* echo:
    -1: kết nối database thất bại 
     1: thành công nhưng không có hàng nào thỏa yêu cầu
     2: thành công có hàng thỏa
        1\tMinh Toan\tDang giao\t25000\n
        2\tMinh Toan\tDang giao\t25000\n
        
*/

if(isset($_POST["add_employee"])){
    if(isset($_POST["store_id"]) && isset($_POST["employee_id"])){
        $errors = array();
    
        $store_id = $_POST["store_id"];
        $employee_id = $_POST["employee_id"];

        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Thêm dữ liệu vào bảng đơn hàng
        addEmployee($store_id, $employee_id);
        
        // Thêm dữ liệu vào bảng quản lí

            /////////////////////////////

        // 
    }
}

else if(isset($_POST["employee_filter"])){
    if(isset($_POST["store_id"])){
        $errors = array();
        
        // Trả về họ và tên, số điện thoại và danh sách cửa hàng => Trả về thêm id để chỉnh sửa và xóa
        $store_id = $_POST["store_id"];
    
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";
        $result = filterEmployee($store_id);
        if(mysqli_num_rows($result) === 0) {
            echo "1"; // Thành công nhưng không có đơn hàng nào thỏa yêu cầu filter -> Trả về ?
        }        
        else {
            echo "0\n";
            while($row = $result->fetch_assoc()) {
                echo "". $row["maND"] . "\t" . $row["hotenND"] ."\t". $row["sodienthoaiND"] ."\t" . $row["maCH"] ."\n";
                // format echo: 1\t\tDang giao\t25000\n
            }
        }
    }
}

else if(isset($_POST["delete"])){
    if(isset($_POST["$employee_id"])){
    
        $employee_id = $_POST["employee_id"];
    
        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Xóa cửa hàng
        deleteEmployee($employee_id);
    }
}

else if(isset($_POST["update"])){
    if(isset($_POST["$employee_id"]) && isset($_POST["$store_id"]) && isset($_POST["$store_id"])){ // Nhận nhiều giá trị?  
        
        $store_id = $_POST["store_id"];
        $employee_id = $_POST["employee_id"];


        // Kết nối tới database
        require './connection.php';
    
        // Câu query lấy tất cả các đơn hàng từ $time_from tới $time_to của cửa hàng $store
        require "./function.php";

        // Chỉnh sửa cửa hàng (sửa trạng thái có 1 nút khác)
        updateEmployeeStore($employee_id, $store_id, $value);
    }
}

?>