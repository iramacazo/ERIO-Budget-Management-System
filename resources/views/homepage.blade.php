<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ERIO BMS</title>
    <link rel="icon" href="{{asset('images/dlsu_logo.png')}}">
    {{-- JQuery --}}
    <script src="{{ asset('js\jquery-3.2.1.min.js') }}"></script>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('css\materialize.min.css') }}">

    <!-- Compiled and minified JavaScript -->
    <script src="{{ asset('js\materialize.min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('css/homepage.css')}}">
</head>
<body class="grey lighten-4">
    <div class="valign-wrapper" style="height: 100vh;">
        <div class="row">
            <div class="row section">
                <div class="col s2"></div>
                <img src="{{asset('images/Logo-2.png')}}" height="250px">
            </div>
            <div class="row center-align section">
                @if(Auth::guest())
                    <a class="green-text text-darken-3 menu-item" href="{{route('login')}}">Login</a>
                    <a class="green-text text-darken-3 menu-item" href="{{route('register')}}">Register</a>
                @elseif(Auth::user()->usertype == "System Admin")
                    <a class="green-text text-darken-3 menu-item" href="{{route('add_user')}}">Add New User</a>
                    <a class="green-text text-darken-3 menu-item" href="{{route('get-all-users')}}">List of Users</a>
                    <a class="green-text text-darken-3 menu-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @elseif(Auth::user()->usertype == 'Budget Requestee')
                    <a href="{{ route('pettyCashView') }}">Petty Cash</a>
                @endif
            </div>
        </div>

    </div>
</body>
</html>