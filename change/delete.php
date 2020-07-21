<?php 

	require "../connection/connectDB.php";
	require "../classes/transaction.php";

	if(array_key_exists('idTransaction', $_GET) and is_numeric($_GET['idTransaction'])){
		$idTransaction=intval($_GET['idTransaction']);
	} else {
		echo "error";
		exit();
	}

	if($idTransaction>0){
		$query = "DELETE FROM `transactions` WHERE idTransaction = $idTransaction";

		$result=$conn->query($query) or die($query.' '.$conn->mysqli_error);
	}

	header("Location: ../visible/main.php");

?>