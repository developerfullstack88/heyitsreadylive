<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
    @if($orderData)
      <p>Hey, {{ucfirst($orderData['name'])}}</p>
      <p>{{$orderData['message']}}</p>
      <p>Thank you for using <b>Hey It’s Ready.</b></p>
      <p>Why not download our mobile app today and track all of your orders at <a href="https://www.heyitsready.com" target="_blank">www.heyitsready.com</a></p>
    @endif
  </body>
</html>
