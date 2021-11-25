<?php

    // POST: Username, Card_id, email, name, password, type(Nhân viên/Chủ (0/1))
    /* echo
        -1: kết nối database thất bại
         1: Đăng nhập thành công
         0: Đăng nhập thất bại
    */

if(isset($_POST["userid"]) &&isset($_POST["name"]) && isset($_POST["id_card_num"]) && isset($_POST["email"]) && isset($_POST["phone"]) && isset($_POST["username"]) && isset($_POST["password1"]) && isset($_POST["password2"]) && isset($_POST["type"])){
    $errors = array();
    
    $email_max = 254;
    $usr_max = 20;
    $usr_min = 3;
    $pwd_max = 19;
    $pwd_min = 5;
    
    $user_id = $_POST["user_id"];
    $name = $_POST["name"];
    $type = $_POST["type"];
    $username = $_POST["username"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];
    $email = strtolower($_POST["email"]);
    $phone = $_POST["phone"];
    $id_card_num = $_POST["id_card_num"];
    
    // Đánh giá cú pháp của email
    if(preg_match('/\s/', $email) || !validate_email($email)){
        $errors[] = "Email không hợp lệ";
    }
    if(!validate_email($phone)){
        $errors[] = "Số điện thọai không hợp lệ";
    }
    // Kiểm tra độ dài của email
    else if(strlen($email) > $email_max){
        $errors[] = "Email phải nhỏ hơn " . strval($email_max) . " kí tự";
    }    
    // Kiểm tra độ dài của tên đăng nhập
    if(strlen($username) > $usr_max || strlen($username) < $usr_min){
        $errors[] = "Tên người dùng từ " . strval($usr_min) . " đến " . strval($usr_max) . " kí tự";
    }
    else{
        // Kiểm tra tên đăng nhập có chứa kí tự đặc biệt không
        if(!ctype_alnum ($username)){
            $errors[] = "Tên người dùng chỉ gồm chữ và số";
        }
    }
    // Kiểm tra mật khẩu nhập lại có khớp với mật khẩu nhập trước đó không
    if($password1 != $password2){
        $errors[] = "Mật khẩu nhập lại không đúng";
    }
    else{
        // Kiểm tra mật khẩu có chứa khoảng trắng
        if(preg_match('/\s/', $password1)){
            $errors[] = "Mật khẩu không chứa khoảng trắng";
        }
        else{
            // Kiểm tra độ dài của mật khẩu
            if(strlen($password1) > $pwd_max || strlen($password1) < $pwd_min){
                $errors[] = "Mật khẩu từ " . strval($pwd_min) . " đến " . strval($pwd_max) . " kí tự";
            }
        }
    }

    // Nếu không có lỗi gì thì kết nối tới database để kiểm tra người dùng tồn tại hay chưa
    // Nếu chưa thì thêm vào database
    if(count($errors) == 0){
        // Kết nối tới database
        require'./connection.php';
        global $db;
        // Câu query truy vấn kiểm tra người dùng đã tồn tại chưa (kiểm tra username và email)        
        $query = mysqli_query($db,"SELECT * FROM nguoi_dung WHERE Tendangnhap = '$username' AND Matkhau='$password'"); #SQL here

        // Nếu đã tồn tại
        if(mysqli_num_rows($query) > 0) {
            $errors[] = "Tên đăng nhập hoặc email đã tồn tại."; 
            echo "0";
            exit;
        }        
        // Nếu không có lỗi gì, và chưa tồn tại người dùng này thì thêm vào database
        else if (count($errors) == 0) {
            // Mã hóa mật khẩu trước khi thêm
            // $password = md5($password1);
            // Câu truy vấn thêm tài khoản vào database
            $query = mysqli_query($db,"INSERT INTO nguoi_dung VALUES ('$user_id', '$id_card_num', '$name', '$phone', '$email', '$username', '$password')");
            if ($type == "1") { // $stype == 1 ?
                // Thêm vào bảng chủ cửa hảng
                $query = mysqli_query($db,"INSERT INTO chu_cua_hang VALUES ('$user_id')");
            }
            else if ($type == "0"){
                // Thêm vào bảng chủ cửa hảng
                $query = mysqli_query($db,"INSERT INTO nhan_vien VALUES ('$user_id')");
            }   
        }
    }
    else
    {
        echo "2";
    }
}

function validate_email($email) {
    return preg_match('/^([a-z0-9!#$%&\'*+-\/=?^_`{|}~.]+@[a-z0-9.-]+\.[a-z0-9]+)$/i', $email);
}

// Validate Phone number
function validate_phone($phone) {
    $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    // Remove "-" from number
    $phone_to_check = str_replace("-", "", $filtered_phone_number);
    if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
        return false;
    }
    return true;
}
?>