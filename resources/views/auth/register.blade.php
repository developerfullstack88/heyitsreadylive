@extends('layouts.login')

@section('content')
<div class="row">
	 <div class="col-md-12 text-center">
	 		<img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:12rem;">
	 </div>
</div>
  <form id="form-signin-task" role="form" class="form-signin form-signin-task"
  method="post" action="{{ route('register') }}" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" data-stripe-secret="{{ env('STRIPE_SECRET') }}">
      @csrf
      <h2 class="form-signin-heading">Hey It's Ready Business Registration Form</h2>
      <div class="row login-wrap">
        <div class="col-md-6">
          <p>Enter your personal details below</p>
          <label for="first_name">{{ __('First Name') }}</label>
          <div>
            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror"
            name="first_name" required value="{{ old('first_name') }}"  autocomplete="first_name"
            autofocus>
            @error('first_name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <label for="last_name">{{ __('Last Name') }}</label>
          <div>
            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror"
            name="last_name" required value="{{ old('last_name') }}"  autocomplete="last_name"
            autofocus>
            @error('last_name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
					<label for="company_name">{{ __('Company Name') }}</label>
          <div>
            <input id="company_name" type="text" class="form-control @error('country') is-invalid @enderror"
            name="company_name" required value="{{ old('company_name') }}"  autocomplete="company_name"
            autofocus>
            @error('company_name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
					<p class="mb-0">{{ __('Billing Address') }}</p>
					<label for="address">{{ __('Street Number') }}</label>
          <div>
            <input type="text" class="form-control @error('address') is-invalid @enderror"
            name="address" required value="{{ old('address') }}"  autocomplete="false"
            autofocus>
            @error('address')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
					<label for="street_address">{{ __('Street') }}</label>
          <div>
            <input type="text" class="form-control @error('street_address') is-invalid @enderror"
            name="street_address" required value="{{ old('street_address') }}"  autocomplete="false"
            autofocus>
            @error('street_address')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
					<label for="line2_address">{{ __('Line2') }}</label>
          <div>
            <input type="text" class="form-control @error('line2_address') is-invalid @enderror"
            name="line2_address" value="{{ old('line2_address') }}"  autocomplete="false"
            autofocus>
            @error('street_address')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
					<div>
            {{Form::label('city', 'City')}}
						<input type="text" class="form-control @error('city') is-invalid @enderror"
            name="city" required value="{{ old('city') }}"  autocomplete="false"
            autofocus>
            @error('city')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
					<div>
            {{Form::label('state', 'State/Province')}}
						<input type="text" class="form-control @error('state') is-invalid @enderror"
            name="state" required value="{{ old('state') }}"  autocomplete="false"
            autofocus>
            @error('state')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
					<div>
            {{Form::label('country', 'Country')}}
						<select class="form-control custom-select mb-3" name="country" required="true"
						id="register-select-country">
							<option value="">Select Country</option>
								@foreach(all_countries_latest() as $country)
									<option data-code="{{$country->phonecode}}" data-id="{{$country->id}}" value="{{$country->name}}" {{ old('country') == $country->name ? 'selected' : '' }}>{{$country->name}}</option>
								@endforeach
              </select>
          </div>

					<label for="zip_code">{{ __('Zip/Postal Code') }}</label>
          <div>
            <input id="zip_code" type="text" class="form-control @error('zip_code') is-invalid @enderror"
            name="zip_code" required value="{{ old('zip_code') }}"  autocomplete="zip_code"
            autofocus>
            @error('zip_code')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <p>Enter your account details below</p>
          <label for="email">{{ __('E-Mail Address') }}</label>
          <div>
            <input id="email" type="email" required class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
					<label for="company_website">{{ __('Company Website') }}</label>
          <div>
            <input id="company_website" type="text" class="form-control @error('company_website') is-invalid @enderror"
            name="company_website" required value="{{ old('company_website') }}"  autocomplete="company_website"
            autofocus>
            @error('company_website')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <label for="phone_code">{{ __('Phone Number') }}</label>
          <div class="row">
            <div class="col-md-3">
							<input type="hidden" class="form-control mb-3" id="phone-code-hidden" name="phone_code"/>
								<select class="form-control custom-select mb-3" id="phone-code-register"
								required="true" autofocus>
									<option value="">Select</option>
										@php
											$phonCode=getPhoneCode();
										@endphp
										@foreach(allPhoneCode() as $code)
											<option value="{{$code}}" {{ old('phone_code') || $phonCode== $code ? 'selected' : '' }}>{{$code}}</option>
										@endforeach
		              </select>
                @error('phone_code')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
            <div class="col-md-9">
              <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror"
              name="phone_number" required value="{{ old('phone_number') }}"  autocomplete="off"
              autofocus>
              @error('phone_number')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
					<div style="display:none">
						<label for="password">{{ __('Password') }}</label>
	          <div>
	            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">
	              @error('password')
	                  <span class="invalid-feedback" role="alert">
	                      <strong>{{ $message }}</strong>
	                  </span>
	              @enderror
	          </div>
	          <label for="password-confirm">{{ __('Confirm Password') }}</label>
	          <div>
	              <input id="password-confirm" type="password" class="form-control"
	              name="password_confirmation"  autocomplete="new-password">
	          </div>
					</div>
					@if(fetchTimezone())
						{{Form::hidden('timezone', fetchTimezone())}}
					@else
	          <div>
	            {{Form::label('timezone', 'Timezone')}}
	            <select class="form-control" id="timezone" name="timezone" required="true">
	              <option value="">Select Timezone</option>
	                @foreach(all_timezones() as $key=>$timezone)
	                  <option value="{{$key}}" {{ old('timezone') == $key ? 'selected' : '' }}>{{$timezone}}</option>
	                @endforeach
	            </select>
	          </div>
					@endif
          <div class="mt-2 d-none">
            {{Form::label('need_billing', 'Need Billing')}}
            <select class="form-control" id="need_billing" name="need_billing" disabled="true">
              <option value="">Select Option</option>
              <option value="yes" {{ old('need_billing') == 'yes' ? 'selected' : '' }}>Yes</option>
              <option value="no" {{ old('need_billing') ==  'no' ? 'selected' : '' }}>No</option>
            </select>
          </div>
					<div class="register-grey-background">
	          <p>Enter your card details below</p>
						<div class="form-row">
					    <div id="card-element" style="width: 35.5em;border: 1px solid #eaeaea;margin-bottom: 15px;padding: 10px;margin-left: 6px;">
					      <!-- A Stripe Element will be inserted here. -->
					    </div>

					    <!-- Used to display form errors. -->
					    <div id="card-errors" role="alert"></div>
	  				</div>
						<div class="col-md-12 col-12">
							<input type="checkbox" id="openTermsModal" class="required" required/>  <label>I have read and understand the Terms of Service</label>
						</div>
	          <div class='form-row row' id="errorDiv">
	            <div class='col-md-12 error form-group hide'>
	              <div class='alert-danger alert'>Please correct the errors and try again.</div>
	            </div>
	          </div>
	          <div>
						<div class='form-row row' id="startFreeTrialRow">
							<div class="col-md-12 ml-3">
								<span>Start your 6O days free account now, we will save your credit card detail for future use</span>
							</div>
						</div>
	            <button type="submit" class="btn btn-primary hey-blue ml-3 submit-button">
	                {{ __('Register') }}
	            </button>
	          </div>
	        <div class="registration ml-2 mt-2 registered-already">Already Registered.
	          <a class="" href="{{route('login')}}">Login</a>
	        </div>
				</div>
      </div>
  </form>
  @endsection
  @section('myScripts')
  <script src="https://js.stripe.com/v3/"></script>
  <script src="{{URL::asset('js/stripe.js')}}"></script>
  <script type="text/javascript">
      //Google map autocomplete location
      /*function autocompleteLocation() {
        var input = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.setFields(
            ['address_components', 'geometry', 'icon', 'name']);

        autocomplete.addListener('place_changed', function() {
          var place = autocomplete.getPlace();
          if (!place.geometry) { }
        });
      }*/

      //code autocomplete
      /*var availableTags = [
      "+44", "+1", "+213", "+376", "+244", "+1264", "+1268", "+54", "+374", "+297", "+61", "+43", "+994", "+1242", "+973", "+880", "+1246", "+375", "+32", "+501", "+229", "+1441", "+975", "+591", "+387", "+267", "+55", "+673", "+359", "+226", "+257", "+855", "+237", "+238", "+1345", "+236", "+56", "+86", "+57", "+269", "+242", "+682", "+506", "+385", "+53", "+90392", "+357", "+42", "+45", "+253", "+1809", "+593", "+20", "+503", "+240", "+291", "+372", "+251", "+500", "+298", "+679", "+358", "+33", "+594", "+689", "+241", "+220", "+7880", "+49", "+233", "+350", "+30", "+299", "+1473", "+590", "+671", "+502", "+224", "+245", "+592", "+509", "+504", "+852", "+36", "+354", "+91", "+62", "+98", "+964", "+353", "+972", "+39", "+1876", "+81", "+962", "+7", "+254", "+686", "+850", "+82", "+965", "+996", "+856", "+371", "+961", "+266", "+231", "+218", "+417", "+370", "+352", "+853", "+389", "+261", "+265", "+60", "+960", "+223", "+356", "+692", "+596", "+222", "+52", "+691", "+373", "+377", "+976", "+1664", "+212", "+258", "+95", "+264", "+674", "+977", "+31", "+687", "+64", "+505", "+227", "+234", "+683", "+672", "+670", "+47", "+968", "+680", "+507", "+675", "+595", "+51", "+63", "+48", "+351", "+1787", "+974", "+262", "+40", "+250", "+378", "+239", "+966", "+221", "+381", "+248", "+232", "+65", "+421", "+386", "+677", "+252", "+27", "+34", "+94", "+290", "+1869", "+1758", "+249", "+597", "+268", "+46", "+41", "+963", "+886", "+66", "+228", "+676", "+1868", "+216", "+90", "+993", "+1649", "+688", "+256", "+380", "+971", "+598", "+678", "+379", "+58", "+84", "+681", "+969", "+967", "+260", "+263"
    ];
    $( "#phone_code" ).autocomplete({
      source: availableTags
    });*/
    $('#phone_number').mask('(000) 000-0000');
		$(document).on('click','#openTermsModal',function(){
			$('#openTermsModalPop').modal('show');
		});

		$(document).on('change','#register-select-country',function(){
			var code=$(this).find('option:selected').data('code');
			if(code){ code='+'+code; }
			$('#phone-code-register').val(code);
			$('#phone-code-hidden').val(code);
			$('#phone-code-register').attr('disabled',true);
		});

		$(document).ready(function(){
			/*$('#register-select-country').on('change', function () {
        var idCountry = $(this).find('option:selected').data('id');
        $("#register-select-state").html('');
        $.ajax({
            url: "{{url('fetch-states')}}",
            type: "POST",
            data: {
                country_id: idCountry,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function (result) {
                $('#register-select-state').html('<option value="">Select State</option>');
                $.each(result.states, function (key, value) {
                    $("#register-select-state").append('<option value="' + value
                        .id + '">' + value.name + '</option>');
                });
                $('#register-select-city').html('<option value="">Select City</option>');
            }
        });
      });
      $('#register-select-state').on('change', function () {
        var idState = this.value;
        $("#register-select-city").html('');
        $.ajax({
            url: "{{url('fetch-cities')}}",
            type: "POST",
            data: {
                state_id: idState,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function (res) {
                $('#register-select-city').html('<option value="">Select City</option>');
                $.each(res.cities, function (key, value) {
                    $("#register-select-city").append('<option value="' + value
                        .id + '">' + value.name + '</option>');
                });
            }
        });
      });*/
		});
  </script>
  <!--<script
      src="//maps.googleapis.com/maps/api/js?key=AIzaSyBOB2S7yoLqno0FIagdBu7X0PpuU5ggsiY&libraries=places&callback=autocompleteLocation">
  </script>-->
@endsection
