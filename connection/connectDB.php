<?php

	ini_set('display_errors', 1);
	
	// $host="mysql-kananmikayilov.alwaysdata.net";
	// $user="169547";
	// $passwd="kenanm10";
	// $bd="kananmikayilov_hw_php";

	$host="localhost";
	$user="root";
	$passwd="";
	$bd="hw";

	$conn = new mysqli($host,$user,$passwd,$bd);

	if($conn->connect_errno){
			echo "Error N : ".$conn->connect_errno." , Msg ".$conn->connect_error."<br>";
			exit();
		}

	$conn->set_charset("utf8");
	
?>
