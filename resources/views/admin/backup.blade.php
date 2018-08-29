@extends('layout')

@section('title')
BACKUP
@endsection

@section('css')
{{ asset('imports/css/inventory.css') }}
@endsection

@section('content')

</br>
<div class="container">
<!---title inventory-->
<h3 class="title">Backup Administration </h3>
  <nav>
    <div class="nav nav-tabs justify-content-end memberstab" id="nav-tab" role="tablist">
      <a class="nav-item nav-link" id="nav-profile-tab"  href="/preferences/profile" role="tab" aria-controls="nav-profile" aria-selected="true">Profile
      <a class="nav-item nav-link" id="nav-discount-tab"  href="/preferences/discounts" role="tab" aria-controls="nav-discount" aria-selected="false">Discounts</a>
      <a class="nav-item nav-link  active" id="nav-discount-tab"  href="/preferences/backup" role="tab" aria-controls="nav-discount" aria-selected="false">Backup</a>
      </div>
    </nav>
<!---end of title inventory-->
<!--second row add item button and search bar--->
<div class="row">
    <div class="col-md-8">
       <button type="button" id="create-new-backup-button" class="btn btn-outline-info add-item-btn" data-toggle="modal" data-target=".add_backup"> Create New Backup </button>
    </div>

    <div class="col-md-4">
      <form class="form ml-auto" action="/preferences/backup/search" method="GET">
            <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                <input class="form-control datetimepicker-input" name="search" type="text" placeholder="Search by Date" aria-label="Search" style="padding-left: 20px; border-radius: 40px;" id="product-search" data-toggle ="datetimepicker" data-target="#datetimepicker4" autocomplete="off">
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
        <th>File Name</th>
        <th>File Size</th>
        <th>Date Created</th>
        <th>File Age</th>
        <th>Actions</th>
    </tr>
    </thead>
        <tbody>
        @foreach($backups as $backup)
            <tr>
                <td class="td-center">{{ $backup['file_name'] }}</td>
                <td class="td-center">{{ $backup['file_size'] }}</td>
                <td class="td-center">{{ $backup['last_modified'] }}</td>
                <td class="td-center">{{ $backup['age'] }}</td>
                <td>
                    <a class="btn btn-primary edit-btn"
                       href="{{ url('backup/download/'.$backup['file_name']) }}"><i class="material-icons md-18">cloud_download</i>
                    </a>
                    <button type="button" id="delete-backup" class="btn btn-xs btn-danger del-btn" data-filename="{{$backup['file_name']}}" data-toggle="modal" data-target=".delete_backup"><i class="material-icons md-18">delete</i>
                   </a>
                </td>
            </tr>
        @endforeach
        </tbody>
</table>

{{$backups->links()}}


<div class="modal fade add_backup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">Create New Backup</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
    <center>  <span> Are you sure you want to create a new backup? </span>  </center>
</div> 

<div class="modal-footer" id="modal-footer-create-backup">
  <button type="button" onclick="createBackup()" class="btn btn-info btn-save-modal">Yes</button>
  <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">No</button>
</div>

</div>
</div>
</div>

<form>
    <div class="modal fade delete_backup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Delete Backup</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
        <center>  <p> Are you sure you want to permanently delete the backup <br> <b><span id="delete-name"></span></b>?</p> </center>
        <span class="backup-name-delete" hidden="hidden"></span>
        </div>

    <div class="modal-footer" id="modal-footer-backup-delete">
      <button type="button" id="destroy-backup" class="btn btn-danger btn-save-modal">Yes</button>
      <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">No</button>
    </div>
    </div>
    </div>
    </div>
</form>


</div>

<script type="text/javascript">

function createBackup()
{
    localStorage.setItem("add","success");
    window.location='{{url('backup/create') }}';
}

$(document).on('click', '#delete-backup', function() {
    $('#delete-name').text($(this).data('filename'));
    $('.backup-name-delete').text($(this).data('filename'));
});

$('#modal-footer-backup-delete').on('click', '#destroy-backup', function(){
    var filename =$('.backup-name-delete').text();
    deleteBackup(filename);
});

function deleteBackup(file_name)
{
   $.ajax({
    type: 'GET',
    url: '/backup/delete/' + file_name,
    data: {
      'file_name': file_name
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
}   

$(document).ready(function(){
if(localStorage.getItem("add"))
{
    swal({
            title: "Success!",
            text: "You have successfully created a new backup!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();
}
else if(localStorage.getItem("delete"))
{
    swal({
            title: "Success!",
            text: "You have successfully deleted the backup!",
            icon: "success",
            button: "Close",
          });
    localStorage.clear();  
}
});

$(document).ready(function() {
  $('#datetimepicker4').datetimepicker({
      format: 'MMMM DD, YYYY'
  });
});


</script>

@endsection
