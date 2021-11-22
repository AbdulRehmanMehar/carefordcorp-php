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

  <!--Add-->
  <?php
    if(isset($_GET['add'])){
      $add = test_input($_GET['add']);
      if($add=="Yes"){?>
        <?php
          try {
            if(isset($_POST['add'])){
              $img = test_input($_FILES['img']['name']);

              $name = test_input($_POST['name']);
              $email = test_input($_POST['email']);
              $position  = test_input($_POST['position']);
              $dsc = test_input($_POST['dsc']);
              //Fetch
              $ftch = $conn->prepare("SELECT * FROM about_team WHERE name=:name");
              $ftch->bindParam(':name',$name);
              $ftch->setFetchMode(PDO::FETCH_OBJ);
              $ftch->execute();
              $rtch = $ftch->fetch();
              if($rtch->name == $name){
                echo "<script>alert('Record Exists')</script>";
                return;
              }
              $insrt = $conn->prepare('INSERT INTO about_team(img,name,email,position,dsc) VALUES(:img,:name,:email,:positon,:dsc)');
              $insrt->bindParam(':img',$img);
              $insrt->bindParam(':name',$name);
              $insrt->bindParam(':email',$email);
              $insrt->bindParam(':positon',$position);
              $insrt->bindParam(':dsc',$dsc);
              $exe = $insrt->execute();
              if($exe){move_uploaded_file($_FILES['img']['tmp_name'],'../assets/img/team/'.$img); header('Location: pages.php?msg=Success');}
            }
          } catch (PDOException $e) {
            echo "Oops : ".$e->getMessage();
          }

        ?>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label class="custom-file">
              <input type="file" id="file2" name="img" class="custom-file-input" required>
              <span class="custom-file-control">Select Image</span>
            </label>
          </div>
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control">
          </div>
          <div class="form-group">
            <label for="positon">Position</label>
            <input type="text" name="position" name='add' class="form-control">
          </div>
          <div class="form-group">
            <label for="dsc">Description</label>
            <textarea name="dsc" rows="8" cols="80" class="form-control" required></textarea>
          </div>
          <input type="submit" name="add" value="Insert" class="btn btn-info extended">
        </form>

  <?php    }  }  ?>
  <?php
    if(!empty($_GET['edt'])){
      if(isset($_GET['edt'])){
        $edt = test_input($_GET['edt']);  ?>

        <?php
          try {
            //Fetch
            $ftch = $conn->prepare("SELECT * FROM about_team WHERE id=:id");
            $ftch->bindParam(':id',$edt);
            $ftch->setFetchMode(PDO::FETCH_OBJ);
            $ftch->execute();
            $rtch = $ftch->fetch();

            if(isset($_POST['update'])){
              $img = test_input($_FILES['img']['name']);
              if(empty($img)){
                $img = $rtch->img;
              }
              $name = test_input($_POST['name']);
              $email =test_input($_POST['email']);
              $position  = test_input($_POST['position']);
              $dsc = test_input($_POST['dsc']);


              $insrt = $conn->prepare('UPDATE about_team SET img=:img,name=:name,email=:email,position=:position,dsc=:dsc WHERE id=:id');
              $insrt->bindParam(':img',$img);
              $insrt->bindParam(':name',$name);
              $insrt->bindParam(':email',$email);
              $insrt->bindParam(':position',$position);
              $insrt->bindParam(':dsc',$dsc);
              $insrt->bindParam(':id',$edt);
              $exe = $insrt->execute();
              if($exe){move_uploaded_file($_FILES['img']['tmp_name'],'../assets/img/team/'.$img); header('Location: pages.php?msg=Success');}
            }
          } catch (PDOException $e) {
            echo "Oops : ".$e->getMessage();
          }

        ?>

        <form action="" method="post" enctype="multipart/form-data">
          <br><img src="/../assets/img/load.gif" class='lazy' data-src='/../assets/img/team/<?php echo $rtch->img; ?>' style="height:200px"><br>
          <div class="form-group">
            <label class="custom-file">
              <input type="file" id="file2" name="img" class="custom-file-input">
              <span class="custom-file-control">Selected Image : <img src="../assets/img/load.gif" class='lazy' data-src='../assets/img/team/<?php echo $rtch->img; ?>' style="height:20px"></span>
            </label>
          </div>
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $rtch->name; ?>">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $rtch->email; ?>">
          </div>
          <div class="form-group">
            <label for="positon">Position</label>
            <input type="text" name="position" name='add' class="form-control" value="<?php echo $rtch->position; ?>">
          </div>
          <div class="form-group">
            <label for="dsc">Description</label>
            <textarea name="dsc" rows="8" cols="80" class="form-control" ><?php echo $rtch->dsc; ?></textarea>
          </div>
          <input type="submit" name="update" value="Update" class="btn btn-info extended">
        </form>
  <?php    }
    }
  ?>

  <!-- Delete -->
  <?php
    if(!empty($_GET['del'])){
      if(isset($_GET['del'])){
        $dlt = test_input($_GET['del']);
        $del = $conn->prepare("DELETE FROM about_team WHERE id=:id");
        $del->bindParam(':id',$dlt);
        $dt = $del->execute();
        if($dt){header('Location: pages.php?msg=Success');}
      }
    }
  ?>


</div>
<br/>
<?php require_once('footer.php'); ?>
