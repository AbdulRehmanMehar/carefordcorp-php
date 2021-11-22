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

  <!-- fetch carousel -->
  <table class="table table-responsive-sm text-center">
    <thead>
      <tr>
        <th colspan="5"><a href='filter_messages.php?type=old'>Old Messages</a> | <a href='filter_messages.php?type=all'>All Messages</a></th>
      </tr>
      <tr>
        <th colspan="5">New Messages</th>
      </tr>
      <tr>
        <th>#</th>
        <th>Sender's Name</th>
        <th>sender's Email</th>
        <th>Message Content</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
        try {
          $rdchk = 0;
          $ftch = $conn->prepare("SELECT * FROM message WHERE read_chk=:rdk");
          $ftch->bindParam(':rdk',$rdchk);
          $ftch->setFetchMode(PDO::FETCH_OBJ);
          $ftch->execute();
          for($i=1; $rtch = $ftch->fetch(); $i++){  ?>
            <tr>
              <th><?php echo $i; ?></th>
              <td><?php echo $rtch->name; ?></td>
              <td><?php echo $rtch->email; ?></td>
              <td><?php echo $rtch->msg; ?></td>
              <td><a href="messages.php?mar=<?php echo $rtch->id; ?>" onclick="return confirm(' <?php echo $rtch->email; ?> will be moved to Old Messages ')">Mark as Read</a>
                | <a href="reply_message.php?rp=<?php echo $rtch->id ?>">Reply</a>
                | <a href="messages.php?del=<?php echo $rtch->id; ?>" onclick="return confirm(' <?php echo $rtch->email; ?> will be deleted ')">Delete</a></td>
            </tr>
        <?php  }
        } catch (PDOException $e) {
          echo "Oops : ".$e->getMessage();
        }

      ?>
    </tbody>
  </table>
</div>
<br/>
<?php require_once('footer.php'); ?>
<?php
//mark as read
if(!empty($_GET['mar'])){
  $mar = test_input($_GET['mar']);
  $marf = 1;
  $mrk = $conn->prepare("UPDATE message SET read_chk=:mrdk WHERE id=:uid");
  $mrk->bindParam(':mrdk',$marf);
  $mrk->bindParam(':uid',$mar);
  $mrkd = $mrk->execute();
  if($mrkd){echo "<script>window.open('messages.php?msg=Success','_self')</script>";}
}
if(!empty($_GET['del'])){
  $dlt = $_GET['del'];
  $dq = $conn->prepare("DELETE FROM message WHERE id=:dld");
  $dq->bindParam(':dld' , $dlt);
  $dltd = $dq->execute();
  if($dltd){echo "<script>window.open('messages.php?msg=Successfully%20Deleted','_self')</script>";}
}
?>
