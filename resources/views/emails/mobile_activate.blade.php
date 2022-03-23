<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
      @if($userData)
        <p>Hey, {{ucfirst($userData->first_name)}} {{ucfirst($userData->last_name)}}</p>
      @endif
      <p>Thank you for choosing Hey It's Ready, you have made a great decision.<p>
      <p>Please use this code on mobile app for activating your account:</p>
      <p><b>{{$userData->active_code}}</b></p>
      <p>Once your account is active, you can enjoy all our services.</p>
      <p>Sincerely,</p>
      <p>The Team at Hey It’s Ready!</p>
      <img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:100px;margin-left:300px;"/>
      <p><small>If you did not register on HeyIsReady, please disregard this email.</small></p>
  </body>
</html>
