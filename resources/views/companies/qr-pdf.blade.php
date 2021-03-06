<style>
	ol li{list-style:upper-roman;}
	ul li{list-style:none;}
	li{font-size:17px;}
	h4{font-size:20px;}
	#apps-qr-code {
	  display: flex;
	  justify-content: start;
	}
	.qrOne div{
	  right:60%;
	}
	.qrTwo div{
	  left:50%;
	}
	#item-qr-code-pdf div{left:-10%;}
	#apps-qr-container .col-lg-6{width:50%;float:left;}
	#apps-qr-container .col-lg-6:last-child{margin-top:10px;}
	#apps-qr-container .col-lg-6 div{left:-10%;}
	#apps-qr-container .col-lg-6:last-child div{top:5%;}

</style>
<div id="qrOrderPage">
	<div class="row">
		<div class="col-lg-6">
	    <section class="card qr-code-card">
				<!--<header class="card-header" style="font-size:40px;color:#000;text-align:center;">
						{{ucwords(getBusinessName())}}
				</header>-->
				<div class="col-lg-12">
					<center><img src="img/ipixup_background_image.png" style="max-width:100px;text-align:center;"/></center>
				</div>
				<p style="text-align:center;color:#1562A5;font-size:24px;">Scan the QR Code to get notified when your order is ready</p>
				<div id="item-qr-code-pdf"><?=$bobj->getHtmlDiv();?></div>
	    </section>
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<section class="card">
				<div class="row">
					<div class="col-lg-6">
						<p style="text-align:center;font-size:20px;"><b>@lang('qr.using_app_heading')</b></p>
						<ul class="list-group">
								<li class="list-group-item"><b>@lang('qr.step1_li_txt'): </b>@lang('qr.app_use_step1_li_description')</li>
								<li class="list-group-item"><b>@lang('qr.step2_li_txt'): </b>@lang('qr.app_use_step2_li_description')</li>
								<li class="list-group-item"><b>@lang('qr.step3_li_txt'): </b>@lang('qr.app_use_step3_li_description')
								</li>
						</ul>
					</div>
					<div class="col-lg-6">
						<p style="text-align:center;font-size:20px;"><b>@lang('qr.not_using_app_heading')</b></p>
						<ul class="list-group">
								<li class="list-group-item"><b>@lang('qr.step1_li_txt'): </b>@lang('qr.not_use_app_step1_li_description')</li>
								<li class="list-group-item"><b>@lang('qr.step2_li_txt'): </b>@lang('qr.not_use_app_step2_li_description')</li>
								<li class="list-group-item"><b>@lang('qr.step3_li_txt'): </b>@lang('qr.not_use_app_step3_li_description')
								</li>
						</ul>
					</div>
				</div>
				<div class="row" id="apps-qr-container">
					<p style="text-align:center;font-size:20px;">Scan the QR Code for your device to download the Hey It's Ready App</p>
					<div class="col-lg-6">
						<center><img src="img/google-play-icon.png" style="max-width:200px;text-align:center;"/></center>
						<div><?=$googleplay->getHtmlDiv();?></div>
					</div>
					<div class="col-lg-6">
						<center><img src="img/apple-store-icon.png" style="max-width:180px;text-align:center;"/></center>
						<div><?=$appleplay->getHtmlDiv();?></div>
					</div>
				</div>
				<div class="row" id="company-name-div" style="clear:both;">
					<p style="text-align:center;font-size:20px;"><b>{{getBusinessName()}}</b></p>
					<p style="text-align:center;font-size:20px;"><b>{{getBusinessInfo(auth()->user()->company_id)->address}}</b></p>
				</div>
	    </section>
	  </div>
	</div>
</div>
