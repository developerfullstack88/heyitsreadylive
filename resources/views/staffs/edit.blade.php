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
		  <header class="card-header font-title">Edit your staff</header>
			<div class="card-body" id="siteAddCardBody">
        {{ Form::model($user,array('action'=> array('StaffController@update',$user->id),'files'=>true)) }}
         @method('PUT')
          {{Form::token()}}
          <div class="form-group">
            {{Form::label('first_name','First Name')}}
            {{Form::text('first_name',$user->first_name,
              ['class'=>'form-control','placeholder'=>'Enter first name','required'=>true]
            )}}
          </div>
          <div class="form-group">
            {{Form::label('last_name','Last Name')}}
            {{Form::text('last_name',$user->last_name,
              ['class'=>'form-control','placeholder'=>'Enter last name','required'=>true]
            )}}
          </div>
          <div class="form-group">
            {{Form::label('email','Email')}}
            {{Form::email('email',$user->email,
              ['class'=>'form-control','placeholder'=>'Enter email','required'=>true,'autocomplete'=>'off']
            )}}
          </div>
          <div>
            {{Form::label('country', 'Country')}}
						<select class="form-control custom-select mb-3" name="country" required="true">
							<option value="">Select Country</option>
								@foreach(all_countries() as $country)
									<option value="{{$country}}" {{ old('country') == $country || $user->country == $country ? 'selected' : '' }}>{{$country}}</option>
								@endforeach
              </select>
          </div>
          <label for="phone_code">{{ __('Phone Number') }}</label>
          <div class="row">
            <div class="col-md-3">
								<select class="form-control custom-select mb-3" name="phone_code" required="true" autofocus>
									<option value="">Select</option>
										@php
											$phonCode=getPhoneCode();
										@endphp
										@foreach(allPhoneCode() as $code)
											<option value="{{$code}}" {{ old('phone_code') || $user->phone_code== $code ? 'selected' : '' }}>{{$code}}</option>
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
              name="phone_number" required value="{{$user->phone_number}}"  autocomplete="off"
              autofocus>
              @error('phone_number')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
          <div class="form-group">
						{{Form::label('profile_photo','Profile Image')}}
						{{Form::file('profile_photo',['required'=>false])}}
					</div>
          <div class="form-group">
            <img src="{{asset($user->profile_photo_thumbnail)}}" class="rounded"/>
					</div>
          <div class="form-group form-check text-center">
              {{Form::submit('Save Staff',["class"=>"btn btn-lg btn-primary hey-blue"])}}
          </div>
          {{ Form::close() }}
			</div>
		</section>
	</div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
  $('#phone_number').mask('(000) 000-0000');
</script>
@endsection
