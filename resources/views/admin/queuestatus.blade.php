<div class="container-fluid">
  
  <h6>WASHERS</h6> <br>
  <div class="row mx-auto">
      @foreach($washers as $washer)
        <div class="col-md-2">
          <h6><b>{{$loop->iteration}} </b></h6>
          @if($washer->switch != 0)
            <center><i class="material-icons laundry_icon 
            <?php 
              $finish_date = \Carbon\Carbon::parse($washer->finish_date);
              $now = \Carbon\Carbon::now();
              
              if($finish_date->diffInMinutes($now) < 10)
              {
                echo "laundry_icon_lastminute"; 
              }
              else
              {
                echo "laundry_icon_active";
              }
            ?>
              ">local_laundry_service</i></center>
            <p class="timer timerexpire" data-expire="{{$washer->finish_date}}"></p>
          @else
            <center><i class="material-icons laundry_icon laundry_icon_inactive">local_laundry_service</i></center>
            <p class="timer">Available</p>
          @endif
        </div>
      @endforeach          
  </div> 
  <hr>
  <h6>DRYERS</h6> <br>
  <div class="row mx-auto">
      @foreach($dryers as $dryer)
        <div class="col-md-2">
          <h6><b>{{$loop->iteration}}</b></h6>
          @if($dryer->switch != 0)
            <center><i class="material-icons laundry_icon 
            <?php 
              $finish_date = \Carbon\Carbon::parse($dryer->finish_date);
              $now = \Carbon\Carbon::now();
              
              if($finish_date->diffInMinutes($now) < 10)
              {
                echo "laundry_icon_lastminute"; 
              }
              else
              {
                echo "laundry_icon_active";
              }
            ?>
              ">toys</i></center>
            <p class="timer timerexpire" data-expire="{{$dryer->finish_date}}"></p>
          @else
            <center><i class="material-icons laundry_icon laundry_icon_inactive">toys</i></center>
            <p class="timer">Available</p>
          @endif
        </div>
      @endforeach   
  </div>  

</div>