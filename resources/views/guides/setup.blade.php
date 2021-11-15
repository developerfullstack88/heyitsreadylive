@extends('layouts.default')

@section('content')
<div class="row">
  <div class="col-lg-12 col-12">
      <section class="card">
          <header class="card-header font-title">
              Setup Guides
          </header>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-3">
                <a target="_blank" href="{{asset('files/setup_guide.pdf')}}"><img src="{{asset('img/pdf_avatar.jpg')}}"/></a>
              </div>
              <div class="col-lg-3">
                <a target="_blank" href="{{asset('files/Set_up_guide_for_menu_QR_Code_only.pdf')}}"><img src="{{asset('img/pdf_avatar.jpg')}}"/></a>
              </div>


            </div>
            <div class="row">
              <div class="col-lg-3">
                <h6 class="ml-5">Setup guide for full app</h6>
              </div>
              <div class="col-lg-3">
                <h6>Setup guide for menu qr code only</h6>
              </div>
            </div>
          </div>
      </section>
  </div>
  <div class="col-lg-12 col-12">
    <section class="card">
      <header class="card-header font-title">
          Marketing
      </header>
      <div class="card-body">
        <h2>Hey It's Ready Poster</h2>
        <div id="heyItsPoster">
          <ul>
            <li>Please use the customer explanation poster help your customers to use the Hey It's Ready mobile app</li>
            <li>The poster is 12 inches x 18 inches</li>
            <li>You can support your local printer and forward this PDF to them for printing</li>
          </ul>
        </div>
        <div class="row">
          <div class="col-lg-3">
            <a target="_blank" href="{{asset('files/heyposter.pdf')}}"><img src="{{asset('img/pdf_avatar.jpg')}}"/></a>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
@endsection
@section('myScripts')

@endsection
