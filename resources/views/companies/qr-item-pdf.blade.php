<style>
#apps-qr-code,#apps-qr-code2 {
  display: flex;
  justify-content: start;
}
.logo1 img{
  float:left;
  margin-left:150px;
}
.logo2 img{
  float:right;
  margin-right:50px;
}
.qrOne div{
  right:70%;
  top:10%;
}
.qrTwo div{
  left:50%;
  top:12%;
}
#item-qr-code-pdf div{left:-10%;}
</style>
<div id="qrOrderPage">
  <div class="row">
    <div class="col-lg-6" style="text-align:center;">
      <section class="card qr-code-card">
        <header class="card-header text-center" style="font-size:40px;color:#000;">
            {{ucwords(getBusinessName())}} Menu
        </header>
				<p style="text-align:center;color:#1562A5;font-size:25px;">Please Scan the QR Code to see our menu</p>
        <div id="item-qr-code-pdf"><?= $bobj->getHtmlDiv();?></div>
					<p class="text-center mt-2" style="font-size:18px;">Thank you for using Hey It's Ready</p>
					<p class="text-center" style="font-size:18px;">Scan the QR Code for your device to download the Hey It's Ready App</p>
          <div class="row" id="apps-qr-code">
            <div class="logo1">
              <img src="img/google-play-icon.png" style="max-width:120px;"/>
            </div>
            <div class="qrOne">
              <?=$googleplay->getHtmlDiv();?>
            </div>
            <div class="logo2">
              <img src="img/apple-store-icon.png" style="max-width:100px;margin-top:10px;"/>
            </div>
            <div class="qrTwo">
              <?=$appleplay1->getHtmlDiv();?>
            </div>
          </div>
          <div class="row" id="apps-qr-code2">


          </div>
          <div class="row">
            <img src="img/ipixup_background_image.png" style="max-width:100px;margin-top:-100px;"/>
          </div>
      </section>
    </div>
  </div>
</div>
