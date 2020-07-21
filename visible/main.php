<?php

  require "../connection/connectDB.php";
  require "../classes/transaction.php";

  $query = "SELECT * 
            FROM transactions 
            join  payments  on transactions.idPayment  = payments.idPayment 
            join categories on transactions.idCategory  = categories.idCategory 
            join accounting on categories.idAccounting =  accounting.idAccounting";

  $final_query = "";
  
  if(isset($_POST['search'])){

    $idcategory =  $conn->real_escape_string($_POST['idCategory']);
    $idpayment =  $conn->real_escape_string($_POST['idPayment']);
    $idaccounting =  $conn->real_escape_string($_POST['idAccounting']);

   
    if($idcategory != '' || $idpayment != '' || $idaccounting != ''){
      $query .= " WHERE";
    }
    
    if($idcategory != ''){
      $query .= " categories.idCategory = $idcategory AND";
    }

    if($idpayment != ''){
      $query .= " payments.idPayment = $idpayment AND";
    }

    if($idaccounting != ''){
      $query .= " accounting.idAccounting = $idaccounting AND";
    }

    $final_query .= $query;

    if($idcategory != '' || $idpayment != '' || $idaccounting != ''){
      $final_query = substr($final_query, 0, -3);
    }

    $final_query .= " ORDER BY transactions.idTransaction ASC";
  
  }else{
    $final_query .= $query." ORDER BY transactions.idTransaction ASC";
  }
    
  
  $result =$conn->query($final_query)  or die($conn->error);


  $listTransactions = array();
  while($var = $result->fetch_assoc()){
    extract($var);
    $listTransactions[$idTransaction] = new Transaction($transactionAmount,$transactionDate, $category, $paymentMethod, $accountingType);
  }


  $query1 = "SELECT * FROM categories";
  $query2 = "SELECT * FROM payments";
  $query3 = "SELECT * FROM accounting";

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

  $result3 = $conn->query($query3);
  $listAccounting = array();
  while ($var = $result3->fetch_assoc()) {
    $listAccounting[$var['idAccounting']] = $var['accountingType'];
  }



  $query_transactions = "SELECT transactions.idTransaction, transactions.transactionAmount, transactions.transactionDate,
    categories.category, payments.paymentMethod, accounting.accountingType 
            FROM transactions 
            join  payments  on transactions.idPayment  = payments.idPayment 
            join categories on transactions.idCategory  = categories.idCategory 
            join accounting on categories.idAccounting =  accounting.idAccounting 
            ORDER BY transactions.idTransaction DESC";

  $result4 = $conn->query($query_transactions) or die($query_transactions.' '.$conn->mysqli_error);

  $listTransactions = array();
  while($var = mysqli_fetch_assoc($result4)){
    extract($var);
    $listTransactions[$idTransaction] = new Transaction($transactionAmount,$transactionDate, $category, $paymentMethod, $accountingType);
  }
            
  $summary = 0;
  $expense = 0;
  $income = 0;
  foreach ($listTransactions as $idTransaction => $valueTransaction){
      
    if($valueTransaction->accountingType == 'income'){
        $income += $valueTransaction->transactionAmount;
        $summary+= $valueTransaction->transactionAmount;
    }else{
        $expense += $valueTransaction->transactionAmount;
        $summary -= $valueTransaction->transactionAmount;
    }
    $inc = sprintf("%.2f", $income);
    $sum = sprintf("%.2f", $summary);
    $exp = sprintf("%.2f", $expense);

  }

?> 

<!DOCTYPE html>
<html>
<head>

  <title>Budget Management</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>

  <link rel="stylesheet" type="text/css" href="../css/index.css">

  <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.1/js/dataTables.scroller.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scal=1.0">

  <script>
    $(document).ready(function() {
      $('#table').DataTable();
    } );

    // let content = document.getElementById('table-wrapper');
    // console.log(content); 
    // let firstChild = content.firstChild;
    // content.removeChild(firstChild);

  </script>  
</head>
<body>
    <header>
      <div class="name">
          <h3><a href="main.php">Budget Management Tool</a></h3>
      </div>

      <div class="list">
          <a href="form.php?idTransaction=0">Add transaction</a>
      </div>
    </header>

    <div class="main">
    
      <div class="filter">
            
            <?php
              echo "<div class=\"show\">";
              echo "<span class=\"s1\">TOTAL INCOME: ".$inc."</span>";
              echo "<span class=\"s2\">TOTAL EXPENSE: ".$exp."</span>";
              echo "<span class=\"s3\">NET: ".$sum."</span>";
              echo "</div>"
            ?>
            
            <form action="main.php" method="POST">
                
                <div class="filter-row">
                        <select id="category" name="idCategory" class="custom-select">
                            <option value="">Choose a category</option>
                            <?php
                                foreach ($listCategory as $keyCategory => $valueCategory) {
                                    echo "<option value=\"$keyCategory\"";
                                    if(isset($idCategory) and $keyCategory==$idCategory);
                                    echo ">$valueCategory</option>";
                                }
                            ?>
                        </select>
                  </div>
                    
                     <div class="filter-row">
                        <select id="payment" name="idPayment" class="custom-select">
                          <option value="">Choose a payment method</option>                         
                          <?php
                              foreach ($listPayments as $keyPayment => $valuePayment) {
                                  echo "<option value=\"$keyPayment\"";
                                 if(isset($idPayment) and $keyPayment==$idPayment) ;
                                  echo ">$valuePayment</option>";
                              }
                          ?>

                        </select>
                 </div>
                   <div class="filter-row">
                        <select id="accounting" name="idAccounting" class="custom-select">
                          <option value="">Choose an accounting type</option>
                          <?php
                              foreach ($listAccounting as $keyAccounting => $valueAccounting) {
                                  echo "<option value=\"$keyAccounting\"";
                                if(isset($idAccounting) and $keyAccounting==$idAccounting);
                                  echo ">$valueAccounting</option>";
                              }
                          ?>
                        </select>
                </div>
                <div class="btn-row">
                  <button type="submit" name="search" class="btn btn-primary btn-custom-dark search_btn">Search</button>
                </div>
            </form>


        </div>

      <div class="table-wrapper mt-5">
           <table class="table" id="table">
            <thead class="thead-dark">
              <tr>
                  <th scope="col">#</th>
                  <th scope="col">Amount</th>
                  <th scope="col">Date</th>
                  <th scope="col">Category</th>
                  <th scope="col">Payment</th>
                  <th scope="col">Accounting</th>
                  <th></th>
              </tr>
            </thead>

            <tbody>

            <?php
              foreach ($listTransactions as $idTransaction => $valueTransaction) {
                echo "<tr>
                        <td>$idTransaction</td>
                        <td>$valueTransaction->transactionAmount</td>
                        <td>$valueTransaction->transactionDate</td>
                        <td>$valueTransaction->category</td>
                        <td>$valueTransaction->paymentMethod</td>
                        <td>$valueTransaction->accountingType</td>
                        <td><a href=\"../change/delete.php?idTransaction=$idTransaction\"><img src=\"../images/delete1.png\" width=\"50\" height=\"50\"></a></td>";
              }
            ?>
            </tbody>
          </table>
      </div>
    </div>
</body>
</html>
