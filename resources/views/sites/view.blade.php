@extends('layouts.default')
@section('content')
<div class="row justify-content-center">
	<div class="col-lg-8 col-12 ipix-full-width-col">
		  <section class="card">
			  <div class="bio-graph-heading project-heading font-title">
				  <strong> <?= $site->name ?> </strong>
			  </div>
			  <div class="card-body bio-graph-info">
				  <!--<h1>New Dashboard BS3 </h1>-->
					<div class="row p-details">
						<div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('site.table_list_name'):</span> <?= getBusinessName() ?></p>
						</div>
						<div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('site.address_label'):</span> <?= ($site->address) ?></p>
						</div>
						<div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('site.site_manager_label'):</span>
								@if($site->manager)
									<?= ($site->manager->name) ?>
								@endif
							</p>
						</div>
						<div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('site.site_cover_image'):</span>
								@if($site->cover_image_thumbnail)
									<img class="menu-edit-img" src="{{asset($site->cover_image_thumbnail)}}"/>
								@endif
							</p>
						</div>
					 </div>
				  </div>
				</div>
			</section>
</div>
@endsection()
