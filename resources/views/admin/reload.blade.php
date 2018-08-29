@extends('layout')

@section('title')
RELOAD SALES
@endsection

@section('css')
{{ asset('imports/css/sales.css') }}
@endsection

@section('content')

</br>
<div class="container">
<!---title inventory-->
<h3 class="title">Reload Sales Record</h3>
</br>
<hr>
<!---end of title inventory-->
<!--second row add item button and search bar--->
<div class="row">
    <div class="col-md-6">
   <h3 class="text-info">Total Sales: <span style="color:dimgray">₱ {{number_format($sumsales,2)}}</span></h3>
  </div>
   <div class="col-md-6">
    <div class="row">
      <div class="col-md-4">
        <a href="/logs/reload/export" class="form-control btn btn-outline-info add-item-btn">Export to CSV</a>
      </div>
      <div class="col-md-8">
        <form class="form ml-auto" action="/logs/reload/filter" method="GET">
          <div class="input-group">
              <input class="form-control" name="date_filter" type="text" placeholder="Filter by Date" aria-label="Search" style="padding-left: 20px; border-radius: 40px;" id="date_filter" autocomplete="off">
              <div class="input-group-addon" style="margin-left: -50px; z-index: 3; border-radius: 40px; background-color: transparent; border:none;">
                <button class="btn btn-outline-info btn-sm" type="submit" style="border-radius: 100px;" id="search-btn"><i class="material-icons">search</i></button>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
 </div> <!----end of second row--->
 <!---table start---->

  @if((!empty($date_start) && !empty($date_end)) && ($date_start != $date_end))
      @if($totalcount > 7)
        <center><p> Showing {{$count}} out of {{$totalcount}} results
          from <b>{{date('F d, Y', strtotime($date_start))}}</b> until <b>{{date('F d, Y', strtotime($date_end))}} </b> </p></center>
      @else
        <center><p> Showing {{$count}}
        @if($count > 1 || $count == 0)
          {{'results'}}
        @else
          {{'result'}}
        @endif
          from <b>{{date('F d, Y', strtotime($date_start))}}</b> until <b>{{date('F d, Y', strtotime($date_end))}} </b> </p></center>
      @endif
  @elseif((!empty($date_start) && !empty($date_end)) && ($date_start == $date_end))
      @if($totalcount > 7)
        <center><p> Showing {{$count}} out of {{$totalcount}} results
          for <b>{{date('F d, Y', strtotime($date_start))}}</b> </p></center>
      @else
        <center><p> Showing {{$count}}
        @if($count > 1 || $count == 0)
          {{'results'}}
        @else
          {{'result'}}
        @endif
          for <b>{{date('F d, Y', strtotime($date_start))}}</b> </p></center>
      @endif  
  @endif

    <table class="table table-hover">
      @csrf
      <thead class ="th_css">
        <tr>
          <th scope="col">Date</th>
          <th scope="col">Card Number</th>
          <th scope="col">Member Name</th>
          <th scope="col">Amount Reloaded</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody class="td_class">
        @foreach($reloads as $reload)
        <tr>
          <th scope="row" class="td-center">{{date("F d, Y", strtotime($reload->transaction_date))}}</th>
          <td class="td-center">{{$reload->user->card_number}}</td>
          <td class="td-center">{{$reload->user->firstname . " " . $reload->user->lastname}}</td>
          <td class="td-center">₱ {{number_format($reload->amount_due,2, '.', '')}}</td>

          <td> <a href="{{ url('logs/reload/showdetails/' . $reload->id) }}"><button type="button" id="view-receipt" data-id="{{$reload->id}}" class="btn btn-secondary edit-btn"><i class="material-icons md-18">receipt</i></button></a>
      <button type="button" class="btn btn-danger del-btn" id="delete-reload" data-id="{{$reload->id}}" data-toggle="modal" data-target=".delete"><i class="material-icons md-18">delete</i></button> </td>
        </tr>
        @endforeach

      </tbody>
    </table>

    {{$reloads->links()}}

    <!----end of modal---->
   <!----start of modal for DELETE---->
    <div class="modal fade delete" tabindex="-1" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Message</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
        <center>  <p> Are you sure you want to delete this <b>reload log</b>?</p> </center>
        <span class="reload-id-delete" hidden="hidden"></span>
        </div>

    <div class="modal-footer" id="modal-footer-reload-delete">
      <button type="button" class="btn btn-info btn-save-modal" id="destroy-reload">Yes</button>
      <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">No</button>

    </div>
    </div>
    </div>
    </div>

</div>

<script type="text/javascript">
  //view receipt
  // $(document).on('click', '#view-receipt', function() {
  //   $.ajax({
  //   type: 'POST',
  //   url: '/logs/reload/showdetails',
  //   data: {
  //     '_token': $('input[name=_token]').val(),
  //     'reload_id': $(this).data('id'),
  //   },
  //   success: function(data){
  //     $('.view_details').modal('show');
  //     $('#view_body').html(data);
  //   },
  //   error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
  //     console.log(JSON.stringify(jqXHR));
  //     console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
  //   }
  //   });
  // });

  //delete log
  $(document).on('click', '#delete-reload', function() {
    $('.reload-id-delete').text($(this).data('id'));
  });

  $('#modal-footer-reload-delete').on('click', '#destroy-reload', function(){
  $.ajax({
    type: 'POST',
    url: '/logs/reload/delete_reload',
    data: {
      '_token': $('input[name=_token]').val(),
      'reload_id': $('.reload-id-delete').text()
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
                text: "You have successfully deleted the reload log!",
                icon: "success",
                button: "Close",
              });
        localStorage.clear();
    }
  });


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
</script>

@endsection