@extends('layout')

@section('title')
SERVICES
@endsection

@section('css')
{{ asset('imports/css/inventory.css') }}
@endsection

@section('content')

</br>
<div class="container">
<!---title inventory-->
<nav>
    <h3 class="title">Dryers</h3>
    <div class="nav nav-tabs justify-content-end memberstab" id="nav-tab" role="tablist">
      
      <a class="nav-item nav-link" id="nav-profile-tab" href="/services/washers" role="tab" aria-controls="nav-profile" aria-selected="true">Washers
      <a class="nav-item nav-link active" id="nav-discount-tab"  href="/services/dryers" role="tab" aria-controls="nav-discount" aria-selected="false">Dryers</a>
      <a class="nav-item nav-link" id="nav-discount-tab"  href="/services/products" role="tab" aria-controls="nav-discount" aria-selected="false">Products</a>
        
      </div>
    </nav>
<div class="row">
    <div class="col-md-8">
    </div>
    <div class="col-md-4">
      <form class="form ml-auto" action="/services/search_dryer" method="GET">
      <div class="input-group">
          <input class="form-control" name="search" type="text" placeholder="Search" aria-label="Search" style="padding-left: 20px; border-radius: 40px;" id="product-search">
          <div class="input-group-addon" style="margin-left: -50px; z-index: 3; border-radius: 40px; background-color: transparent; border:none;">
            <button class="btn btn-outline-info btn-sm" type="submit" style="border-radius: 100px;" id="search-btn"><i class="material-icons">search</i></button>
          </div>
      </div>
      </form>
    </div>
</div>

	@if(!empty($search))
	    @if($totalcount > 7)
	    	<center><p> Showing {{$count}} out of {{$totalcount}} results
	    	for <b> {{ $search }} </b> </p></center>
	    @else
	    	<center><p> Showing {{$count}}
				@if($count > 1 || $count == 0)
					{{'results'}}
				@else
					{{'result'}}
				@endif
	      	for <b> {{ $search }} </b> </p></center>
	    @endif
  @endif

    <table class="table table-hover">
    @csrf
      <thead class ="th_css">
        <tr>
		      <th scope="col">Name</th>
		      <th scope="col">Price</th>
		      <th scope="col">Member's Price</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody class="td_class">

      	@foreach($products as $product)
        <tr>
          <th scope="row" class="td-center">{{str_limit($product->product_name,40)}}</th>
          <td class="td-center">₱ {{$product->price}}</td>
          <td class="td-center">₱ {{$product->member_price}}</td>
          <td>
            <button type="button" id="edit-product" class="btn btn-primary edit-btn" data-toggle="modal" data-target=".edit_product" data-id="{{$product->product_id}}" data-productname="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-prodqty="{{$product->product_qty}}"><i class="material-icons md-18">mode_edit</i></button>
        </tr>
        @endforeach

      </tbody>
    </table>

    {{$products->links()}}

    <!----end of modal---->
   <!----start of modal for EDIT---->
    <div class="modal fade edit_product" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

    <form class="nosubmitform">
    <br>
      <input type="hidden" name="product_id" id="product-id-edit">
      <input type="hidden" name="prodname" id="product-prodname-edit">
      <input type="hidden" name="prodqty" id="product-prodqty-edit">
		  <div class="form-group row mx-auto">
          <label for="price" class="col-form-label col-md-4 modal-address">Price:</label>
          <div class="col-md-8">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon-price-edit">₱</span>
              </div>
              <input type="text" name="price" class="form-control modal-add" id="product-price-edit">
            </div>
            <p id="error-price-edit" class="error-edit" hidden="hidden"></p>
          </div>
		  </div>

      <div class="form-group row mx-auto">
          <label for="memprice" class="col-form-label col-md-4 modal-address">Member's Price:</label>
          <div class="col-md-8">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon-memprice-edit">₱</span>
              </div>
              <input type="text" name="memprice" class="form-control modal-add" id="product-memprice-edit">
            </div>
            <p id="error-memprice-edit" class="error-edit" hidden="hidden"></p>
          </div>
      </div>  

        <div class="modal-footer" id="modal-footer-product-edit">
          <button type="submit" id="update-product" class="btn btn-info btn-save-modal">Save Changes</button>
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Cancel</button>
        </div>

      </form>
      </div>
    </div>
    </div>
</div>

<script type="text/javascript">
$('.nosubmitform').submit(function(event){
  event.preventDefault();
});

//success alerts - add, update, delete
$(document).ready(function(){
if(localStorage.getItem("update"))
{
  swal({
          title: "Success!",
          text: "You have successfully updated the item's information!",
          icon: "success",
          button: "Close",
        });
  localStorage.clear();
}
});

$('.edit_product').on('hide.bs.modal', function(){
    //hide error messages in modal
    $('#error-price-edit').attr("hidden", true);
    $('#error-memprice-edit').attr("hidden", true);

    //remove css style in modal
    $('#product-price-edit').removeAttr('style');
    $('#basic-addon-price-edit').removeAttr('style');
    $('#product-memprice-edit').removeAttr('style');
    $('#basic-addon-memprice-edit').removeAttr('style');
});

//edit product
$(document).on('click', '#edit-product', function() {
  $('#product-id-edit').val($(this).data('id'));   
  $('#product-prodname-edit').val($(this).data('productname'));
  $('#product-prodqty-edit').val($(this).data('prodqty'));    
  $("#product-price-edit").val($(this).data('price'));
  $("#product-memprice-edit").val($(this).data('memprice'));
});

$('#modal-footer-product-edit').on('click', '#update-product', function(event) {
$.ajax({
  type: 'POST',
  url: '/services/update_service',
  data: {
          '_token': $('input[name=_token]').val(),
          'product_id': $("#product-id-edit").val(),
          'product_name': $("#product-prodname-edit").val(),
          'product_qty' : $("#product-prodqty-edit").val(),
          'price': $("#product-price-edit").val(),
          'member_price': $("#product-memprice-edit").val(),
        },
  success: function(data) {
    console.log(data);
    if ((data.errors)) {
        if(data.errors.price)
        {
          $('#error-price-edit').removeAttr("hidden");
          $('#error-price-edit').text(data.errors.price);
          $('#product-price-edit').css("border", "1px solid #cc0000");
          $('#basic-addon-price-edit').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-price-edit').attr("hidden", true);
          $('#product-price-edit').removeAttr('style');
          $('#basic-addon-price-edit').removeAttr('style');
        }

        if(data.errors.member_price)
        {
          $('#error-memprice-edit').removeAttr("hidden");
          $('#error-memprice-edit').text(data.errors.member_price);
          $('#product-memprice-edit').css("border", "1px solid #cc0000");
          $('#basic-addon-memprice-edit').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-memprice-edit').attr("hidden", true);
          $('#product-memprice-edit').removeAttr('style');
          $('#basic-addon-memprice-edit').removeAttr('style');
        }
    }
    else
    {
      localStorage.setItem("update","success");
      window.location.reload();
    }
    },

      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log(JSON.stringify(jqXHR));
      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
  });
});

</script>

@endsection
