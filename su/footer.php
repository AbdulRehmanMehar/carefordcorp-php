<!--copy_right-->
<footer class="footer bg-dark">
<div class="footer-table">
  <div class="footer-table-cell">
    <p>&copy; Careford Corp 2017 . All rights Reserved.</p>
  </div>
</div>

</footer>

<script src="/../assets/js/jquery.min.js"></script>
<script src="/../assets/js/popper.min.js"></script>
<script src="/../assets/js/bootstrap.min.js"></script>
<script src="/../assets/js/lazy.js"></script>
<script src="/../assets/js/script.js"></script>
<?php if ($page == 'Products'): ?>
<script type="text/javascript">
$(document).ready(function(){
  $(document).on('click','#load', function(){
    var last_id = $(this).data("vid");
    // var get = $(this).data("get");
    var srch = $(this).data("srch");
    var gcat = $(this).data("cat");
    var gtag = $(this).data("tag");
    $('#load').html('Loading...');
    $.ajax({
      url:"load-products.php",
      method:"POST",
      data:{last_id:last_id,srch:srch,gcat:gcat,gtag:gtag},
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
</body>
</html>
