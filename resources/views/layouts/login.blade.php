<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="{{ URL::to('/img/favicon.ico') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap core CSS -->
    <link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/bootstrap-reset.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/bootstrap-reset.css')}}" rel="stylesheet">
    <!--external css-->
    <link href="{{URL::asset('assets/font-awesome/css/all.min.css')}}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{URL::asset('css/style.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/style-responsive.css')}}" rel="stylesheet" />
</head>
<body class="login-body">
  @include('partials.flash')
    <section class="container">
      <section class="wrapper">
        @yield('content')
      </section>
    </section>
    <div class="modal fade" id="openTermsModalPop" tabindex="-1" role="dialog" aria-labelledby="orderDetailLabel"
  	aria-hidden="true">
  	    <div class="modal-dialog modal-lg" role="document">
  	        <div class="modal-content">
  	            <div class="modal-header">
  	                <h5 class="modal-title">Terms & Services</h5>
  	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  	                    <span aria-hidden="true">&times;</span>
  	                </button>
  	            </div>
  	            <div class="modal-body">
  								<div class="row termsServices">
  									 <div class="col-md-12 col-12">
  									 		<h3 class="text-center">Terms of Service</h3>
  								      <h3 class="text-center">Hey It’s Ready Customer App</h3>
  								      <p class="text-center last-date">Last update: 9/10/2020</p>

  								      <p class="font-p">These terms and conditions of use constitute a legal agreement between you, the “User” and Quick Report Systems Inc. (“QRSI”) for all Hey It’s Ready Apps, websites, features or other services. By indicating your acceptance below you acknowledge that you have read and understood this Agreement in its entirety, and you expressly agree to be bound by the terms and conditions of use as set out herein.</p>

  								      <ol>
  								        <li class="font-ol-li mt-10">Definitions
  								          <p class="font-ol-li-p">In this Agreement:</p>
  								          <ul class="subul">
  								            <li class="mt-10"><b class="font-800">“Agreement”</b> means this agreement and the terms and conditions of use set out herein, also referred to as the <b class="font-800">“Terms of Service”</b>.</li>

  								            <li class="mt-10"><b class="font-800">“QRSI”</b> means Quick Report Systems Inc., a corporation duly incorporated and validly existing in accordance with the laws of the Province of Ontario.</li>

  								            <li class="mt-10"><b class="font-800">“QRSI Products”</b> means all QRSI websites (including, without limitation, the (Hey It’s Ready Apps, websites, features or other services), QRSI services, QRSI software (whether accessed from an QRSI server, pre-installed on a medium or offered by download), and all other software, features, tools, websites and services provided by or through QRSI and QRSI's Third- Party Service Providers not covered expressly by a different agreement. This also includes all accompanying data files and documentation, any updates to the QRSI Products that may be provided from time to time by QRSI, and all copies of any of the foregoing.</li>

  								            <li class="mt-10"><b class="font-800">“Recommended Devices”</b> means the list of handheld devices attached as Schedule “A” to this Agreement which are recommended by QRSI for use with Hey It’s Ready App due to their GPS accuracy and capabilities.</li>

  								            <li class="mt-10"><b class="font-800">“Registration Information”</b> means any information that the user may be required to provide to QRSI to be able to register and use QRSI Products. Such information may include your name, e-mail address, address, province, postal code, etc.</li>

  								            <li class="mt-10"><b class="font-800">“Third Party Service Providers”</b> means any non-QRSI branded products, software or services.</li>

  								            <li class="mt-10"><b class="font-800">“User ID”</b> means a valid form of identification to use QRSI Products, such as a valid sign-in name or such other forms of user identification authorized by QRSI.</li>
  								          </ul>
  								        </li>
  								        <li class="font-ol-li mt-10">
  								          Quick Report Systems Inc. Terms of Service
  								          <p class="font-ol-li-p">You must agree to these Terms of Service to use QRSI Products, including Hey It’s Ready App and Hey It’s Ready Business Application. By using or registering for a QRSI Product you agree to these Terms of Service as set out herein. You cannot use or sign up for QRSI Products until you have accepted these Terms of Service. Each time you use a QRSI Product, you reaffirm your acceptance of the then-current Terms of Service. If you do not wish to be bound by these Terms of Service, you may discontinue using the QRSI Products. You agree to accept notices electronically.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          Requirements for Use or Registration of QRSI Products
  								          <p class="font-ol-li-p">Some QRSI Products may require you to provide Registration Information. If you register for any QRSI Product, you agree to provide accurate and complete Registration Information and you agree to keep such information current. You will be provided with login details and a User ID which is required to access and use QRSI Products.
  								          You may not: (1) use anyone else’s User ID without authorization; (2) select or use a User ID of another person with the intention of impersonating that person; (3) use a User ID in violation of the intellectual property rights of any person; or (4) use a User ID that QRSI considers in its sole discretion to be inappropriate. You acknowledge that QRSI Products are intended for general audiences. You represent and warrant that you have adequate legal capacity to enter into binding agreements such as these Terms of Service and to utilize the QRSI Products on the devices from which you have caused QRSI Products to be accessed and/or installed.
  								          </p>
  								        </li>
  								        <li class="font-ol-li">
  								          Access to QRSI Products
  								          <p class="font-ol-li-p">You are responsible for obtaining your own Internet access, such as maintaining all telephone, computer hardware, software, wi-fi networks, and other equipment needed for access to and use of the QRSI Products, and all charges related thereto.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          Recommended Devices Hand Held Devices
  								          <p class="font-ol-li-p">QRSI has carried out extensive testing on many types of devices in order to bring you the most reliable and user-friendly experience possible. Schedule “A” to this Agreement is a list of the handheld devices recommended by QRSI for use with Hey It’s Ready App due to their GPS accuracy and capabilities (the “Recommended Devices”). While other devices may be able to provide you with a functional and acceptable experience, the reliability and functionality of other devices may not currently meet QRSI’s performance expectations and QRSI is not currently able to offer support for devices that are not listed as a recommended device on Schedule “A” to this agreement.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          Recommended Web Browser Versions
  								          <p class="font-ol-li-p">QRSI has carried out extensive testing on many Web Browsers in order to bring you the most reliable and user-friendly experience possible. Schedule “B” to this Agreement is a list of the Web Browsers recommended by QRSI for use with Hey It’s Ready Business Application due to web standards compliance. While other Web browsers may be able to provide you with functionality and acceptable experience, the reliability and functionality of other browsers may not currently meet QRSI’s performance expectations and QRSI is not currently able to offer support for Web browsers that are not listed as a recommended on Schedule “B” to this agreement.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          QRSI Subscription
  								          <p class="font-ol-li-p">Once you accept this Agreement, QRSI grants you a personal, non-exclusive, non-transferable subscription to access the QRSI Products made available to you by QRSI and to use the QRSI Products solely in accordance with these Terms of Service. You may not sub-license or charge others to use or access QRSI Products without first obtaining written permission from QRSI. QRSI occasionally will provide automatic upgrades to improve your online experience, although these upgrades may not be consistent across all platform and devices. You agree to accept and to take no action to interfere with such automatic upgrades, scanning, and related services. You may not sell, assign, grant a security interest in or otherwise transfer any right in the QRSI Products or incorporate it (or any portion of it) into another product.
  								          You may not copy (or otherwise duplicate), translate, reverse-engineer or reverse-compile or decompile, disassemble, make derivative works from, or otherwise attempt to discover any source code in the QRSI Products. You may not modify the QRSI Products or use them in any way not expressly authorized by these Terms of Service. You may not obtain the communications protocol for accessing the QRSI Products. Finally, you may not authorize, permit or assist any third party to do any of the things described in this paragraph. You understand that QRSI's introduction of various technologies may not be consistent across all platforms and that the performance and features offered by QRSI may vary depending on your computer and other equipment.
  								          </p>
  								        </li>
  								        <li class="font-ol-li">
  								          Registered User Information & User Data
  								          <p class="font-ol-li-p">You consent to QRSI using your Registration Information, user data and other personal information. By registering with or using QRSI Products, you consent to the collection, storage and use of this information and the transfer of this information within or outside of Canada for processing and storage by QRSI. Additionally, you agree that QRSI may use whatever means it deems necessary to authenticate you as a valid user of QRSI Products, help store your user data, registration information and transaction-related information, and enable you to take advantage of offerings from QRSI and its Third Party Service Providers.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          User Responsibility
  								          <p class="font-ol-li-p">You agree to keep confidential the passwords associated with your account. You are responsible for all activity undertaken by you, or anyone you intentionally or unintentionally permit to use your User ID or QRSI Products, including your family or friends. You agree to indemnify and hold harmless QRSI for losses incurred by QRSI or another party due to another  party’s  use  of  your account or password as a result of your failure to use reasonable care to keep your account information confidential or as a result of your failure to use reasonable care while using QRSI Products. You are responsible for any materials you post or make available on or through the QRSI Products. You are solely responsible for any damage caused by unauthorized destruction, loss, interception, or alteration of the data stored by QRSI by unauthorized persons.
  								          QRSI shall not use the data it collects except to: (a) provide the QRSI Products; (b) monitor your use of the QRSI Products for security purposes; (c) perform statistical analysis and business measures of the performance of the QRSI Products; and (d) enforce the terms of this Agreement. QRSI shall not disclose the data stored at an QRSI server to a third party, except to: (a) QRSI employees or subcontractors who need to know such information in order to provide the QRSI Products, provided that they are bound by similar confidentiality obligations.
  								          </p>
  								        </li>
  								        <li class="font-ol-li">
  								          No Duty to Monitor
  								          <p class="font-ol-li-p">QRSI is not required to pre-screen data or information available on the QRSI Products, including the content of e-mail or other messaging that occurs on or through the QRSI service, although QRSI reserves the right to do so in its sole discretion. QRSI is not liable for data or information that is provided by others. QRSI reserves the right to remove any data or information, or discontinue any account that in its sole judgment does not meet its standards or does not comply with these Terms of Service, but QRSI is not responsible for any failure or delay in removing such material.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          QRSI Support
  								          <p class="font-ol-li-p">You understand that your use of the QRSI Products is at your own risk and that QRSI is not obligated to provide assistance other than the information posted on the QRSI and Hey It’s Ready websites. QRSI is under no obligation to provide you with any error resolution.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          Amendments or Modifications to the Terms of Service
  								          <p class="font-ol-li-p">QRSI may modify or change the Terms of Service at any time and in its sole discretion. The modified Terms of Service will be effective immediately upon posting and you agree to the new posted Terms of Service by continuing your use of the QRSI Products. QRSI will provide not less than thirty (30) days' notice before any material changes take effect. If you do not agree with the modified Terms of Service, your only remedy is to discontinue using the QRSI Products and cancel your registration, subscription, or services.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          Changes to QRSI Products
  								          <p class="font-ol-li-p">QRSI has the right at any time to change, modify, add to or discontinue or retire any aspect or feature of the QRSI Products including, but not limited to, the website, software, Mobile App hours of availability, equipment needed for access or use, the maximum disk space that will be allotted on QRSI servers on your behalf either cumulatively or for any particular service or the availability of QRSI Products on any particular device or communications service. QRSI has no obligation to provide you with notice of any such changes.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          Restrictions on Access to or Use of QRSI Products
  								          <p class="font-ol-li-p">You may access QRSI Products only through the interfaces and protocols provided or authorized by QRSI. You agree that you will not access QRSI Products through unauthorized means, such as unlicensed software clients, and that you will only use QRSI Products in conjunction with QRSI authorized products and components.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          Lawful Purposes Only
  								          <p class="font-ol-li-p">You may use QRSI Products for lawful purposes only. You are expressly not permitted to post on or transmit through community areas (e.g., message boards, chat, e-mail, calendars, instant messaging products) or other means any material that: (1) violates or infringes in any way upon the rights of others; (2) is unlawful, threatening, abusive, defamatory, invasive of privacy or publicity rights, vulgar, obscene, profane, indecent or otherwise objectionable ; (3) encourages conduct that would constitute a criminal offense; (4) gives rise to civil liability; (5) violates any policies posted in any community areas; or (6) otherwise violates any law. You also may not undertake any conduct that, in QRSI's sole judgment, restricts or inhibits any other user from using or enjoying the QRSI Products.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          Indemnification
  								          <p class="font-ol-li-p">You agree to defend, indemnify and hold harmless QRSI, its vendors, and their respective directors, officers, employees and agents from and against all claims and expenses, including legal fees, arising out of your use of the QRSI Products. QRSI reserves the right, at its own expense and in its sole discretion, to assume the exclusive defense and control of any matter otherwise subject to indemnification by you. In that event, and only in such event, shall you have no further obligation to provide indemnification for QRSI in that specific matter only.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          Termination
  								          <p class="font-ol-li-p">QRSI has the right to terminate your User ID, your registration, your subscription or your access to QRSI Products for any reason, including, without limitation, if it considers your use to be unacceptable in its sole discretion, or in the event of any breach by you of the Terms of Service (either directly or through breach of any other terms and conditions o r operating rules applicable to you). QRSI may, but shall be under no obligation to, provide you with a warning prior to terminating your use of the QRSI Products.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          Trademarks & Intellectual Property
  								          <p class="font-ol-li-p">All trademarks and intellectual property appearing on the QRSI Products are the property of their respective owners.</p>
  								        </li>
  								        <li class="font-ol-li">
  								          Miscellaneous
  								          <ul class="subul">
  								            <li class="mt-10"><u>Notices.</u> QRSI, as an online business, transacts with its users electronically. When you sign up for any QRSI product, you consent to receive electronically from QRSI any privacy or other notices, agreements, disclosures, reports, documents, communications, or other records (collectively, "Notices"). You agree that QRSI generally can send you electronic Notices in either or both of the following ways: (1) to the e-mail address that you provided to QRSI during registration; or (2) QRSI may post Notices on a welcoming screen or top page of the relevant QRSI Product.You must check your designated e-mail address regularly for Notices. You must have a personal computer with a modem connected to a communications source (telephone, wireless or broadband), a Windows-based operating system with an Internet browser or a Macintosh-based operating system with an Internet browser and Internet e-mail software in order to access electronic communications.
  								            You will need a printer attached to your personal computer to print any Notices. You can retrieve an electronic copy and a printable version of the Terms of Service by clicking on the "Terms of Service" link on the home page of QRSI. The delivery of any Notice from QRSI is effective when sent by QRSI, regardless of whether you read the Notice when you receive it or whether you actually receive the delivery. Your only method of withdrawing consent to receive Notices electronically is to terminate any subscriptions, services or other products provided under these Terms of Service
  								            </li>
  								            <li class="mt-10">
  								              <u>Survival.</u> Provisions of these Terms of Service addressing disclaimers of representations and warranties, limitation of liability, indemnity obligations, intellectual property and governing law shall survive the termination of these Terms of Service and your registration with the QRSI Products.
  								            </li>
  								            <li class="mt-10">
  								              <u>Entire Agreement.</u> These Terms of Service and any operating rules for any areas of functionality of the QRSI Products established by QRSI constitute the entire agreement between QRSI and you regarding the subject matter of these Terms of Service, and supersede all previous written or oral agreements.
  								            </li>
  								            <li class="mt-10">
  								              <u>Conflicts.</u> In the event of any inconsistency between these Terms of Service and any such other terms of use or operating rules of a specific QRSI Product, these Terms of Service will supersede such other terms of service or operating rules.
  								            </li>
  								            <li class="mt-10">
  								              <u>Jurisdiction.</u> The Terms of Service shall be governed by and construed in accordance with the laws of the Province of Ontario, without regard to conflict of laws rules. You expressly agree that the exclusive, personal and subject matter jurisdiction for any claim or dispute under the Terms of Service and or your use of the QRSI Products resides in the jurisdiction of the Province of Ontario, Canada, and you further expressly agree to submit to the personal jurisdiction of such courts for the purpose of litigating any such claim or action. This provision may not apply to you depending on the laws of your jurisdiction.
  								            </li>
  								            <li class="mt-10">
  								              <u>Waiver.</u> No Waiver by either party of any breach or default hereunder shall be deemed to be a waiver of any preceding or subsequent breach or default.
  								            </li>
  								            <li class="mt-10">
  								              <u>Headings.</u> The section headings used herein are for convenience only and shall not be given any legal import.
  								            </li>
  								            <li class="mt-10">
  								              <u>Authority.</u> By accepting this Agreement you represent and warrant that you have adequate legal capacity to enter into this Agreement and that you have all necessary power and authority to execute and deliver this Agreement and to perform your obligations hereunder. If you are entering into this Agreement on behalf of a corporation, company or other legal entity, you represent and warrant that you have the authority to bind such entity to these terms and conditions, in which case the terms "you" or "your" shall refer to such entity.
  								            </li>
  								            <li class="mt-10">
  								              <u>Severability.</u> If any provision of this Agreement or the application of such provision to any person or circumstances shall be held illegal, invalid or unenforceable, the remainder of this Agreement, or the application of such provision to persons or circumstances other than those as to which it is held illegal, invalid or unenforceable shall not be affected thereby. Each provision of this Agreement is intended to be severable, and if any provision is illegal, invalid or unenforceable in any jurisdiction, this will not affect the legality, validity or enforceability of such provision in any other jurisdiction or the validity of the remainder of this Agreement.
  								            </li>
  								            <li class="mt-10">
  								              <u>Force Majeure.</u> Any delays in, or failure by, either party hereto in the performance hereunder shall be excused to the extent that the same is caused by force majeure including, but not limited to, strikes or other labour disturbances, war, sabotage, and any other cause or causes, whether similar or dissimilar, to those herein specified, which cannot be controlled by such party.
  								            </li>
  								            <li class="mt-10">
  								              <u>No Agency.</u> Nothing in this Agreement shall constitute or be construed to be or to create a partnership or principal and agent relationship or any other relationship of a similar nature between the parties.
  								            </li>
  								            <li class="mt-10">
  								              <u>Currency.</u> All amounts referred to herein or in other documents pertaining to this Agreement shall be in Canadian Dollars (CAD) unless otherwise specified.
  								            </li>
  								          </ul>
  								        </li>
  								      </ol>
  								      <ol class="upper-alpha" Type ="A" start="2">
  								        <li class="font-ol-li mt-10">
  								          Disclaimer of Warranty & Limitation of Liability
  								          <p class="font-ol-li-p">
  								            <u>DISCLAIMER OF WARRANTIES.</u> YOUR USE OF THE QRSI PRODUCTS IS AT YOUR SOLE RISK. THE QRSI PRODUCTS ARE PROVIDED "AS IS," "WITH ALL FAULTS" AND "AS AVAILABLE" FOR YOUR USE, WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, UNLESS SUCH WARRANTIES ARE LEGALLY INCAPABLE OF EXCLUSION. SPECIFICALLY, QRSI DISCLAIMS IMPLIED WARRANTIES THAT THE QRSI PRODUCTS ARE MERCHANTABLE, OF SATISFACTORY QUALITY, ACCURATE, FIT FOR A PARTICULAR PURPOSE OR NEED, OR ARE NON-INFRINGING. QRSI DOES NOT WARRANT THAT THE FUNCTIONS CONTAINED IN THE QRSI PRODUCTS WILL MEET YOUR REQUIREMENTS OR THAT THE OPERATION OF THE QRSI PRODUCTS WILL BE UNINTERRUPTED OR ERROR-FREE, OR THAT DEFECTS IN THE QRSI PRODUCTS WILL BE CORRECTED. QRSI DOES NOT WARRANT OR MAKE ANY REPRESENTATIONS REGARDING THE USE OR THE RESULTS OF THE USE OF THE QRSI PRODUCTS OR RELATED DOCUMENTATION IN TERMS OF THEIR CORRECTNESS, ACCURACY, RELIABILITY OR OTHERWISE. QRSI PROVIDES THE QRSI PRODUCTS ON A COMMERCIALLY REASONABLE BASIS AND DOES NOT GUARANTEE THAT USERS WILL BE ABLE TO ACCESS OR USE THE QRSI PRODUCTS AT TIMES OR LOCATIONS OF THEIR CHOOSING, OR THAT QRSI WILL HAVE ADEQUATE CAPACITY FOR THE QRSI PRODUCTS AS A WHOLE.
  								            <u>LIMITATION OF LIABILITY.</u> QRSI'S ENTIRE LIABILITY AND YOUR EXCLUSIVE REMEDY WITH RESPECT TO ANY DISPUTE WITH QRSI (INCLUDING WITHOUT LIMITATION YOUR USE OF THE QRSI PRODUCTS) IS TO DISCONTINUE YOUR USE OF THE QRSI PRODUCTS. QRSI SHALL NOT BE LIABLE FOR ANY INDIRECT, SPECIAL, INCIDENTAL, CONSEQUENTIAL OR EXEMPLARY DAMAGE ARISING FROM YOUR USE OF THE QRSI PRODUCTS OR FOR ANY OTHER DAMAGES RELATED IN ANY WAY TO YOUR USE OR REGISTRATION WITH QRSI. THESE EXCLUSIONS FOR INDIRECT, SPECIAL, INCIDENTAL, CONSEQUENTIAL OR EXEMPLARY DAMAGES INCLUDE, WITHOUT LIMITATION, DAMAGES FOR LOST PROFITS, LOST DATA, DATA ACCURACY, LOSS OF GOODWILL, WORK STOPPAGE, COMPUTER FAILURE OR MALFUNCTION, OR ANY OTHER COMMERCIAL DAMAGES OR LOSSES, EVEN IF QRSI HAD BEEN ADVISED OF THE POSSIBILITY THEREOF AND REGARDLESS OF THE LEGAL OR EQUITABLE THEORY UPON WHICH THE CLAIM IS BASED. BECAUSE SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OR THE LIMITATION OF LIABILITY FOR CONSEQUENTIAL OR INCIDENTAL DAMAGES, IN SUCH JURISDICTIONS, QRSI’S LIABILITY IN SUCH  JURISDICTION SHALL BE LIMITED TO THE EXTENT PERMITTED BY LAW. QRSI DOES NOT ENDORSE, WARRANT OR GUARANTEE ANY PRODUCT OR SERVICE OFFERED THROUGH QRSI AND WILL NOT BE A PARTY TO OR IN ANY WAY BE RESPONSIBLE FOR MONITORING ANY TRANSACTION BETWEEN YOU AND THIRD-PARTY PROVIDERS OF PRODUCTS OR SERVICES.
  								          </p>
  								        </li>
  								      </ol>
  								      <h3 class="text-center font-weight-900">SCHEDULE "A"</h3>
  								      <h4><u>RECOMMEND DEVICES for the QRS Reporting App</u></h4>
  								      <p class="font-p mt-30">QRSI has carried out extensive testing on many types of devices using the Hey It’s Ready App for iOS and Android in order to bring you the most reliable and user-friendly experience possible. The following is a list of mobile operating systems recommended by QRSI for use with Hey It’s Ready App. While other devices may be able to provide you with a functional and acceptable experience, the reliability and functionality of other devices may not currently meet QRSI’s performance expectations and QRSI is not currently able to offer support for devices that are not listed below.
  								      Support is subject to the changing requirements and adding future functionality to the Hey It’s Ready App and the compatibility with outdated operating systems. Support for older devices and operating system may be dropped to facilitate improved functionality or performance, for more modern devices or operating system versions, when the older device or versions have reached a point where support and testing can no longer be justified at the discretion of QRSI.</p>
  								      <p class="font-p mt-10"><u>iOS</u></p>
  								      <p class="font-p mb-0">iOS Operating Systems, iOS 10 and up</p>
  								      <p class="font-p mb-0">iOS Devices</p>
  									 </div>
  								</div>
  								<div class="row mt-10 termsServices">
  								  <div class="col-md-6">
  								    <ul>
  								      <li class="font-phones"><b>iPhone</b></li>
  								      <li class="font-phones">iPhone 5s</li>
  								      <li class="font-phones">iPhone 6</li>
  								      <li class="font-phones">iPhone Plus</li>
  								      <li class="font-phones">iPhone 6s</li>
  								      <li class="font-phones">iPhone 6s Plus</li>
  								      <li class="font-phones">iPhone SE</li>
  								      <li class="font-phones">iPhone 7</li>
  								      <li class="font-phones">iPhone 7 Plus</li>
  								      <li class="font-phones">iPhone 8</li>
  								      <li class="font-phones">iPhone 8 Plus</li>
  								      <li class="font-phones">iPhone X</li>
  								      <li class="font-phones">iPhone XS</li>
  								      <li class="font-phones">iPhone XS Max</li>
  								      <li class="font-phones">iPhone XR</li>
  								      <li class="font-phones">iPhone 11</li>
  								      <li class="font-phones">iPhone 11 Pro</li>
  								      <li class="font-phones">iPhone 11 Pro Max</li>
  								    </ul>
  								  </div>
  								  <div class="col-md-6">
  								    <ul>
  								      <li class="font-phones"><b>iPad</b></li>
  								      <li class="font-phones">iPad 4</li>
  								      <li class="font-phones">iPad 5</li>
  								      <li class="font-phones">iPad Air</li>
  								      <li class="font-phones">iPad Air 2</li>
  								      <li class="font-phones">iPad mini</li>
  								      <li class="font-phones">iPad mini 2</li>
  								      <li class="font-phones">iPad mini 3</li>
  								      <li class="font-phones">iPad mini 4</li>
  								      <li class="font-phones">iPad Pro 12.9 inch</li>
  								      <li class="font-phones">iPad Pro 9.7 inch</li>
  								      <li class="font-phones">iPad Pro 12.9-inch (2nd generation)</li>
  								      <li class="font-phones">iPad Pro 10.5-inch</li>
  								      <li class="font-phones">iPad Air</li>
  								      <li class="font-phones">iPad Air 3rd generation</li>
  								      <li class="font-phones">iPad Pro 4th generation</li>
  								      <li class="font-phones">iPad Mini 5th generation</li>
  								      <li class="font-phones">iPad (6th generation)</li>
  								      <li class="font-phones">10.2-inch iPad 7th generation</li>
  								    </ul>
  								  </div>
  								  <div class="col-md-12" class="termsServices">
  								    <p class="font-p mt-10 mb-0"><u>Android</u></p>
  								    <p class="font-p mt-10">Android Operating Systems, OS 4.4 (KitKat) and up</p>

  								    <h3 class="text-center font-weight-900">SCHEDULE "B"</h3>
  								    <h4><u>RECOMMEND Web Browsers</u></h4>
  								    <p class="font-p mt-10">QRSI has carried out extensive testing on many types of Web Browsers to bring you the most reliable and user-friendly experience possible. The following is a list of mobile Web Browsers recommended by QRSI for use with Hey It’s Ready Business Application. While Web Browsers may be able to provide you with a functional and acceptable experience, the reliability and functionality of other Web Browsers may not currently meet QRSI’s performance expectations and QRSI is not currently able to offer support for Web Browsers that are not listed below.
  								    Support is subject to the changing requirements and adding future functionality to the Hey It’s Ready Business Application and the compatibility with outdated Web Browsers. Support for older Web Browsers may be dropped to facilitate improved functionality or performance, for updated Web Browsers, when the older Web Browser versions have reached a point where support and testing can no longer be justified at the discretion of QRSI.
  								    </p>

  								    <h4><u>RECOMMEND Web Browsers</u></h4>
  								    <ul id="recommend-ul">
  								      <li><b class="font-800 mt-10">Google Chrome</b> 60 or higher</li>
  								      <li><b class="font-800 mt-10">Firefox</b> 55 and higher</li>
  								      <li><b class="font-800 mt-10">Microsoft Edge</b> 40 and higher</li>
  								      <li><b class="font-800 mt-10">Opera</b> 47 and higher</li>
  								      <li><b class="font-800 mt-10">Brave</b> 1.80 or higher</li>
  								      <li><b class="font-800 mt-10">Safari</b> 11 and higher on OSX</li>
  								    </ul>
  								  </div>
  								</div>
  	            </div>
  	            <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  	            </div>
  	        </div>
  	    </div>
  	</div>
</body>
<script type="text/javascript">
  var APP_URL = {!! json_encode(url('/')) !!}
</script>
<script src="{{URL::asset('js/jquery.js')}}"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{URL::asset('js/jquery.mask.min.js')}}" ></script>
<script src="{{URL::asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{URL::asset('js/custom_login.js')}}"></script>
@yield('myScripts')
</html>
