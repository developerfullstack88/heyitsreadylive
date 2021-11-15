<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                left: 20%;
                top: 80%;
            }
            .track-button{
              position: absolute;
              left: 24%;
              top: 113%;
            }
            .track-button a{margin-left:46px;}

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .btn-huge{padding-top:15px;padding-bottom:15px;padding-left:27px;padding-right:28px;
            background:#000000;font-weight:bold;font-size:20px;border-radius:10px;}
            .btn-huge:hover{background:#1562A5;}
            .track-button .btn-huge{padding-left:45px;padding-right:45px;}
            .home-background-image .padding{padding-bottom:8rem;}
            .track-order-p{font-size:1.8rem;}
            .home-background-image img{max-width:25rem;}

            @media (max-width: 768px) and (max-height: 1024px) {
              .home-background-image img{max-width:25rem;}
              .top-right {
                  left: 20%;
                  top: 48%;
              }
              .btn-huge{padding-left:30px;padding-right:30px;font-size:18px;padding-top:15px;padding-bottom:15px;}
              .track-button{
                position: absolute;
                left: 22%;
                top: 61%;
              }
              .track-button .btn-huge{padding-left:30px;padding-right:30px;padding-top:10px;padding-bottom:10px;}
              .track-button a{margin-left: 57px;}
              .home-background-image .padding{padding-bottom:30rem;}

            }
            @media (max-width: 414px) and (max-height: 896px) {
              .home-background-image img{max-width:17rem;}
              .top-right {
                  left: 19%;
                  top: 0%;
              }
              .btn-huge{padding-left:25px;padding-right:25px;font-size:18px;padding-top:10px;padding-bottom:10px;}
              .track-button{
                position: absolute;
                left: 16%;
                top: 65%;
              }
              .track-button .btn-huge{padding-left:30px;padding-right:30px;padding-top:10px;padding-bottom:10px;}
              .home-background-image .padding{padding-bottom:40rem;}
              .track-order-p{font-size:1.5rem;}
              .track-button a{margin-left:30px;}

            }

            @media (max-width: 767px) {
              .home-background-image img{max-width:19rem;}
              .top-right {
                  left: 21%;
                  top: 55%;
              }
              .btn-huge{padding-left:17px;padding-right:17px;font-size:16px;padding-top:10px;padding-bottom:10px;}
              .btn-huge:last-child{padding-left:18px;padding-right:18px;font-size:16px;padding-top:10px;padding-bottom:10px;}
              .track-button{
                position: absolute;
                left: 16%;
                top: 65%;
              }
              .track-button .btn-huge{padding-left:30px;padding-right:30px;padding-top:10px;padding-bottom:10px;}
              .home-background-image .padding{padding-bottom:17rem;}
              .track-order-p{font-size:1.5rem;}
              .track-button a{margin-left:30px;}
            }
            @media (max-width: 896px) and (max-height: 414px) {
              .home-background-image img{max-width:16rem;}
              .home-background-image .padding{padding-bottom:8rem;}
              .top-right {
                  left: 21%;
                  top: 70%;
              }
              .btn-huge{padding-left:15px;padding-right:15px;font-size:15px;padding-top:10px;padding-bottom:10px;}
              .btn-huge:last-child{padding-left:13px;padding-right:13px;font-size:15px;padding-top:10px;padding-bottom:10px;}
              .btn-huge.mr-3{margin-right:0.7rem !important;}
            }
            @media (max-width: 568px) and (max-height: 320px) {
              .home-background-image .padding{padding-bottom:2rem;}
              .home-background-image img{max-width:16rem;}
              .top-right {
                  left: 21%;
                  top: 90%;
              }
            }
            @media (max-width: 320px) and (max-height: 568px) {
              .home-background-image img{max-width:16rem;}
              .home-background-image .padding{padding-bottom:8rem;}
              .top-right {
                  left: 21%;
                  top: 70%;
              }
              .btn-huge{padding-left:15px;padding-right:15px;font-size:15px;padding-top:10px;padding-bottom:10px;}
              .btn-huge:last-child{padding-left:13px;padding-right:13px;font-size:15px;padding-top:10px;padding-bottom:10px;}
              .btn-huge.mr-3{margin-right:0.7rem !important;}
            }
        </style>
    </head>
    <body>
        @include('partials.flash')
        <div class="flex-center position-ref full-height home-background-image">
          <div class="row">
            <div class="col-md-12">
              <div class="padding" id="welcomeLogo">
              <img src="../img/ipixup_background_image.png"/>
            </div>
              @if (Route::has('login'))
                <div class="top-right links d-flex">
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-primary btn-huge">@lang('common.welcome_home')</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-huge mr-3">@lang('common.welcome_login')</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-huge">@lang('common.welcome_register')</a>
                        @endif
                    @endauth
                </div>
              </div>
            </div>
            @endif
        </div>
    </body>
</html>
