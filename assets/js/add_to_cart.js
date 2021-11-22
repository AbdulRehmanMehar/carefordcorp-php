function atc()
{
  var code = document.getElementById("p_code").value;
  var name = document.getElementById("p_name").value;
  var quantity = document.getElementById("quantity").value;
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
            text: "Added to Cart",
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
