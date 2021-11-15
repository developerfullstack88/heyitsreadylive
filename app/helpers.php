<?php
use App\Company;
use App\User;
use App\Order;
use App\Subscription;
use App\Category;
use App\Country;
use App\RestaurantMenu;
use App\Cart;
use App\Menu;
use App\Extra;
use App\Tax;
use App\Payment;
use Carbon\Carbon;

  //this will get all company from db
  if(! function_exists('all_companies')){
    function all_companies(){
      return Company::all()->pluck('name','id');
    }
  }

  //this will return all roles of website
  if(!function_exists('all_roles')){
    function all_roles(){
      if(Auth::user()->role==Config::get('constants.role.subadmin')) {
        $manager=Config::get('constants.role.manager');
        $teamMember=Config::get('constants.role.teammember');
        return $role=array(
            $manager=>ucfirst($manager),
            $teamMember=>ucfirst($teamMember)
          );
      }
    }
  }

  if(!function_exists('admin_roles')){
    function admin_roles(){
      if(auth()->user()->role==COMPANY) {
        return $role=array(
            'manager'=>ucfirst(MANAGER),
            'supervisor'=>ucfirst(SUPERVISOR)
          );
      }
    }
  }

  if(!function_exists('manager_roles')){
    function manager_roles(){
      if(auth()->user()->role==MANAGER) {
        return $role=array(
            'supervisor'=>ucfirst(SUPERVISOR)
          );
      }
    }
  }

  //this will return all countries name
  if(!function_exists('all_countries')){
    function all_countries(){
      return $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh",
      "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada",
      "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark",
      "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories",
      "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong",
      "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic",
      "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius",
      "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria",
      "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation",
      "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka",
      "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan",
      "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia",
      "Zimbabwe");
    }
}

/*This method will get company order id*/
if(!function_exists('getCompanyOrderNumber')){
  function getCompanyOrderNumber($company_id){
    $orderInfo=Order::where('company_id',$company_id);
    if($count=$orderInfo->count()==0){
      return 1;
    }else{
      $lastid=Order::orderBy('created_at', 'desc')->first()->id;
      return $lastid+1;
    }

  }
}

//this method will used for timediffernce
if(!function_exists('getTimeDifference')){
  function getTimeDifference($firstTime){
    $date2=Timezone::convertToLocal(Carbon::now(),'Y-m-d H:i:s');
    $firstTime=Carbon::create($firstTime);
    $firstTime=Timezone::convertToLocal($firstTime,'Y-m-d H:i:s');
		$date2 = strtotime($date2);
		$date1 = strtotime($firstTime);
		$diff = abs($date2 - $date1);
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 -  $months*30*60*60*24)/ (60*60*24));
		$hours = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24) / (60*60));
		$minutes = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24  - $hours*60*60)/ 60);
		$seconds = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
		$time = '';
		if($months>0){
			$time.=	"month".$months;
		}
		if( $days>0){
			$time.=	"".$days."day";
		}
		if($hours>0){
			$time.=	" ".$hours."h";
		}
		if($minutes>0){
			$time.=	" ".$minutes."m";
		}
		if($seconds>0){
			$time.=	" ".$seconds."s";
		}
    if($minutes<0 || $seconds<0) {
      return '';
    }else{
      return $time;
    }

  }
}

if(!function_exists('getTwoTimesDifference')){
  function getTwoTimesDifference($firstTime,$secondTime){
    $firstTime=Carbon::create($firstTime);
    $firstTime=Timezone::convertToLocal($firstTime,'Y-m-d H:i:s');
    $secondTime=Carbon::create($secondTime);
    $date2=Timezone::convertToLocal($secondTime,'Y-m-d H:i:s');
		$date2 = strtotime($date2);
		$date1 = strtotime($firstTime);
		$diff = abs($date2 - $date1);
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 -  $months*30*60*60*24)/ (60*60*24));
		$hours = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24) / (60*60));
		$minutes = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24  - $hours*60*60)/ 60);
		$seconds = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
		$time = '';
		if($months>0){
			$time.=	"month".$months;
		}
		if( $days>0){
			$time.=	"".$days."day";
		}
		if($hours>0){
			$time.=	" ".$hours."h";
		}
		if($minutes>0){
			$time.=	" ".$minutes."m";
		}
		if($seconds>0){
			$time.=	" ".$seconds."s";
		}
    if($minutes<0 || $seconds<0) {
      return '';
    }else{
      return $time;
    }

  }
}

//this method will used for timediffernce
if(!function_exists('getTimeDifferenceMobile')){
  function getTimeDifferenceMobile($firstTime){
    //$date2 = date("Y-m-d H:i:s");
    $date2=Timezone::convertToLocal(Carbon::now(),'Y-m-d H:i:s');
    $firstTime=Carbon::create($firstTime);
    $firstTime=Timezone::convertToLocal($firstTime,'Y-m-d H:i:s');
    if(!checkTimeGreater($firstTime)){
      return '';
    }
		$date2 = strtotime($date2);
		$date1 = strtotime($firstTime);
		$diff = abs($date2 - $date1);
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 -  $months*30*60*60*24)/ (60*60*24));
		$hours = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24) / (60*60));
		$minutes = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24  - $hours*60*60)/ 60);
		$seconds = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
		$time = '';

    if($hours<=0){
      $time=	strlen($minutes)>1?$minutes.":":"0".$minutes.":";
      $time.=strlen($seconds)>1?$seconds:"0".$seconds;
      if($minutes<1){
        $time=$time.' sec';
      }else{
        $time=$time.' min';
      }


    }else if($hours>0){
      $time=	$hours.":";
      $time.=strlen($minutes)>1?$minutes.' hr':"0".$minutes.' hr';
		}
    if($minutes<0 || $seconds<0) {
      return '';
    }else{
      return $time;
    }

  }
}

if(!function_exists('getTimezoneDiff')){
  function getTimezoneDiff($firstTime,$timezone){
    $date2=Carbon::now()->timezone($timezone)->format('Y-m-d H:i:s');
    $firstTime=Carbon::create($firstTime)->timezone($timezone)->format('Y-m-d H:i:s');
    if(!checkTimeGreater($firstTime)){
      return '';
    }
		$date2 = strtotime($date2);
		$date1 = strtotime($firstTime);
		$diff = abs($date2 - $date1);
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 -  $months*30*60*60*24)/ (60*60*24));
		$hours = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24) / (60*60));
		$minutes = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24  - $hours*60*60)/ 60);
		$seconds = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
		$time = '';

    if($hours<=0){
      /*$time=	strlen($minutes)>1?$minutes.":":"0".$minutes.":";
      $time.=strlen($seconds)>1?$seconds:"0".$seconds;
      if($minutes<1){
        $time=$time.' sec';
      }else{
        $time=$time.' min';
      }*/
      $time=$minutes.'m ';
      $time.=$seconds.'s';

    }else if($hours>0){
      $time=	$hours.":";
      $time.=strlen($minutes)>1?$minutes.' hr':"0".$minutes.' hr';
		}
    if($minutes<0 || $seconds<0) {
      return '';
    }else{
      return $time;
    }

  }
}

//set phone formatting like masking
if(!function_exists('getPhoneFormat')){
  function getPhoneFormat($phone){
    if($phone){
      $phone=explode(" ",$phone);
      $phoneCode='';
      if(count($phone)>0){
        $phoneCode=$phone[0];
        $phone=$phone[1];
      }
      $ac = substr($phone, 0, 3); // 123
      $prefix = substr($phone, 3, 3); // 456
      $suffix = substr($phone, 6); // 7890
      return $phoneCode.' ('.$ac.') '.$prefix.'-'.$suffix;
    }else{
      return false;
    }
  }
}

//check if string is valid date
if(!function_exists('validateDate')){
  function validateDate($date, $format = 'Y-m-d H:i:s')
  {
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) === $date;
  }
}

//check time is greater than current time
if(!function_exists('checkTimeGreater')){
  function checkTimeGreater($etaTime){
	   $currentDateTime=Timezone::convertToLocal(Carbon::now(),'Y-m-d H:i:s');
		return ($etaTime>=$currentDateTime)?true:false;
	}
}

//get address components
if(! function_exists('getaddress')){
  function getaddress(){
     $userInfo=User::with(['company'])->find(Auth::user()->id);
     if($userInfo->has('company')){
       $addr=$userInfo->company->address;
       $addr = preg_replace('/\s+/', '', $addr);
       $url = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBOB2S7yoLqno0FIagdBu7X0PpuU5ggsiY&address=".$addr."&sensor=false";
       $json = @file_get_contents($url);
       $data=json_decode($json);
       if($data->status){
         $lat=$data->results[0]->geometry->location->lat;
         $lng=$data->results[0]->geometry->location->lng;
        $data = @file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBOB2S7yoLqno0FIagdBu7X0PpuU5ggsiY&latlng=".$lat.",".$lng."&sensor=false");
        $data = json_decode($data);
        $add_array  = $data->results;
        $add_array = $add_array[0];
        $add_array = $add_array->address_components;
        foreach ($add_array as $key) {
          if($key->types[0] == 'administrative_area_level_2'){
            return $key->long_name;
          }
          else if($key->types[0] == 'administrative_area_level_3'){
            return $key->long_name;
          }
        }
       }
     }

  }
}

//get address components
if(! function_exists('getLatLng')){
  function getLatLng($addr){
    $addr = preg_replace('/\s+/', '', $addr);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBOB2S7yoLqno0FIagdBu7X0PpuU5ggsiY&address=".$addr."&sensor=false";
    $json = @file_get_contents($url);
    $data=json_decode($json);
    return ($data->status=="OK")?$data->results[0]->geometry->location->lat.','.$data->results[0]->geometry->location->lng:false;
  }
}

//get business Name
if(! function_exists('getBusinessName')){
  function getBusinessName(){
    $userInfo=User::with(['company'])->find(Auth::user()->id);
    if($userInfo->has('company')){
      return $userInfo->company->company_name;
    }
  }
}

if(! function_exists('getBusinessNameById')){
  function getBusinessNameById($id){
    return Company::find($id)->company_name;
  }
}

//This method will update device token for user id
if(! function_exists('updateDeviceToken')){
  function updateDeviceToken($id,$token){
    $userInfo=User::find($id);
    $userInfo->device_token=$token;
    if($userInfo->save()){
      return true;
    }else{
      return false;
    }
  }
}

//This method will update device type for user id
if(! function_exists('updateDeviceType')){
  function updateDeviceType($id,$deviceType){
    $userInfo=User::find($id);
    $userInfo->device_type=$deviceType;
    if($userInfo->save()){
      return true;
    }else{
      return false;
    }
  }
}

//This method will update ios version for user id
if(! function_exists('updateIosVersion')){
  function updateIosVersion($id,$iosVersion){
    $userInfo=User::find($id);
    $userInfo->ios_version=$iosVersion;
    if($userInfo->save()){
      return true;
    }else{
      return false;
    }
  }
}


//This method will app used for user id
if(! function_exists('updateAppUsed')){
  function updateAppUsed($id,$appUsed){
    $userInfo=User::find($id);
    $userInfo->app_used=$appUsed;
    if($userInfo->save()){
      return true;
    }else{
      return false;
    }
  }
}

//This method will update device token for user id
if(! function_exists('updateTimzone')){
  function updateTimzone($id,$timezone){
    $userInfo=User::find($id);
    $userInfo->timezone=$timezone;
    if($userInfo->save()){
      return true;
    }else{
      return false;
    }
  }
}

//This method will update login token for user id
if(! function_exists('updateLoginToken')){
  function updateLoginToken($id,$tokenString){
    $userInfo=User::find($id);
    $userInfo->login_token=$tokenString;
    if($userInfo->save()){
      return true;
    }else{
      return false;
    }
  }
}

//This method will update background field for user_id
if(! function_exists('updateBackground')){
  function updateBackground($id,$value){
    $userInfo=User::find($id);
    $userInfo->background=$value;
    if($userInfo->save()){
      return true;
    }else{
      return false;
    }
  }
}

//this method will upload profile photo
if(! function_exists('uploadProfilePhoto')){
  function uploadProfilePhoto($id,$photoString){
    if($photoString){
      $imgdata = base64_decode($photoString);
      $f = finfo_open();
      $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
      list($type,$extension)=explode("/",$mime_type);
      $imageName = 'users/'.$id. '/profile_'.time().'.'.$extension; //generating unique file name;
      Storage::disk('public')->put($imageName,$imgdata);
      return URL::to('/').Storage::url($imageName);
    }
  }
}

//this will return all timezones name
if(!function_exists('all_timezones')){
  function all_timezones(){
    return $timezones = array(
      'Asia/Kolkata'=>'Asia/Kolkata',
      'America/New_York'=>'Toronto/Quebec',
      'US/Samoa'=>'Samoa(USA)',
      'US/Hawaii'=>'Hawaii(USA)',
      'US/Alaska'=>'Alaska(USA)',
      'America/Tijuana'=>'Tijuana(USA)',
      'US/Arizona'=>'Arizona(USA)',
      'America/Mexico_City'=>'Mexico_City(USA)',
      'America/Monterrey'=>'Monterrey(USA)',
      'Canada/Saskatchewan'=>'Saskatchewan(Canada)',
      'America/Los_Angeles'=>'Los_Angeles(USA)',
      'America/Vancouver'=>'Vancouver(USA)',
      'America/Denver'=>'Denver(USA)',
      'America/Jamaica'=>'Jamaica(USA)',
      'America/Santiago'=>'Santiago(USA)',
      'Canada/Pacific'=>'Pacific(Canada)',
      'Canada/Yukon'=>'Yukon(Canada)',
      'Canada/Mountain'=>'Mountain(Canada)',
      'Canada/Central'=>'Central(Canada)',
      'America/Caracas'=>'Venezuela(USA)'
    );
  }
}

//get current local time
if(!function_exists('currentLocalTime')){
  function currentLocalTime(){
    $currentDate=Carbon::now()->format('Y-m-d');
    if(auth()->user()->timezone=='EDT' && $currentDate>'2020-03-07' && $currentDate<'2020-11-01'){
      $date=Carbon::now()->addHours(1);
    }else{
      $date=Carbon::now();
    }
    return Timezone::convertToLocal($date,'Y-m-d h:i a');
  }
}

//get current local time
	if(!function_exists('currentLocalTime24')){
	  function currentLocalTime24(){
	    $currentDate=Carbon::now()->format('Y-m-d');
	    if(auth()->user()->timezone=='EDT' && $currentDate>'2020-03-07' && $currentDate<'2020-11-01'){
	      $date=Carbon::now()->addHours(1);
	    }else{
	      $date=Carbon::now();
	    }
	    return Timezone::convertToLocal($date,'H:i');
	  }
	}


//get current local time
if(!function_exists('spanishMonth')){
  function spanishMonth($key){
    $monthsSpanish = array(
      'january' => 'Enero',
      'february' => 'Febrero',
      'march' => 'Marzo',
      'april' => 'Abril',
      'may' => 'Mayo',
      'june' => 'Junio',
      'july' => 'Julio',
      'august' => 'Agosto',
      'september' => 'Septiembre',
      'october' => 'Octubre',
      'november' => 'Noviembre',
      'december' => 'Diciembre',
    );
    return $monthsSpanish[$key];
  }
}

if(!function_exists('frenchMonth')){
  function frenchMonth($key){
    $monthsFrench = array(
      'january' => 'Janvier',
      'february' => 'février',
      'march' => 'Mars',
      'april' => 'Avril',
      'may' => 'Peut',
      'june' => 'juin',
      'july' => 'juillet',
      'august' => 'Août',
      'september' => 'Septembre',
      'october' => 'Octobre',
      'november' => 'Novembre',
      'december' => 'Décembre',
    );
    return $monthsFrench[$key];
  }
}
if(!function_exists('dashboardDate')){
  function dashboardDate(){
    $date=Carbon::now();
    $fullDateTime=Timezone::convertToLocal($date,'F d, Y');
    $lang=Config::get('app.locale');

    $fullDateTimeArr=explode(" ",$fullDateTime);
    $mnthName=strtolower($fullDateTimeArr[0]);
    if($lang=='es'){
      $mnthName=spanishMonth($mnthName);
    }
    if($lang=='fr'){
      $mnthName=frenchMonth($mnthName);
    }
    return $mnthName.' '.Timezone::convertToLocal($date,'d, Y');
  }
}

//get current local time
if(!function_exists('dashboardTime')){
  function dashboardTime(){
    $currentDate=Carbon::now()->format('Y-m-d');
    if(auth()->user()->timezone=='EDT' && $currentDate>'2020-03-07' && $currentDate<'2020-11-01'){
      $date=Carbon::now()->addHours(1);
    }else{
      $date=Carbon::now();
    }
    return Timezone::convertToLocal($date,'h:i A');
  }
}

//get current local date
if(!function_exists('currentLocalDate')){
  function currentLocalDate(){
    $date=Carbon::now();
    return Timezone::convertToLocal($date,'Y-m-d');
  }
}

if(!function_exists('currentLocalFullDate')){
  function currentLocalFullDate(){
    $date=Carbon::now();
    return Timezone::convertToLocal($date,'Y-m-d H:i:s');
  }
}

//get current local time
if(!function_exists('convertToLocal')){
  function convertToLocal($dbDate,$format=0){
    $date=Carbon::create($dbDate);
    if($format==1){
      return Timezone::convertToLocal($date,'Y-m-d H:i:s');
    }else if($format==3){
      return Timezone::convertToLocal($date,'Y-m-d H:i');
    }else{
      return Timezone::convertToLocal($date,'d/m/Y H:i:s');
    }

  }
}

/*convert to local without creating with carbon*/
if(!function_exists('convertDirectToLocal')){
  function convertDirectToLocal($date,$time=0){
    if($time==1){
      return Timezone::convertToLocal($date,'h:i:s a');
    }else{
      return Timezone::convertToLocal($date,'Y-m-d h:i:s a');
    }
  }
}

//get current local time in 12 hours
if(!function_exists('convertToLocal12')){
  function convertToLocal12($dbDate,$format=0){
    $date=Carbon::create($dbDate);
    if($format==1){
      return Timezone::convertToLocal($date,'Y-m-d h:i:s a');
    }else if($format==3){
      return Timezone::convertToLocal($date,'Y-m-d h:i a');
    }
    return Timezone::convertToLocal($date,'d/m/Y h:i:s a');
  }
}

if(!function_exists('convertToLocal12TimeOnly')){
  function convertToLocal12TimeOnly($dbDate,$format=0){
    $date=Carbon::create($dbDate);
    if($format==1){
      return Timezone::convertToLocal($date,'H:i:s');
    }else if($format==2){
      return Timezone::convertToLocal($date,'h:i:s a');
    }else if($format==3){
      return Timezone::convertToLocal($date,'h:i a');
    }else{
      return Timezone::convertToLocal($date,'h:i:s a');
    }

  }
}

//get current local date
if(!function_exists('convertToLocalDate')){
  function convertToLocalDate($dbDate,$format=0){
    $date=Carbon::create($dbDate);
    if($format==1){
      return Timezone::convertToLocal($date,'Y-m-d');
    }else{
      return Timezone::convertToLocal($date,'d/m/Y H:i:s');
    }

  }
}

///*This method will generate new password*/
if(!function_exists('randomPassword')){
  function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!?~@#-_+<>[]{}';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
  }
}

///*This method will generate new password*/
if(!function_exists('getUserInfo')){
  function getUserInfo($id) {
    if($id){
      return User::find($id);
    }else{
      return false;
    }
  }
}

///*This method getcurrentUserWithRelation*/
if(!function_exists('getcurrentUserWithRelation')){
  function getcurrentUserWithRelation() {
      return User::with(['company'])->find(auth()->user()->id);
  }
}

///*Dashboard in progress count*/
if(!function_exists('getInprogressOrders')){
  function getInprogressOrders() {
    $currentDate=Timezone::convertToLocal(Carbon::now(),'Y-m-d');
    $pendingOrders=Order::where(['new_order'=>0,'status'=>'pending','company_id'=>Auth::user()->company_id,'deleted'=>0,'cancel'=>0])
    ->whereDate('created_at',$currentDate)->count();
    $confirmOrders=Order::where(['new_order'=>0,'status'=>'confirm','company_id'=>Auth::user()->company_id,'deleted'=>0,'cancel'=>0])
    ->whereDate('created_at',$currentDate)->count();
    return $pendingOrders+$confirmOrders;
  }
}

///*Dashboard ready order count*/
if(!function_exists('getReadyOrders')){
  function getReadyOrders() {
    $currentDate=Timezone::convertToLocal(Carbon::now(),'Y-m-d');
    return $inProgressOrders=Order::where(['company_id'=>Auth::user()->company_id,'status'=>'ready','deleted'=>0])
    ->whereDate('created_at',$currentDate)->count();
  }
}

///*Dashboard completed order count*/
if(!function_exists('getCompletedOrders')){
  function getCompletedOrders() {
    $currentDate=Timezone::convertToLocal(Carbon::now(),'Y-m-d');
    return $inProgressOrders=Order::where(['company_id'=>Auth::user()->company_id,'status'=>'complete','deleted'=>0])
    ->whereDate('created_at',$currentDate)->count();
  }
}

//get business Info
if(! function_exists('getBusinessInfo')){
  function getBusinessInfo($id){
    return Company::find($id);
  }
}

//get push server key
if(! function_exists('getPushServerKey')){
  function getPushServerKey(){
    //return 'AAAAtOvlIvo:APA91bEfqgfEJ4taBF-3fjY7vK1mgeoMRZkqZUTrqDx-OS6H7QFVDECf2rjPTojMj2jjjvgJUW2d6KmDbgoh5IhuB3oa7ul3t2qOfcBelMBOvm84gXpKg6HCyFXASnr94Uo8sAJ9zYbl';
    //return 'AAAA9fL759c:APA91bG-cThXzQHEpoWUX3Jj2WgN8IuUrR0f-PaJWauQmQ-75zxkwEPDZGfHB3MJXM3YokiKI0MtuHQTHzdCwz3T41MVEdfuqfo3leB_XRyCGZhh8h0JMlsjqjVwWflC1qv3kQ7rpq35';

    return 'AAAATQswizw:APA91bEOzVh35SzYbjYeMXFnHxZzQGkidkX00FJDq0mwFudTwkZxW75oE-3SnXVv6tSF-svPEnyk0HQ5m--Ufz2zsZ5WnAVfnYXcO20Tow5oEMYKhSPEVtECPPs47XZg3knQP1-BKe2l';
  }
}

//get user device token
if(! function_exists('getDeviceToken')){
  function getDeviceToken($uid){
    $userDetail=User::find($uid);
    if($userDetail && $userDetail->notification==1) {
      return $userDetail->device_token;
    }
  }
}

//get user device type
if(! function_exists('getDeviceType')){
  function getDeviceType($uid){
    $userDetail=User::find($uid);
      return $userDetail->device_type;
  }
}

//get login token
if(! function_exists('getLoginToken')){
  function getLoginToken($uid){
    $userDetail=User::find($uid);
      return $userDetail->login_token;
  }
}

//get user device version
if(! function_exists('getDeviceVersion')){
  function getDeviceVersion($uid){
    $userDetail=User::find($uid);
      return $userDetail->ios_version;
  }
}

//get user background
if(! function_exists('getBackground')){
  function getBackground($uid){
    $userDetail=User::find($uid);
      return $userDetail->background;
  }
}

//send push notification
if(! function_exists('sendPushNotification')){
  function sendPushNotification($data,$uid,$extraData=array()){
    $deviceTokenId=getDeviceToken($uid);
    $deviceType=getDeviceType($uid);
    $deviceVersion=getDeviceVersion($uid);
    $loginToken=getLoginToken($uid);
    $background=getBackground($uid);
    if($deviceTokenId && $loginToken){
      $url = "https://fcm.googleapis.com/fcm/send";
      $token = $deviceTokenId;
      $serverKey = getPushServerKey();
      if($deviceType=='IOS') {
        /*if(!$deviceVersion || $deviceVersion<14){*/
        if($background===0){
          $dataIos['sound']='Default';
          $dataIos['badge']=0;
          $dataIos['mutable-content']=1;
          $dataIos['priority']='high';
          $extraData['click_action']='FLUTTER_NOTIFICATION_CLICK';
          $extraData['title']=$data['title'];
          $extraData['content_available']=true;
          $extraData['priority']='high';
          $extraData['message']=$data['body'];
          if(!isset($extraData['type'])){
            $extraData['type']='';
          }
          $arrayToSend = array('content_available'=>true,'to' => $token, 'notification' => $dataIos,'priority'=>'high','data'=>$extraData);
        }else{
          $extraData['badge']=0;
          $data['sound']='default';
          $data['priority']='high';
          $data['type']=1;
          $extraData['click_action']='FLUTTER_NOTIFICATION_CLICK';
          $data['content_available']=false;
          $extraData['message']=$data['body'];
          if(!isset($extraData['type'])){
            $data['type']='';
          }
          $arrayToSend = array('content_available'=>false,'to' => $token,'notification'=>$data);
        }
        /*}else{
          //if($uid==11){
            if($background===0){
              $extraData['mutable-content']=1;
              $extraData['badge']=0;
              $extraData['sound']='default';
              $extraData['priority']='high';
              $extraData['click_action']='FLUTTER_NOTIFICATION_CLICK';
              $extraData['content_available']=true;
              //$extraData1['priority']='high';
              $extraData['title']=$data['title'];
              $extraData['message']=$data['body'];
              if(!isset($extraData['type'])){
                $extraData['type']='';
              }
              $arrayToSend = array('to' => $token,'data'=>$extraData,'priority'=>'high');
            }else{
              //$extraData['mutable-content']=1;
              $extraData['badge']=0;
              $data['sound']='default';
              $data['priority']='high';
              $data['type']=1;
              $extraData['click_action']='FLUTTER_NOTIFICATION_CLICK';
              $data['content_available']=false;
              //$extraData1['priority']='high';
              //$extraData['title']=$data['title'];
              $extraData['message']=$data['body'];
              //$extraData['notiType']=1;

              if(!isset($extraData['type'])){
                $data['type']='';
              }
              //$data['data']=$extraData;
              $arrayToSend = array('content_available'=>false,'to' => $token,'notification'=>$data);
            }
          /*}else{
            $extraData['mutable-content']=1;
            $extraData['badge']=0;
            $extraData['sound']='default';
            $extraData['priority']='high';
            $extraData['click_action']='FLUTTER_NOTIFICATION_CLICK';
            $extraData['content_available']=true;
            //$extraData1['priority']='high';
            $extraData['title']=$data['title'];
            $extraData['message']=$data['body'];
            if(!isset($extraData['type'])){
              $extraData['type']='';
            }
            $arrayToSend = array('to' => $token,'data'=>$extraData,'priority'=>'high');
          }

        }*/
      }else{
        $data['sound']='Default';
        $data['vibrate']=500000;
        $data['click_action']='FLUTTER_NOTIFICATION_CLICK';
        $arrayToSend = array('to' => $token, 'notification' => $data,'priority'=>'high','data'=>$extraData);
      }
      $json = json_encode($arrayToSend,JSON_FORCE_OBJECT);
      $headers = array();
      $headers[] = 'Content-Type: application/json';
      $headers[] = 'Authorization: key='. $serverKey;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
      curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
      //Send the request
      $response = curl_exec($ch);
      //Close request
      if ($response === FALSE) {
        echo "failed";die;
      }
      curl_close($ch);
    }
  }
}

/*This method will check 1 Month
free trial of user is expired or not*/
if(! function_exists('checkFreeSoftwareExpire')){
  function checkFreeSoftwareExpire(){
    $uid=auth()->user()->id;
    if($uid){
      $userInfo=User::find($uid);
      $currentDate=Carbon::now();
      $NextMonthUserDate=Carbon::create($userInfo->created_at->format('Y-m-d'))->addDay(60)->format('Y-m-d');
      return ($currentDate>$NextMonthUserDate)?true:false;
    }
  }
}

/*This method will check subscription of user expire or not*/
if(! function_exists('checkSubscriptionExpire')){
  function checkSubscriptionExpire(){
    $uid=auth()->user()->id;
    if($uid){
      $subscriptionInfo=Subscription::where('user_id',$uid)->get();
      if($subscriptionInfo->count()>0){
        $subscriptionInfo=$subscriptionInfo->last();
        $subscriptionDate=Carbon::create($subscriptionInfo->subscription_end_date)->format('Y-m-d');
        $currentDate=Carbon::now()->format('Y-m-d');
        return ($subscriptionDate>=$currentDate)?false:true;
      }else{
        return true;
      }
    }
  }
}

/*This method will get random order*/
if(!function_exists('getRandomOrderNumber')){
  function getRandomOrderNumber(){
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));
    for ($i = 0; $i < 4; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    $count=Order::where('order_number',$key)->count();
    if($count>0){
      $this->getRandomOrderNumber();
    }
    return $key;

  }
}

/*get latest order of user*/
if(!function_exists('getLatestUserOrder')){
  function getLatestUserOrder($uid){
    $orderInfo=Order::leftJoin('payments', function($join) {
      $join->on('orders.id', '=', 'payments.order_id');
    })->where('orders.user_id',$uid)->orderBy('orders.created_at','desc')
    ->whereNull('payments.order_id')
    ->select('orders.id','orders.order_number','orders.user_id','orders.eta','orders.status','orders.company_id','orders.amount','orders.actual_order_time')
    ->whereDate('orders.created_at', '>=', Carbon::now())
    ->where(['orders.confirm'=>0,'orders.deleted'=>0])
    ->whereNotIn( 'orders.status', ['complete'])
    ->limit(1);
    if($orderInfo->count()>0){
      $orderInfo=$orderInfo->first();
      if($orderInfo){
        if(auth()->user()->timezone && $orderInfo->status=='confirm'){
          //$orderInfo->eta=($orderInfo->eta)?convertToLocal12($orderInfo->eta,1):null;
          $orderInfo->eta_diff=getTimeDifferenceMobile($orderInfo->eta);
        }else{
          //$orderInfo->eta=$orderInfo->eta;
          $orderInfo->eta_diff='';
        }
        if($orderInfo->eta){
          $now = date('Y-m-d');
          $etaDate = date('Y-m-d',strtotime($orderInfo->eta));
          if ($etaDate > $now){
            $orderInfo->eta=convertToLocal12TimeOnly($orderInfo->eta);
          }else{
            $orderInfo->eta=convertToLocal12TimeOnly($orderInfo->eta,2);
          }
        }
        $companyInfo=getBusinessInfo($orderInfo->company_id);
        $orderInfo->business_name=$companyInfo->company_name;
        $orderInfo->business_address=$companyInfo->address;
        $latLngInfo=getLatLng($companyInfo->address);
        if($latLngInfo){
          list($lat,$lng)=explode(",",$latLngInfo);
          $orderInfo->business_lat=$lat;
          $orderInfo->business_lng=$lng;
        }
        $orderInfo->actual_order_time=convertToLocal12TimeOnly($orderInfo->actual_order_time);
      }
      return $orderInfo;
    }else{
      return [];
    }
  }
}

///*This method will current user info*/
if(!function_exists('currentUser')){
  function currentUser() {
    if(auth()->user()){
      return User::find(auth()->user()->id);
    }else{
      return false;
    }
  }
}

/*This method will return lang flag*/
if(!function_exists('getLangFlag')){
  function getLangFlag() {
    switch(session()->get('locale')){
      case 'fr':
        $flag='/img/flags/fr.png';
        break;
      case 'es':
        $flag='/img/flags/es.png';
        break;
      case 'aus':
        $flag='/img/flags/aus.png';
        break;
      case 'ca':
        $flag='/img/flags/canada.png';
        break;
      case 'nz':
        $flag='/img/flags/nz.png';
        break;
      default:
        $flag='/img/flags/us.png';
        break;
    }
    return $flag;
  }
}

/*This method will return lang name*/
if(!function_exists('getLangName')){
  function getLangName() {
    switch(session()->get('locale')){
      case 'fr':
        $lang='French';
        break;
      case 'es':
        $lang='Spanish';
        break;
      case 'aus':
        $lang='Aus';
        break;
      case 'ca':
        $lang='Canada';
        break;
      case 'nz':
        $lang='New Zealand';
        break;
      default:
        $lang='US';
        break;
    }
    return $lang;
  }
}

/*This method will return lang name*/
if(!function_exists('getLangHtml')){
  function getLangHtml() {
    switch(session()->get('locale')){
      case 'fr':
        $html='<li><a href="/language/es"><img src="/img/flags/es.png" alt=""> Spanish</a></li><li><a href="/language/en"><img src="/img/flags/us.png" alt=""> US</a></li><li><a href="/language/aus"><img src="/img/flags/aus.png" alt=""> Aus</a></li><li><a href="/language/ca"><img src="/img/flags/canada.png" alt=""> Canada</a></li><li><a href="/language/nz"><img src="/img/flags/nz.png" alt=""> New Zealand</a></li>';
        break;
      case 'es':
        $html='<li><a href="/language/fr"><img src="/img/flags/fr.png" alt=""> French</a></li><li><a href="/language/en"><img src="/img/flags/us.png" alt=""> US</a></li><li><a href="/language/aus"><img src="/img/flags/aus.png" alt=""> Aus</a></li><li><a href="/language/ca"><img src="/img/flags/canada.png" alt=""> Canada</a></li><li><a href="/language/nz"><img src="/img/flags/nz.png" alt=""> New Zealand</a></li>';
        break;
      case 'aus':
        $html='<li><a href="/language/fr"><img src="/img/flags/fr.png" alt=""> French</a></li><li><a href="/language/en"><img src="/img/flags/us.png" alt=""> US</a></li><li><a href="/language/es"><img src="/img/flags/es.png" alt=""> Spanish</a></li><li><a href="/language/ca"><img src="/img/flags/canada.png" alt=""> Canada</a></li><li><a href="/language/nz"><img src="/img/flags/nz.png" alt=""> New Zealand</a></li>';
        break;
      case 'ca':
        $html='<li><a href="/language/fr"><img src="/img/flags/fr.png" alt=""> French</a></li><li><a href="/language/en"><img src="/img/flags/us.png" alt=""> US</a></li><li><a href="/language/es"><img src="/img/flags/es.png" alt=""> Spanish</a></li><li><a href="/language/aus"><img src="/img/flags/aus.png" alt=""> Aus</a></li><li><a href="/language/nz"><img src="/img/flags/nz.png" alt=""> New Zealand</a></li>';
        break;
      case 'nz':
        $html='<li><a href="/language/fr"><img src="/img/flags/fr.png" alt=""> French</a></li><li><a href="/language/en"><img src="/img/flags/us.png" alt=""> US</a></li><li><a href="/language/es"><img src="/img/flags/es.png" alt=""> Spanish</a></li><li><a href="/language/aus"><img src="/img/flags/aus.png" alt=""> Aus</a></li><li><a href="/language/nz"><img src="/img/flags/nz.png" alt=""> New Zealand</a></li>';
        break;
      default:
        $html='<li><a href="/language/es"><img src="/img/flags/es.png" alt=""> Spanish</a></li><li><a href="/language/fr"><img src="/img/flags/fr.png" alt=""> French</a></li><li><a href="/language/aus"><img src="/img/flags/aus.png" alt=""> Aus</a></li><li><a href="/language/aus"><img src="/img/flags/canada.png" alt=""> Canada</a></li><li><a href="/language/nz"><img src="/img/flags/nz.png" alt=""> New Zealand</a></li>';
        break;
    }
    return $html;
  }
}
/*Send push to web browser*/
if(!function_exists('sendWebPushNotification')){
  function sendWebPushNotification($orderInfo,$data,$extraData=array()) {
    if($orderInfo){
      $companyId=$orderInfo->company_id;
      $userInfo=user::where(['company_id'=>$companyId,'role'=>null])->first();
      if($userInfo){
        $webToken=$userInfo->device_token;
        if($webToken){
          $serverKey=config('app.firebase_server_key');
          $url = "https://fcm.googleapis.com/fcm/send";
          $webData=$data;
          $webData['title']="Hey It's Ready Web";
          $extraData['order_id']=$orderInfo->id;
          $arrayToSend = array('to' => $webToken, 'notification' => $webData,'priority'=>'high','data'=>$extraData);
          $json = json_encode($arrayToSend,JSON_FORCE_OBJECT);
          $headers = array();
          $headers[] = 'Content-Type: application/json';
          $headers[] = 'Authorization: key='. $serverKey;
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
          curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
          curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
          //Send the request
          $response = curl_exec($ch);
          //Close request
          if ($response === FALSE) {
            echo "failed";die;
          }
          curl_close($ch);
        }
      }
    }


  }
}

/*This will check phone number is exist or not*/
if(!function_exists('checkUserExistByPhone')){
  function checkUserExistByPhone($phone) {
    if($phone){
      $userInfo=User::where('phone_number',$phone)->get();
      if($userInfo->count()>0){
        $userInfo=$userInfo->first();
        return ($userInfo)?$userInfo->id:false;
      }
    }
  }
}

/*This will check phone number is exist or not*/
if(!function_exists('checkUserExistByEmail')){
  function checkUserExistByEmail($email) {
    if($email){
      $userInfo=User::where('email',$email)->get();
      if($userInfo->count()>0){
        $userInfo=$userInfo->first();
        return ($userInfo)?$userInfo->id:false;
      }
    }
  }
}


//this will return all categories of company
if(!function_exists('all_categories')){
  function all_categories(){
    $categories=Category::where(['company_id'=>Auth::user()->company_id,'deleted'=>0])->get();
    return $categories;
  }
}

//this will return all categories of company
if(!function_exists('all_menus')){
  function all_menus(){
    $menus=RestaurantMenu::where(['company_id'=>Auth::user()->company_id,'deleted'=>0])->get();
    return $menus;
  }
}

/*Fetch timezone with IP*/
if(!function_exists('fetchTimezone')){
  function fetchTimezone() {
    $ipInfo=file_get_contents('http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']);
    if($ipInfo){
      $ipInfo=json_decode($ipInfo);
      return $ipInfo->timezone;
    }
  }
}

if(!function_exists('ipInfo')){
  function ipInfo() {
    $ipInfo=file_get_contents('http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']);
    return ($ipInfo)?json_decode($ipInfo):false;
  }
}

//get phone code
if(!function_exists('getPhoneCode')){
  function getPhoneCode() {
    $ipInfo=file_get_contents('http://ip-api.com/json/' . $_SERVER['REMOTE_ADDR']);
    if($ipInfo){
      $ipInfo=json_decode($ipInfo);
      if($ipInfo->countryCode){
        $countryInfo=Country::where('country_code',$ipInfo->countryCode);
        if($countryInfo->count()>0){
          return $phoneCode=$countryInfo->first()->phone_code;
        }
      }
    }
  }
}

//get all phone codes
if(!function_exists('allPhoneCode')){
  function allPhoneCode() {
    return array("+44", "+1", "+213", "+376", "+244", "+1264", "+1268", "+54", "+374", "+297", "+61", "+43", "+994", "+1242", "+973", "+880", "+1246", "+375", "+32", "+501",
    "+229", "+1441", "+975", "+591", "+387", "+267", "+55", "+673", "+359", "+226", "+257", "+855", "+237", "+238", "+1345", "+236", "+56", "+86", "+57", "+269",
    "+242", "+682", "+506", "+385", "+53", "+90392", "+357", "+42", "+45", "+253", "+1809", "+593", "+20", "+503", "+240", "+291", "+372", "+251", "+500", "+298",
    "+679", "+358", "+33", "+594", "+689", "+241", "+220", "+7880", "+49", "+233", "+350", "+30", "+299", "+1473", "+590", "+671", "+502", "+224", "+245", "+592",
    "+509", "+504", "+852", "+36", "+354", "+91", "+62", "+98", "+964", "+353", "+972", "+39", "+1876", "+81", "+962", "+7", "+254", "+686", "+850", "+82",
    "+965", "+996", "+856", "+371", "+961", "+266", "+231", "+218", "+417", "+370", "+352", "+853", "+389", "+261", "+265", "+60", "+960", "+223", "+356", "+692",
    "+596", "+222", "+52", "+691", "+373", "+377", "+976", "+1664", "+212", "+258", "+95", "+264", "+674", "+977", "+31", "+687", "+64", "+505", "+227", "+234",
    "+683", "+672", "+670", "+47", "+968", "+680", "+507", "+675", "+595", "+51", "+63", "+48", "+351", "+1787", "+974", "+262", "+40", "+250", "+378", "+239",
    "+966", "+221", "+381", "+248", "+232", "+65", "+421", "+386", "+677", "+252", "+27", "+34", "+94", "+290", "+1869", "+1758", "+249", "+597", "+268", "+46",
    "+41", "+963", "+886", "+66", "+228", "+676", "+1868", "+216", "+90", "+993", "+1649", "+688", "+256", "+380", "+971", "+598", "+678", "+379", "+58", "+84",
    "+681", "+969", "+967", "+260", "+263");
  }
}

if(!function_exists('userValidForDelete')){
  function userValidForDelete($date){
    if($date){
      $after3MonthDate=date('Y-m-d',strtotime("+3 months", strtotime($date)));
      if($date>$after3MonthDate){
        return true;
      }else{
        return false;
      }
    }
  }
}

/*This will check cart against item id*/
if(!function_exists('checkCartByItem')){
  function checkCartByItem($itemId){
    $cartCount=Cart::where(['item_id'=>$itemId,'user_id'=>auth()->user()->id,'payment_id'=>0])->count();
    $totalQuantity=0;
    if($cartCount>0){
      $cartDetail=Cart::where(['item_id'=>$itemId,'user_id'=>auth()->user()->id,'payment_id'=>0])->get();
      if($cartDetail){
        foreach ($cartDetail as $cart) {
          $totalQuantity+=$cart->quantity;
        }
      }
    }
    return $totalQuantity;
  }
}

/*This will check cart against item id*/
if(!function_exists('checkCartByItemPayment')){
  function checkCartByItemPayment($itemId,$paymentId){
    $cartCount=Cart::where(['item_id'=>$itemId,'user_id'=>auth()->user()->id,'payment_id'=>$paymentId])->count();
    $totalQuantity=0;
    if($cartCount>0){
      $cartDetail=Cart::where(['item_id'=>$itemId,'user_id'=>auth()->user()->id,'payment_id'=>$paymentId])->get();
      if($cartDetail){
        foreach ($cartDetail as $cart) {
          $totalQuantity+=$cart->quantity;
        }
      }
    }
    return $totalQuantity;
  }
}

if(!function_exists('checkCartByItemForAdmin')){
  function checkCartByItemForAdmin($itemId){
    $cartCount=Cart::where(['item_id'=>$itemId])->count();
    $totalQuantity=0;
    if($cartCount>0){
      $cartDetail=Cart::where(['item_id'=>$itemId])->get();
      if($cartDetail){
        foreach ($cartDetail as $cart) {
          $totalQuantity+=$cart->quantity;
        }
      }
    }
    return $totalQuantity;
  }
}

/*This will check cart quantity against item id*/
if(!function_exists('checkQuantityAllowed')){
  function checkQuantityAllowed($itemId){
    $cartCount=Menu::where(['id'=>$itemId])->count();
    if($cartCount>0){
      $cartDetail=Menu::where(['id'=>$itemId])->first();
      return $cartDetail->quantity;
    }else{
      return 0;
    }
  }
}

/*check other restaurant item allowed*/
if(!function_exists('cartAllowed')){
  function cartAllowed($companyId){
    $cartCount=Cart::where(['user_id'=>auth()->user()->id,'payment_id'=>0])->count();
    if($cartCount>0){
      $cartDetail=Cart::where(['user_id'=>auth()->user()->id,'payment_id'=>0])->first();
      if($cartDetail->company_id!=$companyId){
        return false;
      }
    }
    return true;
  }
}

/*get extras of cart for user*/
if(!function_exists('getCartExtras')){
  function getCartExtras($extraString){
    $extrasDataArr=array();
    $extrasArr=explode(",",$extraString);
    if(count($extrasArr)>0){
      foreach ($extrasArr as $extra) {
        $extraInfo=Extra::find($extra);
        $extrasDataArr[]=$extraInfo;
      }
    }
    return $extrasDataArr;
  }
}

if(!function_exists('getCartExtrasString')){
  function getCartExtrasString($extraString){
    $extrasDataArr=array();
    $extrasArr=explode(",",$extraString);
    if(count($extrasArr)>0){
      foreach ($extrasArr as $extra) {
        $extraInfo=Extra::find($extra);
        $extrasDataArr[]=$extraInfo->name;
      }
    }
    if(count($extrasDataArr)>1){
      return implode(",",$extrasDataArr);
    }else{
      return $extrasDataArr[0];
    }
  }
}

if(!function_exists('getCartExtrasIds')){
  function getCartExtrasIds($extraString){
    $extrasDataArr=array();
    $extrasArr=explode(",",$extraString);
    if(count($extrasArr)>0){
      foreach ($extrasArr as $extra) {
        $extraInfo=Extra::find($extra);
        $extrasDataArr[]=$extraInfo->id;
      }
    }
    if(count($extrasDataArr)>1){
      return implode(",",$extrasDataArr);
    }else{
      return $extrasDataArr[0];
    }
  }
}

if(!function_exists('getCardInfo')){
  function getCardInfo($customerId,$cardId){
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    return \Stripe\Customer::retrieveSource(
      $customerId,$cardId
    );
  }
}


if(!function_exists('checkIndexExist')){
  function checkIndexExist($extId,$userId,$index,$categoryId,$item_id){
      $cartCount = DB::table('carts')
      ->where(['user_id'=>$userId,'payment_id'=>0,'category_id'=>$categoryId,
      'item_id'=>$item_id])
      ->whereRaw("FIND_IN_SET($extId,extras)")
      ->whereRaw("FIND_IN_SET($index,extras_index)")->count();
    return ($cartCount>0)?true:false;
  }
}

if(!function_exists('getCheckedQuantity')){
  function getCheckedQuantity($extId,$userId,$index,$categoryId,$item_id){
    $cartCount= DB::table('carts')
    ->where(['user_id'=>$userId,'payment_id'=>0,'category_id'=>$categoryId,
    'item_id'=>$item_id])
    ->whereRaw("FIND_IN_SET($extId,extras)")
    ->whereRaw("FIND_IN_SET($index,extras_index)")->count();
    $extraMatchedQuantity=0;
    if($cartCount>0){
      $cartInfo= DB::table('carts')
      ->where(['user_id'=>$userId,'payment_id'=>0])
      ->whereRaw("FIND_IN_SET($extId,extras)")
      ->whereRaw("FIND_IN_SET($index,extras_index)")->first();
      if($cartInfo->extras){
        $extrasArr=explode(",",$cartInfo->extras);
        $extrasQuantityArr=explode(",",$cartInfo->extras_quantity);

        $extraMatchedIndex=array_search($extId,$extrasArr);
        return $extraMatchedQuantity=$extrasQuantityArr[$extraMatchedIndex];
      }
    }
    return $extraMatchedQuantity;
  }
}

if(!function_exists('getItemExtras')){
  function getItemExtras($extraString,$count,$categoryId,$item_id){
    $extrasDataArr=array();
    $extrasArr=explode(",",$extraString);
    $j=0;
    if(count($extrasArr)>0){
        $loopStart='start';
        foreach ($extrasArr as $extra) {
          $extraInfo=Extra::find($extra);
          $extraInfo->checked=checkIndexExist($extraInfo->id,auth()->user()->id,$j,$categoryId,$item_id);
          $extraInfo->quantity_added=getCheckedQuantity($extraInfo->id,auth()->user()->id,$j,$categoryId,$item_id);
          $totalQuantityLeft=checkExtraQuantityForAdmin($extraInfo->id);
          if($extraInfo->quantity_added>0){
            $extraInfo->quantity=$extraInfo->quantity-$totalQuantityLeft;
            if($extraInfo->quantity<0){
              $extraInfo->quantity=0;
            }
          }
          $extraInfo->index=$j;
          $extraInfo->loop=$loopStart;
          $extrasDataArr[]=$extraInfo;
          $loopStart='';
          $j++;
        }
    }
    return $extrasDataArr;
  }
}

if(!function_exists('checkExtraQuantityForAdmin')){
  function checkExtraQuantityForAdmin($id){
    $cartCount = DB::table('carts')->whereRaw("FIND_IN_SET($id,extras)")->count();
    $totalQuantity=0;
    if($cartCount>0){
      $cartDetail=DB::table('carts')->whereRaw("FIND_IN_SET($id,extras)")->get();
      if($cartDetail){
        foreach ($cartDetail as $cart) {
          $extrasArr=explode(",",$cart->extras);
          $extrasQuantityArr=explode(",",$cart->extras_quantity);

          $extraMatchedIndex=array_search($id,$extrasArr);
          $extraMatchedQuantity=$extrasQuantityArr[$extraMatchedIndex];
          $totalQuantity+=$extraMatchedQuantity;
        }
      }
    }
    return $totalQuantity;
  }
}

if(!function_exists('getCartInfoForItem')){
  function getCartInfoForItem($itemId){
    $cartCount=Cart::where(['item_id'=>$itemId,'user_id'=>auth()->user()->id,'payment_id'=>0])->count();
    if($cartCount>0){
      $cartDetail=Cart::where(['item_id'=>$itemId,'user_id'=>auth()->user()->id,'payment_id'=>0])->first();
      if($cartDetail){
        return $cartDetail;
      }
    }
    return false;
  }
}

/*get menu id for category id*/
if(!function_exists('getRestaurantMenuId')){
  function getRestaurantMenuId($categoryId){
    $categoryInfo=Category::find($categoryId);
    if($categoryInfo){
      return $categoryInfo->menu_id;
    }
    return false;
  }
}

/*check other menu item allowed in cart*/
if(!function_exists('MenuAllowedForCart')){
  function MenuAllowedForCart($restaurantMenuId){
    $cartCount=Cart::where(['user_id'=>auth()->user()->id,'payment_id'=>0])->count();
    if($cartCount>0){
      $cartDetail=Cart::where(['user_id'=>auth()->user()->id,'payment_id'=>0])->first();
      if($cartDetail->restaurant_menu_id!=$restaurantMenuId){
        return false;
      }
    }
    return true;
  }
}

/*check order is paid or not*/
if(!function_exists('checkPaidOrder')){
  function checkPaidOrder($oid){
    $paymentCount=Payment::where(['order_id'=>$oid])->count();
    if($paymentCount>0){
      return true;
    }
    return false;
  }
}

if(!function_exists('checkPrePaidOrder')){
  function checkPrePaidOrder($oid){
    $paymentQuery=Payment::where(['order_id'=>$oid]);
    if($paymentQuery->count()>0){
      $paymentInfo=$paymentQuery->first();
      if(strpos($paymentInfo->transaction_id, 'cash')===false){
        return true;
      }else{
        return false;
      }
    }
    return false;
  }
}

/*check order is locate or not*/
if(!function_exists('checkOrderLocate')){
  function checkOrderLocate($oid){
    $orderInfo=Order::find($oid)->locate;
    return ($orderInfo==1)?true:false;
  }
}

/*check order is in progress for user*/
if(!function_exists('OrderInProgress')){
  function OrderInProgress($uid){
    $paymentCount=Payment::where(['user_id'=>$uid])->count();
    if($paymentCount>0){
      $paymentInfo=Payment::where(['user_id'=>$uid])->latest()->first();
      if($paymentInfo->order_id){
        $orderInfo=Order::find($paymentInfo->order_id);
        if($orderInfo->status=='complete'){
          return true;
        }else{
          return false;
        }
      }else{
        return true;
      }
    }else{
      return true;
    }
  }
}

/*get extras string with quantity*/
if(!function_exists('getExtrasStringWithQuantity')){
  function getExtrasStringWithQuantity($extraString,$extrasQuantityString){
    $extrasDataArr=array();
    $quantityArr=array();
    $extrasArr=explode(",",$extraString);
    $quantityArr=explode(",",$extrasQuantityString);
    if(count($extrasArr)>0){
      foreach ($extrasArr as $key=>$extra) {
        $extraInfo=Extra::find($extra);
        $data['name']=$extraInfo->name;
        $data['quantity']=$quantityArr[$key];
        $totalPrice=$extraInfo->price*$quantityArr[$key];
        $data['amount']=number_format($totalPrice,2);
        $extrasDataArr[]=$data;
      }
    }
    return $extrasDataArr;
    /*if(count($extrasDataArr)>1){
      return implode(", ",$extrasDataArr);
    }else{
      return $extrasDataArr[0];
    }*/
  }
}

/*get tax of company*/
if(!function_exists('getCompanyTax')){
  function getCompanyTax($companyId){
    $payableTaxCount=Tax::where(['company_id'=>$companyId,'is_default'=>1])->count();
    if($payableTaxCount>0){
      return Tax::where(['company_id'=>$companyId,'is_default'=>1])->first();
    }
  }
}

//Time difference for cart automatic notification
if(!function_exists('cartTimeDifference')){
  function cartTimeDifference($cartTime,$currentTime){
		$date2 = strtotime($currentTime);
		$date1 = strtotime($cartTime);
		$diff = abs($date2 - $date1);
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 -  $months*30*60*60*24)/ (60*60*24));
		$hours = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24) / (60*60));
		$minutes = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24  - $hours*60*60)/ 60);
		$seconds = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
    $time['day']=0;
    $time['hour']=0;
    $time['minute']=0;
    //$time['second']='';
		if($months>0){
			$time['month']=$months;
		}
		if( $days>0){
			$time['day']=$days;
		}
		if($hours>0){
			$time['hour']=$hours;
		}
		if($minutes>0){
			$time['minute']=$minutes;
		}
		/*if($seconds>0){
			$time['second']=$seconds;
		}*/
    return $time;

  }
}

//This will return time set by user If exist
if(!function_exists('getOrderTimeSetByUser')){
  function getOrderTimeSetByUser($oid){
    $query=Payment::where('order_id',$oid);
    if($query->count()>0){
      if($query->first()->order_date){
        return date('d/m/Y h:i:s a',strtotime($query->first()->order_date));
      }else{
        return false;
      }


    }
  }
}

//update stripe connect id
if(! function_exists('updateStripeConnectId')){
  function updateStripeConnectId($id,$connectId){
    $userInfo=User::find($id);
    $userInfo->connect_account_id=$connectId;
    if($userInfo->save()){
      return true;
    }else{
      return false;
    }
  }
}

//update stripe access token
if(! function_exists('updateStripeAccessToken')){
  function updateStripeAccessToken($id,$token){
    $userInfo=User::find($id);
    $userInfo->stripe_access_token=$token;
    if($userInfo->save()){
      return true;
    }else{
      return false;
    }
  }
}

//get business user Info
if(! function_exists('getBusinessUserInfo')){
  function getBusinessUserInfo($id){
    return User::where('company_id',$id)->get()->first();
  }
}

if(! function_exists('paymentType')){
  function paymentType($id){
    $query=Payment::where('order_id',$id);
    if($query->count()>0){
      $paymentInfo=$query->latest()->first();
      if(strpos($paymentInfo->transaction_id,'cash') === false) {
        return 'Credit Card';
      }else{
        return 'Cash';
      }
    }else{
      return false;
    }
  }
}

/*getCountryCode*/
if ( ! function_exists( 'getCountryCode' ) ) {
	function getCountryCode($name) {
    $query=Country::where('countries_name',$name);
    if($query->count()>0){
      return $query->first()->country_code;
    }else{
      return false;
    }
  }
}

/*getCurrencyCode*/
if ( ! function_exists( 'getCurrencyCode' ) ) {
	function getCurrencyCode($code) {
    $currencies=get_country_currency();
    if(array_key_exists($code,$currencies)){
      return $currencies[$code];
    }else{
      return false;
    }
  }
}
/**
 * get_country_currency.
 *
 * 237 countries.
 * Two-letter country code (ISO 3166-1 alpha-2) => Three-letter currency code (ISO 4217).
 */
if ( ! function_exists( 'get_country_currency' ) ) {
	function get_country_currency() {
		return array(
			'AF' => 'AFN',
			'AL' => 'ALL',
			'DZ' => 'DZD',
			'AS' => 'USD',
			'AD' => 'EUR',
			'AO' => 'AOA',
			'AI' => 'XCD',
			'AQ' => 'XCD',
			'AG' => 'XCD',
			'AR' => 'ARS',
			'AM' => 'AMD',
			'AW' => 'AWG',
			'AU' => 'AUD',
			'AT' => 'EUR',
			'AZ' => 'AZN',
			'BS' => 'BSD',
			'BH' => 'BHD',
			'BD' => 'BDT',
			'BB' => 'BBD',
			'BY' => 'BYR',
			'BE' => 'EUR',
			'BZ' => 'BZD',
			'BJ' => 'XOF',
			'BM' => 'BMD',
			'BT' => 'BTN',
			'BO' => 'BOB',
			'BA' => 'BAM',
			'BW' => 'BWP',
			'BV' => 'NOK',
			'BR' => 'BRL',
			'IO' => 'USD',
			'BN' => 'BND',
			'BG' => 'BGN',
			'BF' => 'XOF',
			'BI' => 'BIF',
			'KH' => 'KHR',
			'CM' => 'XAF',
			'CA' => 'CAD',
			'CV' => 'CVE',
			'KY' => 'KYD',
			'CF' => 'XAF',
			'TD' => 'XAF',
			'CL' => 'CLP',
			'CN' => 'CNY',
			'HK' => 'HKD',
			'CX' => 'AUD',
			'CC' => 'AUD',
			'CO' => 'COP',
			'KM' => 'KMF',
			'CG' => 'XAF',
			'CD' => 'CDF',
			'CK' => 'NZD',
			'CR' => 'CRC',
			'HR' => 'HRK',
			'CU' => 'CUP',
			'CY' => 'EUR',
			'CZ' => 'CZK',
			'DK' => 'DKK',
			'DJ' => 'DJF',
			'DM' => 'XCD',
			'DO' => 'DOP',
			'EC' => 'ECS',
			'EG' => 'EGP',
			'SV' => 'SVC',
			'GQ' => 'XAF',
			'ER' => 'ERN',
			'EE' => 'EUR',
			'ET' => 'ETB',
			'FK' => 'FKP',
			'FO' => 'DKK',
			'FJ' => 'FJD',
			'FI' => 'EUR',
			'FR' => 'EUR',
			'GF' => 'EUR',
			'TF' => 'EUR',
			'GA' => 'XAF',
			'GM' => 'GMD',
			'GE' => 'GEL',
			'DE' => 'EUR',
			'GH' => 'GHS',
			'GI' => 'GIP',
			'GR' => 'EUR',
			'GL' => 'DKK',
			'GD' => 'XCD',
			'GP' => 'EUR',
			'GU' => 'USD',
			'GT' => 'QTQ',
			'GG' => 'GGP',
			'GN' => 'GNF',
			'GW' => 'GWP',
			'GY' => 'GYD',
			'HT' => 'HTG',
			'HM' => 'AUD',
			'HN' => 'HNL',
			'HU' => 'HUF',
			'IS' => 'ISK',
			'IN' => 'INR',
			'ID' => 'IDR',
			'IR' => 'IRR',
			'IQ' => 'IQD',
			'IE' => 'EUR',
			'IM' => 'GBP',
			'IL' => 'ILS',
			'IT' => 'EUR',
			'JM' => 'JMD',
			'JP' => 'JPY',
			'JE' => 'GBP',
			'JO' => 'JOD',
			'KZ' => 'KZT',
			'KE' => 'KES',
			'KI' => 'AUD',
			'KP' => 'KPW',
			'KR' => 'KRW',
			'KW' => 'KWD',
			'KG' => 'KGS',
			'LA' => 'LAK',
			'LV' => 'EUR',
			'LB' => 'LBP',
			'LS' => 'LSL',
			'LR' => 'LRD',
			'LY' => 'LYD',
			'LI' => 'CHF',
			'LT' => 'EUR',
			'LU' => 'EUR',
			'MK' => 'MKD',
			'MG' => 'MGF',
			'MW' => 'MWK',
			'MY' => 'MYR',
			'MV' => 'MVR',
			'ML' => 'XOF',
			'MT' => 'EUR',
			'MH' => 'USD',
			'MQ' => 'EUR',
			'MR' => 'MRO',
			'MU' => 'MUR',
			'YT' => 'EUR',
			'MX' => 'MXN',
			'FM' => 'USD',
			'MD' => 'MDL',
			'MC' => 'EUR',
			'MN' => 'MNT',
			'ME' => 'EUR',
			'MS' => 'XCD',
			'MA' => 'MAD',
			'MZ' => 'MZN',
			'MM' => 'MMK',
			'NA' => 'NAD',
			'NR' => 'AUD',
			'NP' => 'NPR',
			'NL' => 'EUR',
			'AN' => 'ANG',
			'NC' => 'XPF',
			'NZ' => 'NZD',
			'NI' => 'NIO',
			'NE' => 'XOF',
			'NG' => 'NGN',
			'NU' => 'NZD',
			'NF' => 'AUD',
			'MP' => 'USD',
			'NO' => 'NOK',
			'OM' => 'OMR',
			'PK' => 'PKR',
			'PW' => 'USD',
			'PA' => 'PAB',
			'PG' => 'PGK',
			'PY' => 'PYG',
			'PE' => 'PEN',
			'PH' => 'PHP',
			'PN' => 'NZD',
			'PL' => 'PLN',
			'PT' => 'EUR',
			'PR' => 'USD',
			'QA' => 'QAR',
			'RE' => 'EUR',
			'RO' => 'RON',
			'RU' => 'RUB',
			'RW' => 'RWF',
			'SH' => 'SHP',
			'KN' => 'XCD',
			'LC' => 'XCD',
			'PM' => 'EUR',
			'VC' => 'XCD',
			'WS' => 'WST',
			'SM' => 'EUR',
			'ST' => 'STD',
			'SA' => 'SAR',
			'SN' => 'XOF',
			'RS' => 'RSD',
			'SC' => 'SCR',
			'SL' => 'SLL',
			'SG' => 'SGD',
			'SK' => 'EUR',
			'SI' => 'EUR',
			'SB' => 'SBD',
			'SO' => 'SOS',
			'ZA' => 'ZAR',
			'GS' => 'GBP',
			'SS' => 'SSP',
			'ES' => 'EUR',
			'LK' => 'LKR',
			'SD' => 'SDG',
			'SR' => 'SRD',
			'SJ' => 'NOK',
			'SZ' => 'SZL',
			'SE' => 'SEK',
			'CH' => 'CHF',
			'SY' => 'SYP',
			'TW' => 'TWD',
			'TJ' => 'TJS',
			'TZ' => 'TZS',
			'TH' => 'THB',
			'TG' => 'XOF',
			'TK' => 'NZD',
			'TO' => 'TOP',
			'TT' => 'TTD',
			'TN' => 'TND',
			'TR' => 'TRY',
			'TM' => 'TMT',
			'TC' => 'USD',
			'TV' => 'AUD',
			'UG' => 'UGX',
			'UA' => 'UAH',
			'AE' => 'AED',
			'GB' => 'GBP',
			'US' => 'USD',
			'UM' => 'USD',
			'UY' => 'UYU',
			'UZ' => 'UZS',
			'VU' => 'VUV',
			'VE' => 'VEF',
			'VN' => 'VND',
			'VI' => 'USD',
			'WF' => 'XPF',
			'EH' => 'MAD',
			'YE' => 'YER',
			'ZM' => 'ZMW',
			'ZW' => 'ZWD',
		);
	}
}

/*This will return geofence customers inside geofence*/
if(!function_exists('getGeofenceCustomers')){
  function getGeofenceCustomers(){
    return Order::where([
      ['status','<>','complete'],
      'company_id'=>auth()->user()->company_id,
      'cancel'=>0,'locate'=>1])->count();
  }
}

if(!function_exists('categories_options')){
  function categories_options(){
    return array(
      'Appliances',
      'Artist',
      'Automotive',
      'Baby & Children’s',
      'Beauty, Cosmetic & Personal Care',
      'Book Store',
      'Camera/Photo',
      'Clothing',
      'Commercial & Industrial',
      'Convenience Store',
      'Coffee Shop',
      'Farm',
      'Food & Beverage',
      'Foot ware',
      'Food Stand',
      'Games & Toys',
      'Hardware',
      'Health & Wellness',
      'Home Improvement',
      'Heating, Ventilation & Air Conditioning',
      'Jewelry & Watches',
      'Kitchen & Bath',
      'Lumber Yard',
      'Library',
      'Market',
      'Medical',
      'Office Supplies',
      'Other',
      'Outdoor Sports',
      'Pet Services',
      'Plumbing',
      'Rentals',
      'Restaurant',
      'Re-seller',
      'Shopping & Retail',
      'Sports & Recreation',
      'Tire & Repair',
      'Video & Games',
      'Winery/Vineyard'
    );
  }
}
?>
