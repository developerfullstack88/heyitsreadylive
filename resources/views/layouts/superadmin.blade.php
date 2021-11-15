<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="{{ URL::to('/img/favicon.ico') }}">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>
@include('partials.head')
</head>
<body>
	<section id="container">
		@include('partials.admin-header')
		@include('partials.admin-sidebar')
		<section id="main-content">
			<section class="wrapper">
				@include('partials.flash')
			@yield('content')
			</section>
			<div class="clearfix"></div>

		</section>
	</section>
	@include('partials.footer-scripts-admin')
	<script type="text/javascript">
		var APP_URL = {!! json_encode(url('/')) !!}
	</script>
	@yield('myScripts')
	<script>
	$(document).ready(function(){

	});
	</script>
 </body>
</html>
