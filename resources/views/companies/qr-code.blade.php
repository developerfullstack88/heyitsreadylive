@extends('layouts.default')

@section('content')
<style>
	ul li{list-style:upper-roman;}
	li{font-size:15px;}
</style>
<div id="qrOrderPage">
	<div class="row">
		<div class="col-lg-12">
	    <section class="card qr-code-card">
				<?=$bobj->getHtmlDiv();?>
	    </section>
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
	    <section class="card">
				<header class="card-header font-title">
						{{getBusinessName()}}
						<a href="{{ URL::to('/company/order-qr-pdf') }}" class="btn btn-light pull-right mr-2">@lang('qr.pdf_btn')
							<i class="fas fa-question-circle"
							data-toggle="tooltip" data-placement="top" title="This button will generate a PDF of your ORDER QR Code for your specific site. When you print this off your walk in customers are able to scan the QR Code with the Hey It’s Ready mobile app and start the order for you that will appear on the admin dashboard then all you need to do is enter the “E.P.U.T.” (Estimated Pick Up Time) and add the amount of the order (This is optional)." data-container="body"></i>
						</a>
				</header>
				<div class="row">
					<div class="col-lg-6">
						<h4 class="text-center"><b>@lang('qr.using_app_heading')</b></h4>
						<ul class="list-group">
								<li class="list-group-item"><b>@lang('qr.step1_li_txt'): </b>@lang('qr.app_use_step1_li_description')</li>
								<li class="list-group-item"><b>@lang('qr.step2_li_txt'): </b>@lang('qr.app_use_step2_li_description')</li>
								<li class="list-group-item"><b>@lang('qr.step3_li_txt'): </b>@lang('qr.app_use_step3_li_description'):
									<ol>
										<li>@lang('qr.sub_step1_description')</li>
										<li>@lang('qr.sub_step2_description')</li>
										<li>@lang('qr.sub_step3_description')</li>
										<li>@lang('qr.sub_step4_description')</li>
										<li>@lang('qr.sub_step5_description')</li>
									</ol>
								</li>
						</ul>
					</div>
					<div class="col-lg-6">
						<h4 class="text-center"><b>@lang('qr.not_using_app_heading')</b></h4>
						<ul class="list-group">
								<li class="list-group-item"><b>@lang('qr.step1_li_txt'): </b>@lang('qr.not_use_app_step1_li_description')</li>
								<li class="list-group-item"><b>@lang('qr.step2_li_txt'): </b>@lang('qr.not_use_app_step2_li_description')</li>
								<li class="list-group-item"><b>@lang('qr.step3_li_txt'): </b>@lang('qr.not_use_app_step3_li_description'):
									<ol>
										<li>@lang('qr.sub_step1_description')</li>
										<li>@lang('qr.sub_step2_description')</li>
										<li>@lang('qr.sub_step3_description')</li>
										<li>@lang('qr.sub_step4_description')</li>
										<li>@lang('qr.sub_step5_description')</li>
									</ol>
								</li>
						</ul>
					</div>
				</div>
					<p class="text-center mt-2">@lang('qr.thank_you_p')</p>
					<p class="text-center">@lang('qr.download_p') www.heyitsready.com</p>
	    </section>
	  </div>
	</div>
</div>

@endsection

@section('myScripts')
<script type="text/javascript">

</script>
@endsection
