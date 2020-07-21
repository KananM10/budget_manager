<?php

	require "../connection/connectDB.php";
	require "../classes/transaction.php";


	if(array_key_exists('idTransaction', $_POST) and is_numeric($_POST['idTransaction'])){
		$idCategory =  $conn->real_escape_string($_POST['idCategory']);					
		$transactionDate =  $conn->real_escape_string($_POST['transactionDate']);					
		$transactionAmount =  $conn->real_escape_string($_POST['transactionAmount']);					
		$idPayment =  $conn->real_escape_string($_POST['idPayment']); 
	}else{
		echo "error";
		exit();
	}

	if($idTransaction==0){//creation
		$stmnt = $conn->prepare("INSERT INTO transactions (idTransaction,transactionAmount,transactionDate,idCategory,idPayment) VALUES (NULL,?,?,?,?) "); 
	
		$stmnt -> bind_param('dsii',$transactionAmount,$transactionDate,$idCategory,$idPayment);
		$stmnt -> execute();	

		$conn->close();
	}
	header("Location: ../visible/main.php");

?>