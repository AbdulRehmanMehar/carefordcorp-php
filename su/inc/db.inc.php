<?php
// session_start();
try {
  $conn = new PDO("mysql:host=localhost;dbname=carefordcorp_main",'carefordcorp','H@<k3r6925');
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //if ($conn){echo "Connected";}

} catch (PDOException $e) {
  echo "Opps : " . $e->getMessage();
  die();
}
//
// if($_SERVER['REQUEST_METHOD'] === 'POST' ){
//   if((!isset($_POST['_token']) || ($_POST['_token'] !== $_SESSION['_token']))){
//     die('Something Went Wrong');
//   }
// }
// $_SESSION['_token'] = bin2hex(random_bytes(16));

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data,ENT_QUOTES,'UTF-8');
  return $data;
}
require_once('ip.inc.php');
require_once('hash.inc.php');
?>
