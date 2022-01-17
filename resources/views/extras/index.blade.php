@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <section class="card" id="CategoryListingPage">
	        <header class="card-header font-title">
            {{$categoryDetail->name}} @lang('restaurant_menu.extras_listing')
            <a class="btn btn-light pull-right" href="{{route('extras.add',[$categoryDetail->id])}}"><i class="fa fa-plus-circle"></i> @lang('restaurant_menu.table_th_add')</a>
          </header>
          <div class="card-body">
               <div class="table-responsive">
	                 <table class="table table-bordered">
		                   <thead>
              			       <tr>
              							  <th class="">@lang('restaurant_menu.table_th_name')</th>
                              <th class="">@lang('restaurant_menu.extras_listing_th_price')</th>
              							  <!--Quantity code version 2.5<th class="">Quantity</th>-->
                              <th class="">@lang('restaurant_menu.table_th_actions')</th>
              						 </tr>
		                   </thead>
                       <tbody>
                         @if($extras->count()>0)
                           @foreach($extras as $extra)
                              <tr>
                                <td>{{$extra->name}}</td>
                                <td>${{number_format($extra->price,2)}}</td>
                                <!--Quantity code version 2.5
                                <td>
                                  @php
                                    $usedQuantity=checkExtraQuantityForAdmin($extra->id);
                                    $leftQuantity=$extra->quantity-$usedQuantity;
                                    $leftQuantity=($leftQuantity>0)?$leftQuantity:0
                                  @endphp
                                  {{$leftQuantity}}
                                </td>-->
                                 <td>
                                   <a href="{{route('extras.edit',[$extra->id])}}"
                                     class="btn btn-primary btn-sm">
                                     <i class="fa fa-pencil"></i>
                                   </a>
                                   <a href="{{route('extras.delete',[$extra->id])}}"
                                     class="btn btn-danger btn-sm"
                                     onclick="return Confirm_Delete({{$extra->id}});">
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
          {{ $extras->links() }}
      </section>
  </div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
  function Confirm_Delete(id){
    if(confirm('Do you want to delete extra:'+id)){
      return true;
    }else{
      return false;
    }
  }
</script>
@endsection
