@extends('layouts.default')
@section('content')
<section class="card" id="payoutsSection">
    <header class="card-header font-title">
      @lang('payouts.list_heading')
      <i class="fas fa-question-circle"
      data-toggle="tooltip" data-placement="top" title="This table will show all payouts for your stripe connect account. When a client pays by credit card you will see the payment transaction here." data-container="body"></i>
    </header>
    <div class="card-body">
        <div class="col-md-12">
          <div id="baseDateControl">
            <div class="dateControlBlock">
                <label>@lang('payouts.filter_start_date') </label>
                <i class="fas fa-question-circle"
                data-toggle="tooltip" data-placement="top" title="You are able to select the start date in the filter option here." data-container="body"></i>
                <input type="text" name="dateStart" id="dateStart" class="datepicker" value="" size="8" />
                <label>@lang('payouts.filter_end_date') </label>
                <i class="fas fa-question-circle"
                data-toggle="tooltip" data-placement="top" title="You are able to select end date in the filter option here." data-container="body"></i>
                <input type="text" name="dateEnd" id="dateEnd" class="datepicker" value="" size="8"/>
                <button class="btn btn-default" id="resetPayouts">
                  @lang('payouts.filter_reset_btn')
                  <i class="fas fa-question-circle"
                  data-toggle="tooltip" data-placement="top" title="This will reset all filter form options." data-container="body"></i>
                </button>
                <button class="btn btn-secondary" id="filterPayouts">@lang('payouts.filter_label')</button>
            </div>
          </div>
          <div class="table-responsive mt-4">
            <table class="table table-striped table-advance table-hover" id="payoutsTable">
                <thead>
                    <tr>
                      <th>@lang('payouts.table_transaction_label')</th>
                      <th>@lang('payouts.table_amount_label')</th>
                      <th>@lang('payouts.table_currency_label')</th>
                      <th>@lang('payouts.table_payment_type_label')</th>
                      <th>@lang('payouts.table_card_number_label')</th>
                      <th>@lang('payouts.table_created_label')</th>
                    </tr>
                </thead>
                <tbody>
                  @if($payments)
                    @foreach($payments as $payout)

                      <tr>
                        <td>{{$payout->id}}</td>
                        <td>${{(float)number_format(($payout->amount/100), 2, '.', '')}}</td>
                        <td>{{$payout->currency}}</td>
                        <td>{{ucwords(str_replace("_"," ",$payout->payment_method_details->type))}}</td>
                        <td>
                          @if($payout->payment_method_details->type=='card')
                            {{$payout->payment_method_details->card->last4}}
                          @else
                          @endif
                        </td>
                        <td>{{date('Y-m-d',$payout->created)}}</td>
                      </tr>

                    @endforeach
                  @endif
                </tbody>
                <tfoot>
                    <tr>
                      <th>@lang('payouts.table_transaction_label')</th>
                      <th>@lang('payouts.table_amount_label')</th>
                      <th>@lang('payouts.table_currency_label')</th>
                      <th>@lang('payouts.table_payment_type_label')</th>
                      <th>@lang('payouts.table_card_number_label')</th>
                      <th>@lang('payouts.table_created_label')</th>
                    </tr>
                </tfoot>
            </table>
          </div>
        </div>
    </div>
</section>
@endsection
@section('myScripts')
<link href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>
<link href="//cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css"/>
<link href="//cdn.datatables.net/datetime/1.1.1/css/dataTables.dateTime.min.css"/>

<script type="text/javascript" src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
<script type="text/javascript">
  var minDate, maxDate;

  // Custom filtering function which will search data in column four between two values
  // The plugin function for adding a new filtering routine
    $.fn.dataTableExt.afnFiltering.push(
        function(oSettings, aData, iDataIndex){
            //var dateStart = parseDateValue($("#dateStart").val());
            //var dateEnd = parseDateValue($("#dateEnd").val());
            if($("#dateStart").val() || $("#dateEnd").val()){
              var dateStart = new Date($("#dateStart").val());
              var dateEnd = new Date($("#dateEnd").val());
              var evalDate= new Date(aData[5]);
              if (evalDate >= dateStart && evalDate <= dateEnd) {
                  return true;
              }else if(evalDate >= dateStart){
                return true;
              }else {
                console.log(evalDate);
                console.log(dateStart);
                console.log(dateEnd);
                  return false;
              }
            }else{
              return true;
            }
        });

    // Function for converting a mm/dd/yyyy date value into a numeric string for comparison (example 08/12/2010 becomes 20100812
    function parseDateValue(rawDate) {
        var dateArray= rawDate.split("/");
        var parsedDate= dateArray[2] + dateArray[0] + dateArray[1];
        return parsedDate;
    }
  $(document).ready( function () {
    var $dTable= $('#payoutsTable').DataTable({
      "order": [[ 5, "desc" ]],
      "lengthMenu": [[10, 20, 30, -1], [10, 20, 30, "All"]],
      dom: 'Blfrtip',
      buttons: [{
        extend: 'pdf',
        title: 'Payouts',
        filename: 'Payouts',
        className: 'btn btn-secondary mb-2'
      }]
    });

    // Implements the jQuery UI Datepicker widget on the date controls
      $.fn.datepicker.defaults.format = "yyyy-mm-dd";
        $('.datepicker').datepicker({
          showOn: 'button',
          buttonImage: 'assets/images/calendar.gif',
          buttonImageOnly: true
        });

        // Create event listeners that will filter the table whenever the user types in either date range box or
        // changes the value of either box using the Datepicker pop-up calendar
        /*$("#dateStart").keyup ( function() { $dTable.draw(); } );
        $("#dateStart").change( function() { $dTable.draw(); } );
        $("#dateEnd").keyup ( function() { $dTable.draw(); } );
        $("#dateEnd").change( function() { $dTable.draw(); } );*/

        $('#filterPayouts').click(function(){
          $dTable.draw();
        });
        $('#resetPayouts').click(function(){
          $('#dateStart').val('');
          $('#dateEnd').val('');
          $dTable.draw();
        });
  });
</script>
@endsection
