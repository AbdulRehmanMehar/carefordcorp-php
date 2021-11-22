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
try {
  if(!empty($_GET['edt'])){
    $edt = test_input($_GET['edt']);
    $ftch = $conn->prepare("SELECT * FROM carousel WHERE id=:id");
    $ftch->bindParam(':id',$edt);
    $ftch->setFetchMode(PDO::FETCH_OBJ);
    $ftch->execute();
    $rtch = $ftch->fetch();
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $headline = test_input($_POST['headline']);
      $caption = test_input($_POST['caption']);
      $file = test_input($_FILES['image']['name']);
      if(empty($file)){
        $file = $rtch->img;
      }

      $stmt = $conn->prepare("UPDATE carousel SET headline=:hdne , caption=:cptn , img=:img WHERE id=:ids");
      $stmt->bindParam(':hdne',$headline);
      $stmt->bindParam(':cptn',$caption);
      $stmt->bindParam(':img',$file);
      $stmt->bindParam(':ids',$edt);
      $exec = $stmt->execute();
      if($exec){
        if(!empty($file)){
          move_uploaded_file($_FILES['image']['tmp_name'] , "../assets/img/carousel/".$file );
        }
        header('Location: carousel.php?msg=Updated%20Successfully');

      }
      else{header('Location: carousel.php?msg=Something%20went%20wrong');}
    }
  }
} catch (PDOException $e) {
  echo "Oops : " . $e->getMessage();
}

?>
  <div class="text-center">
    <h3>Edit Carousel</h3>

    <form class=" mx-auto" method="post" enctype="multipart/form-data" action="<?php $_SERVER['PHP_SELF'] ?>">
      <div class="form-group mx-sm-3">
        <label for="headline" class="sr-only">Carousel Headline</label>
        <input type="text" class="form-control" id="headline" name='headline' value="<?php echo $rtch->headline; ?>">
      </div>
      <div class="form-group mx-sm-3">
        <label for="caption" class="sr-only">Carousel Caption</label>
        <input type="text" class="form-control" id="caption" name='caption' maxlength="100" value="<?php echo $rtch->caption; ?>">
      </div>
      <img src="../assets/img/load.gif" class="lazy" data-src="../assets/img/carousel/<?php echo $rtch->img; ?>" alt="<?php echo $rtch->headline; ?>" style="max-height:300px" />
      <br/><br/>
      <div class="form-group">
        <label class="custom-file">
          <input type="file" id="file2" name="image" class="custom-file-input">
          <span class="custom-file-control">Selected Image : <img src="../assets/img/load.gif" class="lazy" data-src="../assets/img/carousel/<?php echo $rtch->img; ?>" alt="<?php echo $rtch->headline; ?>" style="max-height:30px" /></span>
        </label><br/>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary form-control" name='sbmt' value="Update">
      </div>
    </form>

  </div>
  <br/><br/>
</div>
<?php
  require_once('footer.php'); ?>
