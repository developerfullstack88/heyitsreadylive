@extends('layouts.default')
@section('content')
<div class="row" id="createCategoryPage">
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
		  <header class="card-header font-title">@lang('restaurant_menu.extras_listing_form_edit_extras_for') {{$categoryDetail->name}}</header>
			<div class="card-body">
        {{ Form::model($extraDetail,array('action'=> array('ExtraController@update',$extraDetail->id),'files'=>true)) }}
         @method('PUT')
        {{Form::token()}}
        <div class="form-group">
          {{Form::label('name',trans('restaurant_menu.table_th_name'))}}
          {{Form::text('name',$extraDetail->name,
            ['class'=>'form-control','placeholder'=>'Enter name','required'=>true]
          )}}
        </div>
				<!--Quantity code version 2.5
        <div class="form-group">
					@php
						$usedQuantity=checkExtraQuantityForAdmin($extraDetail->id);
						$leftQuantity=$extraDetail->quantity-$usedQuantity;
						$leftQuantity=($leftQuantity>0)?$leftQuantity:0
					@endphp
          {{Form::label('quantity','Quantity')}}
          {{Form::text('quantity',$leftQuantity,
            ['class'=>'form-control','placeholder'=>'Enter quantity','required'=>true]
          )}}
        </div>-->
        <div class="form-group">
            {{Form::checkbox('is_free', 1,($extraDetail->is_free==1)?true:false,['id'=>'is_free_chk'])}}
          {{Form::label('is_free', trans('restaurant_menu.extras_listing_form_eligible'))}}
        </div>
        <div class="form-group">
            {{Form::label('price', trans('dashboard.table_th_amount'))}}
            <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
						<input type="number" class="form-control" placeholder="" title="Please enter only numeric" name="price" value="{{$extraDetail->price}}" id="price" step="0.01"/>
          </div>
        </div>
			</div>
      <div class="form-group form-check text-center">
          {{Form::submit(trans('restaurant_menu.extras_listing_form_save_extra_btn'),["class"=>"btn btn-lg btn-primary btn-valet-submmit valet-submmit-save"])}}
      </div>
      {{ Form::close() }}
		</section>
	</div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
$(document).ready(function(){
  /*var is_free='{!! $extraDetail->is_free !!}';
  if(is_free==1){
    $('#price').attr('disabled',true);
  }else{
    $('#price').attr('disabled',false);
  }
  $(document).on('click','#is_free_chk',function(){
    if($(this).is(':checked')){
      $('#price').attr('disabled',true);
    }else{
      $('#price').attr('disabled',false);
    }
  });*/
	/*$(document).on('blur','#price',function(){
		var value=$(this).val();
		if(value && value<=0){
			alert("Amount must be greater than 0");
			$(this).val('');
		}
	});*/
});
</script>

@endsection
