@extends('layouts.superadmin')

@section('content')
<div class="row createSitePage">
	<div class="col-lg-offset-2 col-lg-8 frmctr">
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
		  <header class="card-header font-title">Edit User</header>
			<div class="card-body">
        {{ Form::model($userInfo,array('route'=> array('business-users.update',$userInfo->id))) }}
         @method('PUT')
          {{Form::token()}}
          <div class="form-group">
            {{Form::label('company_name','Company Name',['class'=>'color-black'])}}
            {{Form::text('company_name',$userInfo->company->company_name,
              ['class'=>'form-control','placeholder'=>'Enter company name',
              'required'=>true]
            )}}
          </div>
          <div class="form-group">
            {{Form::label('company_website','Company Website',['class'=>'color-black'])}}
            {{Form::text('company_website',$userInfo->company->company_website,
              ['class'=>'form-control','placeholder'=>'Enter company website',
              'required'=>true]
            )}}
          </div>
          <div class="form-group">
            {{Form::label('name','Admin Name',['class'=>'color-black'])}}
            {{Form::text('name',$userInfo->name,
              ['class'=>'form-control','placeholder'=>'Enter name',
              'required'=>true]
            )}}
          </div>
          <div class="form-group">
            {{Form::label('phone_number','Admin Phone Number',['class'=>'color-black'])}}
            {{Form::text('phone_number',$userInfo->phone_number,
              ['class'=>'form-control','placeholder'=>'Enter phone number',
              'required'=>true]
            )}}
          </div>
          <div class="form-group">
            <div class="row">
              {{Form::label('status','Active?',['class'=>'color-black ml-4'])}}
              {{Form::checkbox('status',1,($userInfo->active==2 || $userInfo->active==0)?false:true,['class'=>'ml-2 mb-2 largerCheckbox','id'=>''])}}
            </div>
          </div>
          <div class="form-group">
            {{Form::label('deactivate_reason','Deactivation Reason',['class'=>'color-black'])}}
            {{Form::text('deactivate_reason',$userInfo->deactivate_reason,
              ['class'=>'form-control']
            )}}
          </div>
          <div class="form-group form-check text-center">
              {{Form::submit('Update User',["class"=>"btn btn-lg btn-primary btn-valet-submmit valet-submmit-save"])}}
          </div>
          {{ Form::close() }}
			</div>
		</section>
	</div>
</div>
@endsection
