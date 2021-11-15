@extends('layouts.login')

@section('content')
<div class="row mt-3" id="createOrderPage">
	<div class="col-lg-offset-2 col-xs-12 col-sm-12 col-md-12 col-lg-8 frmctr">
    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif
	  <section class="card">
		  <header class="card-header text-center"><?= __("Hey It's Ready Order Information") ?></header>
			<div class="card-body">
        {{ Form::open(array('action'=> 'UsersController@store','id'=>'orderForm')) }}
          {{Form::token()}}
					<div class="form-group">
            {{Form::label('order_number', 'Business Name')}}
            {{Form::text('business_name',getBusinessNameById($companyId),
              ['class'=>'form-control','disabled'=>true]
            )}}
          </div>
          <div class="form-group">
            {{Form::label('order_number', 'Order Number')}}
            {{Form::text('order_number',$orderId,
              ['class'=>'form-control','placeholder'=>'Enter Order','required'=>true,'readonly'=>true]
            )}}
          </div>
					<div class="form-group">
						{{Form::label('phone_code', trans('dashboard.table_th_cell_number'))}}
						<div class="row no-gutters">
	            <div class="col-md-2 col-2">
		            {{Form::text('phone_code',$phoneCode,
		              ['class'=>'form-control','placeholder'=>'Phone code','required'=>true,'readonly'=>true]
		            )}}
	            </div>
							<div class="col-md-10 col-10">
		            {{Form::text('phone_number','',
		              ['class'=>'form-control ml-1','id'=>'phone_number','placeholder'=>trans('dashboard.table_th_cell_number'),'required'=>true]
		            )}}
	            </div>
						</div>
          </div>
          <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name','',
              ['class'=>'form-control','placeholder'=>'Enter Name','required'=>true]
            )}}
          </div>
          <div class="form-group d-none">
            {{Form::label('email', 'Email')}}
            {{Form::email('email','',
              ['class'=>'form-control','placeholder'=>'Enter Email']
            )}}
          </div>
					{{ Form::hidden('user_id','',['id'=>'userId']) }}
					{{ Form::hidden('company_id',$companyId,['id'=>'companyId']) }}
			</div>
      <div class="form-group form-check text-center">
          {{Form::submit('Enter',["class"=>"btn btn-lg btn-primary btn-valet-submmit valet-submmit-save"])}}
      </div>
      {{ Form::close() }}
		</section>
	</div>
</div>
@endsection

@section('myScripts')
<script type="text/javascript">
$('#phone_number').mask('(000) 000-0000');
var cid={!! Request::get('company_id') !!}
	/*get user info from temp table
	setInterval(function(){
		if(!$('#email').val() && !$('#name').val()){
		  $.ajax({
		    url:APP_URL+'/ajax-user-order/'+cid,
		    datatype:'json',
		    success:function(response){
					if(response){
						var json=$.parseJSON(response);
							$('#userId').val(json.user_id);
							$('#email').val(json.email);
							$('#name').val(json.name);
							$('#phone_number').val(json.phone_number);
					}
		    }
		  });
		}
	},1000);
	/*get user info from temp table*/
</script>
@endsection
