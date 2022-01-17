@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <section class="card">
	        <header class="card-header font-title">@lang('report.basic_report_heading')</header>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6">
                <section class="card">
                  <header class="card-header font-title">
                   @lang('report.filteration_heading')
                  </header>
                  <div class="card-body">
                    {{ Form::open(array('action'=> 'ReportController@store','method'=>'get')) }}
                    <div class="form-group">
                      {{Form::label('date', trans('report.date_label'))}}
                      <i class="fas fa-question-circle"
          						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
                      {{Form::text('date',(isset($postedData) && $postedData['date'])?$postedData['date']:'',['class'=>'form-control set-eta-date-picker'])}}
                    </div>
                    <div class="form-group">
                      {{Form::label('time_from', trans('report.time_from_label'))}}
                      <i class="fas fa-question-circle"
          						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
                      {{Form::text('time_from',(isset($postedData) && $postedData['time_from'])?$postedData['time_from']:'',['class'=>'form-control eta-time-picker'])}}
                    </div>
                    <div class="form-group">
                      {{Form::label('time_to', trans('report.time_to_label'))}}
                      <i class="fas fa-question-circle"
          						data-toggle="tooltip" data-placement="right" title="Add content here." data-container="body"></i>
                      {{Form::text('time_to','',['class'=>'form-control eta-time-picker'])}}
                    </div>
                    <div class="form-group form-check">
                        {{Form::submit(trans('report.filter_btn'),["class"=>"btn btn-lg btn-primary hey-blue"])}}
                        <a href="{{route('reports.index')}}" class="btn btn-lg btn-default"/>@lang('report.reset_btn')</a>
                    </div>
                  </div>
                </section>
              </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                  {{Form::label('per_page', trans('report.show_record_heading'))}}
                  {{Form::select('per_page', array(10 => 10,25=>25,50=>50,100=>100), (isset($perpageQueryString) && $perpageQueryString)?$perpageQueryString:10,['id'=>'filterShowPerPage'])}}
                </div>
            </div>
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-striped table-advance table-hover" id="dashboardTable">
                    <thead>
                        <tr>
                          <th>@lang('report.table_order_number_th')</th>
                          <th>@lang('report.table_customer_name_th')</th>
                          <th>@lang('dashboard.table_th_order_time')</th>
                          <th>@lang('report.table_eput_th')</th>
                          <th>@lang('dashboard.table_th_spot_number')</th>
                          <th>@lang('dashboard.table_th_paid_order')</th>
                          <th>@lang('dashboard.table_th_order_confirm')</th>
                          <th>@lang('report.table_order_delayed_th')</th>
                          <th>@lang('report.table_total_time_th')</th>
                          <th>@lang('report.table_payment_type')</th>
                          <th>@lang('report.table_delayed_time')</th>
                          <th class="d-none">@lang('report.table_order_amount_th')</th>
                        </tr>
                    </thead>
                    <tbody id="dashboardOrder">
                      @if($orders->count()>0)
                        @php
                        $totalAmount=0;
                        @endphp
                        @foreach($orders as $order)
                        @php
                          $totalAmount+=$order->amount;
                        @endphp
                           <tr class="{{($order->eta && ($order->status=='pending' || $order->status=='confirm') && !checkTimeGreater(convertToLocal($order->eta,1)))?'table-background-red':''}}">
                             <td>{{$order->order_number}}</td>
                             <td>{{$order->user->name}}</td>
                             <td>
                               @if($order->actual_order_time)
                                  {{convertToLocal($order->actual_order_time)}}
                               @endif
                             </td>
                             <td>{{convertToLocal($order->eta,1)}}</td>
                             <td>{{$order->spot_number}}</td>
                             <td>{{(checkPaidOrder($order->id))?'Yes':'No'}}</td>
                             <td>{{($order->confirm==1)?'Yes':'No'}}</td>
                             <td>{{($order->delayed==1)?'Yes':'No'}}</td>
                             <td>{{$order->total_time}}</td>
                             <td>{{paymentType($order->id)}}</td>
                             <td>{{getTwoTimesDifference($order->actual_order_time,$order->eta)}}
                             <td class="d-none">{{number_format($order->amount,2)}}</td>
                           </tr>
                        @endforeach
                        <tr class="d-none">
                          <td colspan="6" class="text-right mr-2"><b>@lang('report.table_total_amount_th'):</b>{{number_format($totalAmount,2)}}</td>
                        </tr>
                      @endif
                    </tbody>
                </table>
                @if(isset($postedData))
                  {{ $orders->appends(['date' => ($postedData['date'])??'','time_from' => ($postedData['time_from'])??'','time_to' => ($postedData['time_to'])??'','per_page'=>($perpageQueryString)??10])->links() }}
                @else
                  {{ $orders->appends(['per_page'=>($perpageQueryString)??10])->links() }}
                @endif
              </div>
            </div>
          </div>
          </div>
      </section>
  </div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
  $(document).ready(function(){
    var timeFrom='{!! (isset($postedData) && $postedData['time_from'])?$postedData['time_from']:'' !!}';
    $('#time_from').val(timeFrom);
    var timeTo='{!! (isset($postedData) && $postedData['time_to'])?$postedData['time_to']:'' !!}';
    $('#time_to').val(timeTo);
  });
</script>
@endsection
