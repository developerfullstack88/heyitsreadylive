@extends('layouts.default')
@section('content')
<div class="row justify-content-center" id="MenuViewPage">
	<div class="col-lg-8 col-12 ipix-full-width-col">
		  <section class="card">
			  <div class="bio-graph-heading project-heading font-title">
				  <strong> <?= $menu->name ?> </strong>
			  </div>
			  <div class="card-body bio-graph-info">
					<div class="row p-details">
						<div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('restaurant_menu.dishes_listing_th_category'):</span> <?= ($menu->category->name) ?></p>
						</div>
						<div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('dashboard.table_th_amount'):</span> <?= number_format($menu->amount,2) ?></p>
						</div>
					 </div>
					 <div class="row p-details">
 						<div class="bio-row bio-row-width100">
 							<p><span class="bold">@lang('restaurant_menu.dishes_listing_form_description'):</span> <?= ($menu->description) ?></p>
 						</div>
						<!--Quantity code version 2.5
						<div class="bio-row bio-row-width100">
 							<p><span class="bold">Quantity:</span>
								@php
								$totalCartQuantity=checkCartByItemForAdmin($menu->id);
								$leftQuantity=$menu->quantity-$totalCartQuantity;
								if($leftQuantity<0){
									$leftQuantity=0;
								}
								@endphp
								<?php //($leftQuantity)
								?></p>
 						</div>-->
 					 </div>
					<div class="row p-details menu-view-img">
						@if($menu->image_path)
							<img class="menu-view-img" src="{{asset($menu->image_path)}}"/>
						@endif
					 </div>
				  </div>
				</div>
			</section>
</div>
@endsection()
