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
          </ul>
          <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="col-lg-12">
                  <div class="row generalSettings font-title">
                    @lang('setting.delete_complete_heading')
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
                <div class="col-lg-12 generalSettingsForm mt-2">
                  @if(!auth()->user()->connect_account_id)
                    <a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_KSB0YGI4UuwrjSYjU2ss6FWnmYdsyVtQ&scope=read_write" class="btn btn-primary">Add connect account</a>
                  @else
                  <p><b>Stripe Connect accounts has been setup</b></p>
                  @endif
                </div>
              </div>
              <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
                <div class="row" id="billingSettingPage">
                  <div class="col-lg-12 col-12 mt-2">
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
                  </div>
                  <div class="form-group col-lg-12 col-12 mt-3">
                    <div class="row CardListing">
                      <div class="col-lg-10 col-6 font-title">@lang('setting.card_listing_heading')</div>
                      <div class="col-lg-2 col-6 pull-right">
                        <a href="javascript:void(0);" id="openNewCard" class="btn btn-primary hey-blue"><i class="fa fa-plus-circle"></i>@lang('setting.add_card_txt')</a>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-11 col-11">
                      @if($allCards && $allCards->data)
                        @foreach($allCards as $card)
                          <section class="card cards-section">
                              <header class="card-header">
                                  xxxx-xxxx-xxxx-{{$card->last4}}
                                  <span class="tools pull-right">
                                    @if($card->id===$allCards->default_card)
                                      <span><i class="fa fa-star" aria-hidden="true"></i></span>
                                    @endif
                                    <a class="fa fa-chevron-up" href="javascript:void(0);"></a>
                                </span>
                              </header>
                              <div class="card-body" style="display:none;">
                                {{ Form::open(array('route' => 'settings.updateCard')) }}
                                <input name="_method" type="hidden" value="PUT">
                                  <div class="col-lg-6 col-12">
                                    {{ Form::label('name', trans('setting.card_list_holder_label')) }}
                                    {{Form::text('name', ($card->name)??auth()->user()->email,['class'=>'form-control','readonly'=>true])}}
                                  </div>
                                  <div class="col-lg-6 col-12">
                                    {{ Form::label('address_line1', trans('profile.billing_address_label')) }}
                                    {{Form::text('address_line1', ($card->address_line1)??getcurrentUserWithRelation()->company->address,['class'=>'form-control address_line1'])}}
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

                    <div class="col-md-12 mt-2">
          						<div class="row existingCardDetail font-title">
          							just the Menu QR Code
          						</div>
                    </div>
                    <div class="row">
                      {{ Form::open(['route' => ['settings.updatePlan', auth()->user()->id],  'method' => 'PUT']) }}
                      <div class="col-md-12 ml-3 mt-3">
                        <div class="d-flex">
                          <input type="checkbox" {{(getcurrentUserWithRelation()->menu_qr_code==1?'checked':false)}} data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="secondary" data-offstyle="danger"  data-width="100" data-height="40" name="menu_qr_code">
                          <div class="form-group ml-4">
      				               <button class="btn btn-primary" type="submit">@lang('common.save_changes_btn')</button>
                          </div>
                        </div>
                      </div>
                      {{ Form::close() }}
                    </div>
                    <div class="col-md-12 mt-2">
          						<div class="row existingCardDetail font-title">Delete Account</div>
                      <div class="row">
                        {{ Form::open(['route' => ['userAccountSoftDelete', auth()->user()->id],  'method' => 'get']) }}
                        <div class="col-md-12 ml-3 mt-3">
                          <div class="d-flex">
                            <div class="form-group ml-4">
        				               <button class="btn btn-danger" type="submit">Delete Account</button>
                            </div>
                          </div>
                        </div>
                        {{ Form::close() }}
                      </div>
                    </div>
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
  <input type="hidden" id="stripeEmail" name="name" />
</form>
@endsection
@section('myScripts')
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<script type="text/javascript" src="https://checkout.stripe.com/checkout.js"></script>

<script type="text/javascript">
$('.card .tools .fa-chevron-up').on('click', function () {
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
	    $("#addNewCreditCard").submit();
	  }
	});
  var customerEmail='{!! auth()->user()->email !!}';
  $('#openNewCard').on('click', function(e) {
	  handler.open({
			name: 'KNEX-APP',
	    description: 'Add Card',
      email:customerEmail,
			'panel-label':'Add Card'
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
</script>
@endsection
