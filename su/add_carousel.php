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

  <div class="text-center">
    <h3>Add Carousel</h3>
    <form class=" mx-auto" method="post" enctype="multipart/form-data" action="<?php $_SERVER['PHP_SELF'] ?>">
      <div class="form-group mx-sm-3">
        <label for="headline" class="sr-only">Carousel Headline</label>
        <input type="text" class="form-control" id="headline" name='headline' placeholder="Headline...." required>
      </div>
      <div class="form-group mx-sm-3">
        <label for="caption" class="sr-only">Carousel Caption</label>
        <input type="text" class="form-control" id="caption" name='caption' maxlength="100" placeholder="Small Description....." required>
      </div>
      <div class="form-group">
        <label class="custom-file">
          <input type="file" id="file2" name="image" class="custom-file-input" required>
          <span class="custom-file-control">Select Image</span>
        </label><br/>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary form-control" name='sbmt' value="Add">
      </div>
    </form>
  </div>
  <br/><br/>
</div>

<?php

try {
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $headline = test_input($_POST['headline']);
    $caption = test_input($_POST['caption']);
    $file = test_input($_FILES['image']['name']);
    $allowed =  array('gif','png' ,'jpg');
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    if(!in_array($ext,$allowed) ) {
      echo '<script>alert("You must select an image")</script>';
      return;
    }
    $ftch = $conn->prepare("SELECT * FROM carousel");
    $ftch->bindParam(':h1',$headline);
    $ftch->bindParam(':im',$file);
    $ftch->setFetchMode(PDO::FETCH_OBJ);
    $ftch->execute();
    while ($rtch = $ftch->fetch()) {
      $hdl = $rtch->healine;
      $imgs = $rtch->img;
      if ((($hdl == $headline) || ($imgs == $file)) || (($hdl == $headline) && ($imgs == $file)) ) {
        $hdr =  header('Location: carousel.php?msg=Item%20Already%20Exists');
        if(!$hdr){echo "<script>window.open('carousel.php?msg=Item%20Already%20Exists','_self')</script>"; return;}
        return;
      }
    }


    $stmt = $conn->prepare("INSERT INTO carousel(headline,caption,img) VALUES(:headline,:caption,:img)");
    $stmt->bindParam(':headline',$headline);
    $stmt->bindParam(':caption',$caption);
    $stmt->bindParam(':img',$file);

    if ($stmt->execute()) {
      move_uploaded_file($_FILES['image']['tmp_name'] , "../assets/img/carousel/".$file );
      $sm = header('Location:carousel.php?msg=Item%20Added');
      if(!$sm){echo "<script>window.open('carousel.php?msg=Item%20Added','_self')</script>";}
    }
  }
} catch (PDOException $e) {
  echo "Oops : " . $e->getMessage();
}



require_once('footer.php');
?>
