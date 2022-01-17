$(document).ready(function(){
  //month array
  const monthNames = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
  ];
  const monthNamesSpanish = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
  "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
  ];
  const monthNamesFrench = ["Janvier", "Février", "Mars", "Avril", "Peut", "Juin",
  "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
  ];


  //set eta datepciker*/
  $('.set-eta-picker').datetimepicker({
    useCurrent:true,
    format: "yyyy-mm-dd HH:ii P",
    showMeridian: true
  });
  $('.set-eta-date-picker').datepicker({useCurrent:true});
  $('.eta-time-picker').timepicker();
  $('.set-eta-picker').val(currentDateTime);
  //$('#orderForm #amount').maskNumber();
  //set eta datepciker*/

  /*dashboard table timer*/
  if(FILTERTYPE!='complete'){
    setInterval(function(){
      if(TIMEZONE){
        var dt = new Date().toLocaleString('en-US', {timeZone: TIMEZONE});
        dt=new Date(dt);
      }else{
        dt=new Date(dt);
      }
      var timenew = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
      var mnth=parseInt(dt.getMonth())+1;
      var current_time = dt.getDate()+'/'+mnth+'/'+dt.getFullYear()+" "+timenew;
      $('.etaTimeDiff').each(function() {
        var dbtime = $(this).data('date');
        var status = $(this).parent().attr('data-status');
        var oid = $(this).data('id');
        var ms= moment(dbtime, "DD/MM/YYYY HH:mm:ss").diff(moment(current_time, 'DD/MM/YYYY HH:mm:ss'));
        var diff = moment.duration(ms);
        var hours= diff.hours();
        var mints= diff.minutes();
        var sec= diff.seconds();
        var days=diff.days();
        if(days>0){
          var total = days+"days "+hours+ "h " + mints + "m " + sec+"s";
        }else if(sec<0 || mints<0){
          //var total = '';
        }else{
          var total = hours+ "h " + mints + "m " + sec+"s";
        }
        $(this).parent('td').next().text(total);
        if(mints==0 && sec==0 && hours==0){
          $(this).parent('td').next().text('');

          if((status=='pending' || status=='confirm') && status!='ready'){
            $(this).parents('tr').addClass('table-background-red');
          }
          if(!$(this).parents('tr').find('.btns-td button').hasClass('passed-due') && (status=='pending' || status=='confirm') && status!='ready') {
            //$(this).parents('tr').find('.btns-td').append('<button class="btn btn-danger btn-sm orderDelay passed-due" data-id="'+oid+'" type="button">Passed Due</button>');
          }
          $(this).removeClass('etaTimeDiff');
        }

      });
    },1000);

    setInterval(function(){
      if(TIMEZONE){
        var dt = new Date().toLocaleString('en-US', {timeZone: TIMEZONE});
        dt=new Date(dt);
      }else{
        dt=new Date(dt);
      }
      var timenew = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
      var mnth=parseInt(dt.getMonth())+1;
      var current_time = dt.getDate()+'/'+mnth+'/'+dt.getFullYear()+" "+timenew;
      $('.etaTimeDiffNotify').each(function() {
        var dbtime = $(this).text();
        if($.trim(dbtime)){
          var status = $(this).next().attr('data-status');
          var locate = $(this).next().attr('data-locate');
          var oid=$(this).data('id');
          var spot_number=$(this).parents('tr').find('.home-order-spot-number').text();
          var spot_color=$(this).parents('tr').find('.home-order-spot-color').text();
          if(status!='complete'){
            //getSpotNumber(oid,spot_number);
            getSpotOrderLocate(oid,spot_number,locate,spot_color);
          }
          var ms= moment(dbtime, "DD/MM/YYYY HH:mm:ss").diff(moment(current_time, 'DD/MM/YYYY HH:mm:ss'));
          var diff = moment.duration(ms);
          var hours= diff.hours();
          var mints= diff.minutes();
          var sec= diff.seconds();
          var days=diff.days();
          var total = days+"days "+hours+ "h " + mints + "m " + sec+"s";
          if(status!='complete'){
            if(mints=='-5' && sec=='-0'){
              sendInCompletePush(oid);
            }
          }
        }
      });
    },1000);
  }
  /*dashboard table timer*/


  /*This will send in completed order
  push notification after eta expiration 5 min*/
  function sendInCompletePush(oid){
    $.ajax({
      type:'get',
      url:APP_URL+'/home/send-incomplete-push/'+oid,
      datatype:'text/html',
      success:function(response){
        console.log('done');
      }
    });
  }
  //get day of month
  function day_of_the_month(d){
    return (d.getDate() < 10 ? '0' : '') + d.getDate();
  }

  function tConvert (time) {
  // Check correct time format and split into components
  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
  if (time.length > 1) { // If time format correct
    time = time.slice (1);  // Remove full string match value
    time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
    time[0] = +time[0] % 12 || 12; // Adjust hours
    time[0]=((time[0]<10)?'0':'')+time[0];
  }
  return time.join (''); // return adjusted time or original string
}

  /*dashboard clock current time*/
  setInterval(function(){
    if(TIMEZONE){
      var dt = new Date().toLocaleString('en-US', {timeZone: TIMEZONE});
      dt=new Date(dt);
    }else{
      dt=new Date(dt);
    }
    var minutes_with_zero=(dt.getMinutes() < 10 ? '0' : '') + dt.getMinutes();
    var seconds_with_zero=(dt.getSeconds() < 10 ? '0' : '') + dt.getSeconds();
    var hours_with_zero=(dt.getHours() < 10 ? '0' : '') + dt.getHours();

    var timenew = hours_with_zero + ":" + minutes_with_zero;
    var mnth=parseInt(dt.getMonth())+1;
    var month=mnth<10?'0'+mnth:mnth;
    var date=dt.getDate()<10?'0'+dt.getDate():dt.getDate();
      if(CURRENTUSERLANG=='es'){
        var current_date = monthNamesSpanish[mnth-1]+' '+day_of_the_month(dt)+', '+dt.getFullYear();
        current_time=tConvert(timenew);
      }else if(CURRENTUSERLANG=='fr'){
        var current_date = monthNamesFrench[mnth-1]+' '+day_of_the_month(dt)+', '+dt.getFullYear();
        current_time=tConvert(timenew);
      }else{
        var current_date = monthNames[mnth-1]+' '+day_of_the_month(dt)+', '+dt.getFullYear();
        current_time=tConvert(timenew);
      }
      $('#clockTimeClock').text(current_date);
      $('#clockTimeOnly').text(current_time);
  },1000);
  /*dashboard clock current time*/

  /*add order eta radio buttons*/
  $('#etaMinutesTxt').parents('.form-group').show();
  $('#etaMinutesTxt').attr('disabled',false);
  $('#etaDateTxt').parents('.form-group').hide();
  $('#etaDateTxt').attr('disabled',true);
  $('#etaMinutesTxt2').parents('.form-group').hide();
  $('#etaMinutesTxt2').attr('disabled',true);
  $('#etaDateTxt2').parents('.form-group').hide();
  $('#etaDateTxt2').attr('disabled',true);
  $('.eta-radio-btn').click(function(){
    var currentId=$(this).parents('.modal.fade').attr('id');
    var currentVal=$(this).val();

    if(currentId=='resetEtaModal' || $(this).hasClass('eta-radio-btn-order')){
      if(currentVal=='minutes'){
        $('#etaMinutesTxt').parents('.form-group').show();
        $('#etaDateTxt').parents('.form-group').hide();
        $('#etaDateTxt').attr('disabled',true);
        $('#etaDateTxt').attr('required',false);
        $('#etaMinutesTxt').attr('disabled',false);
        $('#etaMinutesTxt').attr('required',true);
      }else{
        $('#etaMinutesTxt').parents('.form-group').hide();
        $('#etaDateTxt').parents('.form-group').show();
        $('#etaMinutesTxt').attr('disabled',true);
        $('#etaMinutesTxt').attr('required',false);
        $('#etaDateTxt').attr('disabled',false);
        $('#etaDateTxt').attr('required',true);
      }
    }else{
      if(currentVal=='minutes'){
        $('#etaMinutesTxt2').parents('.form-group').show();
        $('#etaDateTxt2').parents('.form-group').hide();
        $('#etaDateTxt2').attr('disabled',true);
        $('#etaMinutesTxt2').attr('disabled',false);
      }else{
        $('#etaMinutesTxt2').parents('.form-group').hide();
        $('#etaDateTxt2').parents('.form-group').show();
        $('#etaMinutesTxt2').attr('disabled',true);
        $('#etaDateTxt2').attr('disabled',false);
      }
    }
  });
  /*add order eta radio buttons*/

  //set autocomplete functionality
  /*$("#orderForm #name,#orderForm #email,#orderForm #phone_number").autocomplete({
    source: APP_URL+"/orders/get-user-info",
    minLength: 2,
    select: function( event, ui ) {
      $.ajax({
        url:APP_URL+"/orders/get-user-info/?uid="+ui.item.id,
        datatype:'json',
        success:function(response){
          if(response){
            var json=$.parseJSON(response);
            $("#orderForm #name").val(json.name);
            $("#orderForm #email").val(json.email);
            $("#orderForm #phone_number").val(json.phone_number);
            $("#orderForm #userId").val(json.id);
          }
        }
      });
    },
    response: function(event, ui) {
        if (!ui.content.length) {
          /*$("#orderForm #name").val('');
          $("#orderForm #email").val('');
          $("#orderForm #phone_number").val('');
          $("#orderForm #userId").val('');
        }
      }
  });*/


  //ajax setup csrf token
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

});

/*set eta time button*/
$(document).on('click','.set-eta',function(){
  var order_id=$(this).data('id');
  $(this).addClass('active-eta');
  $('#setEtaModal #orderId').val(order_id);
  $('#setEtaModal #etaDateTxt2').parents('.form-group').next().show();
  $('#setEtaModal #etaMinutesTxt2').attr('disabled',false);
  $('#setEtaModal').modal('show');
});
/*set eta time button*/

/*Set eta modal submit functionality*/
$(document).on('click','#etaSubmit',function(){
  $.ajax({
    type:'post',
    url:APP_URL+'/orders/set-eta',
    data:$('#etaForm').serialize(),
    datatype:'text/html',
    success:function(response){
      if(response){
        var json=$.parseJSON(response);
        if($('.active-eta').parents('tr').find('.btns-td button').hasClass('home-btn-call-user') && !$('.active-eta').parents('tr').find('.btns-td button').hasClass('home-btn-completed')){
          $('.active-eta').parents('tr').find('.btns-td').prepend('<button class="btn btn-danger btn-sm change-status home-btn-completed" data-status="complete" data-id="'+$('#etaForm').find('#orderId').val()+'" type="button">'+COMPLETEDBTNTEXT+'</button>');
        }
        if(!$('.active-eta').parents('tr').find('.btns-td button').hasClass('home-btn-call-user')){
          if(!$('.active-eta').parents('tr').find('.btns-td button').hasClass('home-btn-ready') && !$('.active-eta').parents('tr').find('.btns-td button').hasClass('home-btn-completed')){
            $('.active-eta').parents('tr').find('.btns-td').prepend('<button class="btn btn-info btn-sm home-btn-ready change-status ml-1" data-status="ready" data-id="'+$('#etaForm').find('#orderId').val()+'" type="button">'+READYBTNTEXT+'</button>');
          }
          if(!$('.active-eta').parents('tr').find('.btns-td button').hasClass('orderDelay')){
            $('.active-eta').parents('tr').find('.btns-td').prepend('<button class="btn btn-primary btn-sm orderDelay home-btn-delayed" data-id="'+$('#etaForm').find('#orderId').val()+'" type="button">'+DELAYEDBTNTEXT+'</button>');
          }
        }
        //$('.active-eta').parents('tr').find('.hey-its-actual-order-time').text(json.actual_order_time);
        $('.active-eta').parents('tr').find('.etaTimeDiffNotify').text(json.local_24);
        $('.active-eta').parent().html('<span class="etaTimeDiff" data-id="'+$('#etaForm').find('#orderId').val()+'" data-date="'+json.local_24+'">'+json.local_12+'</span>');

      }
      $('#setEtaModal').modal('hide');
    }
  });
});

//order delayed button
$(document).on('click','.orderDelay',function(){
  var order_id=$(this).data('id');
  $('.orderDelay').removeClass('active-eta-delayed');
  $(this).addClass('active-eta-delayed');
  $('#resetEtaModal #orderId').val(order_id);
  $('#resetEtaModal #etaDateTxt').parents('.form-group').next().show();
  $('#resetEtaModal #etaMinutesTxt').attr('disabled',false);
  $('#resetEtaModal').modal('show');
  $('.set-eta-picker').val(currentDateTime);
});

/*Set eta modal submit functionality*/
$(document).on('click','#resetEtaSubmit',function(){
  var order_id=$('#resetEtaModal #orderId').val();
  $.ajax({
    type:'post',
    url:APP_URL+'/orders/set-eta',
    data:$('#resetEtaForm').serialize(),
    datatype:'text/html',
    success:function(response){
      if(response){
        var json=$.parseJSON(response);
        if(FILTERTYPE=='active'){
          json.local_12=json.local_12_time_only;
        }
        $('.active-eta-delayed').parents('tr').find('.eta-td').html('<span class="etaTimeDiff" data-id="'+order_id+'" data-date="'+json.local_24+'">'+json.local_12+'</span>');
        $('.active-eta-delayed').parents('tr').find('.etaTimeDiffNotify').text(json.local_24);
        /*if($('.active-eta-delayed').hasClass('passed-due')){
          $('.active-eta-delayed').remove();
        }else{
          $('.active-eta-delayed').parents('td.btns-td').find('.orderDelay.passed-due').remove();
        }*/
        $('.active-eta-delayed').parents('tr').removeClass('table-background-red');
        $('.orderDelay').removeClass('active-eta-delayed');
      }
      $('#resetEtaModal').modal('hide');
    }
  });
});

//change status of order
$(document).on('click','.change-status',function(){
  var id=$(this).data('id');
  var status=$(this).data('status');
  var th=$(this);
  $(this).prop('disabled',true);
  var order_amount=$(th).parents('tr').find('.hidden-order-amount').text();
  if(id && status){
    $.ajax({
      type:'PUT',
      url:APP_URL+'/orders/'+id,
      data:{status:status, _method: "PATCH"},
      datatype:'json',
      success:function(response){
        var json=$.parseJSON(response);
        console.log(json);
        if(json.status==true){
          $(this).prop('disabled',false);
          if(status=='ready'){
            $(th).parents('tr').removeClass('table-background-red');
            $(th).parents('tr').find('td.eta-td').attr('data-status',status);
            var btn_html='<button class="btn btn-info btn-sm ready-reminder" data-id="'+id+'" type="button">'+REMINDERBTNTEXT+'</button>&nbsp;&nbsp;';

            if(order_amount>0 && json.order_paid){
              btn_html+='<button class="btn btn-danger btn-sm change-status home-btn-completed" data-status="complete" data-id="'+id+'" type="button">'+COMPLETEDBTNTEXT+'</button>&nbsp;&nbsp;';
            }else{
              if(order_amount==0){
                btn_html+='<button class="btn btn-danger btn-sm change-status home-btn-completed" data-status="complete" data-id="'+id+'" type="button">'+COMPLETEDBTNTEXT+'</button>&nbsp;&nbsp;';
              }
              btn_html+='<button class="btn btn-danger btn-sm cash-payment-action home-btn-completed" data-status="payment" data-id="'+id+'" type="button">'+CASHPAYMENTBTNTEXT+'</button>&nbsp;&nbsp;';
            }
            btn_html+='<button class="btn btn-success btn-sm btn-view-detail home-btn-detail mt-btn-10" data-id="'+id+'" type="button">'+DETAILDBTNTEXT+'</button><button class="btn btn-warning btn-sm btn-view-print ml-1 mt-btn-10" data-id="'+id+'" type="button">'+PRINTBTNTEXT+'</button>';
            $(th).parents('td').html(btn_html);
          }else if(status=='complete'){
            $(th).parents('tr').removeClass('table-background-red');
            $(th).parents('tr').find('td.eta-td').attr('data-status',status);
            $(th).parents('tr').find('td.eta-td').text('');
            $(th).parents('tr').find('.timer-td').text('');
            $(th).parents('tr').find('.eta-td span').removeClass('etaTimeDiff');
            $(th).parents('td').html('<button class="btn btn-secondary btn-sm home-btn-completed" type="button">'+COMPLETEDBTNTEXT+'</button>&nbsp;&nbsp;<button class="btn btn-success btn-sm btn-view-detail home-btn-detail mt-btn-10" data-id="'+id+'" type="button">'+DETAILDBTNTEXT+'</button><button class="btn btn-warning btn-sm btn-view-print ml-1 mt-btn-10" data-id="'+id+'" type="button">'+PRINTBTNTEXT+'</button>');
            if($('#home-delete-complete-btn').length>0){
              $('#home-delete-complete-btn').show();
            }

          }
          $(th).parent().prev().text(status);
          $(th).remove();
        }else{
          $(this).prop('disabled',false);
        }
        dashboardStats(status);
      }
    });
  }
});

//Ready Reminder click
$(document).on('click','.ready-reminder',function(){
  var id=$(this).data('id');
  $.ajax({
    url:APP_URL+'/orders/ready-reminder/'+id,
    datatype:'text/html',
    success:function(response){}
  });
});

//quick min action button of add order
$(document).on('click','.quick-min-btn',function(){
  var btnText=$(this).text();
  $('#etaMinutesTxt').val(btnText);
  $('#etaMinutesTxt2').val(btnText);
});

//generate Map method
function generateMap(address) {
   //variable declaration
   var poly_btn = document.getElementById("custom-BtnPolygon");
   var geocoder;
   var map;
   var map = new google.maps.Map(document.getElementById('map'), {
     zoom: 10,
     center: {lat: -34.397, lng: 150.644}
  });

  geocoder = new google.maps.Geocoder();

  //This will set location on map according to address field
  geocoder.geocode({'address': address}, function(results, status) {
    if (status === 'OK') {
      map.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
        map: map,
        position: results[0].geometry.location
      });
    } else { }
  });

  //polygon code start
  /*var drawingManager = new google.maps.drawing.DrawingManager({
      drawingMode: google.maps.drawing.OverlayType.MARKER,
      drawingControl: true,
      drawingControlOptions: {
        position: google.maps.ControlPosition.TOP_CENTER,
        drawingModes: [
          /*google.maps.drawing.OverlayType.POLYGON
        ]
      },
      markerOptions: {
        //icon: '//developers.google.com/maps/documentation/javascript/examples/full/images/transparent-1x1.png'
        icon:{
          path: google.maps.SymbolPath.CIRCLE,
          scale: 0
        }
      },
      polygonOptions: {
      fillColor: '#ffff00',
      fillOpacity: 1,
      strokeWeight: 5,
      clickable: false,
      editable: true,
      zIndex: 1
    }
  });
  drawingManager.setMap(map);

  //drawing manager marker click
  google.maps.event.addListener(drawingManager, 'markercomplete', function(event) {
    var latlng = {lat: parseFloat(event.position.lat()), lng: parseFloat(event.position.lng())};
    geocoder.geocode({
      'latLng': latlng
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
          //$('#address').val(results[0].formatted_address);
        }
      }
    });
  });

  //drawing manager marker click
  google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon,place_polygon_path) {
    GMapPolygonToWKT(polygon);
    place_polygon_path=polygon;
    place_polygon_pth=polygon.getPath();
    google.maps.event.addListener(place_polygon_pth, 'set_at', function(){
      GMapPolygonToWKT(place_polygon_path);
    });
    google.maps.event.addListener(place_polygon_pth, 'insert_at', function(){
      GMapPolygonToWKT(place_polygon_path);
    });
  });

  google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
    if (e.type != google.maps.drawing.OverlayType.MARKER) {
    // Switch back to non-drawing mode after drawing a shape.
    drawingManager.setDrawingMode(null);

    // Add an event listener that selects the newly-drawn shape when the user
    // mouses down on it.
    var newShape = e.overlay;
    newShape.type = e.type;
    google.maps.event.addListener(newShape, 'click', function() {
      setSelection(newShape);
    });
    setSelection(newShape);
  }
});*/

  //get map zoom on change
	/*google.maps.event.addListener(map, 'zoom_changed', function() {
		var zoom = map.getZoom();
		$('#map_zoom').val(zoom);
	});*/

  //custom polygon button
  /*poly_btn.addEventListener("click", (() => {
    // closure handles local toggle variables
    let toggled = false;
    const originalHTML = poly_btn.innerHTML;
    return e => {
      if (toggled) {
        drawingManager.setDrawingMode(null);
        e.target.innerHTML = originalHTML;
        e.target.classList.add("custom-cancel-geofence");
      } else {
        drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
        e.target.innerHTML = $('#cancel-btn-txt').text();
        e.target.classList.remove("custom-cancel-geofence");
      }
      toggled = !toggled;
    };
  })());
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(poly_btn);*/
  //custom polygon button
  //polygon code end
}

function generateCircleMap(lat,lng,radius){
    // initialize the map variable
    var map= new google.maps.Map(document.getElementById('map'), {
      scrollwheel: true,
      mapTypeControl: true,
      center: {
        lat: lat,
        lng: lng
      },
      zoom: 10,
      streetViewControl: true,
      zoomControl: true
    });



    google.maps.event.addListener(map, 'zoom_changed', function() {
  		var zoom = map.getZoom();
  		$('#map_zoom').val(zoom);
  	});



    var circle = new google.maps.Circle({
      strokeColor: "#FF0000",
      strokeOpacity: 0.8,
      strokeWeight: 1,
      fillColor: "#FF0000",
      fillOpacity: 0.35,
      draggable: true,
      map: map,
      center: {
        lat: lat,
        lng: lng
      },
      radius: parseInt(radius)
    });
    $('#map_lat').val(circle.getCenter().lat());
    $('#map_lng').val(circle.getCenter().lng());

    google.maps.event.addListener(circle, 'center_changed', function() {
      $('#map_lat').val(circle.getCenter().lat());
      $('#map_lng').val(circle.getCenter().lng());
    });

    map.fitBounds(circle.getBounds());
}

/*This will init circle map*/
function CircleInitMap(lat,lng,radius,zoomVal){
  lat=parseFloat(lat);
  lng=parseFloat(lng);
  radius=parseInt(radius);
  zoomVal=parseInt(zoomVal);
    // initialize the map variable
    var map= new google.maps.Map(document.getElementById('map'), {
      scrollwheel: true,
      mapTypeControl: true,
      center: {
        lat: lat,
        lng: lng
      },
      zoom: zoomVal,
      streetViewControl: false,
      zoomControl: true
    });

    google.maps.event.addListener(map, 'zoom_changed', function() {
      var zoom = map.getZoom();
      $('#map_zoom').val(zoom);
    });

    var circle = new google.maps.Circle({
      strokeColor: "#FF0000",
      strokeOpacity: 0.8,
      strokeWeight: 1,
      fillColor: "#FF0000",
      fillOpacity: 0.35,
      draggable: true,
      map: map,
      center: {
        lat: lat,
        lng: lng
      },
      radius: parseInt(radius)
    });
    //alert(circle.getRadius());
    $('#map_lat').val(circle.getCenter().lat());
    $('#map_lng').val(circle.getCenter().lng());

    google.maps.event.addListener(circle, 'center_changed', function() {
      $('#map_lat').val(circle.getCenter().lat());
      $('#map_lng').val(circle.getCenter().lng());
    });

    map.fitBounds(circle.getBounds());
}
/*This will init circle map*/

//used to convert google txt to wkt
function GMapPolygonToWKT(poly){
  var wkt = "POLYGON(";
  var paths = poly.getPaths();
  for(var i=0; i<paths.getLength(); i++)
  {
  var path = paths.getAt(i);
  // Open a ring grouping in the Polygon Well Known Text
  wkt += "(";
  for(var j=0; j<path.getLength(); j++)
  {
  // add each vertice and anticipate another vertice (trailing comma)
  wkt += path.getAt(j).lng().toString() +" "+ path.getAt(j).lat().toString() +",";
  }
  wkt += path.getAt(0).lng().toString() + " " + path.getAt(0).lat().toString() + "),";
  }
  wkt = wkt.substring(0, wkt.length - 1) + ")";
  poly.setOptions({strokeWeight: 2.0, strokeColor: 'red'});
  $("#polygon_wkt").val(wkt);
}

/*cancel previous polygon*/
function clearSelection() {
  if (selectedShape) {
    selectedShape.setEditable(false);
    selectedShape = null;
  }
}

function setSelection(shape) {
 clearSelection();
 selectedShape = shape;
 shape.setEditable(true);
}

function deleteSelectedShape() {
  if (selectedShape) {
    selectedShape.setMap(null);
    $('#polygon-wkt').val('');
  }
}

$(document).on('click','.custom-cancel-geofence',function(){
    deleteSelectedShape();
});
/*cancel previous polygon*/

/*detail button click*/
$(document).on('click','.btn-view-detail',function(){
  var oid=$(this).data('id');
  $('#viewOrderDetail .modal-body').empty();
  $.ajax({
    url:APP_URL+'/ajax-order-detail/'+oid,
    datatype:'text/html',
    success:function(response){
      if(response){
        $('#viewOrderDetail .order-edit-detail').attr('href',APP_URL+'/orders/'+oid+'/edit')
        $('#viewOrderDetail .modal-body').html(response);
        $('#viewOrderDetail').modal('show');
      }
    }
  });
});
/*detail button click*/

/*print button click*/
$(document).on('click','.btn-view-print',function(){
  var oid=$(this).data('id');
  $.ajax({
    url:APP_URL+'/ajax-order-detail/'+oid,
    datatype:'text/html',
    success:function(response){
      if(response){
        $('#viewOrderDetail .order-edit-detail').attr('href',APP_URL+'/orders/'+oid+'/edit')
        $('#viewOrderDetail .modal-body').html(response);
        setTimeout(function(){
          $('#printOut').click();
        },100);

      }
    }
  });
});
/*detail button click*/


//Google map autocomplete location
function autocompleteLocation() {
  var input = document.getElementById('address');
  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.setFields(
      ['address_components', 'geometry', 'icon', 'name']);

  autocomplete.addListener('place_changed', function() {
    var place = autocomplete.getPlace();
    var radius=$('#radius').val();
    var address = $('#address').val();
    var geocoder = new google.maps.Geocoder();
    if(address && radius){
      geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          var lat = results[0].geometry.location.lat();
          var lng = results[0].geometry.location.lng();
          generateCircleMap(lat,lng,radius);
        }

      });
    }
    if (!place.geometry) { }
  });
}


/*get calls orders*/
function GetCallsOrders(){
  $.ajax({
    url:APP_URL+'/ajax-calls-orders/'+COMPANY_ID,
    datatype:'text/html',
    success:function(response){
      if(response){
        var json=$.parseJSON(response);
        $(json).each(function(k,v){
          var id=json[k].id;
          var status=json[k].status;
          var confirm=json[k].confirm;
          var cancel=json[k].cancel;
          var app_used=json[k].user.app_used;
          if(app_used==1 && cancel==0){
            if(status=='confirm'){
              if(!$('#home_order_'+id+' .btns-td button').hasClass('home-btn-delayed')) {
                if($('#home_order_'+id+' .btns-td button').hasClass('home-btn-completed')) {
                  $('#home_order_'+id+' .btns-td').empty();
                }
                $('#home_order_'+id+' .btns-td').append('<button class="btn btn-primary btn-sm orderDelay home-btn-delayed" data-id="'+id+'" type="button">'+DELAYEDBTNTEXT+'</button>');
              }
              if(!$('#home_order_'+id+' .btns-td button').hasClass('home-btn-detail')) {
                $('#home_order_'+id+' .btns-td').append('<button class="btn btn-success btn-sm btn-view-detail home-btn-detail mt-btn-10" data-id="'+id+'" type="button">'+DETAILDBTNTEXT+'</button><button class="btn btn-warning btn-sm btn-view-print ml-1 mt-btn-10" data-id="'+id+'" type="button">'+PRINTBTNTEXT+'</button>');
              }
            }else if(status=='pending'){
              if(!$('#home_order_'+id+' .btns-td button').hasClass('home-btn-detail')) {
                $('#home_order_'+id+' .btns-td').html('<button class="btn btn-success btn-sm btn-view-detail home-btn-detail mt-btn-10" data-id="'+id+'" type="button">'+DETAILDBTNTEXT+'</button><button class="btn btn-warning btn-sm btn-view-print ml-1 mt-btn-10" data-id="'+id+'" type="button">'+PRINTBTNTEXT+'</button>');
              }
            }
            if(confirm==1){
              if(!$('#home_order_'+id+' .btns-td button').hasClass('home-btn-ready')) {
                $('#home_order_'+id+' .btns-td .orderDelay').after('<button class="btn btn-info btn-sm change-status ml-1 home-btn-ready" data-status="ready" data-id="'+id+'" type="button">'+READYBTNTEXT+'</button>');
            }
          }
        }else{
          $('#home_order_'+id).remove();
        }
        });
      }
    }
  });
}
/*get calls orders*/

/*Get new order for bussiness with ajax*/
function fetchAjaxOrders(){
  $.ajax({
    url:APP_URL+'/ajax-orders/'+COMPANY_ID,
    datatype:'text/html',
    success:function(response){
      $('#dashboardOrder').prepend(response);
      updateAjaxOrders();
    }
  });
}
function updateAjaxOrders(){
  $.ajax({
    url:APP_URL+'/update-ajax-orders/'+COMPANY_ID,
    datatype:'text/html',
    success:function(response){}
  });
}

/*Get spot number for order if exist*/
var getSpotOrderLocate=(oid,spot_number='',locate,spot_color)=>{
  $.ajax({
    url:APP_URL+'/orders/check-spot-exist/'+oid,
    datatype:'json',
    success:function(response){
      var json=$.parseJSON(response);
      if(json.spot_number && !spot_number){
        $('#home_order_'+oid+' td:nth-child(3)').text(json.spot_number);
      }
      if(json.spot_color){
        $('#home_order_'+oid+' td:nth-child(4)').text(json.spot_color);
      }
      $('#home_order_'+oid+' td:nth-child(11)').html(json.order_locate);

      $('#dashboardGeoFenceCount').text(json.geofence_count);
      $('#home_order_'+oid+' td:nth-child(6)').html(json.order_paid);
      $('#home_order_'+oid+' td:nth-child(7)').html(json.order_confirm);
    }
  });
}

/*Get spot number for order if exist
var getOrderLocate=(oid)=>{
    $.ajax({
      url:APP_URL+'/orders/get-order-locate/'+oid,
      datatype:'text/html',
      success:function(response){

      }
    });
}*/
if(FILTERTYPE!='complete'){
  if($('#dashboardTable').length>0){
    setInterval(function(){
        fetchAjaxOrders();
        GetCallsOrders();
    },5000);
  }
}

/*Dashboard stats count*/
function dashboardStats(status){
  if(status=='ready'){
    DASHINPROGRESSCOUNT=DASHINPROGRESSCOUNT-1;
    DASHREADYCOUNT=DASHREADYCOUNT+1;
  }
  if(status=='complete'){
    DASHREADYCOUNT=DASHREADYCOUNT-1;
    DASHGEOFENCECUSTOMERCOUNT=DASHGEOFENCECUSTOMERCOUNT-1;
    DASHCOMPLETEDCOUNT=DASHCOMPLETEDCOUNT+1;
  }
  DASHINPROGRESSCOUNT=(DASHINPROGRESSCOUNT<0)?0:DASHINPROGRESSCOUNT;
  DASHREADYCOUNT=(DASHREADYCOUNT<0)?0:DASHREADYCOUNT;
  DASHCOMPLETEDCOUNT=(DASHCOMPLETEDCOUNT<0)?0:DASHCOMPLETEDCOUNT;
  DASHGEOFENCECUSTOMERCOUNT=(DASHGEOFENCECUSTOMERCOUNT<0)?0:DASHGEOFENCECUSTOMERCOUNT;

  $('#dashboardInProgressCount').text(DASHINPROGRESSCOUNT);
  $('#dashboardReadyCount').text(DASHREADYCOUNT);
  $('#dashboardCompletedCount').text(DASHCOMPLETEDCOUNT);
  $('#dashboardGeoFenceCount').text(DASHGEOFENCECUSTOMERCOUNT);
}

/*Filteration show per page dropdown*/
$('#filterShowPerPage').change(function(){
  var val=$(this).val();
  var currentUrl=window.location.href ;
  var url=updateQueryStringParameter(currentUrl,'per_page',val);
  var url=updateQueryStringParameter(url,'page',1);
  window.location.href=url;
});

function updateQueryStringParameter(uri, key, value) {
  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
  var separator = uri.indexOf('?') !== -1 ? "&" : "?";
  if (uri.match(re)) {
    return uri.replace(re, '$1' + key + "=" + value + '$2');
  }
  else {
    return uri + separator + key + "=" + value;
  }
}
/*Filteration show per page dropdown*/

/*delete selected orders*/
$(document).on('click','#all-dashboard-chkbox',function(){
  var orders = [];
  if($(this).is(':checked')){
    $('#delete-selected-order').show();
    $('.single-chk-box').prop('checked',true);
    $('.single-chk-box').each(function(){
      orders.push($(this).val());
    });
  }else{
    orders = []
    $('#delete-selected-order').hide();
    $('.single-chk-box').prop('checked',false);
  }
  if(orders.length>0){
    orders=orders.toString();
  }
  $('#hiddenOrderId').val(orders);
});

$(document).on('click','.single-chk-box',function(){
  var hiddenOrderId=$('#hiddenOrderId').val();

  if(!hiddenOrderId){
    var orders=[];
  }else{
    var orders=hiddenOrderId.split(",");
  }

  if($(this).is(':checked')){
    orders.push($(this).val());
  }else{
    var index = orders.indexOf($(this).val());
    orders.splice(index, 1);
  }
  if(orders.length>0){
    orders=orders.toString();
    $('#delete-selected-order').show();
  }else{
    $('#delete-selected-order').hide();
  }
  console.log(orders);
  $('#hiddenOrderId').val(orders);
});

$('#delete-selected-order').click(function(){
  if(confirm('Do you want to deleted checked order?')){
    $('#selectedChkboxForm').submit();
  }
});
/*delete selected orders*/

/*cash payment button*/
$(document).on('click','.cash-payment-action',function(){
  var order_number=$(this).parents('tr').find('.hidden-order-number').text();
  var order_id=$(this).parents('tr').find('.single-chk-box').val();
  var order_amount=$(this).parents('tr').find('.hidden-order-amount').text();
  $('#cashPaymentModal #order_number').val(order_number);
  $('#cashPaymentModal #order_id').val(order_id);
  if(order_amount>0){
    $('#cashPaymentModal #amount').val(order_amount);
  }
  var set_paid_url=APP_URL+'/orders/set-order-paid/'+order_id;
  $('#payment-modal-paybtn').attr('href',set_paid_url);
  $('#cashPaymentModal').modal('show');
});

$(document).on('click','#addOrderCashPayment',function(){
  var id=$('#cashPaymentModal #order_id').val();
  var amount=$('#cashPaymentModal #amount').val();
  var status='payment';
  if(amount){
    $.ajax({
      type:'PUT',
      url:APP_URL+'/orders/'+id,
      data:{status:status, _method: "PATCH",amount:amount},
      datatype:'json',
      success:function(response){
        var json=$.parseJSON(response);
        console.log(json);
        if(json.status==true){
          //$(this).prop('disabled',false);
          if(status=='payment'){
            //$(th).parents('tr').removeClass('table-background-red');
            if(json.order_paid){
              $('#home_order_'+id+' td:nth-child(5)').html(json.order_paid);
            }
            $('#home_order_'+id).find('td.eta-td').attr('data-status',status);
            var btn_html='<button class="btn btn-info btn-sm ready-reminder" data-id="'+id+'" type="button">'+REMINDERBTNTEXT+'</button>&nbsp;&nbsp;';
            btn_html+='<button class="btn btn-danger btn-sm change-status home-btn-completed" data-status="complete" data-id="'+id+'" type="button">'+COMPLETEDBTNTEXT+'</button>&nbsp;&nbsp;';
            btn_html+='<button class="btn btn-success btn-sm btn-view-detail home-btn-detail mt-btn-10" data-id="'+id+'" type="button">'+DETAILDBTNTEXT+'</button><button class="btn btn-warning btn-sm btn-view-print ml-1 mt-btn-10" data-id="'+id+'" type="button">'+PRINTBTNTEXT+'</button>';
            $('#home_order_'+id).find('td.btns-td').html(btn_html);
            $('#cashPaymentModal').modal('hide');
          }
        }
      }
    });
  }
});
