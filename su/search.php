<?php
require_once('inc/db.inc.php');
if(isset($_POST['search'])){
  $search = $_POST['search'];
  $query = $conn->prepare("SELECT * FROM products WHERE title LIKE ?");
  $query->execute("[%$search%]");
  $count = $query->rowCount();
  if($count == 0){
    echo "Product Not Found!";
  }else {
    echo '<div class="row">';
    echo "<div class='col-md-12 text-center'>Search Results : </div>";

    while ($rtch = $query->fetch(PDO::FETCH_OBJ)) { ?>
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
            <p>Price : $<?php echo $rtch->price; ?></p>
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
                    <p><b>Price</b> : $<?php echo $rtch->price; ?></p>
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
<?php    }
echo "</div>";
  }
}
?>
