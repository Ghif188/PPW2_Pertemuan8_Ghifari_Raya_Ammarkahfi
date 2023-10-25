<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dasboard |</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=League+Spartan&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body{
            font-family: 'League Spartan', sans-serif;
            color: white;
        }
        #about{
            height: 80vh !important;
        }
        #footer{
            height: 10vh !important;    
        }
        .img-logo{
            width: 3rem !important;
        }
    </style>
  </head>
  <body class="">
    <nav class="navbar bg-dark border-bottom border-body px-5 py-2 navbar-expand-lg" data-bs-theme="dark" >
        <div class="container-fluid">
            <a class="fs-2 fw-semibold navbar-brand">Ghifari</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto">
                    @guest
                    @else    
                    <a class="nav-link active fs-5" aria-current="page" href="#">About Me</a>
                    <a class="nav-link fs-5" href="#">Skills</a>
                    <a class="nav-link fs-5" href="#">Experiences</a>
                    @endguest
                </div>
                <div class="navbar-nav ms-auto">
                    @guest
                    <a class="btn btn-outline-light me-3 {{ (request()->is('login')) ? 'active' : '' }}" href="{{ route('login') }}" >Login</a>
                    <a class="btn btn-outline-light {{ (request()->is('register')) ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
                    @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item btn btn-outline-light" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>