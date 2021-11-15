@extends('layouts.default')

@section('content')
<div class="row" id="createOrderPage">
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
		  <header class="card-header font-title">@lang('add_order.add_heading')</header>
			<div class="card-body">
				<div class="row ml-1 d-none">
						{{Form::label('',trans('add_order.does_customer_use_app'))}}
				</div>
        {{ Form::open(array('action'=> 'OrderController@store','id'=>'orderForm')) }}
          {{Form::token()}}
					<div class="row ml-1 d-none">
	          <div class="form-group">
							<div class="radio-div" style="display:none;">
								{{Form::radio('app_used',1,false,['class'=>'app_used_radio','id'=>'app_used-0'])}}
								<label for="new-order-user-app-yes">@lang('setting.delete_complete_yes_option')</label>
							</div>
							{{Form::button(trans('setting.delete_complete_yes_option'),["class"=>"btn btn-default order-app-button"])}}
						</div>
						<div class="form-group ml-2">
							<div class="radio-div" style="display:none;">
								{{Form::radio('app_used',0,false,['class'=>'app_used_radio','id'=>'app_used-1'])}}
								<label for="new-order-user-app-no">@lang('setting.delete_complete_no_option')</label>
							</div>
							{{Form::button(trans('setting.delete_complete_no_option'),["class"=>"btn btn-default order-app-button"])}}
						</div>
	        </div>
          <div class="form-group">
						<div class="row">
							<div class="col-md-4">
								{{Form::label('order_number', trans('dashboard.table_th_order_number'))}}
		            {{Form::text('order_number',$orderId,
		              ['class'=>'form-control text-primary','placeholder'=>'','required'=>true,'readonly'=>true]
		            )}}
							</div>
							<div class="col-md-2">
								<label></label>
								@if($phoneCode)
									{{Form::text('phone_code',$phoneCode,
											['class'=>'form-control mt-3','placeholder'=>'Phone code','required'=>true,'readonly'=>true]
										)}}
								@else
									{{ Form::hidden('phone_code',$phoneCode) }}
								@endif
							</div>
							<div class="col-md-4">
								{{Form::label('phone_number', trans('dashboard.table_th_cell_number'))}}
								{{Form::text('phone_number','',
		              ['class'=>'form-control mt-1','id'=>'phone_number',
									'placeholder'=>trans('dashboard.table_th_cell_number'),'required'=>true]
		            )}}
							</div>
						</div>
          </div>
					<div class="form-group d-none">

						<div class="row">

							<div class="col-md-10">

	            </div>
						</div>

          </div>
          <div class="form-group">
            {{Form::label('name', trans('site.table_list_name'))}}
            {{Form::text('name','',
              ['class'=>'form-control','placeholder'=>trans('add_order.placeholder_name'),'required'=>true]
            )}}
          </div>
          <div class="form-group order-create-element order-create-element-email d-none">
            {{Form::label('email', trans('add_order.label_email'))}}
            {{Form::email('email','',
              ['class'=>'form-control','placeholder'=>trans('add_order.placeholder_email')]
            )}}
          </div>
					<div class="form-group">
            {{Form::radio('eta_radio','datetime',false,['class'=>'eta-radio-btn eta-radio-btn-order'])}}
						{{Form::label('eta_radio', trans('dashboard.eput_modal_eta_datetime_radio'))}}
						{{Form::radio('eta_radio', 'minutes',true,['class'=>'eta-radio-btn eta-radio-btn-order'])}}
						{{Form::label('eta_radio', trans('dashboard.eput_modal_eta_minute_radio'))}}
          </div>
					<div class="form-group">
            {{Form::label('eta', trans('dashboard.table_th_eput'))}}
            {{Form::text('eta','',
              ['class'=>'form-control set-eta-picker','placeholder'=>'','id'=>'etaDateTxt']
            )}}
          </div>
					<div class="form-group">
            {{Form::label('eta', trans('dashboard.eput_modal_eta_minute_radio'))}}
						{{Form::button('15 Min',["class"=>"btn btn-lg btn-primary btn-sm quick-min-btn"])}}
						{{Form::button('20 Min',["class"=>"btn btn-lg btn-primary btn-sm quick-min-btn"])}}
						{{Form::button('25 Min',["class"=>"btn btn-lg btn-primary btn-sm quick-min-btn"])}}
						{{Form::button('30 Min',["class"=>"btn btn-lg btn-primary btn-sm quick-min-btn"])}}
						{{Form::button('35 Min',["class"=>"btn btn-lg btn-primary btn-sm quick-min-btn"])}}
						{{Form::button('40 Min',["class"=>"btn btn-lg btn-primary btn-sm quick-min-btn"])}}
						{{Form::button('45 Min',["class"=>"btn btn-lg btn-primary btn-sm quick-min-btn"])}}
						{{Form::button('50 Min',["class"=>"btn btn-lg btn-primary btn-sm quick-min-btn"])}}
						{{Form::button('55 Min',["class"=>"btn btn-lg btn-primary btn-sm quick-min-btn"])}}
						{{Form::button('1 Hour',["class"=>"btn btn-lg btn-primary btn-sm quick-min-btn"])}}
            {{Form::text('eta','',
              ['class'=>'form-control','placeholder'=>trans('dashboard.eput_modal_eta_placeholder'),
							'id'=>'etaMinutesTxt','required'=>true]
            )}}
          </div>
					<div class="form-group">
            	{{Form::label('amount', trans('dashboard.table_th_amount'))}}
							<div class="input-group mb-3">
						  <div class="input-group-prepend">
						    <span class="input-group-text">$</span>
						  </div>
							@php
							$userInfo=getUserInfo(auth()->user()->id);
							@endphp
							{{Form::text('amount','',
								['class'=>'form-control',
								'placeholder'=>trans('add_order.placeholder_amount'),
								'disabled'=>($userInfo->connect_account_id)?false:true
								]
							)}}
						</div>
					</div>
					{{ Form::hidden('user_id','',['id'=>'userId']) }}
			</div>
      <div class="form-group form-check text-center">
					<a href="{{route('home')}}" class="btn btn-lg btn-default"/>@lang('common.cancel_btn')</a>
          {{Form::submit(trans('add_order.save_order_btn'),["class"=>"btn btn-lg btn-primary hey-blue","id"=>"orderCreateSubmit"])}}
      </div>
      {{ Form::close() }}
		</section>
	</div>
</div>
@endsection

@section('myScripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('#phone_number').mask('(000) 000-0000');
		//$('#amount').maskNumber();
		$(document).on('click','.order-app-button',function(){
			var btnText=$(this).text();
			$('.order-create-element').show();
			if(btnText.indexOf('es')>0){
				$(this).attr('class','btn btn-primary order-app-button');

				var noBtn=$(this).parents('.form-group').next().find('.order-app-button');
				noBtn.attr('class','btn btn-default order-app-button');
				$(this).parents('.form-group').find('.radio-div input').prop('checked',true);

				$('.order-create-element-email').hide();
			}else{
				$(this).attr('class','btn btn-info order-app-button');

				var yesBtn=$(this).parents('.form-group').prev().find('.order-app-button');
				yesBtn.attr('class','btn btn-default order-app-button');
				$(this).parents('.form-group').find('.radio-div input').prop('checked',true);

				$('.order-create-element-email').show();
			}
			$('#etaMinutesTxt').parents('.form-group').show();
		  $('#etaMinutesTxt').attr('disabled',false);
		});
		/*$(document).on('click','.app_used_radio',function(){
			$('.order-create-element').show();
			if($(this).val()=='1'){
				$('.order-create-element-email').hide();
			}else{
				$('.order-create-element-email').show();
			}
			$('#etaMinutesTxt').parents('.form-group').show();
		  $('#etaMinutesTxt').attr('disabled',false);
		});*/
		$(document).on('click','#orderCreateSubmit',function(){
			if($('#orderForm')[0].checkValidity()){
				$(this).hide();
			}else{
				$(this).show();
			}
		});
	});
</script>
@endsection
