<?php
require_once('su/inc/db.inc.php');
if((isset($_POST['quantity'])) && isset($_POST['name']) && isset($_POST['code']) ){
  $ip = getUserIP();
  $quantity = $_POST['quantity'];
  $name = $_POST['name'];
  $code = $_POST['code'];
  $date_added = date('d-m-Y');
  $date_expiry = date('d-m-Y',strtotime('+1 month'));
  //check if exists
  // $chk = $conn->prepare('SELECT * FROM cart WHERE ip_add=:ip_add AND p_code=:p_code');
  // $chk->bindParam(':ip_add',$ip);
  // $chk->bindParam(':code',$code);
  // $chk->setFetchMode(PDO::FETCH_OBJ);
  // $chk->execute();
  // $rck = $chk->fetch();
  // if(($rck->ip === $ip) AND ($rck->p_code === $code)){
  //   return;
  // }

  //Inserting to database
  $atc = $conn->prepare('INSERT INTO cart(ip_add,p_name,p_code,quantity,date_added,date_expiry) VALUES(:ip,:name,:code,:quantity,:date_added,:date_expiry)');
  $atc->bindParam(':ip',$ip);
  $atc->bindParam(':name',$name);
  $atc->bindParam(':code',$code);
  $atc->bindParam(':quantity',$quantity);
  $atc->bindParam(':date_added',$date_added);
  $atc->bindParam(':date_expiry',$date_expiry);
  if($atc->execute()){
    $msg = $name . 'is Added to Cart';
  }
}
