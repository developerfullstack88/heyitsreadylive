@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <section class="card">
      <div class="card-body">
        <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
              <li class="nav-item font-title">
                  <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"> <i class="fa fa-home pr-2"></i>@lang('setting.general_tab')</a>
              </li>
              @if($allCards)
              <li class="nav-item font-title">
                  <a class="nav-link" id="billing-tab" data-toggle="tab" href="#billing" role="tab" aria-controls="billing" aria-selected="false">@lang('setting.billing_tab')</a>
              </li>
              @endif
              <li class="nav-item font-title">
                  <a class="nav-link" id="accept-payments-tab" data-toggle="tab" href="#acceptPaymentsTab" role="tab" aria-controls="billing" aria-selected="false">@lang('setting.accept_payment_tab')</a>
              </li>
              <li class="nav-item font-title">
                  <a class="nav-link" id="change-language-tab" data-toggle="tab" href="#changeLanguageTab" role="tab" aria-controls="billing" aria-selected="false">@lang('setting.language_tab')</a>
              </li>
          </ul>
          <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="col-lg-12">
                  <div class="row generalSettings font-title">
                    @lang('setting.delete_complete_heading')
                    <i class="fas fa-question-circle ml-1 mt-1"
        						data-toggle="tooltip" data-placement="right" title="By selecting “Yes” this will display a button on your dashboard that will allow you to delete completed orders in the dashboard. If you select “No” then all you need to do is refresh the browser to remove “Completed Orders” from the dashboard." data-container="body"></i>
                  </div>
                </div>
                <div class="row">
                  {{ Form::open(array('route' => 'settings.deleteCompleteOrder')) }}
                  <input type="hidden" name="_method" value="PUT"/>
                  <div class="col-md-12 mt-2 ml-3 generalSettingsForm">
                      <select class="form-control custom-select" id="delete_complete_order" name="delete_complete_order">
                      <option value="">@lang('setting.empty_option_label')</option>
                      <option value="1" {{ (currentUser() && currentUser()->delete_complete_order == 1) ? 'selected' : '' }}>@lang('setting.delete_complete_yes_option')</option>
                      <option value="0" {{ (currentUser() && currentUser()->delete_complete_order ==  0) ? 'selected' : '' }}>@lang('setting.delete_complete_no_option')</option>
                      </select>
                  </div>
                </div>
                <div class="col-md-4 mt-2 ml-1 generalSettingsForm">
                  {{Form::submit(trans('common.save_changes_btn'),["class"=>"btn btn-sm btn-primary"])}}
                </div>
                {{ Form::close() }}
              </div>
              <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
                <div class="row" id="billingSettingPage">
                  <!--<div class="col-lg-12 col-12 mt-2">
                    <div class="row existingCardDetail font-title">
                      @lang('setting.existing_card_heading')
                    </div>
                  </div>
                  <div class="col-lg-6 col-12">
                    <section class="category">
                         <div class="container">
                           <div class="list-billing">
                              @if($allCards && $allCards->data)
                                @foreach($allCards as $card)
                                  @if($card->id===$allCards->default_card)
                                <div class="row">
                                  <div class="col-md-6 col-6 mb-4">
                                    <h6 class="">@lang('setting.existing_email_card_label')</h6>
                                  </div>
                                  <div class="col-md-6 col-6 mb-4">
                                    <h6 class="">{{ ($card->name)??auth()->user()->email }}</h6>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-6 col-6 mb-4">
                                    <h6 class="">@lang('setting.existing_card_type_label')</h6>
                                  </div>
                                  <div class="col-md-6 col-6 mb-4">
                                    <h6 class="">{{ $card->brand }}</h6>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-6 col-6 mb-4">
                                    <h6 class="">@lang('setting.existing_card_number_label')</h6>
                                  </div>
                                  <div class="col-md-6 col-6 mb-4">
                                    <h6 class="">xxxx-xxxx-xxxx-{{$card->last4}}</h6>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-6 col-6 mb-4">
                                    <h6 class="">@lang('setting.existing_address_label')</h6>
                                  </div>
                                  <div class="col-md-6 col-6 mb-4">
                                    <h6 class="">{{ ($card->address_line1)??getcurrentUserWithRelation()->company->address }}</h6>
                                  </div>
                                </div>
                              @endif
                            @endforeach
                          @endif
                        </div>
                      </div>
                    </section>
                  </div>-->
                  <div class="form-group col-lg-12 col-12 mt-1">
                    <div class="row CardListing">
                      <div class="col-lg-10 col-6 font-title">@lang('setting.card_listing_heading')</div>
                      <div class="col-lg-2 col-6 pull-right">
                        <a href="javascript:void(0);" id="openNewCard" class="btn btn-primary hey-blue"><i class="fa fa-plus-circle"></i>
                          @lang('setting.add_card_txt')
                          <i class="fas fa-question-circle"
                          data-toggle="tooltip" data-placement="left" title="{{__('setting.credit_card_tooltip')}}" data-container="body"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-11 col-11">
                      @if($allCards && $allCards->data)
                        @foreach($allCards as $card)
                          <section class="card cards-section">
                              <header class="card-header">
                                  xxxx-xxxx-xxxx-{{$card->last4}}
                                  <span class="tools">
                                    @if($card->id===$allCards->default_card)
                                      <span><i class="fa fa-star" aria-hidden="true"></i></span>
                                    @endif
                                </span>
                                <a class="btn btn-primary hey-blue btn-sm fa fa-chevron-up float-right" href="javascript:void(0);">Edit </a>
                              </header>
                              <div class="card-body" style="display:none;">
                                {{ Form::open(array('route' => 'settings.updateCard')) }}
                                <input name="_method" type="hidden" value="PUT">
                                  <div class="col-lg-6 col-12">
                                    {{ Form::label('name', trans('setting.card_list_holder_label')) }}
                                    {{Form::text('name', ($card->name)??auth()->user()->email,['class'=>'form-control','readonly'=>true])}}
                                  </div>
                                  <div class="col-lg-6 col-12">
                                    {{ Form::label('address_line1', 'Address Line1') }}
                                    {{Form::text('address_line1', ($card->address_line1)??getcurrentUserWithRelation()->company->address,['class'=>'form-control'])}}
                                  </div>
                                  <div class="col-lg-6 col-12">
                                    {{ Form::label('address_line2', 'Address Line2') }}
                                    {{Form::text('address_line2', ($card->address_line2)??getcurrentUserWithRelation()->address_line2,
                                    ['class'=>'form-control'])}}
                                  </div>
                                  <div class="col-lg-6 col-12">
                                    {{ Form::label('address_country', 'Country') }}
                                    <select class="form-control custom-select mb-3 register-select-country" name="address_country" required="true">
                        							<option value="">Select Country</option>
                        								@foreach(all_countries_latest() as $country)
                        									<option data-code="{{$country->phonecode}}" data-id="{{$country->id}}" value="{{$country->name}}" {{ $card->address_country == $country->name  ? 'selected' : '' }}>{{$country->name}}</option>
                        								@endforeach
                                      </select>
                                  </div>
                                  <div class="col-lg-6 col-12">
                                    {{Form::label('address_state', 'State')}}
                        						<select class="form-control custom-select mb-3 register-select-state" name="address_state" required="true">
                        							<option value="">Select State</option>
                                      @if($card->address_country)
                                        @foreach(all_states($card->address_country) as $state)
                                          <option value="{{$state->name}}" data-id="{{$state->id}}" {{ $card->address_state == $state->name  ? 'selected' : '' }}>{{$state->name}}</option>
                                        @endforeach
                                      @endif
                                      </select>
                                  </div>
                                  <div class="col-lg-6 col-12">
                                    {{Form::label('address_city', 'City')}}
                        						<select class="form-control custom-select mb-3 register-select-city" name="address_city" required="true">
                        							<option value="">Select City</option>
                                      @if($card->address_country)
                                        @foreach(all_cities($card->address_state) as $city)
                                          <option value="{{$city->name}}" data-id="{{$city->id}}" {{ $card->address_city == $city->name  ? 'selected' : '' }}>{{$city->name}}</option>
                                        @endforeach
                                      @endif
                                      </select>
                                  </div>
                                  <div class="col-lg-6 col-12">
                                    {{ Form::label('address_zip', 'Zip/Postal Code') }}
                                    {{Form::text('address_zip', ($card->address_zip)??getcurrentUserWithRelation()->zip_code,
                                    ['class'=>'form-control'])}}
                                  </div>
                                  <div class="col-lg-6 col-12">
                                    {{ Form::label('exp_month', trans('setting.card_list_exp_month_label')) }}
                                    {{Form::text('exp_month', $card->exp_month,['class'=>'form-control'])}}
                                  </div>
                                  <div class="col-lg-6 col-12">
                                    {{ Form::label('exp_year', trans('setting.card_list_exp_year_label')) }}
                                    {{Form::text('exp_year', $card->exp_year,['class'=>'form-control'])}}
                                  </div>
                                  @if($card->id!==$allCards->default_card)
                                  <div class="col-lg-6 col-12">
                                    {{ Form::label('make_default', trans('setting.card_list_make_default_label')) }}
                                    {{Form::checkbox('make_default')}}
                                  </div>
                                 @endif
                                 {{Form::hidden('card_id',$card->id)}}
                                 {{Form::hidden('default_card_id',$allCards->default_card)}}
                                 <div class="form-group form-check mt-2">
                                   {{Form::submit(trans('common.save_changes_btn'),['class'=>'btn btn-primary'])}}
                                </div>
                                {{ Form::close() }}
                              </div>
                          </section>
                        @endforeach
                       @endif
                  </div>
                  @if(checkFreeSoftwareExpire() && checkSubscriptionExpire())
                    <div class="col-md-12 mt-2">
          						<div class="row existingCardDetail font-title">
          							Full access to software
          						</div>
                    </div>
                    <div class="row">
                      {{ Form::open(['route' => ['settings.update', auth()->user()->id],  'method' => 'PUT']) }}
                      <div class="col-md-12 ml-3 mt-3">
                        <div class="d-flex">
                          <input type="checkbox" {{(getcurrentUserWithRelation()->need_billing==1?'checked':false)}} data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="secondary" data-offstyle="danger"  data-width="100" data-height="40" name="need_billing">
                          <div class="form-group ml-4">
      				               <button class="btn btn-primary" type="submit">@lang('common.save_changes_btn')</button>
                          </div>
                        </div>
                      </div>
                      {{ Form::close() }}
                    </div>
                  @endif

                    <!--<div class="col-md-12 mt-2">
          						<div class="row existingCardDetail font-title">
          							@lang('setting.just_menu_qr_code_label')
          						</div>
                    </div>
                    <div class="row">
                      {{ Form::open(['route' => ['settings.updatePlan', auth()->user()->id],  'method' => 'PUT']) }}
                      <div class="col-md-12 ml-3 mt-3">
                        <div class="d-flex">
                          <input type="checkbox" {{(getcurrentUserWithRelation()->menu_qr_code==1?'checked':false)}} data-toggle="toggle" data-on="{{trans('setting.enable_label')}}" data-off="{{trans('setting.disable_label')}}" data-onstyle="secondary" data-offstyle="danger"  data-width="100" data-height="40" name="menu_qr_code">
                          <div class="form-group ml-4">
      				               <button class="btn btn-primary" type="submit">@lang('common.save_changes_btn')</button>
                          </div>
                        </div>
                      </div>
                      {{ Form::close() }}
                    </div>-->
                    <div class="col-md-12 mt-2">
          						<div class="row existingCardDetail font-title">@lang('setting.delete_account_label')</div>
                      <div class="row">
                        {{ Form::open(['route' => ['userAccountSoftDelete', auth()->user()->id],
                        'method' => 'get','onsubmit'=>"return confirm('Your account will be held securely in our data base for 90 days if you wish to re-activate your account and restore all past order history and reports. After 90 days the account will be permanently deleted?');"]) }}
                        <div class="col-md-12 ml-3 mt-3">
                          <div class="d-flex">
                            <div class="form-group ml-4">
        				               <button class="btn btn-danger" type="submit">
                                 @lang('setting.delete_account_label')
                                 <i class="fas fa-question-circle"
                                 data-toggle="tooltip" data-placement="top" title="{{__('setting.delete_account_tooltip')}}" data-container="body"></i>
                               </button>
                            </div>
                          </div>
                        </div>
                        {{ Form::close() }}
                      </div>
                    </div>
                </div>
              </div>
              <div class="tab-pane fade" id="acceptPaymentsTab" role="tabpanel" aria-labelledby="accept-payments-tab">
                <div class="col-lg-12 generalSettingsForm mt-2">
                  @if(!auth()->user()->connect_account_id)
                    <a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_KSB0YGI4UuwrjSYjU2ss6FWnmYdsyVtQ&scope=read_write" class="btn btn-primary">Add connect account</a>
                  @else
                  <p><b>@lang('setting.stripe_connect_p_label')</b></p>
                  @endif
                </div>
                <div class="col-lg-6">
                  <div class="row generalSettings font-title">
                    @lang('payouts.list_heading')
                  </div>
                  <div class="table-responsive mt-4">
                    <table class="table table-striped table-advance table-hover" id="payoutsTable">
                        <thead>
                            <tr>
                              <th>@lang('payouts.table_transaction_label')</th>
                              <th>@lang('payouts.table_amount_label')</th>
                              <th>@lang('payouts.table_currency_label')</th>
                              <th>@lang('payouts.table_payment_type_label')</th>
                              <th>@lang('payouts.table_card_number_label')</th>
                              <th>@lang('payouts.table_created_label')</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if($payouts)
                            @foreach($payouts as $payout)

                              <tr>
                                <td>{{$payout->id}}</td>
                                <td>${{(float)number_format(($payout->amount/100), 2, '.', '')}}</td>
                                <td>{{$payout->currency}}</td>
                                <td>{{ucwords(str_replace("_"," ",$payout->payment_method_details->type))}}</td>
                                <td>
                                  @if($payout->payment_method_details->type=='card')
                                    {{$payout->payment_method_details->card->last4}}
                                  @else
                                  @endif
                                </td>
                                <td>{{date('Y-m-d',$payout->created)}}</td>
                              </tr>

                            @endforeach
                          @endif
                        </tbody>
                        <tfoot>
                            <tr>
                              <th>@lang('payouts.table_transaction_label')</th>
                              <th>@lang('payouts.table_amount_label')</th>
                              <th>@lang('payouts.table_currency_label')</th>
                              <th>@lang('payouts.table_payment_type_label')</th>
                              <th>@lang('payouts.table_card_number_label')</th>
                              <th>@lang('payouts.table_created_label')</th>
                            </tr>
                        </tfoot>
                    </table>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="row generalSettings font-title">
                    @lang('payouts.list_heading_payments')
                  </div>
                  <div class="table-responsive mt-4">
                    <table class="table table-striped table-advance table-hover" id="payoutsTable">
                        <thead>
                            <tr>
                              <th>@lang('payouts.table_id_label')</th>
                              <th>@lang('payouts.table_amount_label')</th>
                              <th>@lang('payouts.table_currency_label')</th>
                              <th>@lang('payouts.table_payment_type_label')</th>
                              <th>@lang('payouts.table_card_number_label')</th>
                              <th>@lang('payouts.table_created_label')</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if($payments)
                            @foreach($payments as $payout)
                              <tr>
                                <td>{{$payout->id}}</td>
                                <td>${{$payout->amount}}</td>
                                <td>
                                  @if($payout->payment_type=='Card')
                                  {{$payout->transaction_detail->currency}}
                                  @endif
                                </td>
                                <td>{{$payout->payment_type}}</td>
                                <td>
                                  @if($payout->payment_type=='Card')
                                  {{$payout->card_number}}
                                  @endif
                                </td>
                                <td>{{date('Y-m-d',strtotime($payout->created_at))}}</td>
                              </tr>

                            @endforeach
                          @endif
                        </tbody>
                        <tfoot>
                            <tr>
                              <th>@lang('payouts.table_id_label')</th>
                              <th>@lang('payouts.table_amount_label')</th>
                              <th>@lang('payouts.table_currency_label')</th>
                              <th>@lang('payouts.table_payment_type_label')</th>
                              <th>@lang('payouts.table_card_number_label')</th>
                              <th>@lang('payouts.table_created_label')</th>
                            </tr>
                        </tfoot>
                    </table>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="changeLanguageTab" role="tabpanel" aria-labelledby="change-language-tab">
                <div class="col-lg-6">
                  <div class="row generalSettings font-title">
                    @lang('setting.change_language_label')
                  </div>
                  <div class="col-lg-6">
                    <ul>
                      <li class="dropdown language">
                          <a data-close-others="true" data-hover="dropdown" data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="true">
                              <img src="{{getLangFlag()}}" alt="">
                              <span class="username">{{getLangName()}}</span>
                              <!--<b class="caret"></b>-->
                          </a>
                          <ul class="dropdown-menu" x-placement="bottom-start">

                              {!! getLangHtml() !!}
                          </ul>
                      </li>
                    </ul>
                  </div>
              </div>
          </div>
        </div>
    </section>
  </div>
</div>
<form id="addNewCreditCard" action="{{route('settings.newCard')}}" method="POST">
  {{Form::token()}}
  <input type="hidden" id="stripeToken" name="source" />
  <input type="hidden" id="stripeEmail" name="email" />
  <input type="hidden" id="stripeName" name="name" />
  <input type="hidden" id="stripeAddressCity" name="address_city" />
  <input type="hidden" id="stripeAddressState" name="address_state" />
  <input type="hidden" id="stripeAddressCountry" name="address_country" />
  <input type="hidden" id="stripeAddressLine1" name="address_line1" />
  <input type="hidden" id="stripeAddressLine2" name="address_line2" />
  <input type="hidden" id="stripeAddressZip" name="address_zip" />

</form>
<style>
.fa-chevron-up{color:#fff;}
.fa-chevron-up:before{content:"";}
.fa-chevron-up:after{content:"\f077";}
.fa-chevron-down{color:#fff;}
.fa-chevron-down:before{content:"";}
.fa-chevron-down:after{content:"\f078";}
</style>
@endsection
@section('myScripts')
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<script type="text/javascript" src="https://checkout.stripe.com/checkout.js"></script>

<script type="text/javascript">
$('.card .fa-chevron-up').on('click', function () {
      var el = $(this).parents(".cards-section").children(".card-body");
      if ($(this).hasClass("fa-chevron-down")) {
          $(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
          el.slideUp(200);
      } else {
          $(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
          el.slideDown(200);
      }
  });

  //Opem New Card Form
  var publish_key = '{!! env('STRIPE_KEY') !!}';
  var handler = StripeCheckout.configure({
	  key: publish_key,
	  image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
		allowRememberMe: false,
	  token: function(token) {
	    $("#stripeToken").val(token.id);
      $("#stripeEmail").val(token.email);
      $("#stripeName").val(token.card.name);
      $("#stripeAddressCity").val(token.card.address_city);
      $("#stripeAddressState").val(token.card.address_state);
      $("#stripeAddressCountry").val(token.card.address_country);
      $("#stripeAddressLine1").val(token.card.address_line1);
	    $("#stripeAddressLine2").val(token.card.address_line2);
	    $("#addNewCreditCard").submit();
	  }
	});
  var customerEmail='{!! auth()->user()->email !!}';
  $('#openNewCard').on('click', function(e) {
	  handler.open({
			//name: 'Heyitsready',
	    description: 'Add Card',
      billingAddress:true,
      email:customerEmail,
      name:'Jasmaninder Singh',
      zipCode:false,
			panelLabel:'Add Card'
	  });
	  e.preventDefault();
	});
  function billingAutocomplete() {
    if($('.address_line1').length>0){
      $('.address_line1').each(function(){
        var autocomplete=new google.maps.places.Autocomplete(this);
        autocomplete.addListener('place_changed', function() {
          var place = autocomplete.getPlace();
        });
      });
    }

  }
  google.maps.event.addDomListener(window, 'load', billingAutocomplete);

  $(document).ready(function(){
    /*tab types settings*/
    var tabType='<?=Request::get('type')?Request::get('type'):''?>';
  	if(!tabType){
  		$('.nav-tabs a[href="#general"]').tab('show');
  	}else if(tabType=='billing'){
  		$('.nav-tabs a[href="#billing"]').tab('show');
  	}
    /*tab types settings*/
  });

  $(document).ready(function(){
    $('.register-select-country').on('change', function () {
      var idCountry = $(this).find('option:selected').data('id');
      var th=this;
      $(th).parents('.cards-section').find('.register-select-state').html('');
      $.ajax({
          url: "{{url('fetch-states')}}",
          type: "POST",
          data: {
              country_id: idCountry,
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (result) {
              $(th).parents('.cards-section').find('.register-select-state').html('<option value="" data-id="">Select State</option>');
              $.each(result.states, function (key, value) {
                  $(th).parents('.cards-section').find('.register-select-state').append('<option value="' + value
                      .name + '" data-id="' + value.id + '">' + value.name + '</option>');
              });
              $(th).parents('.cards-section').find('.register-select-city').html('<option value="" data-id="">Select City</option>');
          }
      });
    });
    $('.register-select-state').on('change', function () {
      var idState = $(this).find('option:selected').data('id');
      var th=this;
      $(th).parents('.cards-section').find('.register-select-city').html('');
      $.ajax({
          url: "{{url('fetch-cities')}}",
          type: "POST",
          data: {
              state_id: idState,
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (res) {
              $(th).parents('.cards-section').find('.register-select-city').html('<option value="" data-id="">Select City</option>');
              $.each(res.cities, function (key, value) {
                  $(th).parents('.cards-section').find('.register-select-city').append('<option value="' + value
                      .name + '" data-id="' + value.id + '">' + value.name + '</option>');
              });
          }
      });
    });
  });
</script>
@endsection
