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
<?php require_once('header.php'); ?>
<div class="container">

  <!-- Page Heading/Breadcrumbs -->
  <h1 class="mt-4 mb-3"><?php echo $page; ?>
    <small>(Super User => Careford Corp)</small>
  </h1>

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="https://carefordcorp/su">Super User</a>
    </li>
    <li class="breadcrumb-item active"><?php echo $page; ?></li>
  </ol>

  <br/>

  <div class="text-center">
    <h3>Add Category</h3>
    <form class=" mx-auto" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
      <div class="form-group mx-sm-3">
        <label for="newcat" class="sr-only">New Category Name</label>
        <input type="text" class="form-control" id="newcat" name='catname' placeholder="i.e Beauty" required>
      </div>
      <input type="submit" class="btn btn-primary" value="Add">
    </form>
  </div>
  <br/><br/>
</div>

<?php
try {
  if(($_SERVER['REQUEST_METHOD'] == "POST")){
    $catname = test_input($_POST['catname']);
    if(!preg_match ('/^([a-z]+)$/', $catname)){
      header("Location: categories.php?msg=Only%20Lower%20Case%20Letters%20are%20Allowed");
      return;
    }
    $chk = $conn->prepare("SELECT * FROM categories");
    $chk->setFetchMode(PDO::FETCH_OBJ);
    $chk->execute();
    while($chkr = $chk->fetch()){
      if ($chkr->title == $catname) {header("Location: categories.php?msg=Category%20%27".$catname."%27%20Already%20Exists"); return;}
    }

      $insrt = $conn->prepare("INSERT INTO categories(title) VALUES(:title)");
      $insrt->bindParam(":title",$catname);
      $ited = $insrt->execute();
      if ($ited) {header("Location: categories.php?msg=Category%20%27".$catname."%27%20Added%20to%20Database");}
      else{  header("Location: categories.php?msg=Something%20went%20worng");  }

  }
} catch (PDOException $e) {
  echo "Oops : " .$e->getMessage();
}



require_once('footer.php');
?>
