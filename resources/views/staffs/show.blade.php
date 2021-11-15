@extends('layouts.default')
@section('content')
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
							<p><span class="bold">First Name:</span> <?= ($user->first_name) ?></p>
						</div>
            <div class="bio-row bio-row-width100">
							<p><span class="bold">Last Name:</span> <?= ($user->last_name) ?></p>
						</div>
            <div class="bio-row bio-row-width100">
							<p><span class="bold">Email:</span> <?= ($user->email) ?></p>
						</div>
            <div class="bio-row bio-row-width100">
							<p><span class="bold">Phone Number:</span> <?= ($user->phone_number) ?></p>
						</div>
            <div class="bio-row bio-row-width100">
							<p><span class="bold">Country:</span> <?= ($user->country) ?></p>
						</div>
            <div class="bio-row bio-row-width100">
							<p><span class="bold">Timezone:</span> <?= ($user->timezone) ?></p>
						</div>
            @if($user->profile_photo)
              <div class="bio-row bio-row-width100">
  							<p><span class="bold">Profile Image:</span> <img class="menu-edit-img" src="{{asset($user->profile_photo)}}"/></p>
  						</div>
            @endif
					 </div>
				  </div>
				</div>
			</section>
</div>
@endsection()
