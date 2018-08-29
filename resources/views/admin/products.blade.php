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
    <h3 class="title">Products</h3>
    <div class="nav nav-tabs justify-content-end memberstab" id="nav-tab" role="tablist">
      
      <a class="nav-item nav-link" id="nav-profile-tab" href="/services/washers" role="tab" aria-controls="nav-profile" aria-selected="true">Washers
      <a class="nav-item nav-link" id="nav-discount-tab"  href="/services/dryers" role="tab" aria-controls="nav-discount" aria-selected="false">Dryers</a>
      <a class="nav-item nav-link active" id="nav-discount-tab"  href="/services/products" role="tab" aria-controls="nav-discount" aria-selected="false">Products</a>
        
      </div>
    </nav>
<div class="row">
    <div class="col-md-8">
    <button type="button" class="btn btn-outline-info add-prod-btn" data-toggle="modal" data-target=".add_product">
      Add Item
    </button>
  </div>
    <div class="col-md-4">
      <form class="form ml-auto" action="/services/search_product" method="GET">
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
          <th scope="col">Stock on Hand</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody class="td_class">

        @foreach($products as $product)
        <tr>
          <th scope="row" class="td-center">{{str_limit($product->product_name,40)}}</th>
          <td class="td-center">₱ {{$product->price}}</td>
          <td class="td-center">₱ {{$product->member_price}}</td>
          <td class="td-center">{{$product->product_qty}}</td>
          <td>
            <button type="button" id="edit-product" class="btn btn-primary edit-btn" data-toggle="modal" data-target=".edit_product" data-id="{{$product->product_id}}" data-productname="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-prodqty="{{$product->product_qty}}"><i class="material-icons md-18">mode_edit</i></button>
            <button type="button" id="delete-product" class="btn btn-danger del-btn" data-toggle="modal" data-target=".delete_product" data-id="{{$product->product_id}}" data-productname="{{$product->product_name}}"><i class="material-icons md-18">delete</i></button>
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
      <div class="form-group row mx-auto">
          <label for="prodname" class="col-form-label col-md-4 modal-address">Item Name:</label>
          <div class="col-md-8">
              <input type="text" name="prodname" class="form-control modal-add" id="product-prodname-edit">
            <p id="error-prodname-edit" class="error-edit" hidden="hidden"></p>
          </div>
      </div>  

      <div class="form-group row mx-auto">
          <label for="prodqty" class="col-form-label col-md-4 modal-address">Stock on Hand:</label>
          <div class="col-md-8">
              <input type="text" name="prodqty" class="form-control modal-add" id="product-prodqty-edit">
            <p id="error-prodqty-edit" class="error-edit" hidden="hidden"></p>
          </div>
      </div> 

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

    <div class="modal fade add_product" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

    <form class="nosubmitform">
    <br>
      <input type="hidden" name="product_id" id="product-id-add">
      <div class="form-group row mx-auto">
          <label for="prodname" class="col-form-label col-md-4 modal-address">Item Name:</label>
          <div class="col-md-8">
              <input type="text" name="prodname" class="form-control modal-add" id="product-prodname-add">
            <p id="error-prodname-add" class="error-add" hidden="hidden"></p>
          </div>
      </div>  

      <div class="form-group row mx-auto">
          <label for="prodqty" class="col-form-label col-md-4 modal-address">Stock on Hand:</label>
          <div class="col-md-8">
              <input type="text" name="prodqty" class="form-control modal-add" id="product-prodqty-add">
            <p id="error-prodqty-add" class="error-add" hidden="hidden"></p>
          </div>
      </div> 

      <div class="form-group row mx-auto">
          <label for="price" class="col-form-label col-md-4 modal-address">Price:</label>
          <div class="col-md-8">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon-price-add">₱</span>
              </div>
              <input type="text" name="price" class="form-control modal-add" id="product-price-add">
            </div>
            <p id="error-price-add" class="error-add" hidden="hidden"></p>
          </div>
      </div>
  
      <div class="form-group row mx-auto">
          <label for="memprice" class="col-form-label col-md-4 modal-address">Member's Price:</label>
          <div class="col-md-8">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon-memprice-add">₱</span>
              </div>
              <input type="text" name="memprice" class="form-control modal-add" id="product-memprice-add">
            </div>
            <p id="error-memprice-add" class="error-add" hidden="hidden"></p>
          </div>
      </div>  

        <div class="modal-footer" id="modal-footer-product-add">
          <button type="submit" id="add-product" class="btn btn-info btn-save-modal">Add Item</button>
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Cancel</button>
        </div>

      </form>
      </div>
    </div>
    </div>

    <div class="modal fade delete_product" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Delete Item</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
        <center>  <p> Are you sure you want to permanently delete the product <b><span id="delete-name"></span></b>?</p>
        <span class="product-id-delete" hidden="hidden"></span>
    </div>

    <div class="modal-footer" id="modal-footer-product-delete">
      <button type="button" id="destroy-product" class="btn btn-danger btn-savemem-modal">Yes</button>
      <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">No</button>

    </div>
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

if(localStorage.getItem("add"))
{
  swal({
          title: "Success!",
          text: "You have successfully added a new item!",
          icon: "success",
          button: "Close",
        });
  localStorage.clear();
}

if(localStorage.getItem("delete"))
{
  swal({
          title: "Success!",
          text: "You have successfully deleted the item!",
          icon: "success",
          button: "Close",
        });
  localStorage.clear();
}
});

$('.edit_product').on('hide.bs.modal', function(){
    //hide error messages in modal
    $('#error-prodname-edit').attr("hidden", true);
    $('#error-prodqty-edit').attr("hidden", true);
    $('#error-price-edit').attr("hidden", true);
    $('#error-memprice-edit').attr("hidden", true);

    //remove css style in modal
    $('#product-prodname-edit').removeAttr('style');
    $('#product-price-edit').removeAttr('style');
    $('#product-prodqty-edit').removeAttr('style');
    $('#basic-addon-price-edit').removeAttr('style');
    $('#product-memprice-edit').removeAttr('style');
    $('#basic-addon-memprice-edit').removeAttr('style');
});

$('.add_product').on('hide.bs.modal', function(){
    $('#product-prodname-add').val('');
    $('#product-prodqty-add').val('');
    $('#product-memprice-add').val('');
    $('#product-price-add').val('');

    //hide error messages in modal
    $('#error-prodname-add').attr("hidden", true);
    $('#error-prodqty-add').attr("hidden", true);
    $('#error-price-add').attr("hidden", true);
    $('#error-memprice-add').attr("hidden", true);

    //remove css style in modal
    $('#product-prodname-add').removeAttr('style');
    $('#product-prodqty-add').removeAttr('style');
    $('#product-price-add').removeAttr('style');
    $('#basic-addon-price-add').removeAttr('style');
    $('#product-memprice-add').removeAttr('style');
    $('#basic-addon-memprice-add').removeAttr('style');
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
          'product_qty': $("#product-prodqty-edit").val(),
          'price': $("#product-price-edit").val(),
          'member_price': $("#product-memprice-edit").val(),
        },
  success: function(data) {
    console.log(data);
    if ((data.errors)) {

        if(data.errors.product_name)
        {
          $('#error-prodname-edit').removeAttr("hidden");
          $('#error-prodname-edit').text(data.errors.product_name);
          $('#product-prodname-edit').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-prodname-edit').attr("hidden", true);
          $('#product-prodname-edit').removeAttr('style');
        }

        if(data.errors.product_qty)
        {
          $('#error-prodqty-edit').removeAttr("hidden");
          $('#error-prodqty-edit').text(data.errors.product_qty);
          $('#product-prodqty-edit').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-prodqty-edit').attr("hidden", true);
          $('#product-prodqty-edit').removeAttr('style');
        }

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

$('#modal-footer-product-add').on('click', '#add-product', function(event) {
$.ajax({
  type: 'POST',
  url: '/services/add_service',
  data: {
          '_token': $('input[name=_token]').val(),
          'product_id': $("#product-id-add").val(),
          'product_name': $("#product-prodname-add").val(),
          'product_qty': $("#product-prodqty-add").val(),
          'price': $("#product-price-add").val(),
          'member_price': $("#product-memprice-add").val(),
        },
  success: function(data) {
    console.log(data);
    if ((data.errors)) {

        if(data.errors.product_name)
        {
          $('#error-prodname-add').removeAttr("hidden");
          $('#error-prodname-add').text(data.errors.product_name);
          $('#product-prodname-add').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-prodname-add').attr("hidden", true);
          $('#product-prodname-add').removeAttr('style');
        }

        if(data.errors.product_qty)
        {
          $('#error-prodqty-add').removeAttr("hidden");
          $('#error-prodqty-add').text(data.errors.product_qty);
          $('#product-prodqty-add').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-prodqty-add').attr("hidden", true);
          $('#product-prodqty-add').removeAttr('style');
        }

        if(data.errors.price)
        {
          $('#error-price-add').removeAttr("hidden");
          $('#error-price-add').text(data.errors.price);
          $('#product-price-add').css("border", "1px solid #cc0000");
          $('#basic-addon-price-add').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-price-add').attr("hidden", true);
          $('#product-price-add').removeAttr('style');
          $('#basic-addon-price-add').removeAttr('style');
        }

        if(data.errors.member_price)
        {
          $('#error-memprice-add').removeAttr("hidden");
          $('#error-memprice-add').text(data.errors.member_price);
          $('#product-memprice-add').css("border", "1px solid #cc0000");
          $('#basic-addon-memprice-add').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-memprice-add').attr("hidden", true);
          $('#product-memprice-add').removeAttr('style');
          $('#basic-addon-memprice-add').removeAttr('style');
        }
    }
    else
    {
      localStorage.setItem("add","success");
      window.location.reload();
    }
    },

      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log(JSON.stringify(jqXHR));
      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
  });
});

$(document).on('click', '#delete-product', function() {
    $('.product-id-delete').text($(this).data('id'));
    $('#delete-name').text($(this).data('productname'));
  });

$('#modal-footer-product-delete').on('click', '#destroy-product', function(){
  $.ajax({
    type: 'POST',
    url: '/services/delete_service',
    data: {
      '_token': $('input[name=_token]').val(),
      'product_id': $('.product-id-delete').text()
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
</script>

@endsection
