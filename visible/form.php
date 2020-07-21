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
		$query = "SELECT transactions.idTransaction, transactions.transactionAmount, transactions.transactionDate,
    categories.category, payments.paymentMethod, accounting.accountingType 
            FROM transactions 
            join  payments  on transactions.idPayment  = payments.idPayment 
            join categories on transactions.idCategory  = categories.idCategory 
            join accounting on categories.idAccounting =  accounting.idAccounting 
            WHERE idTransaction = $idTransaction 
            ORDER BY transactions.idTransaction DESC";

		$result=$conn->query($query) or die($query.' '.$conn->mysqli_error);
		$var=mysqli_fetch_assoc($result);
		extract($var);
	}


	$query1 = "SELECT * FROM categories";
	$query2 = "SELECT * FROM payments";
	
	$result1 = $conn->query($query1);
	$listCategory=array();
	while ($var = $result1->fetch_assoc()) {
	  $listCategory[$var['idCategory']] = $var['category'];
	}

	$result2 = $conn->query($query2);
	$listPayments=array();
	while ($var = $result2->fetch_assoc()) {
	  $listPayments[$var['idPayment']] = $var['paymentMethod'];
	}
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Budget Management</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/index.css">

</head>

<body>

	<header>
      <div class="name">
          <h3><a href="main.php">Budget Management Tool</a></h3>
      </div>

      <div class="list">
			<a href="main.php">Data</a>
          </ul>
      </div>
    </header>

	<div class="main">
		<form action="../change/update.php" method="post" class="form">
			<input type="hidden" name="idTransaction" value="<?php echo $idTransaction; ?>">
			
			<div class="form_row">
				
				<div class="col1">
					<label for="id_amount">Amount</label>
				</div>
				
				<div class="col2">
				 	<input type="number" class="form-control" name="transactionAmount" id="id_amount" placeholder="amount spent..." value="<?=(isset($_POST['amount'])) ? $_POST['amount'] : "" ?>" required>
				</div>
			</div>
			
			<div class="form_row">
				<div class="col1">
					<label for="id_date">Date</label>
				</div>
				<div class="col2">
				<input type="date" class="form-control" name="transactionDate" id="id_date" placeholder="MM/DD/YYYY" value="
                    <?=(isset($_POST['date'])) ? $_POST['date'] : "" ?>" required>
                </div>
			</div>

			<div class="form_row">
				<div class="col1">
				<label for="category">Category</label>
				</div>
				<div class="col2">
					<select id="category" name="idCategory" class="custom-select" required>
						<option value="">Choose category</option>
			            <?php
			                foreach ($listCategory as $keyCategory => $valueCategory) {
			                    echo "<option value=\"$keyCategory\"";
			                    if(isset($idCategory) and $keyCategory==$idCategory) echo " selected";
			                    echo ">$valueCategory</option>";
			                }
			            ?>
			        </select>
		    	</div>

			</div>


			<div class="form_row">
				<div class="col1">
					<label for="id_payment">Payment Method</label>
				</div>
				<div class="col2">
					<select id="payment" name="idPayment" class="custom-select" required>
						<option value="">Choose payment method</option>
			            <?php
			                foreach ($listPayments as $keyPayment => $valuePayment) {
			                    echo "<option value=\"$keyPayment\"";
			          if(isset($idPayment) and $keyPayment==$idPayment) echo " selected";
			                    echo ">$valuePayment</option>";
			                }
			            ?>

			        </select>
			    </div>
			</div>

			<div class="form_row">
				<div class="submit col1">
				</div>

				<div class="reset col2">
					<button type="submit" name="submit" class="btn btn-outline-success">Add</button>
					<button type="Reset" class="btn btn-outline-primary">Reset</button>
				</div>
			</div>

		</form>
	</div>
	<script type="text/javascript" src="../javascript/main.js"></script>
</body>
</html>