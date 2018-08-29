<html>
  <head>
    <title>LOGIN</title>
    <meta name="viewport" content="width=device-width", initial-scale="1.0">
    
    <link rel="stylesheet" type="text/css" href="imports/css/main.css"/>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link rel="stylesheet" href="{{ asset('imports/css/bootstrap.min.css') }}">
  <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous"> -->
  <link href="{{ asset('imports/css/materialicon.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('imports/css/daterangepicker.css') }}" />
  <link href="{{ asset('imports/css/font.css') }}" rel="stylesheet"> 
  
  <script type="text/javascript" src="{{ asset('imports/js/jquery.min.js') }}"></script>
  <script src="{{ asset('imports/js/jquery1-11-1.min.js') }}"></script>
  <script src="{{ asset('imports/js/popper.min.js') }}"></script>
  <script src="{{ asset('imports/js/bootstrap.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('imports/js/moment.min.js') }}"></script>  
  <script type="text/javascript" src="{{ asset('imports/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('imports/css/tempusdominus-bootstrap-4.min.css') }}" />
  <script src="{{ asset('imports/js/sweetalert.min.js') }}"></script>
  <script src="{{ asset('imports/js/jquery-ui.js') }}"></script>
  <script src="{{ asset('imports/js/Chart.bundle.js') }}"></script>
  <script type="text/javascript" src="{{ asset('imports/js/daterangepicker.min.js') }}"></script>
    </head>
    
    <body class="login_bg">
      
      <div class ="container mx-auto"><!---first cont div-->
      
      <p class="text-center logo_hp">TRICELL</p>
      <p class="text-center logo1_hp">POS SYSTEM</p>
      <div class="row mx-auto">
        
        <form action="/verify" method="post" id="submit_form" class="mx-auto">
          @csrf
          
          <div class="input-group mb-2 mr-sm-2"> <!--user-->
          <div class="input-group-prepend">
            <div class="input-group-text" id="log_user" ><i class="material-icons">person</i></div>
          </div>
          <input type="text" class="form-control" id="log_user_form" name="username" placeholder="Username"required autocomplete="off">
          </div><!--user-->
          
          <div class="input-group mb-2 mr-sm-2 log_pass_div "> <!--pass-->
          <div class="input-group-prepend">
            <div class="input-group-text" id="log_pass" ><i class="material-icons">lock</i></div>
          </div>
          <input type="password" class="form-control" name="password" id="log_pass_form" placeholder="Password" required>
          </div><!--pass-->

          
          <center><button class="btn-hover color-8 jquery" type="submit">LOGIN</button></center>
          
        </form>
      </div>
      </div>

<script>
@if (Session::has('message'))

  $(".log_pass_div").after('<div class="alert alert-dark" role="alert">{{ session('message') }}<div>')

  $(".alert").effect("shake");

@endif

</script>

</body>

</html>