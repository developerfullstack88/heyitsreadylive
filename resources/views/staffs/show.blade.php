@extends('layouts.default')
@section('content')
<style>
.menu-edit-img{max-width:55rem;}
</style>
<div class="row justify-content-center">
	<div class="col-lg-8 col-12 ipix-full-width-col">
		  <section class="card">
			  <div class="bio-graph-heading project-heading font-title">
				  <strong> <?= $user->name ?> </strong>
			  </div>
			  <div class="card-body bio-graph-info">
				  <!--<h1>New Dashboard BS3 </h1>-->
					<div class="row p-details">
						<div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('staff.staff_list_first_name_label'):</span> <?= ($user->first_name) ?></p>
						</div>
            <div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('staff.staff_list_last_name_label'):</span> <?= ($user->last_name) ?></p>
						</div>
            <div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('staff.staff_list_email_label'):</span> <?= ($user->email) ?></p>
						</div>
            <div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('staff.staff_list_phone_number_label'):</span> <?= ($user->phone_number) ?></p>
						</div>
            <div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('staff.staff_list_country_label'):</span> <?= ($user->country) ?></p>
						</div>
            <div class="bio-row bio-row-width100">
							<p><span class="bold">@lang('staff.staff_list_timezone_label'):</span> <?= ($user->timezone) ?></p>
						</div>
            @if($user->profile_photo)
              <div class="bio-row bio-row-width100">
  							<p><span class="bold">@lang('staff.staff_list_profile_image_label'):</span> <img class="menu-edit-img" src="{{asset($user->profile_photo)}}"/></p>
  						</div>
            @endif
					 </div>
				  </div>
				</div>
			</section>
</div>
@endsection()
