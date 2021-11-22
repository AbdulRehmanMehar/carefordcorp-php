$(document).ready(function(){
  $(document).on('click','#load', function(){
    var last_id = $(this).data("vid");
    var get = $(this).data("get");
     var tg = $(this).data("tg");

    $('#load').html('Loading...');
    $.ajax({
      url:"load-products.php",
      method:"POST",
      data:{last_id:last_id,get:get,tg:tg},
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






// $(document).ready(function(){
//   $(document).on('click','#load', function(){
//     var last_id = $(this).data("vid");
//     $('#load').html('Loading...');
//     $.ajax({
//       url:"load-products.php",
//       method:"POST",
//       data:{last_id:last_id},
//       dataType:'text',
//       success:function(data){
//         if(data != ''){
//           $('#remove_row').remove();
//           $('#more').append(data);
//         }else{
//           $('#load').html("It seems that you've reached the end!");
//         }
//       }
//     });
//   });
// });
