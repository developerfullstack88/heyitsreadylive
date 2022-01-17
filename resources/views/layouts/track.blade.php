<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="{{ URL::to('/img/favicon.ico') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap core CSS -->
    <link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/bootstrap-reset.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/bootstrap-reset.css')}}" rel="stylesheet">
    <!--external css-->
    <link href="{{URL::asset('assets/font-awesome/css/all.min.css')}}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{URL::asset('css/style.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/style-responsive.css')}}" rel="stylesheet" />
</head>
<body class="login-body">
  @include('partials.flash')
    <section class="container" style="padding-left:10px;padding-right:10px;">
        @yield('content')
    </section>
</body>
<script type="text/javascript">
  var APP_URL = {!! json_encode(url('/')) !!}
</script>
<script src="{{URL::asset('js/jquery.js')}}"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{URL::asset('js/jquery.mask.min.js')}}" ></script>
<script src="{{URL::asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{URL::asset('js/custom_login.js')}}"></script>
@yield('myScripts')
</html>
