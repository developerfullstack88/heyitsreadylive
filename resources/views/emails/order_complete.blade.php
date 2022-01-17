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
    <p>Thank you for using <b>Hey It’s Ready</b> to track your order</p>
    <p>If you would like to track your future orders please download our mobile app at <a href="https://www.heyitsready.com" target="_blank">www.heyitsready.com</a></p>
  </body>
</html>
