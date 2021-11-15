@extends('layouts.default')
@section('content')
<div class="row createCategoryPage">
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
		  <header class="card-header font-title">@lang('tax.form_percentage_add_tax')</header>
			<div class="card-body" id="siteAddCardBody">
        {{ Form::open(array('action'=> 'TaxController@store')) }}
            {{Form::token()}}
            <div class="form-group">
              {{Form::label('name',trans('tax.form_name'))}}
              {{Form::text('name','',
                ['class'=>'form-control','placeholder'=>trans('tax.form_name_placeholder'),'required'=>true]
              )}}
            </div>
            <div class="form-group">
              {{Form::label('tax_value',trans('tax.form_percentage'))}}
              {{Form::text('tax_value','',
                ['class'=>'form-control','placeholder'=>trans('tax.form_percentage_placeholder'),'required'=>true]
              )}}
            </div>
            <div class="form-group">
                {{Form::checkbox('is_default', 1,false,['id'=>'is_free_chk'])}}
              {{Form::label('is_default', trans('tax.form_percentage_default_chk_box'))}}
            </div>
            <div class="form-group form-check text-center">
                {{Form::submit(trans('tax.form_percentage_save_tax_btn'),["class"=>"btn btn-lg btn-primary btn-valet-submmit valet-submmit-save"])}}
            </div>
          {{ Form::close() }}
			</div>
		</section>
	</div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
  $(document).ready(function(){

  });
</script>
@endsection
