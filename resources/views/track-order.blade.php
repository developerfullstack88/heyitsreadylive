@extends('layouts.track')

@section('content')
<style>
.bio-row p span{display:inline;}
#eput-timer-parent{margin-left: 20px;opacity: 0.8;}
.extra-bold{font-weight:900;}
label,.extra-bold-black{color: #000000;font-weight: 800;}
#eput-p-timer{font-size:22px;}
#eput-status{font-size:20px;}
.bio-row{margin-bottom:0;}
#eput-p-timer-main-parent p,#eput-p-timer-main-parent.bio-row{margin-bottom:0;}
.extra-bold-green{color:#30FA2C;font-weight: 800;font-size:14px;}
</style>
<div class="row">
	 <div class="col-md-12 text-center">
	 		<img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:150px;">
	 </div>
</div>
<div class="row mt-3" id="createOrderPage">
	<div class="col-lg-offset-2 col-lg-8 col-12 frmctr">
	  <section class="card">
		  <header class="card-header">Track my order</header>
			<div class="card-body">
        {{ Form::open(array('action'=> 'HomeController@trackOrder','id'=>'trackOrderForm')) }}
          {{Form::token()}}
          <div class="form-group">
						{{Form::label('phone_number', 'Please enter your phone number')}}
						<div class="row">
							<div class="col-md-2 col-3">
								@php $phonCode=getPhoneCode();@endphp
								@if($phoneCode)
									{{Form::text('phone_code',$phoneCode,
											['class'=>'form-control','placeholder'=>'Phone code','required'=>true,'readonly'=>true]
										)}}
										{{ Form::hidden('phone_code',$phoneCode) }}
								@else
									{{ Form::hidden('phone_code',$phoneCode) }}
								@endif
							</div>

							<div class="col-md-10 col-9">
								{{Form::text('phone_number',($originalPhone)?$originalPhone:'',
		              ['class'=>'form-control','placeholder'=>'Enter Phone','required'=>true]
		            )}}
							</div>
						</div>
          </div>
			</div>
      <div class='form-row row' id="errorDiv" @if(!$orderInfo || $notFound==0) style="display:none;" @endif>
        <div class='col-md-12 error form-group hide'>
          <div class='alert-danger alert'>No record found in database</div>
        </div>
      </div>
      <div class="form-group form-check text-center">
          {{Form::button($orderInfo?'Update':'Track my Order',["class"=>"btn btn-lg btn-primary track-order-detail"])}}
      </div>
      {{ Form::close() }}
		</section>
		<section class="card">
			<div class="card-body">
				<div class="col-md-12 text-center">
					<img id="track-order-taxi-img" src="{{asset('img/hey-its-ready-track-order.png')}}"/>
				</div>
			</div>
		</section>
    @if($orderInfo)
    <section class="card">
      <div class="card-body bio-graph-info">
        <div class="row p-details">
          <div class="col-md-12">
						@if($orderInfo->status=='Ready')
							@if($orderInfo->friendly_reminder===1)
								<div id="friendly-reminder-div" class="bio-row p-0" style="width:100%">
		              <p class="text-center extra-bold-green">Friendly Reminder Your order is ready</p>
		            </div>
							@else
							<div id="friendly-reminder-div" class="bio-row p-0" style="width:100%;display:none;">
								<p class="text-center extra-bold-green">Friendly Reminder Your order is ready</p>
							</div>
							@endif
						@endif
						<div class="bio-row p-0" style="width:100%">
              <p>
                <span class="bold"><b class="extra-bold-black">EPUT</b> (<b class="extra-bold-black">E</b>stimated <b class="extra-bold-black">P</b>ick <b class="extra-bold-black">U</b>p <b class="extra-bold-black">T</b>ime)</span>
              </p>
							<p>Date:<span id="eput-p-only-date" class="extra-bold-black">{{$orderInfo->only_date}}</span></b><span class="ml-2">Time:</span><span id="eput-p" data-date="{{$orderInfo->eta_24}}" class="extra-bold-black">{{$orderInfo->only_time}}</span></b></p>
            </div>
						<div class="bio-row p-0"  style="width:100%">
							@if($orderInfo->status!='Completed' && $orderInfo->status!='Ready')
								@if($orderInfo->status=='Pending')
		              <p id="eput-p-timer-main-parent">
		                	<span class="extra-bold-black" id="your-order-ready"></span> <span id="eput-p-timer" style="font-weight:bold;color:red;">{{getTimezoneDiff($orderInfo->eta_original,$userInfo->timezone)}}</span>
		              </p>
								@else
								<p id="eput-p-timer-main-parent">
										<span class="extra-bold-black">Your order will be ready in</span> <span id="eput-p-timer" style="font-weight:bold;color:red;">{{getTimezoneDiff($orderInfo->eta_original,$userInfo->timezone)}}</span>
								</p>
								@endif
							@endif
            </div>
						<div class="bio-row p-0" style="width:100%">
              <p>
                <span class="bold">
                  Order Status
                </span>:
								@if($orderInfo->status=='Ready' || $orderInfo->status=='Completed' || $orderInfo->status=='Confirmed' || $orderInfo->status=='EPUT Updated')
									<span id="eput-status" style="font-weight:bold;color:red;">{{$orderInfo->status}}</span>
								@else
									<b><span id="eput-status">{{$orderInfo->status}}</span></b>
								@endif
              </p>
            </div>
            <div class="bio-row p-0" style="width:100%">
              <p class=""><span class="bold">Order Number </span>:
                <b class="extra-bold-black">{{$orderInfo->order_number}}</b>
              </p>
							<span id="track-order-id" class="d-none">{{$orderInfo->id}}</span>
            </div>
						<div class="bio-row p-0" style="width:100%">
              <p class="">
                <span class="bold">
                  Company Name
                </span>:
                <b class="extra-bold-black">{{$orderInfo->company->company_name}}</b>
              </p>
            </div>
            <div class="bio-row p-0" style="width:100%">
              <p class="">
                <span class="bold">
                  Customer Name
                </span>:
                <b class="extra-bold-black">{{$orderInfo->user->name}}</b>
              </p>
            </div>
            <div class="bio-row p-0" style="width:100%">
              <p class="">
                <span class="bold">
                  Cell Number
                </span>:
                <b class="extra-bold-black">
									{{getPhoneFormat($orderInfo->user->phone_number)}}
								</b>
              </p>
            </div>

            <div class="bio-row d-none" style="width:100%">
              <p>
                <span class="bold">
                  Amount
                </span>:
                {{number_format($orderInfo->amount,2)}}
              </p>
            </div>

						<div class="bio-row" style="width:100%">
              <p class="text-center extra-bold-black hey-download-website-p">Please download the FREE Hey Its Ready App at <a href="https://www.heyitsready.com" target="_blank"><b>www.heyitsready.com</b></a></p>
							<p class="text-center font-weight-bold" style="color:red;">Get Notified When your order is ready</p>
            </div>
          </div>
          </div>
        </div>
      </section>
      @endif
	</div>
</div>
@endsection

@section('myScripts')
<script type="text/javascript">
var cid='{!! Request::get('company_id') !!}';
var oid='{!! Request::get('id') !!}';
$(document).ready(function(){
	if(oid){
		$('#trackOrderForm').submit();
	}
	$('#phone_number').mask('(000) 000-0000');
  $(document).on('click','.track-order-detail',function(){
    var phone_number=$('#phone_number').val();
    $('#errorDiv .alert-danger').empty();
    if(!phone_number){
      $('#errorDiv .alert-danger').html('Phone number is required');
      $('#errorDiv').show();
    }else{
      $('#trackOrderForm').submit();
    }
  });

	/*get detail*/
	var ETA='';
	@if($orderInfo)
		ETA='{{$orderInfo->eta}}';
	@endif
	setInterval(function(){
		var oid=$('#track-order-id').text();
		if(!oid){
			return false;
		}
		$.ajax({
	    url:APP_URL+'/ajax-track-detail/'+oid,
	    datatype:'text/html',
	    success:function(response){
	      if(response){
					var json=$.parseJSON(response);
					if(json.eta){
						$('#eput-p').text(json.only_time);
						$('#eput-p-only-date').text(json.only_date);
						ETA=json.eta;
						$('#eput-p').attr('data-date',json.eta_24);
					}
					$('#eput-status').text(json.status);
					if(json.status!='Pending'){
						$('#your-order-ready').text('Your order will be ready in');
					}
					if(json.status=='Ready' || json.status=='Completed' || json.status=='Confirmed'){
						$('#eput-status').css({
							'font-weight':'bold',
							'color':'red'
						});
					}
					if(json.status=='Ready'){
						if(json.friendly_reminder===0){
							$('#friendly-reminder-div').hide();
						}else{
							$('#friendly-reminder-div').show();
						}
					}else{
						$('#friendly-reminder-div').hide();
					}


	      }else{
					$('#errorDiv .alert-danger').html('No record found in database.');
		      $('#errorDiv').show();
				}
	    }
	  });
	},5000);

	/*set time interval for timer*/
	var TIMEZONEUSER='';
	@if($orderInfo)
		var TIMEZONEUSER='{{$userInfo->timezone}}';
		var STATUS='{{$orderInfo->status}}';
		if(STATUS!='complete'){
			setInterval(function(){
		    if(TIMEZONEUSER){
		      var dt = new Date().toLocaleString('en-US', {timeZone: TIMEZONEUSER});
		      dt=new Date(dt);
		    }else{
		      dt=new Date(dt);
		    }
		    var timenew = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
		    var mnth=parseInt(dt.getMonth())+1;
		    var current_time = dt.getDate()+'/'+mnth+'/'+dt.getFullYear()+" "+timenew;
		    var dbtime = $('#eput-p').attr('data-date');
				var status = $('#eput-status').text();
		    var ms= moment(dbtime, "DD/MM/YYYY HH:mm:ss").diff(moment(current_time, 'DD/MM/YYYY HH:mm:ss'));
		    var diff = moment.duration(ms);
		    var hours= diff.hours();
		    var mints= diff.minutes();
		    var sec= diff.seconds();
		    var days=diff.days();
		      if(days>0){
		        var total = mints + "m " + sec+"s";
		      }else if(sec<0 || mints<0){
		        //var total = '';
		      }else{
		        var total = mints + "m " + sec+"s";
		      }
					if(ETA){
						if(mints==0 && sec==0 && hours==0){
							$('#eput-p-timer').text('');
							$('#eput-p-timer-main-parent').hide();
			      }else{
							if(status=='Completed' || status=='Ready'){
								$('#eput-p-timer').text('');
								$('#eput-p-timer-main-parent').hide();
							}else{
								$('#eput-p-timer').text(total);
								$('#eput-p-timer-main-parent').show();
							}
						}
					}



		  },1000);
		}
	@endif
	/*set time interval for timer*/
});
</script>
@endsection
