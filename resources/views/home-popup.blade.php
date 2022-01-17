<!--User view-->
<div class="row justify-content-center">
    <div class="col-md-12 attendant-profile-page">
      <section class="card">
        <div class="card-body bio-graph-info">
          <div class="row p-details">
            <div class="col-md-6">
              <div class="bio-row" style="width:100%">
                <p><span class="bold text-primary">@lang('dashboard.table_th_order_number')</span>:
                  <b class="text-dark">{{$order->order_number}}</b>
                </p>
              </div>
              <div class="bio-row" style="width:100%">
                <p>
                  @lang('dashboard.table_th_customer_name'):
                  <span class="bold text-dark">{{$order->user->name}}</span>
                </p>
              </div>
              <div class="bio-row" style="width:100%">
                <p>
                    @lang('dashboard.table_th_cell_number'):
                  <span class="bold text-dark">
                    {{$order->user->phone_number}}
                  </span>
                </p>
              </div>
              <div class="bio-row" style="width:100%">
                <p>
                    @lang('dashboard.table_th_eput'):
                  <span class="bold text-dark">
                    @if($order->eta)
                      {{convertToLocal($order->eta)}}
                    @endif
                  </span>
                </p>
              </div>
              <div class="bio-row" style="width:100%">
                <p>
                    @lang('dashboard.table_th_order_time'):
                  <span class="bold text-dark">
                    @if($order->actual_order_time)
                       {{convertToLocal($order->actual_order_time)}}
                    @endif
                  </span>
                </p>
              </div>
              <div class="bio-row d-none" style="width:100%">
                <p>
                  <span class="bold">
                    @lang('dashboard.table_th_amount')
                  </span>:
                  {{number_format($order->amount,2)}}
                </p>
              </div>
              <div class="bio-row" style="width:100%">
                <p>
                    @lang('dashboard.table_th_customer_located'):
                  <span class="bold text-dark">
                    @if(checkOrderLocate($order->id))
                    Yes
                    @else
                    No
                    @endif
                  </span>
                </p>
              </div>
              <div class="bio-row" style="width:100%">
                <p>
                    @lang('dashboard.order_detail_pop_total_amount'):
                  <span class="bold text-dark">
                    ${{number_format($order->amount,2)}}
                  </span>
                </p>
              </div>
              <div class="bio-row" style="width:100%">
                <p>
                    @lang('dashboard.table_th_spot_number'):
                  <span class="bold text-dark">
                    {{$order->spot_number}}
                  </span>
                </p>
              </div>
              <div class="bio-row" style="width:100%">
                <p>
                    @lang('dashboard.table_th_paid_order'):
                  <span class="bold text-dark">
                    @if(checkPaidOrder($order->id))
                      Yes
                    @else
                     No
                    @endif
                  </span>
                </p>
              </div>
              <div class="bio-row" style="width:100%">
                <p>
                    @lang('site.table_list_location'):
                  <span class="bold text-dark">
                    {{getDefaultSiteName()}}
                  </span>
                </p>
              </div>
              @if($order->has('payment') && $order->payment)
              <div class="bio-row d-none" style="width:100%">
                <p>
                  <span class="bold">
                    @lang('dashboard.table_th_time_by_user')
                  </span>:
                  @if($order->payment->order_date)
                    {{date('d/m/Y H:i:s',strtotime($order->payment->order_date))}}
                  @endif
                </p>
              </div>
              @endif
              @if($cartCount>0)
                <div class="bio-row" style="width:100%">
                  <p>
                    <span class="bold">
                      @lang('dashboard.order_detail_pop_restaurant_name')
                    </span>:
                    {{$cartInfo[0]->company->company_name}}
                  </p>
                </div>
                <div class="bio-row" style="width:100%">
                  <p>
                    <span class="bold">
                      @lang('dashboard.order_detail_pop_menu_name')
                    </span>:
                    {{$cartInfo[0]->restaurant_menu->name}}
                  </p>
                </div>
                @php
                  $totalAmount=0;
                @endphp
                @foreach ($cartInfo as $key => $cart)
                  @php
                    $totalAmount+=$cart->total_price;
                  @endphp
                @endforeach
                <div class="bio-row" style="width:100%">
                  <p>
                    <span class="bold">
                      @lang('dashboard.order_detail_pop_total_amount')
                    </span>:
                    ${{number_format($totalAmount,2)}}
                  </p>
                </div>
              @endif
            </div>
            </div>
          </div>
          @if($cartCount>0)
            <header class="card-header font-title modal-title">Items Details</header>
            <div class="card-body bio-graph-info">
              <div class="row p-details">
                <div class="col-md-12">
                  @foreach ($cartInfo as $key => $cart)
                    <div class="bio-row" style="width:100%">
                      <p><span class="bold">@lang('dashboard.order_detail_pop_category_name')</span>:
                        {{$cart->category->name}}
                      </p>
                    </div>
                    <div class="bio-row" style="width:100%">
                      <p><span class="bold">@lang('dashboard.order_detail_pop_item_name')</span>:
                        {{$cart->item->name}}
                      </p>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                      <!--notification start-->
                      <section class="card">
                          <div class="card-body">
                              <div class="alert alert-dark modal-title" role="alert">
                                  @lang('dashboard.order_detail_pop_extras')
                              </div>
                              @if($cart->extras)
                                @php
                                  $allExtras=getCartExtras($cart->extras);
                                @endphp
                                @foreach ($allExtras as $ext)
                                <div class="bio-row ml-3" style="width:100%">
                                  <p><span class="bold">@lang('dashboard.order_detail_pop_extras_name')</span>:
                                    {{$ext->name}}
                                  </p>
                                </div>
                                <div class="bio-row ml-3" style="width:100%">
                                  <p><span class="bold">@lang('dashboard.order_detail_pop_amount')</span>:
                                    ${{number_format($ext->price,2)}}
                                  </p>
                                </div>
                                @endforeach
                              @endif
                          </div>
                      </section>
                      </div>
                    </div>
                  @endforeach
                </div>
                </div>
              </div>
            @endif
      </section>
    </div>
</div>
<!--User view-->
