<div class="container">
    <div class="page-header">
        <h1>Payment Invoice </h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 body-main">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                          <img src="{{asset('img/ipixup_background_image.png')}}" style="max-width:100px;"/>
                        </div>
                        <div class="col-md-8 text-right">
                          @if($userData)
                            <p>Hey {{ucfirst($userData->user->first_name)}} {{ucfirst($userData->user->last_name)}},</p>
                          @endif
                        </div>
                    </div>
                    <br />
                    <div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="col-md-9"><strong>Transaction Id</strong></td>
                                    <td class="col-md-3">{{$userData->transaction_id}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-9"><strong>Invoice Number</strong></td>
                                    <td class="col-md-3">{{$userData->id}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-9"><strong>Amount</strong></td>
                                    <td class="col-md-3">${{$userData->amount}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-9"><strong>Note</strong></td>
                                    <td class="col-md-3">{{$userData->note}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-9"><strong>Business Name</strong></td>
                                    <td class="col-md-3">{{$userData->order->company->company_name}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-9"><strong>Business Address</strong></td>
                                    <td class="col-md-3">{{$userData->order->company->address}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-9"><strong>Order Placed</strong></td>
                                    <td class="col-md-3">
                                      @if($userData->order->actual_order_time)
                                        {{convertEmailTime($userData->order->actual_order_time)}}
                                      @endif
                                    </td>
                                </tr>
                                <tr style="display:none;">
                                    <td class="col-md-9"><strong>Created Date</strong></td>
                                    <td class="col-md-3">{{convertDirectToLocal($userData->created_at)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <div class="col-md-12">
                          <p style="margin-top:30px;">Sincerely,</p>
                          <p>The Team at Hey It’s Ready!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
