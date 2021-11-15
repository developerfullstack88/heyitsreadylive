<aside>
  <div id="sidebar"  class="nav-collapse ">
    <ul class="sidebar-menu" id="nav-accordion">
        <li>
          <a class="" href="{{route('home')}}">
            <i class="fa fa-tachometer"></i>
            <span>@lang('common.sidebar_dashboard')</span>
          </a>
        </li>
        @if(auth()->user()->role==COMPANY)
          <li class="sub-menu">
            <a href="javascript:void(0);" >
              <i class="fa fa-map-marker"></i>
              <span>@lang('common.sidebar_location')</span>
            </a>
            <ul class="sub">
    				  <li><a href="{{route('sites.index')}}" >@lang('common.common_listing')</a></li>
    				  <li><a href="{{route('sites.create')}}" >@lang('common.common_add')</a></li>
    			  </ul>
          </li>
        @endif
        @if(auth()->user()->role!=SUPERVISOR)
          <li class="sub-menu d-none">
            <a href="javascript:void(0);" >
              <i class="fa fa-map-marker"></i>
              <span>Staff</span>
            </a>
            <ul class="sub">
    				  <li><a href="{{route('staffs.index')}}" >@lang('common.common_listing')</a></li>
    				  <li><a href="{{route('staffs.create')}}" >@lang('common.common_add')</a></li>
    			  </ul>
          </li>
        @endif
        <li>
          <a class="" href="{{route('QrOrder')}}">
            <i class="fa fa-qrcode"></i>
            <span>@lang('common.sidebar_order_qr')</span>
          </a>
        </li>
        <li>
          <a class="" href="{{route('itemQr',[0])}}">
            <i class="fa fa-qrcode"></i>
            <span>@lang('common.sidebar_order_menu_qr')</span>
          </a>
        </li>
        <!--<li class="sub-menu">
          <a href="javascript:void(0);" >
            <i class="fa fa-coffee"></i>
            <span>@lang('common.sidebar_setup')</span>
          </a>
          <ul class="sub">
            <li><a href="{{route('menus.index')}}">@lang('common.sidebar_menus')</a></li>
            <li><a href="{{route('taxes.index')}}">@lang('common.sidebar_taxes')</a></li>
  			  </ul>
        </li>-->
        @if(auth()->user()->role==COMPANY)
          <li class="sub-menu">
            <a href="javascript:void(0);" >
              <i class="fa fa-file"></i>
              <span>@lang('common.sidebar_reports')</span>
            </a>
            <ul class="sub">
    				  <li><a href="{{route('reports.index')}}" >@lang('common.sidebar_reports')</a></li>
              <li><a href="{{route('reports.payouts')}}" >Payouts</a></li>
    				  <li><a href="{{route('reports.payments')}}" >Payments</a></li>
    			  </ul>
          </li>
        @endif
        <li>
          <a class="" href="https://help.heyitsready.app/" target="_blank">
            <i class="fa fa-question-circle"></i>
            <span>@lang('common.sidebar_help')</span>
          </a>
        </li>
    </ul>
  </div>
</aside>
