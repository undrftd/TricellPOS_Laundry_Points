@extends('layout')

@section('title')
SYSTEM PREFERENCES
@endsection

@section('css')
{{ asset('imports/css/members.css') }}
@endsection

@section('content')
</br>
<div class="container">
  <!----sys pref nav--->
  <nav>
    <h3 class="title">System Profile</h3>
    <div class="nav nav-tabs justify-content-end memberstab" id="nav-tab" role="tablist">
      
      <a class="nav-item nav-link active " id="nav-profile-tab" href="/preferences/profile" role="tab" aria-controls="nav-profile" aria-selected="true">Profile
      <a class="nav-item nav-link" id="nav-discount-tab"  href="/preferences/discounts" role="tab" aria-controls="nav-discount" aria-selected="false">Discounts</a>
      <a class="nav-item nav-link" id="nav-discount-tab"  href="/preferences/backup" role="tab" aria-controls="nav-discount" aria-selected="false">Backup</a>
        
      </div>
    </nav>
    <!--sys pref nav---->
    <!---content of tabs start--->
    <div class="tab-content" id="nav-tabContent">
      
      
      <!-------profile------>
      <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        </br>
        <form class="nosubmitform">
          @csrf
          <fieldset id="profile-edit-fieldset" disabled>
          </br>
          <div class="row">
            <div class ="col-md-6 members-info border-right">
              
              <div class="form-group row mx-auto">
                <label for="first-name" class="col-form-label col-md-3 modal-fname">Name:</label>
                <div class="col-md-9">
                  <input type="text" name="branch_name" value="{{$profile->branch_name}}" class="form-control modal-fname" id="branch-name-profile">
                  <p id="error-branchname-profile" class="error-profile" hidden="hidden"></p>
                </div>
              </div>
              <div class="form-group row mx-auto">
                <label for="last-name" class="col-form-label col-md-3 modal-lname">Address:</label>
                <div class="col-md-9">
                  <input type="text" name="address" value="{{$profile->address}}" class="form-control" id="address-profile">
                  <p id="error-address-profile" class="error-profile" hidden="hidden"></p>
                </div>
              </div>
              <div class="form-group row mx-auto">
                <label for="contact" class="col-form-label col-md-3 modal-contact">Contact #:</label>
                <div class="col-md-9">
                  <input type="text" name="contact_number" value="{{$profile->contact_number}}" class="form-control" id="contact-profile">
                  <p id="error-contact-profile" class="error-profile" hidden="hidden"></p>
                </div>
              </div>
              <div class="form-group row mx-auto">
                <label for="email" class="col-form-label col-md-3 modal-mobile">Email:</label>
                <div class="col-md-9">
                  <input type="text" name="email" value="{{$profile->email}}" class="form-control" id="email-profile">
                  <p id="error-email-profile" class="error-profile" hidden="hidden"></p>
                </div>
              </div>
              <div class="form-group row mx-auto">
                <label for="card-no" class="col-form-label col-md-3 modal-card">TIN:</label>
                <div class="col-md-9">
                  <input type="text" name="tin" value="{{$profile->tin}}" class="form-control modal-card" id="tin-profile">
                  <p id="error-tin-profile" class="error-profile" hidden="hidden"></p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mx-auto">
                <label for="card-no" class="col-form-label col-md-4 modal-card">VAT:</label>
                <div class="col-md-8">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class ="input-group-text" id="basic-addon-profile">%</span>
                  </div>
                  <input type="text" name="vat" value="{{floatval($profile->vat)}}" class="form-control modal-card" id="vat-profile">
                </div>
                <p id="error-vat-profile" class="error-profile" hidden="hidden"></p>
                </div>
              </div>
              <div class="form-group row mx-auto">
                <label for="washer_timer" class="col-form-label col-md-4 modal-mobile">Washer Timer:</label>
                <div class="col-md-8">
                  <input type="text" name="washer_timer" value="{{$profile->washer_timer}}" class="form-control" id="washertimer-profile">
                  <p id="error-washertimer-profile" class="error-profile" hidden="hidden"></p>
                </div>
              </div>
              <div class="form-group row mx-auto">
                <label for="dryer_timer" class="col-form-label col-md-4 modal-mobile">Dryer Timer:</label>
                <div class="col-md-8">
                  <input type="text" name="dryer_timer" value="{{$profile->dryer_timer}}" class="form-control" id="dryertimer-profile">
                  <p id="error-dryertimer-profile" class="error-profile" hidden="hidden"></p>
                </div>
              </div>
              <div class="form-group row mx-auto">
                <label for="lowstock" class="col-form-label col-md-4 modal-mobile">Low Stock Indicator</label>
                <div class="col-md-8">
                  <input type="text" name="lowstock" value="{{$profile->low_stock}}" class="form-control" id="lowstock-profile">
                  <p id="error-lowstock-profile" class="error-profile" hidden="hidden"></p>
                </div>
              </div>
            </div>
              
            </div>
          </fieldset>
          </div>
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
            text: "You have successfully updated the profile!",
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
    });

     $("#close-profile").click(function(){
        window.location.reload();
    });
  });

  //update profile
  $('#modal-footer-profile-edit').on('click', '#update-profile', function(event) {
  $.ajax({
    type: 'POST',
    url: '/preferences/update_profile',
    data: {
            '_token': $('input[name=_token]').val(),
            'branch_name': $("#branch-name-profile").val(),
            'address': $("#address-profile").val(),
            'contact_number': $("#contact-profile").val(),
            'email': $("#email-profile").val(),
            'tin': $("#tin-profile").val(),
            'vat': $("#vat-profile").val(),
            'washertimer': $("#washertimer-profile").val(),
            'dryertimer': $("#dryertimer-profile").val(),
            'lowstock': $("#lowstock-profile").val(),
          },
    success: function(data) {
      console.log(data);
      if ((data.errors)) {
          if(data.errors.branch_name)
          {
            $('#error-branchname-profile').removeAttr("hidden");
            $('#error-branchname-profile').text(data.errors.branch_name);
            $('#branch-name-profile').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-branchname-profile').attr("hidden", true);
            $('#branch-name-profile').removeAttr('style');
          }

          if(data.errors.address)
          {
            $('#error-address-profile').removeAttr("hidden");
            $('#error-address-profile').text(data.errors.address);
            $('#address-profile').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-address-profile').attr("hidden", true);
            $('#address-profile').removeAttr('style');
          }

          if(data.errors.contact_number)
          {
            $('#error-contact-profile').removeAttr("hidden");
            $('#error-contact-profile').text(data.errors.contact_number);
            $('#contact-profile').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-contact-profile').attr("hidden", true);
            $('#contact-profile').removeAttr('style');
          }

          if(data.errors.email)
          {
            $('#error-email-profile').removeAttr("hidden");
            $('#error-email-profile').text(data.errors.email);
            $('#email-profile').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-email-profile').attr("hidden", true);
            $('#email-profile').removeAttr('style');
          }

          if(data.errors.tin)
          {
            $('#error-tin-profile').removeAttr("hidden");
            $('#error-tin-profile').text(data.errors.tin);
            $('#tin-profile').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-tin-profile').attr("hidden", true);
            $('#tin-profile').removeAttr('style');
          }

          if(data.errors.vat)
          {
            $('#error-vat-profile').removeAttr("hidden");
            $('#error-vat-profile').text(data.errors.vat);
            $('#vat-profile').css("border", "1px solid #cc0000");
            $('#basic-addon-profile').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-vat-profile').attr("hidden", true);
            $('#vat-profile').removeAttr('style');
            $('#basic-addon-profile').removeAttr('style');
          }

          if(data.errors.washertimer)
          {
            $('#error-washertimer-profile').removeAttr("hidden");
            $('#error-washertimer-profile').text(data.errors.washertimer);
            $('#washertimer-profile').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-washertimer-profile').attr("hidden", true);
            $('#washertimer-profile').removeAttr('style');
          }

          if(data.errors.dryertimer)
          {
            $('#error-dryertimer-profile').removeAttr("hidden");
            $('#error-dryertimer-profile').text(data.errors.dryertimer);
            $('#dryertimer-profile').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-dryertimer-profile').attr("hidden", true);
            $('#dryertimer-profile').removeAttr('style');
          }

          if(data.errors.lowstock)
          {
            $('#error-lowstock-profile').removeAttr("hidden");
            $('#error-lowstock-profile').text(data.errors.lowstock);
            $('#lowstock-profile').css("border", "1px solid #cc0000");
          }
          else
          {
            $('#error-lowstock-profile').attr("hidden", true);
            $('#lowstock-profile').removeAttr('style');
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