<header class="header white-bg">
    <div class="navbar-header">
      <div class="row">
        <div class="col-md-3 col-4">
          <div class="sidebar-toggle-box">
              <i class="fa fa-bars"></i>
          </div>
          <!--logo start-->
          <a href="{{route('home')}}" class="logo" >
            <img src="{{asset('img/ipixup_background_image.png')}}" class="ipix-logo"/>
          </a>
          <!--logo end-->
        </div>
        <div class="col-md-5 order-2 order-md-1 text-center font-title" id="clockTime">
          @lang('dashboard.dashboard_top_date'): <span id="clockTimeClock">{{dashboardDate()}}</span>
          <span class="ml-2 ml-xs-2"></span>@lang('dashboard.dashboard_top_time'): <span id="clockTimeOnly">{{dashboardTime()}}</span>
        </div>
        <div class="col-md-4 col-8 order-1 order-md-2">
          <div class="top-nav ">
              <!--search & user info start-->
              <ul class="nav pull-right top-menu">
                  <!--<li>
                      <input type="text" class="form-control search" placeholder="Search">
                  </li>-->
                  <!-- user login dropdown start-->
                  <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          @if(auth()->user()->profile_photo && auth()->user()->role==COMPANY)
                          <!--<img alt="" id="ipixupProfileIcon" width="30" height="30" src="{{asset('storage/'.Auth::user()->profile_photo)}}">-->
                          @elseif(auth()->user()->profile_photo)
                            <img alt="" id="ipixupProfileIcon" width="30" height="30"
                            src="{{asset(auth()->user()->profile_photo)}}">
                          @else
                          <!--<img alt="" id="ipixupProfileIcon" width="30" height="30" src="/img/avatar1_small.jpg">-->
                          @endif
                          <span class="username">
                            Welcome {{Auth::user()->first_name}}
                            {{Auth::user()->last_name}}
                          </span>
                          <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu extended logout dropdown-menu-right">
                          <div class="log-arrow-up"></div>
                          <li><a href="{{route('users.show',auth()->user()->id)}}"><i class=" fa fa-suitcase"></i>@lang('common.top_menu_dropdown_profile')</a></li>
                          <li><a href="{{route('settings.index')}}"><i class="fa fa-cog"></i> @lang('common.top_menu_dropdown_settings')</a></li>
                          <li><a href="{{route('setupGuides')}}" target="_blank"><i class="fa fa-question"></i>Setup Guide</a></li>
                          <li><a href="{{ route('logout') }}"
                                     onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();"><i class="fa fa-key"></i>
                                      @lang('common.top_menu_dropdown_logout')
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
        </div>
      </div>

    </div>
      <!--logo start-->
      <a href="index.html" class="logo">{{Auth::user()->company_name}}</a>
      <!--logo end-->

  </header>
