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
		  <header class="card-header font-title">@lang('staff.staff_list_add_your_staff_label')</header>
			<div class="card-body" id="siteAddCardBody">
        {{ Form::open(array('action'=> 'StaffController@store','files'=>true)) }}
          {{Form::token()}}
          <div class="form-group">
            {{Form::label('first_name',trans('staff.staff_list_first_name_label'))}}
            {{Form::text('first_name','',
              ['class'=>'form-control','placeholder'=>trans('staff.placeholder_first_name'),
							'required'=>true]
            )}}
          </div>
          <div class="form-group">
            {{Form::label('last_name',trans('staff.staff_list_last_name_label'))}}
            {{Form::text('last_name','',
              ['class'=>'form-control','placeholder'=>trans('staff.placeholder_last_name'),
							'required'=>true]
            )}}
          </div>
          <div class="form-group">
            {{Form::label('email',trans('staff.staff_list_email_label'))}}
            {{Form::email('email','',['class'=>'form-control',
						'placeholder'=>trans('staff.placeholder_email'),'required'=>true,'autocomplete'=>'off']
            )}}
          </div>
          <div>
            {{Form::label('country', trans('staff.staff_list_country_label'))}}
						<select class="form-control custom-select mb-3" name="country" required="true">
							<option value="">@lang('staff.empty_country_dropdown')</option>
								@foreach(all_countries() as $country)
									<option value="{{$country}}" {{ old('country') == $country ? 'selected' : '' }}>{{$country}}</option>
								@endforeach
              </select>
          </div>
          <label for="phone_code">{{ trans('staff.staff_list_phone_number_label') }}</label>
          <div class="row">
            <div class="col-md-3">
								<select class="form-control custom-select mb-3" name="phone_code" required="true" autofocus>
									<option value="">@lang('staff.empty_phone_dropdown')</option>
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
          @if(auth()->user()->role==COMPANY)
            <div>
              {{Form::label('role', trans('staff.staff_list_role_label'))}}
  						<select class="form-control custom-select mb-3" name="role" required="true">
  							<option value="">@lang('staff.empty_role_dropdown')</option>
  								@foreach(admin_roles() as $role)
  									<option value="{{$role}}" {{ old('role') == $role ? 'selected' : '' }}>{{$role}}</option>
  								@endforeach
                </select>
            </div>
          @elseif(auth()->user()->role==MANAGER)
            <div>
              {{Form::label('role', trans('staff.staff_list_role_label'))}}
  						<select class="form-control custom-select mb-3" name="role" required="true">
  							<option value="">@lang('staff.empty_role_dropdown')</option>
  								@foreach(manager_roles() as $role)
  									<option value="{{$role}}" {{ old('role') == $role ? 'selected' : '' }}>{{$role}}</option>
  								@endforeach
                </select>
            </div>
          @endif
          <div class="form-group">
						{{Form::label('profile_photo',trans('staff.staff_list_profile_image_label'))}}
						{{Form::file('profile_photo',['required'=>false])}}
					</div>
          <div class="form-group form-check text-center">
              {{Form::hidden('timezone', fetchTimezone())}}
              {{Form::submit(trans('staff.staff_list_save_staff_btn'),["class"=>"btn btn-lg btn-primary hey-blue"])}}
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
