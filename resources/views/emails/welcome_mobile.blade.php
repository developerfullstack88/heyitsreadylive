<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
      @if($userData)
        <p>Hey, {{ucfirst($userData->first_name)}} {{ucfirst($userData->last_name)}}</p>
      @endif
      <p>Thank you very much for using the HeyIsReady service, the platform to know the status of your order and optimize your time.<p>
      <p>With our service you will know the moment in which your order is ready to be withdrawn, saving your time.</p>
      <p>Keep an eye out for the next email with the link to activate your account.Please let us know If you have any questions, we’re always ready to answer any emails I get.</p>
      <p>Sincerely,</p>
      <p>The Team at Hey It’s Ready!</p>
      <p><small>If you did not register on HeyIsReady, please disregard this email.</small></p>
  </body>
</html>
