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
  <nav>
    <h3 class="title">Staff</h3>
    <div class="nav nav-tabs justify-content-end memberstab" id="nav-tab" role="tablist">
      <a class="nav-item nav-link" id="nav-members-tab" href="/accounts/members" role="tab" aria-controls="nav-members" aria-selected="false">Members</a>
      <a class="nav-item nav-link active " id="nav-staff-tab"  href="/accounts/staff" role="tab" aria-controls="nav-staff" aria-selected="false">Staff</a>
      <a class="nav-item nav-link " id="nav-admin-tab"  href="/accounts/admin" role="tab" aria-controls="nav-admin" aria-selected="true">Admin</a>
    </div>
  </nav>
  <!--end of members nav---->
<!---content of tabs start-->
  <div class="row">
    <div class="col-md-8">
        <button type="button" class="btn btn-outline-info add-staff-btn" data-toggle="modal" data-target=".add_staff">Add Staff</button>
    </div>
    <div class="col-md-4">
      <form class="form ml-auto" action="/accounts/search_staff" method="GET">
      <div class="input-group">
          <input class="form-control" type="text" name ="search" placeholder="Search" aria-label="Search" style="padding-left: 20px; border-radius: 40px;" id="staff-search">
          <div class="input-group-addon" style="margin-left: -50px; z-index: 3; border-radius: 40px; background-color: transparent; border:none;">
            <button class="btn btn-outline-info btn-sm" type="submit" style="border-radius: 100px;" id="staff-search-submit"><i class="material-icons">search</i></button>
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
          <th scope="col">Username</th>
          <th scope="col">Name</th>
          <th scope="col">Contact No.</th>
          <th scope="col">E-mail Address</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody class="td_class">
        @foreach($staffs as $staff)
        <tr class="staff{{$staff->id}}">
          <td class="td-center"><b>{{ $staff->card_number }}</b></td>
          <td class="td-center">{{ $staff->username }}</td>
          <td class="td-center">{{ $staff->firstname . " " . $staff->lastname }}</td>
          <td class="td-center">{{ $staff->contact_number }}</td>
          <td class="td-center">{{ $staff->email }}</td>
          <td>
            <button type="button" id="edit-staff" class="btn btn-primary edit-btn" data-toggle="modal" data-target=".edit_staff" data-id="{{ $staff->id }}" data-cardnumber="{{ $staff->card_number}}" data-username ="{{ $staff->username }}" data-firstname="{{$staff->firstname}}" data-lastname="{{$staff->lastname}}" data-address="{{$staff->address}}" data-contact="{{$staff->contact_number}}" data-email="{{$staff->email}}"><i class="material-icons md-18">mode_edit</i></button>
            <button type="button" id="delete-staff" class="btn btn-danger del-btn" data-id="{{ $staff->id }}" data-firstname="{{$staff->firstname}}" data-lastname="{{$staff->lastname}}" data-toggle="modal" data-target=".delete_staff"><i class="material-icons md-18">delete</i></button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{$staffs->links()}}

    <!----start of modal for add staff---->
    <div class="modal fade add_staff" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Staff</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        </br>

        <form id="add-form" class="nosubmitform">
        
        <div class="form-group row mx-auto">
          <label for="card-no" class="col-form-label col-md-3 modal-card">Card No:</label>
          <div class="col-md-9">
            <input type="text" name="card_number" class="form-control modal-card" id="cardnumber-add">
           <p id="error-cardnumber-add" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="username" class="col-form-label col-md-3 modal-user">Username:</label>
          <div class="col-md-9">
              <input type="text" name="username" class="form-control modal-card" id="username-add">
              <p id="error-username-add" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="password" class="col-form-label col-md-3 modal-password">Password:</label>
          <div class="col-md-9">
              <input type="password" name="password" class="form-control" id="password-add">
              <p id="error-password-add" class="error-add" hidden="hidden"></p>
          </div>
        </div>
          
        <div class="form-group row mx-auto">
          <label for="password_confirmation" class="col-form-label col-md-3 modal-password">Confirm Password:</label>
          <div class="col-md-9"> 
              <input type="password" name="password_confirmation" class="form-control" id="confirm-password-add">
              <p id="error-confirm-password-add" class="error-add" hidden="hidden"></p>
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
          <label for="email" class="col-form-label col-md-3 modal-mobile">E-mail Address:</label>
          <div class="col-md-9">
              <input type="text" name="email" class="form-control" id="email-add">
              <p id="error-email-add" class="error-add" hidden="hidden"></p>
          </div>
        </div>


        <div class="modal-footer" id="modal-footer-staff-add">
          <button type="submit" id="add-staff" class="btn btn-info btn-savemem-modal">Save New Staff</button>
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
        </div>
      </form>
      </div>
    </div>
    </div>

    <!----end of modal---->
    <!----start of modal for EDIT---->
    <div class="modal fade edit_staff" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Staff</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      </br>

      <form id="edit-form" class="nosubmitform">
      <input type="hidden" name="staff_id" id="staff-id-edit">
      
      <div class="form-group row mx-auto">
        <label for="card-no" class="col-form-label col-md-3 modal-card">Card No:</label>
        <div class="col-md-9">
            <input type="text" name="card_number" class="form-control modal-card" id="cardnumber-edit">
            <p id="error-cardnumber-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="username" class="col-form-label col-md-3 modal-user">Username:</label>
        <div class="col-md-9">
            <input type="text" name="username" class="form-control modal-card" id="username-edit">
            <p id="error-username-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="password" class="col-form-label col-md-3 modal-password">Password:</label>
        <div class="col-md-9">
            <input type="password" name="password" class="form-control" id="password-edit">
            <p id="error-password-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="password_confirmation" class="col-form-label col-md-3 modal-password">Confirm Password:</label>
        <div class="col-md-9">
            <input type="password" name="password_confirmation" class="form-control" id="confirm-password-edit">
            <p id="error-confirm-password-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>
    
      <div class="form-group row mx-auto">
        <label for="first-name" class="col-form-label col-md-3 modal-fname">First Name:</label>
        <div class="col-md-9">
            <input type="text" name="firstname" class="form-control modal-fname" id="firstname-edit">
            <p id="error-firstname-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="last-name"  class="col-form-label col-md-3 modal-lname">Last Name:</label>
        <div class="col-md-9">
            <input type="text" name="lastname" class="form-control" id="lastname-edit">
            <p id="error-lastname-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="address" class="col-form-label col-md-3 modal-address">Address:</label>
        <div class="col-md-9">
            <input type="text" name="address" class="form-control modal-add" id="address-edit">
            <p id="error-address-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="contact" class="col-form-label col-md-3 modal-contact">Contact No:</label>
        <div class="col-md-9">
            <input type="text" name="contact_number" class="form-control" id="contact-edit">
            <p id="error-contact-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="form-group row mx-auto">
        <label for="email" class="col-form-label col-md-3 modal-mobile">E-mail Address:</label>
        <div class="col-md-9">
            <input type="text" name="email" class="form-control" id="email-edit">
            <p id="error-email-edit" class="error-edit" hidden="hidden"></p>
        </div>
      </div>

      <div class="modal-footer" id="modal-footer-staff-edit">
        <button type="submit" id="update-staff" class="btn btn-info btn-savemem-modal">Save Changes</button></a>
        <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
      </div>
    </form>
    </div>
    </div>
    </div>
    <!----end of modal---->
    <!----start of modal for DELETE---->
    <form>
    <div class="modal fade delete_staff" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Delete Staff</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
        <center>  <p> Are you sure you want to permanently delete the account of <b><span id="delete-name"></span></b>?</p> </center>
        <span class="staff-id-delete" hidden="hidden"></span>
        </div>

    <div class="modal-footer" id="modal-footer-staff-delete">
      <button type="button" id="destroy-staff" class="btn btn-danger btn-savemem-modal">Yes</button>
      <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">No</button>
    </div>
    </div>
    </div>
    </div>
    </form>

    <!----end of modal---->

  </div>

  <script type="text/javascript">
  $('.nosubmitform').submit(function(event){
      event.preventDefault();
  });

  $('.edit_staff').on('hide.bs.modal', function(){
    //hide error messages in modal
    $('#error-cardnumber-edit').attr("hidden", true);
    $('#error-username-edit').attr("hidden", true);
    $('#error-password-edit').attr("hidden", true);
    $('#error-confirm-password-edit').attr("hidden", true);
    $('#error-firstname-edit').attr("hidden", true);
    $('#error-lastname-edit').attr("hidden", true);
    $('#error-address-edit').attr("hidden", true);
    $('#error-contact-edit').attr("hidden", true);
    $('#error-email-edit').attr("hidden", true);

    //remove css style in modal
    $('#cardnumber-edit').removeAttr('style');
    $('#username-edit').removeAttr('style');
    $('#password-edit').removeAttr('style');
    $('#confirm-password-edit').removeAttr('style');
    $('#firstname-edit').removeAttr('style');
    $('#lastname-edit').removeAttr('style');
    $('#address-edit').removeAttr('style');
    $('#contact-edit').removeAttr('style');
    $('#email-edit').removeAttr('style');
  });

  $('.add_staff').on('hide.bs.modal', function(){
    //hide error messages in modal
    $('#cardnumber-add').val('');
    $('#username-add').val('');
    $('#password-add').val('');
    $('#confirm-password-add').val('');
    $('#firstname-add').val('');
    $('#lastname-add').val('');
    $('#address-add').val('');
    $('#contact-add').val('');
    $('#email-add').val('');
    $('#error-cardnumber-add').attr("hidden", true);
    $('#error-username-add').attr("hidden", true);
    $('#error-password-add').attr("hidden", true);
    $('#error-confirm-password-add').attr("hidden", true);
    $('#error-firstname-add').attr("hidden", true);
    $('#error-lastname-add').attr("hidden", true);
    $('#error-address-add').attr("hidden", true);
    $('#error-contact-add').attr("hidden", true);
    $('#error-email-add').attr("hidden", true);

    //remove css style in modal
    $('#cardnumber-add').removeAttr('style');
    $('#username-add').removeAttr('style');
    $('#password-add').removeAttr('style');
    $('#confirm-password-add').removeAttr('style');
    $('#firstname-add').removeAttr('style');
    $('#lastname-add').removeAttr('style');
    $('#address-add').removeAttr('style');
    $('#contact-add').removeAttr('style');
    $('#email-add').removeAttr('style');
  });

  //success alerts - add, update, delete
  $(document).ready(function(){
  if(localStorage.getItem("update"))
  {
    swal({
            title: "Success!",
            text: "You have successfully updated the staff's details!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  else if(localStorage.getItem("delete"))
  {
    swal({
            title: "Success!",
            text: "You have successfully deleted the staff!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  else if(localStorage.getItem("add"))
  {
    swal({
            title: "Success!",
            text: "You have successfully added a staff!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  });

  //add staff
  $('#modal-footer-staff-add').on('click', '#add-staff', function(event) {
  $.ajax({
    type: 'POST',
    url: '/accounts/add_staff',
    data: {
            '_token': $('input[name=_token]').val(),
            'card_number': $("#cardnumber-add").val(),
            'username': $("#username-add").val(),
            'password': $("#password-add").val(),
            'password_confirmation': $("#confirm-password-add").val(),
            'firstname': $("#firstname-add").val(),
            'lastname': $("#lastname-add").val(),
            'address': $("#address-add").val(),
            'contact': $("#contact-add").val(),
            'email': $("#email-add").val()
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

          if(data.errors.username)
          {
            $('#error-username-add').removeAttr("hidden");
            $('#error-username-add').text(data.errors.username);
            $('#username-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-username-add').attr("hidden", true);
            $('#username-add').removeAttr('style');
          }

          if(data.errors.password)
          {
            $('#error-password-add').removeAttr("hidden");
            $('#error-password-add').text(data.errors.password);
            $('#password-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-password-add').attr("hidden", true);
            $('#password-add').removeAttr('style');
          }

          if(data.errors.password_confirmation)
          {
            $('#error-confirm-password-add').removeAttr("hidden");
            $('#error-confirm-password-add').text(data.errors.password_confirmation);
            $('#confirm-password-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-confirm-password-add').attr("hidden", true);
            $('#confirm-password-add').removeAttr('style');
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


  // edit staff
  $(document).on('click', '#edit-staff', function() {
    $('#staff-id-edit').val($(this).data('id'));
    $('#cardnumber-edit').val($(this).data('cardnumber'));
    $('#username-edit').val($(this).data('username'));
    $('#password-edit').val("");
    $('#confirm-password-edit').val("");
    $("#firstname-edit").val($(this).data('firstname'));
    $("#lastname-edit").val($(this).data('lastname'));
    $("#address-edit").val($(this).data('address'));
    $("#contact-edit").val($(this).data('contact'));
    $("#email-edit").val($(this).data('email'));
  });

  $('#modal-footer-staff-edit').on('click', '#update-staff', function(event) {
  $.ajax({
    type: 'POST',
    url: '/accounts/update_staff',
    data: {
            '_token': $('input[name=_token]').val(),
            'staff_id': $("#staff-id-edit").val(),
            'card_number': $("#cardnumber-edit").val(),
            'username': $("#username-edit").val(),
            'password': $("#password-edit").val(),
            'password_confirmation': $("#confirm-password-edit").val(),
            'firstname': $("#firstname-edit").val(),
            'lastname': $("#lastname-edit").val(),
            'address': $("#address-edit").val(),
            'contact': $("#contact-edit").val(),
            'email': $("#email-edit").val()
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

          if(data.errors.username)
          {
            $('#error-username-edit').removeAttr("hidden");
            $('#error-username-edit').text(data.errors.username);
            $('#username-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-username-edit').attr("hidden", true);
            $('#username-edit').removeAttr('style');
          }

          if(data.errors.password)
          {
            $('#error-password-edit').removeAttr("hidden");
            $('#error-password-edit').text(data.errors.password);
            $('#password-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-password-edit').attr("hidden", true);
            $('#password-edit').removeAttr('style');
          }

          if(data.errors.password_confirmation)
          {
            $('#error-confirm-password-edit').removeAttr("hidden");
            $('#error-confirm-password-edit').text(data.errors.password_confirmation);
            $('#confirm-password-edit').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-confirm-password-edit').attr("hidden", true);
            $('#confirm-password-edit').removeAttr('style');
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


  //delete staff
  $(document).on('click', '#delete-staff', function() {
    $('.staff-id-delete').text($(this).data('id'));
    $('#delete-name').text($(this).data('firstname') + " " + $(this).data('lastname'));
  });

  $('#modal-footer-staff-delete').on('click', '#destroy-staff', function(){
  $.ajax({
    type: 'POST',
    url: '/accounts/delete_staff',
    data: {
      '_token': $('input[name=_token]').val(),
      'staff_id': $('.staff-id-delete').text()
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
