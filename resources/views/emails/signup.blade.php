<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
      @if($userData)
        <p>Hey {{ucfirst($userData->first_name)}} {{ucfirst($userData->last_name)}},</p>
      @endif
      <p style="margin-top:50px;">Welcome to Hey It’s Ready!<p>
      <p style="margin-top:30px;">We are excited to have you as part of our mission to enable people to connect to and support the backbone of our world “Your Business”.</p>
      <p style="margin-top:50px;">There are two PDF setup guides attached to this email.One setup guide is to help you set up the full access use of the software and the second guide is for those companies wishing to just use the FREE Menu QR Code option.<p>
      <p style="margin-top:30px;">Both of these PDF setup guides are also located in your Hey It's Ready welcome drop down menu.</p>
      <p style="margin-top:30px;">If you have any questions or would like any help, please feel free to contact us at <a href="mailto:info@heyitsready.com">info@heyitsready.com</a></p>
      <p style="margin-top:50px;">Sincerely,</p>
      <p>The Team at Hey It’s Ready!</p>
      <img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:100px;margin-left:350px;"/>
  </body>
</html>
