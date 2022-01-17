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
		  <header class="card-header font-title">@lang('site.add_site_heading')</header>
			<div class="card-body" id="siteAddCardBody">
        {{ Form::open(array('action'=> 'SiteController@store','files'=>true)) }}
          {{Form::token()}}
          <div class="form-group tooltip-wrapper">
            {{Form::label('name',trans('site.business_name_label'))}}
						<i class="fas fa-question-circle"
						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
            {{Form::text('name',getBusinessName(),
              ['class'=>'form-control','placeholder'=>'Enter business name','required'=>true,'readonly'=>true]
            )}}
          </div>
					<div class="form-group">
						{{Form::label('cover_image',trans('site.site_cover_image'))}}
						<i class="fas fa-question-circle"
						data-toggle="tooltip" data-placement="top" title="Add content here." data-container="body"></i>
						{{Form::file('cover_image',['required'=>false])}}
					</div>
					<div>
            {{Form::label('category', trans('site.site_category'))}}
						<i class="fas fa-question-circle"
						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
						<select class="form-control custom-select mb-3" name="category" required="true">
							<option value="">Select Category</option>
								@foreach(categories_options() as $category)
									<option value="{{$category}}" {{ old('category') == $category ? 'selected' : '' }}>{{$category}}</option>
								@endforeach
              </select>
          </div>
					<div>
            {{Form::label('manager_id', trans('site.site_manager_label'))}}
						<i class="fas fa-question-circle"
						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
						<select class="form-control custom-select mb-3" name="manager_id">
							<option value="">Select Manager</option>
								@foreach(all_company_managers() as $manager)
									<option value="{{$manager->id}}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
										{{$manager->name}}
									</option>
								@endforeach
              </select>
          </div>
					<div class="form-group d-none">
            {{Form::label('radius',trans('Radius'))}}
            {{Form::text('radius',75,
              ['class'=>'form-control','placeholder'=>'Enter radius','required'=>true,'onblur'=>'getaddress(event);']
            )}}
          </div>
          <div class="form-group">
            {{Form::label('address', trans('site.address_label'))}}
						<i class="fas fa-question-circle"
						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
            {{Form::text('address','',
              ['class'=>'form-control','placeholder'=>trans('site.placeholder_location'),'required'=>true]
            )}}
          </div>
          <span style="display:none;">
            {{Form::button(trans('site.create_geo_fence_btn'),
            ["type"=>"button","class"=>"btn btn-primary hey-blue","id"=>"custom-BtnPolygon"]
            )}}
  				</span>
          <span class="invisible">
            {{Form::textarea('polygon_wkt','',['id'=>'polygon_wkt','class'=>'form-control','required'=>false])}}
  				</span>
					<span id="cancel-btn-txt" style="display:none;">@lang('site.cancel_btn_text')</span>
			</div>
		</section>
    <div id="map" class="map"></div>
    <div class="form-group form-check text-center">
        {{Form::submit(trans('site.save_site_btn'),["class"=>"btn btn-lg btn-primary hey-blue"])}}
				{{ Form::hidden('map_zoom', 10,['id'=>'map_zoom']) }}
				{{ Form::hidden('lat', 10,['id'=>'map_lat']) }}
				{{ Form::hidden('lng', 10,['id'=>'map_lng']) }}

    </div>
    {{ Form::close() }}
	</div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
  var address='<?php echo $cityName?>';
  var place_polygon_path;
	var place_polygon_path;
	var selectedShape;
  $(document).ready(function(){
      generateMap(address);
      $('#geofenceForm').disableAutoFill({
        randomizeInputName: true
      });
  });

  //get address
  function getaddress(e){
		  if(!radius){
				alert('Radius is required field');
				return false;
			}
			//generateMap(address);
  }
	google.maps.event.addDomListener(window, 'load', autocompleteLocation);

	/*$('input[type="submit"]').on("click",function() {
  	var poly=$('#polygon_wkt').val();
		if(!poly){
			alert('please create geofence by clicking on Create a Geo-fence button');
			return false;
		}
	});*/
</script>
@endsection
