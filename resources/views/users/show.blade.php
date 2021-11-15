@extends('layouts.default')

@section('content')
  <div class="row" id="profileInfoPage">
      <!--<aside class="profile-nav col-lg-4">
          <section class="card">
              <div class="user-heading round card-header profile-preview-container d-none">
                @if(auth()->user()->profile_photo && auth()->user()->role==COMPANY)
                  <img src="{{asset('storage/'.$userInfo->profile_photo)}}" id="memberProfilePreview"/>
                @elseif(auth()->user()->profile_photo)
                  <img src="{{asset($userInfo->profile_photo)}}" id="memberProfilePreview"/>
                @else
                  <img src="{{asset('img/task_track_avatar_small.png')}}" id="memberProfilePreview"/>
                @endif

                <div class="row">
                  <div class="col-12 text-center mt-2 position-relative">
                    @if(auth()->user()->profile_photo)
                      {{Form::button(trans('profile.change_profile_btn_label'),array("class"=>"btn btn-primary btn-valet-submmit","onclick"=>"$('#memberImage').click()"))}}
                      <i class="fas fa-question-circle"
          						data-toggle="tooltip" data-placement="top" title="Just click on change profile image button. Your image will change automatically." data-container="body"></i>
                    @else
                      {{Form::button(trans('profile.upload_btn_label'),array("class"=>"btn btn-primary hey-blue","onclick"=>"$('#memberImage').click()"))}}
                      <i class="fas fa-question-circle"
          						data-toggle="tooltip" data-placement="top" title="Just click on Upload a photo button. Your image will set automatically." data-container="body"></i>
                    @endif
                    {{Form::file('member_image',array('style'=>'display:none','id'=>'memberImage'))}}
                  </div>
                </div>
                <h1 class="mt-2 font-title">{{$userInfo->name}}</h1>
              </div>

              <ul class="nav nav-pills nav-stacked">
                  <li class="nav-item font-title">

                  </li>
              </ul>

          </section>
      </aside>-->
      <aside class="profile-info col-lg-8 col-lg-offset-2 frmctr">
          <section class="card">
              <div class="bio-graph-heading card-header font-title">
                <div class="row">
                  <div class="col-lg-6">
                    @lang('profile.user_profile_label')
                  </div>
                  <div class="col-lg-6">
                    <a href="{{route('users.edit',$userInfo->id)}}" id="show-profile-edit-profile-link">
                      @lang('profile.edit_profile_label')
                    </a>
                  </div>
                </div>
               </div>
              <div class="card-body bio-graph-info">
                  <div class="row">
                      <div class="bio-row">
                          <p><span>@lang('profile.first_name_label')</span> : <span class="content-value">{{$userInfo->first_name}}</span></p>
                      </div>
                      <div class="bio-row">
                          <p><span>@lang('profile.last_name_label')</span> : <span class="content-value">{{$userInfo->last_name}}</span></p>
                      </div>
                      <div class="bio-row">
                          <p><span>@lang('profile.email_label')</span> : <span class="content-value">{{$userInfo->email}}</span></p>
                      </div>
                      <div class="bio-row">
                          <p><span>@lang('profile.phone_label')</span> : <span class="content-value">{{$userInfo->phone_number}}</span></p>
                      </div>
                      <div class="bio-row">
                          <p><span>@lang('profile.company_name_label') </span> : <span class="content-value">{{$userInfo->company->company_name}}</span></p>
                      </div>
                      <div class="bio-row">
                          <p><span>@lang('profile.company_website_label')</span> : <span class="content-value">{{$userInfo->company->company_website}}</span></p>
                      </div>
                      <div class="bio-row">
                          <p><span>@lang('profile.billing_address_label')</span> : <span class="content-value">{{$userInfo->company->address}}</span></p>
                      </div>
                      <div class="bio-row">
                          <p><span>@lang('profile.country_label')</span> : <span class="content-value">{{$userInfo->country}}</span></p>
                      </div>
                      <div class="bio-row">
                        <p><span>@lang('profile.timezone_label')</span> : <span class="content-value">{{$userInfo->timezone}}</span></p>
                      </div>
                      <div class="bio-row">
                        <p><span>@lang('profile.role_label')</span> : <span class="content-value">Admin</span></p>
                      </div>
                  </div>
              </div>
          </section>
      </aside>
  </div>
@endsection
@section('myScripts')
<script type="text/javascript">
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        $('#memberProfilePreview').attr('src', e.target.result);
        $('#ipixupProfileIcon').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  function uploadProfile(input){
    var fd = new FormData();
    var files = $('#memberImage')[0].files[0];
    fd.append('file',files);
    fd.append('_token','{!! csrf_token() !!}');
    $.ajax({
        url: '{{route('users.profile.image')}}',
        type: 'post',
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
          readURL(input);
        },
    });
  }

  $("#memberImage").change(function() {
    uploadProfile(this);
  });
  $('.hey-tooltip').tooltipster({theme: 'tooltipster-borderless',fixedWidth: 250});
</script>
@endsection
