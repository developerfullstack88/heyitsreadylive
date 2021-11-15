@extends('layouts.login')

@section('content')
<div class="row">
	 <div class="col-md-12 text-center">
	 		<img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:12rem;">
	 </div>
</div>
<form class="form-signin ipixup-signin" method="POST" action="{{ route('super-admin-login-post') }}">
 @csrf
 <h2 class="form-signin-heading">Admin Login</h2>
 <div class="login-wrap">
    <label for="email">@lang('login.login_email_label')</label>
    <div>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>


    <label for="password">@lang('login.login_password_label')</label>
    <div>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <button class="btn btn-lg btn-login btn-block" type="submit">@lang('login.login_submit_btn')</button>
</div>

</form>
@endsection
