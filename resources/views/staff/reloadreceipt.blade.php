<html>
<head>
  <title>RECEIPT</title>
  <meta name="viewport" content="width=device-width", initial-scale="1.0">
  <link rel="stylesheet" type="text/css" href="{{ asset('imports/css/Receipt.css') }}"/>
  <link href="https://fonts.googleapis.com/css?family=Oxygen+Mono" rel="stylesheet">
    <!-- bootstrap -->

    <!-- bootstrap -->

   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
   <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
   <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
   <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
         rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  </head>

<body>
<div class="container mx-auto border">
    <br>
    <p>{{$profile->branch_name}}</p>
    <p class="address">{{$profile->address}}</p>
    <table class="table table-borderless">
      <tbody>
        <tr>
          <td>Receipt Number:</td>
          <td>{{sprintf('%08d',$reload->id)}}</td>
        </tr>

        <tr>
          <td>Cashier Name:</td>
          <td>{{$cashier->firstname}}</td>
        </tr>

        <tr>
          <td>{{date('F d, Y', strtotime($reload->transaction_date))}}</td>
          <td>{{date('h:i:s A', strtotime($reload->transaction_date))}}</td>
        </tr>
      </tbody>
    </table>

    <hr>

    <table class="table table-borderless">
      <thead>
        <th>Description</th>
        <th></th>
        <th>Price</th>
        <th>Subtotal</th>
      </thead>
      
      <tr class="productdetails items">
        <td>Reload</td>
        <td></td>
        <td>₱ {{number_format($reload->amount_due,2, '.', '')}}</td>
        <td class="totalprice">₱ {{number_format($reload->amount_due,2, '.', '')}}</td>
      </tr>

    <tbody class="computation">
      <tr class="subtotal">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </tbody>
  
    <tbody class="computation">
      <tr class="total">
        <td>Total</td>
        <td></td>
        <td></td>
        <td>₱ {{number_format($reload->amount_due,2, '.', '')}}</td>
      </tr>
    </tbody>

    <tbody class="computation">
      <tr class="amount_paid">
        <td>Amount Paid</td>
        <td></td>
        <td></td>
        <td>₱ {{number_format($reload->amount_paid,2, '.', '')}}</td>
      </tr>
    </tbody>

    <tbody class="computation">
      <tr class="amount_paid">
        <td>Change</td>
        <td></td>
        <td></td>
        <td>₱ {{number_format($reload->change_amount,2, '.', '')}}</td>
      </tr>
    </tbody>

    </tbody>
    </table>

</div>

</body>
</html>