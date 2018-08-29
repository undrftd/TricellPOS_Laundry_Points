@extends('layout')

@section('title')
MY ACCOUNT
@endsection

@section('css')
{{ asset('imports/css/members.css') }}
@endsection

@section('content')
</br>
<div class="container">
	<h3 class="title">My Account</h3>
	</br>
	<hr>
	<div class="tab-content" id="nav-tabContent">
		<br></br>
		<form class="nosubmitform">
			@csrf
			<fieldset id="profile-edit-fieldset" disabled>
			<div class="row">
				<div class ="col-md-6 members-info border-right">
					<div class="form-group row mx-auto">
						<label for="first-name" class="col-form-label col-md-3 modal-fname">First Name:</label>
						<div class="col-md-9">
							<input type="text" name="firstname" value="{{Auth::user()->firstname}}" class="form-control modal-fname" id="firstname-add">
							<p id="error-firstname-add" class="error-add" hidden="hidden"></p>
						</div>
					</div>
					<div class="form-group row mx-auto">
						<label for="first-name" class="col-form-label col-md-3 modal-fname">Last Name:</label>
						<div class="col-md-9">
							<input type="text" name="lastname" value="{{Auth::user()->lastname}}" class="form-control modal-fname" id="lastname-add">
							<p id="error-lastname-add" class="error-add" hidden="hidden"></p>
						</div>
					</div>
					<div class="form-group row mx-auto">
						<label for="last-name" class="col-form-label col-md-3 modal-lname">Address:</label>
						<div class="col-md-9">
							<input type="text" name="address" value="{{Auth::user()->address}}" class="form-control" id="address-add">
							<p id="error-address-add" class="error-add" hidden="hidden"></p>
						</div>
					</div>
					<div class="form-group row mx-auto">
						<label for="contact" class="col-form-label col-md-3 modal-contact">Contact No:</label>
						<div class="col-md-9">
							<input type="text" name="contact_number" value="{{Auth::user()->contact_number}}" class="form-control" id="contact-add">
							<p id="error-contact-add" class="error-add" hidden="hidden"></p>
						</div>
					</div>
					<div class="form-group row mx-auto">
						<label for="email" class="col-form-label col-md-3 modal-mobile">Email:</label>
						<div class="col-md-9">
							<input type="text" name="email" value="{{Auth::user()->email}}" class="form-control" id="email-add">
							<p id="error-email-add" class="error-add" hidden="hidden"></p>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row mx-auto">
						<label for="card-no" class="col-form-label col-md-4 modal-card">Username:</label>
						<div class="col-md-8">
							<input type="text" name="username" value="{{Auth::user()->username}}" class="form-control modal-card" id="username-add">
							<p id="error-username-add" class="error-add" hidden="hidden"></p>
						</div>
					</div>
					<div class="form-group row mx-auto">
						<label for="card-no" class="col-form-label col-md-4 modal-card">Password:</label>
						<div class="col-md-8">
							<input type="password" name="password" value="randompass" class="form-control modal-card" id="password-add">
							<p id="error-password-add" class="error-add" hidden="hidden"></p>
						</div>
					</div>
					<div class="form-group row mx-auto" id="confirm-pass" hidden="hidden">
						<label for="card-no" class="col-form-label col-md-4 modal-card">Confirm Password:</label>
						<div class="col-md-8">
							<input type="password" name="password_confirmation" class="form-control modal-card" id="confirmpassword-add">
							<p id="error-confirmpassword-add" class="error-add" hidden="hidden"></p>
						</div>
					</div>
					
				</div>
			</fieldset>
			</div>
			<br>
			<div class="modal-footer" id="modal-footer-profile-edit">
            <button type="submit" id="update-profile" class="btn btn-info btn-savemem-modal" style="display: none;">Save Changes</button>
            <button type="button" id="edit-profile" onclick ="undisableField()" class="btn btn-info btn-savemem-modal">Edit Profile</button>
            <button type="button" id="close-profile" class="btn btn-secondary btn-close-modal" data-dismiss="modal" style="display: none;">Close</button>
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

  	//success alerts - add, update, delete
  	$(document).ready(function(){
  	if(localStorage.getItem("update"))
  	{
    	swal({
	            title: "Success!",
	            text: "You have successfully updated your account!",
	            icon: "success",
	            button: "Close",
          	});
    	localStorage.clear();
  	}
  	});

	function undisableField() {
    	document.getElementById("profile-edit-fieldset").disabled = false;
    	$("#edit-profile").click(function(){
        	$("#edit-profile").hide();
    	});
  	}

  	$(document).ready(function(){
    	$("#edit-profile").click(function(){
        $("#update-profile").show();
        $("#close-profile").show();
        $('#confirm-pass').removeAttr('hidden');
        $('#password-add').val('');
    });

     $("#close-profile").click(function(){
        window.location.reload();
    });
  });

  //update profile
  $('#modal-footer-profile-edit').on('click', '#update-profile', function(event) {
  $.ajax({
    type: 'POST',
    url: '/update_account',
    data: {
            '_token': $('input[name=_token]').val(),
            'firstname': $("#firstname-add").val(),
            'lastname': $("#lastname-add").val(),
            'address': $("#address-add").val(),
            'email': $("#email-add").val(),
            'contact_number': $("#contact-add").val(),
            'username': $("#username-add").val(),
            'password': $("#password-add").val(),
            'password_confirmation': $("#confirmpassword-add").val(),
          },
    success: function(data) {
      console.log(data);
      if ((data.errors)) {
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

          if(data.errors.contact_number)
          {
            $('#error-contact-add').removeAttr("hidden");
            $('#error-contact-add').text(data.errors.contact_number);
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
            $('#error-confirmpassword-add').removeAttr("hidden");
            $('#error-confirmpassword-add').text(data.errors.password_confirmation);
            $('#confirmpassword-add').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-confirmpassword-add').attr("hidden", true);
            $('#confirmpassword-add').removeAttr('style');
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