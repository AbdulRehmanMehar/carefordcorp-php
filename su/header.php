<?php include_once('inc/db.inc.php');
try {
  $glo = $conn->prepare("SELECT * FROM general");
  $glo->setFetchMode(PDO::FETCH_OBJ);
  $glo->execute();
  $grow = $glo->fetch();
  $brand = $grow->brand_name;
  $brand_img = $grow->brand_img;
  $brand_phone = $grow->phone;
  $brand_wapp = $grow->wapp;
} catch (PDOException $e) {
  echo "Oops : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="NOINDEX,NOFOLLOW">
  <meta name="author" content="Abdul Rehman">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $page; ?> | Careford Corp</title>
  <!--Stylesheets-->
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" /> -->
  <link rel="stylesheet" type="text/css" href="/../assets/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="/../assets/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="/../assets/css/style.css" />
  <link rel="stylesheet" type="text/css" href="/../assets/css/admin.css" />
	<link rel="shortcut icon" href="/../assets/img/<?php echo $brand_img; ?>" />
</head>
<body>
  <!--Navbar-->
  <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-custom" onscroll="navbarscroll()">
    <a class="navbar-brand" href="https://carefordcorp.com/su">

      <!--<i class="fa fa-medkit fa-2x"></i>--> <i><img src="/../assets/img/<?php echo $brand_img; ?>" style="height:2em"></i> <b><?php echo $brand; ?> </b><span>Super User</span>

    </a>
    <?php if (!empty($_SESSION['email'])) : ?>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" onclick='navbarFunction(this)'>
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item <?php if($page == 'Super User'){echo 'active';} ?>">
            <a class="nav-link" href="https://carefordcorp.com/su/">Home </a>
          </li>
          <li class="nav-item <?php if($page == 'Pages'){echo 'active';} ?>">
            <a class="nav-link" href="pages.php">Pages</a>
          </li>
          <li class="nav-item <?php if($page == 'Categories'){echo 'active';} ?>">
            <a class="nav-link" href="categories.php">Categories</a>
          </li>
          <li class="nav-item <?php if($page == 'Products'){echo 'active';} ?>">
            <a class="nav-link" href="products.php">Products</a>
          </li>
          <li class="nav-item <?php if($page == 'Carousel'){echo 'active';} ?>">
            <a class="nav-link" href="carousel.php">Carousel</a>
          </li>
          <li class="nav-item <?php if($page == 'Messages'){echo 'active';} ?>">
            <a class="nav-link" href="messages.php">Messages
              <?php
                $mrp = 0; $dte = date("Y-m-d");
                $mpq = $conn->prepare("SELECT * FROM message WHERE read_chk=:msgck");
                $mpq->bindParam(':msgck',$mrp);
                //$mpq->bindParam(':dte',$dte);
                $mpq->setFetchMode(PDO::FETCH_OBJ);
                $mpq->execute();
                $rowm = $mpq->fetch();
                // while($rowm = $mpq->fetch()){
                  @$read = $rowm->read_chk;
                  //$dater = $rowm->date_snt;
                  if(($read == $mrp)){
                    echo '<span class="badge badge-light">new</span>';
                  }
                // }


              ?>
            </a>
          </li>
          <li class="nav-item <?php if($page == 'Cart'){echo 'active';} ?>">
            <a class="nav-link" href="cart.php"><i class="fa fa-shopping-cart fa-lg"></i> Cart</a>
          </li>
          
        </ul>
        <form class="form-inline my-2 my-lg-0" action="logout.php" method="post" >
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="logout">Logout</button>
        </form>
      </div>
    <?php endif; ?>
  </nav>
