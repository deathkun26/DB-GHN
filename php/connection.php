<?php
	$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "transfer_system";

	$db = mysqli_connect($servername, $username, $password, $dbname);
    if (mysqli_connect_errno()) {
        echo "-1"; 
    }
?>