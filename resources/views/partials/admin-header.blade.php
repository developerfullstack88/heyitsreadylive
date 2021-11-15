<header class="header white-bg">
    <div class="navbar-header">
      <div class="row">
        <div class="col-md-8 col-4">
          <div class="sidebar-toggle-box">
              <i class="fa fa-bars"></i>
          </div>
          <!--logo start-->
          <a href="{{route('admin.dashboard')}}" class="logo" >
            <img src="{{asset('img/ipixup_background_image.png')}}" class="ipix-logo"/>
          </a>
          <!--logo end-->
        </div>
        <div class="col-md-4 col-8 order-1 order-md-2">
          <div class="top-nav ">
              <!--search & user info start-->
              <ul class="nav pull-right top-menu">
                  <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <img alt="" id="ipixupProfileIcon" width="30" height="30" src="/img/avatar1_small.jpg">

                          <span class="username">
                            {{Auth::user()->name}}
                          </span>
                          <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu extended logout dropdown-menu-right">
                          <div class="log-arrow-up"></div>
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
  </header>
