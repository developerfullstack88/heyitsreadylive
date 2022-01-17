<!--Set eta Modal-->
<div class="modal fade " id="setEtaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          {{ Form::open(array('action'=> 'OrderController@setEta','id'=>'etaForm')) }}
            <div class="modal-header">
                <h5 class="modal-title" id="setEtaModal">@lang('dashboard.eput_modal_heading')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ Form::hidden('order_id', '',["id"=>"orderId"]) }}
                <div class="form-group">
                  {{Form::radio('eta_radio','datetime',false,['class'=>'eta-radio-btn'])}}
      						{{Form::label('eta_radio', trans('dashboard.eput_modal_eta_datetime_radio'))}}
      						{{Form::radio('eta_radio', 'minutes',true,['class'=>'eta-radio-btn'])}}
      						{{Form::label('eta_radio', trans('dashboard.eput_modal_eta_minute_radio'))}}
                </div>
      					<div class="form-group">
                  {{Form::label('eta', trans('dashboard.eput_modal_eta_label'))}}
                  {{Form::text('eta','',
                    ['class'=>'form-control set-eta-picker','placeholder'=>'','id'=>'etaDateTxt2']
                  )}}
                </div>
      					<div class="form-group">
                  {{Form::label('eta', trans('dashboard.eput_modal_eta_datetime_radio'))}}
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
                    ['class'=>'form-control','placeholder'=>trans('dashboard.eput_modal_eta_placeholder'),'id'=>'etaMinutesTxt2']
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
            </div>
            <div class="modal-footer">
                {{Form::button(trans('common.close_btn_txt'),["class"=>"btn btn-secondary","data-dismiss"=>"modal"])}}
                {{Form::button(trans('common.save_btn_txt'),["class"=>"btn btn-primary","id"=>"etaSubmit"])}}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--Set eta Modal-->


<!--Reset eta Modal-->
<div class="modal fade " id="resetEtaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          {{ Form::open(array('action'=> 'OrderController@setEta','id'=>'resetEtaForm')) }}
            <div class="modal-header">
                <h5 class="modal-title" id="setEtaModal">@lang('dashboard.reseteput_modal_heading')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ Form::hidden('order_id', '',["id"=>"orderId"]) }}
                <div class="form-group">
                  {{Form::radio('eta_radio','datetime',false,['class'=>'eta-radio-btn'])}}
      						{{Form::label('eta_radio', trans('dashboard.eput_modal_eta_datetime_radio'))}}
      						{{Form::radio('eta_radio', 'minutes',true,['class'=>'eta-radio-btn'])}}
      						{{Form::label('eta_radio', trans('dashboard.eput_modal_eta_minute_radio'))}}
                </div>
      					<div class="form-group">
                  {{Form::label('eta', trans('dashboard.eput_modal_eta_label'))}}
                  {{Form::text('eta','',
                    ['class'=>'form-control set-eta-picker','placeholder'=>'','id'=>'etaDateTxt']
                  )}}
                </div>
      					<div class="form-group">
                  <p class="reset-eta-modal-p-min"><b>@lang('dashboard.reseteput_modal_eta_min_note')</b></p>
                  {{Form::label('eta', trans('dashboard.eput_modal_eta_datetime_radio'))}}
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
                    ['class'=>'form-control','placeholder'=>trans('dashboard.eput_modal_eta_placeholder'),'id'=>'etaMinutesTxt']
                  )}}
                </div>
            </div>
            <div class="modal-footer">
                {{Form::button(trans('common.close_btn_txt'),["class"=>"btn btn-secondary","data-dismiss"=>"modal"])}}
                {{Form::button(trans('common.save_btn_txt'),["class"=>"btn btn-primary","id"=>"resetEtaSubmit"])}}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--Reset eta Modal-->

<!--Detail button dashboard Modal-->
<div class="modal fade " id="viewOrderDetail" tabindex="-1" role="dialog" aria-labelledby="orderDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-4">
                  <h5 class="modal-title" id="setEtaModal">
                    @lang('dashboard.order_detail_modal_heading')
                  </h5>
                </div>
                <div class="col-md-4">
                  <a href="" class="order-edit-detail hide-print-link">
                    @lang('common.edit_label')
                  </a>
                </div>
                <div class="col-md-4">
                  <a id="printOut" href="javascript:void(0);" class="order-edit-detail hide-print-link">@lang('dashboard.table_print_action_btn')</a>
                  <button type="button" class="close hide-print-link" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!--Detail button dashboard Modal-->
<div class="modal fade" id="freeTrialExpire" tabindex="-1" role="dialog" aria-labelledby="orderDetailLabel"
aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Free Trial Expired</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <p>You may continue to use Hey It's Ready for FREE for the Menu QR Code only.</p>
              <p>If you wish to continue using Hey It's Ready full version, please go to “Settings” and select “Enable”.</p>
            </div>
            <div class="modal-footer">
              <a href="{{route('itemQr',[0])}}" class="btn btn-secondary">@lang('common.close_btn_txt')</a>
            </div>
        </div>
    </div>
</div>

<!--Cash Payment Modal-->
<div class="modal fade " id="setEtaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          {{ Form::open(array('action'=> 'OrderController@setEta','id'=>'etaForm')) }}
            <div class="modal-header">
                <h5 class="modal-title" id="setEtaModal">@lang('dashboard.eput_modal_heading')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ Form::hidden('order_id', '',["id"=>"orderId"]) }}
                <div class="form-group">
                  {{Form::radio('eta_radio','datetime',false,['class'=>'eta-radio-btn'])}}
      						{{Form::label('eta_radio', trans('dashboard.eput_modal_eta_datetime_radio'))}}
      						{{Form::radio('eta_radio', 'minutes',true,['class'=>'eta-radio-btn'])}}
      						{{Form::label('eta_radio', trans('dashboard.eput_modal_eta_minute_radio'))}}
                </div>
      					<div class="form-group">
                  {{Form::label('eta', trans('dashboard.eput_modal_eta_label'))}}
                  {{Form::text('eta','',
                    ['class'=>'form-control set-eta-picker','placeholder'=>'','id'=>'etaDateTxt2']
                  )}}
                </div>
      					<div class="form-group">
                  {{Form::label('eta', trans('dashboard.eput_modal_eta_datetime_radio'))}}
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
                    ['class'=>'form-control','placeholder'=>trans('dashboard.eput_modal_eta_placeholder'),'id'=>'etaMinutesTxt2']
                  )}}
                </div>

            </div>
            <div class="modal-footer">
                {{Form::button(trans('common.close_btn_txt'),["class"=>"btn btn-secondary","data-dismiss"=>"modal"])}}
                {{Form::button(trans('common.save_btn_txt'),["class"=>"btn btn-primary","id"=>"etaSubmit"])}}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--Set eta Modal-->
<div class="modal fade " id="cashPaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="order_number">Order Number</label>
                <input class="form-control" type="text" value="" id="order_number" readonly="">
                <input class="form-control" type="hidden" value="" id="order_id">
              </div>
              <div class="form-group">
            	<label for="amount">Amount</label>
							<div class="input-group mb-3">
						  <div class="input-group-prepend">
						    <span class="input-group-text">$</span>
						  </div>
							<input class="form-control" placeholder="Enter Amount" name="amount" type="text" value="" id="amount">
						</div>
					</div>
            </div>
            <div class="modal-footer">
              {{Form::button(trans('common.close_btn_txt'),["class"=>"btn btn-secondary","data-dismiss"=>"modal"])}}
              <a class="btn btn-success" href="javascript:void(0);" id="payment-modal-paybtn">Pay</a>
              {{Form::button('Submit',["class"=>"btn btn-primary","id"=>"addOrderCashPayment"])}}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--Set eta Modal-->
