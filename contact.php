<?php

$page="Contact"; require_once('header.php');

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

  <!-- Content Row -->
 <div class="row">
   <!-- Map Column -->
   <div class="col-lg-6 mb-4">
     <!-- Embedded Google Map -->
<iframe width="100%" height="280px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13462.115179925278!2d74.48013782727719!3d32.48529299697476!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391ee9f21993e825%3A0x219b47d2a2c703fc!2sAdalat+Garh%2C+Sialkot%2C+Pakistan!5e0!3m2!1sen!2s!4v1509968393234" allowfullscreen></iframe>   </div>
   <!-- Contact Details Column -->
   <div class="col-lg-6 mb-4 contact-details">
     <h3>Contact Details</h3>
     <?php
      try {
        $cont = $conn->prepare('SELECT * FROM contact');
        $cont->setFetchMode(PDO::FETCH_OBJ);
        $cont->execute();
        $conr = $cont->fetch();
      } catch (PDOException $e) {
        echo "Oops : " . $e->getMessage();
      }

     ?>
     <p>
      <i class="fa fa-map-marker fa-lg"></i> &nbsp; <?php echo $conr->location; ?>
       <!-- <br>Beverly Hills, CA 90210 -->
       <br>
     </p>
     <p>
        <i class="fa fa-phone fa-lg"></i> &nbsp; <a href="tel:<?php echo $conr->phone; ?>"><?php echo  $conr->phone; ?></a>
     </p>
     <p>
       <i class="fa fa-whatsapp fa-lg"></i> &nbsp; <a href="tel:<?php echo  $conr->wapp; ?>"><?php echo  $conr->wapp; ?></a>
     </p>
     <p>
       <i class="fa fa-envelope fa-lg"></i> &nbsp; <a href="mailto:<?php echo  $conr->email; ?>"><?php echo  $conr->email; ?></a>
     </p>
     <ul class="icons list-unstyled">
 			<li> &nbsp; <a href="<?php echo $brand_twitter ?>" class="icon circle fa fa-twitter" target="_blank"><span class="label">Twitter</span></a></li>
 			<li><a href="<?php echo $brand_fb ?>" class="icon circle fa fa-facebook" target="_blank"><span class="label">Facebook</span></a></li>
 			<li><a href="<?php echo $brand_gp ?>" class="icon circle fa fa-google-plus" target="_blank"><span class="label">Google+</span></a></li>
 			<li><a href="<?php echo $brand_insta ?>" class="icon circle fa fa-instagram" target="_blank"><span class="label">Instagram</span></a></li>
 		</ul>
   </div>
 </div>
 <!-- /.row -->

 <!-- Contact Form -->
 <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
 <div class="row">
   <div class="col-md-3"></div>
   <div class="col-md-6 mb-4">
     <h3 class="text-center">Send us a Message</h3>
     <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
       <div class="form-group">
         <label for="name">Name</label>
         <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
       </div>
       <div class="form-group">
         <label for="l_email">Email address</label>
         <input type="email" class="form-control" id="l_email" name="email" placeholder="Enter email" required>
       </div>
       <div class="form-group">
         <label for="msg">Message</label>
        <textarea class="form-control" name="msg" rows="5" cols="80" id="msg" placeholder="Your Message" required></textarea>
       </div>
       <input type="submit" class="btn btn-success extended" name="snd" value="Send">
     </form>
   </div>
   <div class="col-md-3"></div>

 </div>
 <!-- /.row -->

</div>



<?php require_once("footer.php"); ?>
<?php
try {

    // if($_SERVER['REQUEST_METHOD'] == "POST") {
      if(isset ($_POST['snd'])){
        $name_ = $_POST['name'];
        $email_ = $_POST['email'];
        //$token = $_POST['csrfp'];
        $msg_u = $_POST['msg'];
        // if(Token::check($_POST['_token'])){
        //   echo "<script>alert('True')</script>";
        //   return;
        // }
        if (!filter_var($email_, FILTER_VALIDATE_EMAIL)) {
          echo"<script>alert('Please enter a valid email address')</script>";
          return;
        }
        $name = test_input($name_);
        $email = test_input($email_);
        $msg = test_input($msg_u);
        $dte = date("Y-m-d");
        $ftch = $conn->prepare("SELECT * FROM message WHERE email=:ml AND date_snt=:dat");
        $ftch->bindParam(':ml',$email);
        $ftch->bindParam(':dat',$dte);
        $ftch->setFetchMode(PDO::FETCH_OBJ);
        $ftch->execute();
        $chk = $ftch->fetch();
        $chk->email;
        // if ((($chk->email) == $email) AND (($chk->date_snt) == $dte ) ) {
        //   $chdr = header("Location: contact.php?msg=Your%20message%20already%20sent");
        //   if(!$chdr){echo "<script>window.open('contact.php?msg=Your%20message%20already%20sent','_self')</script>"; return;}
        //   return;
        // }



        $qry = "INSERT INTO message (name,email,msg,date_snt) VALUES(:name,:email,:msg,:ndt)";
        $stmt = $conn->prepare($qry);
        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':ndt',$dte);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':msg',$msg);

        $exe = $stmt->execute();
        if($exe){

          $to = "carefordcorp@gmail.com";
          $subject = "Careford Corp";
          $txt = $msg;
          $headers = "From: ".$email;
          mail($to,$subject,$txt,$headers);

          $sdr =  header('Location: contact.php?msg=Your%20message%20is%20sent%20successfully%20.Thanks%20for%20Messaging%20us!');
            if(!$sdr){echo "<script>window.open('contact.php?msg=Your%20message%20is%20sent%20successfully%20.Thanks%20for%20Messaging%20us','_self')</script>"; return;}
        }
      }
    // }
} catch (PDOException $e) {
  echo "Oops : " . $e->getMessage();
}

?>
