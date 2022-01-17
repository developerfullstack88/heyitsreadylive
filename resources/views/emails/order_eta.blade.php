<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
    @if($orderData)
      <p>Hey, {{ucfirst($orderData['name'])}}</p>
      <p>{{$orderData['message']}}</p>
    @endif
    <p>You are able to track your order at <a href="www.heyitsready.com">www.heyitsready.com</a></p>
    <p>Thank you for using <b>Hey It’s Ready.</b></p>
    <p>Why not download our mobile app today and track all of your orders at <a href="https://www.heyitsready.com" target="_blank">www.heyitsready.com</a></p>
  </body>
</html>
