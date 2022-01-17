<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Scheduler</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap3/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap3/bootstrap-reset.css') }}" rel="stylesheet">

    <!--external css-->
    <link href="{{ asset('css/bootstrap3/font-awesome.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/bootstrap3/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap3/style-responsive.css') }}" rel="stylesheet">
    <link href="calendar/css/style.css" rel="stylesheet">
    <link href="calendar/css/smoothness/jquery-ui.css" rel="stylesheet">
    <link href="calendar/css/fullcalendar.print.css" media="print" rel="stylesheet">
    <link href="calendar/css/fullcalendar.css" rel="stylesheet">
    <link href="calendar/lib/spectrum/spectrum.css" rel="stylesheet">
    <link href="calendar/lib/timepicker/jquery-ui-timepicker-addon.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container">
      <!--header start-->
      <header class="header white-bg">
              <div class="sidebar-toggle-box">
                  <i class="fa fa-bars"></i>
              </div>
            <!--logo start-->
            <a href="{{route('home')}}" class="logo" >Task<span>NTrack</span></a>
            <!--logo end-->
            <div class="top-nav ">
                <!--search & user info start-->
                <ul class="nav pull-right top-menu">
                    <!--<li>
                        <input type="text" class="form-control search" placeholder="Search">
                    </li>-->
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            @if(Auth::user()->user_image)
                            <img alt="" width="50" height="50" src="{{asset('storage/'.Auth::user()->user_image)}}">
                            @else
                            <img alt="" src="img/avatar1_small.jpg">
                            @endif
                            <span class="username">
                              {{Auth::user()->first_name}}
                              {{Auth::user()->last_name}}
                            </span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout dropdown-menu-right">
                            <div class="log-arrow-up"></div>
                            <li><a href="{{route('companies.profile')}}"><i class=" fa fa-suitcase"></i>Profile</a></li>
                            <li><a href="{{route('coming-soon')}}"><i class="fa fa-cog"></i> Settings</a></li>
                            <li><a href="{{route('coming-soon')}}"><i class="fa fa-question"></i> Help</a></li>
                            <li><a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-key"></i>
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                        </ul>
                    </li>
                    <!-- user login dropdown end -->
                </ul>
                <!--search & user info end-->
            </div>
        </header>
      <!--header end-->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
                <li>
                  <a class="@if(Route::current()->getName() == 'home') active @endif" href="{{route('home')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                  </a>
                </li>
                <li class="sub-menu">
                  <a class="@if(Request::is('tasks') || Request::is('tasks/*')) active @endif" href="javascript:void(0);">
                    <i class="fa fa-users"></i>
                    <span>Tasks</span>
                  </a>
                  <ul class="sub">
                    <li class="@if(Route::current()->getName()=='tasks.index') active @endif"><a href="{{route('tasks.index')}}">Listing</a></li>
                    <li class="@if(Request::is('tasks/create')) active @endif"><a href="{{route('tasks.create')}}" >Add a Task</a></li>
                    <li><a href="{{route('coming-soon')}}" >Site Tasks</a>
                      <ul class="sub">
                        <li><a href="{{route('tasks.index')}}" >All Tasks</a></li>
                        <li><a href="{{route('coming-soon')}}" >Task in Progress</a></li>
                        <li><a href="{{route('coming-soon')}}" >Assigned Tasks</a></li>
                        <li><a href="{{route('coming-soon')}}" >Completed Tasks</a></li>
                        <li><a href="{{route('coming-soon')}}" >Suggested Tasks</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li>
                  <a class="@if(Request::is('scheduler') || Request::is('scheduler/*')) active @endif" href="{{route('scheduler.index')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Schedule</span>
                  </a>
                </li>
                <li class="sub-menu">
                  <a class="@if(Request::is('sites') || Request::is('sites/*')) active @endif" href="javascript:void(0);" >
                    <i class="fa fa-map-marker"></i>
                    <span>Site</span>
                  </a>
                  <ul class="sub">
                    <li class="@if(Route::current()->getName()=='sites.index') active @endif"><a href="{{route('sites.index')}}">Listing</a></li>
                    <li class="@if(Route::current()->getName()=='sites.create') active @endif"><a href="{{route('sites.create')}}" >Add</a></li>
                  </ul>
                </li>
                <li class="sub-menu">
                  <a class="@if(Request::is('companies') || Request::is('companies/*')) active @endif" href="javascript:void(0);" >
                    <i class="fa fa-building-o"></i>
                    <span>Company</span>
                  </a>
                  <ul class="sub">
                    <li class="@if(Route::current()->getName()=='companies.index') active @endif"><a href="{{route('companies.index')}}">Listing</a></li>
                    <li class="@if(Route::current()->getName()=='companies.create') active @endif"><a href="{{route('companies.create')}}" >Add</a></li>
                  </ul>
                </li>
                <li class="sub-menu">
                  <a class="@if(Request::is('team-members') || Request::is('team-members/*')) active @endif"  href="javascript:void(0);" >
                    <i class="fa fa-users"></i>
                    <span>Team Members</span>
                  </a>
                  <ul class="sub">
                      <li class="@if(Route::current()->getName()=='team-members.index') active @endif"><a href="{{route('team-members.index')}}">Listing</a></li>
                      <li class="@if(Route::current()->getName()=='team-members.create') active @endif"><a href="{{route('team-members.create')}}" >Add</a></li>
                    </li>
                  </ul>
                </li>
                <li>
                  <a href="{{route('coming-soon')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Reports</span>
                  </a>
                </li>

              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      <!--main content start-->
      <section id="main-content">
        <section class="wrapper">
          @include('partials.flash')
          @yield('content')
        </section>
        <div class="clearfix"></div>
        <footer class="site-footer">
          <div class="text-center">
              2019 &copy; Task and Track.
              <a href="#" class="go-top">
                  <i class="fa fa-angle-up"></i>
              </a>
          </div>
        </footer>
        <!--footer start-->
      </section>
      <!--main content end-->
      <!--footer end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="{{ asset('js/bootstrap3/jquery.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap3/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap3/jquery.dcjqaccordion.2.7.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap3/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap3/jquery.nicescroll.js') }}"></script>
    <script src="{{ asset('js/bootstrap3/slidebars.min.js') }}"></script>
    <script src="calendar/lib/moment.js"></script>
    <script src="calendar/lib/jquery-ui.js"></script>
    <script src="calendar/js/fullcalendar.js"></script>
    <script src="calendar/js/lang-all.js"></script>
    <script src="calendar/js/jquery.calendar.js"></script>
    <script src="calendar/lib/spectrum/spectrum.js"></script>
    <script src="{{ asset('js/bootstrap3/common-scripts.js') }}"></script>
    <script src="calendar/lib/timepicker/jquery-ui-sliderAccess.js"></script>
    <script src="calendar/lib/timepicker/jquery-ui-timepicker-addon.min.js"></script>

     <!--call calendar plugin-->
    <script type="text/javascript">
    var baseCalendarPath='//taskntrack.com/calendar';
    var basePathLaravel = {!! json_encode(url('/')) !!}
    $().FullCalendarExt({
      calendarSelector: '#calendar',
      lang: 'en',
      fc_extend: {
        nowIndicator: true
      },
      nextDayThreshold: '24:00:00',
      displayEventEnd:true
    });

  </script>
  </body>
</html>
