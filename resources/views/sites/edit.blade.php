@extends('layouts.default')
@section('content')
<div class="row createSitePage">
	<div class="col-lg-offset-2 col-lg-8 frmctr">
    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif
	  <section class="card">
		  <header class="card-header font-title">@lang('site.edit_site_heading')</header>
			<div class="card-body">
        {{ Form::model($site,array('action'=> array('SiteController@update',$site->id),'files'=>true)) }}
         @method('PUT')
          {{Form::token()}}
          <div class="form-group">
            {{Form::label('name',trans('site.business_name_label'))}}
						<i class="fas fa-question-circle"
						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
            {{Form::text('name',getBusinessName(),
              ['class'=>'form-control','placeholder'=>trans('site.placeholder_name'),
              'required'=>true,'readonly'=>true]
            )}}
          </div>
					@if($site->cover_image_thumbnail)
						<div class="form-group">
	            <img class="menu-edit-img" src="{{asset($site->cover_image_thumbnail)}}"/>
	          </div>
					@endif
					<div class="form-group">
						{{Form::label('cover_image',trans('site.site_cover_image'))}}
						<i class="fas fa-question-circle"
						data-toggle="tooltip" data-placement="top" title="Add content here." data-container="body"></i>
						{{Form::file('cover_image',[])}}
					</div>
					<div>
            {{Form::label('category', 'Category')}}
						<i class="fas fa-question-circle"
						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
						<select class="form-control custom-select mb-3" name="category" required="true">
							<option value="">Select Category</option>
								@foreach(categories_options() as $category)
									<option value="{{$category}}" {{ old('category') == $category || $site->category == $category? 'selected' : '' }}>{{$category}}</option>
								@endforeach
              </select>
          </div>
					<div class="form-group d-none">
            {{Form::label('radius',trans('Radius'))}}
            {{Form::text('radius',$site->radius,
              ['class'=>'form-control','placeholder'=>'Enter radius','required'=>true,'onchange'=>'getaddress(event);']
            )}}
          </div>
          <div class="form-group">
            {{Form::label('address',trans('site.address_label'))}}
						<i class="fas fa-question-circle"
						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
            {{Form::text('address',$site->address,
              ['class'=>'form-control','placeholder'=>trans('site.placeholder_location'),'onblur'=>'getaddress(event);']
            )}}
          </div>
          <span style="display:none;">
            {{Form::button(trans('site.edit_geo_fence_btn'),
            ["type"=>"button","class"=>"btn btn-primary hey-blue","id"=>"custom-BtnPolygon"]
            )}}
  				</span>
          <span class="invisible">
            {{Form::textarea('polygon_wkt',$site->polygon_wkt,['id'=>'polygon_wkt','class'=>'form-control'])}}
  				</span>
					<span id="cancel-btn-txt" style="display:none;">@lang('site.cancel_btn_text')</span>
			</div>
		</section>
		<!--Map section
		<div>
			<ul class="nav nav-tabs" role="tablist">
	    	<li class="nav-item">
	      	<a class="nav-link active" data-toggle="pill" href="#mapShow">Show Polygon</a>
	    	</li>
	    	<li class="nav-item">
	      	<a class="nav-link" data-toggle="pill" href="#mapedit">New Polygon</a>
	    	</li>
  		</ul>
			<!-- Tab panes -
		  <div class="tab-content"><br>
		    <div id="mapShow" class="container tab-pane active">
					<div id="map2" class="map"></div>
		    </div>
		    <div id="mapedit" class="container tab-pane fade">
					<div id="map" class="map"></div>
		    </div>
		  </div>
		</div>-->
		<!--Map section-->
		<div id="map" class="map"></div>
    <div class="form-group form-check text-center">
        {{Form::submit(trans('site.save_site_btn'),["class"=>"btn btn-lg btn-primary hey-blue"])}}
				{{ Form::hidden('map_zoom',$site->map_zoom,['id'=>'map_zoom']) }}
				{{ Form::hidden('lat', 10,['id'=>'map_lat']) }}
				{{ Form::hidden('lng', 10,['id'=>'map_lng']) }}
    </div>
    {{ Form::close() }}
	</div>
</div>
<style>#map2{height: 500px;width: 100%;}</style>
@endsection
@section('myScripts')
<script type="text/javascript">
  var address="<?php echo $site->address?>";
  var place_polygon_path;
	var place_polygon_path;
	var selectedShape;
  $(document).ready(function(){
      //generateMap(address);
      $('#geofenceForm').disableAutoFill({
        randomizeInputName: true
      });
			var mapZoomValue='<?php echo ($site->map_zoom)?$site->map_zoom:14?>';
			var mapLat='<?php echo ($site->lat)?$site->lat:''?>';
			var mapLng='<?php echo ($site->lng)?$site->lng:''?>';
			var mapRadius='<?php echo ($site->radius)?$site->radius:''?>';
			//initMap(mapZoomValue);
			CircleInitMap(mapLat,mapLng,mapRadius,mapZoomValue);
  });

  //get address
  function getaddress(e){
		var radius=$('#radius').val();
		var address = $('#address').val();
		var geocoder = new google.maps.Geocoder();
		if(!radius){
			alert('Radius is required field');
			return false;
		}
		if(address && radius){
			geocoder.geocode( { 'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					var lat = results[0].geometry.location.lat();
					var lng = results[0].geometry.location.lng();
					generateCircleMap(lat,lng,radius);
				}

			});
		}
      //generateMap(address)
  }
	google.maps.event.addDomListener(window, 'load', autocompleteLocation);

	/*This will used for show polygon
		function initMap(mapZoomValue) {
			let map2;
			var fenceLat='<?php //(float)$site->lat?>';
			var fenceLng='<?php //(float)$site->lng?>';

			map2 = new google.maps.Map(document.getElementById("map2"), {
				zoom: parseInt(mapZoomValue),
				center: { lat:parseFloat(fenceLat), lng:parseFloat(fenceLng)},
				//mapTypeId: "terrain",
			});

			var polygonWkt="<?php //$polygonWkt?>";
			var triangleCoords = [];
			if(polygonWkt){
				polygonWktArr=polygonWkt.split(',');
				if(polygonWktArr){
					for(var i=0;i<polygonWktArr.length;i++){
						var polyValue=(polygonWktArr[i]).split(" ");
						triangleCoords.push({lat:parseFloat(polyValue[1]),lng:parseFloat(polyValue[0])});
					}
				}
			}
			const bermudaTriangle = new google.maps.Polygon({
				paths: triangleCoords,
				strokeColor: "#FF0000",
				strokeOpacity: 0.8,
				strokeWeight: 3,
				fillColor: "#FF0000",
				fillOpacity: 0.35,
			});
			bermudaTriangle.setMap(map2);
		}
		/*This will used for show polygon*/
</script>

@endsection
