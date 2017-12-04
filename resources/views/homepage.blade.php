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

    {{--Icons--}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{asset('css/homepage.css')}}">

    {{-- Use this Stylesheet on every page with a form --}}
    <link rel="stylesheet" href="{{asset('css/globalform.css')}}">
</head>
<body class="teal lighten-5">
<nav class="navbar-fixed green darken-4">
    <div class="nav-wrapper">
        <a href="{{route('homepage')}}" class="brand-logo" style="padding-left: 20px">
            <div style="height: 64px;" class="valign-wrapper">
                <img src="{{asset('images/Logo.png')}}" height="50px">
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
<div class="valign-wrapper center-align" style="height: calc(100vh - 64px)">
    <div class="row" style="width: 100vw; margin-top: -10vh">
        @if(Auth::guest())
            <form class="white col s4 offset-s4 z-depth-2" action="{{route('login')}}" method="POST"
                  style="padding: 25px;">
                {{csrf_field()}}
                <h4 class="grey-text text-darken-4">Login</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">email</i>
                        <input name="email" id="email" type="email" class="{{ $errors->has('email') ?
                            'validate invalid': 'validate'}}" value="{{ old('email') }}">
                        <label for="email" data-error="{{ $errors->has('email') ?
                            $errors->first('email'): 'Please enter a valid e-mail'}}">E-mail Address</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock</i>
                        <input type="password" name="password" id="password" class="validate">
                        <label for="password">Password</label>
                    </div>
                </div>
                <input class="waves-effect waves-light btn green darken-3 right"
                       type="submit" value="Login">
            </form>
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
            <a href="{{ route('accessedAccountsView') }}">Accessed Accounts</a>
            <a href="{{ route('brfView') }}">Bookstore Requisition Form</a>
            <a href="{{ route('viewMRF') }}"> Material Requisition Form </a>
            <a href="{{ route('transacView') }}"> Transactions </a>
            <a class="green-text text-darken-3 menu-item" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        @elseif(Auth::user()->usertype == 'Budget Admin')
            <a href="{{ route('pettyCashView') }}">Petty Cash</a> <br>
            <a href="{{ route('createBudgetProposal') }}">Create Budget Proposal</a> <br>
            <a href="{{ route('editBudgetProposal') }}">Edit Budget Proposal</a> <br>
            <a href="{{ route('generalJournal') }}"> Journal </a> <br>
        @elseif(Auth::user()->usertype == 'Executive')
            <a href="{{ route('requestsForAccess') }}">Accessed Budgets</a>
            <a href="{{ route('execMRF') }}">Material Requisition Forms</a>
        @endif
    </div>
</div>


</body>
</html>
<script>
    $(document).ready(function(){
        $(".dropdown-button").dropdown();
        $('#email').on("keyup", function(){
            $("#email").next('label').attr('data-error', "Please enter a valid E-mail address");
            Materialize.updateTextFields();
        });
    })
</script>