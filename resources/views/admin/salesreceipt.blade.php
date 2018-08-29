<html>
<head>
  <title>RECEIPT</title>
  <meta name="viewport" content="width=device-width", initial-scale="1.0">
  <link href="{{ asset('imports/css/font.css') }}" rel="stylesheet"> 
  <link rel="stylesheet" type="text/css" href="{{ asset('imports/css/receipt.css') }}"/>

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link rel="stylesheet" href="{{ asset('imports/css/bootstrap.min.css') }}">
  <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous"> -->
  <link href="{{ asset('imports/css/materialicon.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('imports/css/daterangepicker.css') }}" />
  <link href="{{ asset('imports/css/font.css') }}" rel="stylesheet"> 
  
  <script type="text/javascript" src="{{ asset('imports/js/jquery.min.js') }}"></script>
  <script src="{{ asset('imports/js/jquery1-11-1.min.js') }}"></script>
  <script src="{{ asset('imports/js/popper.min.js') }}"></script>
  <script src="{{ asset('imports/js/bootstrap.min.js') }}"></script>
  </head>

<body>
<input type="text" id="vat_amount" value="{{$sales->vat}}" hidden="hidden">
<div class="container mx-auto border">
    <br>
    <p>{{$profile->branch_name}}</p>
    <p class="address">{{$profile->address}}</p>
    <table class="table table-borderless">
      <tbody>
        <tr>
          <td>Receipt Number:</td>
          <td>{{sprintf('%08d',$sales->id)}}</td>
        </tr>

        <tr>
          <td>Cashier Name:</td>
          <td>{{$cashier->firstname}}</td>
        </tr>

        <tr>
          <td>{{date('F d, Y', strtotime($sales->transaction_date))}}</td>
          <td>{{date('h:i:s A', strtotime($sales->transaction_date))}}</td>
        </tr>
      </tbody>
    </table>

    <hr>

    <table class="table table-borderless">
      <thead>
        <th>Description</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Subtotal</th>
      </thead>
      
    @foreach($salesdetails as $details)
      <tr class="productdetails items">
        <td>{{$details->product->product_name}}</td>
        <td>{{$details->quantity}}</td>
        <td>{{$details->product->price}}</td>
        <td class="totalprice">{{number_format($details->subtotal * $details->quantity,2, '.', '')}}</td>
      </tr>
    @endforeach

    <tbody class="computation">
      <tr class="subtotal">
        <td>Subtotal</td>
        <td></td>
        <td></td>
        <td><span class="subtotalprice"></span></td>
      </tr>
    </tbody>

    <tbody class="computation">
      <tr class="vatheader">
        <td>VAT</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </tbody>

    <tbody>
      <tr>
        <td></td>
        <td>{{floatval($sales->vat)}}% VAT</td>
        <td></td>
        <td><span class="vatvalue"></span></td>
      </tr>
      <tr>
        <td></td>
        <td>VAT Sale</td>
        <td></td>
        <td><span class="vatsale"></span></td>
      </tr>
      <tr>
        <td></td>
        <td>VAT Exempt Sales</td>
        <td></td>
        <td><span class="vatexempt"></span></td>
      </tr>
      <tr>
        <td></td>
        <td>Zero-Rated</td>
        <td></td>
        <td><span class="zerorated"></span></td>
      </tr>
    </tbody>
  @if(!empty($discounts))
      <input type="text" id="discount_id" value="{{$discounts->id}}" hidden="hidden">
      <input type="text" id="discount_name" value="{{$discounts->discount_name}}" hidden="hidden">
      <input type="text" id="discount_type" value="{{$discounts->discount_type}}" hidden="hidden">
      <input type="text" id="discount_value" value="{{$discounts->discount_value}}" hidden="hidden">
      
      <tbody class="computation">
        <tr class="vatheader">
          <td>Discount</td>
          <td><span class="discount_name"></span></td>
          <td></td>
          <td><span class="discount"></span></td>
        </tr>
      </tbody>
  @else
    <tbody class="computation">
        <tr class="vat">
          <td>Discount</td>
          <td><span class="discount_name"></span></td>
          <td></td>
          <td>0.00</td>
        </tr>
      </tbody>
    @endif
  
  <tbody class="computation">
      <tr class="total">
        <td>Total</td>
        <td></td>
        <td></td>
        <td><span class="totalpayment"></span></td>
      </tr>
      
    @if($sales->payment_mode == 'cash')
      <tr>
        <td>Amount Paid</td>
        <td></td>
        <td></td>
        <td>{{number_format($sales->amount_paid,2, '.', '')}}<span class="payment"></span></td>
      </tr>
       <tr>
        <td>Change</td>
        <td></td>
        <td></td>
        <td>{{number_format($sales->change_amount,2, '.', '')}} <span class="change"></span></td>
      </tr>
    @else
      <tr>
        <td>Load Deducted</td>
        <td></td>
        <td></td>
        <td>{{number_format($sales->amount_paid,2, '.', '')}}<span class="payment"></span></span></td>
      </tr>
    @endif

    </tbody>
    </table>

</div>
<script type="text/javascript">
  var sum = 0;

  $('.totalprice').each(function() {
     sum += parseFloat($(this).text());
  });
  $('.subtotalprice').text(sum.toFixed(2));

  if($('#discount_id').val() == 1)
  {
    var subtotal = $('.subtotalprice').text();
    var discount_name = $('#discount_name').val();
    var discount_percent = $('#discount_value').val() * 100;
    var vat_amount = $('#vat_amount').val();
    var vat_percent = (100 + parseFloat(vat_amount)) / 100;
    var zero = 0;

    $('.vatvalue').text(zero.toFixed(2));
    $('.vatsale').text(zero.toFixed(2));

    var vatexempt =  subtotal / vat_percent;
    $('.vatexempt').text(vatexempt.toFixed(2));

    $('.zerorated').text(zero.toFixed(2));

    var percent = parseFloat($('#discount_value').val());
    var discount = (vatexempt * percent);
    $('.discount').text(discount.toFixed(2));

    var total = vatexempt -  discount;
    $('.totalpayment').text(total.toFixed(2));

    $('.discount_name').text( discount_name + ' ' + discount_percent + '%');
  }
  else if($('#discount_id').val() == undefined)
  {
      var zero = 0;
      var subtotal = $('.subtotalprice').text();
      var vat_amount = $('#vat_amount').val();
      var vat_percent = (100 + parseFloat(vat_amount)) / 100;
      var vat_pointpercent = parseFloat(vat_amount) / 100;
      var vatsale =  subtotal / vat_percent;
      var vat = vatsale * vat_pointpercent;
      var discount_name = $('#discount_name').val();
      var discount_type = $('#discount_type').val();
      var discount_value = $('#discount_value').val();

      $('.vatvalue').text(vat.toFixed(2));
      $('.vatsale').text(vatsale.toFixed(2));
      $('.vatexempt').text(zero.toFixed(2));
      $('.zerorated').text(zero.toFixed(2));

      var total = subtotal;
      $('.totalpayment').text(total);
  }
  else
  {
    var zero = 0;
    var subtotal = $('.subtotalprice').text();
    var vat_amount = $('#vat_amount').val();
    var vat_percent = (100 + parseFloat(vat_amount)) / 100;
    var vat_pointpercent = parseFloat(vat_amount) / 100;
    var vatsale =  subtotal / vat_percent;
    var vat = vatsale * vat_pointpercent;
    var discount_name = $('#discount_name').val();
    var discount_type = $('#discount_type').val();
    var discount_value = $('#discount_value').val();

    $('.vatvalue').text(parseFloat(vat).toFixed(2));
    $('.vatsale').text(vatsale.toFixed(2));
    $('.vatexempt').text(zero.toFixed(2));
    $('.zerorated').text(zero.toFixed(2));

    if(discount_type == 'deduction')
    {
      var discount = discount_value;
      $('.discount').text(discount);

      var total = subtotal -  discount;
      if(total > 0)
      {
        $('.totalpayment').text(total.toFixed(2));
      }
      else
      {
        $('.totalpayment').text(zero.toFixed(2));
      }

      $('.discount_name').text(discount_name);
    }
    else if(discount_type = 'percentage')
    {
      var discount_percent = $('#discount_value').val() * 100;
      var percent = parseFloat($('#discount_value').val());
      var discount = subtotal * percent;  
      $('.discount').text(discount.toFixed(2));

      var total = subtotal -  discount;
      if(total > 0)
      {
        $('.totalpayment').text(total.toFixed(2));
      }
      else
      {
        $('.totalpayment').text(zero.toFixed(2));
      }

      $('.discount_name').text(discount_name + ' ' + discount_percent + '%');
    }
  }
</script>
</body>
</html>