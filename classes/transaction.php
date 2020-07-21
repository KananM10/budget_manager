<?php

class Transaction{
   
   public $transactionAmount;
   public $transactionDate;
   public $category;
   public $paymentMethod;
   public $accountingType;


   public function __construct($transactionAmount,$transactionDate,$category,$paymentMethod,$accountingType){
      $this->transactionAmount = $transactionAmount;
      $this->transactionDate = $transactionDate;
      $this->category = $category;
      $this->paymentMethod = $paymentMethod;
      $this->accountingType = $accountingType;
   }
}

?>