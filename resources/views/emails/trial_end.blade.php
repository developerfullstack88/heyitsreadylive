<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
      @if($userData)
        <p>Dear, {{ucfirst($userData->first_name)}} {{ucfirst($userData->last_name)}}</p>

        <p style="margin-top:50px;">Your FREE trial period for Hey It’s Ready will end on {{$userData->date}} at around {{$userData->time}} {{$userData->timezone}}.<p>

        <p style="margin-top:30px;">Your card XXXX-XXXX-XXXX-{{$userData->card_number}} will be charged for the estimated amount due of $35.00 USD. (The estimated amount does not include any add-on or overage charges that may be added between now and renewal.)</p>
      @endif
      <p style="margin-top:30px;">If you wish to only use the Free version of Hey It’s Ready for the Menu QR Code please go to your Hey It’s Ready dashboard and Select “Settings” and then choose “Billing” and select the option for just the Menu QR Code.</p>

      <p style="margin-top:30px;">If you have any questions please send your inquiries to <a href="mailto:info@heyitsready.com">info@heyitsready.com</a></p>

      <p style="margin-top:50px;">Sincerely,</p>
      <p>The Team at Hey It’s Ready!</p>
  </body>
</html>
