@extends('layout')

@section('title')
QUEUEING
@endsection

@section('css')
{{ asset('imports/css/queue.css') }}
@endsection

@section('content')
</br>
<div class="container">
<!---title inventory-->
<h3 class="title">Queue</h3>
</br>
<hr>

<!---end of title inventory-->
<!--second row add item button and search bar--->
<div class="row">
  <div class="col-md-10"> 
  </div>
  <div class="col-md-2">
    <button type="button" class="btn btn-outline-info view-btn">
      View Status
    </button>
  </div>
</div> 


    <table class="table table-hover">

  @csrf
      <thead class ="th_css">
        <tr>
      		  <th scope="col">Queue No.</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Washers</th> 
            <th scope="col">Dryers</th>
      		  <th scope="col">Switch</th> 
        </tr>
      </thead>
      <tbody class="td_class">
        @foreach($queues as $queue)
        <tr>
          <th scope="row" class="td-center">{{$loop->iteration + $skipped}}</th>
          <td class="td-center">
          @if($queue->guest_id != 0)
            {{ $queue->guest->customer_name }}
          @else
            {{ $queue->user->firstname . " " . $queue->user->lastname}}  
          @endif
          </td>
          <td class="td-center">
            @if($queue->salesdetails->where('product_id', '<=', 12)->count() > 0)
              @if($queue->salesdetails->where('product_id', '<=', 12)->where('isUsed', '==', 0)->count() > 0)
                @foreach($queue->salesdetails->where('product_id', '<=', 12)->where('isUsed', '==', 0)->sortBy('product_id') as $q)
                  {{substr($q->product->product_name, strpos($q->product->product_name, " ") + 1)}}
              
                  @if($loop->iteration != $queue->salesdetails->where('product_id', '<=', 12)->where('isUsed', '==', 0)->count())
                    {{", "}}
                  @endif
               @endforeach
              @else
                {{"All Orders Completed"}}
              @endif
            @else
              {{ "No Washers Ordered" }}
            @endif
          </td>
          <td class="td-center">
            @if($queue->salesdetails->where('product_id', '>', 12)->where('product_id', '<=', 24)->count() > 0)
              @if($queue->salesdetails->where('product_id', '>', 12)->where('product_id', '<=', 24)->where('isUsed', '==', 0)->count() > 0)
                @foreach($queue->salesdetails->where('product_id', '>', 12)->where('product_id', '<=', 24)->where('isUsed', '==', 0)->sortBy('product_id') as $q)
                  {{substr($q->product->product_name, strpos($q->product->product_name, " ") + 1)}}
              
                  @if($loop->iteration != $queue->salesdetails->where('product_id', '>', 12)->where('product_id', '<=', 24)->where('isUsed', '==', 0)->count())
                    {{", "}}
                  @endif
               @endforeach
              @else
                {{"All Orders Completed"}}
              @endif
            @else
              {{ "No Washers Ordered" }}
            @endif
          </td>
          <td>
            <button type="button" class="btn btn-success switch-btn" data-id="{{$queue->id}}"><i class="material-icons md-18">
power_settings_new
</i></button>
          </td>
        </tr>
        @endforeach

      </tbody>
    </table>

    {{$queues->links()}}

 <!----start of modal for add item---->
    <div class="modal fade view-status" tabindex="-1" role="dialog">
     <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body" id="viewstatus">
          
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
    </div>

    <!----end of modal---->
	 <!----start of modal for add item---->
    <div class="modal fade switch-modal" tabindex="-1" role="dialog">
     <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Switch</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body mx-auto" id="detailsmodal">
      
		    </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
        </div>
   
    </div>
    </div>


</div>

<script type="text/javascript">
  $(document).on('click', '.switch-btn', function() {
    $.ajax({
    type: 'POST',
    url: '/queue/showdetails',
    data: {
            '_token': $('input[name=_token]').val(),
            'sales_id': $(this).attr('data-id'),
          },
    success: function(data)
    {
      $('.switch-modal').modal('show');
      $('#detailsmodal').html(data);
    },
    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log(JSON.stringify(jqXHR));
      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      window.location.reload();
    }
  })

  });

  $(document).ready(function(){
    if(localStorage.getItem("move"))
    {
      swal({
              title: "Success!",
              text: "The customer has completed his/her transaction!",
              icon: "success",
              button: "Close",
            });
      localStorage.clear();
    }
  });

  $(document).on('click', '.switch__toggle', function() {
    $('.switch__toggle').attr('disabled', true);
    
    $('.switch-modal').on('hide.bs.modal', function(e){
      e.preventDefault();
    });

    var switches = $('.service').find($('[data-id="'+ $(this).attr('data-id') +'"]'));
    var attr = $(this).attr('checked');
    var id = $(this).attr('data-id');
    var prod_id = $(this).attr('data-product-id');

    $.ajax({
    type: 'POST',
    url: '/queue/switch',
    data: {
            '_token': $('input[name=_token]').val(),
            'id': id,
            'sales_id': $(this).attr('data-sales-id'),
            'product_id': prod_id,
          },
    success: function(data)
    {  
      if(data.used >= data.quantity)
      {
        if($('#switch' + id).is(':checked'))
        {  
          $('input[type=checkbox]').not('[data-isUsed="1"]').removeAttr('disabled');
        }
        else
        {  
          $('#switch' + id).attr('data-isUsed', data.isUsed);
          $('#switch' + id).attr('disabled', true);

          $('input[type=checkbox]').not('[data-isUsed="1"]').not('#switch' + id).removeAttr('disabled');
        } 

        if((data.countrow == data.detailcount) && (data.sumswitch == 0))
        {
          localStorage.setItem("move","success");
          window.location.reload();
        } 
      }
      else
      {
        $('input[type=checkbox]').not('[data-isUsed="1"]').removeAttr('disabled');
      }

      $('.switch-modal').off('hide.bs.modal');
      $('.switch-modal').on('hide.bs.modal', function(e){
        window.location.reload();
      });
    },
    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log(JSON.stringify(jqXHR));
      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      window.location.reload();
    }
  })

  });

  $(document).on('click', '.view-btn', function() {
    $.ajax({
    type: 'POST',
    url: '/queue/view_status',
    data: {
            '_token': $('input[name=_token]').val(),
          },
    success: function(data)
    {
      $('.view-status').modal('show');
      $('#viewstatus').html(data);

      $('.timerexpire').each(function() {
        var finishDate = $(this).data('expire');
        $(this).countdown(finishDate, function(event) {
          $(this).html(event.strftime('%N:%S'));
        });
      });
      
    },
    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log(JSON.stringify(jqXHR));
      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      window.location.reload();
    }
  })

  });
</script>
@endsection