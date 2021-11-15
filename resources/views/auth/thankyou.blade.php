@extends('layouts.login')

@section('content')
<div class="row">
	 <div class="col-md-12 text-center">
	 		<img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:12rem;">
	 </div>
</div>
<div class="thanks-page">
  <div class="text-center position-relative">
    <i class="fa fa-check-square-o check-icon" aria-hidden="true"></i> <h3 class="ml-2 color-thanks">Success! One more step!</h3>
    <p class="font-19 color-light font-weight-bold">Your information has been added to our system and your account has been created.</p>
    <p class="font-19 font-weight-bold color-dark">Please click the activation link in the email that was just sent to you.</p>
    <p class="font-19 color-light font-weight-bold">Activation is required in order to ensure your are the owner of the email address provided.</p>
    <div class="position-relative">
      <i class="fa fa-envelope envelope-icon" aria-hidden="true"></i>
      <i class="fa fa-check circle-icon" aria-hidden="true"></i>
    </div>
  </div>
</div>

<!-- style -->
<style>

.thanks-page{margin-top:50px;}
.login-body{background:#fff;}
.check-icon{position: absolute;
top: 0%;
left: 31%;
font-size: 35px;
color:#219FB7;
}
.color-thanks{color:#219FB7;}
.font-19{font-size:19px;}
.color-light{color:#B9B8BA;}
.color-dark{color:#343335;}
.envelope-icon{color:#59585C;
  font-size: 50px;
}
.circle-icon{font-size: 20px;
position: absolute;
color: #2197bf;
right: 47%;
top:0%;
background: #fff;
border: 1px solid #2197bf;
border-radius: 50%;
}
</style>

@endsection
