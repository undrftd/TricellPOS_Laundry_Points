  <input type="text" id="vat_amount" value="{{$sales->vat}}" hidden="hidden">
  <table class="table date-header">
    <thead>
      <th>Receipt #{{sprintf('%08d',$sales->id)}}</td>
      <th></td>
      <th>{{date('F d, Y', strtotime($sales->transaction_date))}}</td>
      <th>{{date('h:i:s A', strtotime($sales->transaction_date))}}</td>
    </thead>
  </table>
	<table class="table table_modal">
  <thead>
    <tr>
      <th scope="col">Description</th>
      <th scope="col">Qty</th>
      <th scope="col">Price</th>
      <th scope="col">Subtotal</th>
    </tr>
  </thead>
  <tbody>
    @foreach($salesdetails as $details)
    <tr class="productdetails">
      <td>{{$details->product->product_name}}</td>
      <td>{{$details->quantity}}</td>
      <td>₱ {{$details->product->price}}</td>
      <td class="totalprice">₱ {{number_format($details->subtotal * $details->quantity,2, '.', '')}}</td>
    </tr>
    @endforeach

  <!--<tr class="table-light">
      <td colspan="3"></td>
      <td colspan="2"></td>
  </tr>-->
	<tr class="table-light">
      <td colspan="3"><b>Subtotal</b></td>
      <td colspan="2">₱ <span class="subtotal"></span></td>
  </tr>
	<tr class="clickable table-light" data-toggle="collapse" data-target="#group-vat" aria-expanded="false" aria-controls="group-vat">
      <td colspan="3"><b> VAT</b> &#9660; </td>
      <td colspan="2"></td>
  </tr>
  <tbody id="group-vat" class="collapse">
  <tr class="table-light">
      <td colspan="3">&emsp;<b>{{floatval($sales->vat)}}% VAT</b></td>
      <td colspan="2">₱ <span class="vat"></span></td>
  </tr>
  <tr class="table-light">
      <td colspan="3">&emsp;<b>VAT Sale</b></td>
      <td colspan="2">₱ <span class="vatsale"></span></td>
  </tr>
  <tr class="table-light">
      <td colspan="3">&emsp;<b>VAT Exempt Sales</b></td>
      <td colspan="2">₱ <span class="vatexempt"></span></td>
  </tr>
  <tr class="table-light">
      <td colspan="3">&emsp;<b>Zero-Rated</b></td>
      <td colspan="2">₱ <span class="zerorated"></span></td>
  </tr>
</tbody>
  @if(isset($discounts))
    <input type="text" id="discount_id" value="{{$discounts->id}}" hidden="hidden">
    <input type="text" id="discount_name" value="{{$discounts->discount_name}}" hidden="hidden">
    <input type="text" id="discount_type" value="{{$discounts->discount_type}}" hidden="hidden">
    <input type="text" id="discount_value" value="{{$discounts->discount_value}}" hidden="hidden">
  	<tr class="table-light">
        <td colspan="3"><b>Discount <span class="discount_name"></span></b></td>
        <td colspan="2">₱ <span class="discount"></span></td>
    </tr>
  @else
    <tr class="table-light">
        <td colspan="3"><b>Discount <span class="discount_name"></span></b></td>
        <td colspan="2">₱ 0.00</td>
    </tr>
  @endif


	<tr class="table-light">
      <td colspan="3"><b>Total</b></td>
      <td colspan="2">₱ <span class="total"></span></td>
  </tr>

  @if($sales->payment_mode == 'cash')
    <tr class="table-light">
        <td colspan="3"><b>Amount Paid</b></td>
        <td colspan="2">₱ {{number_format($sales->amount_paid,2, '.', '')}}<span class="payment"></span></td>
    </tr>
    <tr class="table-light">
        <td colspan="3"><b>Change</b></td>
        <td colspan="2">₱ {{number_format($sales->change_amount,2, '.', '')}} <span class="change"></span></td>
    </tr>
  @else
    <tr class="table-light">
        <td colspan="3"><b>Load Deducted</b></td>
        <td colspan="2">₱ {{number_format($sales->amount_paid,2, '.', '')}}<span class="payment"></span></td>
    </tr>
  @endif


	</tbody>

</table>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-info btn-save-modal" data-dismiss="modal">Print</button>
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
<script type="text/javascript">

</script>
