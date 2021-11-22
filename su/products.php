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
  <?php
    if (!empty($_GET['msg'])) {    $msg = $_GET['msg'];     ?>
    <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
      <?php echo test_input($msg); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php  } ?>

    <h5 class="text-center mb-4">Which kind of product you would like to see?</h5>
    <ul class="nav justify-content-center mb-4">
      <li class="nav-item">
        <a class="nav-link disabled">Category : </a>
      </li>
      <?php
        try {
          $fct = $conn->prepare("SELECT * FROM categories");
          $fct->setFetchMode(PDO::FETCH_OBJ);
          $fct->execute();
          for($i=1;$rct = $fct->fetch();$i++){?>
            <li class="nav-item">
              <a class="nav-link " href="products.php?cat=<?php echo $rct->title; ?>"><?php echo $rct->title ?></a>
            </li>
      <?php  }
        } catch (PDOException $e) {
          echo "Oops : " . $e->getMessage();
        }
      ?>
    </ul>
    <div class="alert  text-center alert-dismissible fade show" role="alert">
      <p class="mb-4">Filter Tag : <a href='products.php?tag=featured'>Featured</a> | <a href='products.php?tag=popular'>Popular</a></p>
    </div>
    <!-- </div> -->
    <!-- Content Column -->
    <!-- <div class="col-lg-9 mb-4"> -->
      <!-- search form -->
      <div class="text-center mb-4"  style="margin:0 auto">
        <form class="form-inline mx-auto" method="post" action="">
          <div class="form-group mx-sm-3">
            <input type="text" class="form-control-plaintext" name='catname' value="Product Name | Code" readonly>
          </div>
          <div class="form-group mx-sm-3">
            <input type="text" class="form-control" name='wts' id='wts' placeholder="Search" required>
          </div>
          <div class="form-group mx-sm-3 text-center">
            <button type="submit" name='srch' class="btn btn-outline-primary"><i class="fa fa-search fa-lg"></i></button>
          </div>
          <div class="form-group mx-sm-3 text-center">
          | &nbsp; &nbsp;  <a href='add_product.php?add=Yes'>Add a Product</a>
          </div>
        </form>

        <br/>

      </div>


        <!--Product Area-->

        <div class="row" id='more'>
          <?php
            $last_id = '';
            $tp = '';

            if(isset($_POST['srch'])){
              $wts = $tp = test_input($_POST['wts']);
              $ttb =  '<h3 class="text-center mb-4">Results for Search : <span style="text-transform:capitalize">'.$wts.'</span></h3>';
              $ftch = $conn->prepare("SELECT * FROM products WHERE title LIKE ? LIMIT 3");

              $ftch->setFetchMode(PDO::FETCH_OBJ);
              $ftch->execute(["%$wts%"]);
            }elseif(isset($_GET['cat'])){
              $gcat = $tp = test_input($_GET['cat']);
              $cat = $conn->prepare('SELECT * FROM categories WHERE title=:ttle LIMIT 3');
              $cat->bindParam(':ttle',$gcat);
              $cat->setFetchMode(PDO::FETCH_OBJ);
              $cat->execute();
              $rat = $cat->fetch();
              $gttle = $rat->title;
              $ttb = '<h3 class="text-center mb-4">Results for Category : <span style="text-transform:capitalize">'.$gttle.'</span></h3>';
              $ftch = $conn->prepare("SELECT * FROM products WHERE category=:category LIMIT 3");
              $ftch->bindParam(':category',$gttle);
              $ftch->setFetchMode(PDO::FETCH_OBJ);
              $ftch->execute();
            }elseif(isset($_GET['tag'])){
              $gtag = $tp = test_input($_GET['tag']);
              $gtp = "popular";
              $gtf = "featured";
              $ftch = $conn->prepare("SELECT * FROM products WHERE tag=:tag LIMIT 3");
              if($gtag == $gtp){
                $ttb = '<h3 class="text-center mb-4">Results for Tag : <span style="text-transform:capitalize">'.$gtp.'</span></h3>';
                $ftch->bindParam(':tag',$gtp);
                $ftch->setFetchMode(PDO::FETCH_OBJ);
                $ftch->execute();
              }
              if($gtag == $gtf){
                $ttb =  '<h3 class="text-center mb-4">Results for Tag : <span style="text-transform:capitalize">'.$gtf.'</span></h3>';
                $ftch->bindParam(':tag',$gtf);
                $ftch->setFetchMode(PDO::FETCH_OBJ);
                $ftch->execute();
              }
            }else{
              $ttb = "<h3>All Products</h3>";
              $ftch = $conn->prepare("SELECT * FROM products LIMIT 3");
              $ftch->setFetchMode(PDO::FETCH_OBJ);
              $ftch->execute();
            }
             ?>
            <div class="col-md-12 text-center"><?php echo $ttb . '<br/>'; ?></div>
          <?php  while($rtch = $ftch->fetch()){  ?>

          <div class="col-md-4">
            <div class="card text-center" style="width:auto !important">
              <!-- <div class=" background" style="background-image: url('http://placehold.it/500x250'); height: 250px"></div> -->
              <a href="#detail-<?php echo $rtch->id; ?>" data-toggle='modal' style="margin:0 auto">
                <div ><img class="card-img-top lazy" src="../assets/img/load.gif" data-src="../assets/img/products/<?php echo $rtch->img; ?>" style="height:200px; max-height:200px; width:auto !important"></div>
              </a>
              <div class="card-body">
                <a href="#detail-<?php echo $rtch->id; ?>" data-toggle='modal'>
                  <h4 class="card-title"><?php echo $rtch->title; ?></h4>
                </a>
                <h6> (Code : <?php echo $rtch->code; ?>)</h6>

                <p>Action : <a href='add_product.php?edt=<?php echo $rtch->id; ?>'>Edit</a> | <a href='add_product.php?dlt=<?php echo $rtch->id; ?>'>Delete</a></p>
              </div>
            </div>
          </div>
          <!-- Modal -->
          <div class="modal fade" id="detail-<?php echo $rtch->id; ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <div class="modal-title">
                    <h5><?php echo $rtch->title; ?> <small>(Code : <?php echo $rtch->code; ?>)</small></h5>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <!-- product details -->
                  <div class="container">
                    <div class="row">
                      <div class="col-md-6">
                        <p><b>Description </b>:
                          <br>
                          &nbsp;&nbsp;&nbsp;<?php echo $rtch->dsc; ?>
                        </p>
                        
                        <p><b>Action</b> : <a href='add_product.php?edt=<?php echo $rtch->id; ?>'>Edit</a> | <a href='add_product.php?dlt=<?php echo $rtch->id; ?>'>Delete</a></p>
                      </div><!--col-md-6-->
                      <div class="col-md-6">
                        <div class="parent">
                          <div class="child">
                            <img src="../assets/img/load.gif" class="lazy" data-src="../assets/img/products/<?php echo $rtch->img; ?>" style="max-width:100%; overflow:hidden">
                          </div>
                        </div>
                        <br/>
                      </div><!--col-md-6-->
                    </div><!--row-->
                  </div><!--container-->
                </div>
                <div class="modal-footer text-center" style="margin:0 auto">
                  <p>Added on <b><?php echo $rtch->dates; ?> </b>
                    <?php if($rtch->tag != '0'){ ?>
                      , Tagged as <b> <?php echo $rtch->tag; ?> </b>
                    <?php } ?>
                    in Category <b><?php echo $rtch->category; ?> </b> &
                    <?php if($rtch->stock == "avail"){echo '<b>Available</b>';}else{echo '<b>Not Available</b>';} ?> in Stock.
                  </p>
                </div>
              </div>
            </div>
          </div><!--modal ends-->
        <?php
          $last_id =  $rtch->id;
          }
        ?>
        <div class="col-md-12" id='remove_row'>
          <button type="button" class="btn btn-outline-info text-center mt-4 mb-4 extended" id="load" data-vid='<?php echo $last_id; ?>' data-srch='<?php echo @$wts; ?>' data-cat='<?php echo @$gcat; ?>' data-tag='<?php echo @$gtag; ?>'>
            <i class="fa fa-refresh" aria-hidden="true"></i> Load More
          </button>
        </div>
      </div><!--row-->

</div>

<?php require_once('footer.php'); ?>
