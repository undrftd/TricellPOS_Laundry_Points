@extends('staff_layout')

@section('title')
BILLING
@endsection

@section('css')
{{ asset('imports/css/pos.css') }}
@endsection

@section('content')

<div class="container-fluid">
  @csrf
  <div class="row">
    <div class="col-lg-5 border" id="left">
      <div class="mx-auto">
        
        <div class="row">
          
          <table class="table main-total">
            <tbody>
              <tr>
                <th scope="row" class="table-dark" style="width: 73%" id="total">TOTAL</th>
                <td class="table-dark" id="total">₱ <span class="totalprice">0.00</span></td>
              </tr>
              
            </tbody>
          </table>
          
        </div>
        
        
        <div class="row items-table">
          <table class="table pos-table" id="display_table">
            <thead class="table-light">
              <tr>
                <th class="header">Description</th>
                <th class="header">Quantity</th>
                <th class="header">Price</th>
                <th class="header">Subtotal</th>
                <th class="header2"></th>
              </tr>
            </thead>
            
            <tbody>
            </tbody>
          </table>
        </div><!---end of row-->
          
          <div class="row">
            
            <table class="table table-total">
              <tbody>
                
                <tr>
                  <th scope="row" class="table-light" style="width: 73%">Subtotal</th>
                  <td class="table-light">₱ <span class="subtotal">0.00</td>
                </tr>
                <tr>
                  <th scope="row" class="table-light" style="width: 73%">Discount</th>
                  <td class="table-light"> 
                    <select class="form-control form-control-sm select-box-discount">
                      <option class="discountoption" data-name="No Discount" data-id="0" data-type="deduction" data-value="0" selected>No Discount</option>
                      @foreach($discounts as $discount)
                        @if($discount->discount_type=='percentage')
                          <option class="discountoption" data-id="{{$discount->id}}" data-name="{{$discount->discount_name}}" data-type="{{$discount->discount_type}}" data-value="{{$discount->discount_value}}">{{$discount->discount_name}} - {{$discount->discount_value * 100}}%</option>
                        @else
                          <option class="discountoption" data-id="{{$discount->id}}" data-name="{{$discount->discount_name}}" data-type="{{$discount->discount_type}}" data-value="{{$discount->discount_value}}">{{$discount->discount_name}} - ₱{{$discount->discount_value}}</option>
                        @endif
                      @endforeach
                    </select></td>
                </tr>
                 <tr>
                  <th scope="row" class="table-light" style="width: 73%"></th>
                  <td class="table-light"><span class="discountspanvalue">₱ 0.00</span></td>
                </tr>
                <tr>
                  <th scope="row" class="table-light" style="width: 73%">VAT</th>
                  <td class="table-light">{{floatval($vat->vat)}}%</td>
                </tr>
                
              </tbody>
            </table>
          </div>
          
          <div class="row" id="onchange">
            <select class="form-control form-control-sm select-box-role">
              <option value="member" selected>Member</option>
              <option value="guest">Walk-in</option>
            </select>
          </div>
          
          <div class="row" id="member">  
            <input type="text" class="form-control form-control-sm" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" id="member_input">
            <i class="material-icons" id="faces">faces</i>
            <p id="member-name">&nbsp;<span id="membername"></span></p>
            <i class="material-icons icon-align-right" id="date_range">date_range</i>
            <p align="right" id="date">{{date('M d, Y')}}</p>

            <i class="material-icons" id="faces">credit_card</i>
            <p id="member-name">&nbsp;<span id="memberspanload"></span></p>
            <i class="material-icons icon-align-right" id="date_range">stars</i>
            <p  align="right" id="date">&nbsp;<span id="memberspanpoints"></span></p>
          </div>
          
          <div class="row" id="guest">
            <input type="text" class="form-control form-control-sm" onkeypress="return alpha(event)" id="guest_input">
            <i class="material-icons icon-align-right" id="date_range_2">date_range</i>
            <p id="date_2">{{date('M d, Y')}}</p>
          </div>
          
          <div class="row">
            <table class="table">
              <tbody>
                <tr>
                  <th scope="row"  style="width: 60%" id="total"></th>
                  <td><button type="button" class="btn btn-secondary float-right payment-btn">Cash Payment</button></td>
                  <td>
                  <button type="button" class="btn btn-secondary  lpayment-btn">Load Payment</button></td>
                  <td>
                  <button type="button" class="btn btn-secondary  ppayment-btn">Points Payment</button>
                  </td>
              </tr>
            </tbody>
          </table>
          
          <!----start of modal for payment---->
          <div class="modal fade payment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Cash Payment</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <form>
                  <div class="form-group">
                    <div class="container-fluid">
                    <br>
                      <div class="form-group row mx-auto">
                      <label for="totalprice" class="col-form-label col-sm-5 modal-pay">Total Price:</label>

                        <div class="col-sm-7">
                          <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon-totalprice-cash">₱</span>
                            </div>
                            <input type="text" name="total-price" class="form-control modal-add" id="total-price-cash" disabled="disabled">
                          </div>

                          <p id="error-total-price-cash" class="error-pos" hidden="hidden"></p>
                        </div>
                      </div>

                      <div class="form-group row mx-auto">
                      <label for="paymentamount" class="col-form-label col-sm-5 modal-pay">Payment Amount:</label>

                        <div class="col-sm-7">
                          <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon-paymentamount-cash">₱</span>
                            </div>
                            <input type="text" name="paymentamount" class="form-control modal-add" id="payment-amount-cash">
                          </div>

                          <p id="error-payment-amount-cash" class="error-pos" hidden="hidden"></p>
                        </div>
                      </div>

                      <div class="form-group row mx-auto">
                      <label for="changeamount" class="col-form-label col-sm-5 modal-pay">Change:</label>

                        <div class="col-sm-7">
                          <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon-changeamount-cash">₱</span>
                            </div>
                            <input type="text" name="changeamount" class="form-control modal-add" id="change-amount-cash" disabled="disabled">
                          </div>
                          <p id="error-change-amount-cash" class="error-pos" hidden="hidden"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </form>
                <div class="modal-footer">
                  <button type="button" class="btn btn-info btn-savemem-modal" id="paysale">Pay</button>
                  <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </div>
          
          <!----end of modal---->
          <!----start of modal for load payment---->
          <div class="modal fade lpayment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Load Payment</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <form>
                  <div class="form-group">
                    <div class="container-fluid">
                      <br>
                      <div class="form-group row mx-auto">
                        <label for="currentload" class="col-form-label col-sm-5 modal-pay">Current Load:</label>

                          <div class="col-sm-7">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon-currentload-card">₱</span>
                              </div>
                              <input type="text" name="paymentamount" class="form-control modal-add" id="current-load-card" disabled="disabled">
                            </div>
                            <p id="error-current-load-card" class="error-pos" hidden="hidden"></p>
                          </div>
                        </div>

                        <div class="form-group row mx-auto">
                        <label for="loadpay" class="col-form-label col-sm-5 modal-pay">Amount to Pay:</label>

                          <div class="col-sm-7">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon-loadpay-card">₱</span>
                              </div>
                              <input type="text" name="paymentamount" class="form-control modal-add" id="loadpay-card" disabled="disabled">
                            </div>
                            <p id="error-loadpay-card" class="error-pos" hidden="hidden"></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  
                </form>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger btn-savemem-modal reload-btn" id="reload" hidden="hidden">Reload</button>
                  <button type="button" class="btn btn-info btn-savemem-modal" id="payload">Pay</button>
                  <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </div>

          <!----start of modal for load payment---->
          <div class="modal fade ppayment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Points Payment</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <form>
                  <div class="form-group">
                    <div class="container-fluid">
                      <br>
                      <div class="form-group row mx-auto">
                        <label for="currentpoints" class="col-form-label col-sm-5 modal-pay">Current Points:</label>

                          <div class="col-sm-7">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon-currentpoints-card">₱</span>
                              </div>
                              <input type="text" name="paymentamount" class="form-control modal-add" id="current-points-card" disabled="disabled">
                            </div>
                            <p id="error-current-points-card" class="error-pos" hidden="hidden"></p>
                          </div>
                        </div>

                        <div class="form-group row mx-auto">
                        <label for="pointspay" class="col-form-label col-sm-5 modal-pay">Amount to Pay:</label>

                          <div class="col-sm-7">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon-pointspay-card">₱</span>
                              </div>
                              <input type="text" name="paymentamount" class="form-control modal-add" id="pointspay-card" disabled="disabled">
                            </div>
                            <p id="error-pointspay-card" class="error-pos" hidden="hidden"></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  
                  <div class="col-7 offset-3">
                    <span id="lowpoints" class="low_stock text-center" hidden="hidden">You have insufficient points!</span>
                  </div>
                  
                </form>
                <div class="modal-footer">
                  <button type="button" class="btn btn-info btn-savemem-modal" id="paypoints">Pay</button>
                  <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </div>
          
          <!----end of modal---->
          <!----start of modal for reload---->
          <div class="modal fade reload" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Reload</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <form>
                  <div class="form-group">
                    <div class="container-fluid">
                    <br>
                      <div class="form-group row mx-auto">
                        <label for="points" class="col-form-label col-sm-5 modal-load">Current Load:</label>
                          <div class="col-sm-7">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon-load">₱</span>
                              </div>
                              <input type="text" name="load" class="form-control modal-add" id="load-reload" disabled="disabled">
                            </div>

                            <p id="error-load-reload" class="error-pos" hidden="hidden"></p>
                          </div>
                        </div>

                        <div class="form-group row mx-auto">
                        <label for="points" class="col-form-label col-sm-5 modal-points">Reload Amount:</label>
                          <div class="col-sm-7">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon-reload-amount">₱</span>
                              </div>
                              <input type="text" name="reload-amount" class="form-control modal-add" id="reload-amount-reload" autocomplete="off">
                            </div>

                            <p id="error-reload-amount-reload" class="error-pos" hidden="hidden"></p>
                          </div>
                        </div>

                        <div class="form-group row mx-auto">
                        <label for="points" class="col-form-label col-sm-5 modal-load">Payment Amount:</label>

                          <div class="col-sm-7">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon-payment-amount">₱</span>
                              </div>
                              <input type="text" name="payment-amount" class="form-control modal-add" id="payment-amount-reload" autocomplete="off">
                            </div>

                            <p id="error-payment-amount-reload" class="error-pos" hidden="hidden"></p>
                          </div>
                        </div>

                        <div class="form-group row mx-auto">
                        <label for="points" class="col-form-label col-sm-5 modal-load">Change:</label>

                          <div class="col-sm-7">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon-change">₱</span>
                              </div>
                              <input type="text" name="change" class="form-control modal-add" id="change-reload" disabled="disabled">
                            </div>

                            <p id="error-change-reload" class="error-pos" hidden="hidden"></p>
                          </div>
                        </div>
                    </div>
                  </div>
                  
                </form>
                <div class="modal-footer">
                  <button type="button" class="btn btn-info btn-savemem-modal" id="update-reload-member">Reload</button>
                  <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </div>
          
          <!----end of modal---->
        </div>
        
        
        
        
        </div><!---end of center--->
        
        </div> <!---end first div--->
        
        <!---second div start-->
        <div class="col-lg-7 mx-auto right second_div">
            @include('staff.posbuttons')
        </div> <!---end second div--->

        <div id="mirror-pos" hidden="hidden">
          @foreach($allitems as $item)
            <div class="col-lg-12">
              <div class="btn btn-sm btn-info full mirror-pos-button" data-id="{{$item->product_id}}" data-description="{{$item->product_name}}" data-price="{{$item->price}}" data-memprice="{{$item->member_price}}" data-qty="{{$item->product_qty}}">{{str_limit($item->product_name,10)}}</div>
            </div>
          @endforeach
        </div>

       <input type="hidden" id="discountvalue" value="0"> 
       <input type="hidden" id="memberload" value="">
       <input type="hidden" id="memberpoints" value="">
       <input type="hidden" id="memberid" value="">
       <input type="hidden" id="membercardno" value="">
       <input type="hidden" id="posvat" value="{{floatval($vat->vat)}}">

     </div>  <!---row-->
  </div> <!--container-->
         
<script>
  $(document).ready(function(){
    $('#member_input').val('');
    $('#guest_input').val('');
    $('#payment-amount-cash').val('');
  });

  $(document).on('click', '.pagination a', function(e){
    e.preventDefault();
    var myurl = $(this).attr('href');

    var page=$(this).attr('href').split('page=')[1];

    getData(page);
  });

  function getData(page)
  {
    $.ajax({
      url:'/staff/sales/buttons?page='+ page

    }).done(function(data){
      $('.second_div').html(data);
      location.hash=page;
      update_paginate();
    })
  }

  function update_total(){
    var sum = 0;
    var discount = $('#discountvalue').val();
    var discount_id = $('#discountvalue').attr('discount_id');
    var discount_type = $('#discountvalue').attr('discount_type');
       
    $('.itemsubtotal').each(function() {
      sum += parseFloat($(this).text());
    });

    if(discount_type == 'percentage' && discount_id == 1)
    {
        var vat_amount = $('#posvat').val();
        var vat_percent = (100 + parseFloat(vat_amount)) / 100;
        var zero = 0;

        var vatexempt =  sum / vat_percent;

        var totaldiscount = (vatexempt * discount);

        var total = vatexempt - totaldiscount;

        if(total < 0)
        {
          $('.totalprice').text(zero.toFixed(2)); 
        }
        else
        {
          $('.totalprice').text(total.toFixed(2)); 
        }
    }
    else if(discount_type == 'percentage' && discount_id != 1)
    {
      var discountpercent = $('#discountvalue').val();
      var discountdeduct = sum * parseFloat(discount);
      var total = sum - discountdeduct;
      var zero = 0;

      if(total < 0)
      {
        $('.totalprice').text(zero.toFixed(2)); 
      }
      else
      {
        $('.totalprice').text(total.toFixed(2)); 
      }
    }
    else
    {
      var total = sum - discount;
      var zero = 0;
      
      if(total < 0)
      {
        $('.totalprice').text(zero.toFixed(2)); 
      }
      else
      {
        $('.totalprice').text(total.toFixed(2)); 
      }
    }

    $('.subtotal').text(sum.toFixed(2));
  }

  $(document).on("click",".pos-button", function() {

      var id =$(this).attr("data-id");
      var price = $(this).attr("data-price");
      var description = $(this).attr("data-description");
      var checkContent =  $('#display_table').find($('.description:contains('+ description + ')')).length;

      if(!$(this).hasClass('disabled'))
      {
        $(this).addClass('pos-button-active');
        $('#mirror-pos').find($('[data-id="'+id +'"]')).addClass('pos-button-active');
      }

      if($("#membercardno").val().length == 0)
      {
        price = $(this).attr("data-price");
      }
      else
      {
        price = $(this).attr("data-memprice");

      }

      if(checkContent == 0 && !$(this).hasClass('disabled'))
      {
      var str_item = '<tr class="itemrow" id="'+ id +'"><td class="description append-td"><b>' + description + '</b></td>' +
          '<td class="append-td"><input type="text" class="quantity" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" id="qty_input" value="1" maxlength="3"></td>' +
          '<td class="itemprice append-td">' + price + '</td>' +
          '<td class="itemsubtotal append-td">' + price + '</td>' +
          '<td class="append-td-2"><button type="submit" class="btn delete"><i class="material-icons" id="clear-btn">clear</i></button></td></tr>';

        //$("#display_table").append(str_item).fadeIn(1000);
        $(str_item).hide().appendTo("#display_table tbody").fadeIn(370);
        $(".quantity").focus().select();
        update_total();
        compute_discount();
      }
      // else
      // {
      //   var existdelete = $('#display_table').find($('.description:contains('+ description + ')'));
      //   $(this).removeClass('active');
      //   DeleteRow(existdelete);
      //   update_total();
      // }
  });

  function DeleteRow(cellButton) {
    var row = $(cellButton).closest('tr')
        .children('td');
    setTimeout(function() {
        $(row)
            .animate({
                paddingTop: 0,
                paddingBottom: 0
            }, 400)
            .wrapInner('<div />')
            .children()
            .slideUp(400, function() {
                $(this).closest('tr').remove();
                update_total();
            });
    }, 150);
  }

  $(document).ready(function() {
      $(document).on('click', '.delete', function() {
        var whichtr = $(this).closest("tr"); 
        var description = whichtr.find($('.description')).text();
        var existdelete = $('.second_div').find($('[data-description="'+description +'"]'));
        var deletemirror =  $('#mirror-pos').find($('[data-description="'+description +'"]'));
        
        DeleteRow(this);
        $(existdelete).removeClass('pos-button-active');
        $(deletemirror).removeClass('pos-button-active');
      });
  });

 $(document).on('blur','#qty_input',function(){
    var whichtr = $(this).closest("tr"); 
    var itemprice = whichtr.find($('.itemprice')).text();

    if(whichtr.find($(this)).val().trim().length == 0 || whichtr.find($(this)).val().trim() == 0){
      whichtr.find($(this)).val(1);
      whichtr.find($('.itemsubtotal')).text(itemprice); 
    }

    update_total();
    compute_discount();
  });
  $(document).on('keydown','#qty_input',function(event){
    if (event.which == 13) {
      $(this).blur();
    }
  });

  $(document).on('input','#qty_input', function() {
    var whichtr = $(this).closest("tr");  
    var zero = 0;
    var subtotal = 0;
    var qty = $(this).val();
    var price = parseFloat(whichtr.find($('.itemprice')).text());

    var desc = whichtr.find($('.description')).text();
    var dataqty = $('#mirror-pos').find($('[data-description="'+ desc +'"]')).attr('data-qty');
    var dataid = $('#mirror-pos').find($('[data-description="'+ desc +'"]')).attr('data-id');

    if(dataid > 24)
    {
      if(parseFloat($(this).val()) > dataqty)
      {
        $(this).val(dataqty);
        var newqty = $(this).val()
        subtotal = newqty * price;
        
        whichtr.find($('.itemsubtotal')).text(subtotal.toFixed(2));
        update_total();
        compute_discount();
      }
      else
      {
        subtotal = qty * price;

        whichtr.find($('.itemsubtotal')).text(subtotal.toFixed(2));
        update_total();
        compute_discount();
      }
    }
    else
    {
      subtotal = qty * price;

      whichtr.find($('.itemsubtotal')).text(subtotal.toFixed(2));
      update_total();
      compute_discount();
    }
  });

  $(document).ready(function(){
     var discount_id = $('.select-box-discount option:selected').attr('data-id');
     $('#discountvalue').attr('discount_id', discount_id);
  });

  $(document).on('change', '.select-box-discount',function() {
    var discount_id = $('.select-box-discount option:selected').attr('data-id');
    var discount_name = $('.select-box-discount option:selected').attr('data-name');
    var discount_type = $('.select-box-discount option:selected').attr('data-type');
    var discount_value = $('.select-box-discount option:selected').attr('data-value');

    $('#discountvalue').attr('discount_id', discount_id);
    $('#discountvalue').attr('discount_name', discount_name);
    $('#discountvalue').attr('discount_type', discount_type);
    $('#discountvalue').val(discount_value);
    update_total();
    compute_discount();
  });

  function compute_discount()
  {
    var discount_id = $('.select-box-discount option:selected').attr('data-id');
    var discount_name = $('.select-box-discount option:selected').attr('data-name');
    var discount_type = $('.select-box-discount option:selected').attr('data-type');
    var discount_value = $('.select-box-discount option:selected').attr('data-value');

    var discount = $('#discountvalue').val();
    var zero = 0;
    var sum = parseFloat($('.subtotal').text());

    if(discount_type == 'percentage' && discount_id == 1)
    {
      var vat_amount = $('#posvat').val();
      var vat_percent = (100 + parseFloat(vat_amount)) / 100;
      var vatexempt =  sum / vat_percent;
      var totaldiscount = (vatexempt * discount);
    }
    else if(discount_type == 'percentage' && discount_id != 1)
    {
      var totaldiscount = sum * parseFloat(discount);
    }
    else
    {
      var totaldiscount = parseFloat(discount);
    }
    
    $('.discountspanvalue').text(('₱ ' + totaldiscount.toFixed(2)));
  }

  function alpha(e) {
    var k;
    document.all ? k = e.keyCode : k = e.which;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32);
  }

  $(document).ready(function(){
    $('#member').show();
    $('#guest').hide();

    $('.payment-btn').hide();
  });

  $(document).on('change', '.select-box-role',function() {
    var type = $(".select-box-role option:selected").text();
    
    if (type === "Member") 
    {
      $('.lpayment-btn').show();
      $('.ppayment-btn').show();
      $('.payment-btn').hide();
      if($("#member").not(':visible')) 
      {
        $("#member").show();
        $("#guest").hide();
        $('#membercardno').val('');
        $('#memberid').val('');
        $('#membername').text('');
        $('#memberspanload').text('');
        $('#memberspanpoints').text('');
        $('#member_input').val('');
        $('#guest_input').val('');
        $('#member_input').removeAttr('style');
        $('#guest_input').removeAttr('style');
      }
      align_price();
    } 
    else 
    {
      $('.ppayment-btn').hide();
      $('.lpayment-btn').hide();
      $('.payment-btn').show();
      $("#member").hide();
      $("#guest").show();
      $('#membercardno').val('');
      $('#memberid').val('');
      $('#membername').text('');
      $('#memberspanload').text('');
      $('#memberspanpoints').text('');
      $('#member_input').val('');
      $('#guest_input').val('');
      $('#member_input').removeAttr('style');
      $('#guest_input').removeAttr('style');

      align_price();
    }

  }).trigger("change");

  function align_price()
  {
    $("#mirror-pos .btn.pos-button-active").each(function(){
    var price = $(this).attr("data-price");

    if($("#membercardno").val().length == 0)
    {
      price = price;
    }
    else
    {
      price = $(this).attr("data-memprice");
    }

    var quantity = $("#display_table tbody tr#"+$(this).attr("data-id")).find("input.quantity").val();
    var subtotal = parseFloat(price) * quantity;

    $("#display_table tbody tr#"+$(this).attr("data-id")).find("td.itemprice").text(price);

    $("#display_table tbody tr#"+$(this).attr("data-id")).find("td.itemsubtotal").text(subtotal.toFixed(2));
    });
    update_total();
    compute_discount();

  }

  function update_paginate(){
    $("#mirror-pos .btn.pos-button-active").each(function(){
      var id = $(this).attr("data-id");
      var button = $('.second_div').find($('[data-id="'+ id +'"]'));
      button.addClass("pos-button-active");
    });
  };

  $(document).on('click', '.lpayment-btn', function()
  {
    var rowcount = $('#display_table tbody').find('tr').length;
    var member_input = $('#member_input').val();
    var member_name = $('#membername').text();

    if(rowcount > 0)
    {
      if(member_name == '' && member_input == '')
      {
        swal({
              title: "Error!",
              text: "Please tap the customer card first!",
              icon: "error",
              button: "Close",
            });

        $('#member_input').css("border", "1px solid #cc0000");
        for(var i = 0; i < 3; i++)
        {
          $('#member_input').fadeOut().fadeIn('slow');
        }
      }
      else if(member_name == '' && member_input != '')
      {
        swal({
              title: "Error!",
              text: "Please double check the card number!",
              icon: "error",
              button: "Close",
            });

        $('#member_input').css("border", "1px solid #cc0000");
        for(var i = 0; i < 3; i++)
        {
          $('#member_input').fadeOut().fadeIn('slow');
        }
      }
      else
      {
        $('#member_input').removeAttr('style');
        $('.lpayment').modal('show');

        var currentload = parseFloat($('#memberload').val());
        var amount = parseFloat($('.totalprice').text());

        $('#current-load-card').val(currentload.toFixed(2));
        $('#loadpay-card').val(amount.toFixed(2));


        if(currentload < amount)
        {
          $('#reload').removeAttr('hidden');
          $('#payload').attr('hidden', true);
        }
        else
        {
          $('#reload').attr('hidden', true);
          $('#payload').removeAttr('hidden');
        }  
      }
    }
    else
    {
      $('#guest_input').removeAttr('style');
      $('#member_input').removeAttr('style');
      swal({
        title: "Error!",
        text: "You must select an item first!",
        icon: "error",
        button: "Close",
      });
    } 
  });

  $(document).on('click', '.ppayment-btn', function()
  {
    var rowcount = $('#display_table tbody').find('tr').length;
    var member_input = $('#member_input').val();
    var member_name = $('#membername').text();

    if(rowcount > 0)
    {
      if(member_name == '' && member_input == '')
      {
        swal({
              title: "Error!",
              text: "Please tap the customer card first!",
              icon: "error",
              button: "Close",
            });

        $('#member_input').css("border", "1px solid #cc0000");
        for(var i = 0; i < 3; i++)
        {
          $('#member_input').fadeOut().fadeIn('slow');
        }
      }
      else if(member_name == '' && member_input != '')
      {
        swal({
              title: "Error!",
              text: "Please double check the card number!",
              icon: "error",
              button: "Close",
            });

        $('#member_input').css("border", "1px solid #cc0000");
        for(var i = 0; i < 3; i++)
        {
          $('#member_input').fadeOut().fadeIn('slow');
        }
      }
      else
      {
        $('#member_input').removeAttr('style');
        $('.ppayment').modal('show');

        var currentpoints = parseFloat($('#memberpoints').val());
        var amount = parseFloat($('.totalprice').text());

        $('#current-points-card').val(currentpoints.toFixed(2));
        $('#pointspay-card').val(amount.toFixed(2));


        if(currentpoints < amount)
        {
          $('#lowpoints').removeAttr('hidden');
          $('#paypoints').attr('hidden', true);
        }
        else
        {
          $('#lowpoints').attr('hidden', true);
          $('#paypoints').removeAttr('hidden');
        }  
      }
    }
    else
    {
      $('#guest_input').removeAttr('style');
      $('#member_input').removeAttr('style');
      swal({
        title: "Error!",
        text: "You must select an item first!",
        icon: "error",
        button: "Close",
      });
    } 
  });

  $(document).on('click', '#paypoints', function(){
    var itemsBought = [];
    $("#display_table .itemrow").each(function() { 
        var arrayOfThisRow = [];
        var desc = $(this).find('.description').text();
        var id = $('#mirror-pos').find($('[data-description="'+ desc +'"]')).attr('data-id');
        var qty = $(this).find('.quantity');
        var price = $(this).find('.itemprice');
        var subtotal = $(this).find('.itemsubtotal');

        arrayOfThisRow.push(id,qty.val(),price.text(), subtotal.text()); 
        itemsBought.push(arrayOfThisRow);
    });

    $.ajax({
      type: 'POST',
      url: '/staff/sales/member_pointspayment',
      data: {
              '_token': $('input[name=_token]').val(),
              'member_id': $("#memberid").val(),
              'discount_id': $('#discountvalue').attr('discount_id'),
              'amount_paid': $("#pointspay-card").val(),
              'current_points': $("#current-points-card").val(),
              'vat': $("#posvat").val(),
              'itemsbought': itemsBought
            },
      success: function(data) {
        localStorage.setItem("sold","success");
        window.location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    });
  });

  $(document).on('click', '#payload', function(){
    var itemsBought = [];
    $("#display_table .itemrow").each(function() { 
        var arrayOfThisRow = [];
        var desc = $(this).find('.description').text();
        var id = $('#mirror-pos').find($('[data-description="'+ desc +'"]')).attr('data-id');
        var qty = $(this).find('.quantity');
        var price = $(this).find('.itemprice');
        var subtotal = $(this).find('.itemsubtotal');

        arrayOfThisRow.push(id,qty.val(),price.text(), subtotal.text()); 
        itemsBought.push(arrayOfThisRow);
    });

    $.ajax({
      type: 'POST',
      url: '/staff/sales/member_loadpayment',
      data: {
              '_token': $('input[name=_token]').val(),
              'member_id': $("#memberid").val(),
              'discount_id': $('#discountvalue').attr('discount_id'),
              'amount_paid': $("#loadpay-card").val(),
              'current_load': $("#current-load-card").val(),
              'vat': $("#posvat").val(),
              'itemsbought': itemsBought
            },
      success: function(data) {
        localStorage.setItem("sold","success");
        window.location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    });
  });

  $(document).on('click', '#reload', function(){
    $('.reload').modal('show');
    $('.lpayment').modal('hide');
    var currentload = parseFloat($('#memberload').val());
    $('#load-reload').val(currentload.toFixed(2));

    if($('#change-reload').val() == ' ')
    {
      $('#update-reload-member').removeAttr('disabled');
    }
    else
    {
      $('#update-reload-member').attr('disabled', true);
    }
  });

  $('.reload').on('hide.bs.modal', function(){
    //clear input boxes
    $('#reload-amount-reload').val('');
    $('#payment-amount-reload').val('');
    $('#change-reload').val('');

    //hide error messages in modal
    $('#error-reload-amount-reload').attr('hidden', true);
    $('#error-reload-amount-reload').text('');
    $('#error-reload-amount-reload').attr('hidden', true);
    $('#error-payment-amount-reload').text('');

    //remove css style in modal
    $('#reload-amount-reload').removeAttr('style')
    $('#basic-addon-reload-amount').removeAttr('style');
    $('#payment-amount-reload').removeAttr('style')
    $('#basic-addon-payment-amount').removeAttr('style');

    $('#update-reload-member').attr('disabled', true);
  });

  $('#reload-amount-reload, #payment-amount-reload').on('input',function() {
      var reload_amount = parseFloat($('#reload-amount-reload').val());
      var payment = parseFloat($('#payment-amount-reload').val());
      $('#change-reload').val((payment - reload_amount ? payment - reload_amount : 0).toFixed(2));

      //isNaN
      if(isNaN($('#reload-amount-reload').val()))
      {
        $('#error-reload-amount-reload').removeAttr("hidden");
        $('#error-reload-amount-reload').text("Reload Amount field is not a valid amount.");
        $('#reload-amount-reload').css("border", "1px solid #cc0000");
        $('#basic-addon-reload-amount').css("border", "1px solid #cc0000");
        $('#update-reload-member').attr('disabled', true);
      }
      else
      {
        $('#error-reload-amount-reload').attr("hidden", true);
        $('#error-reload-amount-reload').text("");
        $('#reload-amount-reload').removeAttr("style")
        $('#basic-addon-reload-amount').removeAttr("style");
      }

      if(isNaN($('#payment-amount-reload').val()))
      {
        $('#error-payment-amount-reload').removeAttr("hidden");
        $('#error-payment-amount-reload').text("Payment Amount field is not a valid amount.");
        $('#payment-amount-reload').css("border", "1px solid #cc0000");
        $('#basic-addon-payment-amount').css("border", "1px solid #cc0000");
        $('#update-reload-member').attr('disabled', true);
      }
      else
      {
        $('#error-payment-amount-reload').attr("hidden", true);
        $('#error-payment-amount-reload').text("");
        $('#payment-amount-reload').removeAttr("style")
        $('#basic-addon-payment-amount').removeAttr("style");
      }

      //Reload is greater than payment
      if((reload_amount > payment)) // && (payment != '' || payment == ''))
      {
        $('#error-payment-amount-reload').removeAttr("hidden");
        $('#error-payment-amount-reload').text("Payment cannot be less than Reload Amount");
        $('#payment-amount-reload').css("border", "1px solid #cc0000");
        $('#basic-addon-payment-amount').css("border", "1px solid #cc0000");
        $('#update-reload-member').attr('disabled', true);
      }
      else if((payment != '' && reload_amount != '') &&
        (reload_amount <= payment) && (isNaN($('#payment-amount-reload').val()) == false && isNaN($('#reload-amount-reload').val()) == false))
      {
        $('#payment-amount-reload').removeAttr("style")
        $('#basic-addon-payment-amount').removeAttr("style");
        $('#update-reload-member').removeAttr('disabled');
      }
      else
      {
        $('#update-reload-member').attr('disabled', true);
      }
  });

  $(document).on('click', '#update-reload-member', function(event) {
  $.ajax({
    type: 'POST',
    url: '/staff/sales/member_reload',
    data: {
            '_token': $('input[name=_token]').val(),
            'member_id': $("#memberid").val(),
            'current_load': $('#load-reload').val(),
            'reload_amount': $('#reload-amount-reload').val(),
            'payment_amount': $('#payment-amount-reload').val(),
            'change_amount': $('#change-reload').val()
          },
    success: function(data) {
        swal({
                title: "Success!",
                text: "You have successfully reloaded the member's account!",
                icon: "success",
                button: "Close",
              });
        
        var parseload = parseFloat(data);
        var newload = parseload.toFixed(2);

        $('#memberload').val(newload);
        $('#memberspanload').text('₱ ' + newload);
        $('.reload').modal('hide');

      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
  });

  $(document).on('click', '.payment-btn', function()
  {
    var rowcount = $('#display_table tbody').find('tr').length;
    var guest_input = $('#guest_input').val();
    var member_input = $('#member_input').val();
    var member_name = $('#membername').text();
    var type = $(".select-box-role option:selected").text();

    if(rowcount > 0)
    {  
      if(type == "Walk-in")
      {
        if(guest_input == '')
        {
          swal({
                title: "Error!",
                text: "Please input the customer name first!",
                icon: "error",
                button: "Close",
              });

          $('#guest_input').css("border", "1px solid #cc0000");
          for(var i = 0; i < 3; i++)
          {
            $('#guest_input').fadeOut().fadeIn('slow');
          }
        }
        else
        {
          $('#guest_input').removeAttr('style');
          $('.payment').modal('show');
          $('#paysale').attr('disabled', true);

          var total_amount = $('.totalprice').text();
          var zero = 0;
          $('#total-price-cash').val(total_amount);

          if($('#total-price-cash').val() == 0)
          {
            $('#payment-amount-cash').val(zero.toFixed(2));
            $('#change-amount-cash').val(zero.toFixed(2));
            $('#payment-amount-cash').attr('disabled', true);
            $('#paysale').removeAttr('disabled');
          }

          $('#payment-amount-cash').on('input',function() {
            var total = parseFloat($('#total-price-cash').val());
            var payment = parseFloat($('#payment-amount-cash').val());
            $('#change-amount-cash').val((payment - total ? payment - total : 0).toFixed(2));

            //isNaN
            if(isNaN($('#payment-amount-cash').val()))
            {
              $('#error-payment-amount-cash').removeAttr("hidden");
              $('#error-payment-amount-cash').text("Payment Amount field is not a valid amount.");
              $('#payment-amount-cash').css("border", "1px solid #cc0000");
              $('#basic-addon-paymentamount-cash').css("border", "1px solid #cc0000");
              $('#paysale').attr('disabled', true);
            }
            else
            {
              $('#error-payment-amount-cash').attr("hidden", true);
              $('#error-payment-amount-cash').text("");
              $('#payment-amount-cash').removeAttr("style")
              $('#basic-addon-paymentamount-cash').removeAttr("style");
            }

            //Total is greater than payment
            if((total > payment)) // && (payment != '' || payment == ''))
            {
              $('#error-payment-amount-cash').removeAttr("hidden");
              $('#error-payment-amount-cash').text("Payment cannot be less than the Total Price");
              $('#payment-amount-cash').css("border", "1px solid #cc0000");
              $('#basic-addon-paymentamount-cash').css("border", "1px solid #cc0000");
              $('#paysale').attr('disabled', true);
            }
            else if((payment != '' && total != '') &&(total <= payment) && (isNaN($('#payment-amount-cash').val()) == false))
            {
              $('#payment-amount-cash').removeAttr("style")
              $('#basic-addon-paymentamount-cash').removeAttr("style");
              $('#paysale').removeAttr('disabled');
            }
            else if(($('#total-price-cash').val() == 0))
            {
              $('#payment-amount-cash').removeAttr("style")
              $('#basic-addon-paymentamount-cash').removeAttr("style");
              $('#payment-amount-cash').val('0');
              $('#paysale').removeAttr('disabled');
            }
            else
            {
              $('#paysale').attr('disabled', true);
            }
          });
        } 
      }
      else
      {
        if(member_name == '' && member_input == '')
        {
          swal({
                title: "Error!",
                text: "Please tap the customer card first!",
                icon: "error",
                button: "Close",
              });

          $('#member_input').css("border", "1px solid #cc0000");
          for(var i = 0; i < 3; i++)
          {
            $('#member_input').fadeOut().fadeIn('slow');
          }
        }
        else if(member_name == '' && member_input != '')
        {
          swal({
                title: "Error!",
                text: "Please double check the card number!",
                icon: "error",
                button: "Close",
              });

          $('#member_input').css("border", "1px solid #cc0000");
          for(var i = 0; i < 3; i++)
          {
            $('#member_input').fadeOut().fadeIn('slow');
          }
        }
        else
        {
          $('#member_input').removeAttr('style');
          $('.payment').modal('show');

          $('#paysale').attr('disabled', true);

          var total_amount = $('.totalprice').text();
          var zero = 0;
          $('#total-price-cash').val(total_amount);

          if($('#total-price-cash').val() == 0)
          {
            $('#payment-amount-cash').val(zero.toFixed(2));
            $('#change-amount-cash').val(zero.toFixed(2));
            $('#payment-amount-cash').attr('disabled', true);
            $('#paysale').removeAttr('disabled');
          }

          $('#payment-amount-cash').on('input',function() {
            var total = parseFloat($('#total-price-cash').val());
            var payment = parseFloat($('#payment-amount-cash').val());
            $('#change-amount-cash').val((payment - total ? payment - total : 0).toFixed(2));

            //isNaN
            if(isNaN($('#payment-amount-cash').val()))
            {
              $('#error-payment-amount-cash').removeAttr("hidden");
              $('#error-payment-amount-cash').text("Payment Amount field is not a valid amount.");
              $('#payment-amount-cash').css("border", "1px solid #cc0000");
              $('#basic-addon-paymentamount-cash').css("border", "1px solid #cc0000");
              $('#paysale').attr('disabled', true);
            }
            else
            {
              $('#error-payment-amount-cash').attr("hidden", true);
              $('#error-payment-amount-cash').text("");
              $('#payment-amount-cash').removeAttr("style")
              $('#basic-addon-paymentamount-cash').removeAttr("style");
            }

            //Total is greater than payment
            if((total > payment)) // && (payment != '' || payment == ''))
            {
              $('#error-payment-amount-cash').removeAttr("hidden");
              $('#error-payment-amount-cash').text("Payment cannot be less than the Total Price");
              $('#payment-amount-cash').css("border", "1px solid #cc0000");
              $('#basic-addon-paymentamount-cash').css("border", "1px solid #cc0000");
              $('#paysale').attr('disabled', true);
            }
            else if((payment != '' && total != '') &&(total <= payment) && (isNaN($('#payment-amount-cash').val()) == false))
            {
              $('#payment-amount-cash').removeAttr("style")
              $('#basic-addon-paymentamount-cash').removeAttr("style");
              $('#paysale').removeAttr('disabled');
            }
            else
            {
              $('#paysale').attr('disabled', true);
            }
          });
        } 
      }
    }
    else
    {
      $('#guest_input').removeAttr('style');
      $('#member_input').removeAttr('style');
      swal({
        title: "Error!",
        text: "You must select an item first!",
        icon: "error",
        button: "Close",
      });
    }
  });

  $(document).ready(function(){
    autocomplete();
  function autocomplete(){
    $('#member_input').autocomplete({
      minLength:7, 
      delay: 0,
      selectFirst: true,
      autoFocus: true,
      response: function(event, ui) {
          ui.item = ui.content[0];
          $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
          $(this).autocomplete('close');
      },
      source: function(request,response){
        $.ajax({
            url: '/staff/sales/member_autocomplete/',
            data: { 'input': $('#member_input').val() },
            type: "GET",
            success: function(data){
              var parser = jQuery.parseJSON(data);         
              response(
              $.map(parser, function() {
                  var id = parser.id;
                  var card_number = parser.card_number;
                  var fullname = parser.firstname + " " + parser.lastname;
                  var load = parser.balance.load_balance;
                  var points = parser.balance.points_balance;
                  return {
                      label: card_number,
                      id: id,
                      fullname: fullname,
                      load: load,
                      points: points
                  }
                }));
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
              console.log(JSON.stringify(jqXHR));
              console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
              }
          });
      },
      select: function(event, ui) {
        event.preventDefault();
        $(this).val(ui.item.label);
        $('#member_input').removeAttr('style');
        $('#memberid').val(ui.item.id);
        $('#membercardno').val(ui.item.label);
        $('#membername').text(ui.item.fullname);
        $('#memberspanload').text('₱ ' + ui.item.load);
        $('#memberspanpoints').text('₱ ' + ui.item.points);
        $('#memberload').val(ui.item.load);
        $('#memberpoints').val(ui.item.points);
        // compute_discount();
        align_price();
      }
    });
  }
  });

  $(document).on('keyup', '#member_input', function() {
    if(!this.value)
    {
      $('#membercardno').val('');
      $('#memberid').val('');
      $('#membername').text('');
      $('#memberspanload').text('');
      $('#memberspanpoints').text('');
      align_price();
    }
  });

  $('.payment').on('hide.bs.modal', function(){
     $('#payment-amount-cash').val('');
     $('#change-amount-cash').val('');
     $('#payment-amount-cash').removeAttr('style');
     $('#basic-addon-paymentamount-cash').removeAttr('style');
     $('#error-payment-amount-cash').attr('hidden', true);
  });

  $(document).on('input', '#guest_input', function() {
      $('#guest_input').removeAttr('style');
  });

  if(localStorage.getItem("sold"))
  {
    swal({
            title: "Success!",
            text: "The transaction has been completed!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }

  $(document).on('click', '#paysale', function(){
    var itemsBought = [];
    
    $("#display_table .itemrow").each(function() { 
      var arrayOfThisRow = [];
      var desc = $(this).find('.description').text();
      var id = $('#mirror-pos').find($('[data-description="'+ desc +'"]')).attr('data-id');
      var qty = $(this).find('.quantity');
      var price = $(this).find('.itemprice');
      var subtotal = $(this).find('.itemsubtotal');

      arrayOfThisRow.push(id,qty.val(),price.text(), subtotal.text()); 
      itemsBought.push(arrayOfThisRow);
    });

    if($('#memberid').val().length <= 0)
    {
      $.ajax({
        type: 'POST',
        url: '/staff/sales/guest_cashpayment',
        data: {
                '_token': $('input[name=_token]').val(),
                'customer_name': $('#guest_input').val(),
                'discount_id': $('#discountvalue').attr('discount_id'),
                'amount_due': $("#total-price-cash").val(),
                'amount_paid': $("#payment-amount-cash").val(),
                'change_amount': $("#change-amount-cash").val(),
                'vat': $("#posvat").val(),
                'itemsbought': itemsBought
              },
        success: function(data) {
          localStorage.setItem("sold","success");
          window.location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
      });
    }
    else
    {
      $.ajax({
        type: 'POST',
        url: '/staff/sales/member_cashpayment',
        data: {
                '_token': $('input[name=_token]').val(),
                'member_id': $("#memberid").val(),
                'discount_id': $('#discountvalue').attr('discount_id'),
                'amount_due': $("#total-price-cash").val(),
                'amount_paid': $("#payment-amount-cash").val(),
                'change_amount': $("#change-amount-cash").val(),
                'vat': $("#posvat").val(),
                'itemsbought': itemsBought
              },
        success: function(data) {
          localStorage.setItem("sold","success");
          window.location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
      });
    }
  });




</script>
@endsection