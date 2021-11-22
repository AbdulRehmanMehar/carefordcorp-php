<?php $page="About"; require_once('header.php'); ?>



<!-- Page Content -->
<div class="container">

  <!-- Page Heading/Breadcrumbs -->
  <h1 class="mt-4 mb-3"><?php echo $page; ?>
    <small>(<?php echo $brand; ?>)</small>
  </h1>

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?php echo $brand_url; ?>"><?php echo $brand; ?></a>
    </li>
    <li class="breadcrumb-item active"><?php echo $page; ?></li>
  </ol>
  <?php
    try {
      $abtr = $conn->prepare('SELECT * FROM about');
      $abtr->setFetchMode(PDO::FETCH_OBJ);
      $abtr->execute();
      $abbr = $abtr->fetch();
    } catch (PDOException $e) {
      echo "Oops : " .$e->getMessage();
    }

  ?>
  <!-- Intro Content -->
  <div class="row">
    <div class="col-lg-6">
     <img src="/assets/img/load.gif" class="img-fluid rounded mb-4 lazy" data-src="/assets/img/<?php echo $abbr->img_main; ?>">
    </div>
    <div class="col-lg-6">
      <h2><?php echo $page . ' ' . $brand; ?></h2>
      <p><?php echo $abbr->desc_main; ?></p>
    </div>
  </div>
  <!-- /.row -->

  <!-- Team Members -->

    <h2 class="testimonial-heading">Our Team</h2>
</div>
  <!-- carousel Team -->

  <div id="carouselExampleIndicators" class="testimonial carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <?php
        $ftch = $conn->prepare("SELECT * FROM about_team");
        $ftch->setFetchMode(PDO::FETCH_OBJ);
        $ftch->execute();
        for($i = 0; $rtch = $ftch->fetch() ; $i++){  ?>
          <li data-target="#carouselExampleIndicators" data-slide-to="0" <?php if($i == 0){echo "class='active'";} ?>></li>
      <?php } ?>
    </ol>
    <div class="carousel-inner">
      <?php
        $ftch = $conn->prepare("SELECT * FROM about_team");
        $ftch->setFetchMode(PDO::FETCH_OBJ);
        $ftch->execute();
        for($i = 0; $rtch = $ftch->fetch() ; $i++){  ?>

        <div class="carousel-item testimonial-item <?php if($i == 0){echo 'active'; } ?>">
          <div class="col-md-6 offset-md-3">
            <div class="background-img" style="background-image:url(/assets/img/team/<?php echo $rtch->img; ?>)"></div>
            <div class="carousel-txt">

              <h3><?php echo $rtch->name; ?> </h3>
              <h5 class="testimonial-subheading"><?php if($rtch->email != ""){echo '<small><a href="mailto:'.$rtch->email.'">'.$rtch->email.'</a></small>';} ?></h5>
              <h6>Position : <?php echo $rtch->position; ?></h6>
              <p class="testimonial-txt">"<?php echo $rtch->dsc; ?>"</p>

            </div>
          </div>
        </div>

    <?php } ?>

    </div>

  </div>


<?php require_once("footer.php"); ?>
