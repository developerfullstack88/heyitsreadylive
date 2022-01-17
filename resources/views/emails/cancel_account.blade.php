<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
      Hey,
      @if($userData)
        <p style="margin-top:50px;">{{ucfirst($userData->first_name)}} {{ucfirst($userData->last_name)}} has cancelled his account.</p>
      @endif
  </body>
</html>
