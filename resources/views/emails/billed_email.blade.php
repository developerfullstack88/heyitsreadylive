<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
      @if($userData)
        <p>Dear, {{ucfirst($userData->first_name)}} {{ucfirst($userData->last_name)}}</p>
      @endif
      <p style="margin-top:50px;">You were just charged $35.00 USD for your monthly subscription of Hey It’s Ready .<p>

      <p style="margin-top:30px;">Thank you for choosing Hey It’s Ready, we appreciate your business. </p>

      <p style="margin-top:50px;">Sincerely,</p>
      <p>The Team at Hey It’s Ready!</p>
  </body>
</html>
