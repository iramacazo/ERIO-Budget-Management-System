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


    {{--Icons--}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css\sidenav.css')}}">

    <!-- Compiled and minified JavaScript -->
    <script src="{{ asset('js\materialize.min.js') }}"></script>


</head>
<body class="teal lighten-5">
<div class="navbar-fixed">
    <nav class="green darken-4">
        <div class="nav-wrapper">
            <a href="{{route('homepage')}}" class="brand-logo" style="padding-left: 20px">
                <div style="height: 64px;" class="valign-wrapper">
                    <img src="{{asset('images/Logo.png')}}" height="50px">
                </div></a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a class="white-text text-darken-3 menu-item" href="{{ route('logout') }}"
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
</div>

<ul id="slide-out" class="side-nav fixed" style="margin-top: 64px">
    <li><div class="user-view">
            <div class="background">
                <img src="{{asset('images/dlsu.png')}}" style="width: 100%; -webkit-filter: brightness(50%)">
            </div>
            <a href="#!name"><span class="white-text name">{{Auth::user()->name}}</span></a>
            <a href="#!email"><span class="white-text email">{{Auth::user()->email}}</span></a>
        </div></li>
    <li><a href="{{route('add_user')}}">Add a New User</a></li>
    <li><a href="{{route('get-all-users')}}">View All Users</a></li>
</ul>
<a href="#" data-activates="slide-out"
   class="btn-floating btn-large waves-effect waves-light green darken-4 button-collapse"
   style="margin-top: 20px; margin-left: 20px; z-index: 0"><i class="material-icons">menu</i></a>
<div class="valign-wrapper center-align main-body"
     style="height: calc(100vh - 250px)">
    <div class="row" style="width: 100%; margin-top: -10vh">
        <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
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
        $(".button-collapse").sideNav();
        @if(session()->has('newuser'))
            Materialize.toast("{{session('newuser')}} was successfully added!", 4000);
        @endif
    })
</script>