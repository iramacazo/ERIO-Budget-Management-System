<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unauthorized Access!</title>
    <link rel="icon" href="{{asset('images/dlsu_logo.png')}}">
    {{-- JQuery --}}
    <script src="{{ asset('js\jquery-3.2.1.min.js') }}"></script>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('css\materialize.min.css') }}">

    <!-- Compiled and minified JavaScript -->
    <script src="{{ asset('js\materialize.min.js') }}"></script>
    {{--Icons--}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body class="grey lighten-4">
    <div class="valign-wrapper" style="height: 100vh;">
        <div class="row">
            <div class="row section center-align">
                <img src="{{asset('images/dlsu_logo.png')}}">
            </div>
            <div class="row section center-align">
                <h1 class="green-text text-darken-3">Error: You are unauthorized to access this page!</h1>
            </div>
            <div class="divider"></div>
            <div class="row section center-align">
                <a class="waves-effect waves-light btn green darken-3" href="{{route('homepage')}}">
                    <i class="material-icons left">home</i>Go home</a>
            </div>
        </div>

    </div>
</body>
</html>