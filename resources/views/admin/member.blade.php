@extends('layout')

@section('title')
ACCOUNTS
@endsection

@section('css')
{{ asset('imports/css/members.css') }}
@endsection

@section('content')

</br>
<div class="container">
  <!--members nav-->
  <h3 class="title">Members</h3>

  <nav>
    <div class="nav nav-tabs justify-content-end memberstab" id="nav-tab" role="tablist">
      <a class="nav-item nav-link active" id="nav-members-tab" href="/accounts/members" role="tab" aria-controls="nav-members" aria-selected="false">Members</a>
      <a class="nav-item nav-link" id="nav-staff-tab"  href="/accounts/staff" role="tab" aria-controls="nav-staff" aria-selected="false">Staff</a>
      <a class="nav-item nav-link" id="nav-admin-tab"  href="/accounts/admin" role="tab" aria-controls="nav-admin" aria-selected="true">Admin</a>
    </div>
  </nav>
  <!--end of members nav---->
<!---content of tabs start-->

  <div class="row">
    <div class="col-md-8">
    <button type="button" class="btn btn-outline-info add-mem-btn" data-toggle="modal" data-target=".bd-example-modal-lg">
      Add Member
    </button>
  </div>
  <div class="col-md-4">
    <form class="form ml-auto" action="/accounts/search_member" method="GET">
			<div class="input-group">
    			<input class="form-control" type="text" name ="search" placeholder="Search" aria-label="Search" style="padding-left: 20px; border-radius: 40px;" id="member-search">
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
          <th scope="col">Card No.</th>
          <th scope="col">Name</th>
          <th scope="col">Contact No.</th>
          <th scope="col">Load</th>
          <th scope="col">Points</th>
          <th scope="col" colspan="2">Actions</th>
        </tr>
      </thead>
      <tbody class="td_class">
      	@foreach($members as $member)
        <tr>
          <th scope="row" class="td-center">{{$member->card_number}}</th>
          <td class="td-center"> {{$member->firstname . " " . $member->lastname}}</td>
          <td class="td-center">{{$member->contact_number}}</td>
          <td class="td-center">₱ {{$member->balance->load_balance}}</td>
          <td class="td-center">{{$member->balance->points_balance}}</td>
          <td>
          	<button type="button" id="edit-member" class="btn btn-primary edit-btn" data-toggle="modal" data-target=".edit_member" data-id="{{ $member->id }}" data-cardnumber ="{{ $member->card_number }}" data-firstname="{{$member->firstname}}" data-lastname="{{$member->lastname}}" data-address="{{$member->address}}" data-contact="{{$member->contact_number}}" data-email="{{$member->email}}" data-points="{{$member->balance->points_balance}}"><i class="material-icons md-18">mode_edit</i></button>
            <button type="button" id="reload-member" class="btn btn-success edit-btn" data-toggle="modal" data-target=".reload_member" data-id="{{$member->id}}" data-load="{{$member->balance->load_balance}}" data-points="{{$member->balance->points}}"><i class="pp-reload"></i></button>
          	<button type="button" id="delete-member" class="btn btn-danger del-btn" data-id="{{$member->id}}" data-firstname="{{$member->firstname}}" data-lastname="{{$member->lastname}}" data-toggle="modal" data-target=".delete_member"><i class="material-icons md-18">delete</i></button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div>{{$members->links()}}</div>

     <div class="modal fade reload_member" tabindex="-1" role="dialog">
     <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Reload Member</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

      <form class="nosubmitform">

      <div class="form-group">
      <input type="hidden" name="member_id" id="member-id-reload">
      <div class="container-fluid">
      </br>

      <div class="form-group row mx-auto">
      <label for="points" class="col-form-label col-sm-5 modal-load">Current Load:</label>

        <div class="col-sm-7">
          <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon-load">₱</span>
            </div>
            <input type="text" name="load" class="form-control modal-add" id="load-reload" disabled="disabled">
          </div>

          <p id="error-load-reload" class="error-reload" hidden="hidden"></p>
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

          <p id="error-reload-amount-reload" class="error-reload" hidden="hidden"></p>
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

          <p id="error-payment-amount-reload" class="error-reload" hidden="hidden"></p>
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

          <p id="error-change-reload" class="error-reload" hidden="hidden"></p>
        </div>
      </div>

      </div>
      </div>

      <div class="modal-footer" id="modal-footer-member-reload">
        <button type="submit" class="btn btn-info btn-savemem-modal" id="update-reload-member">Reload</button>
        <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Cancel</button>
      </div>
      </form>
      </div>
    </div>
    </div>
    <!----end of modal---->

    <!----start of modal for add members---->
    <div class="modal fade bd-example-modal-lg add_member" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add New Member</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form class="nosubmitform">
        </br>
    <div class="row">
      <div class ="col-md-6 members-info border-right">
        <div class="form-group row mx-auto">
          <label for="card-no" class="col-form-label col-md-3 modal-card">Card No:</label>
          <div class="col-md-9">
            <input type="text" name="card_number" class="form-control modal-card" id="cardnumber-add">
      	   <p id="error-cardnumber-add" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="first-name" class="col-form-label col-md-3 modal-fname">First Name:</label>
          <div class="col-md-9">
            <input type="text" name="firstname" class="form-control modal-fname" id="firstname-add">
            <p id="error-firstname-add" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="last-name" class="col-form-label col-md-3 modal-lname">Last Name:</label>
          <div class="col-md-9">
            <input type="text" name="lastname" class="form-control" id="lastname-add">
            <p id="error-lastname-add" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="address" class="col-form-label col-md-3 modal-address">Address:</label>
          <div class="col-md-9">
            <input type="text" name="address" class="form-control modal-add" id="address-add">
            <p id="error-address-add" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="contact" class="col-form-label col-md-3 modal-contact">Contact No:</label>
          <div class="col-md-9">
            <input type="text" name="contact_number" class="form-control" id="contact-add">
            <p id="error-contact-add" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="email" class="col-form-label col-md-3 modal-mobile">Email:</label>
          <div class="col-md-9">
            <input type="text" name="email" class="form-control" id="email-add">
            <p id="error-email-add" class="error-add" hidden="hidden"></p>
          </div>
        </div>
    </div>

    <div class="col-md-6">
      
    <div class="form-group row mx-auto">
      <label for="points" class="col-form-label col-sm-4 modal-points">Load Amount:</label>
        <div class="col-sm-8">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon-initial-amount-add">₱</span>
            </div>
            <input type="text" name="initial_amount" class="form-control modal-add" id="initial-amount-add" autocomplete="off">
          </div>

          <p id="error-initial-amount-add" class="error-add" hidden="hidden"></p>
        </div>
      </div>
         

      <div class="form-group row mx-auto">
      <label for="points" class="col-form-label col-sm-4 modal-load">Payment Amount:</label>

        <div class="col-sm-8">
          <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon-payment-amount-add">₱</span>
            </div>
            <input type="text" name="payment_amount" class="form-control modal-add" id="payment-amount-add" autocomplete="off">
          </div>

          <p id="error-payment-amount-add" class="error-add" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
      <label for="points" class="col-form-label col-sm-4 modal-load">Change:</label>

        <div class="col-sm-8">
          <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon-change-add">₱</span>
            </div>
            <input type="text" name="change_amount" class="form-control modal-add" id="change-amount-add" disabled="disabled">
          </div>

          <p id="error-change-amount-add" class="error-add" hidden="hidden"></p>
        </div>
      </div>
    </div>
  </div>
        <!--modal footer-->
        <div class="modal-footer" id="modal-footer-member-add">
          <button type="submit" id="add-member" class="btn btn-info btn-savemem-modal">Save New Member</button>
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
        </div>
      </form>
      </div>
    </div>
    </div>

    <!----end of modal---->
    <!----start of modal for EDIT---->
    <div class="modal fade edit_member" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Member</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      </br>

      <form class="nosubmitform">
      </br>
      <input type="hidden" name="member_id" id="member-id-edit">

      <div class="form-group row mx-auto">
        <label for="card-no" class="col-form-label col-md-2 modal-card">Card No:</label>
        <div class="col-md-10">
            <input type="text" name="card_number" class="form-control modal-card" id="cardnumber-edit">
            <p id="error-cardnumber-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="first-name" class="col-form-label col-md-2 modal-fname">First Name:</label>
        <div class="col-md-10">
            <input type="text" name="firstname" class="form-control modal-fname" id="firstname-edit">
    	      <p id="error-firstname-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="last-name" class="col-form-label col-md-2 modal-lname">Last Name:</label>
        <div class="col-md-10">
            <input type="text" name="lastname" class="form-control" id="lastname-edit">
    	      <p id="error-lastname-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="address" class="col-form-label col-md-2 modal-address">Address:</label>
        <div class="col-md-10">
            <input type="text" name="address" class="form-control modal-add" id="address-edit">
    	      <p id="error-address-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="contact" class="col-form-label col-md-2 modal-contact">Contact No:</label>
        <div class="col-md-10">
            <input type="text" name="contact_number" class="form-control" id="contact-edit">
    	      <p id="error-contact-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="email" class="col-form-label col-md-2 modal-mobile">Email:</label>
        <div class="col-md-10">
            <input type="text" name="email" class="form-control" id="email-edit">
    	      <p id="error-email-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="points" class="col-form-label col-md-2 modal-mobile">Points:</label>
        <div class="col-md-10">
            <input type="text" name="points" class="form-control" id="points-edit">
            <p id="error-points-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="modal-footer" id="modal-footer-member-edit">
        <button type="submit" class="btn btn-info btn-savemem-modal" id="update-member">Save Changes</button>
        <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
      </div>
    </div>

    </form>
    </div>
    </div>
    </div>
    <!----end of modal---->
    <!----start of modal for DELETE---->
    <div class="modal fade delete_member" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Delete Member</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
        <center>  <p> Are you sure you want to permanently delete the account of <b><span id="delete-name"></span></b>?</p>
        <span class="member-id-delete" hidden="hidden"></span>
    </div>

    <div class="modal-footer" id="modal-footer-member-delete">
      <button type="button" id="destroy-member" class="btn btn-danger btn-savemem-modal">Yes</button>
      <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">No</button>

    </div>
    </div>
    </div>
    </div>


    <!----end of modal---->

  </div>

  <script type="text/javascript">

  $('.nosubmitform').submit(function(event){
    event.preventDefault();
  });

  $('.add_member').on('hide.bs.modal', function(){
    //hide error messages in modal
    $('#cardnumber-add').val('');
    $('#load-add').val('');
    $('#firstname-add').val('');
    $('#lastname-add').val('');
    $('#address-add').val('');
    $('#contact-add').val('');
    $('#email-add').val('');
    $('#initial-amount-add').val('');
    $('#payment-amount-add').val('');
    $('#change-amount-add').val('');
    $('#error-cardnumber-add').attr('hidden', true);
    $('#error-load-add').attr('hidden', true);
    $('#error-firstname-add').attr('hidden', true);
    $('#error-lastname-add').attr('hidden', true);
    $('#error-address-add').attr('hidden', true);
    $('#error-contact-add').attr('hidden', true);
    $('#error-email-add').attr('hidden', true);
    $('#error-initial-amount-add').attr('hidden', true);
    $('#error-payment-amount-add').attr('hidden', true);

    //remove css style in modal
    $('#cardnumber-add').removeAttr('style');
    $('#load-add').removeAttr('style');
    $('#firstname-add').removeAttr('style');
    $('#lastname-add').removeAttr('style');
    $('#address-add').removeAttr('style');
    $('#contact-add').removeAttr('style');
    $('#email-add').removeAttr('style');
    $('#initial-amount-add').removeAttr('style');
    $('#payment-amount-add').removeAttr('style');
    $('#basic-addon-initial-amount-add').removeAttr('style');
    $('#basic-addon-payment-amount-add').removeAttr('style');
  });

  $('.edit_member').on('hide.bs.modal', function(){
    //hide error messages in modal
    $('#error-cardnumber-edit').attr('hidden', true);
    $('#error-firstname-edit').attr('hidden', true);
    $('#error-lastname-edit').attr('hidden', true);
    $('#error-address-edit').attr('hidden', true);
    $('#error-contact-edit').attr('hidden', true);
    $('#error-email-edit').attr('hidden', true);
    $('#error-points-edit').attr('hidden', true);

    //remove css style in modal
    $('#cardnumber-edit').removeAttr('style');
    $('#firstname-edit').removeAttr('style');
    $('#lastname-edit').removeAttr('style');
    $('#address-edit').removeAttr('style');
    $('#contact-edit').removeAttr('style');
    $('#email-edit').removeAttr('style');
    $('#points-edit').removeAttr('style');
  });

  $('.reload_member').on('hide.bs.modal', function(){
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

  //success alerts - add, update, delete
  $(document).ready(function(){
  if(localStorage.getItem("update"))
  {
    swal({
            title: "Success!",
            text: "You have successfully updated the member's details!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  else if(localStorage.getItem("delete"))
  {
    swal({
            title: "Success!",
            text: "You have successfully deleted the member!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  else if(localStorage.getItem("add"))
  {
    swal({
            title: "Success!",
            text: "You have successfully added a member!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  else if(localStorage.getItem("reload"))
  {
    swal({
            title: "Success!",
            text: "You have successfully reloaded the member's account!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  });

  //add member
  $('#modal-footer-member-add').on('click', '#add-member', function(event) {
  $.ajax({
    type: 'POST',
    url: '/accounts/add_member',
    data: {
            '_token': $('input[name=_token]').val(),
            'card_number': $("#cardnumber-add").val(),
            'firstname': $("#firstname-add").val(),
            'lastname': $("#lastname-add").val(),
            'address': $("#address-add").val(),
            'contact': $("#contact-add").val(),
            'email': $("#email-add").val(),
            'initial_amount': $("#initial-amount-add").val(),
            'payment_amount': $("#payment-amount-add").val(),
            'change_amount': $("#change-amount-add").val()
          },
    success: function(data) {
      console.log(data);
      if ((data.errors)) {
          if(data.errors.card_number)
          {
            $('#error-cardnumber-add').removeAttr("hidden");
            $('#error-cardnumber-add').text(data.errors.card_number);
            $('#cardnumber-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-cardnumber-add').attr("hidden", true);
            $('#cardnumber-add').removeAttr('style');
          }

          if(data.errors.firstname)
          {
            $('#error-firstname-add').removeAttr("hidden");
            $('#error-firstname-add').text(data.errors.firstname);
            $('#firstname-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-firstname-add').attr("hidden", true);
            $('#firstname-add').removeAttr('style');
          }

          if(data.errors.lastname)
          {
            $('#error-lastname-add').removeAttr("hidden");
            $('#error-lastname-add').text(data.errors.lastname);
            $('#lastname-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-lastname-add').attr("hidden", true);
            $('#lastname-add').removeAttr('style');
          }

          if(data.errors.address)
          {
            $('#error-address-add').removeAttr("hidden");
            $('#error-address-add').text(data.errors.address);
            $('#address-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-address-add').attr("hidden", true);
            $('#address-add').removeAttr('style');
          }

          if(data.errors.contact)
          {
            $('#error-contact-add').removeAttr("hidden");
            $('#error-contact-add').text(data.errors.contact);
            $('#contact-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-contact-add').attr("hidden", true);
            $('#contact-add').removeAttr('style');
          }

          if(data.errors.email)
          {
            $('#error-email-add').removeAttr("hidden");
            $('#error-email-add').text(data.errors.email);
            $('#email-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-email-add').attr("hidden", true);
            $('#email-add').removeAttr('style');
          }

          if(data.errors.initial_amount)
          {
            $('#error-initial-amount-add').removeAttr("hidden");
            $('#error-initial-amount-add').text(data.errors.initial_amount);
            $('#initial-amount-add').css("border", "1px solid #cc0000");
            $('#basic-addon-initial-amount-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-initial-amount-add').attr("hidden", true);
            $('#initial-amount-add').removeAttr('style');
            $('#basic-addon-initial-amount-add').removeAttr('style');
          }

          if(data.errors.payment_amount)
          {
            $('#error-payment-amount-add').removeAttr("hidden");
            $('#error-payment-amount-add').text(data.errors.payment_amount);
            $('#payment-amount-add').css("border", "1px solid #cc0000");
            $('#basic-addon-payment-amount-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-payment-amount-add').attr("hidden", true);
            $('#payment-amount-add').removeAttr('style');
            $('#basic-addon-payment-amount-add').removeAttr('style');
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

  $('#initial-amount-add, #payment-amount-add').on('input',function() {
    var reload_amount = parseFloat($('#initial-amount-add').val());
    var payment = parseFloat($('#payment-amount-add').val());
    $('#change-amount-add').val((payment - reload_amount ? payment - reload_amount : 0).toFixed(2));
  });

  //reload member
  $(document).on('click', '#reload-member', function() {
    $('#member-id-reload').val($(this).data('id'));
    $('#load-reload').val($(this).data('load'));

    if($('#change-reload').val() == ' ')
    {
      $('#update-reload-member').removeAttr('disabled');
    }
    else
    {
      $('#update-reload-member').attr('disabled', true);
    }
  });

  $('#reload-amount-reload, #payment-amount-reload').on('input',function() {
      var reload_amount = parseFloat($('#reload-amount-reload').val());
      var payment = parseFloat($('#payment-amount-reload').val());
      $('#change-reload').val((payment - reload_amount ? payment - reload_amount : 0).toFixed(2));

      //required fields
      // if(($('#reload-amount-reload').val() == '') && ($('#payment-amount-reload').val() == ''))
      // {
      //   $('#error-reload-amount-reload').removeAttr("hidden");
      //   $('#error-reload-amount-reload').text("Reload Amount field is required.");
      //   $('#reload-amount-reload').css("border", "1px solid #cc0000");
      //   $('#basic-addon-reload-amount').css("border", "1px solid #cc0000");
      //   $('#update-reload-member').attr('hidden', true);

      //   $('#error-payment-amount-reload').removeAttr("hidden");
      //   $('#error-payment-amount-reload').text("Payment Amount field is required.");
      //   $('#payment-amount-reload').css("border", "1px solid #cc0000");
      //   $('#basic-addon-payment-amount').css("border", "1px solid #cc0000");
      //   $('#update-reload-member').attr('hidden', true);
      // }
      // else
      // {
      //   $('#error-reload-amount-reload').attr("hidden", true);
      //   $('#error-reload-amount-reload').text("");
      //   $('#reload-amount-reload').removeAttr("style")
      //   $('#basic-addon-reload-amount').removeAttr("style");

      //   $('#error-reload-amount-reload').attr("hidden", true);
      //   $('#error-payment-amount-reload').text("");
      //   $('#payment-amount-reload').removeAttr("style")
      //   $('#basic-addon-payment-amount').removeAttr("style");
      //   $('#update-reload-member').attr('hidden', true);
      // }

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

  $('#modal-footer-member-reload').on('click', '#update-reload-member', function(event) {
  $.ajax({
    type: 'POST',
    url: '/accounts/reload_member',
    data: {
            '_token': $('input[name=_token]').val(),
            'member_id': $('#member-id-reload').val(),
            'current_load': $('#load-reload').val(),
            'reload_amount': $('#reload-amount-reload').val(),
            'payment_amount': $('#payment-amount-reload').val(),
            'change_amount': $('#change-reload').val()
          },
    success: function(data) {
        console.log(data);
        localStorage.setItem("reload","success");
        window.location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
  });

  //edit member
  $(document).on('click', '#edit-member', function() {
  	$('#member-id-edit').val($(this).data('id'));
    $('#cardnumber-edit').val($(this).data('cardnumber'));
    $("#firstname-edit").val($(this).data('firstname'));
    $("#lastname-edit").val($(this).data('lastname'));
    $("#address-edit").val($(this).data('address'));
    $("#contact-edit").val($(this).data('contact'));
    $("#email-edit").val($(this).data('email'));
    $("#points-edit").val($(this).data('points'));
  });

  $('#modal-footer-member-edit').on('click', '#update-member', function(event) {
  $.ajax({
    type: 'POST',
    url: '/accounts/update_member',
    data: {
            '_token': $('input[name=_token]').val(),
            'member_id': $("#member-id-edit").val(),
            'card_number': $("#cardnumber-edit").val(),
            'firstname': $("#firstname-edit").val(),
            'lastname': $("#lastname-edit").val(),
            'address': $("#address-edit").val(),
            'contact': $("#contact-edit").val(),
            'email': $("#email-edit").val(),
            'points': $("#points-edit").val(),
          },
    success: function(data) {
      console.log(data);
      if ((data.errors)) {
          if(data.errors.card_number)
          {
            $('#error-cardnumber-edit').removeAttr("hidden");
            $('#error-cardnumber-edit').text(data.errors.card_number);
            $('#cardnumber-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-cardnumber-edit').attr("hidden", true);
            $('#cardnumber-edit').removeAttr('style');
          }

          if(data.errors.firstname)
          {
            $('#error-firstname-edit').removeAttr("hidden");
            $('#error-firstname-edit').text(data.errors.firstname);
            $('#firstname-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-firstname-edit').attr("hidden", true);
            $('#firstname-edit').removeAttr('style');
          }

          if(data.errors.lastname)
          {
            $('#error-lastname-edit').removeAttr("hidden");
            $('#error-lastname-edit').text(data.errors.lastname);
            $('#lastname-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-lastname-edit').attr("hidden", true);
            $('#lastname-edit').removeAttr('style');
          }

          if(data.errors.address)
          {
            $('#error-address-edit').removeAttr("hidden");
            $('#error-address-edit').text(data.errors.address);
            $('#address-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-address-edit').attr("hidden", true);
            $('#address-edit').removeAttr('style');
          }

          if(data.errors.contact)
          {
            $('#error-contact-edit').removeAttr("hidden");
            $('#error-contact-edit').text(data.errors.contact);
            $('#contact-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-contact-edit').attr("hidden", true);
            $('#contact-edit').removeAttr('style');
          }

          if(data.errors.email)
          {
            $('#error-email-edit').removeAttr("hidden");
            $('#error-email-edit').text(data.errors.email);
            $('#email-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-email-edit').attr("hidden", true);
            $('#email-edit').removeAttr('style');
          }

          if(data.errors.points)
          {
            $('#error-points-edit').removeAttr("hidden");
            $('#error-points-edit').text(data.errors.points);
            $('#points-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-points-edit').attr("hidden", true);
            $('#points-edit').removeAttr('style');
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

  //delete member
  $(document).on('click', '#delete-member', function() {
    $('.member-id-delete').text($(this).data('id'));
    $('#delete-name').text($(this).data('firstname') + " " + $(this).data('lastname'));
  });

  $('#modal-footer-member-delete').on('click', '#destroy-member', function(){
  $.ajax({
    type: 'POST',
    url: '/accounts/delete_member',
    data: {
      '_token': $('input[name=_token]').val(),
      'member_id': $('.member-id-delete').text()
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
