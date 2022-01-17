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
		  <header class="card-header font-title">@lang('restaurant_menu.table_th_add_menu')</header>
			<div class="card-body" id="siteAddCardBody">
        {{ Form::open(array('action'=> 'RestaurantMenuController@store','files'=>true)) }}
          {{Form::token()}}
          <div class="form-group">
            {{Form::label('name',trans('restaurant_menu.table_th_name'))}}
            {{Form::text('name','',
              ['class'=>'form-control','placeholder'=>'Enter name','required'=>true]
            )}}
          </div>

          <div class="form-group">
						<div class="row">
							<div class="col-md-3">
								{{Form::label('start_time',@trans('restaurant_menu.form_start_time'))}}
		            {{Form::text('start_time','',
		              ['class'=>'form-control eta-time-picker','required'=>true]
		            )}}
							</div>
							<div class="col-md-3">
								{{Form::label('end_time',@trans('restaurant_menu.form_end_time'))}}
		            {{Form::text('end_time','',
		              ['class'=>'form-control eta-time-picker','required'=>true]
		            )}}
							</div>
						</div>
          </div>
          <div class="form-group form-check text-center">
              {{Form::submit(trans('restaurant_menu.form_save_menu_btn'),["class"=>"btn btn-lg btn-primary hey-blue"])}}
          </div>
          {{ Form::close() }}
			</div>
		</section>
	</div>
</div>
@endsection
@section('myScripts')

@endsection
