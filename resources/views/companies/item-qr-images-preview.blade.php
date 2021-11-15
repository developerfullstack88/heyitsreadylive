@extends('layouts.default')

@section('content')
<div id="qrOrderPage" class="qrItemCodePagePreview">
  <div class="row">
    <div class="col-lg-8 col-12">
      <section class="card qr-code-card">
        <header class="card-header font-title">
          <div class="row">
            <div class="col-lg-4 col-6 text-center company-name">
              {{ucwords(getBusinessName())}}
            </div>
            <div class="col-lg-4 col-6 col-lg-btns d-flex">
              <a href="javascript:void(0);" class="btn btn-light pull-right" id="reorderImages">@lang('qr.reorder_img_btn')</a>
              <a href="{{route('itemQr',[1])}}" class="btn btn-light pull-right mr-2">@lang('qr.qr_code_btn')</a>
            </div>
          </div>
        </header>
        <div class="card-body d-flex">
          @foreach($companyItemInfo as $company)
          <div class="preview-img-relative">
            <i class="fa fa-times-circle" data-id="{{$company->id}}"></i>
            <a href="{{asset($company->image_path)}}" target="_blank">
              @if (pathinfo($company->image_path, PATHINFO_EXTENSION) == 'pdf')
                <img src="{{asset('img/pdf_avatar.jpg')}}" height="200" width="200" class="img-thumbnail mx-auto"/>
              @else
                <img src="{{asset($company->image_path)}}" height="200" width="200" class="img-thumbnail mx-auto"/>
              @endif
           </a>
          </div>
          @endforeach
        </div>
      </section>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-8 col-12">
      <section class="card">
        <header class="card-header font-title">
            @lang('qr.add_more_img_heading')
        </header>
        <div class="card-body">
          {{ Form::open(array('action'=> 'CompanyController@UploadMenuImage','id'=>'UploadMenuImage','files'=>true)) }}
          @csrf
          <div class="row">
            <div class="col-md-6">
              <input type="file" name="image[]" id="qrfile" multiple="true" required>
            </div>
            <div class="col-md-6">
              <button type="submit" class="btn btn-primary hey-blue mt-btn-10">@lang('qr.upload_btn')</button>
            </div>
          </div>
          {{ Form::close() }}
        </div>
      </section>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-8 col-12">
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
</div>
<div class="modal fade " id="reorderModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-title" id="setEtaModal">@lang('qr.reorder_img_btn')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-12 col-12 gallery">
                  <p class="text-center"><b>1. @lang('qr.reorder_photo_p1')</b></p>
                  <p class="text-center"><b>2. @lang('qr.reorder_photo_p2')</b></p>
                  <ul id="sortable-images">
                  @foreach($companyItemInfo as $company)
                   <li class="float-left" id="image_{{$company->id}}"><a href="{{asset($company->image_path)}}" target="_blank" id="image_{{$company->id}}">
                     @if (pathinfo($company->image_path, PATHINFO_EXTENSION) == 'pdf')
                      <img src="{{asset('img/pdf_avatar.jpg')}}" height="200" width="200" class="img-thumbnail mx-auto"/>
                      @else
                        <img src="{{asset($company->image_path)}}" height="200" width="200" class="img-thumbnail mx-auto"/>
                      @endif
                  </a></li>
                  @endforeach
                </ul>
              </div>
            </div>
            </div>
            <div class="modal-footer">
              {{Form::button(trans('common.close_btn_txt'),["class"=>"btn btn-secondary","data-dismiss"=>"modal"])}}
              {{Form::button(trans('common.save_btn_txt'),["class"=>"btn btn-primary hey-blue","id"=>"reorderButton"])}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('myScripts')
<script type="text/javascript">
  $(document).ready(function(){
    $('#reorderImages').click(function(){
      $('#reorderModal').modal('show');
      $('#sortable-images').sortable({ tolerance: 'pointer' });
    });
    $('#reorderButton').click(function(){
      var orderArr = [];
      $('#sortable-images li').each(function(){
        orderArr.push($(this).attr('id').substr(6));
      });
      $.ajax({
        type:'post',
        url:APP_URL+"/orders/item-qr-images-reorder",
        data:{ids:orderArr},
        success:function(response){
          if(response=='success'){
            location.reload();
          }
        }
      });
    });

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

    $('.fa-times-circle').click(function(){
      var id=$(this).data('id');
      var th=this;
      if(confirm('Do you want to delete?')){
        if(id){
          $.ajax({
            url:APP_URL+"/company/delete-item-qr-images/"+id,
            success:function(response){
              if(response=='success'){
                $(th).parent().remove();
                location.reload();
              }
            }
          });
        }
      }
    });
  });
</script>
@endsection
