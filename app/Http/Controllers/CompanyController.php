<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use PDF;
use Illuminate\Support\Facades\URL;
use App\Company;
use App\QrItem;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      /*block access of full software*/
      $this->middleware(function ($request, $next) {
        if(auth()->user()){
          $currentRoute=\Route::currentRouteName();
          if(checkFreeSoftwareExpire() && checkSubscriptionExpire() && $currentRoute!='itemQr'){
            Session::flash('warning', "If you want to use Hey It's Ready full version, please go to “Settings” and select “Enable”");
            return redirect()->route('itemQr',0);
          }else{
            return $next($request);
          }

        }else{
          return $next($request);
        }
      });
      /*block access of full software*/

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->middleware('auth',['except'=>['itemQrImages']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /*generate order qr code*/
    public function orderQr(){
      $company_id=Auth::user()->company_id;
      $qrUrl=url('/').'/users/order-create?company_id='.$company_id.'&type=order';
      // instantiate the barcode class
      $barcode = new \Com\Tecnick\Barcode\Barcode();
      // generate a barcode
      $bobj = $barcode->getBarcodeObj(
          'QRCODE,H',                     // barcode type and additional comma-separated parameters
          $qrUrl,          // data string to encode
          -6,                             // bar width (use absolute or negative value as multiplication factor)
          -6,                             // bar height (use absolute or negative value as multiplication factor)
          'black',                        // foreground color
          array(-2, -2, -2, -1)           // padding (use absolute or negative values as multiplication factors)
          )->setBackgroundColor('white'); // background color
      return view('companies.qr-code',compact('bobj'));
    }

    /*generate pdf order qr code*/
    public function orderPdfQr(){
      $company_id=Auth::user()->company_id;
      $qrUrl=url('/').'/users/order-create?company_id='.$company_id.'&type=order';
      // instantiate the barcode class
      $barcode = new \Com\Tecnick\Barcode\Barcode();
      // generate a barcode
      $data['bobj'] = $barcode->getBarcodeObj(
          'QRCODE,H',                     // barcode type and additional comma-separated parameters
          $qrUrl,          // data string to encode
          -8,                             // bar width (use absolute or negative value as multiplication factor)
          -8,                             // bar height (use absolute or negative value as multiplication factor)
          'black',                        // foreground color
          array(-2, -2, -2, -20)           // padding (use absolute or negative values as multiplication factors)
          )->setBackgroundColor('white'); // background color
      view()->share('bobj',$data['bobj']);

      /*Google play store QR*/
      $qrUrl="https://play.google.com/store/apps/details?id=com.heyitsready";
      $data['googleplay'] = $barcode->getBarcodeObj(
          'QRCODE,H',                     // barcode type and additional comma-separated parameters
          $qrUrl,          // data string to encode
          -7,                             // bar width (use absolute or negative value as multiplication factor)
          -7,                             // bar height (use absolute or negative value as multiplication factor)
          'black',                        // foreground color
          array(-2, -2, -2, -20)           // padding (use absolute or negative values as multiplication factors)
          )->setBackgroundColor('white'); // background color

      view()->share('googleplay',$data['googleplay']);

      /*apple play store QR*/
      $qrUrl1="https://apps.apple.com/us/app/hey-its-ready/id1525567239";
      $data['appleplay'] = $barcode->getBarcodeObj(
          'QRCODE,H',                     // barcode type and additional comma-separated parameters
          $qrUrl1,          // data string to encode
          -7,                             // bar width (use absolute or negative value as multiplication factor)
          -7,                             // bar height (use absolute or negative value as multiplication factor)
          'black',                        // foreground color
          array(-2, -2, -2, -20)             // padding (use absolute or negative values as multiplication factors)
          )->setBackgroundColor('white'); // background color

      view()->share('appleplay',$data['appleplay']);
      $customPaper = array(0,0,900,500);
      $pdf = PDF::loadView('companies.qr-pdf', $data)->setPaper($customPaper, 'landscape');
      //return view('companies.qr-pdf',compact('data'));
      return $pdf->download('pdf_file2.pdf');
    }

    /*upload menu image for generating qr of items*/
    public function itemQr($qr=0){
      $companyInfo=QrItem::where('company_id',Auth::user()->company_id)->get();
      $companyInfoMenu=QrItem::where('company_id',Auth::user()->company_id)->count();
      if($companyInfoMenu>0 && $qr==0){
        return redirect()->route('itemQrImagesPreview');
      }
      if($companyInfo){
        $qrUrl=url('/').'/company/item-qr-images?company_id='.Auth::user()->company_id.'&type=menu';
        // instantiate the barcode class
        $barcode = new \Com\Tecnick\Barcode\Barcode();
        // generate a barcode
        $bobj = $barcode->getBarcodeObj(
            'QRCODE,H',                     // barcode type and additional comma-separated parameters
            $qrUrl,          // data string to encode
            -6,                             // bar width (use absolute or negative value as multiplication factor)
            -6,                             // bar height (use absolute or negative value as multiplication factor)
            'black',                        // foreground color
            array(-2, -2, -2, -1)           // padding (use absolute or negative values as multiplication factors)
            )->setBackgroundColor('white'); // background color
        return view('companies.item-qr',compact('companyInfo','bobj','companyInfoMenu'));
      }
      return view('companies.item-qr',compact('companyInfo','companyInfoMenu'));
    }

    /*Item qr pdf method*/
    public function itemQrPdf(){
      $companyInfoMenu=QrItem::where('company_id',Auth::user()->company_id)->count();
      if($companyInfoMenu>0){
        $qrUrl=url('/').'/company/item-qr-images?company_id='.Auth::user()->company_id.'&type=menu';
        // instantiate the barcode class
        $barcode = new \Com\Tecnick\Barcode\Barcode();
        // generate a barcode
        $data['bobj'] = $barcode->getBarcodeObj(
            'QRCODE,H',                     // barcode type and additional comma-separated parameters
            $qrUrl,          // data string to encode
            -7,                             // bar width (use absolute or negative value as multiplication factor)
            -7,                             // bar height (use absolute or negative value as multiplication factor)
            'black',                        // foreground color
            array(-2, -2, -2, -27)           // padding (use absolute or negative values as multiplication factors)
            )->setBackgroundColor('white'); // background color

            view()->share('bobj',$data['bobj']);

            /*Google play store QR*/
            $qrUrl="https://play.google.com/store/apps/details?id=com.heyitsready";
            $data['googleplay'] = $barcode->getBarcodeObj(
                'QRCODE,H',                     // barcode type and additional comma-separated parameters
                $qrUrl,          // data string to encode
                -4,                             // bar width (use absolute or negative value as multiplication factor)
                -4,                             // bar height (use absolute or negative value as multiplication factor)
                'black',                        // foreground color
                array(-2, -2, -2, -27)           // padding (use absolute or negative values as multiplication factors)
                )->setBackgroundColor('white'); // background color

            view()->share('googleplay',$data['googleplay']);

            /*apple play store QR*/
            $qrUrl1="https://apps.apple.com/us/app/hey-its-ready/id1525567239";
            $data['appleplay1'] = $barcode->getBarcodeObj(
                'QRCODE,H',                     // barcode type and additional comma-separated parameters
                $qrUrl1,          // data string to encode
                -4,                             // bar width (use absolute or negative value as multiplication factor)
                -4,                             // bar height (use absolute or negative value as multiplication factor)
                'black',                        // foreground color
                array(-2, -2, -2, -27)           // padding (use absolute or negative values as multiplication factors)
                )->setBackgroundColor('white'); // background color

            view()->share('appleplay1',$data['appleplay1']);
            $customPaper = array(0,0,900,500);
            $pdf = PDF::loadView('companies.qr-item-pdf', $data)->setPaper($customPaper, 'landscape');
            //return view('companies.qr-item-pdf',compact('data'));
            return $pdf->download('item_qr_pdf.pdf');
      }
    }

    /*Upload item menu images*/
    public function UploadMenuImage(Request $request){
      /*$request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
      ]);*/
      if($request->all()){
        $postedData=$request->all();
        foreach ($postedData['image'] as $post) {
          $imageName = time().'.'.$post->extension();
          $uploadPath='images/menu-item/'.Auth::user()->company_id;
          $fullPath=public_path($uploadPath);
          $post->move($fullPath, $imageName);

          /*Add qr item table*/
          $qrItem = new QrItem;
          $qrItem->image_path=$uploadPath.'/'.$imageName;
          $qrItem->company_id=Auth::user()->company_id;
          $qrItem->save();
          /*Add qr item table*/
        }
      }
      return redirect()->route('itemQrImagesPreview');
    }

    /*get menu item images*/
    public function itemQrImages(Request $request){
      $cid=$request->get('company_id');
      if($cid){
        $companyItemInfo=QrItem::where('company_id',$cid)
        ->orderBy('img_order')
        ->get();
        return view('companies.item-qr-images',compact('companyItemInfo'));
      }
    }

    /*This method will show all Qr menu images
    in Thumbnal*/
    public function itemQrImagesPreview(){
      $companyItemInfo=QrItem::where('company_id',auth()->user()->company_id)
      ->orderBy('img_order')
      ->get();
      return view('companies.item-qr-images-preview',compact('companyItemInfo'));
    }

    /*
    @developer:-jasmaninder
    @method:-deleteItemImage
    @description delete  item qr image
    */
    public function deleteItemImage($id){
      if($id){
        $qrItemImageInfo=QrItem::find($id);
        if($qrItemImageInfo){
          $qrItemImageInfo->delete();
          echo "success";die;
        }else{
          echo "failed";die;
        }
      }
    }
}
