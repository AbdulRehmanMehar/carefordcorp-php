<?php
$page = "Categories";
session_start();
if (empty($_SESSION['email'])) {
  header("Location: https://carefordcorp.com/su");
  session_unset();
  session_destroy();
   exit();
}
require_once('header.php');
try {

  if(!empty($_GET['dlt'])){
    $delete_id = test_input($_GET['dlt']);
    if(isset($delete_id)){
      $ftch = $conn->prepare("SELECT * FROM carousel WHERE id=:id");
      $ftch->bindParam(':id',$delete_id);
      $ftch->setFetchMode(PDO::FETCH_OBJ);
      $ftch->execute();
      $rtch = $ftch->fetch();
      $hdl = $rtch->headline;
      $ctn = $rtch->caption;

      $dlt = $conn->prepare("DELETE FROM carousel WHERE id=:di");
      $dlt->bindParam(':di',$delete_id);
      $dltv = $dlt->execute();
      if($dltv){header('Location: carousel.php?msg=You%20have%20deleted%20%27'.$hdl.'%27%20Successfully%20along%20with%20image%20and%20%27'.$ctn.'%27');}
      else{header('Location: carousel.php?msg=Something%20went%20wrong');}
    }
  }

} catch (PDOException $e) {
  echo "Oops : " . $e->getMessage();
}
