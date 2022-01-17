@extends('layouts.default')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <section class="card" id="CategoryListingPage">
	        <header class="card-header font-title">
            {{$menuDetail->name}} @lang('restaurant_menu.table_th_sub_menus')
            <a class="btn btn-light pull-right" href="{{route('categories.add',[$menuDetail->id])}}"><i class="fa fa-plus-circle"></i> @lang('restaurant_menu.table_th_add')</a>
          </header>
          <div class="card-body">
               <div class="table-responsive">
	                 <table class="table table-bordered">
		                   <thead>
              			       <tr>
              							  <th class="">@lang('site.table_list_name')</th>
                              <th class="">@lang('restaurant_menu.category_listing_th_image')</th>
              							  <th class="">@lang('common.table_list_actions')</th>
              						 </tr>
		                   </thead>
                       <tbody>
                         @if($categories->count()>0)
                           @foreach($categories as $category)
   		                       <tr>
         							          <td>{{$category->name}}</td>
         								        <td>
                                  @if($category->image_path)
                                    <img class="category-listing-img" src="{{asset($category->image_path)}}"/>
                                  @endif
                                </td>
                                 <td>
               									  <a href="{{route('categories.show',[$category->id])}}"
                                     class="btn btn-success btn-sm">
                                     <i class="fa fa-eye"></i>
                                   </a>
                                   <a href="{{route('categories.edit',[$category->id])}}"
                                     class="btn btn-primary btn-sm">
                                     <i class="fa fa-pencil"></i>
                                   </a>
                                   <a href="{{route('categories.delete',[$category->id])}}"
                                     class="btn btn-danger btn-sm"
                                     onclick="return Confirm_Delete({{$category->id}});">
                                     <i class="fa fa-trash-o"></i>
                                   </a>
                                   <a href="{{route('extras.listing',[$category->id])}}"
                                     class="btn btn-secondary btn-sm">
                                     @lang('restaurant_menu.category_listing_extras')
                                   </a>
                                   <a href="{{route('items.listing',[$category->id])}}"
                                     class="btn btn-info btn-sm">
                                     @lang('restaurant_menu.category_listing_dishes')
                                   </a>
                                 </td>
   		                       </tr>
                           @endforeach
                         @endif
		                   </tbody>
                   </table>
	             </div>
          </div>
        {{ $categories->links() }}
      </section>
  </div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
  function Confirm_Delete(id){
    if(confirm('Do you want to delete sub menu:'+id)){
      return true;
    }else{
      return false;
    }
  }
</script>
@endsection
