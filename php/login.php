<?php
    // POST: Email, Password
    /* echo
        -1: kết nối database thất bại
         1: Đăng nhập thành công
         0: Đăng nhập thất bại
    */
	if(isset($_POST["username"]) && isset($_POST["password"])){
		$errors = array();
		
        require './connection.php';
        global $db;
		$username = mysqli_real_escape_string($db, $_POST['username']);
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if (empty($username)) {
            $errors[] = "Vui lòng nhập tên tài khoản.";
        }
        if (empty($password)) {
            $errors[] = "Vui lòng nhập mật khẩu.";
        }

        if (count($errors) == 0) {
            // Mã hóa mật khẩu trước khi kiểm tra
            // $password = md5($password);
            // Câu truy vấn kiểm tra tài khoản đăng nhập đúng hay không?
            require "./function.php";
            $results = getUser($username, $password);
            if (mysqli_num_rows($results) === 1) {
                echo "ừa, ok";
                //echo "1"; // Login thành công
                return;
            }
            else {
                $errors[] = "Tên đăng nhập hoặc mật khẩu không đúng";
                echo "del";
                //echo "0"; // Sai tên đăng nhập / mật khẩu
            }
        }
        else {
            $errors[] = "Nhập thiếu tên hoặc mật khẩu";
            echo "2";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Dan">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Assignment</title>
	<link
      rel="icon"
      type="image/x-icon"
      href="../images/Logo BK_vien trang.png"
    />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./Views/Login/login.css">
	<link href="./Views/Navbar/navbar.css" rel="stylesheet" type="text/css" />
	
	<link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,400;0,500;0,600;0,700;0,800;1,200;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <script src="https://use.fontawesome.com/721412f694.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
<div class="card-wrapper col-md-8 border-warning w-25">
    <div class="card fat p-1">
        <div class="card-body">
            <h3 class="card-title mb-4">Đăng nhập</h3>
            <form method="POST" class="my-login-validation" action="login.php">
                <div class="form-group">
                    <label for="validationCustomUsername" class="form-label">Tên đăng nhập</label>
                    <div class="input-group has-validation">
                        <input type="text" class="form-control" id="username" required name="username">
                        <div class="invalid-feedback">
                            Tên đăng nhập trống.
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2 mb-2">
                    <label class="d-flex justify-content-between" for="password">Mật khẩu
                    <a href="?url=/Home/forgot/" class="float-right">
                        Quên mật khẩu?
                    </a>
                    </label>
                    <input id="password" type="password" class="form-control mt-2" name="password" required data-eye>
                    <div class="invalid-feedback">
                        Mật khẩu trống
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="remember" id="remember" class="custom-control-input">
                    <label for="remember" class="custom-control-label">Ghi nhớ tài khoản</label>
                </div>

                <div class="card mt-4">
                    <button type="submit" class="btn btn-warning btn-block w-100">
                        Đăng nhập
                    </button>
                </div>
                <div class="mt-4 text-center">
                    Bạn chưa có tài khoản? <a href="?url=/Home/register/">Đăng kí thành viên</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>