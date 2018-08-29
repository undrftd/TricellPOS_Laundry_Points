<html>
<head>
	<title>@yield('title')</title>
	<meta name="viewport" content="width=device-width", initial-scale="1.0">
  <link rel="stylesheet" type="text/css" href="@yield('css')"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('imports/css/nav.css') }}"/>
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
  <script src="{{ asset('imports/js/jquery.countdown.js') }}"></script>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a href="/" class="navbar-brand"><img style="height: 20px; margin-right: 20px;" src="{{ asset('imports/img/tricell_nav.png') }}" /></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar6">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse justify-content-stretch" id="navbar6">
        <ul class="navbar-nav">
            <li class="nav-item">
       <a class="nav-link {{ Request::segment(1)=='dashboard' ? 'active' : '' }}" href="/dashboard"> DASHBOARD </a>
     </li>
     <li class="nav-item">
       <a class="nav-link {{ Request::segment(1)=='sales' ? 'active' : '' }}" href="/sales"> BILLING </a>
     </li>
     <li class="nav-item">
       <a class="nav-link {{ Request::segment(1)=='queue' ? 'active' : '' }}" href="/queue">QUEUEING</a>
     </li>
     <li class="nav-item dropdown" id="logsdrop">
          <a class="nav-link dropdown-toggle {{ Request::segment(1)=='logs' ? 'active' : '' }}"  id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            LOGS
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item {{ Request::segment(2)=='sales' ? 'active' : '' }}" href="/logs/sales">Sales</a>
            <a class="dropdown-item {{ Request::segment(2)=='reload' ? 'active' : '' }}" href="/logs/reload">Reload</a>
      </li>
     <li class="nav-item">
       <a class="nav-link {{ Request::segment(1)=='services' ? 'active' : '' }}" href="/services/washers">SERVICES</a>
     </li>
     <li class="nav-item">
       <a class="nav-link {{ Request::segment(1)=='accounts' ? 'active' : '' }}" href="/accounts/members">ACCOUNTS</a>
     </li>
     <li class="nav-item">
       <a class="nav-link {{ Request::segment(1)=='timesheet' ? 'active' : '' }}" href="/timesheet">TIMESHEET</a>
     </li>
        </ul>
        <ul class="navbar-nav ml-auto"> <!--right links-->
             <li class="nav-item dropdown" id="logsdrop">
          <a class="nav-link dropdown-toggle  {{ Request::segment(1)=='account' || Request::segment(1)=='preferences' ? 'active' : '' }}" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            HELLO, {{strtoupper(Auth::user()->firstname)}}
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item {{ Request::segment(1)=='account' ? 'active' : '' }}" href="/account">My Account</a>
            <a class="dropdown-item {{ Request::segment(1)=='preferences' ? 'active' : '' }}" href="/preferences/profile">Preferences</a>
            <a class="dropdown-item" href="/shutdown">Shutdown</a>
            <a class="dropdown-item" href="/logout">Logout</a>

        </li>
        </ul>
    </div>
</nav>

	@yield('content')
	
	<script type="text/javascript">
   $('ul.navbar-nav li.dropdown').hover(function() {
    $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(400);
  }, 
  function() {
    $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(400);
  });
  </script>
</body>
</html>