<?php

$page = "Register" ; require_once('header.php');

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
<?php if((empty($_GET)) || (strpos($_SERVER["REQUEST_URI"], "?msg")!==false )){ ?>
  <?php if (!isset($_SESSION['l_email'])): ?>
    <h3 class="mt-4 mb-4 text-center">Login Here</h3>
    <form action="" method="post">
      <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" name="email" placeholder="Enter Email">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="pass" placeholder="Enter Password">
      </div>
      <div class="form-group">
        <input type="submit" class="form-control btn btn-outline-info" name="login" value="Login">
      </div>
    </form>
    <p>Not Registered yet? <a href='register.php?sup'>Register Now</a></p>
  <?php
    elseif(isset($_SESSION['l_email'])):
      $ftch = $conn->prepare('SELECT * FROM customers WHERE email=:email');
      $ftch->bindParam(':email',$_SESSION['l_email']);
      $ftch->setFetchMode(PDO::FETCH_OBJ);
      $ftch->execute();
      $rtch = $ftch->fetch();
  ?>
    <div class="text-center mt-4 mb-4">
      <div class="background-img mb-4" style="background-image:url(assets/img/customers/<?php echo $rtch->img; ?>)"></div>
      <h3 class="mb-4"><?php echo $rtch->name; ?></h3>
      <p><a class="btn btn-outline-info" href="register.php?edtpro">Edit Profile</a> &nbsp;
        <form action="" method="post">
          <button type="submit" class="btn btn-outline-danger" name="logout">&nbsp;&nbsp; Logout &nbsp;&nbsp;</button>
        </form>
      </p>
    </div>
  <?php endif; ?>
<?php } ?>

<?php if (isset($_GET['sup'])): ?>
  <h3 class="text-center mt-4 mb-4">Signup Here</h3>
  <form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Name</label>
      <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" placeholder="Email" required>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col">
          <label>Password</label>
          <input type="password" class="form-control" name="pass" placeholder="Password" min="8" required>
        </div>
        <div class="col">
          <label>Confirm Password</label>
          <input type="password" class="form-control" name="cnfrm" placeholder="Confirm Password" min="8" required>
        </div>
      </div>
    </div>
    <div class="row">
       <div class="col-md-6 mb-3">
         <label for="validationCustom03">City</label>
         <input type="text" class="form-control" name='country' placeholder="Country" required>
       </div>
       <div class="col-md-3 mb-3">
         <label for="validationCustom04">State</label>
         <input type="text" class="form-control" name='city' placeholder="City" required>
       </div>
       <div class="col-md-3 mb-3">
         <label for="validationCustom05">Zip</label>
         <input type="text" class="form-control" name='zip' placeholder="Zip" required>
       </div>
     </div>
    <div class="form-group">
      <label>Address 1</label>
      <input type="text" class="form-control"  name="address_1" placeholder="Address 1"  required>
    </div>
    <div class="form-group">
      <label>Address 2 (Optional)</label>
      <input type="text" class="form-control"  name="address_2" placeholder="Address 2" >
    </div>
    <div class="form-group">
      <label>Phone</label>
      <input type="tel" class="form-control"  name="phone" placeholder="Phone Number (i.e +92 345 6789012)"  required>
    </div>
    <div class="form-group">
      <label>Image</label><br>
      <label class="custom-file">
        <input type="file" name='img' class="custom-file-input">
        <span class="custom-file-control">Select Image (Optional)</span>
      </label>
    </div>
    <div class="form-group">
      <input type="submit" name="signup" class="form-control btn btn-outline-info" value="Signup" required>
      <?php
        if(isset($_POST['signup'])){

          $e_cnfrm = '0';
          // $f_name = test_input($_POST['f_name']);
          $name = test_input($_POST['name']);
          // $name = $f_name . ' ' . $l_name;
          $email = test_input($_POST['email']);
          $pass = test_input($_POST['pass']) ;
          $cnfrm = test_input($_POST['cnfrm']);
          $phone = test_input($_POST['phone']);
          $address_1 = test_input($_POST['address_1']);
          $address_2 = test_input($_POST['address_2']);
          $country = test_input($_POST['country']);
          $city = test_input($_POST['city']);
          $zip = test_input($_POST['zip']);
          $img = test_input($_FILES['img']['name']);
          if(!empty($img)){
            $allowed =  array('gif','png' ,'jpg');
            $ext = pathinfo($img, PATHINFO_EXTENSION);
            if(!in_array($ext,$allowed) ) {
              echo '<script>window.location.href="register.php?sup&&msg=Invalid%20image%20fromat"</script>';
              return;
            }
          }
          if(empty($img)){
            $img = 'assets/img/customers/about_main.png';
          }
          $e_code = $_SESSION['r_code'] = rand();
          //Fetching from db
          $chkr = $conn->prepare("SELECT * FROM customers");

          $chkr->setFetchMode(PDO::FETCH_OBJ);
          $chkr->execute();
          while($rchk = $chkr->fetch()){
            if($rchk->email == $email){echo '<script>window.location.href="register.php?sup&&msg=User%20Already%20Registered";</script>'; return;}
          }
          //Validating the form
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<script>window.location.href="register.php?sup&&msg=Invalid%20Email";</script>';
            return;
          }
          if(strlen($pass) < '8'){
            echo '<script>window.location.href="register.php?sup&&msg=Password%20must%20be%208%20character%20long";</script>';
            return;
          }
          if (!preg_match("#[0-9]+#", $pass)) {
            echo '<script>window.location.href="register.php?sup&&msg=Password%20must%20contain%20numbers%20and%20characters";</script>';
            return;
          }
          if (!preg_match("#[a-zA-Z]+#", $pass)) {
            echo '<script>window.location.href="register.php?sup&&msg=Password%20must%20contain%20numbers%20and%20characters";</script>';
            return;
          }
          //hashing the password
          $pwd = password_hash($pass, PASSWORD_DEFAULT,['cost' => 12]);
          if(!password_verify($cnfrm,$pwd)){
            echo '<script>window.location.href="'.$_SERVER['HTTP_REFERER'].'&&msg=Password%20don%27t%20match";</script>';
            return;
          }
          //Inserting into db
          $register = $conn->prepare("INSERT INTO customers(name,email,pass,address_1,address_2,phone,email_cnfrm,cnfrm_code,country,city,zip,img) VALUES(:name,:email,:pass,:address_1,:address_2,:phone,:e_cnfrm,:cnfrm_code,:country,:city,:zip,:img)");
          $register->bindParam(':name',$name);
          $register->bindParam(':email',$email);
          $register->bindParam(':pass',$pwd);
          $register->bindParam(':address_1',$address_1);
          $register->bindParam(':address_2',$address_2);
          $register->bindParam(':phone',$phone);
          $register->bindParam(':e_cnfrm',$e_cnfrm);
          $register->bindParam(':cnfrm_code',$e_code);
          $register->bindParam(':country',$country);
          $register->bindParam(':city',$city);
          $register->bindParam(':zip',$zip);
          $register->bindParam(':img',$img);
          if($register->execute()){

            $to = $_SESSION['r_email'] = $email;
            $subject = "Careford Corp Registration Varification";
            $message = '<h3>Thanks for Registering! </h3>
            <p>Please verify your account by clicking the link below </p>
            <a href="https://carefordcorp.com/register.php?usr='.$email.'&&code='.$e_code = $_SESSION['r_code'].'">Click Here</a>';
            $header = "From:no-reply@carefordcorp.com \r\n";
            $header = "Cc:no-reply@carefordcorp.com \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            if(mail ($to,$subject,$message,$header)){
              echo '<script>window.location.href="register.php?snd&&msg=Signed%20Up%20Successfully";</script>';
            }
            move_uploaded_file($_FILES['img']['tmp_name'] , 'assets/img/customers/' . $img);
          }
        }
      ?>
    </div>
  </form>
  <p>Already Registered? <a href='register.php'>Login</a></p>
<?php endif; ?>

<!-- if Signed up -->
<?php if (isset($_GET['snd'])): ?>
  <?php if (!empty($_SESSION['r_email'])): ?>
    <div class="mt-4 mb-4 text-center">
      <h3>Registeration Complete</h3>
      <p>We've send a confirmation link to email you provided.Please check your inbox and verify your account</p>
      <p>Having Problems with email? Please check the spam / junk</p>
    </div>
  <?php endif; ?>
<?php endif; ?>

<!-- if clicked on confirmation link -->
<?php
if(isset($_GET['usr'])){
  $usr = test_input($_GET['usr']);
  $code = test_input($_GET['code']);
  $ftch = $conn->prepare("SELECT * FROM customers WHERE email=:email");
  $ftch->bindParam(':email',$usr);
  $ftch->setFetchMode(PDO::FETCH_OBJ);
  $ftch->execute();
  $rtch = $ftch->fetch();
  if(($rtch->email !== $usr) || ($rtch->cnfrm_code !== $code)){
    echo "<script>window.location.href='https://carefordcorp.com/?msg=Sorry%20something%20went%20wrong'</script>";
    return;
  }

  $email_cnfrm = '1';
  $cnfrm_code = '0';
  $upd = $conn->prepare("UPDATE customers SET email_cnfrm=:email_cnfrm , cnfrm_code=:cnfrm_code WHERE email=:email");
  $upd->bindParam(':email_cnfrm',$email_cnfrm);
  $upd->bindParam(':cnfrm_code',$cnfrm_code);
  $upd->bindParam(':email',$usr);
  if($upd->execute()){
    echo "<script>window.location.href='https://carefordcorp.com/?msg=Email%20Confirmed'</script>";
  }

}
?>
<!-- if isset forgot -->
<?php if(isset($_GET['forgot'])){ ?>
  <h3 class="text-center mt-4 mb-4">Password Recovery</h3>
  <form action="" method="post">
    <div class="form-group">
      <input type="email" class="form-control" name="email" placeholder="Email">
    </div>
    <div class="form-group">
      <input type="submit" class="form-control btn btn-info extended" name="recover" value="Send Confirmation Email">
    </div>
    <?php
      if(isset($_POST['recover'])){
        $email  = test_input($_POST['email']);
        $ftch = $conn->prepare('SELECT * FROM customers');
        $ftch->setFetchMode(PDO::FETCH_OBJ);
        $ftch->execute();
        while($rtch = $ftch->fetch()){
          if($rtch->email !== $email){
            echo "<script>window.location.href='register.php?forgot&&msg=You%27re%20not%20Registered'</script>";
            return;
          }
        }
        $cnfrm = "0";
        $code = rand();
        $rcvr = $conn->prepare('UPDATE customers SET email_cnfrm=:cnfrm , cnfrm_code=:code');
        $rcvr->bindParam(':cnfrm',$cnfrm);
        $rcvr->bindParam(':code',$code);
        if($rcvr->execute()){
          $to = $_SESSION['r_email'] = $email;
          $subject = "Careford Corp Email Recovery";
          $message = '
          <p>Please Recover your account by clicking the link below </p>
          <a href="https://carefordcorp.com/register.php?chng&&user='.$email.'&&code='.$code.'">Click Here</a>';
          $header = "From:no-reply@carefordcorp.com \r\n";
          $header = "Cc:no-reply@carefordcorp.com \r\n";
          $header .= "MIME-Version: 1.0\r\n";
          $header .= "Content-type: text/html\r\n";
          if(mail ($to,$subject,$message,$header)){
            echo '<script>window.location.href="https://carefordcorp.com/?msg=A%20confirmation%20link%20is%20sent%20to%20your%20email";</script>';
          }
        }
      }
    ?>
  </form>
<?php } ?>
<!-- if user applies for password change -->
<?php
if(isset($_GET['chng'])){
  if((!empty($_GET['user'])) AND (!empty($_GET['code']))){
    $user = test_input($_GET['user']);
    $code = test_input($_GET['code']);
    $ftch = $conn->prepare('SELECT * FROM customers');
    $ftch->setFetchMode(PDO::FETCH_OBJ);
    $ftch->execute();
    while ($rtch = $ftch->fetch()) {
      if(($rtch->email !== $user) || ($rtch->cnfrm_code !== $code)){
        echo "<script>window.location.href='register.php?msg=Sorry%20something%20went%20wrong'</script>";
        return;
      }
    } ?>
    <h3 class="text-center mt-4 mb-4">Change Password</h3>
    <form action="" method="post">
      <div class="form-group">
        <input type="password" class="form-control" name="pwd" placeholder="Password">
      </div>
      <div class="form-group">
        <input type="password" class="form-control" name="cnfrm_pwd" placeholder="Confirm Password">
      </div>
      <div class="form-group">
        <input type="submit" class="form-control btn btn-info extended" name="change" value="Change Password">
        <?php
          if(isset($_POST['change'])){
            $pwd = test_input($_POST['pwd']);
            $cnfrm = test_input($_POST['cnfrm_pwd']);
            if((strlen($pwd) < '8') || (strlen($cnfrm) < '8')){
              echo '<script>window.location.href="'.$_SERVER['HTTP_REFERER'].'&&msg=Password%20must%20be%208%20character%20long";</script>';
              return;
            }
            if (!preg_match("#[0-9]+#", $pwd)) {
              echo '<script>window.location.href="'.$_SERVER['HTTP_REFERER'].'&&msg=Password%20must%20contain%20numbers%20and%20characters";</script>';
              return;
            }
            if (!preg_match("#[a-zA-Z]+#", $pwd)) {
              echo '<script>window.location.href="'.$_SERVER['HTTP_REFERER'].'&&msg=Password%20must%20contain%20numbers%20and%20characters";</script>';
              return;
            }
            $pass =  password_hash($pwd, PASSWORD_DEFAULT,['cost' => 12]);
            if(!password_verify($cnfrm,$pass)){
              echo '<script>window.location.href="'.$_SERVER['HTTP_REFERER'].'&&msg=Password%20don%27t%20match";</script>';
              return;
            }
            $email_cnfrm = '1';
            $cnfrm_code = '0';
            $upd = $conn->prepare('UPDATE customers SET pass=:pass, email_cnfrm=:email_cnfrm , cnfrm_code=:cnfrm_code WHERE email=:email');
            $upd->bindParam(':pass',$pass);
            $upd->bindParam(':email_cnfrm',$email_cnfrm);
            $upd->bindParam(':cnfrm_code',$cnfrm_code);
            $upd->bindParam(':email',$user);
            if($upd->execute()){
              echo "<script>window.location.href='https://carefordcorp.com/?msg=Password%20Changed%20Successfully'</script>";
            }
          }
        ?>
      </div>
    </form>
<?php } } ?>
<!-- if isset edit Profile -->
<?php
if(!isset($_SESSION['l_email'])){
  if(isset($_GET['edtpro'])){
  echo '<script>window.location.href="https://carefordcorp.com/?msg=Please%20Login%20to%20edit%20your%20profile"</script>';
 }
}if(isset($_SESSION['l_email'])){
  if(isset($_GET['edtpro'])){
    $ftch = $conn->prepare('SELECT * FROM customers WHERE email=:email');
    $ftch->bindParam(':email',$_SESSION['l_email']);
    $ftch->setFetchMode(PDO::FETCH_OBJ);
    $ftch->execute();
    $rtch = $ftch->fetch();
?>

    <h3 class="mt-4 mb-4 text-center">Edit Profile</h3>
    <form class="mb-4" action="" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label>Profile Picture</label><br>
        <label class="custom-file">
          <input type="file" name="img" class="custom-file-input extended">
          <span class="custom-file-control">
            <span class="hidden-sm">Selected</span> Image : <div class="background-img" style="background-image:url(assets/img/customers/<?php echo $rtch->img; ?>); height:39px; width:39px; margin-top:-33px;"></div>
          </span>
        </label>
      </div>
      <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" name="name" value="<?php echo $rtch->name; ?>">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" name="email" value="<?php echo $rtch->email; ?>">
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col">
            <label>Password</label>
            <input type="password" class="form-control" name="password" placeholder="New Password">
          </div>
          <div class="col">
            <label>Confirm Password</label>
            <input type="password" class="form-control" name="cnfrm" placeholder="Confirm Password">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label>Address 1</label>
        <input type="text" class="form-control" name="add_1" value="<?php echo $rtch->address_1; ?>">
      </div>
      <div class="form-group">
        <label>Address 2</label>
        <input type="text" class="form-control" name="add_2" value="<?php echo $rtch->address_2; ?>">
      </div>
      <div class="form-group">
        <label>Phone</label>
        <input type="tel" class="form-control" name='phone' value="<?php echo $rtch->phone; ?>">
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label>Country</label>
            <input type="text" class="form-control" name='country' value="<?php echo $rtch->country; ?>">
          </div>
          <div class="col-md-3 mb-3">
            <label>City</label>
            <input type="text" class="form-control" name="city" value="<?php echo $rtch->city; ?>">
          </div>
          <div class="col-md-3 mb-3">
            <label>Zip</label>
            <input type="text" class="form-control" name="zip" value="<?php echo $rtch->zip; ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Old Password (required)</label>
          <input type="password" class="form-control" name="old" placeholder="Old Password" required>
        </div>
      </div>
      <div class="form-group">
        <input type="submit" class="form-control btn btn-outline-info extended" name="update" value="Update">
      </div>
    </form>

<?php
    if(isset($_POST['update'])){
      if(!password_verify($_POST['old'],$rtch->pass)){
        echo "<script>window.location.href='https://carefordcorp.com/?msg=Your%20entered%20incorrect%20password%20so%20you%20are%20logged%20out'</script>";
        session_unset();
        session_destroy();
        return;
      }
      $name = $_POST['name'];
      $email = $_POST['email'];
      $img = $_FILES['img']['name'];
      $pass = $_POST['password'];
      $cnfrm = $_POST['cnfrm'];
      $add_1 = $_POST['add_1'];
      $add_2 = $_POST['add_2'];
      $phone = $_POST['phone'];
      $country = $_POST['country'];
      $city = $_POST['city'];
      $zip = $_POST['zip'];
      if(!empty($pass)){
        //Validating the form
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          echo '<script>window.location.href="register.php?edtpro&&msg=Invalid%20Email";</script>';
          return;
        }
        if(strlen($pass) < '8'){
          echo '<script>window.location.href="register.php?edtpro&&msg=New%20Password%20must%20be%208%20character%20long";</script>';
          return;
        }
        if (!preg_match("#[0-9]+#", $pass)) {
          echo '<script>window.location.href="register.php?edtpro&&msg=New%20Password%20must%20contain%20numbers%20and%20characters";</script>';
          return;
        }
        if (!preg_match("#[a-zA-Z]+#", $pass)) {
          echo '<script>window.location.href="register.php?edtpro&&msg=New%20Password%20must%20contain%20numbers%20and%20characters";</script>';
          return;
        }
        //hashing the password
        $pwd = password_hash($pass, PASSWORD_DEFAULT,['cost' => 12]);
        if(!password_verify($cnfrm,$pwd)){
          echo '<script>window.location.href="'.$_SERVER['HTTP_REFERER'].'&&msg=Password%20don%27t%20match";</script>';
          return;
        }
      }if(empty($pass)){
        $pass = $pwd = $rtch->pass;
      }if(!empty($img)){
        $allowed =  array('gif','png' ,'jpg');
        $ext = pathinfo($img, PATHINFO_EXTENSION);
        if(!in_array($ext,$allowed) ) {
          echo '<script>window.location.href="register.php?edtpro&&msg=Invalid%20image%20format"</script>';
          return;
        }
      }
      if(empty($img)){
        $img = $rtch->img;
      }
      if($email !== $rtch->email){
        $email_cnfrm = '0';
        $cnfrm_code = rand();
        $upd = $conn->prepare('UPDATE customers SET name=:name , email=:email , pass=:pass , address_1=:add_1 , address_2=:add_2 , phone=:phone , img=:img , email_cnfrm=:email_cnfrm , cnfrm_code=:cnfrm_code , country=:country , city=:city , zip=:zip');
        $upd->bindParam(':name',$name);
        $upd->bindParam(':email',$email);
        $upd->bindParam(':pass',$pwd);
        $upd->bindParam(':add_1',$add_1);
        $upd->bindParam(':add_2',$add_2);
        $upd->bindParam(':phone',$phone);
        $upd->bindParam(':email_cnfrm',$email_cnfrm);
        $upd->bindParam(':cnfrm_code',$cnfrm_code);
        $upd->bindParam(':country',$country);
        $upd->bindParam(':city',$city);
        $upd->bindParam(':zip',$zip);
        $upd->bindParam(':img',$img);
        if($upd->execute()){
          $to = $_SESSION['r_email'] = $email;
          $subject = "Careford Corp Email Varification";
          $message = '<h3>Your email is now changed!</h3>
          <p>Please verify your email by clicking the link below </p>
          <a href="https://carefordcorp.com/register.php?usr='.$email.'&&code='.$_SESSION['r_code'] = $cnfrm_code.'">Click Here</a>';
          $header = "From:no-reply@carefordcorp.com \r\n";
          $header = "Cc:no-reply@carefordcorp.com \r\n";
          $header .= "MIME-Version: 1.0\r\n";
          $header .= "Content-type: text/html\r\n";
          if(mail ($to,$subject,$message,$header)){
            echo '<script>window.location.href="https://carefordcorp.com/?msg=Profile%20Updated!%20Confirm%20your%20email";</script>';
            session_unset();
            session_destroy();
          }
          move_uploaded_file($_FILES['img']['tmp_name'] , 'assets/img/customers/' . $img);
        }
      }else{
        $upd = $conn->prepare('UPDATE customers SET name=:name , email=:email , pass=:pass , address_1=:add_1 , address_2=:add_2 , phone=:phone , img=:img , country=:country , city=:city , zip=:zip');
        $upd->bindParam(':name',$name);
        $upd->bindParam(':email',$email);
        $upd->bindParam(':pass',$pwd);
        $upd->bindParam(':add_1',$add_1);
        $upd->bindParam(':add_2',$add_2);
        $upd->bindParam(':phone',$phone);
        $upd->bindParam(':country',$country);
        $upd->bindParam(':city',$city);
        $upd->bindParam(':zip',$zip);
        $upd->bindParam(':img',$img);
        if($upd->execute()){
          echo "<script>window.location.href='register.php?edtpro&&msg=Profile%20Updated!'</script>";
          move_uploaded_file($_FILES['img']['tmp_name'] , 'assets/img/customers/' . $img);
        }
      }
    }
  }
}
?>







</div>
<?php require_once('footer.php'); ?>
<?php
  if(isset($_POST['login'])){
    // echo "string";
    $email = test_input($_POST['email']);
    // $pass = password_hash(test_input($_POST['pass']), PASSWORD_DEFAULT,['cost' => 12]);
    $pass = test_input($_POST['pass']);
    $email_cnfrm = '1';
    $login = $conn->prepare("SELECT * FROM customers /* WHERE email=:email AND email_cnfrm=:email_cnfrm */ ");
    // $login->bindParam(':email',$email);
    // $login->bindParam(':email_cnfrm',$email_cnfrm);
    $login->setFetchMode(PDO::FETCH_OBJ);
    $login->execute();
    while($lchk = $login->fetch()){
      if($lchk->email === $email){
        //Check if verified or not
        if($lchk->email_cnfrm !== $email_cnfrm){
          if (strpos($_SERVER['HTTP_REFERER'], "?")!==false){
              echo '<script type="text/javascript">
                      window.location.href="'.$_SERVER['HTTP_REFERER'].'&&msg=Please%20verify%20your%20email%20to%20login";
                    </script>';
                    return;
          }else{
              echo '<script type="text/javascript">
                      window.location.href="'.$_SERVER['HTTP_REFERER'].'?msg=Please%20verify%20your%20email%20to%20login";
                    </script>';
                    return;
          }
          return;
        }
        // check if password is correct
        if(password_verify($pass , $lchk->pass)){
          $_SESSION['l_email'] = $lchk->email;
          if (strpos($_SERVER['HTTP_REFERER'], "?")!==false){
              echo '<script type="text/javascript">
                      window.location.href="'.$_SERVER['HTTP_REFERER'].'&&msg=Logged%20In";
                    </script>';
          }else{
              echo '<script type="text/javascript">
                      window.location.href="'.$_SERVER['HTTP_REFERER'].'?msg=Logged%20In";
                    </script>';
          }
          //error info
        }else{
          if (strpos($_SERVER['HTTP_REFERER'], "?")!==false){
              echo '<script type="text/javascript">
                      window.location.href="'.$_SERVER['HTTP_REFERER'].'&&msg=Incorrect%20Password";
                    </script>';
          }else{
              echo '<script type="text/javascript">
                      window.location.href="'.$_SERVER['HTTP_REFERER'].'?msg=Incorrect%20Password";
                    </script>';
          }
        }
      }else{
        // error info
        if (strpos($_SERVER['HTTP_REFERER'], "?")!==false){
            echo '<script type="text/javascript">
                    window.location.href="'.$_SERVER['HTTP_REFERER'].'&&msg=Incorrect%20Email";
                  </script>';
        }else{
            echo '<script type="text/javascript">
                    window.location.href="'.$_SERVER['HTTP_REFERER'].'?msg=Incorrect%20Email";
                  </script>';
        }
      }
    }
  }
?>

<!-- if clicked resend -->
<?php
  if(!empty($_SESSION['r_email'])){
    if(isset($_GET['resnd'])){
      $subject = "Careford Corp Registration Varification";
      $message = '<h3>Thanks for Registering! </h3>
      <p>Please verify your account by clicking the link below </p>
      <a href="https://carefordcorp.com/register.php?usr='.$_SESSION['r_email'].'&&code='.$_SESSION['r_code'].'">Click Here</a>';
      $header = "From:no-reply@carefordcorp.com \r\n";
      $header = "Cc:no-reply@carefordcorp.com \r\n";
      $header .= "MIME-Version: 1.0\r\n";
      $header .= "Content-type: text/html\r\n";
      if(mail($_SESSION['r_email'],$subject,$message,$header)){
        echo '<script>window.location.href="register.php?snd&&msg=Confirmation%20email%20was%20resent%20Successfully";</script>';
      }
    }
  }
?>
<?php
if (isset($_POST['logout']) ){
  session_unset();
  $out = session_destroy();
  if($out){
    echo '<script>window.location.href="https://carefordcorp.com/?msg=You%20are%20logged%20out%20successfully"</script>';
  }
}
?>
