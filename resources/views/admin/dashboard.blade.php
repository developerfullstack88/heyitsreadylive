@extends('layouts.superadmin')

@section('content')
<div class="row state-overview">
    <div class="col-lg-3 col-sm-6">
        <section class="card">
            <div class="symbol background-gray">
                <i class="fa fa-user"></i>
            </div>
            <div class="value">
                <h1 class="count count-common" id="dashboardInProgressCount">
                    {{$landlords}}
                </h1>
                <p class="ml-1 mr-1 font-title">Landlord's</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="card">
            <div class="symbol red">
                <i class="fa fa-tags"></i>
            </div>
            <div class="value">
                <h1 class=" count2 count-common" id="dashboardReadyCount">
                    {{$businessUsers}}
                </h1>
                <p class="ml-1 mr-1 font-title">Business User's</p>
            </div>
        </section>
    </div>
</div>
@endsection
@section('myScripts')
<script type="text/javascript">
/*dashboard count*/
/*dashboard count*/
</script>
@endsection
