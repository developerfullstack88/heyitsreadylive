<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
  if(auth()->user()):
    return redirect()->route('home');
  endif;
    return view('welcome');
})->name('heyItsReadyHome');
Route::get('/test-qr', 'HomeController@testQr')->name('testEmail');
Route::get('/marketing-qr', 'HomeController@marketingQr')->name('marketingQr');
Route::get('/track-qr', 'HomeController@trackQr')->name('trackQr');
Route::get('thankyou', 'HomeController@thankyou')->name('thankyou');
Route::get('activate-account/{id}', 'HomeController@activateAccount')->name('activateAccount');
Route::post('activate-account/{id}', 'HomeController@activateAccount')->name('activateAccountPost');
Auth::routes();
Route::get('privacy-policy', 'HomeController@privacyPolicy')->name('privacyPolicy');
Route::get('terms-services', 'HomeController@termsServices')->name('termsServices');
Route::get('setup-guides', 'HomeController@setupGuides')->name('setupGuides');
Route::get('android-help', 'AndroidHelpController@index')->name('androidHelp');
Route::get('ios-help', 'IosHelpController@index')->name('IosHelp');
Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
Route::get('coming-soon',function(){
  return view('coming-soon');
})->name('coming-soon');

Route::get('users/order-create/','UsersController@orderCreate')->name('userOrderCreate');
Route::post('users/profile-image-change', 'UsersController@profileImageChange')->name('users.profile.image');
Route::get('users/delete-account/{id}','UsersController@deleteAccount')->name('userAccountSoftDelete');
Route::resource('users','UsersController');

Route::get('orders/get-user-info/','OrderController@getUserInfo');
Route::get('/ajax-user-order/{cid}', 'OrderController@ajaxUserOrder')->name('ajaxUserOrder');
Route::get('/ajax-track-detail/{id}', 'OrderController@ajaxTrackDetail')->name('ajaxTrackDetail');
Route::get('company/item-qr-images','CompanyController@itemQrImages')->name('itemQrImages');
Route::post('payment/success', 'PaymentController@success')->name('payment.success');
Route::post('payment/failed', 'PaymentController@failed')->name('payment.failed');

Route::get('track-order', 'HomeController@trackOrder')->name('home.trackOrder');
Route::post('track-order', 'HomeController@trackOrder')->name('home.trackOrder');

/*Super admin*/
Route::get('admin-login', 'Admin\SuperController@login')->name('super-admin-login');
Route::post('admin-login', 'Admin\SuperController@login')->name('super-admin-login-post');
/*Super admin*/

Route::post('fetch-states', 'DropdownController@fetchState')->name('fetchState');
Route::post('fetch-cities', 'DropdownController@fetchCity')->name('fetchCity');

/*AFTER LOGIN ROUTES*/
Route::group(['middleware' => ['web','auth']], function () {
  Route::get('/home', 'HomeController@index')->name('home');
  Route::get('/home/send-incomplete-push/{oid}', 'HomeController@sendIncompletePush')->name('sendIncompletePush');
  Route::post('/save-device-token', 'HomeController@saveToken')->name('saveToken');


  Route::post('orders/set-eta','OrderController@setEta');
  Route::get('/ajax-orders/{company}', 'OrderController@ajaxOrders')->name('ajaxOrders');
  Route::get('/update-ajax-orders/{company}', 'OrderController@updateAjaxOrders')->name('updateAjaxOrders');
  Route::get('/ajax-calls-orders/{company}', 'OrderController@ajaxCallsOrders')->name('ajaxCallsOrders');
  Route::get('/ajax-order-detail/{oid}', 'OrderController@ajaxOrderDetail')->name('ajaxOrderDetail');
  Route::post('orders/delete', 'OrderController@delete')->name('orders.delete.selected');
  Route::get('orders/delete-complete', 'OrderController@deleteCompleteOrder')->name('orders.delete-complete');
  Route::get('orders/ready-reminder/{id}', 'OrderController@readyReminder')->name('orders.ready-reminder');
  Route::post('orders/item-qr-images-reorder', 'OrderController@imagesReorder')->name('orders.images-reorder');
  Route::put('orders/update-order-detail/{id}', 'OrderController@updateOrderDetail')->name('orders.update-order-detail');
  Route::get('orders/check-spot-exist/{oid}', 'OrderController@checkSpotExist')->name('orders.spot.exist');
  Route::get('orders/get-order-locate/{oid}', 'OrderController@getOrderLocate')->name('orders.order.locate');
  Route::get('orders/set-order-paid/{id}', 'OrderController@setOrderPaid')->name('set-order-paid');
  Route::resource('orders','OrderController');

  Route::get('company/order-qr/','CompanyController@orderQr')->name('QrOrder');
  Route::get('company/item-qr/{qr}','CompanyController@itemQr')->name('itemQr');
  Route::get('company/item-qr-preview/','CompanyController@itemQrImagesPreview')->name('itemQrImagesPreview');
  Route::post('company/upload-qr-item/','CompanyController@UploadMenuImage')->name('UploadMenuImage');
  Route::get('company/order-qr-pdf/','CompanyController@orderPdfQr')->name('orderPdfQr');
  Route::get('company/item-qr-pdf/','CompanyController@itemQrPdf')->name('itemQrPdf');
  Route::get('company/delete-item-qr-images/{id}', 'CompanyController@deleteItemImage')->name('orders.delete-item-qr-images');
  Route::resource('company','CompanyController');

  Route::get('sites/delete/{sites}', 'SiteController@destroy')->name('sites.delete');
  Route::get('sites/set-default/{sites}', 'SiteController@setDefault')->name('sites.set-default');
  Route::resource('sites', 'SiteController');

  Route::get('reports', 'ReportController@index')->name('reports.index');
  Route::get('reports/payouts', 'ReportController@payouts')->name('reports.payouts');
  Route::get('reports/payments', 'ReportController@payments')->name('reports.payments');
  Route::get('reports/store', 'ReportController@store')->name('reports.store');
  Route::resource('reports', 'ReportController')->except(['index','store']);

  Route::post('settings/add-new-card','BillingController@addNewCard')->name('settings.newCard');
  Route::put('settings/update-card','BillingController@updateCard')->name('settings.updateCard');
  Route::put('settings/update-plan/{id}','BillingController@updatePlan')->name('settings.updatePlan');

  Route::put('settings/delete-complete-order','BillingController@deleteCompleteOrder')->name('settings.deleteCompleteOrder');
  Route::put('settings/estimated-delivery-time','BillingController@estimatedDeliveryTime')->name('settings.estimatedDeliveryTime');
  Route::get('settings/add-connect-account','BillingController@addConnectAccount')->name('settings.addConnectAccount');
  Route::get('settings/return-connect-account','BillingController@returnConnectAccount')->name('settings.returnConnectAccount');
  Route::resource('settings', 'BillingController');
  Route::get('language/{locale}', 'HomeController@setLocale')->name('language.locale');

  Route::get('categories/listing/{menu_id}', 'CategoryController@index')->name('categories.listing');
  Route::get('categories/create/{menu_id}', 'CategoryController@create')->name('categories.add');
  Route::get('categories/delete/{categories}', 'CategoryController@destroy')->name('categories.delete');
  Route::resource('categories','CategoryController');

  Route::get('items/listing/{category_id}', 'MenuController@index')->name('items.listing');
  Route::get('items/create/{category_id}', 'MenuController@create')->name('items.add');
  Route::get('items/delete/{menus}', 'MenuController@destroy')->name('items.delete');
  Route::get('items/get-category-extras/{cid}', 'MenuController@getCategoryExtras')->name('items.getCategoryExtras');
  Route::resource('items','MenuController');

  Route::get('menus/delete/{menus}', 'RestaurantMenuController@destroy')->name('menus.delete');
  Route::resource('menus','RestaurantMenuController');

  Route::get('extras/listing/{extras}', 'ExtraController@index')->name('extras.listing');
  Route::get('extras/create/{extras}', 'ExtraController@create')->name('extras.add');
  Route::get('extras/delete/{extras}', 'ExtraController@destroy')->name('extras.delete');
  Route::resource('extras', 'ExtraController');

  Route::get('taxes/delete/{taxes}', 'TaxController@destroy')->name('taxes.delete');
  Route::resource('taxes', 'TaxController');

  Route::get('staffs/delete/{staff}', 'StaffController@destroy')->name('staffs.delete');
  Route::get('staffs/resend-email/{staff}', 'StaffController@resendEmail')->name('staffs.resend-email');
  Route::resource('staffs','StaffController');
});

Route::group(['middleware' => ['admin']], function () {
  Route::get('admin/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard');
  Route::get('admin/landlord/', 'Admin\LandlordController@index')->name('admin.landlord.index');
  Route::post('admin/landlord/add', 'Admin\LandlordController@store')->name('admin.landlord.add');
  Route::put('admin/landlord/update/{id}', 'Admin\LandlordController@update')->name('admin.landlord.update');
  Route::get('admin/landlord/delete/{id}', 'Admin\LandlordController@destroy')->name('admin.landlord.delete');
  Route::get('admin/email-exist', 'Admin\SuperController@emailExist')->name('admin.emailExist');

  Route::get('business-users/delete/{id},{type}', 'Admin\BusinessUsersController@delete')->name('business-users.delete');
  Route::resource('business-users','Admin\BusinessUsersController');
});
