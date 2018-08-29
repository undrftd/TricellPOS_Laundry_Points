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
    <h3 class="title">Admin</h3>
    <div class="nav nav-tabs justify-content-end memberstab" id="nav-tab" role="tablist">
      <a class="nav-item nav-link" id="nav-members-tab" href="/accounts/members" role="tab" aria-controls="nav-members" aria-selected="false">Members</a>
      <a class="nav-item nav-link" id="nav-staff-tab"  href="/accounts/staff" role="tab" aria-controls="nav-staff" aria-selected="false">Staff</a>
      <a class="nav-item nav-link active " id="nav-admin-tab"  href="/accounts/admin" role="tab" aria-controls="nav-admin" aria-selected="true">Admin</a>
    </div>
  </nav>

  <div class="row">
    <div class="col-md-8">
      <button type="button" class="btn btn-outline-info add-admin-btn" data-toggle="modal" data-target=".add_admin">Add Admin</button>
  </div>
  <div class="col-md-4">
    <form class="form ml-auto" action="/accounts/search_admin" method="GET">
      <div class="input-group">
          <input class="form-control" type="text" name="search" placeholder="Search" aria-label="Search" style="padding-left: 20px; border-radius: 40px;" id="admin-search">
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
          <th scope="col">Username</th>
          <th scope="col">Name</th>
          <th scope="col">Contact No.</th>
          <th scope="col">E-mail Address</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody class="td_class">
        @foreach($admins as $admin)
        <tr>
          <td class="td-center"><b>{{ $admin->card_number }}</b></td>
          <td class="td-center">{{ $admin->username }}</td>
          <td class="td-center">{{ $admin->firstname . " " . $admin->lastname }}</td>
          <td class="td-center">{{ $admin->contact_number }}</td>
          <td class="td-center">{{ $admin->email }}</td>
          <td>
            <button type="button" id="view-admin" class="btn btn-secondary view-btn" data-toggle="modal" data-target=".view_admin" data-id="{{ $admin->id}}" data-cardnumber ="{{$admin->card_number}}" data-username ="{{ $admin->username }}" data-firstname="{{$admin->firstname}}" data-lastname="{{$admin->lastname}}" data-address="{{$admin->address}}" data-contact="{{$admin->contact_number}}" data-email="{{$admin->email}}"><i class="material-icons md-18">info_outline</i></button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div>{{$admins->links()}}</div>

    <!----start of modal for add admin---->
    <div class="modal fade add_admin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Admin</h5>
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

        <div class="modal-footer" id="modal-footer-admin-add">
          <button type="submit" class="btn btn-info btn-savemem-modal" id= "add-admin">Save New Admin</button>
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
        </div>
      </form>
      </div>
    </div>
    </div>

    <!----end of modal---->
    <!----start of modal for View---->
    <div class="modal fade view_admin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      </br>

      <form class="nosubmitform">
      <fieldset id="admin-edit-fieldset" disabled>
        <input type="hidden" name="admin_id" id="admin-id-view">
          
        <div class="form-group row mx-auto">
          <label for="card-no" class="col-form-label col-md-3 modal-card">Card No:</label>
          <div class="col-md-9">
            <input type="text" name="card_number" class="form-control modal-card" id="cardnumber-view">
           <p id="error-cardnumber-view" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="username" class="col-form-label col-md-3 modal-user">Username:</label>
          <div class="col-md-9">
              <input type="text" name="username" class="form-control modal-card" id="username-view">
              <p id="error-username-view" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="password" class="col-form-label col-md-3 modal-password">Password:</label>
          <div class="col-md-9">
              <input type="password" name="password" class="form-control" value="{{rand()}}" id="password-view">
              <p id="error-password-view" class="error-add" hidden="hidden"></p>
          </div>
        </div>
          
        <div class="form-group row mx-auto confirm-password-view-div" hidden="hidden">
          <label for="password_confirmation" class="col-form-label col-md-3">Confirm Password:</label>
          <div class="col-md-9"> 
              <input type="password" name="password_confirmation" class="form-control" id="confirm-password-view">
              <p id="error-confirm-password-view" class="error-add" hidden="hidden"></p>
          </div>
        </div>
     

        <div class="form-group row mx-auto">
          <label for="first-name" class="col-form-label col-md-3 modal-fname">First Name:</label>
          <div class="col-md-9"> 
              <input type="text" name="firstname" class="form-control modal-fname" id="firstname-view">
              <p id="error-firstname-view" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="last-name" class="col-form-label col-md-3 modal-lname">Last Name:</label>
          <div class="col-md-9"> 
              <input type="text" name="lastname" class="form-control" id="lastname-view">
              <p id="error-lastname-view" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="address" class="col-form-label col-md-3 modal-address">Address:</label>
          <div class="col-md-9">
              <input type="text" name="address" class="form-control modal-add" id="address-view">
              <p id="error-address-view" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="contact" class="col-form-label col-md-3 modal-contact">Contact No:</label>
          <div class="col-md-9">
              <input type="text" name="contact_number" class="form-control" id="contact-view">
              <p id="error-contact-view" class="error-add" hidden="hidden"></p>
          </div>
        </div>

        <div class="form-group row mx-auto">
          <label for="email" class="col-form-label col-md-3 modal-mobile">E-mail Address:</label>
          <div class="col-md-9">
              <input type="text" name="email" class="form-control" id="email-view">
              <p id="error-email-view" class="error-add" hidden="hidden"></p>
          </div>
        </div>
      </fieldset>
      <div class="modal-footer" id="modal-footer-admin-edit">
          <button type="submit" class="btn btn-info btn-savemem-modal" value ="Save Changes"  id="update-admin" style="display: none;">Save Changes</button>
          <button type="button" class="btn btn-info btn-savemem-modal" onclick ="undisableField()" id="edit-admin">Edit</button>
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
      </div>
    </form>
    </div>
    </div>
    </div>
    <!----end of modal---->



  </div>

  <script type="text/javascript">
  $('.nosubmitform').submit(function(event){
    event.preventDefault();
  });

  $(document).on('click', '#view-admin', function() {
    $('#admin-id-view').val($(this).data('id'));
    $('#cardnumber-view').val($(this).data('cardnumber'));
    $('#username-view').val($(this).data('username'));
    $("#firstname-view").val($(this).data('firstname'));
    $("#lastname-view").val($(this).data('lastname'));
    $("#address-view").val($(this).data('address'));
    $("#contact-view").val($(this).data('contact'));
    $("#email-view").val($(this).data('email'));
  });

  $('.add_admin').on('hide.bs.modal', function(){
     //hide error messages in modal
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

  $('.view_admin').on('hide.bs.modal', function(){
    document.getElementById("admin-edit-fieldset").disabled = true;
    $("#edit-admin").show();
    $("#update-admin").hide();
    $('.confirm-password-view-div').attr("hidden", true);

    $('#error-cardnumber-view').attr("hidden", true);
    $('#error-username-view').attr("hidden", true);
    $('#error-password-view').attr("hidden", true);
    $('#error-confirm-password-view').attr("hidden", true);
    $('#error-firstname-view').attr("hidden", true);
    $('#error-lastname-view').attr("hidden", true);
    $('#error-address-view').attr("hidden", true);
    $('#error-contact-view').attr("hidden", true);
    $('#error-email-view').attr("hidden", true);

    //remove css style in modal
    $('#cardnumber-view').removeAttr('style');
    $('#username-view').removeAttr('style');
    $('#password-view').removeAttr('style');
    $('#confirm-password-view').removeAttr('style');
    $('#firstname-view').removeAttr('style');
    $('#lastname-view').removeAttr('style');
    $('#address-view').removeAttr('style');
    $('#contact-view').removeAttr('style');
    $('#email-view').removeAttr('style');

    $('#password-view').val(Math.random().toString(36).substring(7));
  });

  function undisableField() {
    document.getElementById("admin-edit-fieldset").disabled = false;
    $("#edit-admin").click(function(){
        $("#edit-admin").hide();
        $("#password-view").val("");
        $("#confirm-password-view").val("");
        $('.confirm-password-view-div').removeAttr("hidden");
    });
  }

  $(document).ready(function(){
    $("#edit-admin").click(function(){
        $("#update-admin").show();
    });
  });

  //success alerts - add, update, delete
  $(document).ready(function(){
  if(localStorage.getItem("update"))
  {
    swal({
            title: "Success!",
            text: "You have successfully updated the admin's details!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  else if(localStorage.getItem("delete"))
  {
    swal({
            title: "Success!",
            text: "You have successfully deleted the admin!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  else if(localStorage.getItem("add"))
  {
    swal({
            title: "Success!",
            text: "You have successfully added an admin!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
  }
  });

  //add admin
  $('#modal-footer-admin-add').on('click', '#add-admin', function(event) {
  $.ajax({
    type: 'POST',
    url: '/accounts/add_admin',
    data: {
            '_token': $('input[name=_token]').val(),
            'admin_id': $("#admin-id-add").val(),
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

  //edit admin
  $('#modal-footer-admin-edit').on('click', '#update-admin', function(event) {
  $.ajax({
    type: 'POST',
    url: '/accounts/update_admin',
    data: {
            '_token': $('input[name=_token]').val(),
            'card_number': $("#cardnumber-view").val(),
            'admin_id': $("#admin-id-view").val(),
            'username': $("#username-view").val(),
            'password': $("#password-view").val(),
            'password_confirmation': $("#confirm-password-view").val(),
            'firstname': $("#firstname-view").val(),
            'lastname': $("#lastname-view").val(),
            'address': $("#address-view").val(),
            'contact': $("#contact-view").val(),
            'email': $("#email-view").val()
          },
    success: function(data) {
      console.log(data);
      if ((data.errors)) {
          if(data.errors.card_number)
          {
            $('#error-cardnumber-view').removeAttr("hidden");
            $('#error-cardnumber-view').text(data.errors.card_number);
            $('#cardnumber-view').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-cardnumber-view').attr("hidden", true);
            $('#cardnumber-view').removeAttr('style');
          }

          if(data.errors.username)
          {
            $('#error-username-view').removeAttr("hidden");
            $('#error-username-view').text(data.errors.username);
            $('#username-view').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-username-view').attr("hidden", true);
            $('#username-view').removeAttr('style');
          }

          if(data.errors.password)
          {
            $('#error-password-view').removeAttr("hidden");
            $('#error-password-view').text(data.errors.password);
            $('#password-view').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-password-view').attr("hidden", true);
            $('#password-view').removeAttr('style');
          }

          if(data.errors.password_confirmation)
          {
            $('#error-confirm-password-view').removeAttr("hidden");
            $('#error-confirm-password-view').text(data.errors.password_confirmation);
            $('#confirm-password-view').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-confirm-password-view').attr("hidden", true);
            $('#confirm-password-view').removeAttr('style');
          }

          if(data.errors.firstname)
          {
            $('#error-firstname-view').removeAttr("hidden");
            $('#error-firstname-view').text(data.errors.firstname);
            $('#firstname-view').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-firstname-view').attr("hidden", true);
            $('#firstname-view').removeAttr('style');
          }

          if(data.errors.lastname)
          {
            $('#error-lastname-view').removeAttr("hidden");
            $('#error-lastname-view').text(data.errors.lastname);
            $('#lastname-view').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-lastname-view').attr("hidden", true);
            $('#lastname-view').removeAttr('style');
          }

          if(data.errors.address)
          {
            $('#error-address-view').removeAttr("hidden");
            $('#error-address-view').text(data.errors.address);
            $('#address-view').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-address-view').attr("hidden", true);
            $('#address-view').removeAttr('style');
          }

          if(data.errors.contact)
          {
            $('#error-contact-view').removeAttr("hidden");
            $('#error-contact-view').text(data.errors.contact);
            $('#contact-view').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-contact-view').attr("hidden", true);
            $('#contact-view').removeAttr('style');
          }

          if(data.errors.email)
          {
            $('#error-email-view').removeAttr("hidden");
            $('#error-email-view').text(data.errors.email);
            $('#email-view').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-email-view').attr("hidden", true);
            $('#email-view').removeAttr('style');
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
