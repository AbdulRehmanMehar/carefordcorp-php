<?php
$page = "Products";
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
<?php if(isset($_GET['add'])){ ?>
  <h1 class="text-center">Add Product</h1><br>
  <form  action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Product Image</label><br/>
      <label class="custom-file">

        <input type="file" id="file2" name="img" class="custom-file-input" required>
        <span class="custom-file-control">Select Image</span>
      </label>
    </div>

    <div class="form-group">
      <label>Product Name</label>
      <input type="text" name="name" class="form-control" placeholder="i.e Knife" required>
    </div>
    <!-- <div class="form-group">
      <label>Product Price</label>
      <input type="text" name="price" class="form-control" placeholder="i.e 200 without $ or PKR etc" required>
    </div> -->
    <div class="form-group">
      <label>Product Code</label>
      <input type="text" name="code" class="form-control" placeholder="i.e 1234" required>
    </div>
    <div class="form-group">
      <label>Product Tag (Optional)</label><br/>
      <select class="custom-select " name="tag" required>
        <option selected value="0">Choose...</option>
        <option value="featured">Featured</option>
        <option value="popular">Popular</option>
      </select>
    </div>
    <div class="form-group">
      <label>Product Category</label><br/>
      <select class="custom-select " name='cat' required>
        <option selected value="0">Choose...</option>
        <?php
          $cats = $conn->prepare('SELECT * FROM categories');
          $cats->setFetchMode(PDO::FETCH_OBJ);
          $cats->execute();
          while ($rcat = $cats->fetch()) {
            echo '<option value="'.$rcat->title.'">'.$rcat->title.'</option>';
          }
        ?>
      </select>
    </div>
    <div class="form-group">
      <label>Availability</label><br/>
      <select class="custom-select " name='ava' required>
        <option selected value="0">Choose...</option>
        <option value="avail">Available</option>
        <option value="navail">Not Available</option>
      </select>
    </div>
    <div class="form-group">
      <label>Description</label>
      <textarea name="dsc" rows="8" cols="80" class="form-control"></textarea>
    </div>
    <input type="submit" name="add" value="Add" class="btn btn-info extended">
  </form>
</div>
<?php
try {
  if(isset($_POST['add'])){
    $img = test_input($_FILES['img']['name']);
    // $price = test_input($_POST['price']);
    $name = test_input($_POST['name']);
    $code = test_input($_POST['code']);
    $tag = test_input($_POST['tag']);
    $cat = test_input($_POST['cat']);
    $ava = test_input($_POST['ava']);
    $dsc = test_input($_POST['dsc']);
    $date = date('Y-m-d');
    $allowed =  array('gif','png' ,'jpg');
    $ext = pathinfo($img, PATHINFO_EXTENSION);
    if(!in_array($ext,$allowed) ) {
      echo '<script>alert("You must select an image")</script>';
      return;
    }
    if($cat == "0"){echo "<script>alert('You must select Category')</script>"; return;}
    if($ava == "0"){echo "<script>alert('You must tell if Product is Available or not')</script>"; return;}

    $ftch = $conn->prepare("SELECT * FROM products WHERE code=:code");
    $ftch->bindParam(':code',$code);
    $ftch->setFetchMode(PDO::FETCH_OBJ);
    $ftch->execute();
    $rtch = $ftch->fetch();
    if($rtch->code == $code){
      echo "<script>alert('An item with this code already exists')</script>";
      return;
    }



    $add = $conn->prepare("INSERT INTO products(img,title,code,category,dates,stock,tag,dsc) VALUES(:img,:title,:code,:category,:dates,:stock,:tag,:dsc) ");
    $add->bindParam(':img',$img);
    $add->bindParam(':title',$name);
    $add->bindParam(':code',$code);
    $add->bindParam(':category',$cat);
    $add->bindParam(':dates',$date);
    $add->bindParam(':stock',$ava);
    // $add->bindParam(':price',$price);
    $add->bindParam(':tag',$tag);
    $add->bindParam(':dsc',$dsc);
    if($add->execute()){move_uploaded_file($_FILES['img']['tmp_name'] , '../assets/img/products/'.$img); $hr =  header('Location: products.php?msg=Success');
      if(!$hr){echo '<script>window.open("products.php?msg=Success","_self")</script>';}
    }
  }
} catch (PDOException $e) {
  echo "Oops : ".$e->getMessage();
}

?>
<!--edit-->

<?php
}
if(!empty($_GET['edt'])){
    if(isset($_GET['edt'])){
      $edt = test_input($_GET['edt']);
      $ftch = $conn->prepare("SELECT * FROM products WHERE id=:id");
      $ftch->bindParam(':id',$edt);
      $ftch->setFetchMode(PDO::FETCH_OBJ);
      $ftch->execute();
      $rtch = $ftch->fetch();
?>
  <h1 class="text-center">Edit Product</h1><br>
  <form  action="" method="post" enctype="multipart/form-data">
    <img src='../assets/img/load.gif' class="lazy" data-src='../assets/img/products/<?php echo $rtch->img; ?>' style="height:200px">
    <div class="form-group">
        <label>Product Image</label><br/>
      <label class="custom-file">

        <input type="file" id="file2" name="img" class="custom-file-input">
        <span class="custom-file-control">Selected Image : <img src='../assets/img/load.gif' class="lazy" data-src='../assets/img/products/<?php echo $rtch->img; ?>' style="height:20px"></span>
      </label>
    </div>

    <div class="form-group">
      <label>Product Name</label>
      <input type="text" name="name" class="form-control" placeholder="i.e Knife" value="<?php echo $rtch->title; ?>">
    </div>
    <!-- <div class="form-group">
      <label>Product Price</label>
      <input type="text" name="price" class="form-control" placeholder="i.e 200" value='<?php echo $rtch->price; ?>'>
    </div> -->
    <div class="form-group">
      <label>Product Code</label>
      <input type="text" name="code" class="form-control" placeholder="i.e 1234" value='<?php echo $rtch->code; ?>'>
    </div>
    <div class="form-group">
      <label>Product Tag</label><br/>
      <select class="custom-select " name="tag">
        <option <?php if($rtch->tag == '0'){echo 'selected';} ?> value="0">Choose...</option>
        <option  <?php if($rtch->tag == 'featured'){echo 'selected';} ?> value="featured">Featured</option>
        <option <?php if($rtch->tag == 'popular'){echo 'selected';} ?> value="popular">Popular</option>
      </select>
    </div>
    <div class="form-group">
      <label>Product Category</label><br/>
      <select class="custom-select " name='cat'>
        <option value="0">Choose...</option>
        <?php
          $cats = $conn->prepare('SELECT * FROM categories');
          $cats->setFetchMode(PDO::FETCH_OBJ);
          $cats->execute();
          while ($rcat = $cats->fetch()) {  ?>
            <option <?php if($rtch->category == $rcat->title){echo 'selected';} ?> value="<?php echo $rcat->title; ?>"> <?php echo $rcat->title; ?> </option>

        <?php  }
        ?>
      </select>
    </div>
    <div class="form-group">
      <label>Availability</label><br/>
      <select class="custom-select " name='ava'>
        <option selected value="0">Choose...</option>
        <option <?php if($rtch->stock == 'avail'){echo 'selected';} ?> value="avail">Available</option>
        <option <?php if($rtch->stock == 'navail'){echo 'selected';} ?> value="navail">Not Available</option>
      </select>
    </div>
    <div class="form-group">
      <label>Description</label>
      <textarea name="dsc" rows="8" cols="80" class="form-control"><?php echo $rtch->dsc; ?></textarea>
    </div>
    <input type="submit" name="update" value="Update" class="btn btn-info extended">
  </form>
  </div>
  <?php
  try {
  if(isset($_POST['update'])){
    $img = test_input($_FILES['img']['name']);
    // $price = test_input($_POST['price']);
    $name = test_input($_POST['name']);
    $code =test_input( $_POST['code']);
    $tag = test_input($_POST['tag']);
    $cat = test_input($_POST['cat']);
    $ava =test_input($_POST['ava']);
    $dsc =test_input($_POST['dsc']);
    $date = date('Y-m-d');

    if($cat == "0"){echo "<script>alert('You must select Category')</script>"; return;}
    if($ava == "0"){echo "<script>alert('You must tell if Product is Available or not')</script>"; return;}
    if(empty($img)){
      $img = $rtch->img;
    }

    $add = $conn->prepare("UPDATE products SET img=:img,title=:title,code=:code,category=:category,dates=:dates,stock=:stock,tag=:tag,dsc=:dsc WHERE id=:id");
    $add->bindParam(':img',$img);
    $add->bindParam(':title',$name);
    $add->bindParam(':code',$code);
    $add->bindParam(':category',$cat);
    $add->bindParam(':dates',$date);
    $add->bindParam(':stock',$ava);
    // $add->bindParam(':price',$price);
    $add->bindParam(':tag',$tag);
    $add->bindParam(':dsc',$dsc);
    $add->bindParam(':id',$edt);
    if($add->execute()){move_uploaded_file($_FILES['img']['tmp_name'] , '../assets/img/products/'.$img); $hdr =  header('Location: products.php?msg=Success');
      if(!$hdr){echo '<script>window.open("products.php?msg=Success","_self")</script>';}
    }
  }
  } catch (PDOException $e) {
  echo "Oops : ".$e->getMessage();
  }

  ?>
<?php } }
if(!empty($_GET['dlt'])){
  if(isset($_GET['dlt'])){
    $dlt = test_input($_GET['dlt']);
    $del = $conn->prepare('DELETE FROM products WHERE id=:id');
    $del->bindParam(':id',$dlt);
    if($del->execute()){
      $hdd = header('Location: products.php?msg=Success');
      if(!$hdd){echo "<script>window.open('products.php?msg=Success','_self')</script>";}
    }

 } } ?>
<br/>
<?php require_once('footer.php'); ?>
