@extends('layouts.default')

@section('content')
<div id="qrOrderPage" class="qrItemCodePage">
  @if($companyInfoMenu==0)
  	<div class="row">
  		<div class="col-lg-6 col-12">
  	    <section class="card">
  				<header class="card-header">
  						@lang('qr.upload_item_qr_img_heading')
  				</header>
          <div class="card-body">
            {{ Form::open(array('action'=> 'CompanyController@UploadMenuImage','id'=>'UploadMenuImage','files'=>true)) }}
            @csrf
            <div class="row">
              <div class="col-md-6">
                <input type="file" name="image[]" multiple="true" id="qrfile" required>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary hey-blue">@lang('qr.upload_btn')</button>
              </div>
            </div>
            {{ Form::close() }}
          </div>
  	    </section>
  	  </div>
  	</div>
    <div class="row">
      <div class="col-lg-6 col-12">
        <section class="card">
          <header class="card-header">
            @lang('qr.upload_instructions_title')
          </header>
          <div class="card-body">
            <p><b>1. @lang('qr.upload_instructions_1')</b></p>
            <p><b>2. @lang('qr.upload_instructions_2')</b></p>
            <p><b>3. @lang('qr.upload_instructions_3')</b></p>
          </div>
        </section>
      </div>
    </div>
  @else
  <div class="row">
    <div class="col-lg-6 col-12">
      <section class="card qr-code-card">
        <header class="card-header text-center font-title">
            <h3>{{ucwords(getBusinessName())}}<a href="{{ URL::to('/company/item-qr-pdf') }}" class="btn btn-light pull-right mr-2">@lang('qr.pdf_btn')</a></h3>
        </header>
        <p class="text-center mt-2" style="font-size:14px;">@lang('qr.item_qr_code_scan_p1')</p>
        <?= $bobj->getHtmlDiv();?>
					<p class="text-center mt-2" style="font-size:14px;">@lang('qr.thank_you_p')</p>
					<p class="text-center" style="font-size:14px;">@lang('qr.download_p') www.heyitsready.com</p>
      </section>
    </div>
  </div>
  @endif
</div>

@endsection

@section('myScripts')
<script type="text/javascript">
$(document).ready(function(){
  var _URL = window.URL || window.webkitURL;
  $("#qrfile").change(function(e) {
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function() {
            if(this.width<1000){
              alert('image width must be 1000 or greater');
              $('#UploadMenuImage button').attr('disabled',true);
            }
            if(this.height<1000){
              alert('image height must be 1000 or greater');
              $('#UploadMenuImage button').attr('disabled',true);
            }

            if(this.width>=1000 && this.height>=1000){
              $('#UploadMenuImage button').attr('disabled',false);
            }
        };
        if ( /\.(jpe?g|png|gif)$/i.test(file.name) === false ) {
          $('#UploadMenuImage button').attr('disabled',true);
          alert('please upload only jpg and png files');
          location.reload();
        }else{
          $('#UploadMenuImage button').attr('disabled',false);
        }
        img.src = _URL.createObjectURL(file);
    }
  });
});

</script>
@endsection
