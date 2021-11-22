<?php
$page = "Carousel";
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
        <th colspan="5"><a href="add_carousel.php">Add New Carousel Item</a></th>
      </tr>
      <tr>
        <th colspan="5">View Carousel items</th>
      </tr>
      <tr>
        <th>#</th>
        <th>Carousel Headline</th>
        <th>Carousel Caption</th>
        <th>Carousel Image</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
        try {
          $ftch = $conn->prepare("SELECT * FROM carousel");
          $ftch->setFetchMode(PDO::FETCH_OBJ);
          $ftch->execute();
          for ($i=1; $rtch = $ftch->fetch() ; $i++) { ?>
            <tr>
              <th><?php echo $i; ?></th>
              <td><?php echo $rtch->headline; ?></td>
              <td><?php echo $rtch->caption; ?></td>
              <td><img src="../assets/img/load.gif" class="lazy" data-src="../assets/img/carousel/<?php echo $rtch->img; ?>" alt="<?php echo $rtch->headline; ?>" style="max-height:30px" /></td>
              <td>
                <a href="edit_carousel.php?edt=<?php echo $rtch->id; ?>">Edit</a> |
                <a href="delete_carousel.php?dlt=<?php echo $rtch->id; ?>"  onclick="return confirm('Do you really want to delete <?php echo $rtch->headline; ?> from Categories?')">Delete</a>
              </td>
            </tr>
      <?php }
        } catch (PDOException $e) {
          echo "Oops : " .$e->getMessage() ;
        }

      ?>
    </tbody>
  </table>


</div>
<br/>
<?php require_once('footer.php'); ?>
