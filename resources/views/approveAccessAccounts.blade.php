<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Access Requests</title>
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
    <li class="active"><a href="{{route('add_user')}}">Account Access Requests</a></li>
</ul>
<a href="#" data-activates="slide-out"
   class="btn-floating btn-large waves-effect waves-light green darken-4 button-collapse"
   style="margin-top: 20px; margin-left: 20px; z-index: 0; position: fixed;"><i class="material-icons">menu</i></a>

<div class="main-body" style="margin-top: 75px">
    <div class="row">
        <div class="col s8 offset-s2 white z-depth-2 center" style="padding: 25px">
            <h4> Requests for Access </h4>
            <table class="bordered highlight">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th>Requestee</th>
                        <th>Explanation</th>
                    </tr>
                </thead>
                <tbody>
                @if($primary != null)
                    @foreach($primary as $p)
                        <tr>
                            <td>{{ $p->account_name }}</td>
                            <td>{{ $p->user_name }}</td>
                            <td>{{ $p->explanation }}</td>
                            <td class="right">
                                <form action="{{ route('respondRequest') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="p-{{ $p->id }}" name="id">
                                    <button class="waves-effect waves-light btn green darken-3"
                                            type="submit" value="Approve">
                                        <i class="material-icons">check</i>
                                    </button>
                                    <button class="waves-effect waves-light btn red darken-2"
                                            type="submit" value="Deny">
                                        <i class="material-icons">close</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif

                @if($secondary != null)
                    @foreach($secondary as $s)
                        <tr>
                            <td>{{ $s->account_name }}</td>
                            <td>{{ $s->user_name }}</td>
                            <td>{{ $s->explanation }}</td>
                            <td class="right">
                                <form action="{{ route('respondRequest') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="s-{{ $s->id }}" name="id">
                                    <button class="waves-effect waves-light btn green darken-3"
                                            type="submit" value="Approve">
                                        <i class="material-icons">check</i>
                                    </button>
                                    <button class="waves-effect waves-light btn red darken-2"
                                            type="submit" value="Deny">
                                        <i class="material-icons">close</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif

                @if($tertiary != null)
                    @foreach($tertiary as $t)
                        <tr>
                            <td>{{ $t->account_name }}</td>
                            <td>{{ $t->user_name }}</td>
                            <td>{{ $t->explanation }}</td>
                            <td class="right">
                                <form action="{{ route('respondRequest') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="t-{{ $t->id }}" name="id">
                                    <button class="waves-effect waves-light btn green darken-3"
                                            type="submit" value="Approve">
                                        <i class="material-icons">check</i>
                                    </button>
                                    <button class="waves-effect waves-light btn red darken-2"
                                            type="submit" value="Deny">
                                        <i class="material-icons">close</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>