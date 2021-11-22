<?php
$page = "Messages";
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
    if (!empty($_GET['msg'])) {    $msg = test_input($_GET['msg']);     ?>
    <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
      <?php echo $msg; ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php  } ?>

  <?php
    if(!empty($_GET['rp'])){
      if(isset($_GET['rp'])){
          $rp = test_input($_GET['rp']);
          $rpq = $conn->prepare("SELECT * FROM message WHERE id=:idr");
          $rpq->bindParam(':idr',$rp);
          $rpq->setFetchMode(PDO::FETCH_OBJ);
          $rpq->execute();
          $row = $rpq->fetch();
  ?>
      <h2 class="text-center">Reply</h2>
      <form  method="post" action="<?php $_SERVER['PHP_SELF'] ?>">

        <div class="form-group row">
          <label for="staticEmail" class="col-sm-2 col-form-label">Reply to email: </label>
          <div class="col-sm-10">
            <input type="text" readonly class="form-control-plaintext extended" id="staticEmail" name='rpte' value="<?php echo $row->email; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="staticmsg" class="col-sm-2 col-form-label">Reply to message: </label>
          <div class="col-sm-10">
            <input type="text" readonly class="form-control-plaintext extended" id="staticmsg" name='rptm' value="<?php echo $row->msg; ?>">
          </div>
        </div>

        <div class="form-group row">
          <label for="ymsg" class="col-sm-2 col-form-label">Your Message</label>
          <div class="col-sm-10">
            <textarea name="rply" class="form-control" id="ymsg" rows="5" cols="80" required></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label for="submit" class="col-sm-2 col-form-label">Reply</label>
            <div class="col-sm-10">
              <input type="submit" name="reply" class="btn btn-outline-success extended" value="Reply" />
            </div>
        </div>
        <?php
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $to = test_input($_POST['rpte']);
            $txt = test_input($_POST['rply']);
            $subject = "Careford Corp Contact Form";
            $headers = "From: carefordcorp@gmail.com";
            $mail = mail($to,$subject,$txt,$headers);
            if($mail){echo "<script>window.open('messages.php?msg=Replied%20Successfully','_self')</script>";}
        }
        ?>
      </form>
      <br/>
  <?php  }  }  ?>









</div>
<?php require_once('footer.php'); ?>
