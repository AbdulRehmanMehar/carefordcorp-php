<?php
  $page = 'Search';
  require_once('header.php');
?>

<div class="container">
  <?php
    $last_id = '';
    // $get = '';
    if(isset($_GET['srch'])){
      $get = test_input($_GET['wts']);
      $srch = $conn->prepare('SELECT * FROM products WHERE (title LIKE ? /* AND code LIKE ? */) LIMIT 3');
      $srch->setFetchMode(PDO::FETCH_OBJ);
      $srch->execute(["%$get%"] /* , ["%$get%"] */);
  ?>
  <!-- Page Heading/Breadcrumbs -->
  <h1 class="mt-4 mb-3"><?php echo $page . ' Results for : ' . @$get; ?>
    <!-- <small>(<?php echo $brand; ?>)</small> -->
  </h1>

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?php echo $brand_url; ?>"><?php echo $brand; ?></a>
    </li>
    <li class="breadcrumb-item active"><?php echo $page; ?></li>
  </ol>
  <div class="row" id='more'>
    <div class="col-md-12 text-center"><h3 class="mt-4 mb-4">Results for : <?php echo @$get; ?></h3></div>
    <?php while ($rtch = $srch->fetch()) { ?>
        <div class="col-md-4">
          <div class="card text-center" style="width:auto !important">
            <a href="#detail-<?php echo $rtch->id; ?>" data-toggle='modal' style="margin:0 auto">
              <div ><img class="card-img-top lazy" src="../assets/img/load.gif" data-src="../assets/img/products/<?php echo $rtch->img; ?>" style="height:200px; max-height:200px; width:auto !important"></div>
            </a>
            <div class="card-body">
              <a href="#detail-<?php echo $rtch->id; ?>" data-toggle='modal'>
                <h4 class="card-title"><?php echo $rtch->title; ?></h4>
              </a>
              <p><?php echo substr($rtch->dsc,0,30).'...'; ?></p>
              <form action="" method="post" onsubmit="return atc<?php echo $rtch->id; ?>()">
                <div class="form-group">
                  <input type="hidden" id="p_code<?php echo $rtch->id; ?>" value="<?php echo $rtch->code; ?>" />
                  <input type="hidden" id="p_name<?php echo $rtch->id; ?>" value="<?php echo $rtch->title; ?>">
                  <input type='number' class="form-control form-control-sm" id="quantity<?php echo $rtch->id; ?>" placeholder="Quantity" required />
                </div>
                <input type="submit" class="btn btn-success btn-sm extended" id="add_to_cart<?php echo $rtch->id; ?>" value="Add to Cart" />
              </form>
            </div>
          </div>
        </div>
        <script type="text/javascript">
          function atc<?php echo $rtch->id; ?>()
          {
            var code = document.getElementById("p_code<?php echo $rtch->id; ?>").value;
            var name = document.getElementById("p_name<?php echo $rtch->id; ?>").value;
            var quantity = document.getElementById("quantity<?php echo $rtch->id; ?>").value;
            if((code === '') || (name === '')){
              Toastify({
                  text: "Sorry Something Went Wrong",
                  duration: 6000,
                  gravity: "bottom",
                  positionLeft: false,
                  backgroundColor: "#0f3443"
              }).showToast();
              return;
            }
            if(code && name && quantity)
            {
              $.ajax
              ({
                type: 'post',
                url: 'add_to_cart.php',
                data:
                {
                 name:name,
                 code:code,
                 quantity:quantity
                },
                success: function (response)
                {
                  Toastify({
                      text: "<?php echo $rtch->title; ?> is Added to Cart",
                      duration: 6000,
                      gravity: "bottom",
                      positionLeft: false,
                      backgroundColor: "#0f3443"
                  }).showToast();
                }
              });
            }

            return false;
          }

        </script>
        <!-- Modal -->
        <div class="modal fade" id="detail-<?php echo $rtch->id; ?>" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <div class="modal-title">
                  <h5><?php echo $rtch->title; ?></h5>
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
                      <form action="" method="post" onsubmit="return atc()">
                        <div class="form-group">
                          <input type="hidden" id="p_code" value="<?php echo $rtch->code; ?>" />
                          <input type="hidden" id="p_name" value="<?php echo $rtch->title; ?>">
                          <input type='number' class="form-control form-control-sm" id="quantity" placeholder="Quantity" required />
                        </div>
                        <input type="submit" class="btn btn-success btn-sm extended" id="add_to_cart" value="Add to Cart" />
                      </form>
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
      <?php $last_id =  $rtch->id; } ?>
  </div>
  <div class="row">
    <div class="col-md-12" id='remove_row'>
      <button type="button" class="btn btn-outline-info text-center mt-4 mb-4 extended" id="load" data-vid='<?php echo $last_id; ?>' data-srch='<?php echo $get; ?>'>
        <i class="fa fa-refresh" aria-hidden="true"></i> Load More
      </button>
    </div>
  </div>
<?php }else{ ?>

<div class="col-md-12">
  <!-- Page Heading/Breadcrumbs -->
  <h1 class="mt-4 mb-3"><?php echo $page; ?>
    <!-- <small>(<?php echo $brand; ?>)</small> -->
  </h1>

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?php echo $brand_url; ?>"><?php echo $brand; ?></a>
    </li>
    <li class="breadcrumb-item active"><?php echo $page; ?></li>
  </ol>
  <div class="mt-4 mb-4">
    <form method="get" action="search.php">
      <div class="form-group">
        <input class="form-control form-control-lg" type="search" name="wts" placeholder="Instrument Name" aria-label="Search" required>
      </div>
      <button class="btn btn-outline-success  extended" type="submit" name='srch' title="Search"><i class="fa fa-search"></i></button>
    </form>
  </div>
</div>

<?php } ?>
</div>



<?php require_once('footer.php'); ?>
