@extends('layouts.default')
@section('content')
<div class="row createCategoryPage">
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
		  <header class="card-header font-title">@lang('restaurant_menu.category_form_add_sub_menu_heading') {{$menuDetail->name}}</header>
			<div class="card-body" id="siteAddCardBody">
        {{ Form::open(array('action'=> 'CategoryController@store','files'=>true)) }}
            {{Form::token()}}
						<div class="form-group">
							{{Form::label('menu_id',trans('restaurant_menu.category_listing_form_menu'))}}
							<select class="form-control col-md-6" id="menu_id" name="menu_id" required="true" readonly="true">
								<option value="">@lang('restaurant_menu.category_listing_form_select_menu')</option>
									@foreach(all_menus() as $key=>$menu)
										<option value="{{$menu->id}}" {{ $menuDetail->id == $menu->id ? 'selected' : '' }}>{{$menu->name}}</option>
									@endforeach
							</select>
						</div>
            <div class="form-group">
              {{Form::label('name',trans('site.table_list_name'))}}
              {{Form::text('name','',
                ['class'=>'form-control','placeholder'=>trans('site.placeholder_name'),'required'=>true]
              )}}
            </div>
            <div class="form-group">
                <input type="file" name="image">
            </div>
            <div class="form-group form-check text-center">
                {{Form::submit(trans('restaurant_menu.category_listing_form_save'),["class"=>"btn btn-lg btn-primary btn-valet-submmit valet-submmit-save"])}}
            </div>
          {{ Form::close() }}
			</div>
		</section>
	</div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">

</script>
@endsection
