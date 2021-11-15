@extends('layouts.help')

@section('content')
<div class="row">
  <section class="wrapper wrapper-logo">
    <div class="col-lg-12 col-12 text-center mt-2">
      <h1 class="text-center">Hey It's Ready Ios Help</h1>
       <img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:12rem;">
    </div>
  </section>
  <section class="wrapper main-menu-wrapper">
    <div class="col-lg-12 col-12">
      <ul id="main-menu">
        <li><a href="#android-update-profile">How to update Profile information?</a></li>
        <li><a href="#android-walkin-orders">How does Hey It’s Ready App work for Walk in Orders?</a></li>
        <li><a href="#android-scan-walkin-orders">How to Scan a QR Code – Walk in Order?</a></li>
        <li><a href="#android-view-past-orders">How do I view my past orders?</a></li>
        <li><a href="#android-delete-past-orders">How do I delete my past orders?</a></li>
        <li><a href="#android-view-cancelled-orders">How do I view my cancelled orders?</a></li>
        <li><a href="#android-get-directions-orders">How do I get directions to a business that uses “Hey It’s Ready”?</a></li>
        <li><a href="#android-add-profile-photo">How do I Add a Profile photo?</a></li>
        <li><a href="#android-map-markers-for">What are the map markers for?</a></li>
        <li><a href="#android-search-for-business">How do I search for businesses that use “Hey It’s Ready”?</a></li>
        <li><a href="#android-call-in-orders">How does Hey It’s Ready App work for Call in Orders?</a></li>
      </ul>
    </div>
    <div id="android-update-profile">
      <div class="col-lg-12 col-12 text-center mt-5">
        <h2>How to update Profile information</h2>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <ol id="sub-ul">
          <li class="li-heading">From the Home Screen select the menu in the upper left hand corner.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/add_a_profile_pic_android.png')}}"/></p>
          <li class="li-heading">Choose “Profile” from the menu.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/update_profile_1.png')}}"/></p>
          <li class="li-heading">Select “Update” to save the changes.</li>
          <p class="img-li"><img src="{{asset('img/Help/Android/update_profile_2.png')}}"/></p>
        </ol>
      </div>
    </div>
    <div id="android-walkin-orders">
      <div class="col-lg-12 col-12 text-center mt-5">
        <h2>How does Hey It’s Ready App work for Walk in Orders?</h2>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <ol id="sub-ul">
          <li class="li-heading">Walk into the business and place your order.</li>
          <li class="li-heading">After your order has been placed, scan the <b>“Order QR Code”</b> and a window will pop up in the Hey It’s Ready app asking you to confirm your order. You must confirm your order to receive the notifications. This will automatically update the Business owner’s dashboard with your Name and Phone Number associated with app.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/confirm_order.png')}}"/></p>
          <li class="li-heading">Once you have confirmed your order the Business will enter an <b>“EPUT”</b> (<b>E</b>stimated <b>P</b>ick <b>U</b>p <b>T</b>ime) and you will receive a notification that the order is confirmed with the Date and EPUT for when the order will be ready for pickup and the timer will start counting down showing the time remaining until the order is ready. </li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/order_confirmed.png')}}"/></p>
          <li class="li-heading">When your order is ready for pick up you will get a notification from the business to pick up your order.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/order_ready.png')}}"/></p>
          <li class="li-heading">Once you have picked up your order you will receive a final notification that your order has been completed.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/order_completed.png')}}"/></p>
        </ol>
      </div>
    </div>
    <div id="android-scan-walkin-orders">
      <div class="col-lg-12 col-12 text-center mt-5">
        <h2>How to Scan a QR Code – Walk in Order</h2>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <ol id="sub-ul">
          <li class="li-heading">From the Home Screen select the QR Code icon. This will open the camera on your phone. Scan the “Order QR Code” at the business.
            <p><b>NOTE:</b> you must give permission to use the camera on your phone.</p>
          </li>
          <li class="li-heading">Point the camera at the QR Code and hold it steady and app will open the window for to confirm your order.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/scan_qr_code_1.png')}}"/></p>
        </ol>
      </div>
    </div>
    <div id="android-view-past-orders">
      <div class="col-lg-12 col-12 text-center mt-5">
        <h2>How do I view my past orders?</h2>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <ol id="sub-ul">
          <li class="li-heading">Select the menu in the upper left hand corner of your home screen.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/view_select_home.png')}}"/></p>
          <li class="li-heading">Select “My Orders” from the menu list</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/select_view_order2.png')}}"/></p>
          <li class="li-heading">Select “Past Orders”.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/select_view_order3.png')}}"/></p>
        </ol>
      </div>
    </div>
    <div id="android-delete-past-orders">
      <div class="col-lg-12 col-12 text-center mt-5">
        <h2>How do I delete my past orders?</h2>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <ol id="sub-ul">
          <li class="li-heading">Select “My Orders” from the menu.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/Select_my_orders.png')}}"/></p>
          <li class="li-heading">Select “Past Orders” in Order History</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/select_past_orders.png')}}"/></p>
          <li class="li-heading">Swipe right to left on any order to display the delete button and select “Delete”.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/Slide_to_delete.png')}}"/></p>
        </ol>
      </div>
    </div>
    <div id="android-view-cancelled-orders">
      <div class="col-lg-12 col-12 text-center mt-5">
        <h2>How do I view my cancelled orders?</h2>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <ol id="sub-ul">
          <li class="li-heading">Select the menu in the upper left hand corner of your home screen.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/home_cancelled_orders.png')}}"/></p>
          <li class="li-heading">Select “My Orders” from the menu list.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/Select_my_orders.png')}}"/></p>
          <li class="li-heading">Select “Cancelled Orders”.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/select_cancelled_orders.png')}}"/></p>
        </ol>
      </div>
    </div>
    <div id="android-get-directions-orders">
      <div class="col-lg-12 col-12 text-center mt-5">
        <h2>How do I get directions to a business that uses “Hey It’s Ready”?</h2>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <h4 class="mt-4">There are 2 ways to get directions to a business using the Map Markers and directions from your order</h4>
        <h4 class="mt-4">Directions Option 1: Using the Map Markers</h4>
        <ol id="sub-ul">
          <li class="li-heading">To get directions from your current location to a business simply touch on any map marker to display the window with the Business Name, Website URL and Phone Number and then select that window to open the maps feature. </li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/directions_from_icon.png')}}"/></p>
        </ol>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <h4 class="mt-4">Directions Option 2: Getting directions from your order</h4>
        <ol id="sub-ul">
          <li class="li-heading">From the “My Orders” order window select Get Directions and then choose from my current location!</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/directions_from_order.png')}}"/></p>
        </ol>
      </div>
    </div>
    <div id="android-add-profile-photo">
      <div class="col-lg-12 col-12 text-center mt-5">
        <h2>Add a Profile photo</h2>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <ol id="sub-ul">
          <li class="li-heading">Select the menu in the upper left hand corner of your home screen.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/view_select_home.png')}}"/></p>
          <li class="li-heading">Select “Profile” from the menu list.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/select_view_order2.png')}}"/></p>
          <li class="li-heading">Select the Photo icon.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/Photo_icon.png')}}"/></p>
          <li class="li-heading">Select “Camera” to take a photo or “Gallery” to use a photo from your photo library and upload the photo.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/Select_camera_or_gallery.png')}}"/></p>
          <li class="li-heading">Select “Update” to save the changes.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/select_update.png')}}"/></p>
        </ol>
      </div>
    </div>
    <div id="android-map-markers-for">
      <div class="col-lg-12 col-12 text-center mt-5">
        <h2>What are the map markers for?</h2>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <h4>The Map markers show mark each business that uses the Hey It’s Ready Application.</h4>
        <ol id="sub-ul">
          <li class="li-heading">When you touch on a Map marker it will display the Business Name, phone number and Website URL.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/directions_from_icon.png')}}"/></p>
        </ol>
      </div>
    </div>
    <div id="android-search-for-business">
      <div class="col-lg-12 col-12 text-center mt-5">
        <h2>How do I search for businesses that use “Hey It’s Ready”?</h2>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <ol id="sub-ul">
          <li class="li-heading">To locate businesses that use the Hey It’s Ready App you are able to zoom in and out of the map using 2 fingers or you are able to type the name of a city in the location window.</li>
          <li class="li-heading">To search by city or town please click on the search box located at the top of the home screen.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/view_select_home.png')}}"/></p>
          <li class="li-heading">Enter a city by name and then choose from the drop down list.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/search_new_city_name.png')}}"/></p>
        </ol>
      </div>
    </div>
    <div id="android-call-in-orders">
      <div class="col-lg-12 col-12 text-center mt-5">
        <h2>How does Hey It’s Ready App work for Call in Orders?</h2>
      </div>
      <div class="col-lg-12 col-12 mt-2">
        <ol id="sub-ul">
          <li class="li-heading">Place your order with the business over the phone with the name and phone number that is used in the Hey It’s Ready App.</li>
          <li class="li-heading">The Business will enter your name and number and an <b>“EPUT”</b> (<b>E</b>stimated <b>P</b>ick <b>U</b>p <b>T</b>ime). You will receive a popup message to confirm your order. </li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/Confirm_order.png')}}"/></p>
          <li class="li-heading">Once you select “Confirm” you will receive a notification that the order is confirmed with the Date and EPUT for when the order will be ready for pickup and the timer will start counting down showing the time remaining until the order is ready. </li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/my_order_confirmed.png')}}"/></p>
          <li class="li-heading">When your order is ready for pick up you will get a notification from the business owner that your order is ready.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/Order_ready.png')}}"/></p>
          <li class="li-heading">Once you have picked up your order you will receive a final notification that your order has been completed.</li>
          <p class="img-li"><img src="{{asset('img/Help/Ios/order_completed1.png')}}"/></p>
        </ol>
      </div>
    </div>
  </section>
</div>
@endsection
