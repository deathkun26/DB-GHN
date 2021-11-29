<?php

    // POST: Username, Card_id, email, name, password, type(Nhân viên/Chủ (0/1))
    /* echo
        -1: kết nối database thất bại
         1: Đăng nhập thành công
         0: Đăng nhập thất bại
    */

if(isset($_POST["name"]) && isset($_POST["id_card_num"]) && isset($_POST["email"]) && isset($_POST["phone"]) && isset($_POST["username"]) && isset($_POST["password1"]) && isset($_POST["type"])){
    $errors = array();
    
    $name = $_POST["name"];
    $type = $_POST["type"];
    $username1 = $_POST["username"];
    $password1 = $_POST["password1"];
    $email = strtolower($_POST["email"]);
    $phone = $_POST["phone"];
    $id_card_num = $_POST["id_card_num"];
    
    // Nếu không có lỗi gì thì kết nối tới database để kiểm tra người dùng tồn tại hay chưa
    // Nếu chưa thì thêm vào database
    if(count($errors) == 0){
        // Kết nối tới database
        require'./connection.php';

        require'./function.php';
        
        $query = mysqli_query($db,"SELECT * FROM nguoi_dung WHERE Tendangnhap = '$username1' AND Matkhau='$password1'"); #SQL here

        // Nếu đã tồn tại
        if(mysqli_num_rows($query) > 0) {
            $errors[] = "Tên đăng nhập hoặc email đã tồn tại."; 
            echo "1";
        }        
        // Nếu không có lỗi gì, và chưa tồn tại người dùng này thì thêm vào database
        else {
            // Mã hóa mật khẩu trước khi thêm
            // $password = md5($password1);
            // Câu truy vấn thêm tài khoản vào database
            addUser($id_card_num, $name, $username1, $password1, $email, $phone, $type);
        }
    }
}
?>
