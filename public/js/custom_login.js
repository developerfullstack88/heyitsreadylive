/*set autocomplete functionality
$("#orderForm #name").autocomplete({
  source: APP_URL+"/orders/get-user-info",
  minLength: 2,
  select: function( event, ui ) {
    $.ajax({
      url:APP_URL+"/orders/get-user-info/?uid="+ui.item.id,
      datatype:'json',
      success:function(response){
        if(response){
          var json=$.parseJSON(response);
          $("#orderForm #email").val(json.email);
          $("#orderForm #phone_number").val(json.phone_number);
          $("#orderForm #userId").val(json.id);
          $('.ui-autocomplete.ui-front').empty();
          $('.ui-helper-hidden-accessible').empty();
        }
      }
    });
  },
  response: function(event, ui) {
      if (!ui.content.length) {
        $("#orderForm #email").val('');
        $("#orderForm #phone_number").val('');
        $("#orderForm #userId").val('');
      }
    }
});*/
