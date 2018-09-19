@extends('layout')

@section('title')
DASHBOARD
@endsection

@section('css')
{{ asset('imports/css/dashboard.css') }}
@endsection

@section('content')
<div class="container-fluid">
   <br>
   <div class="row">
      <div class="col m-1" id="new-customers" onclick="window.location='{{ url("accounts/members") }}'">
      <div class="row">
        <div class ="col-xs-1">         
           <i class="material-icons group">group</i>
        </div>
        <div class ="col-xs-2">
           <p class="title">New Customers  <span class="last-time"></br>Last 7 days</span></p>
        </div>
        <div class ="col-xs-9 mx-auto">
           <h3 class="newmember-count">{{$newmembers}}</h3>
        </div>
       </div>
    </div>
      <div class="col m-1" id="new-reload" onclick="window.location='{{ url("logs/reload") }}'">
        <div class="row">
          <div class ="col-xs-1"> 
            <i class="material-icons reload">credit_card</i>
          </div>
         <div class ="col-xs-2">
            <p class="title">Reload Sales <span class="last-time"></br>Last 30 days</span></p>
         </div>
         <div class ="col-xs-9 mx-auto">
            <h3 class="reload-sales">
            @if($reloadsales != '')
              {{ number_format($reloadsales,2) }}
            @else 
              {{ '0.00' }} 
            @endif
          </h3>
         </div>
        </div>
      </div>
      <div class="col m-1" id="new-sales" onclick="window.location='{{ url("logs/sales") }}'">
        <div class="row">
          <div class ="col-xs-1"> 
            <i class="material-icons add_shopping_cart">add_shopping_cart</i>
          </div>
         <div class ="col-xs-2">
            <p class="title">Product Sales <span class="last-time"></br>Last 30 days</span></p>
         </div>
         <div class ="col-xs-9 mx-auto">
            <h3 class="product-sales">
              @if($sales != '')
              {{ number_format($sales,2) }}
            @else 
              {{ '0.00' }} 
            @endif
            </h3>
         </div>
       </div> 
      </div>
      <div class="col m-1" id="new-low" onclick="window.location='{{ url("services/products") }}'">
        <div class="row">
          <div class ="col-xs-1"> 
            <i class="material-icons trending_down">trending_down</i>
          </div>
         <div class ="col-xs-2">
            <p class="title">Low Stock Products <span class="last-time"></br>As of {{\Carbon\Carbon::now()->format('F d')}}</span></p>
         </div>
         <div class ="col-xs-9 mx-auto">
            <h3 class="low-stock">{{$lowstock}} </h3>
          </div>
        </div>
      </div>
   </div>

   <div class="row">
      <div class="col m-1 border" id="member-walkin">
        <!--  <h6>Member vs Walk-in</h6> -->
         <canvas id="salestoday">
         </canvas>  
      </div>
      <div class="col m-1 border" id="sales-day">
         <!-- <h6>Sales for {{ \Carbon\Carbon::now()->format('Y')}}</h6> -->
         <canvas id="sales-for-year">
         </canvas>
      </div>
   </div>
   <div class="row">
      <div class="col m-1 border" id="machines-stat">
         <canvas id="machinestat">
         </canvas>  
      </div>
   </div>

    <div class="row">
      <div class="col m-1 border" id="top-products">
        <center><h6>Machine Cycle Statistics</h6></center>
        <div class="col-md-12">
          <div class="row">
            <div class="btn-group mx-auto">
              <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                1 Week
              </button>
              <div class="dropdown-menu">
                <li class="dropdown-item">1 Week</li>
                <li class="dropdown-item">1 Month</li>
                <li class="dropdown-item">1 Year</li>
              </div>
            </div>
          </div>
        </div>
        <br>
        
        <div class="col-md-12">
          <div class="row">
            <table class="oneweek">
              <tr>
                <th class="text-center label-product">Machine Number</th>
                <th class="text-center label-total">Washer Count</th>
                <th class="text-center label-total">Dryer Count</th>
              </tr>
              <tr>
                <td class="text-center">1</td>
                <td class="text-center"><?php echo ($washerOneStats_weekly === null) ? 0 : $washerOneStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerOneStats_weekly === null) ? 0 : $dryerOneStats_weekly ?></td>
              </tr>
              <tr>
                <td class="text-center">2</td>
                <td class="text-center"><?php echo ($washerTwoStats_weekly === null) ? 0 : $washerTwoStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerTwoStats_weekly === null) ? 0 : $dryerTwoStats_weekly ?></td>
              </tr>
              <tr>
                <td class="text-center">3</td>
                <td class="text-center"><?php echo ($washerThreeStats_weekly === null) ? 0 : $washerThreeStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerThreeStats_weekly === null) ? 0 : $dryerThreeStats_weekly ?></td>
              </tr>
              <tr>
                <td class="text-center">4</td>
                <td class="text-center"><?php echo ($washerFourStats_weekly === null) ? 0 : $washerFourStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerFourStats_weekly === null) ? 0 : $dryerFourStats_weekly ?></td>
              </tr>
              <tr>
                <td class="text-center">5</td>
                <td class="text-center"><?php echo ($washerFiveStats_weekly === null) ? 0 : $washerFiveStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerFiveStats_weekly === null) ? 0 : $dryerFiveStats_weekly ?></td>
              </tr>
              <tr>
                <td class="text-center">6</td>
                <td class="text-center"><?php echo ($washerSixStats_weekly === null) ? 0 : $washerSixStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerSixStats_weekly === null) ? 0 : $dryerSixStats_weekly ?></td>
              </tr>
              <tr>
                <td class="text-center">7</td>
                <td class="text-center"><?php echo ($washerSevenStats_weekly === null) ? 0 : $washerSevenStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerSevenStats_weekly === null) ? 0 : $dryerSevenStats_weekly ?></td>
              </tr>
              <tr>
                <td class="text-center">8</td>
                <td class="text-center"><?php echo ($washerEightStats_weekly === null) ? 0 : $washerEightStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerEightStats_weekly === null) ? 0 : $dryerEightStats_weekly ?></td>
              </tr>
              <tr>
                <td class="text-center">9</td>
                <td class="text-center"><?php echo ($washerNineStats_weekly === null) ? 0 : $washerNineStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerNineStats_weekly === null) ? 0 : $dryerNineStats_weekly ?></td>
              </tr>
              <tr>
                <td class="text-center">10</td>
                <td class="text-center"><?php echo ($washerTenStats_weekly === null) ? 0 : $washerTenStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerTenStats_weekly === null) ? 0 : $dryerTenStats_weekly ?></td>
              </tr>
              <tr>
                <td class="text-center">11</td>
                <td class="text-center"><?php echo ($washerElevenStats_weekly === null) ? 0 : $washerElevenStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerElevenStats_weekly === null) ? 0 : $dryerElevenStats_weekly ?></td>
              </tr>
              <tr>
                <td class="text-center">12</td>
                <td class="text-center"><?php echo ($washerTwelveStats_weekly === null) ? 0 : $washerTwelveStats_weekly ?></td>
                <td class="text-center"><?php echo ($dryerTwelveStats_weekly === null) ? 0 : $dryerTwelveStats_weekly ?></td>
              </tr>
            </table>


            <table class="onemonth">
              <tr>
                <th class="text-center">Machine Number</th>
                <th class="text-center label-total">Washer Count</th>
                <th class="text-center label-total">Dryer Count</th>
              </tr>
              <tr>
                <td class="text-center">1</td>
                <td class="text-center"><?php echo ($washerOneStats_monthly === null) ? 0 : $washerOneStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerOneStats_monthly === null) ? 0 : $dryerOneStats_monthly ?></td>
              </tr>
              <tr>
                <td class="text-center">2</td>
                <td class="text-center"><?php echo ($washerTwoStats_monthly === null) ? 0 : $washerTwoStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerTwoStats_monthly === null) ? 0 : $dryerTwoStats_monthly ?></td>
              </tr>
              <tr>
                <td class="text-center">3</td>
                <td class="text-center"><?php echo ($washerThreeStats_monthly === null) ? 0 : $washerThreeStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerThreeStats_monthly === null) ? 0 : $dryerThreeStats_monthly ?></td>
              </tr>
              <tr>
                <td class="text-center">4</td>
                <td class="text-center"><?php echo ($washerFourStats_monthly === null) ? 0 : $washerFourStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerFourStats_monthly === null) ? 0 : $dryerFourStats_monthly ?></td>
              </tr>
              <tr>
                <td class="text-center">5</td>
                <td class="text-center"><?php echo ($washerFiveStats_monthly === null) ? 0 : $washerFiveStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerFiveStats_monthly === null) ? 0 : $dryerFiveStats_monthly ?></td>
              </tr>
              <tr>
                <td class="text-center">6</td>
                <td class="text-center"><?php echo ($washerSixStats_monthly === null) ? 0 : $washerSixStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerSixStats_monthly === null) ? 0 : $dryerSixStats_monthly ?></td>
              </tr>
              <tr>
                <td class="text-center">7</td>
                <td class="text-center"><?php echo ($washerSevenStats_monthly === null) ? 0 : $washerSevenStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerSevenStats_monthly === null) ? 0 : $dryerSevenStats_monthly ?></td>
              </tr>
              <tr>
                <td class="text-center">8</td>
                <td class="text-center"><?php echo ($washerEightStats_monthly === null) ? 0 : $washerEightStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerEightStats_monthly === null) ? 0 : $dryerEightStats_monthly ?></td>
              </tr>
              <tr>
                <td class="text-center">9</td>
                <td class="text-center"><?php echo ($washerNineStats_monthly === null) ? 0 : $washerNineStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerNineStats_monthly === null) ? 0 : $dryerNineStats_monthly ?></td>
              </tr>
              <tr>
                <td class="text-center">10</td>
                <td class="text-center"><?php echo ($washerTenStats_monthly === null) ? 0 : $washerTenStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerTenStats_monthly === null) ? 0 : $dryerTenStats_monthly ?></td>
              </tr>
              <tr>
                <td class="text-center">11</td>
                <td class="text-center"><?php echo ($washerElevenStats_monthly === null) ? 0 : $washerElevenStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerElevenStats_monthly === null) ? 0 : $dryerElevenStats_monthly ?></td>
              </tr>
              <tr>
                <td class="text-center">12</td>
                <td class="text-center"><?php echo ($washerTwelveStats_monthly === null) ? 0 : $washerTwelveStats_monthly ?></td>
                <td class="text-center"><?php echo ($dryerTwelveStats_monthly === null) ? 0 : $dryerTwelveStats_monthly ?></td>
              </tr>
            </table>

            <table class="oneyear">
              <tr>
                <th class="text-center">Machine Number</th>
                <th class="text-center label-total">Washer Count</th>
                <th class="text-center label-total">Dryer Count</th>
              </tr>
              <tr>
                <td class="text-center">1</td>
                <td class="text-center"><?php echo ($washerOneStats_yearly === null) ? 0 : $washerOneStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerOneStats_yearly === null) ? 0 : $dryerOneStats_yearly ?></td>
              </tr>
              <tr>
                <td class="text-center">2</td>
                <td class="text-center"><?php echo ($washerTwoStats_yearly === null) ? 0 : $washerTwoStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerTwoStats_yearly === null) ? 0 : $dryerTwoStats_yearly ?></td>
              </tr>
              <tr>
                <td class="text-center">3</td>
                <td class="text-center"><?php echo ($washerThreeStats_yearly === null) ? 0 : $washerThreeStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerThreeStats_yearly === null) ? 0 : $dryerThreeStats_yearly ?></td>
              </tr>
              <tr>
                <td class="text-center">4</td>
                <td class="text-center"><?php echo ($washerFourStats_yearly === null) ? 0 : $washerFourStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerFourStats_yearly === null) ? 0 : $dryerFourStats_yearly ?></td>
              </tr>
              <tr>
                <td class="text-center">5</td>
                <td class="text-center"><?php echo ($washerFiveStats_yearly === null) ? 0 : $washerFiveStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerFiveStats_yearly === null) ? 0 : $dryerFiveStats_yearly ?></td>
              </tr>
              <tr>
                <td class="text-center">6</td>
                <td class="text-center"><?php echo ($washerSixStats_yearly === null) ? 0 : $washerSixStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerSixStats_yearly === null) ? 0 : $dryerSixStats_yearly ?></td>
              </tr>
              <tr>
                <td class="text-center">7</td>
                <td class="text-center"><?php echo ($washerSevenStats_yearly === null) ? 0 : $washerSevenStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerSevenStats_yearly === null) ? 0 : $dryerSevenStats_yearly ?></td>
              </tr>
              <tr>
                <td class="text-center">8</td>
                <td class="text-center"><?php echo ($washerEightStats_yearly === null) ? 0 : $washerEightStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerEightStats_yearly === null) ? 0 : $dryerEightStats_yearly ?></td>
              </tr>
              <tr>
                <td class="text-center">9</td>
                <td class="text-center"><?php echo ($washerNineStats_yearly === null) ? 0 : $washerNineStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerNineStats_yearly === null) ? 0 : $dryerNineStats_yearly ?></td>
              </tr>
              <tr>
                <td class="text-center">10</td>
                <td class="text-center"><?php echo ($washerTenStats_yearly === null) ? 0 : $washerTenStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerTenStats_yearly === null) ? 0 : $dryerTenStats_yearly ?></td>
              </tr>
              <tr>
                <td class="text-center">11</td>
                <td class="text-center"><?php echo ($washerElevenStats_yearly === null) ? 0 : $washerElevenStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerElevenStats_yearly === null) ? 0 : $dryerElevenStats_yearly ?></td>
              </tr>
              <tr>
                <td class="text-center">12</td>
                <td class="text-center"><?php echo ($washerTwelveStats_yearly === null) ? 0 : $washerTwelveStats_yearly ?></td>
                <td class="text-center"><?php echo ($dryerTwelveStats_yearly === null) ? 0 : $dryerTwelveStats_yearly ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div class="col m-1 border" id="sales-payment">
        <canvas id="payment-mode">
        </canvas>
      </div>
      <div class="col m-1 border" id="active-members">
         <center><h6>Most Active Members</h6></center>
        <br>
        @if(count($topmembers) != 0)
          <div class="row">
            <div class="col">
              <h5 class="label-product">Member</h5>
            </div>
            <div class="col">
              <h5 class="label-total">Total Purchase</h5>
            </div>
          </div>
            @foreach($topmembers as $member)
              @if($loop->first)
                <div class="row">
                  <div class="col">
                    <h4>{{$loop->iteration . ". " . $member->name}}</h4>
                  </div>
                  <div class="col">
                    <h2 class="top-product-price">₱ {{number_format($member->amount_due,2)}}</h2>
                  </div>
                </div>
              @else
                <div class="row">
                  <div class="col">
                    <h6 class="label-product2">{{$loop->iteration . ". " . $member->name}}</h6>
                  </div>
                  <div class="col">
                    <h6 class="label-total2">₱ {{number_format($member->amount_due,2)}}</h6>
                  </div>
                </div>
              @endif
            @endforeach
          @else
          <div class="row">
            <div class="col">
              <h4 class="text-center"> No Member Sales </h4>
            </div>
          </div>
          @endif
      </div>
    </div>

</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('.onemonth').hide();
    $('.oneyear').hide();
  });

  $(".dropdown-menu li").click(function(){
    var selText = $(this).text();
    $(this).parents('.btn-group').find('.dropdown-toggle').html(selText+' <span class="caret"></span>');

    if(selText === '1 Month') {
      $('.onemonth').show();
      $('.oneweek').hide();
      $('.oneyear').hide();
    } else if(selText === '1 Week') {
      $('.oneweek').show();
      $('.onemonth').hide();
      $('.oneyear').hide();
    } else if(selText === '1 Year') {
      $('.oneyear').show();
      $('.oneweek').hide();
      $('.onemonth').hide();
    }
  });

   var bar_ctx = document.getElementById('machinestat').getContext('2d');
   var areagradient = bar_ctx.createLinearGradient(0, 0, 0, 450);
   areagradient.addColorStop(0, '#3BD0C0');
   areagradient.addColorStop(1, '#FFFFFF');
   var year = <?php echo \Carbon\Carbon::now()->format('Y') ?>;
   new Chart(document.getElementById("machinestat"), {
    type: 'line',
    data: {
         labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
         datasets: [
         {
            data: <?php echo json_encode($washerOneStats)?>,
            label: "Washer 1",
            borderWidth: 3,
            borderColor: "#e6194b",
            fill: false,
         }, 
         {
            data: <?php echo json_encode($washerTwoStats)?>,
            label: "Washer 2",
            borderWidth: 3,
            borderColor: "#3cb44b",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($washerThreeStats)?>,
            label: "Washer 3",
            borderWidth: 3,
            borderColor: "#3cba9f",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($washerFourStats)?>,
            label: "Washer 4",
            borderWidth: 3,
            borderColor: "#ffe119",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($washerFiveStats)?>,
            label: "Washer 5",
            borderWidth: 3,
            borderColor: "#0082c8",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($washerSixStats)?>,
            label: "Washer 6",
            borderWidth: 3,
            borderColor: "#f58231",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($washerSevenStats)?>,
            label: "Washer 7",
            borderWidth: 3,
            borderColor: "#911eb4",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($washerEightStats)?>,
            label: "Washer 8",
            borderWidth: 3,
            borderColor: "#46f0f0",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($washerNineStats)?>,
            label: "Washer 9",
            borderWidth: 3,
            borderColor: "#f032e6",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($washerTenStats)?>,
            label: "Washer 10",
            borderWidth: 3,
            borderColor: "#d2f53c",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($washerElevenStats)?>,
            label: "Washer 11",
            borderWidth: 3,
            borderColor: "#fabebe",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($washerTwelveStats)?>,
            label: "Washer 12",
            borderWidth: 3,
            borderColor: "#008080",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($dryerOneStats)?>,
            label: "Dryer 1",
            borderWidth: 3,
            borderColor: "#e6beff",
            fill: false,
            hidden: true
         }, 
         {
            data: <?php echo json_encode($dryerTwoStats)?>,
            label: "Dryer 2",
            borderWidth: 3,
            borderColor: "#aa6e28",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($dryerThreeStats)?>,
            label: "Dryer 3",
            borderWidth: 3,
            borderColor: "#fffac8",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($dryerFourStats)?>,
            label: "Dryer 4",
            borderWidth: 3,
            borderColor: "#e8c3b9",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($dryerFiveStats)?>,
            label: "Dryer 5",
            borderWidth: 3,
            borderColor: "#800000",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($dryerSixStats)?>,
            label: "Dryer 6",
            borderWidth: 3,
            borderColor: "#aaffc3",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($dryerSevenStats)?>,
            label: "Dryer 7",
            borderWidth: 3,
            borderColor: "#808000",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($dryerEightStats)?>,
            label: "Dryer 8",
            borderWidth: 3,
            borderColor: "#c45850",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($dryerNineStats)?>,
            label: "Dryer 9",
            borderWidth: 3,
            borderColor: "#ffd8b1",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($dryerTenStats)?>,
            label: "Dryer 10",
            borderWidth: 3,
            borderColor: "#000080",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($dryerElevenStats)?>,
            label: "Dryer 11",
            borderWidth: 3,
            borderColor: "#808080",
            fill: false,
            hidden: true
         },
         {
            data: <?php echo json_encode($dryerTwelveStats)?>,
            label: "Dryer 12",
            borderWidth: 3,
            borderColor: "#000000",
            fill: false,
            hidden: true
         },


         ]
      },
      options: {
         title: {
            display: true,
            text: 'Machine Statistics for Year ' + year,
            fontSize: '17',
            fontColor: 'black',
            fontStyle: 'normal',
            fontFamily: 'Montserrat',
         },
         plugins: {
            filler: {
                propagate: true
            }
         },
         scales: {
            yAxes: [{
                  display: true,
                  ticks: {
                      beginAtZero: true,
                      steps: 1000,
                      stepValue: 5,
                  }
              }],
            xAxes: [{
               ticks: {
                  autoSkip: false
               }
            }]
         },
         responsive:true,
         maintainAspectRatio: false,
         legend: {
            display: true
         },
       }
   });

   var bar_ctx = document.getElementById('payment-mode').getContext('2d');
   var cashgradient = bar_ctx.createLinearGradient(0, 0, 0, 600);
   cashgradient.addColorStop(0, '#7ef6b8');
   cashgradient.addColorStop(1, '#66ca72');

   var cardgradient = bar_ctx.createLinearGradient(0, 0, 0, 600);
   cardgradient.addColorStop(0, '#f4b591');
   cardgradient.addColorStop(1, '#eb5757');

   var cash = <?php echo $cashpay; ?>;
   var load = <?php echo $loadpay; ?>;
   new Chart(document.getElementById("payment-mode"), {
    type: 'doughnut',
    data: {
      labels: ["Cash", "Card Load"],
      datasets: [
        {
          backgroundColor: [cashgradient, cardgradient],
          hoverBackgroundColor: [cashgradient, cardgradient],
          hoverBorderWidth: 2,
          hoverBorderColor: [cashgradient, cardgradient],
          data: [cash,load]
        }
      ]
    },
    options: {
      title: {
         display: true,
         text: 'Sales by Payment Mode',
         fontSize: '16',
         fontColor: 'black',
         fontStyle: 'normal',
         fontFamily: 'Montserrat',
      },
      cutoutPercentage: 70,
      responsive:true,
      maintainAspectRatio: false,
    }
   });

   var year = '<?php echo \Carbon\Carbon::now()->format('F d, Y') ?>'
   var areagradient = bar_ctx.createLinearGradient(0, 0, 0, 450);
   areagradient.addColorStop(0, '#3BD0C0');
  areagradient.addColorStop(1, '#FFFFFF');
    new Chart(document.getElementById("salestoday"), {
    type: 'line',
    data: {
         labels: ["00:00-00:59","1:00-1:59","2:00-2:59","3:00-3:59","4:00-4:59","5:00-5:59","6:00-6:59","7:00-7:59","8:00-8:59","9:00-9:59","10:00-10:59", "11:00-11:59", "12:00-12:59", "13:00-13:59", "14:00-14:59", "15:00-15:59", "16:00-16:59", "17:00-17:59", "18:00-18:59", "19:00-19:59","20:00-20:59","21:00-21:59","22:00-22:59","23:00-23:59",],
         datasets: [{
            data: <?php echo json_encode($salestoday)?>,
            label: "",
            borderWidth: 2,
            borderColor: "#3e95cd",
            fill: 'start',
            backgroundColor:  areagradient,
         }]
      },
      options: {
         title: {
            display: true,
            text: 'Product Sales for Today - ' + year,
            fontSize: '17',
            fontColor: 'black',
            fontStyle: 'normal',
            fontFamily: 'Montserrat',
         },
         plugins: {
            filler: {
                propagate: true
            }
         },
         elements: {
           line: {
               tension: 0
           }
         },
         scales: {
            yAxes: [{
                  display: true,
                  ticks: {
                      beginAtZero: true,
                      steps: 1000,
                      stepValue: 5,
                  }
              }],
            xAxes: [{
               ticks: {
                  autoSkip: false
               }
            }]
         },
         responsive:true,
         maintainAspectRatio: false,
         legend: {
            display: false
         },
      },

   });


   var year = <?php echo \Carbon\Carbon::now()->format('Y') ?>;
   var areagradient = bar_ctx.createLinearGradient(0, 0, 0, 450);
   areagradient.addColorStop(0, '#3BD0C0');
  areagradient.addColorStop(1, '#FFFFFF');
    new Chart(document.getElementById("sales-for-year"), {
    type: 'line',
    data: {
         labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
         datasets: [{
            data: <?php echo json_encode($yearsales)?>,
            label: "",
            borderWidth: 2,
            borderColor: "#3e95cd",
            fill: 'start',
            backgroundColor:  areagradient,
         }]
      },
      options: {
         title: {
            display: true,
            text: 'Product Sales for ' + year,
            fontSize: '17',
            fontColor: 'black',
            fontStyle: 'normal',
            fontFamily: 'Montserrat',
         },
         plugins: {
            filler: {
                propagate: true
            }
         },
         elements: {
           line: {
               tension: 0
           }
         },
         scales: {
            yAxes: [{
                  display: true,
                  ticks: {
                      beginAtZero: true,
                      steps: 1000,
                      stepValue: 5,
                  }
              }],
            xAxes: [{
               ticks: {
                  autoSkip: false
               }
            }]
         },
         responsive:true,
         maintainAspectRatio: false,
         legend: {
            display: false
         },
      },

   });

   
</script>
@endsection