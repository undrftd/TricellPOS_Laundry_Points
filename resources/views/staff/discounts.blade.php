@extends('staff_layout')

@section('title')
DISCOUNTS
@endsection

@section('css')
{{ asset('imports/css/members.css') }}
@endsection

@section('content')
</br>
<div class="container">
  <!----sys pref nav--->
  <h3 class="title">Discounts</h3>
  <nav>
    <div class="nav nav-tabs justify-content-end memberstab" id="nav-tab" role="tablist">
      <a class="nav-item nav-link" id="nav-profile-tab"  href="/staff/preferences/profile" role="tab" aria-controls="nav-profile" aria-selected="true">Profile
      <a class="nav-item nav-link active" id="nav-discount-tab"  href="/staff/preferences/discounts" role="tab" aria-controls="nav-discount" aria-selected="false">Discounts</a>
      <a class="nav-item nav-link" id="nav-discount-tab"  href="/staff/preferences/backup" role="tab" aria-controls="nav-discount" aria-selected="false">Backup</a>
      </div>
    </nav>
    <!--sys pref nav---->
    
    <div class="row">
      <div class="col-md-8">
        <button type="button" class="btn btn-outline-info add-staff-btn" id ="create-discount" data-toggle="modal" data-target=".add_discount">Add Discount</button>
      </div>
      <div class="col-md-4">
        <form class="form ml-auto" action="/staff/preferences/discounts/search" method="GET">
          <div class="input-group">
            <input class="form-control" name="search" type="text" placeholder="Search" aria-label="Search" style="padding-left: 20px; border-radius: 40px;" id="mysearch">
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
          <th scope="col">Type</th>
          <th scope="col">Value</th>
          <th scope="col">Action</th>
          
        </tr>
      </thead>
      <tbody class="td_class">
        @foreach($discounts as $discount)
        <tr>
          <td class="td-center">{{$discount->discount_name}}</td>
          <td class="td-center">{{ucfirst($discount->discount_type)}}</td>
          <td class="td-center">
            @if($discount->discount_type == 'percentage')  
               {{$discount->discount_value * 100 . '%'}}
            @else
               {{'₱ '. $discount->discount_value}}
            @endif
          </td>
          <td>
            <button type="button" class="btn btn-primary edit-btn" id="edit-discount" data-id="{{$discount->id}}" data-name="{{$discount->discount_name}}" data-type="{{$discount->discount_type}}" data-value="{{$discount->discount_value}}" data-toggle="modal" data-target=".edit_discount"><i class="material-icons md-18">mode_edit</i></button>
            @if($discount->id != 1)
            <button type="button" class="btn btn-danger del-btn" id="delete-discount" data-id="{{$discount->id}}" data-name="{{$discount->discount_name}}" data-toggle="modal" data-target=".delete_discount"><i class="material-icons md-18">delete</i></button>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{$discounts->links()}}
    <!----start of modal for add discount---->
    <div class="modal fade bd-example-modal-lg add_discount" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Discount</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="nosubmitform">
            </br>
            <div class="form-group row mx-auto">
              <label for="discount-name" class="col-form-label col-md-2 modal-dname">Name:</label>
              <div class="col-md-10">
                <input type="text" name="discountname" class="form-control modal-discountsname" id="discount-name-add">
                <p id="error-discount-name-add" class="error-add" hidden="hidden"></p>
              </div>
            </div>
            <div class="form-group row mx-auto">
              <label for="discount-type" class="col-form-label col-md-2 modal-discount-type">Type:</label>
              <div class="col-md-10">
                <select class="form-control" id="discount-type-add">
                  <option value="" selected hidden>Choose Type</option>
                  <option value="percentage">Percentage</option>
                  <option value="deduction">Deduction</option>
                </select>
                <p id="error-discount-type-add" class="error-add" hidden="hidden"></p>
              </div>
            </div>
            <div class="form-group row mx-auto">
            <label for="discount-value" class="col-form-label col-md-2 modal-discount-value">Value:</label>
              <div class="col-md-10">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class ="input-group-text" id="basic-addon-add"></span>
                  </div>
                  <input type="text" name="discount_value" id="discount-value-add" class="form-control modal-value">
                </div>
                <p id="error-discount-value-add" class="error-add" hidden="hidden"></p>
              </div>
            </div>

            <!--modal footer-->
            <div class="modal-footer" id="modal-footer-discount-add">
              <button type="submit" id="add-discount" class="btn btn-info btn-savemem-modal">Save New Discount</button>
              <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!----end of modal---->
    <!----start of modal for EDIT---->
    <div class="modal fade bd-example-modal-lg edit_discount" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Discount</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="nosubmitform">
          <input type="hidden" name="discount_id" id="discount-id-edit">
            </br>
            <div class="form-group row mx-auto">
              <label for="discount-name" class="col-form-label col-md-2 modal-dname">Name:</label>
              <div class="col-md-10">
                <input type="text" name="discountname" class="form-control modal-dname" id="discount-name-edit">
                <p id="error-discount-name-edit" class="error-add" hidden="hidden"></p>
              </div>
            </div>
            <div class="form-group row mx-auto">
              <label for="discount-type" class="col-form-label col-md-2 modal-discountype">Type:</label>
              <div class="col-md-10">
                <select class="form-control" id="discount-type-edit">
                  <option value="percentage">Percentage</option>
                  <option value="deduction">Deduction</option>
                </select>
                <p id="error-discount-type-edit" class="error-add" hidden="hidden"></p>
              </div>
            </div>
            <div class="form-group row mx-auto">
            <label for="discount-value" class="col-form-label col-md-2 modal-discount-value">Value:</label>
              <div class="col-md-10">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon-edit"></span>
                  </div>
                  <input type="text" name="discount-value" class="form-control modal-value" id="discount-value-edit">
                </div>
                <p id="error-discount-value-edit" class="error-add" hidden="hidden"></p>
              </div>
            </div>

            <!--modal footer-->
            <div class="modal-footer" id="modal-footer-discount-edit">
              <button type="submit" id="update-discount" class="btn btn-info btn-savemem-modal">Save Changes</button>
              <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
<!----end of modal---->
<!----start of modal for DELETE---->
<div class="modal fade delete_discount" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Discount</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <center>  <p> Are you sure you want to permanently delete the <b><span id="delete-name"></span></b> discount?</p> </center>
        <span class="discount-id-delete" hidden="hidden"></span>
      </div>
      
      <div class="modal-footer" id="modal-footer-discount-delete">
        <button type="button" class="btn btn-danger btn-savemem-modal" id="destroy-discount">Yes</button>
        <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">No</button>
        
      </div>
    </div>
  </div>
</div>

<!----end of modal---->

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
            text: "You have successfully updated the discount's details!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  else if(localStorage.getItem("delete"))
  {
    swal({
            title: "Success!",
            text: "You have successfully deleted the discount!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  else if(localStorage.getItem("add"))
  {
    swal({
            title: "Success!",
            text: "You have successfully added a discount!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  });


  $('.add_discount').on('hide.bs.modal', function(){
    //hide error messages in modal
    $('#error-discount-name-add').attr('hidden', true);
    $('#error-discount-type-add').attr('hidden', true);
    $('#error-discount-value-add').attr('hidden', true);
    $('#discount-type-add')

    //remove css style in modal
    $('#discount-name-add').removeAttr('style');
    $('#discount-type-add').removeAttr('style');
    $('#discount-value-add').removeAttr('style');
    $('#basic-addon-add').removeAttr('style');

    //remove defined values/classes
    $('#discount-type-add').val('');
    $('#basic-addon-add').text('');
  });

  $('.edit_discount').on('hide.bs.modal', function(){
    //hide error messages in modal
    $('#error-discount-name-edit').attr('hidden', true);
    $('#error-discount-type-edit').attr('hidden', true);
    $('#error-discount-value-edit').attr('hidden', true);

    //remove css style in modal
    $('#discount-name-edit').removeAttr('style');
    $('#discount-type-edit').removeAttr('style');
    $('#discount-value-edit').removeAttr('style');
    $('#basic-addon-edit').removeAttr('style');

    //remove defined values/classes
    $('#basic-addon-edit').text('');
    $('#discount-name-edit').removeAttr('disabled');
    $('#discount-type-edit').removeAttr('disabled');
  });

  //add discount
  $(document).on('click', '#create-discount', function() {
    $('#discount-type-add').on('change', function (e) {
    if($(this).val() == 'percentage')
    {
      $('#basic-addon-add').text("%");
    }
    else
    {
      $('#basic-addon-add').text("₱");
    }
  })
  });

  $('#modal-footer-discount-add').on('click', '#add-discount', function(event) {
  $.ajax({
    type: 'POST',
    url: '/staff/preferences/add_discount',
    data: {
            '_token': $('input[name=_token]').val(),
            'discount_name': $("#discount-name-add").val(),
            'discount_type': $("#discount-type-add").val(),
            'discount_value': $("#discount-value-add").val(),
          },
    success: function(data) {
      console.log(data);
      if ((data.errors)) {
          if(data.errors.discount_name)
          {
            $('#error-discount-name-add').removeAttr("hidden");
            $('#error-discount-name-add').text(data.errors.discount_name);
            $('#discount-name-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-discount-name-add').attr("hidden", true);
            $('#discount-name-add').removeAttr('style');
          }

          if(data.errors.discount_type)
          {
            $('#error-discount-type-add').removeAttr("hidden");
            $('#error-discount-type-add').text(data.errors.discount_type);
            $('#discount-type-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-discount-type-add').attr("hidden", true);
            $('#discount-type-add').removeAttr('style');
          }

          if(data.errors.discount_value)
          {
            $('#error-discount-value-add').removeAttr("hidden");
            $('#error-discount-value-add').text(data.errors.discount_value);
            $('#discount-value-add').css("border", "1px solid #cc0000");
            $('#basic-addon-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-discount-value-add').attr("hidden", true);
            $('#discount-value-add').removeAttr('style');
            $('#basic-addon-add').removeAttr('style');
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

  //edit discount
  $(document).on('click', '#edit-discount', function() {
    $('#discount-id-edit').val($(this).data('id'));
    $('#discount-name-edit').val($(this).data('name'));
    $('#discount-type-edit').val($(this).data('type'));

    if($(this).data('id') == '1')
    {
      $('#discount-name-edit').attr('disabled', true);
      $('#discount-type-edit').attr('disabled', true);
    }
    
    $('#discount-type-edit').on('change', function (e) {
      if($(this).val() == 'percentage')
      {
        $('#basic-addon-edit').text("%");
      }
      else
      {
        $('#basic-addon-edit').text("₱");
      }
    });
    
    $('#discount-value-edit').val($(this).data('value')); 
    var discount_value = $('#discount-value-edit').val();
    var percent = discount_value * 100;

    if($('#discount-type-edit').val() == 'percentage')
    {
      $('#discount-value-edit').val(percent);
      $('#basic-addon-edit').text('%')
    }
    else
    {
      $('#discount-value-edit').val();  
      $('#basic-addon-edit').text('₱');
    }
  });

  $('#modal-footer-discount-edit').on('click', '#update-discount', function(event) {
  $.ajax({
    type: 'POST',
    url: '/staff/preferences/update_discount',
    data: {
            '_token': $('input[name=_token]').val(),
            'discount_id': $('#discount-id-edit').val(),
            'discount_name': $("#discount-name-edit").val(),
            'discount_type': $("#discount-type-edit").val(),
            'discount_value': $("#discount-value-edit").val(),
          },
    success: function(data) {
        if ((data.errors)) {
          if(data.errors.discount_name)
          {
            $('#error-discount-name-edit').removeAttr("hidden");
            $('#error-discount-name-edit').text(data.errors.discount_name);
            $('#discount-name-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-discount-name-edit').attr("hidden", true);
            $('#discount-name-edit').removeAttr('style');
          }

          if(data.errors.discount_type)
          {
            $('#error-discount-type-edit').removeAttr("hidden");
            $('#error-discount-type-edit').text(data.errors.discount_type);
            $('#discount-type-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-discount-type-edit').attr("hidden", true);
            $('#discount-type-edit').removeAttr('style');
          }

          if(data.errors.discount_value)
          {
            $('#error-discount-value-edit').removeAttr("hidden");
            $('#error-discount-value-edit').text(data.errors.discount_value);
            $('#discount-value-edit').css("border", "1px solid #cc0000");
            $('#basic-addon-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-discount-value-edit').attr("hidden", true);
            $('#discount-value-edit').removeAttr('style');
            $('#basic-addon-edit').removeAttr('style');
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

  //delete discount
  $(document).on('click', '#delete-discount', function() {
    $('.discount-id-delete').text($(this).data('id'));
    $('#delete-name').text($(this).data('name'));
  });

  $('#modal-footer-discount-delete').on('click', '#destroy-discount', function(){
  $.ajax({
    type: 'POST',
    url: '/staff/preferences/delete_discount',
    data: {
      '_token': $('input[name=_token]').val(),
      'discount_id': $('.discount-id-delete').text()
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