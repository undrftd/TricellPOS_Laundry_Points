@extends('layout')

@section('title')
SALES
@endsection

@section('css')
{{ asset('imports/css/sales.css') }}
@endsection

@section('content')

</br>
<div class="container">
<!---title inventory-->
<h3 class="title">Sales Record </h3>
  <span class="text-right" style="display:block;float:right;">

  @if((!empty($account_type) && !empty($payment_mode) && !empty($date_start) && !empty($date_end)) && ($date_start != $date_end))
      <b>{{$account_type}}</b> Account Type using <b>{{$payment_mode}}</b> Payment from <b>{{date('F d, Y', strtotime($date_start))}}</b> until <b>{{date('F d, Y', strtotime($date_end))}}</b>
  @elseif((!empty($account_type) && !empty($payment_mode) && !empty($date_start) && !empty($date_end)) && ($date_start == $date_end))
      <b>{{$account_type}}</b> Account Type using <b>{{$payment_mode}}</b> Payment on <b>{{date('F d, Y', strtotime($date_start))}}</b>
  @endif</span>
</br>
<hr>
<!---end of title inventory-->
<!--second row add item button and search bar--->
<div class="row">
  <div class="col-md-8">
    <h3 class="text-info">Total Sales: <span style="color:dimgray">₱ {{number_format($sumsales,2)}}</span></h3>
  </div>

  <div class="col-md-4">
    <form class="form ml-auto">

    <div class="row">
      <div class="col-md-6">
        <a href="/logs/sales/export" class="form-control btn btn-outline-info add-item-btn">
Export to CSV</a>
      </div>
      <div class="col-md-6">
        <button type="button" class="form-control btn btn-outline-info add-item-btn" data-toggle="popover"  data-original-title='Filter Results <a href ="/logs/sales/" class="btn btn-info btn-save-modal clear-btn" id="filter-submit">Clear</a>' data-content='
<form id="mainForm" action="/logs/sales/filter" method="GET">
  <div class="form-group">
    <b><label for="accounttype">Account Type:</label></b>
    <select class="form-control" name="account_type" id="accounttype">
      <option selected value="Any">Any</option>
      <option value="Member">Member</option>
      <option value="Walk-in">Walk-in</option>
    </select>
    <br>

    <b><label for="paymentmode">Payment Mode:</label></b>
    <select class="form-control" name="payment_mode" id="paymentmode">
      <option selected value="Any">Any</option>
      <option value="Cash">Cash</option>
      <option value="Card Load">Card Load</option>
    </select>
    <br>

    <b><label for="daterange">Date Range:</label></b>
    <input type="text" name="date_filter" value="" class="form-control" autocomplete="off" required>
    <br>
    <center><button type="submit" class="btn btn-info btn-save-modal" id="filter-submit">Submit</button></center>
</form>
'>Filter</button>
  </div>
    </form>
  </div>
  </div>
 </div>

 @if(!empty($account_type) && !empty($payment_mode) && !empty($date_start) && !empty($date_end))
      @if($totalcount > 7)
        <center><p> Showing {{$count}} out of {{$totalcount}} results
      @else
        <center><p> Showing {{$count}}
        @if($count > 1 || $count == 0)
          {{'results'}}
        @else
          {{'result'}}
        @endif
      @endif
  @endif
    <table class="table table-hover">
      @csrf
      <thead class ="th_css">
        <tr>
		      <th scope="col">Date</th>
          <th scope="col">Customer Name</th>
          <th scope="col">Account Type</th>
		      <th scope="col">Discount</th>
          <th scope="col">Total</th>
          <th scope="col">Mode of Payment</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody class="td_class">
        @foreach($sales as $sale)
        <tr>
          <th scope="row" class="td-center">{{ date("F d, Y", strtotime($sale->transaction_date)) }}</th>
          <td class="td-center">
            @if($sale->member_id == 0)
              {{$sale->guest->customer_name}}
            @else
              {{$sale->user->firstname . " " . $sale->user->lastname}}
            @endif
          </td>
          <td class="td-center">
            @if($sale->member_id == 0)
              {{"Walk-in"}}
            @else
              {{ucfirst($sale->user->role)}}
            @endif
          </td>
          <td class="td-center">
            @if(isset($sale->discount->discount_name))
            {{$sale->discount->discount_name}}
            @else
            {{'None'}}
            @endif
          </td>
          <td class="td-center">₱ {{number_format($sale->amount_due,2, '.', '')}}</td>
          <td class="td-center">{{ucwords($sale->payment_mode)}}</td>

          <td> <a href="{{ url('logs/sales/showdetails/' . $sale->id) }}"><button type="button" class="btn btn-secondary edit-btn" id="view-receipt" data-id="{{$sale->id}}" data-discount_id="@if(isset($sale->discount->discount_name)){{$sale->discount->id}}@else
            {{''}}
            @endif"><i class="material-icons md-18">receipt</i></button></a>
		  <button type="button" class="btn btn-danger del-btn" id="delete-sales" data-id="{{$sale->id}}" data-custid="@if(isset($sale->guest->id)) {{$sale->guest->id}} @else {{$sale->user->id}} @endif" data-type="@if(isset($sale->user->role)) {{'member'}} @else {{'guest'}} @endif" data-toggle="modal" data-target=".delete"><i class="material-icons md-18">delete</i></button>

          </td>
        </tr>
        @endforeach


      </tbody>
    </table>

    {{$sales->links()}}

    <!----end of modal---->
   <!----start of modal for DELETE---->
    <div class="modal fade delete" tabindex="-1" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Delete Log</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
        <center> <p> Are you sure you want to delete this <b>sales log</b>?</p> </center>
        <span class="sales-id-delete" hidden="hidden"></span>
        <span class="cust-id-delete" hidden="hidden"></span>
        <span class="cust-type-delete" hidden="hidden"></span>
        </div>

    <div class="modal-footer" id="modal-footer-sales-delete">
      <button type="button" class="btn btn-info btn-save-modal" id="destroy-sales">Yes</button>
      <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">No</button>

    </div>
    </div>
    </div>
    </div>



    <!----end of modal---->

</div>


<script type="text/javascript">

  $(document).on('click', '#delete-sales', function() {
    $('.sales-id-delete').text($(this).data('id'));
    $('.cust-id-delete').text($(this).data('custid'));
    $('.cust-type-delete').text($(this).data('type'));
  });

  $('#modal-footer-sales-delete').on('click', '#destroy-sales', function(){
  $.ajax({
    type: 'POST',
    url: '/logs/sales/delete_sales',
    data: {
      '_token': $('input[name=_token]').val(),
      'sales_id': $('.sales-id-delete').text(),
      'cust_id': $('.cust-id-delete').text(),
      'type': $('.cust-type-delete').text(),
    },
    success: function(data){
      localStorage.setItem("delete","success");
      window.location.reload();
    },
    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log(JSON.stringify(jqXHR));
      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
    }
    });
  });

  $(document).ready(function(){
    if(localStorage.getItem("delete"))
    {
        swal({
                title: "Success!",
                text: "You have successfully deleted the sales log!",
                icon: "success",
                button: "Close",
              });
        localStorage.clear();
    }
  });

  $(document).ready(function () {
    $(function () {
    $('[data-toggle="popover"]').popover({
      html: true,
      placement: 'bottom',
    }).on('shown.bs.popover', function () {
          $('input[name="date_filter"]').daterangepicker({
              autoUpdateInput: false,
              opens: 'center',
              locale: {
                  cancelLabel: 'Clear'
              }
          });

          $('input[name="date_filter"]').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
          });

          $('input[name="date_filter"]').on('cancel.daterangepicker', function(ev, picker) {
              $(this).val('');
          });

          $('#accounttype').change(function(){
            if($(this).val()=="Walk-in") {
              $('#paymentmode option').removeAttr("selected", "selected");
              $('#paymentmode option[value="Cash"]').attr("selected", "selected");
              $('#paymentmode').attr("disabled", "disabled");
            }
            else
            {
              $('#paymentmode').removeAttr("disabled", "disabled");
            }
          });

          $("#mainForm").submit(function() {
            $("#paymentmode").removeAttr("disabled");
          });
      });
    });
  });


</script>
@endsection
