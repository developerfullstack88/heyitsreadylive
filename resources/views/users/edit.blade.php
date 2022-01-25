@extends('layouts.default')

@section('content')
<div class="row">
    <aside class="profile-nav col-lg-4">
        <section class="panel card">
            <div class="user-heading round card-header">
                <a href="#" class="d-none">
                    <img src="{{asset('img/profile-avatar.jpg')}}" alt="">
                </a>
                <h1 class="font-title">{{$userInfo->name}}</h1>
            </div>

            <ul class="nav nav-pills nav-stacked">
              <li class="nav-item font-title">
                <a href="{{route('users.show',$userInfo->id)}}" class="nav-link"><i class="fa fa-user"></i> @lang('profile.profile_label')</a>
              </li>
            </ul>

        </section>
    </aside>
    <aside class="profile-info col-lg-8 col-12">
        <section class="panel card">
            <div class="bio-graph-heading card-header font-title">
                @lang('profile.profile_info_label')
            </div>
            <div class="panel-body bio-graph-info">
              {{ Form::model($userInfo,array('route' => array('users.update', $userInfo->id)),['id'=>'editProfileForm']) }}
              {{Form::hidden('company_id',Null)}}
              {{Form::hidden('stripe_customer_id',Null)}}
              {{Form::hidden('update_action','profile')}}
                <input type="hidden" name="_method" value="PUT">
                <div class="col-lg-8 col-12">
                  {{Form::label('first_name', trans('profile.first_name_label'))}}
                  <div>
                    {{Form::text('first_name',$userInfo->first_name,['class'=>'form-control','required'=>true,'autofocus'=>true])}}
                    @error('first_name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  {{Form::label('last_name', trans('profile.last_name_label'))}}
                  <div>
                    {{Form::text('last_name',$userInfo->last_name,['class'=>'form-control','required'=>true,'autofocus'=>true])}}
                    @error('last_name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  {{Form::label('email', trans('profile.email_label'))}}
                  <div>
                    {{Form::text('email',$userInfo->email,['class'=>'form-control','required'=>true,'autofocus'=>true,'id'=>'email'])}}
                    @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  {{Form::label('company_name', trans('profile.company_name_label'))}}
                  <i class="fas fa-question-circle"
      						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
                  <div>
                    {{Form::text('company_name',$userInfo->company->company_name,['class'=>'form-control','required'=>true,'autofocus'=>true])}}
                    @error('company_name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  {{Form::label('address', 'Street Number')}}
                  <i class="fas fa-question-circle"
      						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
                  <div class="">
                    {{Form::text('address',$userInfo->company->address,['class'=>'form-control','required'=>true,'autofocus'=>true,'id'=>'address'])}}
                    @error('address')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  {{Form::label('country', trans('profile.country_label'))}}
                  <div>
                    {{Form::text('country',$userInfo->country,['class'=>'form-control','autofocus'=>true,'readonly'=>true])}}
                  </div>
                  {{Form::label('state', 'State/Province')}}
                  <div>
                    {{Form::text('state',$userInfo->state,['class'=>'form-control','autofocus'=>true,'readonly'=>true])}}
                  </div>
                  {{Form::label('city', 'City')}}
                  <div>
                    {{Form::text('city',$userInfo->city,['class'=>'form-control','autofocus'=>true,'readonly'=>true])}}
                  </div>
                  {{Form::label('street_address', 'Street')}}
                  <div>
                    {{Form::text('street_address',$userInfo->street_address,['class'=>'form-control','autofocus'=>true])}}
                  </div>
                  {{Form::label('line2_address', 'Line2')}}
                  <div>
                    {{Form::text('line2_address',$userInfo->line2_address,['class'=>'form-control','autofocus'=>true])}}
                  </div>
                  {{Form::label('zip_code', 'Zip/Postal Code')}}
                  <div>
                    {{Form::text('zip_code',$userInfo->zip_code,['class'=>'form-control','autofocus'=>true])}}
                  </div>
                  {{Form::label('company_website', trans('profile.company_website_label'))}}
                  <div>
                    {{Form::text('company_website',$userInfo->company->company_website,['class'=>'form-control','required'=>true,'autofocus'=>true])}}
                    @error('company_website')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  {{Form::label('phone_code', trans('profile.phone_label'))}}
                  <div class="row">
                    <div class="col-md-3">
                      {{Form::text('phone_code',$userInfo->phone_code,['class'=>'form-control','autofocus'=>true,'readonly'=>true])}}
                    </div>
                    <div class="col-md-9">
                      {{Form::text('phone_number',$userInfo->phone_number,['class'=>'form-control','required'=>true,'autofocus'=>true,'id'=>'phone_number'])}}
                      @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group">
          					<div class="col-lg-10 mt-3">
          						<button class="btn btn-primary hey-blue" type="submit">@lang('common.save_changes_btn')</button>
                      <a href="{{route('users.show',$userInfo->id)}}" class="btn btn-default">@lang('common.cancel_btn')</a>
                    </div>
          				</div>
                </div>
              {{ Form::close() }}
            </div>
        </section>
        <section class="panel card">
            <div class="bio-graph-heading card-header font-title">
                @lang('profile.change_password_label')
            </div>
            <div class="panel-body bio-graph-info">
              {{ Form::model($userInfo,array('route' => array('users.update', $userInfo->id)),['id'=>'editProfileForm']) }}
              {{Form::hidden('update_action','change_password')}}
                <input type="hidden" name="_method" value="PUT">
                @if($errors->count()>0)
                  <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                      <p>{{ $error }}</p>
                    @endforeach
                  </div>
                @endif;
                <div class="col-lg-8 col-12">
                  {{Form::label('old_password', trans('profile.current_password_label'))}}
                  <div>
                    {{Form::password('old_password',['class'=>'form-control','required'=>true,'autofocus'=>true])}}
                    @error('old_password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  {{Form::label('new_password', trans('profile.new_password_label'))}}
                  <div>
                    {{Form::password('new_password',['class'=>'form-control','required'=>true,'autofocus'=>true])}}
                    @error('new_password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  {{Form::label('confirm_password', trans('profile.confirm_password_label'))}}
                  <div>
                    {{Form::password('confirm_password',['class'=>'form-control','required'=>true,'autofocus'=>true])}}
                    @error('confirm_password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="form-group">
          					<div class="col-lg-10 mt-3">
          						<button class="btn btn-primary hey-blue" type="submit">@lang('common.save_changes_btn')</button>
                      <a href="{{route('users.show',$userInfo->id)}}" class="btn btn-default">@lang('common.cancel_btn')</a>
                    </div>
          				</div>
                  </div>
                </div>
              {{ Form::close() }}
            </div>
        </section>
    </aside>
</div>
@endsection
@section('myScripts')
  <script type="text/javascript">
  //code autocomplete
  var availableTags = [
  "+44", "+1", "+213", "+376", "+244", "+1264", "+1268", "+54", "+374", "+297", "+61", "+43", "+994", "+1242", "+973", "+880", "+1246", "+375", "+32", "+501", "+229", "+1441", "+975", "+591", "+387", "+267", "+55", "+673", "+359", "+226", "+257", "+855", "+237", "+238", "+1345", "+236", "+56", "+86", "+57", "+269", "+242", "+682", "+506", "+385", "+53", "+90392", "+357", "+42", "+45", "+253", "+1809", "+593", "+20", "+503", "+240", "+291", "+372", "+251", "+500", "+298", "+679", "+358", "+33", "+594", "+689", "+241", "+220", "+7880", "+49", "+233", "+350", "+30", "+299", "+1473", "+590", "+671", "+502", "+224", "+245", "+592", "+509", "+504", "+852", "+36", "+354", "+91", "+62", "+98", "+964", "+353", "+972", "+39", "+1876", "+81", "+962", "+7", "+254", "+686", "+850", "+82", "+965", "+996", "+856", "+371", "+961", "+266", "+231", "+218", "+417", "+370", "+352", "+853", "+389", "+261", "+265", "+60", "+960", "+223", "+356", "+692", "+596", "+222", "+52", "+691", "+373", "+377", "+976", "+1664", "+212", "+258", "+95", "+264", "+674", "+977", "+31", "+687", "+64", "+505", "+227", "+234", "+683", "+672", "+670", "+47", "+968", "+680", "+507", "+675", "+595", "+51", "+63", "+48", "+351", "+1787", "+974", "+262", "+40", "+250", "+378", "+239", "+966", "+221", "+381", "+248", "+232", "+65", "+421", "+386", "+677", "+252", "+27", "+34", "+94", "+290", "+1869", "+1758", "+249", "+597", "+268", "+46", "+41", "+963", "+886", "+66", "+228", "+676", "+1868", "+216", "+90", "+993", "+1649", "+688", "+256", "+380", "+971", "+598", "+678", "+379", "+58", "+84", "+681", "+969", "+967", "+260", "+263"
  ];
  $( "#phone_code" ).autocomplete({
  source: availableTags
  });
  $('#phone_number').mask('(000) 000-0000');
  //google.maps.event.addDomListener(window, 'load', autocompleteLocation);
  </script>
@endsection
