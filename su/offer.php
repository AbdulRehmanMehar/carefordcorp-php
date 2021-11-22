<?php
$page = "Offer";
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

    <?php
      if (!empty($_GET['msg'])) {    $msg = $_GET['msg'];     ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo test_input($msg); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php  } ?>
  <!--view-->
<?php
if(!empty($_GET['make'])){
  if($_GET['make'] === 'yes'){
    $make = test_input($_GET['make']);  ?>
    <h3 class="text-center">Make offer</h3>
    <p class="text-center"><b>Note</b> : Offer will be deactivated Manually</p>
    <form action="" method="post">
      <div class="form-group">
        <input type="text" name="title" placeholder="Offer Title" class="form-control" required>
      </div>
      <div class="form-group">
        <textarea name="dsc" rows="5" cols="80" class="form-control" placeholder="Offer Description" required></textarea>
      </div>
      <!-- <div class="form-group">
        <input type="date" name="date_en" class="form-control" format="dd/MM/yyyy">
      </div> -->
      <input type="submit" name="mak" class="btn btn-info" value="Make">
    </form>
    <br>

    <?php
      if (isset($_POST['mak'])) {
        $title = test_input($_POST['title']);
        $dsc = test_input($_POST['dsc']);
        $status = '1';
        $set = $conn->prepare("INSERT INTO offer(title,dsc,status) VALUES(:title,:dsc,:status) ");
        $set->bindParam(':title',$title);
        $set->bindParam(':dsc',$dsc);
        $set->bindParam(':status',$status);
        if($set->execute()){echo '<script>window.open("offer.php?msg=Success","_self")</script>';}
      }
    ?>
<?php  } } ?>

<!-- edit queries -->

<?php
if(isset($_GET['edt'])) {
  $id = test_input($_GET['edt']);
  $eft = $conn->prepare("SELECT * FROM offer WHERE id=:id");
  $eft->bindParam(':id',$id);
  $eft->setFetchMode(PDO::FETCH_OBJ);
  $eft->execute();
  $rft = $eft->fetch(); ?>

  <h3 class="text-center">Edit offer</h3>
  <!-- <p class="text-center"><b>Note</b> : Offer will be deactivated Manually</p> -->
  <form action="" method="post">
    <div class="form-group">
      <input type="text" name="title" placeholder="Offer Title" class="form-control" value="<?php echo $rft->title; ?>">
    </div>
    <div class="form-group">
      <textarea name="dsc" rows="5" cols="80" class="form-control" placeholder="Offer Description"><?php echo $rft->dsc; ?></textarea>
    </div>
    <!-- <div class="form-group">
      <input type="date" name="date_en" class="form-control" format="dd/MM/yyyy">
    </div> -->
    <input type="submit" name="upd" class="btn btn-info" value="UPDATE">
  </form>
  <br>

<?php
if(isset($_POST['upd'])){
  $title = $_POST['title'];
  $dsc = $_POST['dsc'];
  $upd = $conn->prepare('UPDATE offer SET title=:title , dsc=:dsc');
  $upd->bindParam(':title',$title);
  $upd->bindParam(':dsc',$dsc);
  if($upd->execute()){echo "<script>window.open('offer.php?msg=Success','_self')</script>";}
}



}

?>
    <table class="table">
      <thead>
        <tr>
          <th colspan="4"><a href="offer.php?make=yes">Make Offer</a></th>
        </tr>
        <tr>
          <th colspan="4">View Offers</th>
        </tr>
        <tr>
          <th>#</th>
          <th>Offer Title</th>
          <th>Offer Details</th>
          <!-- <th>Offer Starts</th> -->
          <!-- <th>Offer Ends</th> -->
          <th>Action</th>
        </tr>

      </thead>
      <tbody>
        <?php
          $ftch = $conn->prepare("SELECT * FROM offer");
          $ftch->setFetchMode(PDO::FETCH_OBJ);
          $ftch->execute();
          for ($i = 1; $rtch = $ftch->fetch(); $i++) {  ?>
            <tr>
              <th><?php echo $i; ?></th>
              <td><?php echo $rtch->title; ?></td>
              <td><?php echo $rtch->dsc; ?></td>

              <td>
                <?php if($rtch->status !== '0'){ ?>
                  <a href='offer.php?deactive=<?php echo $rtch->id; ?>'>Deactivate</a>
                <?php }if($rtch->status === '0'){ ?>
                  <a href='offer.php?active=<?php echo $rtch->id; ?>'>Activate</a>
                <?php } ?>
                 | <a href='offer.php?del=<?php echo $rtch->id; ?>'>Delete</a>
                 | <a href="offer.php?edt=<?php echo $rtch->id ; ?>">Edit</a>
               </td>
            </tr>
        <?php  } ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once('footer.php'); ?>
<?php
  if(isset($_GET['active'])){
    $id = test_input($_GET['active']);
    $actst = '1';
    $act = $conn->prepare("UPDATE offer SET status=:status WHERE id=:id");
    $act->bindParam(':status',$actst);
    $act->bindParam(':id',$id);
    if($act->execute()){echo "<script>window.open('offer.php?msg=Success','_self')</script>";}
  }
  if(isset($_GET['deactive'])){
    $id = test_input($_GET['deactive']);
    $dctst = '0';
    $dct = $conn->prepare("UPDATE offer SET status=:status WHERE id=:id");
    $dct->bindParam(':status',$dctst);
    $dct->bindParam(':id',$id);
    if($dct->execute()){echo "<script>window.open('offer.php?msg=Success','_self')</script>";}
  }
  if(isset($_GET['del'])){
    $id = test_input($_GET['del']);
    $dl = $conn->prepare("DELETE FROM offer WHERE id=:id");
    $dl->bindParam(':id',$id);
    if($dl->execute()){echo "<script>window.open('offer.php?msg=Success','_self')</script>";}
  }
?>
