<?php
$page = 'Order';
require_once('header.php');

//Security Check
if(!empty($_GET['usr'])){
  //fetch data
  $usr = test_input($_GET['usr']);
  $id = test_input($_GET['id']);
  $ftch = $conn->prepare('SELECT * FROM cart WHERE buyer_email=:b_email AND order_id=:o_id');
  $ftch->bindParam(':b_email',$usr);
  $ftch->bindParam(':o_id',$id);
  $ftch->setFetchMode(PDO::FETCH_OBJ);
  $ftch->execute();
  $rtch = $ftch->fetch();
  if(($rtch->buyer_email != $usr) AND ($rtch->order_id != $id)){
    echo "<script>window.location.href='https://carefordcorp.com/?msg=Sorry%20Something%20went%20Wrong'</script>";
    return;
  }
  //Update
  $o_cnfrm = '1';
  $n_oid = '0';
  $upd = $conn->prepare("UPDATE cart SET order_cnfrm=:o_cnfrm , order_id=:n_oid WHERE buyer_email=:b_email AND order_id=:o_id");
  $upd->bindParam(':o_cnfrm',$o_cnfrm);
  $upd->bindParam(':n_oid',$n_oid);
  $upd->bindParam(':b_email',$usr);
  $upd->bindParam(':o_id',$id);
  if($upd->execute()){
    $a = 'carefordcorp@gmail.com';
    $b = 'New Order (Careford Corp)';
    $c = "You've new Order.Please visit Super User Area Soon!";
    $d = 'From : no-reply@carefordcorp.com';
    if(mail($a,$b,$c,$d)){
      echo "<script>window.location.href='https://carefordcorp.com/?msg=Order%20Confirmed!%20We%27ll%20Contact%20you%20soon!'</script>";
    }
  }
}elseif(empty($_SESSION['l_email'])){
  echo "<script>window.location.href='https://carefordcorp.com/';</script>";
  return;
}elseif(!isset($_POST['order'])){
  echo "<script>window.location.href='https://carefordcorp.com/';</script>";
  return;
}


//Action
if(isset($_POST['order'])){
  if(!empty($_SESSION['l_email'])){
    $ip = getUserIP();
    $ordered = '1';
    $order_id = rand();
    $upd = $conn->prepare("UPDATE cart SET ordered=:ordered , order_id=:order_id , buyer_name=:b_name , buyer_email=:b_email , buyer_address_1=:b_add1 , buyer_address_2=:b_add2 WHERE ip_add=:ip");
    //getUserInfo
    $getUserInfo = $conn->prepare("SELECT * FROM customers WHERE email=:email");
    $getUserInfo->bindParam(':email',$_SESSION['l_email']);
    $getUserInfo->setFetchMode(PDO::FETCH_OBJ);
    $getUserInfo->execute();
    $gui = $getUserInfo->fetch();
    //Bindparams to upd
    $upd->bindParam(':ordered',$ordered);
    $upd->bindParam(':order_id',$order_id);
    $upd->bindParam(':b_name',$gui->name);
    $upd->bindParam(':b_email',$_SESSION['l_email']);
    $upd->bindParam(':b_add1',$gui->address_1);
    $upd->bindParam(':b_add2',$gui->address_2);
    $upd->bindParam(':ip',$ip);
    if($upd->execute()){
      $to = $_SESSION['l_email'];
      $subject = "Careford Corp Order Confirmation";
      $message = '<h3>Thanks for Order! </h3>
      <p>An order with id '.$order_id.' is placed from your account.Please verify your order by clicking the link below.</p>
      <a href="https://carefordcorp.com/order.php?usr='.$_SESSION['l_email'].'&&id='.$order_id.'">Click Here</a>
      <br/><p>Your Order will be delivered on your Address provided to us.Our Payment Method is <b>Cash on Delivery</b></p>
      <b>Note : </b>Please Reffer Order ID while contacting us!
      ';
      $header = "From:no-reply@carefordcorp.com \r\n";
      $header = "Cc:no-reply@carefordcorp.com \r\n";
      $header .= "MIME-Version: 1.0\r\n";
      $header .= "Content-type: text/html\r\n";
      if(mail ($to,$subject,$message,$header)){
        echo '<script>window.location.href="https://carefordcorp.com/?msg=Order%20Placed!%20Please%20Check%20your%20email%20&%20Confirm%20Order";</script>';
      }
    }
  }
}




//Footer
require_once('footer.php');
?>
