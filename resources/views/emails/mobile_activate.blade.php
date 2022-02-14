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
      <p>To activate your account please click on the link below.</p>
      <a data-click-track-id="37" href="{{$userData->url}}" style="margin-top: 36px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #ffffff; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 12px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: 0.7px; line-height: 48px; mso-line-height-rule: exactly; text-decoration: none;
      vertical-align: top; width: 770px; background-color: #00cc99; border-radius: 28px;
      display: block; text-transform: uppercase;text-align:center;" target="_blank">
        Click here to ACTIVATE YOUR ACCOUNT
      </a>
      <p>Once your email is verified, you can enjoy all our services.</p>
      <p>Sincerely,</p>
      <p>The Team at Hey It’s Ready!</p>
      <img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:100px;margin-left:300px;"/>
      <p><small>If you did not register on HeyIsReady, please disregard this email.</small></p>
  </body>
</html>
