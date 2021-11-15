@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <section class="card" id="CategoryListingPage">
	        <header class="card-header font-title">
            @lang('tax.listing_heading')
            <a class="btn btn-light pull-right" href="{{route('taxes.create')}}"><i class="fa fa-plus-circle"></i> @lang('tax.table_th_add_btn')</a>
          </header>
          <div class="card-body">
               <div class="table-responsive">
	                 <table class="table table-bordered">
		                   <thead>
              			       <tr>
              							  <th class="">@lang('tax.table_th_name')</th>
                              <th class="">@lang('tax.table_th_percentage')</th>
              							  <th class="">@lang('tax.table_th_is_default')</th>
                              <th class="">@lang('tax.table_th_actions')</th>
              						 </tr>
		                   </thead>
                       <tbody>
                         @if($taxes->count()>0)
                           @foreach($taxes as $tax)
                              <tr>
                                <td>{{$tax->name}}</td>
                                <td>{{$tax->tax_value}}</td>
                                <td>
                                  @if($tax->is_default===1)
                                    <img src="{{asset('img/star.jpg')}}" height="25" width="25"/>
                                  @else
                                    <img src="{{asset('img/white-star.png')}}" height="25" width="25"/>
                                  @endif
                                </td>
                                 <td>
                                   <a href="{{route('taxes.edit',[$tax->id])}}"
                                     class="btn btn-primary btn-sm">
                                     <i class="fa fa-pencil"></i>
                                   </a>
                                   <a href="{{route('taxes.delete',[$tax->id])}}"
                                     class="btn btn-danger btn-sm"
                                     onclick="Confirm_Delete({{$tax->id}});">
                                     <i class="fa fa-trash-o"></i>
                                   </a>
                                 </td>
                              </tr>
                           @endforeach
                         @endif
		                   </tbody>
                   </table>
	             </div>
          </div>
          {{ $taxes->links() }}
      </section>
  </div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
  function Confirm_Delete(id){
    if(confirm('Do you want to delete tax:'+id)){
      return true;
    }else{
      return false;
    }
  }
</script>
@endsection
