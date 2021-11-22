<?php
  $page="Cart"; require_once("header.php");
?>
<div class="container">

  <!-- Page Heading/Breadcrumbs -->
  <h1 class="mt-4 mb-3"><?php echo $page; ?>
    <small>(<?php echo $brand; ?>)</small>
  </h1>

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?php echo $brand_url; ?>"><?php echo $brand; ?></a>
    </li>
    <li class="breadcrumb-item active"><?php echo $page; ?></li>
  </ol>
  <?php
    $ip = getUserIP();

    $chk = $conn->prepare('SELECT * FROM cart WHERE ip_add=:ip_add');
    // $chk->bindParam(':ordered',$ordered);
    $chk->bindParam(':ip_add',$ip);
    $chk->setFetchMode(PDO::FETCH_OBJ);
    $chk->execute();
    $rck = $chk->fetch();
    if((@$rck->id !== 0) AND (@$rck->ordered === '') ){
  ?>
    <table class="table text-center table table-responsive-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Product Name</th>
          <th>Quantity</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $ip = getUserIP();
          $ordered = '';
          $ftch = $conn->prepare("SELECT * FROM cart WHERE ip_add=:ip  AND ordered=:ordered ");
          $ftch->bindParam(':ip',$ip);
          $ftch->bindParam(':ordered',$ordered);
          $ftch->setFetchMode(PDO::FETCH_OBJ);
          $ftch->execute();

          for($i = 1; $rtch = $ftch->fetch(); $i++){  ?>
            <tr>
              <th><?php echo $i; ?></th>
              <td><?php echo $rtch->p_name; ?></td>
              <td><?php echo $rtch->quantity; ?>  </td>
              <td><a href="cart.php?remove=<?php echo $rtch->p_code; ?>">Remove</a></td>
            </tr>
        <?php  } ?>
      </tbody>

    </table>
    <form action="order.php" method="post">
      <button type="submit" name="order" class="btn btn-outline-info btn-sm mb-4 form-control" <?php if(empty($_SESSION['l_email'])){echo ' disabled="disabled" ';} ?>><?php if(empty($_SESSION['l_email'])){echo 'Please Login to Order';}else{echo 'Order';} ?></button>
    </form>
  <?php }elseif( (@$rck->id == 0) OR (@$rck->ordered == '1')){  ?>
    <div class="mt-4 mb-4 text-center">
      <h1>Oops : </h1><br/>
      <h3>Your Cart is empty!</h3><br/>
      <h5>Go Ahead and Shop : <a href='products.php'>Click Here</a></h5>
    </div>
  <?php } ?>
</div>
<?php require_once('footer.php'); ?>


<?php
if(isset($_GET['remove'])){
  $remove = test_input($_GET['remove']);
  $ip = getUserIP();
  $rm = $conn->prepare('DELETE FROM cart WHERE p_code=:code AND ip_add=:ip');
  $rm->bindParam(':code',$remove);
  $rm->bindParam(':ip',$ip);
  if($rm->execute()){

    echo '<script>
            window.location.href="cart.php?msg=Removed";
          </script>
    ';
  }
}
?>
