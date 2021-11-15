<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
      @if($userData)
        <p>Dear, {{ucfirst($userData->first_name)}} {{ucfirst($userData->last_name)}}</p>
      @endif

      <p style="margin-top:50px;">Your account is Suspended for 90 Days.<p>

      <p style="margin-top:30px;">After 90 days your account will be permanent deleted.</p>
      
      <p style="margin-top:30px;">If you have any questions please send your inquiries to <a href="mailto:info@heyitsready.com">info@heyitsready.com</a></p>

      <p style="margin-top:50px;">Sincerely,</p>
      <p>The Team at Hey It’s Ready!</p>
  </body>
</html>
