@if($orders->count()>0)
    @foreach($orders as $order)
    <tr>
      <td><input type="checkbox" class="single-chk-box" value="{{$order->id}}"/></td>
      <td>
        {{$order->order_number}}
        <span class="d-none hidden-order-amount">{{$order->amount}}</span>
        <span class="d-none hidden-order-number">{{$order->order_number}}</span>
      </td>
      <td class="home-order-spot-number">{{$order->spot_number}}</td>
      <td>{{$order->user->name}}</td>
      <td>
        @if(checkPaidOrder($order->id))
           <button class="btn btn-success">Yes</button>
           <!--<img src="{{asset('img/tick-mark.jpg')}}" height="25" width="25"/>-->
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
      <td class="eta-td" data-status="{{$order->status}}">
        @if($order->eta)
         @if(checkTimeGreater(convertToLocal($order->eta,1)) && $order->status!='complete')
           <span class="etaTimeDiff" data-id="{{$order->id}}" data-date="{{convertToLocal($order->eta)}}">{{convertToLocal($order->eta,3)}}</span>
         @elseif($order->eta && $order->status=='pending' || $order->status=='confirm')
          @if(!checkTimeGreater(convertToLocal($order->eta,1)))
           {{convertToLocal($order->eta,3)}}
          @endif
         @endif
        @else
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
      <td class="btns-td">
        @if($order->status!='complete')
          @if($order->eta && $order->status!='ready')
           {{Form::button(trans('dashboard.table_delayed_btn'),["class"=>"btn btn-primary btn-sm orderDelay home-btn-delayed","data-id"=>$order->id])}}
         @endif
         @if($order->eta && ($order->status=='pending' || $order->status=='confirm'))
           {{Form::button(trans('dashboard.table_ready_btn'),["class"=>"btn btn-info btn-sm change-status home-btn-ready","data-status"=>"ready","data-id"=>$order->id])}}
         @endif
         @if($order->status=='ready')
           {{Form::button(trans('dashboard.table_completed_btn'),["class"=>"btn btn-danger btn-sm change-status home-btn-completed","data-status"=>"complete","data-id"=>$order->id])}}
         @endif
         @if($order->eta && $order->status=='pending' || $order->status=='confirm')
          @if(!checkTimeGreater(convertToLocal($order->eta,1)))
           {{--{{Form::button('Passed Due',["class"=>"btn btn-danger btn-sm orderDelay passed-due","data-id"=>$order->id])}}--}}
          @endif
         @endif
       @else
         {{Form::button(trans('dashboard.table_completed_btn'),["class"=>"btn btn-secondary btn-sm home-btn-completed"])}}
       @endif
       {{Form::button(trans('dashboard.table_detail_btn'),["class"=>"btn btn-success btn-sm btn-view-detail  home-btn-detail mt-btn-10","data-id"=>$order->id])}}
       {{Form::button(trans('dashboard.table_print_btn'),["class"=>"btn btn-success btn-sm
       btn-view-print mt-btn-10","data-id"=>$order->id])}}
      </td>
    </tr>
    @endforeach
  @endif
