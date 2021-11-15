@extends('layouts.login')

@section('content')
<div id="qrOrderPage">
  	<div class="row">
    		<div class="col-lg-6">
          <section class="card">
    				<header class="card-header">
              @if(getBusinessInfo(app('request')->input('company_id')))
                {{getBusinessInfo(app('request')->input('company_id'))->company_name}}
              @endif

    				</header>
            @if($companyItemInfo->count()>0)
              @foreach($companyItemInfo as $company)
                @if (pathinfo($company->image_path, PATHINFO_EXTENSION) == 'pdf')
                 <a style="text-align:center;" href="{{asset($company->image_path)}}" target="_blank" id="image_{{$company->id}}">
                   <img src="{{asset('img/pdf_avatar.jpg')}}" height="250" width="250" class="img-thumbnail mt-3"/>
                 </a>
                 @else
                 <a style="text-align:center;" href="{{asset($company->image_path)}}" target="_blank" id="image_{{$company->id}}">
                   <img style="max-width:65%;" src="{{asset($company->image_path)}}" class="img-thumbnail mt-3"/>
                 </a>
                 @endif
              @endforeach
            @else
              <center><p id="qr-images-no-found"><b>No images found for this qr code</b></p></center>
            @endif
    	    </section>
    	  </div>
  	</div>
</div>
