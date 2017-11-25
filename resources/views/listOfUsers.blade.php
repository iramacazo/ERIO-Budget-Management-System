<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of Users</title>
    <link rel="icon" href="{{asset('images/dlsu_logo.png')}}">

    {{-- JQuery --}}
    <script src="{{ asset('js\jquery-3.2.1.min.js') }}"></script>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('css\materialize.min.css') }}">

    <!-- Compiled and minified JavaScript -->
    <script src="{{ asset('js\materialize.min.js') }}"></script>
</head>
<body class="grey lighten-4">
<nav class="navbar-fixed white">
    <div class="nav-wrapper">
        <a href="{{route('homepage')}}" class="brand-logo" style="padding-left: 20px">
            <div style="height: 64px;" class="valign-wrapper">
                <img src="{{asset('images/Logo.png')}}" height="55px">
            </div></a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a class="green-text text-darken-3 menu-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form></li>
        </ul>
    </div>
</nav>
<div class="container section">
    <div class="row">
        <div class="col s2"></div>
        <div class="col s8 white z-depth-2" style="padding: 25px">
            <h2>List of Users</h2>
            <table class="bordered striped highlight">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>E-mail Address</th>
                        <th>User Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->usertype}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
<script>
    $(document).ready(function () {
        @if(session()->has('newuser'))
            Materialize.toast("{{session('newuser')}} was successfully added!", 4000);
        @endif
    })
</script>