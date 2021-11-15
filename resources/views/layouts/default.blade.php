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
		@include('partials.header')
		@if(checkFreeSoftwareExpire() && checkSubscriptionExpire())
			@include('partials.sidebar-free')
		@else
			@include('partials.sidebar')
		@endif
		<section id="main-content">
			<section class="wrapper">
				@include('partials.flash')
			@yield('content')
			</section>
			<div class="clearfix"></div>

		</section>
	</section>
	@include('partials.modal')
	<script type="text/javascript">
		var APP_URL = {!! json_encode(url('/')) !!}
		var COMPANY_ID={!! Auth::user()->company_id !!}
		var currentDateTime='<?=currentLocalTime(); ?>';
		var TIMEZONE='{!! Auth::user()->timezone !!}';
		var CURRENTUSERLANG='{!! Session::get('locale') !!}';
		var FILTERTYPE="{!! Request::get('type') !!}";
	</script>
	@include('partials.footer-scripts')
	@yield('myScripts')
	@yield('myScripts2')
	<!-- The core Firebase JS SDK is always required and must be listed first -->
	<!--<script src="//www.gstatic.com/firebasejs/6.3.4/firebase.js"></script>-->

	<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-messaging.js"></script>
	<script>
	$(document).ready(function(){
			const config = {
				apiKey: "AIzaSyAi3RTmC35PzSoM6chGFgXJqj-MCL4O3TY",
				authDomain: "ipixup-web-e4900.firebaseapp.com",
				databaseURL: "https://ipixup-web-e4900.firebaseio.com",
				projectId: "ipixup-web-e4900",
				storageBucket: "ipixup-web-e4900.appspot.com",
				messagingSenderId: "548664581545",
				appId: "1:548664581545:web:57abfb8ddda27b3912b8b8",
				measurementId: "G-QBW7CTZSXH"

			};
			firebase.initializeApp(config);
			const messaging = firebase.messaging();

			messaging
					.requestPermission()
					.then(function () {
							return messaging.getToken()
					})
					.then(function(token) {
							$.ajaxSetup({
									headers: {
											'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
									}
							});
							$.ajax({
									url: '{{ URL::to('/save-device-token') }}',
									type: 'POST',
									data: {fcm_token: token},
									dataType: 'JSON',
									success: function (response) {
											console.log(response)
									},
									error: function (err) {
											console.log(" Can't do because: " + err);
									},
							});
					})
					.catch(function (err) {
							console.log("Unable to get permission to notify.", err);
					});

			messaging.onMessage(function(payload) {
					const noteTitle = payload.notification.title;
					const noteOptions = {
							body: payload.notification.body,
							icon: payload.notification.icon,
					};
					var pushType=payload.data.type;
					var oid=payload.data.order_id;
					if(pushType=='order_cancel'){
						$('#home_order_'+oid).remove();
					}
					console.log(payload);
					if(pushType=='order_confirmed'){
						if(!$('#home_order_'+oid+' .btns-td button').hasClass('home-btn-ready')) {
							$('#home_order_'+oid+' .btns-td .orderDelay').after('<button class="btn btn-info btn-sm change-status ml-1 home-btn-ready" data-status="ready" data-id="'+oid+'" type="button">'+READYBTNTEXT+'</button>');
						}
					}
					new Notification(noteTitle, noteOptions);
			});

			$('[data-toggle="tooltip"]').tooltip();
	});
	</script>
 </body>
</html>
