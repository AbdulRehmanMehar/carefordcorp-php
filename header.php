<!DOCTYPE html>
<?php
  session_start();
  require_once('su/inc/db.inc.php');
  try {
    $glo = $conn->prepare("SELECT * FROM general");
    $glo->setFetchMode(PDO::FETCH_OBJ);
    $glo->execute();
    $grow = $glo->fetch();
    $brand = $grow->brand_name;
    $brand_img = $grow->brand_img;

    $brand_twitter = $grow->twitter;
    $brand_fb = $grow->fb;
    $brand_gp = $grow->gplus;
    $brand_insta = $grow->insta;
  } catch (PDOException $e) {
    echo "Oops : " . $e->getMessage();
  }
  $brand_url = "https://carefordcorp.com";
  $copy_right = date('Y');
  $cameintobeing = '2017';
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="An Instrument Maker Company">
  <meta name="author" content="Abdul Rehman">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $page; ?> | <?php echo $brand; ?></title>
  <!--Stylesheets-->
  <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/toastify.css" />
  <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
	<link rel="shortcut icon" href="/assets/img/<?php echo $brand_img; ?>" />

</head>
<body>
<!--Navbar-->

<nav class="navbar navbar-expand-lg <?php if($page === "Home"){ echo' fixed-top'; }else{ echo 'sticky-top';} ?> navbar-dark bg-custom" onscroll="navbarscroll()">
  <a class="navbar-brand" href="<?php echo $brand_url; ?>">

    <!--<i class="fa fa-medkit fa-2x"></i>--><i><img src='/assets/img/<?php echo $brand_img; ?>' style="height:2em"></i> <b><?php echo $brand; ?> </b><span>Shopping Area</span>

  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" onclick='navbarFunction(this)'>
    <div class="bar1"></div>
    <div class="bar2"></div>
    <div class="bar3"></div>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?php if($page === 'Home'){?> active <?php } ?>">
        <a class="nav-link" href="<?php echo $brand_url; ?>">Home</a>
      </li>
      <li class="nav-item dropdown">

        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Products</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php
            try {
              $stmt = $conn->prepare("SELECT * FROM categories");
              $stmt->setFetchMode(PDO::FETCH_OBJ);
              $stmt->execute();
              while ($row =  $stmt->fetch()) {
                echo '<a class="dropdown-item" href="/products.php?type='. $row->title .'">'.$row->title.'</a>';
              }
            } catch (PDOException $e) {
              echo "Oops : " . $e->getMessage();
            }
          ?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/products.php">All Products</a>
        </div>
      </li>
      <li class="nav-item <?php if($page === 'About'){?> active<?php } ?>">
        <a class="nav-link" href="/about.php">About</a>
      </li>
      <li class="nav-item <?php if($page === 'Contact'){?> active<?php } ?>">
        <a class="nav-link" href="/contact.php">Contact</a>
      </li>
    </ul>

      <div class="cart-wrapper srch">
        <!-- Search form -->
        <a class="btn btn-srch btn-outline-success btn-cart dropdown-toggle " id="search" data-toggle="dropdown" title='Search'>
          <span class="hidden-sm"><i class="fa fa-lg fa-search"></i></span>
          <span class="visible-sm">Search</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="search">
          <form class="form-inline px-4 py-3 my-2 my-lg-0" method="get" action="search.php">
            <input class="form-control form-control-sm mr-sm-2" type="search" name="wts" placeholder="Instrument Name" aria-label="Search" required>
            <button class="btn btn-outline-success btn-sm my-2 my-sm-0" type="submit" name='srch' title="Search"><i class="fa fa-search"></i></button>
          </form>
        </div>
      </div>
      <div class="cart-wrapper">
        <a href="/cart.php" class="btn btn-cart btn-outline-success <?php if($page === 'Cart'){?>cart-active <?php }?>" title="Cart">
          <span class="hidden-sm"><i class="fa fa-shopping-cart fa-lg"></i></span>
          <span class="visible-sm">Cart</span>
          <?php
            $ip = getUserIP();
            $cart = $conn->prepare('SELECT COUNT(*) FROM cart WHERE ip_add=:ip');
            $cart->bindParam(':ip',$ip);
            $cart->execute();
            $number = $cart->fetchColumn();
          ?>
            <span class="badge" id="num"></span>
        </a>
      </div>
      <div class="cart-wrapper">
        <!-- login form -->
        <a class="btn btn-cart btn-outline-success dropdown-toggle <?php if($page === 'Register'){?>cart-active <?php }?>" id="login" data-toggle="dropdown" title='Login'>
          <span class="hidden-sm"><i class="fa fa-sign-in fa-lg"></i></span>
          <?php
            if(!isset($_SESSION['l_email'])){
              echo '<span class="visible-sm">Login | Register</span>';
            }elseif (isset($_SESSION['l_email'])) {
              echo '<span class="visible-sm">Profile</span>';
            }
          ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="login">
          <?php if(!isset($_SESSION['l_email'])): ?>
            <form class="px-4 py-1" method="post" action="register.php">
              <div class="form-group">
                <label>Email address</label>
                <input type="email" name='email' class="form-control form-control-sm" placeholder="Enter Email">
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" name='pass' class="form-control form-control-sm" placeholder="Enter Password">
                <p  style="font-size:14px;">
                  <a href='/register.php?forgot'>Forgot Password?</a> &nbsp;
                  <a href='/register.php?sup'>Signup</a>
                </p>
              </div>
              <button type="submit" name='login' class="btn btn-outline-info btn-sm extended">Login</button>
            </form>
          <?php endif; ?>
          <div class="px-4 py-1 text-center">
            <?php
              if(isset($_SESSION['l_email'])){
                $ftch = $conn->prepare('SELECT * FROM customers WHERE email=:email');
                $ftch->bindParam(':email',$_SESSION['l_email']);
                $ftch->setFetchMode(PDO::FETCH_OBJ);
                $ftch->execute();
                $rtch = $ftch->fetch(); ?>
                <div class="background-img mb-4" style="background-image:url(assets/img/customers/<?php echo $rtch->img; ?>); height:100px; width:100px;"></div>
                <?php echo $rtch->name; ?><br>
                <a href='register.php?edtpro'>Edit Profile</a><br><br>

                <form action="register.php" method="post">
                  <div class="form-group">
                    <input type="submit" class="form-control btn btn-outline-danger btn-sm extended" name="logout" value="Logout">
                  </div>
                </form>
            <?php  }   ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>
