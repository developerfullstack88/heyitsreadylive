@extends('layouts.login')

@section('content')
<div class="row">
	 <div class="col-md-12 text-center">
	 		<img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:12rem;">
	 </div>
</div>
<form class="form-signin ipixup-signin" method="POST" action="{{ route('activateAccountPost',[$id]) }}">
 @csrf
 <h2 class="form-signin-heading">Set Password</h2>
 <div class="login-wrap">
    <label for="password">@lang('login.login_password_label')</label>
    <div>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

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
    <button class="btn btn-lg btn-login btn-block" type="submit">Submit</button>
</div>

</form>
@endsection
