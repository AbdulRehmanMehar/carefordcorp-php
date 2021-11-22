<?php
  require_once('su/inc/db.inc.php');
  $ip = getUserIP();
  $ordered = '';
  $cart = $conn->prepare('SELECT COUNT(*) FROM cart WHERE ip_add=:ip AND ordered=:ordered');
  $cart->bindParam(':ip',$ip);
  $cart->bindParam(':ordered',$ordered);
  $cart->execute();
  $number = $cart->fetchColumn();
  echo $number;
?>
