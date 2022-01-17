<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
      @if($userData)
        <p>Hey {{ucfirst($userData->first_name)}} {{ucfirst($userData->last_name)}},</p>
      @endif
      <p style="margin-top:50px;">Congratulations,You have successfully changed your password.<p>
      <p style="margin-top:50px;">Your new password is:-<p>
      <p><b>{{$userData->new_password}}</b></p>
      <p style="margin-top:30px;">If you have any questions, please feel free to contact us at <a href="mailto:info@heyitsready.com">info@heyitsready.com</a></p>
      <p style="margin-top:50px;">Sincerely,</p>
      <p>The Team at Hey It’s Ready!</p>
      <img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:100px;margin-left:350px;"/>
  </body>
</html>
