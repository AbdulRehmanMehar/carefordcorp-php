<?php
$page = "Pages";
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
    if (!empty($_GET['msg'])) {    $msg = $_GET['msg'];     ?>
    <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
      <?php echo test_input($msg); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php  } ?>
  <h3 class="text-center"><a href="pages.php?type=contact">Contact</a> | <a href="pages.php?type=about">About</a> | <a href="pages.php?type=team">About Team</a></h3>
  <?php
    if(!empty($_GET['type'])){
      if(isset($_GET['type'])){
        $get = test_input($_GET['type']);
  ?>
    <?php
      if($get == "contact"){
        $ftch = $conn->prepare("SELECT * FROM contact");
        $ftch->setFetchMode(PDO::FETCH_OBJ);
        $ftch->execute();
        $ren = $ftch->fetch();
        if(isset($_POST['c_snd'])){
          $location = test_input($_POST['location']);
          $phone = test_input($_POST['phone']);
          $wapp = test_input($_POST['wapp']);
          $insrt = $conn->prepare("UPDATE contact SET location=:location , phone=:phone , wapp=:wapp");
          $insrt->bindParam(':location',$location);
          $insrt->bindParam(':phone',$phone);
          $insrt->bindParam(':wapp',$wapp);
          if($insrt->execute()){header("Location: pages.php?type=contact&&msg=Success");}
        }
    ?>

      <form action="" method="post">
        <div class="form-group">
          <label for="location">Location Info</label>
          <input type="text" class="form-control" id="location" name="location"  value="<?php echo $ren->location; ?>" >
        </div>
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="text" class="form-control" id="phone" name="phone"  value="<?php echo $ren->phone; ?>" >
        </div>
        <div class="form-group">
          <label for="wapp">Whatsapp Number</label>
          <input type="text" class="form-control" id="wapp" name="wapp"  value="<?php echo $ren->wapp; ?>" >
        </div>
        <div class="form-group">
          <label for="email">Email Info</label>
          <input type="text" class="form-control-plaintext extended" id="email" readonly name="email"  value="<?php echo $ren->email; ?>" >
        </div>
        <input type="submit" class="btn btn-info extended" name="c_snd" value="Save Changes" />
      </form>
    <?php
      }
      if($get == "about"){
        $abt = $conn->prepare("SELECT * from about");
        $abt->setFetchMode(PDO::FETCH_OBJ);
        $abt->execute();
        $aen = $abt->fetch();
        if(isset($_POST['ad_snd'])){
          $dscm = test_input($_POST['about_dsc']);
          $imgm = test_input($_FILES['img']['name']);
          if(empty($imgm)){
            $imgm = $aen->img_main;
          }
          $abr = $conn->prepare("UPDATE about SET desc_main=:dsc,img_main=:imgm");
          $abr->bindParam(':dsc',$dscm);
          $abr->bindParam(':imgm',$imgm);
          $aex = $abr->execute();
          if($aex){move_uploaded_file($_FILES['img']['tmp_name'], '../assets/img/'.$imgm); header('Location: pages.php?type=about&&msg=Success');}
        }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="dsc">Description</label>
        <textarea name="about_dsc" class="form-control" id='ckeditor' rows="8" cols="80"><?php echo $aen->desc_main; ?></textarea>
        <script>CKEDITOR.replace('about_dsc');</script>
      </div>
      <img src='../assets/img/load.gif' data-src='../assets/img/<?php echo $aen->img_main; ?>' class="img-responsive lazy" style="height:200px;" /> <br><br>
      <div class="form-group">
        <label class="custom-file">
          <input type="file" id="file2" name="img" class="custom-file-input">
          <span class="custom-file-control">Selected Image : <img src='../assets/img/load.gif' data-src='../assets/img/<?php echo $aen->img_main; ?>' class="img-responsive lazy" style="height:20px;" /> </span>
        </label><br/>

      </div>
      <input type="submit" class="btn btn-info extended" name="ad_snd" value="Save Changes" />
    </form>

    <?php
    }
    if($get == "team"){
    ?>
    <br/>
      <table class="table table-responsive-sm text-center">
        <thead>
          <tr>
            <td colspan="7"><a href='about_team.php?add=Yes'>Add Team Member</a></td>
          </tr>
          <tr>
            <th colspan="7">View all Members</th>
          </tr>
          <tr>
            <th>#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Email</th>
            <th>Position</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            try {
              $mem = $conn->prepare("SELECT * FROM about_team");
              $mem->setFetchMode(PDO::FETCH_OBJ);
              $mem->execute();
              for($i=1; $mer = $mem->fetch() ; $i++){ ?>
                <tr>
                  <th><?php echo $i; ?></th>
                  <td><img src='../assets/img/load.gif' class="lazy" data-src='../assets/img/team/<?php echo $mer->img; ?>' style='height:30px;' /></td>
                  <td><?php echo $mer->name; ?></td>
                  <td><?php echo $mer->email; ?></td>
                  <td><?php echo $mer->position; ?></td>
                  <td><?php echo $mer->dsc; ?></td>
                  <td><a href='about_team.php?edt=<?php echo $mer->id; ?>'>Edit</a> | <a href='about_team.php?del=<?php echo $mer->id; ?>'>Delete</a></td>
                </tr>
            <?php  }
            } catch (PDOException $e) {
              echo "Oops : ".$e->getMessage();
            }

          ?>
        </tbody>
      </table>


  <?php } } } ?>
</div>




<br/>

<?php require_once('footer.php'); ?>
