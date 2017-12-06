<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
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

    @yield('stylesheet')
</head>
<body class="teal lighten-5">
<div class="navbar-fixed">
    <nav class="green darken-4">
        <div class="nav-wrapper">
            <a href="{{route('homepage')}}" class="brand-logo" style="padding-left: 20px">
                <div style="height: 64px;" class="valign-wrapper">
                    <img src="{{asset('images/Logo.png')}}" height="50px" style="user-drag: none;
                        user-select: none;
                        -moz-user-select: none;
                        -webkit-user-drag: none;
                        -webkit-user-select: none;
                        -ms-user-select: none;">
                </div></a>
            @if(!Auth::guest())
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
            @endif
        </div>
    </nav>
</div>
<ul id="slide-out" class="side-nav fixed" style="margin-top: 64px">
    <li><div class="user-view">
            <div class="background">
                <img src="{{asset('images/dlsu.png')}}" style="width: 100%; -webkit-filter: brightness(50%)">
            </div>
            <a href="{{route('edit_account')}}"><span class="white-text name">{{Auth::user()->name}}</span></a>
            <a href="{{route('edit_account')}}"><span class="white-text email">Account Details</span></a>
        </div></li>
    @section('sidebar')
    @show
</ul>
<a href="#" data-activates="slide-out"
   class="btn-floating btn-large waves-effect waves-light green darken-4 button-collapse"
   style="margin-top: 20px; margin-left: 20px; z-index: 0; position: fixed;"><i class="material-icons">menu</i></a>
</body>
<div class="main-body" style="margin-top: 75px">
    <div class="row">
        @yield('content')
    </div>
</div>
</html>
<script>
    $(document).ready(function () {
        $(".button-collapse").sideNav();
        @yield('script')
    })
</script>