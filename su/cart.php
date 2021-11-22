<?php
$page = "Cart";
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

    <table class="table text-center table-responsive-sm">
      <tr>
        <th colspan="7">View Orders</th>
      </tr>
      <tr>
        <th>#</th>
        <th>Product</th>
        <th>Product Code</th>
        <th>Quantity</th>
        <th>Buyer Name</th>
        <th>Buyer Email</th>
        <th>Action</th>
      </tr>
      <?php
        $oc = '1';
        $ftch = $conn->prepare("SELECT * FROM cart WHERE order_cnfrm=:oc");
        $ftch->bindParam(':oc',$oc);
        $ftch->setFetchMode(PDO::FETCH_OBJ);
        $ftch->execute();
        for($i = 1 ; $rtch = $ftch->fetch() ; $i++){  ?>
          <tr>
            <th><?php echo $i; ?></th>
            <td><?php echo $rtch->p_name; ?></td>
            <td><?php echo $rtch->p_code; ?></td>
            <td><?php echo $rtch->quantity; ?></td>
            <td><?php echo $rtch->buyer_name; ?></td>
            <td><?php echo $rtch->buyer_email; ?></td>
            <td><a href="#<?php echo $rtch->id; ?>" data-toggle="modal">Buyer's Complete Info</a>
              | <a href="cart.php?delete=<?php echo  $rtch->id; ?>">Delete</a>
            </td>
            <div class="modal fade" id="<?php echo $rtch->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Buyer's Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body text-center mb-1 mt-1" style="max-width:auto !important">
                    <?php
                      $binfo = $conn->prepare("SELECT * FROM customers WHERE email=:mail");
                      $binfo->bindParam(':mail',$rtch->buyer_email);
                      $binfo->setFetchMode(PDO::FETCH_OBJ);
                      $binfo->execute();
                      $rinfo = $binfo->fetch();
                    ?>
                    <div class="background-img mb-4" style="background-image:url('../assets/img/customers/<?php echo $rinfo->img; ?>')"></div>
                    <h3 class="mb-3"><b>Name</b> : <?php echo $rinfo->name; ?></h3>
                    <h4 class="mb-3 fns"><b>Email</b> : <a href='mailto:<?php echo $rinfo->email; ?>'><?php echo $rinfo->email; ?></a></h4>
                    <h5 class="mb-3"><b>Phone</b> : <a href='tel:<?php echo $rinfo->phone; ?>'><?php echo $rinfo->phone; ?></a></h5>
                    <h6 class="mb-3"><b>Country</b> : <?php echo $rinfo->country; ?> , <b>City</b> : <?php echo $rinfo->city; ?> , <b>Zip</b> : <?php echo $rinfo->zip; ?></h6>
                    <p><b>Address</b> : <?php echo $rinfo->address_1; ?></p>
                    <p><b>Address 2</b> : <?php echo $rinfo->address_2; ?></p>
                  </div>
                </div>
              </div>
            </div>
          </tr>
      <?php } ?>
    </table>
</div>

<?php require_once('footer.php'); ?>
<?php
if(isset($_GET['delete'])){
  $delete_id = test_input($_GET['delete']);
  $del = $conn->prepare('DELETE FROM cart WHERE id=:id');
  $del->bindParam(':id',$delete_id);
  if($del->execute()){
    header('Location: cart.php?msg=Success');
  }
}
?>
