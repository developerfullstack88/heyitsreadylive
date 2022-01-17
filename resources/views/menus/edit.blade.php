@extends('layouts.default')
@section('content')
<style>
	/*--------------Tasks Widget--------------*/

	.task-content {
		margin-bottom: 30px;
	}

	.tasks-widget .task-content:after {
	clear: both;
	}

	.tasks-widget .task-footer  {
	margin-top: 5px;
	}

	.tasks-widget .task-footer:after,
	.tasks-widget .task-footer:before {
	content: "";
	display: table;
	line-height: 0;
	}

	.tasks-widget .task-footer:after {
	clear: both;
	}

	.tasks-widget  .task-list {
	padding:0;
	margin:0;
	}

	.tasks-widget .task-list > li {
	position:relative;
	padding:10px 5px;
	border-bottom:1px dashed #eaeaea;
	}

	.tasks-widget .task-list  li.last-line {
	border-bottom:none;
	}

	.tasks-widget .task-list  li > .task-bell  {
	margin-left:10px;
	}

	.tasks-widget .task-list  li > .task-checkbox {
	float:left;
	width:30px;
	}

	.tasks-widget .task-list  li > .task-title  {
	overflow:hidden;
	margin-right:10px;
	}

	.tasks-widget .task-list  li > .task-config {
	position:absolute;
	top:10px;
	right:10px;
	}

	.tasks-widget .task-list  li .task-title .task-title-sp  {
	margin-right:5px;
	}

	.tasks-widget .task-list  li.task-done .task-title-sp  {
	text-decoration:line-through;
	color: #bbbbbb;
	}

	.tasks-widget .task-list  li.task-done  {
	background:#f6f6f6;
	}

	.tasks-widget .task-list  li.task-done:hover {
	background:#f4f4f4;
	}

	.tasks-widget .task-list  li:hover  {
	background:#f9f9f9;
	}

	.tasks-widget .task-list  li .task-config {
	display:none;
	}

	.tasks-widget .task-list  li:hover > .task-config {
	display:block;
	margin-bottom:0 !important;
	}


	@media only screen and (max-width: 320px) {

	.tasks-widget .task-config-btn {
		float:inherit;
		display:block;
	}

	.tasks-widget .task-list-projects li > .label {
		margin-bottom:5px;
	}

	}

</style>
<div class="row" id="createMenuPage">
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
		  <header class="card-header font-title">@lang('restaurant_menu.dishes_edit_for') {{$categoryDetail->name}}</header>
			<div class="card-body">
        {{ Form::model($menu,array('action'=> array('MenuController@update',$menu->id),'files'=>true)) }}
         @method('PUT')
          {{Form::token()}}
          <div class="form-group">
            {{Form::label('name', trans('site.table_list_name'))}}
            {{Form::text('name',$menu->name,
              ['class'=>'form-control','placeholder'=>trans('site.placeholder_name'),
              'required'=>true]
            )}}
          </div>
					<div class="form-group">
						{{Form::label('description',trans('restaurant_menu.dishes_listing_form_description'))}}
						{{Form::textarea('description',$menu->description,
							['class'=>'form-control','placeholder'=>'Enter description','required'=>true]
						)}}
					</div>
          <div class="form-group">
						@if($menu->image_path)
            	<img class="menu-edit-img" src="{{asset($menu->image_path)}}"/>
						@endif
          </div>
          <div class="form-group">
              <input type="file" name="image">
          </div>
					<div class="form-group">
							{{Form::label('amount', trans('dashboard.table_th_amount'))}}
							<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text">$</span>
							</div>
							<input class="form-control" placeholder="{{trans('restaurant_menu.dishes_listing_form_placeholder_amount')}}" name="amount" type="number" id="amount" step="0.01" value="{{$menu->amount}}"/>
						</div>
					</div>
					<!--Quantity code version 2.5
					<div class="form-group">
						@php
						$totalCartQuantity=checkCartByItemForAdmin($menu->id);
						$leftQuantity=$menu->quantity-$totalCartQuantity;
						if($leftQuantity<0){
							$leftQuantity=0;
						}
						@endphp
						{{Form::label('quantity','Quantity')}}
						{{Form::text('quantity',$leftQuantity,
							['class'=>'form-control','placeholder'=>'Enter quantity','required'=>true]
						)}}
					</div>-->
					<input type="hidden" id="extra-chkbox-id" name="extras" value="{{$menu->extras}}">
					<div class="form-group">
						{{Form::label('extra_free',trans('restaurant_menu.dishes_listing_form_free_items'))}}
						{{Form::text('extra_free',$menu->extra_free,
							['class'=>'form-control','placeholder'=>trans('restaurant_menu.dishes_listing_form_free_items_placeholder')]
						)}}
					</div>
					<div class="row">
								<div class="col-md-12">
										<section class="card tasks-widget">
												<header class="card-header">@lang('dashboard.order_detail_pop_extras')</header>
												<div class="card-body">
													<div class="task-content">
														<ul class="task-list" id="extra-category-list">
															@php
																$itemExtras=explode(",",$menu->extras);
															@endphp
															@if($extras)
																@foreach($extras as $extra)
																		<li class="">
																			<div class="task-checkbox">
																				<input type="checkbox" class="extra-chkbox-full" value="{{$extra->id}}"
																				{{($itemExtras && in_array($extra->id,$itemExtras))?'checked':''}}>
																			</div>
																			<div class="task-title">
																				<span class="task-title-sp">{{$extra->name}}</span>
																			</div>
																		</li>
																@endforeach
															@endif
														</ul>
													</div>
												</div>
										</section>
									</div>
						</div>
			</div>
      <div class="form-group form-check text-center">
          {{Form::submit(trans('restaurant_menu.dishes_listing_form_save_item'),["class"=>"btn btn-lg btn-primary btn-valet-submmit valet-submmit-save"])}}
      </div>
      {{ Form::close() }}
		</section>
	</div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
@if($menu->extras)
	var fullExtrachkArr='{!! $menu->extras !!}';
	if(fullExtrachkArr){
		fullExtrachkArr=fullExtrachkArr.split(',');
	}
@else
	var fullExtrachkArr=[];
@endif

$(document).on('click','.extra-chkbox-full',function(){
	if($(this).is(':checked')){
		fullExtrachkArr.push($(this).val());
	}else{
		var removeItem=$(this).val();
		fullExtrachkArr = $.grep(fullExtrachkArr, function(value) {
			return value != removeItem;
		});
	}
	$('#extra-chkbox-id').val(fullExtrachkArr);
});
</script>

@endsection
