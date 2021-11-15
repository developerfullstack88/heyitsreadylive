@extends('layouts.default')
@section('content')
<div class="row justify-content-center" id="CategoryViewPage">
	<div class="col-lg-8 col-12 ipix-full-width-col">
		  <section class="card">
			  <div class="bio-graph-heading project-heading font-title">
				  <strong> <?= $category->name ?> </strong>
			  </div>
			  <div class="card-body bio-graph-info">
				  <!--<h1>New Dashboard BS3 </h1>-->
					<div class="row p-details category-view-img">
						@if($category->image_path)
							<img class="category-view-img" src="{{asset($category->image_path)}}"/>
						@endif
					 </div>
				  </div>
				</div>
			</section>
</div>
@endsection()
