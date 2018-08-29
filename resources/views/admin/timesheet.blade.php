@extends('layout')

@section('title')
TIMESHEET
@endsection

@section('css')
{{ asset('imports/css/members.css') }}
@endsection

@section('content')

</br>
<div class="container">
<!---title inventory-->
<h3 class="title">Timesheet</h3>
</br>
<hr>
  <!--end of members nav---->
<!---content of tabs start-->
  <div class="row">
    <div class="col-md-8">
           <button type="button" class="btn btn-outline-info add-mem-btn" data-toggle="modal" data-target=".time-in-modal"
            <?php if(count($time_in) >= 1) { echo 'hidden'; } ?>
           >Time In</button>
           <button type="button" class="btn btn-outline-info add-mem-btn" data-toggle="modal" data-target=".time-out-modal"
           <?php if(count($time_out) < 1) { echo 'hidden'; } ?>
           >Time Out</button>
          <a href="/timesheet/export/" class="btn btn-outline-info add-staff-btn">Export to CSV</a>
    </div>
    <div class="col-md-4">
    <form class="form ml-auto" action="/timesheet/filter" method="GET">
      <div class="input-group">
          <input class="form-control" name="date_filter" type="text" placeholder="Filter by Date" aria-label="Search" style="padding-left: 20px; border-radius: 40px;" id="date_filter" autocomplete="off">
          <div class="input-group-addon" style="margin-left: -50px; z-index: 3; border-radius: 40px; background-color: transparent; border:none;">
            <button class="btn btn-outline-info btn-sm" type="submit" style="border-radius: 100px;" id="search-btn"><i class="material-icons">search</i></button>
          </div>
      </div>
    </form>
  </div>
  </div>

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
          <th scope="col">ID Number</th>
          <th scope="col">Username</th>
          <th scope="col">Name</th>
          <th scope="col">Time In</th>
          <th scope="col">Time Out</th>
        </tr>
      </thead>
      <tbody class="td_class">
        @foreach($employees as $employee)
        <tr>
          <td class="td-center"><b>{{ date('F d, Y', strtotime($employee->time_in)) }}</b></td>
          <td class="td-center">{{ $employee->user->card_number }}</td>
          <td class="td-center">{{ $employee->user->username }}</td>
          <td class="td-center">{{ $employee->user->firstname . " " . $employee->user->lastname }}</td>
          <td class="td-center">{{ date('h:i:s A', strtotime($employee->time_in)) }}</td>
          <td class="td-center">
            @if(empty($employee->time_out))
              {{ 'Currently in'}}
            @elseif(date('F d, Y', strtotime($employee->time_out)) != date('F d, Y', strtotime($employee->time_in)))
              {{ date('h:i:s A', strtotime($employee->time_out)) }}

              <span style="color:red">{{ date('m-d-Y', strtotime($employee->time_out)) }}</span>
            @else
              {{ date('h:i:s A', strtotime($employee->time_out)) }}
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{$employees->links()}}
  </div>

   <!----start of modal for time in---->
    <div class="modal fade time-in-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
     <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirm Time In</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        </br>

        <form id="add-form" class="nosubmitform">
        
        <div class="form-group row mx-auto">
          <label for="card-no" class="col-form-label col-md-3 modal-card">ID No:</label>
          <div class="col-md-9">
            <input type="text" name="card_number" class="form-control modal-card" id="cardnumber-time" autocomplete="off">
           <p id="error-cardnumber-add" class="error-add" hidden="hidden"></p>
          </div>
        </div>
        <div class="modal-footer" id="modal-footer-admin-add">
          <button type="submit" class="btn btn-info btn-savemem-modal" id="time-in">Time In</button>
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
        </div>
      </form>
      </div>
    </div>
    </div>

    <!----end of modal---->
    <!----start of modal for time out---->
    <div class="modal fade time-out-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
     <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirm Time Out</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        </br>
          <div class="modal-body">
          <center><p> Are you sure you want to time out at {{\Carbon\Carbon::now()->format('h:i:s A')}}?</p></center>
          </div>
        
        <div class="modal-footer" id="modal-footer-admin-add">
          <button type="submit" class="btn btn-danger btn-savemem-modal" id= "time-out">Time Out</button>
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
        </div>
  
      </div>
    </div>
    </div>

    <!----end of modal---->

  <script type="text/javascript">
  $('.nosubmitform').submit(function(event){
    event.preventDefault();
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

  $('.time-in-modal').on('hide.bs.modal', function(){
    $('#error-cardnumber-add').attr('hidden', true);
    $('#cardnumber-time').removeAttr('style');
    $('#cardnumber-time').val('');
  });

  $(document).ready(function(){
    if(localStorage.getItem("timein"))
    {
      swal({
              title: "Success!",
              text: "You have successfully logged your time-in!",
              icon: "success",
              button: "Close",
            });
      localStorage.clear();
    }
    else if(localStorage.getItem("timeout"))
    {
      swal({
              title: "Success!",
              text: "You have successfully logged your time-out!",
              icon: "success",
              button: "Close",
            });
      localStorage.clear();
    }
  });

  $(document).on('click', '#time-in', function(){
    $.ajax({
      type: 'POST',
      url: '/timesheet/time_in',
      data: {
              '_token': $('input[name=_token]').val(),
              'id': $("#cardnumber-time").val()
            },
      success: function(data) {
        if(data.error)
        {
          $('#error-cardnumber-add').removeAttr("hidden");
          $('#error-cardnumber-add').text(data.error);
          $('#cardnumber-time').css("border", "1px solid #cc0000");
        }
        else
        {
          localStorage.setItem("timein","success");
          window.location.reload();
        }
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    });
  });

  $(document).on('click', '#time-out', function(){
    $.ajax({
      type: 'GET',
      url: '/timesheet/time_out',
      data: {
              '_token': $('input[name=_token]').val(),
              // 'id': $("#cardnumber-time").val()
            },
      success: function(data) {
          localStorage.setItem("timeout","success");
          window.location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    });
  });
  </script>
@endsection
