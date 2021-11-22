<?php
$page = "Categories";
session_start();
if (empty($_SESSION['email'])) {
  header("Location: https://carefordcorp.com/su");
  session_unset();
  session_destroy();
   exit();
}

?>
<?php require_once('../../../header.php'); ?>



<?php require_once('../../../footer.php'); ?>
