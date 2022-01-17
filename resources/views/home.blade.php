@extends('layouts.default')

@section('content')
<div class="row state-overview">
    <div class="col-lg-3 col-sm-6">
        <section class="card">
            <div class="symbol background-gray">
                <i class="fa fa-user"></i>
            </div>
            <div class="value">
                <h1 class="count count-common" id="dashboardInProgressCount">
                    {{getInprogressOrders()}}
                </h1>
                <p class="ml-1 mr-1 font-title">@lang('dashboard.dashboard_progress_order_top')</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="card">
            <div class="symbol red">
                <i class="fa fa-tags"></i>
            </div>
            <div class="value">
                <h1 class=" count2 count-common" id="dashboardReadyCount">
                    {{getReadyOrders()}}
                </h1>
                <p class="ml-1 mr-1 font-title">@lang('dashboard.dashboard_ready_order_top')</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="card">
            <div class="symbol yellow">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="value">
                <h1 class=" count3 count-common" id="dashboardGeoFenceCount">
                    {{getGeofenceCustomers()}}
                </h1>
                <p class="ml-1 mr-1 font-title">@lang('dashboard.dashboard_geofence_order_top')</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="card">
            <div class="symbol background-purple">
                <i class="fas fa-th-list"></i>
            </div>
            <div class="value">
                <h1 class=" count4 count-common" id="dashboardCompletedCount">
                    {{getCompletedOrders()}}
                </h1>
                <p class="ml-1 mr-1 font-title">@lang('dashboard.dashboard_complete_order_top')</p>
            </div>
        </section>
    </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <center>
      @if(getDefaultLocationLoggedUser())
        <a id="addNewOrderBtn" class="btn btn-info btn-lg btn-huge font-title" href="{{route('orders.create')}}">@lang('dashboard.add_new_order_btn')</a>
      @endif
      @if(currentUser() && currentUser()->delete_complete_order == 1
      && (isset($OrdersComplete) && $OrdersComplete->count()>0) && Request::get('type')=='completed')
        <a id="home-delete-complete-btn" class="btn btn-info btn-lg btn-huge ml-2 font-title"
        href="{{route('orders.delete-complete')}}" onclick="return confirm('Do you want to delete complete orders?')">@lang('dashboard.delete_complete_order_btn')</a>
      @elseif(currentUser() && currentUser()->delete_complete_order == 1)
      <a id="home-delete-complete-btn" class="btn btn-info btn-lg btn-huge ml-2 font-title" style="display:none;"
      href="{{route('orders.delete-complete')}}" onclick="return confirm('Do you want to delete complete orders?')">@lang('dashboard.delete_complete_order_btn')</a>
      @endif
      {{Form::button('Delete Checked',["id"=>"delete-selected-order","class"=>"btn btn-danger-red btn-lg btn-huge ml-2 font-title"])}}
    </center>
  </div>
</div>
<br/>
<div class="row">
  <div class="col-lg-12 col-12">
      <section class="card">
          <header class="card-header font-title">
              {{ucfirst(Request::get('type'))}} Orders
          </header>
          <div class="card-body">
            <div class="col-lg-12">
              <div class="float-right mb-2">
                <a class="btn btn-md font-title {{Request::get('type')=='all'?'btn-secondary':'btn-info'}}" href="{{Request::get('type')!='all'?route('home',['type'=>'all']):'javascript:void(0);'}}">
                  @lang('dashboard.all_filter_label')
                </a>
                <a class="btn btn-md font-title {{Request::get('type')=='' || Request::get('type')=='active'?'btn-secondary':'btn-info'}}" href="{{Request::get('type')!='active'?route('home',['type'=>'active']):'javascript:void(0);'}}">
                  @lang('dashboard.active_filter_label')
                </a>
                <a class="btn btn-md font-title {{Request::get('type')=='completed'?'btn-secondary':'btn-info'}}" href="{{Request::get('type')!='completed'?route('home',['type'=>'completed']):'javascript:void(0);'}}">
                  @lang('dashboard.completed_filter_label')
                </a>
                <a class="btn btn-md font-title {{Request::get('type')=='future'?'btn-secondary':'btn-info'}}" href="{{Request::get('type')!='future'?route('home',['type'=>'future']):'javascript:void(0);'}}">
                  @lang('dashboard.future_filter_label')
                </a>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-advance table-hover" id="dashboardTable">
                  <thead>
                      <tr>
                        <th class="chkbox-table-th"><input type="checkbox" id="all-dashboard-chkbox"></th>
                        <th class="font-title order-number-th">@lang('dashboard.table_th_order_number')</th>
                        <th class="font-title spot-number-th">@lang('dashboard.table_th_spot_number')</th>
                        <th class="font-title spot-color-th">Car Color</th>
                        <th class="font-title customer-name-th">@lang('dashboard.table_th_customer_name')</th>
                        <th class="font-title paid-order-th">@lang('dashboard.table_th_paid_order')</th>
                        <th class="font-title order-time-user-th">@lang('dashboard.table_th_order_confirm')</th>
                        <th class="font-title eput-table-th">@lang('dashboard.table_th_eput')</th>
                        <th class="font-title timer-table-th">
                          @lang('dashboard.table_th_timer')
                        </th>
                        <th class="font-title locate-order-th">@lang('dashboard.table_th_located')</th>
                        <th class="font-title actions-table-th">
                          @lang('dashboard.quick_actions_filter_label')
                        </th>
                      </tr>
                  </thead>
                  <tbody id="dashboardOrder">
                    @if($orders->count()>0)
                      @foreach($orders as $order)
                         <tr id="home_order_{{$order->id}}" class="{{($order->eta && ($order->status=='pending' || $order->status=='confirm') && !checkTimeGreater(convertToLocal($order->eta,1)))?'table-background-red':''}}">
                           <td><input type="checkbox" class="single-chk-box" value="{{$order->id}}"/></td>
                           <td>
                             {{$order->order_number}}
                             <span class="d-none hidden-order-amount">{{$order->amount}}</span>
                             <span class="d-none hidden-order-number">{{$order->order_number}}</span>
                           </td>
                           <td class="home-order-spot-number">{{$order->spot_number}}</td>
                           <td class="home-order-spot-color">{{$order->spot_color}}</td>
                           <td>{{$order->user->name}}</td>
                           <td>
                             @if(checkPrePaidOrder($order->id))
                                <button class="btn btn-success">Yes</button>
                             @else
                              <button class="btn btn-danger">No</button>
                             @endif
                           </td>
                           <td>
                             @if($order->confirm==1)
                                <button class="btn btn-success">Yes</button>
                             @else
                              <button class="btn btn-danger">No</button>
                             @endif
                           </td>
                           <td class="etaTimeDiffNotify" data-id="{{$order->id}}" style="display:none;">
                             @if($order->eta)
                                {{convertToLocal($order->eta)}}
                             @endif
                           </td>
                           <td class="eta-td" data-status="{{$order->status}}" data-locate="{{$order->locate}}">
                             @if($order->eta)
                              @if(checkTimeGreater(convertToLocal($order->eta,1)) && $order->status!='complete')
                            <span class="etaTimeDiff" data-id="{{$order->id}}" data-date="{{convertToLocal($order->eta)}}">
                              @if(Request::get('type')=='active' || Request::get('type')=='')
                                {{convertToLocal12TimeOnly($order->eta,3)}}
                              @else
                                {{convertToLocal12($order->eta,3)}}
                              @endif

                            </span>
                              @elseif($order->eta && $order->status=='pending' || $order->status=='confirm')
                               @if(!checkTimeGreater(convertToLocal($order->eta,1)))
                               @if(Request::get('type')=='active' || Request::get('type')=='')
                                 {{convertToLocal12TimeOnly($order->eta,3)}}
                               @else
                                 {{convertToLocal12($order->eta,3)}}
                               @endif
                               @endif
                              @endif
                             @elseif($order->status!='complete')
                              <a href="javascript:void(0);" data-id="{{$order->id}}" class="btn btn-danger btn-sm set-eta">@lang('dashboard.table_set_eput_btn')</a>
                             @endif
                           </td>
                           <td class="timer-td">
                             @if($order->eta && $order->status!='complete')
                              @if(checkTimeGreater(convertToLocal($order->eta,1)))
                                {{getTimeDifference($order->eta)}}
                              @endif
                             @endif
                           </td>
                           <td class="home-order-locate">
                             @if(checkOrderLocate($order->id))
                                <img src="{{asset('img/tick-mark.jpg')}}" height="25" width="25"/>
                             @endif
                           </td>
                           @if($order->call_btn==0)
                             <td class="btns-td">
                               @if($order->status!='complete')
                                 @if($order->eta && $order->status!='ready')
                                  {{Form::button(trans('dashboard.table_delayed_btn'),["class"=>"btn btn-primary btn-sm orderDelay home-btn-delayed","data-id"=>$order->id])}}
                                @endif
                                @if($order->eta && $order->status!='ready')
                                  {{Form::button(trans('dashboard.table_ready_btn'),["class"=>"btn btn-info btn-sm change-status home-btn-ready","data-status"=>"ready","data-id"=>$order->id])}}
                                @endif
                                @if($order->status=='ready')
                                  {{Form::button(trans('dashboard.table_reminder_btn'),["class"=>"btn btn-info btn-sm ready-reminder","data-id"=>$order->id])}}
                                  @if($order->amount>0 || checkPaidOrder($order->id))
                                    {{Form::button(trans('dashboard.table_completed_btn'),["class"=>"btn btn-danger btn-sm change-status home-btn-completed","data-status"=>"complete","data-id"=>$order->id])}}
                                  @else
                                    {{Form::button('Cash Payment',["class"=>"btn btn-danger btn-sm cash-payment-action  home-btn-completed","data-status"=>"payment","data-id"=>$order->id])}}
                                  @endif
                                @endif
                                @if($order->eta && $order->status=='pending' || $order->status=='confirm')
                                 @if(!checkTimeGreater(convertToLocal($order->eta,1)))
                                  {{--Form::button('Passed Due',["class"=>"btn btn-danger btn-sm orderDelay passed-due","data-id"=>$order->id])--}}
                                 @endif
                                @endif
                              @else
                                {{Form::button(trans('dashboard.table_completed_btn'),["class"=>"btn btn-secondary btn-sm home-btn-completed"])}}
                              @endif
                              {{Form::button(trans('dashboard.table_detail_btn'),["class"=>"btn btn-success btn-sm btn-view-detail home-btn-detail mt-btn-10","data-id"=>$order->id])}}
                              {{Form::button('Print',["class"=>"btn btn-warning btn-sm btn-view-print mt-btn-10","data-id"=>$order->id])}}
                             </td>
                            @else
                            <td class="btns-td">
                              @if($order->status!='complete')
                                @if($order->eta && $order->status!='ready')
                                 {{Form::button(trans('dashboard.table_completed_btn'),["class"=>"btn btn-danger btn-sm change-status home-btn-completed","data-status"=>"complete","data-id"=>$order->id])}}
                               @endif
                               @if($order->eta && $order->status=='pending' || $order->status=='confirm')
                                @if(!checkTimeGreater(convertToLocal($order->eta,1)))
                                 {{--Form::button('Passed Due',["class"=>"btn btn-danger btn-sm orderDelay passed-due","data-id"=>$order->id])--}}
                                @endif
                               @endif
                             @else
                               {{Form::button(trans('dashboard.table_completed_btn'),["class"=>"btn btn-secondary btn-sm home-btn-completed"])}}
                             @endif
                             {{Form::button(trans('dashboard.table_call_user_btn'),["class"=>"btn btn-success btn-sm btn-view-detail home-btn-call-user mt-btn-10","data-id"=>$order->id])}}
                             {{Form::button('Print',["class"=>"btn btn-warning btn-sm
                             btn-view-print mt-btn-10","data-id"=>$order->id])}}
                            </td>
                            @endif
                            <td id="dashboardReadyBtnText" style="display:none;">@lang('dashboard.table_ready_btn')</td>
                            <td id="dashboardCompletedBtnText" style="display:none;">@lang('dashboard.table_completed_btn')</td>
                            <td id="dashboardDelayedBtnText" style="display:none;">@lang('dashboard.table_delayed_btn')</td>
                            <td id="dashboardDetailBtnText" style="display:none;">@lang('dashboard.table_detail_btn')</td>
                            <td id="dashboardPrintBtnText" style="display:none;">@lang('dashboard.table_print_btn')</td>
                            <td id="dashboardReminderBtnText" style="display:none;">@lang('dashboard.table_reminder_btn')</td>
                         </tr>
                      @endforeach
                    @endif
                  </tbody>
              </table>
            </div>
          </div>
          <form style="display:none;" method="post" action="{{route('orders.delete.selected')}}" id="selectedChkboxForm">
            @csrf
            <input type="text" name="hiddenOrderId" id="hiddenOrderId" value=""/>
          </form>
      </section>
  </div>
</div>
@if($defaultSiteModal)
  @include('partials.default-site-modal')
@endif
@endsection
@section('myScripts')
<style>
.tablesorter-header {
    background-image: url(data:image/gif;base64,R0lGODlhFQAJAIAAACMtMP///yH5BAEAAAEALAAAAAAVAAkAAAIXjI+AywnaYnhUMoqt3gZXPmVg94yJVQAAOw==);
    background-position:62% 54%;
    background-repeat: no-repeat;
    cursor: pointer;
    white-space: normal;
    padding: 4px 20px 4px 4px;
}
.tablesorter-headerAsc {
    background-image: url(data:image/gif;base64,R0lGODlhFQAEAIAAACMtMP///yH5BAEAAAEALAAAAAAVAAQAAAINjI8Bya2wnINUMopZAQA7);
}
.tablesorter-headerDesc {
    background-image: url(data:image/gif;base64,R0lGODlhFQAEAIAAACMtMP///yH5BAEAAAEALAAAAAAVAAQAAAINjB+gC+jP2ptn0WskLQA7);
}
.tablesorter .sorter-false {
    background-image: none;
    cursor: default;
    padding: 4px;
}
.order-number-th.tablesorter-header{
  background-position:105% 54%;
}
.spot-number-th.tablesorter-header{
  background-position:102% 54%;
}
.spot-color-th.tablesorter-header{
  background-position:90% 54%;
}
.customer-name-th.tablesorter-header{
  background-position:92% 54%;
}
.paid-order-th.tablesorter-header{
  background-position:104% 54%;
}
.order-time-user-th.tablesorter-header{
  background-position:98% 54%;
}
.eput-table-th.tablesorter-header{
  background-position:61% 54%;
}
.timer-table-th.tablesorter-header{
  background-position:62% 54%;
}
.locate-order-th.tablesorter-header{
  background-position:112% 54%;
}
@media (max-width: 1024px) and (max-height: 1366px) {
  .order-number-th.tablesorter-header{
    background-position:110% 70%;
  }
  .spot-number-th.tablesorter-header{
    background-position:94% 70%;
  }
  .spot-color-th.tablesorter-header{
    background-position:115% 70%;
  }
  .customer-name-th.tablesorter-header{
    background-position:88% 70%;
  }
  .paid-order-th.tablesorter-header{
    background-position:110% 70%;
  }
  .order-time-user-th.tablesorter-header{
    background-position:110% 68%;
  }
  .eput-table-th.tablesorter-header{
    background-position:110% 67%;
  }
  .timer-table-th.tablesorter-header{
    background-position:110% 70%;
  }
  .locate-order-th.tablesorter-header{
    background-position:114% 70%;
  }
}
@media (max-width: 768px) and (max-height: 1024px) {
  .tablesorter-header {
      background-position:105% 54%;
  }
  .timer-table-th.tablesorter-header{
    background-position:115% 70%;
  }
}
</style>
<link href="{{URL::asset('css/theme.bootstrap_4.min.css')}}"/>
<script src="{{URL::asset('js/jquery.tablesorter.min.js')}}"></script>
<script src="{{URL::asset('js/jquery.tablesorter.widgets.min.js')}}"></script>
<script type="text/javascript">
/*dashboard count*/
var DASHINPROGRESSCOUNT={!! getInprogressOrders() !!};
var DASHREADYCOUNT={!! getReadyOrders() !!};
var DASHCOMPLETEDCOUNT={!! getCompletedOrders() !!};
var DASHGEOFENCECUSTOMERCOUNT={!! getGeofenceCustomers() !!};
var READYBTNTEXT=$('#dashboardReadyBtnText').text();
var COMPLETEDBTNTEXT=$('#dashboardCompletedBtnText').text();
var CASHPAYMENTBTNTEXT='Cash Payment'
var DELAYEDBTNTEXT=$('#dashboardDelayedBtnText').text();
var DETAILDBTNTEXT=$('#dashboardDetailBtnText').text();
var PRINTBTNTEXT=$('#dashboardPrintBtnText').text();
var REMINDERBTNTEXT=$('#dashboardReminderBtnText').text();

var FREESOFTWAREEXPIRE='{!! checkFreeSoftwareExpire() !!}';
var SUBSCRIPTIONEXPIRE='{!! checkSubscriptionExpire() !!}';
var CURRENTLANG='{!! Config::get('app.locale') !!}';

if(FREESOFTWAREEXPIRE && SUBSCRIPTIONEXPIRE){
  $('#freeTrialExpire').modal('show');
}
/*dashboard count*/
var DEFAULTSITEMODAL='{!! $defaultSiteModal !!}';
var DEFAULTLOGGEDSITE='{!! getDefaultLocationLoggedUser() !!}';
</script>
<script type="text/javascript">
    $(function(){
      /*set default or change default location*/
        if(DEFAULTSITEMODAL==1 && DEFAULTLOGGEDSITE){
          $('#defaultSiteModal').modal('show');
        }
        if(DEFAULTSITEMODAL==1 && !DEFAULTLOGGEDSITE){
          $('#defaultNoSiteModal').modal('show');
        }
      /*set default or change default location*/
        $('#printOut').click(function(e){
            e.preventDefault();
            $('#viewOrderDetail').find('.hide-print-link').hide();
            $('.modal-title').css('font-weight','bold');
            var contents = document.getElementById("viewOrderDetail").innerHTML;
            $('#viewOrderDetail').find('.hide-print-link').show();
            var frame1 = document.createElement('iframe');
            frame1.name = "frame1";
            frame1.style.position = "absolute";
            frame1.style.top = "-1000000px";
            document.body.appendChild(frame1);
            var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
            frameDoc.document.open();
            frameDoc.document.write('<html><head>');
            frameDoc.document.write('</head><body>');
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                document.body.removeChild(frame1);
            }, 500);
            return false;
        });

        $("#dashboardTable").tablesorter({
          theme : "bootstrap",
          headers: {
              0: {sorter: false},
              1: {sorter: true},
              2: {sorter: true},
              3: {sorter: true},
              4: {sorter: true},
              5: {sorter: true},
              6: {sorter: true},
              7: {sorter: true},
              8: {sorter: true},
              9: {sorter: true},
              10: {sorter: false},
          },
          sortList: [[7,0]]
        });
    });
</script>
@endsection
