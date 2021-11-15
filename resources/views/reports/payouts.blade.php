@extends('layouts.default')
@section('content')
<section class="card" id="payoutsSection">
    <header class="card-header font-title">Payouts Listing</header>
    <div class="card-body">
        <div class="col-md-12">
          <div id="baseDateControl">
            <div class="dateControlBlock">
                <label>Start Date </label>
                <i class="fas fa-question-circle"
                data-toggle="tooltip" data-placement="top" title="Add content here." data-container="body"></i>
                <input type="text" name="dateStart" id="dateStart" class="datepicker" value="" size="8" />
                <label>End Date </label>
                <i class="fas fa-question-circle"
                data-toggle="tooltip" data-placement="top" title="Add content here." data-container="body"></i>
                <input type="text" name="dateEnd" id="dateEnd" class="datepicker" value="" size="8"/>
                <button class="btn btn-default" id="resetPayouts">Reset</button>
                <button class="btn btn-secondary" id="filterPayouts">Filter</button>
            </div>
          </div>
          <div class="table-responsive mt-4">
            <table class="table table-striped table-advance table-hover" id="payoutsTable">
                <thead>
                    <tr>
                      <th>Transaction</th>
                      <th>Amount</th>
                      <th>Currency</th>
                      <th>Payment Type</th>
                      <th>Card Number</th>
                      <th>Created</th>
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
                        <td>{{date('d/m/Y',$payout->created)}}</td>
                      </tr>

                    @endforeach
                  @endif
                </tbody>
                <tfoot>
                    <tr>
                      <th>Transaction</th>
                      <th>Amount</th>
                      <th>Currency</th>
                      <th>Payment Type</th>
                      <th>Card Number</th>
                      <th>Created</th>
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
            var dateStart = parseDateValue($("#dateStart").val());
            var dateEnd = parseDateValue($("#dateEnd").val());
            if($("#dateStart").val() || $("#dateEnd").val()){
              // aData represents the table structure as an array of columns, so the script access the date value
              // in the first column of the table via aData[0]
              var evalDate= parseDateValue(aData[5]);

              if (evalDate >= dateStart && evalDate <= dateEnd) {
                console.log('inside');
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
      dom: 'Bfrtip',
      buttons: [{
        extend: 'pdf',
        title: 'Payouts',
        filename: 'Payouts',
        className: 'btn btn-secondary mb-2'
      }]
    });

    // Implements the jQuery UI Datepicker widget on the date controls
      $.fn.datepicker.defaults.format = "dd/mm/yyyy";
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
