<?php
  session_start();
   $page="Super User";
   require_once('header.php');
   //Token to prevent CSRF
  //  require_once('inc/class/token.php');
?>


<div class="container">
  <!--Login Form-->
  <div class="row">
    <div class="col-lg-12 mb-4">
      <br/><br/><br/>
      <?php  if (!empty($_SESSION['email'])) { ?>

        <?php
          if (!empty($_GET['msg'])) {    $msg = $_GET['msg'];     ?>
          <div class="alert text-center alert-success alert-dismissible fade show" role="alert">
            <?php echo test_input($msg); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php  } ?>

          <h1 class="text-center">General Site Settings</h1>
          <?php
            try {
              $gen = $conn->prepare("SELECT * FROM general");
              $gen->setFetchMode(PDO::FETCH_OBJ);
              $gen->execute();
              $ren = $gen->fetch();
              if(isset($_POST['sc'])){
                $brand_name = test_input($_POST['brand_name']);
                $site_img = test_input($_FILES['site_image']['name']);
                $wapp = test_input($_POST['whatsapp']);
                $contact_no = test_input($_POST['contact_no']);
                $fb = test_input($_POST['fb']);
                $twitter = test_input($_POST['twitter']);
                $gplus =test_input($_POST['gplus']);
                $insta =test_input($_POST['insta']);
                if(empty($site_img)){
                  $site_img = $ren->brand_img;
                }

                $update = $conn->prepare("UPDATE general SET brand_name=:st , brand_img=:sl /*, wapp=:sw , phone=:sp */, fb=:fb , twitter=:twitter , gplus=:gplus , insta=:insta");
                $update->bindParam(':st',$brand_name);
                $update->bindParam(':sl',$site_img);
                // $update->bindParam(':sw',$wapp);
                // $update->bindParam(':sp',$contact_no);
                $update->bindParam(':fb',$fb);
                $update->bindParam(':twitter',$twitter);
                $update->bindParam(':gplus',$gplus);
                $update->bindParam(':insta',$insta);
                $uex = $update->execute();
                if($uex){
                  if(!empty($site_img)){
                    move_uploaded_file($_FILES['site_img']['tmp_name'] , "../assets/img/".$site_img );
                  }
                  header('Location: index.php?msg=Success');
                }
              }
            } catch (PDOException $e) {
              echo "Oops : ".$e->getMessage();
            }

          ?>
          <form  action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label for="brand_name">Site Title</label>
              <input type="text" class="form-control" id="brand_name" name="brand_name"  value="<?php echo $ren->brand_name; ?>" >
            </div>
            <div class="form-group">
              <label for="site_image">Brand Image</label><br/>
              <label class="custom-file">
                <input type="file" name="site_image" class="custom-file-input extended" >
                <span class="custom-file-control">Site Logo : <img src="../assets/img/<?php echo $ren->brand_img; ?>"  style="height:30px"/></span>
              </label><br/>
            </div>
            <div class="form-group">
              <label for="fb">Facebook URL</label>
              <input type="text" class="form-control" id="fb" name="fb"  value="<?php echo $ren->fb; ?>">
            </div>
            <div class="form-group">
              <label for="twitter">Twitter URL</label>
              <input type="text" class="form-control" id="twitter" name="twitter" value="<?php echo $ren->twitter; ?>">
            </div>
            <div class="form-group">
              <label for="gplus">Google+ URL</label>
              <input type="text" class="form-control" id="gplus" name="gplus"  value="<?php echo $ren->gplus; ?>">
            </div>
            <div class="form-group">
              <label for="insta">Instagram URL</label>
              <input type="text" class="form-control" id="insta" name="insta" value="<?php echo $ren->insta; ?>">
            </div>
            <input type="submit" class="btn btn-info extended" name='sc' value="Save Changes">
          </form>

      <?php    }else{  ?>

          <?php
            if (!empty($_GET['msg'])) {    $msg = $_GET['msg'];     ?>
            <div class="alert text-center alert-warning alert-dismissible fade show" role="alert">
              <?php echo test_input($msg); ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php  } ?>

          <h3>Login Here</h3>
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
              <label for="l_email">Email address</label>
              <input type="email" class="form-control" id="l_email" name="email" placeholder="Enter email" required>
            </div>
            <div class="form-group">
              <label for="l_password">Password</label>
              <input type="password" class="form-control" id="l_password" name="pwd" placeholder="Password" required>
            </div>
            <!-- <input type="hidden" class="form-control" name="token" value="" /> -->

            <input type="submit" class="btn btn-success extended" name="login" value="Login">
          </form>
        <?php } ?>
        <br/><br/><br/>
    </div>
  </div>
</div>
<?php require_once('footer.php'); ?>
<?php
try {
  //Checking the data

  if($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    //$tkn = $_POST['token'];
  // /  $_SESSION['token']  =  bind2hex(random_bytes(32));
    $mde = md5($email);
    $shae = sha1($mde);
    $crypte = crypt($shae,'sl');

    $md = md5($pwd);
    $sha = sha1($md);
    $crypt = crypt($sha,'sa');
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->setFetchMode(PDO:: FETCH_OBJ);
    $stmt->execute();
    $row = $stmt->fetch();
    //Fetch The Data
    $email_db =  $row->email;
    $pass_db  =  $row->pass;

  if (($email_db == $crypte) AND ($pass_db == $crypt)/* AND ($tkn == $token)*/) {
          unset($_SESSION['token']);
          session_start();
          $_SESSION['email'] = $email;
          header("Location: https://carefordcorp.com/su?msg=You%20are%20logged%20in%20successfully");

    }else{
      header("Location: https://carefordcorp.com/su?msg=Your%20entered%20detailes%20are%20Incorrect");
      //session_destroy();
    }
  }
} catch (PDOException $e) {
  echo "Oops : " . $e->getMessage();
}
?>
