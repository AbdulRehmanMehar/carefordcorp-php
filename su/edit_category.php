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

  <?php
  try {
    if(!empty($_GET['edt'])){
      $edt = test_input($_GET['edt']);
      $ftch = $conn->prepare("SELECT * FROM categories WHERE id=:title");
      $ftch->bindParam(":title",$edt);
      $ftch->setFetchMode(PDO::FETCH_OBJ);
      $ftch->execute();
      $rw = $ftch->fetch();
      $ald = $rw->title;
      if(($_SERVER['REQUEST_METHOD'] == "POST")){
        $catname = $_POST['catname'];
        if(!preg_match ('/^([a-z]+)$/', $catname)){
          header("Location: categories.php?msg=Only%20Lower%20Case%20Letters%20are%20Allowed");
          return;
        }
        $updte = $conn->prepare("UPDATE categories SET title=:titl WHERE id=:pt");
        $updte->bindParam(":titl",$catname);
        $updte->bindParam(":pt",$edt);
        $ited = $updte->execute();
        if ($ited) {header("Location: categories.php?msg=Category%20%27".$ald."%27%20updated%20to%20%27".$catname."%27");}
        else{  header("Location: categories.php?msg=Something%20went%20worng");  }

      }
    }


  } catch (PDOException $e) {
    echo "Oops : " .$e->getMessage();
  }



  ?>


  <div class="text-center">
    <h3>Edit Category</h3>
    <form class=" mx-auto" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
      <div class="form-group mx-sm-3">
        <label for="newcat" class="sr-only">New Category Name</label>
        <input type="text" class="form-control" id="newcat" name='catname' placeholder="i.e Beauty" required value="<?php echo $ald;  ?>">
      </div>
      <input type="submit" class="btn btn-primary" value="Update">
    </form>
  </div>
  <br/><br/>
</div>
<?php
  require_once('footer.php'); ?>
