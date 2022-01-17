@extends('layouts.login')

@section('content')
<div id="qrOrderPage">
	<div class="row">
		<div class="col-lg-12">
	    <section class="card qr-code-card">
				<?=$bobj->getHtmlDiv();?>
	    </section>
	  </div>
	</div>
</div>

@endsection
