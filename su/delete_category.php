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
//Delete Data
if(isset($_GET['dlt'])){
  //echo"<script>alert('Success')</script>";
    $dltd = test_input($_GET['dlt']);
    $ftch = $conn->prepare("SELECT * FROM categories WHERE id=:title");
    $ftch->bindParam(":title",$dltd);
    $ftch->setFetchMode(PDO::FETCH_OBJ);
    $ftch->execute();
    $rw = $ftch->fetch();
    $ald = $rw->title;


    $sdlt = $conn->prepare("DELETE FROM categories WHERE id=:dt");
    $sdlt->bindParam(':dt',$dltd);
    $dltvar = $sdlt->execute();
    if($dltvar){ header("Location: categories.php?msg=You%20Have%20Deleted%20%27".$ald."%27%20Successfully");}
    else{header("Location: categories.php?msg=Something%20went%20wrong%20");}
}

require_once('footer.php');


?>
