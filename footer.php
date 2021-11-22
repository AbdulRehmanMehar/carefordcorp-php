

<!--footer-->
<footer class="footer bg-dark">
  <div class="container">
    <!--copy_right-->
    <ul class="icons list-unstyled">
			<li><a href="<?php echo $brand_twitter ?>" class="icon circle fa fa-twitter" target="_blank"><span class="label">Twitter</span></a></li>
			<li><a href="<?php echo $brand_fb ?>" class="icon circle fa fa-facebook" target="_blank"><span class="label">Facebook</span></a></li>
			<li><a href="<?php echo $brand_gp ?>" class="icon circle fa fa-google-plus" target="_blank"><span class="label">Google+</span></a></li>
			<li><a href="<?php echo $brand_insta ?>" class="icon circle fa fa-instagram" target="_blank"><span class="label">Instagram</span></a></li>
		</ul>
    <p>&copy; <?php echo $brand .' '. $copy_right; ?> . All rights Reserved.</p>
    <p>Developed By : <a href='https://rehmanmehar.000webhostapp.com/' alt='The Fontend - Backend Developer' title="The Fontend - Backend Developer" target="_blank">Abdul Rehman</a></p>
  </div>
</footer>

<!-- Modal -->

<div class="modal hide fade" id="offers">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Offers</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <?php
          $sts = '1';
          $ofr = $conn->prepare("SELECT * FROM offer WHERE status=:sts");
          $ofr->bindParam(':sts',$sts);
          $ofr->setFetchMode(PDO::FETCH_OBJ);
          $ofr->execute();
        ?>
        <?php  for ($i = 1 ;$r = $ofr->fetch(); $i++) {  ?>

            <h4><?php echo $r->title; ?></h4>
            <p><?php echo $r->dsc; ?></p>
        <?php  }  ?>
        <h4>Order another Instrument Kit?</h4>
        <p>Contact Us to do so</p>
      </div>
      <div class="modal-footer" style='margin:0 auto'>
        <a href="/contact.php" class="btn btn-outline-info text-center extended">Contact Us</a>
      </div>
    </div>
  </div>
</div>


<!--Javascripts-->

<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/toastify.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/script.js"></script>
<script src="/assets/js/lazy.js"></script>

<?php if($page === 'Home' || $page === 'Products'){ ?>
<script src='/assets/js/search.js'></script>
<?php } ?>

<?php
if($page == 'Home'){
$stst = '1';
$ochk = $conn->prepare('SELECT * FROM offer WHERE status=:status');
$ochk->bindParam(':status',$stst);
$ochk->setFetchMode(PDO::FETCH_OBJ);
$ochk->execute();
while ($ocr = $ochk->fetch() ) {
  if($ocr->status === '1'){ ?>
  <script>
    // $(window).on("load",function(){
    //     $("#offers").modal("show");
    // });
  </script>
<?  } }  } ?>

<?php if ($page === 'Search'): ?>
<!-- Load more for Search -->
<script type="text/javascript">
$(document).ready(function(){
  $(document).on('click','#load', function(){
    var last_id = $(this).data("vid");
    // var get = $(this).data("get");
    var srch = $(this).data("srch");
    // var gcat = $(this).data("cat");
    // var gtag = $(this).data("tag");
    $('#load').html('Loading...');
    $.ajax({
      url:"load-srch-rslts.php",
      method:"POST",
      data:{last_id:last_id,srch:srch},
      dataType:'text',
      success:function(data){
        if(data != ''){
          $('#remove_row').remove();
          $('#more').append(data);
        }else{
          $('#load').html("It seems that you've reached the end!");
        }
      }
    });
  });
});
</script>
<?php endif; ?>
<?php
  if(isset($_GET['msg'])){
    $msg = test_input($_GET['msg']);
?>
<script type="text/javascript">
Toastify({
    text: "<?php echo $msg; ?>",
    duration: 6000,
    gravity: "bottom",
    positionLeft: false,
    backgroundColor: "#0f3443"
}).showToast();
</script>
<?php } ?>
<script type="text/javascript">
	$(document).ready(function() {
		setInterval(function () {
			$('#num').load('cart_rowCount.php')
		}, 1000);
	});
</script>

</body>
</html>
