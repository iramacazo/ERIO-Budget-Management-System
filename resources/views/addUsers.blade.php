<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User</title>
    <link rel="icon" href="{{asset('images/dlsu_logo.png')}}">
    {{-- JQuery --}}
    <script src="{{ asset('js\jquery-3.2.1.min.js') }}"></script>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('css\materialize.min.css') }}">

    <!-- Compiled and minified JavaScript -->
    <script src="{{ asset('js\materialize.min.js') }}"></script>

    {{--Icons--}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css\sidenav.css')}}">

    {{-- Use this Stylesheet on every page with a form --}}
    <link rel="stylesheet" href="{{asset('css/globalform.css')}}">
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
                <li><a class="white-text menu-item" href="{{ route('logout') }}"
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
    <li class="active"><a href="{{route('add_user')}}">Add a New User</a></li>
    <li><a href="{{route('get-all-users')}}">View All Users</a></li>
</ul>
<a href="#" data-activates="slide-out"
   class="btn-floating btn-large waves-effect waves-light green darken-4 button-collapse"
   style="margin-top: 20px; margin-left: 20px; z-index: 0; position: fixed;"><i class="material-icons">menu</i></a>
    <div class="main-body" style="margin-top: 75px">
        <div class="row">
            <div class="col s8 offset-s2 white z-depth-2" style="padding: 25px">
                <div class="row">
                    <form class="col s12" method="POST" action="{{route('create_user')}}">
                        {{csrf_field()}}
                        <h3 class="grey-text text-darken-4">Add New User</h3>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">account_circle</i>
                                <input name="name" id="name" type="text" class="validate">
                                <label for="name">Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">email</i>
                                <input name="email" id="email" type="email" class="validate">
                                <label for="email">E-mail Address</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">lock_outline</i>
                                <input name="password" id="password" type="password" class="validate">
                                <label for="password">Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">lock</i>
                                <input name="password_confirmation" id="password-confirm" type="password" class="validate">
                                <label for="password-confirm">Confirm Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">accessibility</i>
                                <select name="usertype" id="user_type">
                                    <option value="" disabled selected>Choose your option</option>
                                    <option value="System Admin">System Admin</option>
                                    <option value="Budget Requestee">Budget Requestee</option>
                                    <option value="Budget Admin">Budget Admin</option>
                                    <option value="Executive">Executive</option>
                                </select>
                                <label for="user_type">User Type</label>
                            </div>
                        </div>
                        <input class="waves-effect waves-light btn green darken-3 right"
                               type="submit" value="Create User">
                    </form>
                </div>
            </div>
            <div class="col s3"></div>
        </div>
        @if($errors->any())
            @foreach($errors->all() as $error)
                {{$error}}
            @endforeach
        @endif
    </div>
</body>
</html>
<script>
    $(document).ready(function() {
        $(".button-collapse").sideNav();
        $('select').material_select();
        $(".dropdown-content>li>a").css("color", "#2e7d32 !important");
    });
</script>